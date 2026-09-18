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
        /*
        |--------------------------------------------------------------------------
        | CLEAN OCR TEXT
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | DEFAULT RESULT
        |--------------------------------------------------------------------------
        */

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
        |
        | OCR FORMAT:
        |
        | Last Name. First Name, Middie Name
        | MENDOZA, REGINA BINUYA
        |
        */

        foreach ($lines as $index => $line) {

            if (
                stripos($line, 'Last Name') !== false &&
                stripos($line, 'First Name') !== false
            ) {

                $nameLine = $lines[$index + 1] ?? '';

                /*
                |--------------------------------------------------------------------------
                | NAME FORMAT:
                | LAST NAME, FIRST NAME MIDDLE NAME
                |--------------------------------------------------------------------------
                */

                if (strpos($nameLine, ',') !== false) {

                    $nameParts = array_map(
                        'trim',
                        explode(',', $nameLine, 2)
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | LAST NAME
                    |--------------------------------------------------------------------------
                    */

                    $result['last_name'] =
                        $nameParts[0] ?? '';

                    /*
                    |--------------------------------------------------------------------------
                    | FIRST + MIDDLE NAME
                    |--------------------------------------------------------------------------
                    */

                    if (!empty($nameParts[1])) {

                        $firstMiddle =
                            preg_split(
                                '/\s+/',
                                trim($nameParts[1])
                            );

                        /*
                        |--------------------------------------------------------------------------
                        | FIRST NAME
                        |--------------------------------------------------------------------------
                        */

                        $result['first_name'] =
                            $firstMiddle[0] ?? '';

                        /*
                        |--------------------------------------------------------------------------
                        | MIDDLE NAME
                        |--------------------------------------------------------------------------
                        */

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
        |
        | OCR FORMAT:
        |
        | C11-23-011612
        |
        */

        if (preg_match(
            '/\b([A-Z]\d{2}-\d{2}-\d{6})\b/i',
            $fullText,
            $match
        )) {

            $result['license_number'] =
                strtoupper(
                    trim($match[1])
                );
        }

        /*
        |--------------------------------------------------------------------------
        | BIRTH DATE
        |--------------------------------------------------------------------------
        |
        | OCR FORMAT:
        |
        | Date of Birth
        | 2005/04/25
        |
        */

        if (preg_match(
            '/Date\s+of\s+Birth\s*\n\s*(\d{4}\/\d{2}\/\d{2})/i',
            $fullText,
            $match
        )) {

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
        |
        | OCR FORMAT:
        |
        | 299
        | WAY NAY STREET. PALUDPUD, LA PAZ.
        | TARLAC, 2314
        |
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

                /*
                |--------------------------------------------------------------------------
                | HOUSE NUMBER
                |--------------------------------------------------------------------------
                */

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

                /*
                |--------------------------------------------------------------------------
                | STREET / BARANGAY / CITY LINE
                |--------------------------------------------------------------------------
                */

                $addressParts[] =
                    $line;

                /*
                |--------------------------------------------------------------------------
                | NEXT ADDRESS LINE
                |--------------------------------------------------------------------------
                */

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

        /*
        |--------------------------------------------------------------------------
        | RETURN PARSED INFORMATION
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