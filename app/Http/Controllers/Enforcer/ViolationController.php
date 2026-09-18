<?php

namespace App\Http\Controllers\Enforcer;

use App\Services\OcrSpaceService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\Violation;
use App\Models\ViolationType;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\ViolationImage;

class ViolationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | VIOLATIONS LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Violation::with([
            'driver',
            'vehicle',
            'violationType'
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

    /*
    |--------------------------------------------------------------------------
    | CREATE / ISSUE TICKET PAGE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $violationTypes = ViolationType::orderBy('name')->get();

        return view(
            'enforcer.issue-ticket',
            compact('violationTypes')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DRIVER'S LICENSE OCR
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | PARSE DRIVER'S LICENSE OCR TEXT
    |--------------------------------------------------------------------------
    */

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

        $fullText = implode(
            "\n",
            $lines
        );

        /*
        |--------------------------------------------------------------------------
        | DRIVER NAME
        |--------------------------------------------------------------------------
        */

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

                        $firstMiddle =
                            preg_split(
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

        /*
        |--------------------------------------------------------------------------
        | LICENSE NUMBER
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | BIRTH DATE
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                '/Date\s+of\s+Birth\s*\n\s*(\d{4}\/\d{2}\/\d{2})/i',
                $fullText,
                $match
            )
        ) {

            $birthDate =
                trim($match[1]);

            $date =
                \DateTime::createFromFormat(
                    'Y/m/d',
                    $birthDate
                );

            if ($date) {

                $result['birth_date'] =
                    $date->format('Y-m-d');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ADDRESS
        |--------------------------------------------------------------------------
        */

        foreach ($lines as $index => $line) {

            if (
                stripos($line, 'STREET') !== false ||
                preg_match(
                    '/\bST\.?\b/i',
                    $line
                )
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

                $addressParts[] =
                    $line;

                if (isset($lines[$index + 1])) {

                    $nextLine =
                        $lines[$index + 1];

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

        /*
        |--------------------------------------------------------------------------
        | FALLBACK ADDRESS
        |--------------------------------------------------------------------------
        */

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

    /*
    |--------------------------------------------------------------------------
    | CITATION TICKET OCR
    |--------------------------------------------------------------------------
    */

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
                $this->parseCitationTicketText(
                    $text
                );

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

    /*
    |--------------------------------------------------------------------------
    | PARSE CITATION TICKET OCR TEXT
    |--------------------------------------------------------------------------
    */

    private function parseCitationTicketText(
        string $text
    ): array {

        /*
        |--------------------------------------------------------------------------
        | CLEAN OCR TEXT
        |--------------------------------------------------------------------------
        */

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
                            preg_replace('/\s+/', ' ', $line)
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

        /*
        |--------------------------------------------------------------------------
        | DEFAULT RESULT
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | TICKET NUMBER
        |--------------------------------------------------------------------------
        |
        | Handles:
        |
        | N° 249204
        | Nº 249204
        | No. 249204
        | No 249204
        |
        */

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

        /*
        |--------------------------------------------------------------------------
        | DRIVER NAME
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | The actual citation ticket OCR is column-based.
        |
        | OCR result:
        |
        | LAST
        | NAME
        | FIRST
        | NAME
        | MIDDLE
        | NAME
        | DELA CRUZ
        | JUAN MANUEL
        | PEREZ
        |
        | The three actual values are listed AFTER
        | the MIDDLE NAME label.
        |
        | Therefore:
        |
        | DELA CRUZ   = LAST NAME
        | JUAN MANUEL = FIRST NAME
        | PEREZ       = MIDDLE NAME
        |
        */

        $lastNameIndex = null;
        $firstNameIndex = null;
        $middleNameIndex = null;

        foreach ($lines as $index => $line) {

            if (
                strtoupper($line) === 'LAST'
                &&
                isset($lines[$index + 1])
                &&
                strtoupper($lines[$index + 1]) === 'NAME'
            ) {

                $lastNameIndex = $index;

                break;
            }
        }

        foreach ($lines as $index => $line) {

            if (
                strtoupper($line) === 'FIRST'
                &&
                isset($lines[$index + 1])
                &&
                strtoupper($lines[$index + 1]) === 'NAME'
            ) {

                $firstNameIndex = $index;

                break;
            }
        }

        foreach ($lines as $index => $line) {

            if (
                strtoupper($line) === 'MIDDLE'
                &&
                isset($lines[$index + 1])
                &&
                strtoupper($lines[$index + 1]) === 'NAME'
            ) {

                $middleNameIndex = $index;

                break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | NAME VALUES
        |--------------------------------------------------------------------------
        |
        | The OCR output places all three name values after
        | the MIDDLE NAME label.
        |
        | Example:
        |
        | MIDDLE
        | NAME
        | DELA CRUZ
        | JUAN MANUEL
        | PEREZ
        |
        */

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

            if (
                $lastName !== '' &&
                $firstName !== '' &&
                $middleName !== ''
            ) {

                $result['last_name'] =
                    trim($lastName);

                $result['first_name'] =
                    trim($firstName);

                $result['middle_name'] =
                    trim($middleName);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FALLBACK NAME PARSING
        |--------------------------------------------------------------------------
        |
        | Handles:
        |
        | Last Name: DELA CRUZ
        | First Name: JUAN MANUEL
        | Middle Name: PEREZ
        |
        */

        if ($result['last_name'] === '') {

            if (
                preg_match(
                    '/Last\s+Name\s*:?\s*(.+)/i',
                    $fullText,
                    $match
                )
            ) {

                $result['last_name'] =
                    trim($match[1]);
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
                    trim($match[1]);
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
                    trim($match[1]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | LICENSE NUMBER
        |--------------------------------------------------------------------------
        */

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

            $result['license_number'] =
                strtoupper(
                    trim($match[1])
                );
        }

        /*
        |--------------------------------------------------------------------------
        | ADDRESS
        |--------------------------------------------------------------------------
        |
        | Actual OCR:
        |
        | ADDRESS (Number, Street, Subd., City/Municipality)
        | 25 MACABULOS ST., BARANGAY SAN
        | LOQUE, TARLAC CITY, TARLAC
        | LICENSE
        | NUMBER
        |
        */

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

                $addressParts[] =
                    $line;

                if (
                    count($addressParts) >= 3
                ) {
                    break;
                }
            }

            if (!empty($addressParts)) {

                $result['address'] =
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
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FALLBACK ADDRESS
        |--------------------------------------------------------------------------
        */

        if ($result['address'] === '') {

            foreach ($lines as $index => $line) {

                if (
                    preg_match(
                        '/^Address\s*:\s*(.+)$/i',
                        $line,
                        $match
                    )
                ) {

                    $result['address'] =
                        trim($match[1]);

                    break;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | BIRTH DATE
        |--------------------------------------------------------------------------
        |
        | Handles:
        |
        | BIRTH
        | DATE
        | 01/15/1990
        |
        | and:
        |
        | Birth Date: 01/15/1990
        |
        */

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
            $result['birth_date'] === ''
            &&
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

        /*
        |--------------------------------------------------------------------------
        | VEHICLE TYPE
        |--------------------------------------------------------------------------
        |
        | Handles:
        |
        | SEDAN (e.g., Toyota Vios)
        |
        */

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

        /*
        |--------------------------------------------------------------------------
        | PLATE NUMBER
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | NDO 7890
        |
        */

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

        /*
        |--------------------------------------------------------------------------
        | REGION NUMBER
        |--------------------------------------------------------------------------
        |
        | Only populate when an actual Region label exists.
        |
        */

        if (
            preg_match(
                '/REGION\s*(?:NUMBER|NO\.?)?\s*:?\s*([A-Z0-9-]+)/i',
                $fullText,
                $match
            )
        ) {

            $result['region_number'] =
                strtoupper(
                    trim($match[1])
                );
        }

        /*
        |--------------------------------------------------------------------------
        | VEHICLE OWNER
        |--------------------------------------------------------------------------
        |
        | Actual OCR:
        |
        | Vehicle
        | Owner
        | MARIA DELA CRUZ
        |
        */

        $vehicleOwnerIndex = null;

        foreach ($lines as $index => $line) {

            if (
                strtoupper($line) === 'VEHICLE'
                &&
                isset($lines[$index + 1])
                &&
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
                    $ownerName !== ''
                    &&
                    !preg_match(
                        '/^(You|Notice|Apprehending|Officer|Driver)/i',
                        $ownerName
                    )
                ) {

                    $result['owner_name'] =
                        $ownerName;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FALLBACK VEHICLE OWNER
        |--------------------------------------------------------------------------
        */

        if (
            $result['owner_name'] === ''
            &&
            preg_match(
                '/Vehicle\s+Owner\s*:?\s*(.+)/i',
                $fullText,
                $match
            )
        ) {

            $result['owner_name'] =
                trim($match[1]);
        }

        /*
        |--------------------------------------------------------------------------
        | PLACE OF VIOLATION
        |--------------------------------------------------------------------------
        |
        | Actual OCR:
        |
        | Place of Violation
        | Street
        | F. TANEDO ST.
        |
        */

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
                    $line !== ''
                ) {

                    $result['location'] =
                        $line;

                    break;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FALLBACK PLACE OF VIOLATION
        |--------------------------------------------------------------------------
        */

        if (
            $result['location'] === ''
            &&
            preg_match(
                '/Place\s+of\s+Violation\s*:?\s*(.+)/i',
                $fullText,
                $match
            )
        ) {

            $result['location'] =
                trim($match[1]);
        }

        /*
        |--------------------------------------------------------------------------
        | CHECKED VIOLATIONS
        |--------------------------------------------------------------------------
        |
        | OCR.space converted checked boxes to "*"
        | and unchecked boxes to "•".
        |
        | Example:
        |
        | *Disregarding Traffic lights/signs/Officer
        | *Reckless Driving
        |
        */

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
                        $cleanLine;

                    break;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | REMOVE DUPLICATE VIOLATIONS
        |--------------------------------------------------------------------------
        */

        $result['violations'] =
            array_values(
                array_unique(
                    $result['violations']
                )
            );

        /*
        |--------------------------------------------------------------------------
        | RETURN RESULT
        |--------------------------------------------------------------------------
        */

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | STORE VIOLATION
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'ticket_number' => [
                'nullable',
                'string',
                'unique:violations,ticket_number'
            ],

            'first_name' => 'required|string|max:255',

            'last_name' => 'required|string|max:255',

            'license_number' => 'required|string|max:255',

            'plate_number' => 'required|string|max:50',

            'violation_type_id' => 'required',

            'location' => 'nullable|string|max:500',

            'latitude' => 'nullable|numeric',

            'longitude' => 'nullable|numeric',

            'remarks' => 'nullable|string',

            'ticket_image' => 'nullable|image|max:5120',

            'evidence_images.*' => 'nullable|image|max:5120',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SAVE TICKET IMAGE
        |--------------------------------------------------------------------------
        */

        $ticketImagePath = null;

        if ($request->hasFile('ticket_image')) {

            $ticketImagePath = $request
                ->file('ticket_image')
                ->store(
                    'violations/tickets',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | DRIVER
        |--------------------------------------------------------------------------
        */

        $driver = Driver::firstOrCreate(
            [
                'license_number' =>
                    $request->license_number
            ],
            [
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

                'contact_number' => null,

                'license_type' => null,

                'license_expiration' => null,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | VEHICLE
        |--------------------------------------------------------------------------
        */

        $vehicle = Vehicle::firstOrCreate(
            [
                'plate_number' =>
                    strtoupper(
                        $request->plate_number
                    )
            ],
            [
                'driver_id' =>
                    $driver->id,

                'vehicle_type' =>
                    $request->vehicle_type,

                'region_number' =>
                    $request->region_number,

                'owner_name' =>
                    $request->owner_name,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | HANDLE OTHER VIOLATION TYPE
        |--------------------------------------------------------------------------
        */

        if ($request->violation_type_id === 'other') {

            $newViolationType =
                ViolationType::create([
                    'name' =>
                        $request->other_violation,

                    'description' =>
                        'Added by enforcer during citation',
                ]);

            $violationTypeId =
                $newViolationType->id;

        } else {

            $violationTypeId =
                $request->violation_type_id;
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE VIOLATION
        |--------------------------------------------------------------------------
        */

        $violation = Violation::create([
            'ticket_number' =>
                $request->ticket_number
                    ??
                'TN-' .
                strtoupper(
                    Str::random(8)
                ),

            'driver_id' =>
                $driver->id,

            'vehicle_id' =>
                $vehicle->id,

            'violation_type_id' =>
                $violationTypeId,

            'user_id' =>
                Auth::id(),

            'violation_date' =>
                now()
                    ->setTimezone('Asia/Manila')
                    ->format('Y-m-d'),

            'violation_time' =>
                now()
                    ->setTimezone('Asia/Manila')
                    ->format('H:i:s'),

            'location' =>
                $request->location
                    ??
                'Location not available',

            'latitude' =>
                $request->latitude
                    ??
                null,

            'longitude' =>
                $request->longitude
                    ??
                null,

            'remarks' =>
                $request->remarks
                    ??
                null,

            'ticket_image' =>
                $ticketImagePath,

            'status' =>
                'Pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SAVE EVIDENCE IMAGES
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | REDIRECT SUCCESS
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('enforcer.success')
            ->with(
                'success',
                'Traffic citation successfully recorded.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW VIOLATION
    |--------------------------------------------------------------------------
    */

    public function show(string $id)
    {
        $violation = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'images',
            'user'
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

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(string $id)
    {
        //
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        string $id
    ) {
        //
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(string $id)
    {
        //
    }

    /*
    |--------------------------------------------------------------------------
    | SUCCESS PAGE
    |--------------------------------------------------------------------------
    */

    public function success()
    {
        return view(
            'enforcer.success'
        );
    }
}