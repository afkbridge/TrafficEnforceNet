<?php

namespace App\Http\Controllers\Enforcer;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Violation;
use App\Models\ViolationImage;
use App\Models\ViolationOtherType;
use App\Models\ViolationType;
use App\Services\AuditLogger;
use App\Services\OcrSpaceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ViolationController extends Controller
{
    // =======================================================
    // VIOLATIONS LIST
    // =======================================================

    public function index(Request $request)
    {
        $query = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'violationTypes',
            'violationOtherTypes',
        ])
            ->where('user_id', Auth::id());

        if ($request->filter === 'today') {
            $query->whereDate(
                'violation_date',
                now()
                    ->setTimezone('Asia/Manila')
                    ->toDateString()
            );
        }

        if ($request->filled('date')) {
            $query->whereDate(
                'violation_date',
                $request->date
            );
        }

        $violations = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'enforcer.violations',
            compact('violations')
        );
    }

    // =======================================================
    // CREATE / ISSUE TICKET PAGE
    // =======================================================

    public function create()
    {
        $violationTypes = ViolationType::orderBy('name')->get();

        return view(
            'enforcer.issue-ticket',
            compact('violationTypes')
        );
    }

    // =======================================================
    // DRIVER'S LICENSE OCR
    // =======================================================

    public function ocrDriverLicense(
        Request $request,
        OcrSpaceService $ocrSpaceService
    ) {
        $request->validate([
            'driver_license' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120',
            ],
        ]);

        try {
            $text = $ocrSpaceService->extractText(
                $request->file('driver_license')
            );

            if (trim($text) === '') {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'No text could be detected from the driver\'s license. Please upload a clearer image.',
                ], 422);
            }

            $data = $this->parseDriverLicenseText($text);

            return response()->json([
                'success' => true,
                'message' =>
                    'Driver\'s license information extracted successfully.',
                'data' => $data,
                'raw_text' => $text,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // =======================================================
    // PARSE DRIVER'S LICENSE OCR TEXT
    // =======================================================

    private function parseDriverLicenseText(string $text): array
    {
        $text = preg_replace(
            '/\r\n|\r/',
            "\n",
            $text
        );

        $lines = array_values(
            array_filter(
                array_map(
                    'trim',
                    explode("\n", $text)
                ),
                fn ($line) => $line !== ''
            )
        );

        $result = [
            'first_name' => '',
            'middle_name' => '',
            'last_name' => '',
            'license_number' => '',
            'address' => '',
            'birth_date' => '',
        ];

        $fullText = implode("\n", $lines);

        // ===================================================
        // DRIVER NAME
        // ===================================================

        foreach ($lines as $index => $line) {
            if (
                stripos($line, 'Last Name') !== false &&
                stripos($line, 'First Name') !== false
            ) {
                $nameLine = $lines[$index + 1] ?? '';

                if (strpos($nameLine, ',') !== false) {
                    $nameParts = array_map(
                        'trim',
                        explode(',', $nameLine, 2)
                    );

                    $result['last_name'] =
                        $nameParts[0] ?? '';

                    if (!empty($nameParts[1])) {
                        $firstMiddle = preg_split(
                            '/\s+/',
                            trim($nameParts[1])
                        );

                        $result['first_name'] =
                            $firstMiddle[0] ?? '';

                        if (count($firstMiddle) > 1) {
                            $result['middle_name'] =
                                implode(
                                    ' ',
                                    array_slice(
                                        $firstMiddle,
                                        1
                                    )
                                );
                        }
                    }
                }

                break;
            }
        }

        // ===================================================
        // LICENSE NUMBER
        // ===================================================

        if (
            preg_match(
                '/\b([A-Z]\d{2}-\d{2}-\d{6})\b/i',
                $fullText,
                $match
            )
        ) {
            $result['license_number'] =
                strtoupper(
                    trim($match[1])
                );
        }

        // ===================================================
        // BIRTH DATE
        // ===================================================

        if (
            preg_match(
                '/Date\s+of\s+Birth\s*\n\s*(\d{4}\/\d{2}\/\d{2})/i',
                $fullText,
                $match
            )
        ) {
            $birthDate = trim($match[1]);

            $date = \DateTime::createFromFormat(
                'Y/m/d',
                $birthDate
            );

            if ($date) {
                $result['birth_date'] =
                    $date->format('Y-m-d');
            }
        }

        // ===================================================
        // ADDRESS
        // ===================================================

        foreach ($lines as $index => $line) {
            if (
                stripos($line, 'STREET') !== false ||
                preg_match('/\bST\.?\b/i', $line)
            ) {
                $addressParts = [];

                if (
                    isset($lines[$index - 1]) &&
                    preg_match(
                        '/^\d+$/',
                        $lines[$index - 1]
                    )
                ) {
                    $addressParts[] =
                        $lines[$index - 1];
                }

                $addressParts[] = $line;

                if (isset($lines[$index + 1])) {
                    $nextLine = $lines[$index + 1];

                    if (
                        !preg_match(
                            '/^(License|Agency Code|Blood Type|Eyes Color|DL Codes|Conditions)/i',
                            $nextLine
                        )
                    ) {
                        $addressParts[] =
                            $nextLine;
                    }
                }

                $result['address'] =
                    trim(
                        preg_replace(
                            '/\s+/',
                            ' ',
                            implode(
                                ', ',
                                $addressParts
                            )
                        )
                    );

                break;
            }
        }

        // ===================================================
        // FALLBACK ADDRESS
        // ===================================================

        if ($result['address'] === '') {
            foreach ($lines as $index => $line) {
                if (
                    preg_match(
                        '/\b\d{4}\b/',
                        $line
                    )
                ) {
                    $previousLine =
                        $lines[$index - 1] ?? '';

                    if (
                        !preg_match(
                            '/^(Date of Birth|Weight|Height|License|Agency Code|Blood Type|Eyes Color|DL Codes|Conditions)/i',
                            $previousLine
                        )
                    ) {
                        $result['address'] =
                            trim(
                                preg_replace(
                                    '/\s+/',
                                    ' ',
                                    $previousLine .
                                    ', ' .
                                    $line
                                )
                            );

                        break;
                    }
                }
            }
        }

        return $result;
    }

    // =======================================================
    // CITATION TICKET OCR
    // =======================================================

    public function ocrCitationTicket(
        Request $request,
        OcrSpaceService $ocrSpaceService
    ) {
        $request->validate([
            'ticket_image' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120',
            ],
        ]);

        try {
            $text = $ocrSpaceService->extractText(
                $request->file('ticket_image')
            );

            if (trim($text) === '') {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'No text could be detected from the citation ticket. Please upload a clearer image.',
                ], 422);
            }

            $data =
                $this->parseCitationTicketText($text);

            return response()->json([
                'success' => true,
                'message' =>
                    'Citation ticket information extracted successfully.',
                'data' => $data,
                'raw_text' => $text,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // =======================================================
    // PARSE CITATION TICKET OCR TEXT
    // =======================================================

    private function parseCitationTicketText(
        string $text
    ): array {
        $text = str_replace(
            ["\r\n", "\r"],
            "\n",
            $text
        );

        $lines = array_values(
            array_filter(
                array_map(
                    function ($line) {
                        return trim(
                            preg_replace(
                                '/\s+/',
                                ' ',
                                $line
                            )
                        );
                    },
                    explode("\n", $text)
                ),
                function ($line) {
                    return $line !== '';
                }
            )
        );

        $fullText = implode(
            "\n",
            $lines
        );

        $result = [
            'ticket_number' => '',
            'first_name' => '',
            'middle_name' => '',
            'last_name' => '',
            'license_number' => '',
            'address' => '',
            'birth_date' => '',
            'plate_number' => '',
            'vehicle_type' => '',
            'region_number' => '',
            'owner_name' => '',
            'location' => '',
            'violations' => [],
        ];

        // ===================================================
        // OCR TEXT VALIDATION HELPERS
        // ===================================================

        $isReadableName = function (?string $value): bool {
            if (!$value) {
                return false;
            }

            $value = trim($value);

            if (strlen($value) < 2) {
                return false;
            }

            if (
                preg_match(
                    "/[^a-zA-ZÀ-ÿ\s'\-\.]/u",
                    $value
                )
            ) {
                return false;
            }

            $withoutSpaces =
                str_replace(
                    ' ',
                    '',
                    $value
                );

            if (
                preg_match(
                    '/^(.)\1{3,}$/iu',
                    $withoutSpaces
                )
            ) {
                return false;
            }

            return true;
        };

        $cleanOcrValue = function (
            ?string $value,
            callable $validator
        ): string {
            if (!$value) {
                return '';
            }

            $value =
                trim(
                    preg_replace(
                        '/\s+/',
                        ' ',
                        $value
                    )
                );

            return $validator($value)
                ? $value
                : '';
        };

        // ===================================================
        // TICKET NUMBER
        // ===================================================

        if (
            preg_match(
                '/\bN\s*[°º]\s*([0-9]{3,})\b/u',
                $text,
                $match
            )
        ) {
            $result['ticket_number'] =
                trim($match[1]);
        } elseif (
            preg_match(
                '/\bNo\.?\s*([0-9]{3,})\b/i',
                $text,
                $match
            )
        ) {
            $result['ticket_number'] =
                trim($match[1]);
        }

        // ===================================================
        // DRIVER NAME
        // ===================================================

        $lastNameIndex = null;
        $firstNameIndex = null;
        $middleNameIndex = null;

        foreach ($lines as $index => $line) {
            if (
                strtoupper($line) === 'LAST' &&
                isset($lines[$index + 1]) &&
                strtoupper($lines[$index + 1]) === 'NAME'
            ) {
                $lastNameIndex = $index;
                break;
            }
        }

        foreach ($lines as $index => $line) {
            if (
                strtoupper($line) === 'FIRST' &&
                isset($lines[$index + 1]) &&
                strtoupper($lines[$index + 1]) === 'NAME'
            ) {
                $firstNameIndex = $index;
                break;
            }
        }

        foreach ($lines as $index => $line) {
            if (
                strtoupper($line) === 'MIDDLE' &&
                isset($lines[$index + 1]) &&
                strtoupper($lines[$index + 1]) === 'NAME'
            ) {
                $middleNameIndex = $index;
                break;
            }
        }

        // ===================================================
        // NAME VALUES
        // ===================================================

        if (
            $lastNameIndex !== null &&
            $firstNameIndex !== null &&
            $middleNameIndex !== null
        ) {
            $nameValueStart =
                $middleNameIndex + 2;

            $lastName =
                $lines[$nameValueStart] ?? '';

            $firstName =
                $lines[$nameValueStart + 1] ?? '';

            $middleName =
                $lines[$nameValueStart + 2] ?? '';

            $result['last_name'] =
                $cleanOcrValue(
                    $lastName,
                    $isReadableName
                );

            $result['first_name'] =
                $cleanOcrValue(
                    $firstName,
                    $isReadableName
                );

            $result['middle_name'] =
                $cleanOcrValue(
                    $middleName,
                    $isReadableName
                );
        }

        // ===================================================
        // FALLBACK NAME PARSING
        // ===================================================

        if ($result['last_name'] === '') {
            if (
                preg_match(
                    '/Last\s+Name\s*:?\s*(.+)/i',
                    $fullText,
                    $match
                )
            ) {
                $result['last_name'] =
                    $cleanOcrValue(
                        $match[1],
                        $isReadableName
                    );
            }
        }

        if ($result['first_name'] === '') {
            if (
                preg_match(
                    '/First\s+Name\s*:?\s*(.+)/i',
                    $fullText,
                    $match
                )
            ) {
                $result['first_name'] =
                    $cleanOcrValue(
                        $match[1],
                        $isReadableName
                    );
            }
        }

        if ($result['middle_name'] === '') {
            if (
                preg_match(
                    '/Middle\s+Name\s*:?\s*(.+)/i',
                    $fullText,
                    $match
                )
            ) {
                $result['middle_name'] =
                    $cleanOcrValue(
                        $match[1],
                        $isReadableName
                    );
            }
        }

        // ===================================================
        // LICENSE NUMBER
        // ===================================================

        if (
            preg_match(
                '/\b([A-Z]-\d{2}-\d{8})\b/i',
                $fullText,
                $match
            )
        ) {
            $result['license_number'] =
                strtoupper(
                    trim($match[1])
                );
        } elseif (
            preg_match(
                '/License\s+Number\s*:?\s*([A-Z0-9-]+)/i',
                $fullText,
                $match
            )
        ) {
            $candidate =
                strtoupper(
                    trim($match[1])
                );

            if (
                preg_match(
                    '/^[A-Z0-9]{1,4}(?:-[A-Z0-9]{1,10}){1,3}$/',
                    $candidate
                )
            ) {
                $result['license_number'] =
                    $candidate;
            }
        }

        // ===================================================
        // ADDRESS
        // ===================================================

        $addressStart = null;

        foreach ($lines as $index => $line) {
            if (
                preg_match(
                    '/^ADDRESS\b/i',
                    $line
                )
            ) {
                $addressStart =
                    $index + 1;

                break;
            }
        }

        if ($addressStart !== null) {
            $addressParts = [];

            for (
                $i = $addressStart;
                $i < count($lines);
                $i++
            ) {
                $line =
                    $lines[$i];

                if (
                    preg_match(
                        '/^LICENSE$/i',
                        $line
                    )
                ) {
                    break;
                }

                if (
                    preg_match(
                        '/^NUMBER$/i',
                        $line
                    )
                ) {
                    continue;
                }

                if (
                    preg_match(
                        '/^(Birth|Date|License Confiscated|Violation Details|Time|Place|Vehicle|Plate|Reg|Registration|Owner|Violations|Notice|Apprehending)/i',
                        $line
                    )
                ) {
                    break;
                }

                if (
                    preg_match(
                        '/^\(.*\)$/',
                        $line
                    )
                ) {
                    continue;
                }

                if (
                    preg_match(
                        '/^[^a-zA-Z0-9]+$/',
                        $line
                    )
                ) {
                    continue;
                }

                $addressParts[] =
                    $line;

                if (
                    count($addressParts) >= 3
                ) {
                    break;
                }
            }

            if (!empty($addressParts)) {
                $address =
                    trim(
                        preg_replace(
                            '/\s+/',
                            ' ',
                            implode(
                                ' ',
                                $addressParts
                            )
                        )
                    );

                if (
                    preg_match(
                        '/[a-zA-Z]{2,}/',
                        $address
                    )
                ) {
                    $result['address'] =
                        $address;
                }
            }
        }

        // ===================================================
        // FALLBACK ADDRESS
        // ===================================================

        if ($result['address'] === '') {
            foreach ($lines as $index => $line) {
                if (
                    preg_match(
                        '/^Address\s*:\s*(.+)$/i',
                        $line,
                        $match
                    )
                ) {
                    $address =
                        trim($match[1]);

                    if (
                        preg_match(
                            '/[a-zA-Z]{2,}/',
                            $address
                        )
                    ) {
                        $result['address'] =
                            $address;
                    }

                    break;
                }
            }
        }

        // ===================================================
        // BIRTH DATE
        // ===================================================

        if (
            preg_match(
                '/BIRTH\s+DATE\s+(\d{1,2}\/\d{1,2}\/\d{4})/i',
                $fullText,
                $match
            )
        ) {
            $birthDate =
                trim($match[1]);

            $date =
                \DateTime::createFromFormat(
                    'm/d/Y',
                    $birthDate
                );

            if ($date) {
                $result['birth_date'] =
                    $date->format('Y-m-d');
            }
        }

        if (
            $result['birth_date'] === '' &&
            preg_match(
                '/\b(\d{1,2}\/\d{1,2}\/\d{4})\b/',
                $fullText,
                $match
            )
        ) {
            $birthDate =
                trim($match[1]);

            $date =
                \DateTime::createFromFormat(
                    'm/d/Y',
                    $birthDate
                );

            if ($date) {
                $result['birth_date'] =
                    $date->format('Y-m-d');
            }
        }

        // ===================================================
        // VEHICLE TYPE
        // ===================================================

        $vehicleTypes = [
            'SEDAN',
            'SUV',
            'MPV',
            'VAN',
            'PICKUP',
            'PICK-UP',
            'TRUCK',
            'MOTORCYCLE',
            'MOTORCYCLE WITH SIDECAR',
            'TRICYCLE',
            'BUS',
            'JEEPNEY',
            'UTILITY VEHICLE',
        ];

        foreach ($vehicleTypes as $vehicleType) {
            if (
                preg_match(
                    '/\b' .
                    preg_quote(
                        $vehicleType,
                        '/'
                    ) .
                    '\b/i',
                    $fullText
                )
            ) {
                $result['vehicle_type'] =
                    $vehicleType;

                break;
            }
        }

        // ===================================================
        // PLATE NUMBER
        // ===================================================

        if (
            preg_match(
                '/\b([A-Z]{2,4}\s+\d{3,4})\b/i',
                $fullText,
                $match
            )
        ) {
            $result['plate_number'] =
                strtoupper(
                    trim($match[1])
                );
        }

        // ===================================================
        // REGION NUMBER
        // ===================================================

        if (
            preg_match(
                '/REGION\s*(?:NUMBER|NO\.?)?\s*:?\s*([A-Z0-9-]+)/i',
                $fullText,
                $match
            )
        ) {
            $candidate =
                strtoupper(
                    trim($match[1])
                );

            if (
                preg_match(
                    '/^[A-Z0-9-]{1,20}$/',
                    $candidate
                )
            ) {
                $result['region_number'] =
                    $candidate;
            }
        }

        // ===================================================
        // VEHICLE OWNER
        // ===================================================

        $vehicleOwnerIndex = null;

        foreach ($lines as $index => $line) {
            if (
                strtoupper($line) === 'VEHICLE' &&
                isset($lines[$index + 1]) &&
                strtoupper($lines[$index + 1]) === 'OWNER'
            ) {
                $vehicleOwnerIndex =
                    $index;

                break;
            }
        }

        if ($vehicleOwnerIndex !== null) {
            $ownerValueIndex =
                $vehicleOwnerIndex + 2;

            if (
                isset($lines[$ownerValueIndex])
            ) {
                $ownerName =
                    trim(
                        $lines[$ownerValueIndex]
                    );

                if (
                    $ownerName !== '' &&
                    !preg_match(
                        '/^(You|Notice|Apprehending|Officer|Driver)/i',
                        $ownerName
                    ) &&
                    $isReadableName($ownerName)
                ) {
                    $result['owner_name'] =
                        $ownerName;
                }
            }
        }

        // ===================================================
        // FALLBACK VEHICLE OWNER
        // ===================================================

        if (
            $result['owner_name'] === '' &&
            preg_match(
                '/Vehicle\s+Owner\s*:?\s*(.+)/i',
                $fullText,
                $match
            )
        ) {
            $result['owner_name'] =
                $cleanOcrValue(
                    $match[1],
                    $isReadableName
                );
        }

        // ===================================================
        // PLACE OF VIOLATION
        // ===================================================

        $placeIndex = null;

        foreach ($lines as $index => $line) {
            if (
                stripos(
                    $line,
                    'Place of Violation'
                ) !== false
            ) {
                $placeIndex =
                    $index;

                break;
            }
        }

        if ($placeIndex !== null) {
            for (
                $i = $placeIndex + 1;
                $i < count($lines);
                $i++
            ) {
                $line =
                    trim($lines[$i]);

                if (
                    strtoupper($line) === 'STREET'
                ) {
                    continue;
                }

                if (
                    strtoupper($line) === 'CITY'
                ) {
                    break;
                }

                if (
                    $line !== '' &&
                    preg_match(
                        '/[a-zA-Z]{2,}/',
                        $line
                    )
                ) {
                    $result['location'] =
                        $line;

                    break;
                }
            }
        }

        // ===================================================
        // FALLBACK PLACE OF VIOLATION
        // ===================================================

        if (
            $result['location'] === '' &&
            preg_match(
                '/Place\s+of\s+Violation\s*:?\s*(.+)/i',
                $fullText,
                $match
            )
        ) {
            $location =
                trim($match[1]);

            if (
                preg_match(
                    '/[a-zA-Z]{2,}/',
                    $location
                )
            ) {
                $result['location'] =
                    $location;
            }
        }

        // ===================================================
        // CHECKED VIOLATIONS
        // ===================================================

        $knownViolations = [
            'Truck Ban',
            'Illegal Parking',
            'Obstruction',
            'Stalled Vehicle',
            'Disregarding Traffic lights/signs/Officer',
            'Reckless Driving',
            'Driving while under the influence',
            'Driving without or with invalid license',
            'Unregistered (Mayor\'s Permit) Vehicle',
            'Counterflow',
            'Overloading',
            'Involvement in accident',
            'Loading/Unloading on prohibited zones',
            'Coding',
            'Colorum',
        ];

        foreach ($lines as $line) {
            if (
                !preg_match(
                    '/^(?:\*|X|✓|✔|☑)\s*/u',
                    $line
                )
            ) {
                continue;
            }

            $cleanLine =
                preg_replace(
                    '/^(?:\*|X|✓|✔|☑)\s*/u',
                    '',
                    $line
                );

            $cleanLine =
                trim($cleanLine);

            foreach (
                $knownViolations
                as $knownViolation
            ) {
                if (
                    stripos(
                        $cleanLine,
                        $knownViolation
                    ) !== false
                ) {
                    $result['violations'][] =
                        $knownViolation;

                    break;
                }
            }
        }

        // ===================================================
        // REMOVE DUPLICATE VIOLATIONS
        // ===================================================

        $result['violations'] =
            array_values(
                array_unique(
                    $result['violations']
                )
            );

        // ===================================================
        // RETURN RESULT
        // ===================================================

        return $result;
    }

    // =======================================================
    // STORE VIOLATION
    // =======================================================

    public function store(Request $request)
    {
        // ===================================================
        // BACKEND VALIDATION
        // ===================================================

        // Names: letters (incl. accents), spaces, . ' -
        $namePattern = '/^\p{L}[\p{L}\s.\'\-]*$/u';

        // "Others" free-text violation: letters, numbers, spaces, , . - / ( ) ' &
        $otherPattern = '/^[\p{L}\p{N}\s,.\-\/()\'&]+$/u';

        $request->validate([
            // ===================================================
            // TICKET NUMBER
            // ===================================================

            'ticket_number' => [
                'required',
                'string',
                'digits:6',
                'unique:violations,ticket_number',
            ],

            // ===================================================
            // DRIVER INFORMATION
            // ===================================================

            'first_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:' . $namePattern,
            ],

            'middle_name' => [
                'nullable',
                'string',
                'max:50',
                'regex:' . $namePattern,
            ],

            'last_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:' . $namePattern,
            ],

            // ---------------------------------------------------
            // NO LICENSE SUPPORT
            // ---------------------------------------------------
            //
            // If has_no_license = 1:
            //     license_number may be NULL.
            //
            // Otherwise:
            //     license_number is required.
            // ---------------------------------------------------

            'has_no_license' => [
                'nullable',
                'boolean',
            ],

            'license_number' => [
                'nullable',
                'string',
                'min:5',
                'max:20',
                'regex:/^[A-Za-z0-9\-]+$/',
                'required_unless:has_no_license,1',
            ],

            'address' => [
                'required',
                'string',
                'min:5',
                'max:255',
                'regex:/^[\p{L}\p{N}\s,.\-#\/\'()]+$/u',
            ],

            'birth_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
                'after:1899-12-31',
            ],

            // ===================================================
            // VEHICLE INFORMATION
            // ===================================================

            // ---------------------------------------------------
            // NO PLATE SUPPORT
            // ---------------------------------------------------
            //
            // If has_no_plate = 1:
            //     plate_number may be NULL.
            //
            // Otherwise:
            //     plate_number is required.
            // ---------------------------------------------------

            'has_no_plate' => [
                'nullable',
                'boolean',
            ],

            'plate_number' => [
                'nullable',
                'string',
                'min:3',
                'max:10',
                'regex:/^[A-Za-z0-9 \-]+$/',
                'required_unless:has_no_plate,1',
            ],

            'vehicle_type' => [
                'nullable',
                Rule::in([
                    'MC',
                    'MTC Private',
                    'MTC For Hire',
                    'PUJ',
                    'Private Vehicle',
                    'Others',
                ]),
            ],

            'other_vehicle_type' => [
                'nullable',
                'required_if:vehicle_type,Others',
                'string',
                'min:2',
                'max:50',
                'regex:/^[\p{L}\p{N}\s.\-\/]+$/u',
            ],

            'region_number' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[A-Za-z0-9\-]+$/',
            ],

            'owner_name' => [
                'nullable',
                'string',
                'min:2',
                'max:100',
                'regex:/^\p{L}[\p{L}\s.,\'&\-]*$/u',
            ],

            // ===================================================
            // VIOLATIONS
            // ===================================================

            'violation_type_id' => [
                'required',
            ],

            'other_violation' => [
                'nullable',
                'required_if:violation_type_id,other',
                'string',
                'min:3',
                'max:150',
                'regex:' . $otherPattern,
            ],

            'additional_violation_type_ids' => [
                'nullable',
                'array',
                'max:10',
            ],

            'additional_violation_type_ids.*' => [
                'required',
            ],

            'additional_other_violation_names' => [
                'nullable',
                'array',
                'max:10',
            ],

            'additional_other_violation_names.*' => [
                'nullable',
                'string',
                'min:3',
                'max:150',
                'regex:' . $otherPattern,
            ],

            // ===================================================
            // LOCATION
            // ===================================================

            'location' => [
                'nullable',
                'string',
                'max:500',
            ],

            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
            ],

            // ===================================================
            // REMARKS
            // ===================================================

            'remarks' => [
                'nullable',
                'string',
                'max:500',
            ],

            // ===================================================
            // TICKET IMAGE
            // ===================================================

            'ticket_image' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120',
            ],

            // ===================================================
            // EVIDENCE IMAGES
            // ===================================================

            'evidence_images' => [
                'nullable',
                'array',
                'max:10',
            ],

            'evidence_images.*' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120',
            ],
        ], [
            'first_name.regex' => 'First name may only contain letters, spaces, periods, apostrophes and hyphens.',
            'middle_name.regex' => 'Middle name may only contain letters, spaces, periods, apostrophes and hyphens.',
            'last_name.regex' => 'Last name may only contain letters, spaces, periods, apostrophes and hyphens.',
            'owner_name.regex' => 'Vehicle owner may only contain letters, spaces and . , \' & -',
            'license_number.regex' => 'License number may only contain letters, numbers and hyphens.',
            'address.regex' => 'Address contains invalid characters.',
            'plate_number.regex' => 'Plate number may only contain letters, numbers, spaces and hyphens.',
            'region_number.regex' => 'Region number may only contain letters, numbers and hyphens.',
            'other_vehicle_type.required_if' => 'Please specify the vehicle type.',
            'other_vehicle_type.regex' => 'Vehicle type contains invalid characters.',
            'other_violation.required_if' => 'Please specify the other violation.',
            'other_violation.regex' => 'The violation description contains invalid characters.',
            'additional_other_violation_names.*.regex' => 'An additional violation description contains invalid characters.',
            'birth_date.before_or_equal' => 'Birth date cannot be in the future.',
            'birth_date.after' => 'Please enter a valid birth date.',
        ]);

        // =======================================================
        // NORMALIZE LICENSE / PLATE STATUS
        // =======================================================

        $hasNoLicense =
            $request->boolean('has_no_license');

        $hasNoPlate =
            $request->boolean('has_no_plate');

        // -------------------------------------------------------
        // IMPORTANT:
        //
        // Do NOT store fake values such as:
        //
        // "NOLICENSE"
        // "NOPLATE"
        //
        // NULL is used instead.
        // -------------------------------------------------------

        $licenseNumber = $hasNoLicense
            ? null
            : strtoupper(
                trim(
                    (string) $request->input(
                        'license_number'
                    )
                )
            );

        $plateNumber = $hasNoPlate
            ? null
            : strtoupper(
                trim(
                    (string) $request->input(
                        'plate_number'
                    )
                )
            );

        // -------------------------------------------------------
        // VEHICLE TYPE
        //
        // If "Others" was selected, store the text the enforcer
        // typed instead of the literal word "Others".
        // -------------------------------------------------------

        $vehicleTypeValue = $request->vehicle_type === 'Others'
            ? trim((string) $request->other_vehicle_type)
            : $request->vehicle_type;

        // =======================================================
        // PRIMARY VIOLATION
        // =======================================================

        /*
         * OFFICIAL VIOLATION:
         *   violation_type_id = official ID
         *
         * PRIMARY "OTHERS":
         *   violation_type_id = NULL
         *   other_violation = enforcer's custom text
         *
         * IMPORTANT:
         * The custom text is NEVER inserted into
         * violation_types.
         */

        $primaryViolationTypeId = null;
        $primaryOtherViolation = null;

        $primaryViolationValue = trim(
            (string) $request->input(
                'violation_type_id'
            )
        );

        if (
            strtolower($primaryViolationValue) === 'other'
        ) {
            $primaryOtherViolation = trim(
                (string) $request->input(
                    'other_violation'
                )
            );

            if ($primaryOtherViolation === '') {
                return back()
                    ->withErrors([
                        'other_violation' =>
                            'Please specify the other violation.',
                    ])
                    ->withInput();
            }
        } else {
            $primaryViolationTypeId =
                (int) $primaryViolationValue;

            if (
                $primaryViolationTypeId <= 0 ||
                !ViolationType::where(
                    'id',
                    $primaryViolationTypeId
                )->exists()
            ) {
                return back()
                    ->withErrors([
                        'violation_type_id' =>
                            'The selected violation type is invalid.',
                    ])
                    ->withInput();
            }
        }

        // =======================================================
        // COLLECT OFFICIAL VIOLATION TYPE IDS
        // =======================================================

        $violationTypeIds = [];

        if ($primaryViolationTypeId !== null) {
            $violationTypeIds[] =
                $primaryViolationTypeId;
        }

        // =======================================================
        // ADDITIONAL VIOLATIONS
        // =======================================================

        $additionalViolationIds =
            array_values(
                (array) $request->input(
                    'additional_violation_type_ids',
                    []
                )
            );

        $additionalOtherNames =
            array_values(
                (array) $request->input(
                    'additional_other_violation_names',
                    []
                )
            );

        $additionalOtherViolations = [];

        /*
         * The "Others" text inputs only exist for rows
         * where Others is selected. Therefore their indexes
         * may not match the violation type indexes.
         */

        $otherNameCursor = 0;

        foreach (
            $additionalViolationIds
            as $index => $additionalTypeId
        ) {
            if (
                $additionalTypeId === null ||
                $additionalTypeId === ''
            ) {
                continue;
            }

            $normalizedTypeId =
                strtolower(
                    trim(
                        (string) $additionalTypeId
                    )
                );

            // ===================================================
            // ADDITIONAL "OTHERS"
            // ===================================================

            if ($normalizedTypeId === 'other') {
                $otherName =
                    trim(
                        (string) (
                            $additionalOtherNames[
                                $otherNameCursor
                            ] ?? ''
                        )
                    );

                $otherNameCursor++;

                if ($otherName === '') {
                    return back()
                        ->withErrors([
                            'additional_violation_type_ids' =>
                                'Please specify every additional "Other" violation.',
                        ])
                        ->withInput();
                }

                /*
                 * This does NOT create a ViolationType.
                 *
                 * It is saved separately in
                 * violation_other_types.
                 */

                $additionalOtherViolations[] =
                    $otherName;

                continue;
            }

            // ===================================================
            // ADDITIONAL OFFICIAL VIOLATION
            // ===================================================

            $additionalTypeId =
                (int) $additionalTypeId;

            if ($additionalTypeId <= 0) {
                return back()
                    ->withErrors([
                        'additional_violation_type_ids' =>
                            'An invalid additional violation type was selected.',
                    ])
                    ->withInput();
            }

            if (
                !ViolationType::where(
                    'id',
                    $additionalTypeId
                )->exists()
            ) {
                return back()
                    ->withErrors([
                        'additional_violation_type_ids' =>
                            'One of the selected violation types is invalid.',
                    ])
                    ->withInput();
            }

            // Prevent duplicate official violations.

            if (
                !in_array(
                    $additionalTypeId,
                    $violationTypeIds,
                    true
                )
            ) {
                $violationTypeIds[] =
                    $additionalTypeId;
            }
        }

        // =======================================================
        // SAVE TICKET IMAGE
        // =======================================================

        $ticketImagePath = null;

        if ($request->hasFile('ticket_image')) {
            $ticketImagePath =
                $request
                    ->file('ticket_image')
                    ->store(
                        'violations/tickets',
                        'public'
                    );
        }

        // =======================================================
        // DRIVER
        // =======================================================

        /*
         * IMPORTANT:
         *
         * We CANNOT use:
         *
         * Driver::firstOrCreate([
         *     'license_number' => null
         * ]);
         *
         * because multiple drivers with no license would all
         * have NULL license_number.
         *
         * Instead:
         *
         * 1. If the driver has a real license number:
         *    use license_number as the identity.
         *
         * 2. If the driver has NO license:
         *    create a separate driver record because
         *    NULL is not a reliable unique identity.
         */

        if ($hasNoLicense) {
            $driver = Driver::create([
                'license_number' => null,
                'has_no_license' => true,

                'first_name' => trim(
                    (string) $request->input(
                        'first_name'
                    )
                ),

                'middle_name' => $request->filled(
                    'middle_name'
                )
                    ? trim(
                        (string) $request->input(
                            'middle_name'
                        )
                    )
                    : null,

                'last_name' => trim(
                    (string) $request->input(
                        'last_name'
                    )
                ),

                'birth_date' =>
                    $request->birth_date,

                'address' =>
                    trim(
                        (string) $request->input(
                            'address'
                        )
                    ),

                'contact_number' => null,
                'license_type' => null,
                'license_expiration' => null,
            ]);
        } else {
            $driver = Driver::firstOrCreate(
                [
                    'license_number' =>
                        $licenseNumber,
                ],
                [
                    'has_no_license' => false,

                    'first_name' =>
                        $request->first_name,

                    'middle_name' =>
                        $request->middle_name,

                    'last_name' =>
                        $request->last_name,

                    'address' =>
                        $request->address,

                    'birth_date' =>
                        $request->birth_date,

                    'contact_number' =>
                        null,

                    'license_type' =>
                        null,

                    'license_expiration' =>
                        null,
                ]
            );
        }

        // =======================================================
        // VEHICLE
        // =======================================================

        /*
         * IMPORTANT:
         *
         * We also CANNOT use:
         *
         * Vehicle::firstOrCreate([
         *     'plate_number' => null
         * ]);
         *
         * because multiple vehicles may legitimately have
         * no plate number.
         *
         * Therefore:
         *
         * 1. If a real plate exists:
         *    use plate_number as the identity.
         *
         * 2. If there is NO plate:
         *    create a separate vehicle record for this
         *    citation because there is no unique vehicle
         *    identifier available.
         */

        if ($hasNoPlate) {
            $vehicle = Vehicle::create([
                'driver_id' =>
                    $driver->id,

                'plate_number' =>
                    null,

                'has_no_plate' =>
                    true,

                'vehicle_type' =>
                    $vehicleTypeValue,

                'region_number' =>
                    $request->region_number,

                'owner_name' =>
                    $request->owner_name,
            ]);
        } else {
            $vehicle = Vehicle::firstOrCreate(
                [
                    'plate_number' =>
                        $plateNumber,
                ],
                [
                    'driver_id' =>
                        $driver->id,

                    'has_no_plate' =>
                        false,

                    'vehicle_type' =>
                        $vehicleTypeValue,

                    'region_number' =>
                        $request->region_number,

                    'owner_name' =>
                        $request->owner_name,
                ]
            );
        }

        // =======================================================
        // CREATE MAIN VIOLATION RECORD
        // =======================================================

        $manilaNow = now()
            ->setTimezone('Asia/Manila');

        $violation = Violation::create([
            'ticket_number' =>
                $request->ticket_number,

            'driver_id' =>
                $driver->id,

            'vehicle_id' =>
                $vehicle->id,

            /*
             * Official primary violation:
             * stores the existing violation_types ID.
             *
             * Primary Others:
             * stores NULL here.
             */

            'violation_type_id' =>
                $primaryViolationTypeId,

            'user_id' =>
                Auth::id(),

            'violation_date' =>
                $manilaNow->format('Y-m-d'),

            'violation_time' =>
                $manilaNow->format('H:i:s'),

            /*
             * GPS location supplied by the form.
             *
             * OCR Place of Violation does NOT overwrite
             * this value.
             */

            'location' =>
                $request->location ??
                'Location not available',

            'latitude' =>
                $request->latitude ??
                null,

            'longitude' =>
                $request->longitude ??
                null,

            'remarks' =>
                $request->remarks ??
                null,

            /*
             * Primary custom Others value.
             *
             * Example:
             * "Illegal Turn"
             *
             * This does NOT modify violation_types.
             */

            'other_violation' =>
                $primaryOtherViolation,

            'ticket_image' =>
                $ticketImagePath,

            'status' =>
                'Pending',
        ]);

        // =======================================================
        // SAVE OFFICIAL VIOLATION TYPES
        // =======================================================

        /*
         * Only existing official violation type IDs are
         * synchronized here.
         *
         * Custom "Others" values are NEVER placed here.
         */

        $violation
            ->violationTypes()
            ->sync($violationTypeIds);

        // =======================================================
        // SAVE ADDITIONAL CUSTOM "OTHERS"
        // =======================================================

        /*
         * Example:
         *
         * Primary:
         *   No Helmet
         *
         * Additional:
         *   Others -> "Illegal Turn"
         *
         * The custom text is stored in:
         * violation_other_types
         *
         * It does NOT become an official violation type.
         */

        foreach (
            $additionalOtherViolations
            as $otherName
        ) {
            ViolationOtherType::create([
                'violation_id' =>
                    $violation->id,

                'name' =>
                    $otherName,
            ]);
        }

        // =======================================================
        // SAVE EVIDENCE IMAGES
        // =======================================================

        if ($request->hasFile('evidence_images')) {
            foreach (
                $request->file('evidence_images')
                as $image
            ) {
                $imagePath =
                    $image->store(
                        'violations/evidence',
                        'public'
                    );

                ViolationImage::create([
                    'violation_id' =>
                        $violation->id,

                    'image_path' =>
                        $imagePath,
                ]);
            }
        }

        // =======================================================
        // AUDIT LOG
        // =======================================================

        AuditLogger::log(
            'CREATE',
            'Created traffic violation ticket ' .
                $violation->ticket_number,
            $violation
        );

        // =======================================================
        // REDIRECT SUCCESS
        // =======================================================

        return redirect()
            ->route('enforcer.success')
            ->with(
                'success',
                'Traffic citation successfully recorded.'
            );
    }

    // =======================================================
    // SHOW VIOLATION
    // =======================================================

    public function show(string $id)
    {
        $violation = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'violationTypes',
            'violationOtherTypes',
            'images',
            'user',
        ])
            ->where(
                'user_id',
                Auth::id()
            )
            ->findOrFail($id);

        return view(
            'enforcer.show',
            compact('violation')
        );
    }

    // =======================================================
    // EDIT
    // =======================================================

    public function edit(string $id)
    {
        // Not implemented.
    }

    // =======================================================
    // UPDATE
    // =======================================================

    public function update(
        Request $request,
        string $id
    ) {
        // Not implemented.
    }

    // =======================================================
    // DELETE
    // =======================================================

    public function destroy(string $id)
    {
        // Not implemented.
    }

    // =======================================================
    // SUCCESS PAGE
    // =======================================================

    public function success()
    {
        return view(
            'enforcer.success'
        );
    }
}