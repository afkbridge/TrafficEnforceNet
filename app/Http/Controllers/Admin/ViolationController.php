<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ViolationsExport;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Violation;
use App\Models\ViolationImage;
use App\Models\ViolationOtherType;
use App\Models\ViolationType;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ViolationController extends Controller
{
    /**
     * Display all violation records.
     */
    public function index(Request $request)
    {
        $query = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'violationTypes',
            'violationOtherTypes',
            'user',
        ]);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where(
                    'ticket_number',
                    'like',
                    '%' . $search . '%'
                )
                    ->orWhereHas('driver', function ($driverQuery) use ($search) {
                        $driverQuery->where(function ($nameQuery) use ($search) {
                            $nameQuery
                                ->whereRaw(
                                    "CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?",
                                    ['%' . $search . '%']
                                )
                                ->orWhereRaw(
                                    "CONCAT(first_name, ' ', last_name) LIKE ?",
                                    ['%' . $search . '%']
                                )
                                ->orWhere(
                                    'first_name',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'middle_name',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'last_name',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'license_number',
                                    'like',
                                    '%' . $search . '%'
                                );
                        });
                    })
                    ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                        $vehicleQuery->where(
                            'plate_number',
                            'like',
                            '%' . $search . '%'
                        );
                    })
                    ->orWhereHas('violationType', function ($typeQuery) use ($search) {
                        $typeQuery->where(
                            'name',
                            'like',
                            '%' . $search . '%'
                        );
                    })
                    ->orWhere(
                        'other_violation',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhereHas('violationTypes', function ($typeQuery) use ($search) {
                        $typeQuery->where(
                            'name',
                            'like',
                            '%' . $search . '%'
                        );
                    })
                    ->orWhereHas('violationOtherTypes', function ($otherQuery) use ($search) {
                        $otherQuery->where(
                            'name',
                            'like',
                            '%' . $search . '%'
                        );
                    })
                    ->orWhere(
                        'location',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('date_from')) {
            $query->whereDate(
                'violation_date',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {
            $query->whereDate(
                'violation_date',
                '<=',
                $request->date_to
            );
        }

        $violations = $query
            ->orderBy('violation_date', 'desc')
            ->orderBy('violation_time', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.violations.index',
            compact('violations')
        );
    }

    /**
     * Display a driver's complete violation history.
     */
    public function driverHistory(Driver $driver)
    {
        $driver->load([
            'vehicles',
            'violations.vehicle',
            'violations.violationType',
            'violations.violationTypes',
            'violations.violationOtherTypes',
            'violations.user',
        ]);

        $violations = $driver->violations
            ->sortByDesc(function ($violation) {
                return ($violation->violation_date ?? '') . ' ' .
                    ($violation->violation_time ?? '');
            })
            ->values();

        $totalViolations = $violations->count();

        $pendingViolations = $violations
            ->filter(function ($violation) {
                return strtolower(
                    $violation->status ?? ''
                ) === 'pending';
            })
            ->count();

        $settledViolations = $violations
            ->filter(function ($violation) {
                return strtolower(
                    $violation->status ?? ''
                ) === 'settled';
            })
            ->count();

        return view(
            'admin.violations.driver-history',
            compact(
                'driver',
                'violations',
                'totalViolations',
                'pendingViolations',
                'settledViolations'
            )
        );
    }

    /**
     * Show the form for creating a new violation.
     */
    public function create()
    {
        $violationTypes = ViolationType::orderBy('name')->get();

        return view(
            'admin.violations.create',
            compact('violationTypes')
        );
    }

    /**
     * Store a newly created violation.
     *
     * Supports:
     * - Official primary violation
     * - Primary Other violation
     * - Additional official violations
     * - Additional Other violations
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            /*
            |--------------------------------------------------------------------------
            | CITATION
            |--------------------------------------------------------------------------
            */

            'ticket_number' => [
                'required',
                'string',
                'regex:/^[0-9]{6}$/',
                'unique:violations,ticket_number',
            ],

            'violation_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'violation_time' => [
                'required',
                'date_format:H:i',
            ],

            /*
            |--------------------------------------------------------------------------
            | DRIVER
            |--------------------------------------------------------------------------
            */

            'first_name' => [
                'required',
                'string',
                'max:255',
                "regex:/^[\pL\pM\s.\\'-]+$/u",
            ],

            'middle_name' => [
                'nullable',
                'string',
                'max:255',
                "regex:/^[\pL\pM\s.\\'-]+$/u",
            ],

            'last_name' => [
                'required',
                'string',
                'max:255',
                "regex:/^[\pL\pM\s.\\'-]+$/u",
            ],

            'address' => [
                'nullable',
                'string',
                'max:255',
            ],

            'contact_number' => [
                'nullable',
                'string',
                'max:30',
                'regex:/^[0-9+\-\s()]+$/',
            ],

            'license_number' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9\s\-]+$/',
            ],

            'birth_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            /*
            |--------------------------------------------------------------------------
            | VEHICLE
            |--------------------------------------------------------------------------
            */

            'plate_number' => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Za-z0-9\s\-]+$/',
            ],

            'vehicle_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | PRIMARY VIOLATION
            |--------------------------------------------------------------------------
            |
            | Can contain:
            | - Official ViolationType ID
            | - "other"
            |
            */

            'violation_type_id' => [
                'required',
                'string',
            ],

            'other_violation' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | ADDITIONAL VIOLATIONS
            |--------------------------------------------------------------------------
            |
            | The arrays MUST use the same indexes.
            |
            */

            'additional_violation_type_ids' => [
                'nullable',
                'array',
            ],

            'additional_violation_type_ids.*' => [
                'nullable',
                'string',
            ],

            'additional_other_violation_names' => [
                'nullable',
                'array',
            ],

            'additional_other_violation_names.*' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | LOCATION
            |--------------------------------------------------------------------------
            */

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'latitude' => [
                'nullable',
                'numeric',
            ],

            'longitude' => [
                'nullable',
                'numeric',
            ],

            /*
            |--------------------------------------------------------------------------
            | REMARKS
            |--------------------------------------------------------------------------
            */

            'remarks' => [
                'nullable',
                'string',
                'max:5000',
            ],

            /*
            |--------------------------------------------------------------------------
            | IMAGES
            |--------------------------------------------------------------------------
            */

            'ticket_image' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120',
            ],

            'evidence_images' => [
                'nullable',
                'array',
            ],

            'evidence_images.*' => [
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | NORMALIZE BASIC VALUES
        |--------------------------------------------------------------------------
        */

        $validated['first_name'] = trim(
            $validated['first_name']
        );

        $validated['middle_name'] = isset(
            $validated['middle_name']
        )
            ? trim($validated['middle_name'])
            : null;

        $validated['last_name'] = trim(
            $validated['last_name']
        );

        $validated['license_number'] = strtoupper(
            trim($validated['license_number'])
        );

        $validated['plate_number'] = strtoupper(
            trim($validated['plate_number'])
        );

        if (isset($validated['address'])) {
            $validated['address'] = trim(
                $validated['address']
            );
        }

        if (isset($validated['contact_number'])) {
            $validated['contact_number'] = trim(
                $validated['contact_number']
            );
        }

        if (isset($validated['vehicle_type'])) {
            $validated['vehicle_type'] = trim(
                $validated['vehicle_type']
            );
        }

        if (isset($validated['location'])) {
            $validated['location'] = trim(
                $validated['location']
            );

            if (
                $validated['location'] ===
                'Location services are not available on this device'
            ) {
                return back()
                    ->withErrors([
                        'location' => 'Please enter the violation location.',
                    ])
                    ->withInput();
            }
        }

        if (isset($validated['remarks'])) {
            $validated['remarks'] = trim(
                $validated['remarks']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DETERMINE PRIMARY VIOLATION
        |--------------------------------------------------------------------------
        */

        $primaryViolationTypeId = null;
        $primaryOtherViolation = null;

        $primarySelection = strtolower(
            trim(
                (string) $validated['violation_type_id']
            )
        );

        /*
        |--------------------------------------------------------------------------
        | PRIMARY = OTHER
        |--------------------------------------------------------------------------
        */

        if ($primarySelection === 'other') {
            $primaryOtherViolation = trim(
                (string) (
                    $validated['other_violation'] ?? ''
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
        }

        /*
        |--------------------------------------------------------------------------
        | PRIMARY = OFFICIAL VIOLATION
        |--------------------------------------------------------------------------
        */ else {
            if (
                !ctype_digit(
                    (string) $validated['violation_type_id']
                )
            ) {
                return back()
                    ->withErrors([
                        'violation_type_id' =>
                        'The selected violation type is invalid.',
                    ])
                    ->withInput();
            }

            $primaryViolationTypeId = (int)
            $validated['violation_type_id'];

            if ($primaryViolationTypeId <= 0) {
                return back()
                    ->withErrors([
                        'violation_type_id' =>
                        'The selected violation type is invalid.',
                    ])
                    ->withInput();
            }

            if (
                !ViolationType::where(
                    'id',
                    $primaryViolationTypeId
                )->exists()
            ) {
                return back()
                    ->withErrors([
                        'violation_type_id' =>
                        'The selected violation type does not exist.',
                    ])
                    ->withInput();
            }

            /*
            |--------------------------------------------------------------------------
            | OFFICIAL VIOLATION MUST NOT HAVE OTHER TEXT
            |--------------------------------------------------------------------------
            */

            $primaryOtherViolation = null;
        }

        /*
        |--------------------------------------------------------------------------
        | PROCESS ADDITIONAL VIOLATIONS
        |--------------------------------------------------------------------------
        */

        $officialAdditionalViolationIds = [];
        $additionalOtherViolations = [];

        $additionalViolationIds =
            $validated['additional_violation_type_ids'] ?? [];

        $additionalOtherNames =
            $validated['additional_other_violation_names'] ?? [];

        foreach (
            $additionalViolationIds as $index => $additionalTypeId
        ) {
            /*
            |--------------------------------------------------------------------------
            | IGNORE EMPTY ROW
            |--------------------------------------------------------------------------
            */

            if (
                $additionalTypeId === null ||
                trim((string) $additionalTypeId) === ''
            ) {
                continue;
            }

            $normalizedTypeId = strtolower(
                trim(
                    (string) $additionalTypeId
                )
            );

            /*
            |--------------------------------------------------------------------------
            | ADDITIONAL = OTHER
            |--------------------------------------------------------------------------
            */

            if ($normalizedTypeId === 'other') {
                $otherName = trim(
                    (string) (
                        $additionalOtherNames[$index] ?? ''
                    )
                );

                if ($otherName === '') {
                    return back()
                        ->withErrors([
                            'additional_violation_type_ids' =>
                            'Please specify every additional "Other" violation.',
                        ])
                        ->withInput();
                }

                $additionalOtherViolations[] = $otherName;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | ADDITIONAL = OFFICIAL VIOLATION
            |--------------------------------------------------------------------------
            */

            if (!ctype_digit((string) $additionalTypeId)) {
                return back()
                    ->withErrors([
                        'additional_violation_type_ids' =>
                        'An invalid additional violation type was selected.',
                    ])
                    ->withInput();
            }

            $additionalTypeId = (int) $additionalTypeId;

            if ($additionalTypeId <= 0) {
                return back()
                    ->withErrors([
                        'additional_violation_type_ids' =>
                        'An invalid additional violation type was selected.',
                    ])
                    ->withInput();
            }

            /*
            |--------------------------------------------------------------------------
            | CHECK THAT OFFICIAL VIOLATION EXISTS
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | DO NOT DUPLICATE PRIMARY OFFICIAL VIOLATION
            |--------------------------------------------------------------------------
            */

            if (
                $primaryViolationTypeId !== null &&
                $additionalTypeId === $primaryViolationTypeId
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | DO NOT DUPLICATE ADDITIONAL OFFICIAL VIOLATIONS
            |--------------------------------------------------------------------------
            */

            if (
                !in_array(
                    $additionalTypeId,
                    $officialAdditionalViolationIds,
                    true
                )
            ) {
                $officialAdditionalViolationIds[] =
                    $additionalTypeId;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DATABASE TRANSACTION
        |--------------------------------------------------------------------------
        */

        $violation = DB::transaction(function () use (
            $request,
            $validated,
            $primaryViolationTypeId,
            $primaryOtherViolation,
            $officialAdditionalViolationIds,
            $additionalOtherViolations
        ) {
            /*
            |--------------------------------------------------------------------------
            | DRIVER
            |--------------------------------------------------------------------------
            */

            $driver = Driver::firstOrCreate(
                [
                    'license_number' =>
                    $validated['license_number'],
                ],
                [
                    'first_name' =>
                    $validated['first_name'],

                    'middle_name' =>
                    $validated['middle_name'] ?? null,

                    'last_name' =>
                    $validated['last_name'],

                    'address' =>
                    $validated['address'] ?? null,

                    'contact_number' =>
                    $validated['contact_number'] ?? null,

                    'birth_date' =>
                    $validated['birth_date'],
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | UPDATE DRIVER INFORMATION
            |--------------------------------------------------------------------------
            */

            $driver->first_name =
                $validated['first_name'];

            $driver->middle_name =
                $validated['middle_name'] ?? null;

            $driver->last_name =
                $validated['last_name'];

            $driver->license_number =
                $validated['license_number'];

            $driver->address =
                $validated['address'] ?? null;

            $driver->contact_number =
                $validated['contact_number'] ?? null;

            $driver->birth_date =
                $validated['birth_date'];

            $driver->save();

            /*
            |--------------------------------------------------------------------------
            | VEHICLE
            |--------------------------------------------------------------------------
            */

            $vehicle = Vehicle::firstOrCreate(
                [
                    'plate_number' =>
                    $validated['plate_number'],
                ],
                [
                    'driver_id' =>
                    $driver->id,

                    'vehicle_type' =>
                    $validated['vehicle_type'] ?? null,
                ]
            );

            $vehicle->driver_id =
                $driver->id;

            if (
                !empty($validated['vehicle_type'] ?? null)
            ) {
                $vehicle->vehicle_type =
                    $validated['vehicle_type'];
            }

            $vehicle->save();

            /*
            |--------------------------------------------------------------------------
            | TICKET IMAGE
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | CREATE VIOLATION
            |--------------------------------------------------------------------------
            */

            $violation = Violation::create([
                'ticket_number' =>
                $validated['ticket_number'],

                'driver_id' =>
                $driver->id,

                'vehicle_id' =>
                $vehicle->id,

                'violation_type_id' =>
                $primaryViolationTypeId,

                'other_violation' =>
                $primaryOtherViolation,

                'user_id' =>
                Auth::id(),

                'violation_date' =>
                $validated['violation_date'],

                'violation_time' =>
                $validated['violation_time'],

                'location' =>
                $validated['location'] ?? null,

                'latitude' =>
                $validated['latitude'] ?? null,

                'longitude' =>
                $validated['longitude'] ?? null,

                'remarks' =>
                $validated['remarks'] ?? null,

                'ticket_image' =>
                $ticketImagePath,

                'status' =>
                'Pending',
            ]);

            /*
            |--------------------------------------------------------------------------
            | SAVE ADDITIONAL OFFICIAL VIOLATIONS
            |--------------------------------------------------------------------------
            */

            if (
                !empty($officialAdditionalViolationIds)
            ) {
                $violation
                    ->violationTypes()
                    ->sync(
                        $officialAdditionalViolationIds
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE ADDITIONAL OTHER VIOLATIONS
            |--------------------------------------------------------------------------
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

            /*
            |--------------------------------------------------------------------------
            | EVIDENCE IMAGES
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

            return $violation;
        });

        /*
        |--------------------------------------------------------------------------
        | AUDIT LOG
        |--------------------------------------------------------------------------
        */

        AuditLogger::log(
            'CREATE_VIOLATION',
            'Created violation ticket ' .
                $violation->ticket_number .
                '.',
            $violation
        );

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'violations.show',
                $violation->id
            )
            ->with(
                'success',
                'Citation record added successfully.'
            );
    }

    /**
     * Display a specific violation.
     */
    public function show($id)
    {
        $violation = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'violationTypes',
            'violationOtherTypes',
            'user',
            'images',
        ])->findOrFail($id);

        return view(
            'admin.violations.show',
            compact('violation')
        );
    }

    /**
     * Show the form for editing a violation.
     */
    public function edit($id)
    {
        $violation = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'violationTypes',
            'violationOtherTypes',
            'user',
            'images',
        ])->findOrFail($id);

        $violationTypes =
            ViolationType::orderBy('name')->get();

        return view(
            'admin.violations.edit',
            compact(
                'violation',
                'violationTypes'
            )
        );
    }

    /**
     * Update an existing violation.
     *
     * POSO Admin can update:
     * - Primary violation
     * - Additional violations
     * - Driver information
     * - Vehicle information
     * - Birth date
     * - Location
     * - Remarks
     *
     * Status is intentionally NOT editable here.
     */
    public function update(Request $request, $id)
    {
        $violation = Violation::with([
            'driver',
            'vehicle',
            'violationTypes',
            'violationOtherTypes',
        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            /*
            |--------------------------------------------------------------------------
            | PRIMARY VIOLATION
            |--------------------------------------------------------------------------
            */

            'violation_type_id' => [
                'required',
                'string',
            ],

            'other_violation' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | ADDITIONAL VIOLATIONS
            |--------------------------------------------------------------------------
            */

            'additional_violation_type_ids' => [
                'nullable',
                'array',
            ],

            'additional_violation_type_ids.*' => [
                'nullable',
                'string',
            ],

            'additional_other_violation_names' => [
                'nullable',
                'array',
            ],

            'additional_other_violation_names.*' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | REMARKS
            |--------------------------------------------------------------------------
            */

            'remarks' => [
                'nullable',
                'string',
                'max:5000',
            ],

            /*
            |--------------------------------------------------------------------------
            | LOCATION
            |--------------------------------------------------------------------------
            */

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | DRIVER
            |--------------------------------------------------------------------------
            */

            'first_name' => [
                'required',
                'string',
                'max:255',
            ],

            'middle_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'last_name' => [
                'required',
                'string',
                'max:255',
            ],

            'license_number' => [
                'required',
                'string',
                'max:255',
            ],

            'birth_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'address' => [
                'nullable',
                'string',
                'max:255',
            ],

            'contact_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | VEHICLE
            |--------------------------------------------------------------------------
            */

            'plate_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'vehicle_type' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | NORMALIZE DRIVER / VEHICLE VALUES
        |--------------------------------------------------------------------------
        */

        $validated['first_name'] =
            trim($validated['first_name']);

        $validated['middle_name'] =
            isset($validated['middle_name'])
            ? trim($validated['middle_name'])
            : null;

        $validated['last_name'] =
            trim($validated['last_name']);

        $validated['license_number'] =
            strtoupper(
                trim($validated['license_number'])
            );

        if (isset($validated['address'])) {
            $validated['address'] =
                trim($validated['address']);
        }

        if (isset($validated['contact_number'])) {
            $validated['contact_number'] =
                trim($validated['contact_number']);
        }

        if (isset($validated['plate_number'])) {
            $validated['plate_number'] =
                strtoupper(
                    trim($validated['plate_number'])
                );
        }

        if (isset($validated['vehicle_type'])) {
            $validated['vehicle_type'] =
                trim($validated['vehicle_type']);
        }

        if (isset($validated['location'])) {
            $validated['location'] =
                trim($validated['location']);
        }

        if (isset($validated['remarks'])) {
            $validated['remarks'] =
                trim($validated['remarks']);
        }

        /*
        |--------------------------------------------------------------------------
        | DETERMINE PRIMARY VIOLATION
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | Other -> Official:
        | violation_type_id = official ID
        | other_violation = NULL
        |
        | Official -> Other:
        | violation_type_id = NULL
        | other_violation = custom text
        |
        |--------------------------------------------------------------------------
        */

        $primaryViolationTypeId = null;
        $primaryOtherViolation = null;

        $primarySelection = strtolower(
            trim(
                (string) $validated['violation_type_id']
            )
        );

        /*
        |--------------------------------------------------------------------------
        | PRIMARY = OTHER
        |--------------------------------------------------------------------------
        */

        if ($primarySelection === 'other') {
            $primaryOtherViolation = trim(
                (string) (
                    $validated['other_violation'] ?? ''
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
        }

        /*
        |--------------------------------------------------------------------------
        | PRIMARY = OFFICIAL
        |--------------------------------------------------------------------------
        */ else {
            /*
            |--------------------------------------------------------------------------
            | MUST BE A NUMERIC VIOLATION TYPE ID
            |--------------------------------------------------------------------------
            */

            if (
                !ctype_digit(
                    (string) $validated['violation_type_id']
                )
            ) {
                return back()
                    ->withErrors([
                        'violation_type_id' =>
                        'The selected violation type is invalid.',
                    ])
                    ->withInput();
            }

            $primaryViolationTypeId =
                (int) $validated['violation_type_id'];

            if ($primaryViolationTypeId <= 0) {
                return back()
                    ->withErrors([
                        'violation_type_id' =>
                        'The selected violation type is invalid.',
                    ])
                    ->withInput();
            }

            /*
            |--------------------------------------------------------------------------
            | CHECK OFFICIAL VIOLATION EXISTS
            |--------------------------------------------------------------------------
            */

            if (
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

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT:
            | WHEN CHANGING FROM OTHER TO OFFICIAL,
            | REMOVE THE OLD OTHER TEXT.
            |--------------------------------------------------------------------------
            */

            $primaryOtherViolation = null;
        }

        /*
        |--------------------------------------------------------------------------
        | PROCESS ADDITIONAL VIOLATIONS
        |--------------------------------------------------------------------------
        */

        $officialAdditionalViolationIds = [];
        $additionalOtherViolations = [];

        $additionalViolationIds =
            $validated['additional_violation_type_ids'] ?? [];

        $additionalOtherNames =
            $validated['additional_other_violation_names'] ?? [];

        foreach (
            $additionalViolationIds as $index => $additionalTypeId
        ) {
            /*
            |--------------------------------------------------------------------------
            | IGNORE EMPTY ROWS
            |--------------------------------------------------------------------------
            */

            if (
                $additionalTypeId === null ||
                trim((string) $additionalTypeId) === ''
            ) {
                continue;
            }

            $normalizedTypeId = strtolower(
                trim(
                    (string) $additionalTypeId
                )
            );

            /*
            |--------------------------------------------------------------------------
            | ADDITIONAL = OTHER
            |--------------------------------------------------------------------------
            */

            if ($normalizedTypeId === 'other') {
                $otherName = trim(
                    (string) (
                        $additionalOtherNames[$index] ?? ''
                    )
                );

                if ($otherName === '') {
                    return back()
                        ->withErrors([
                            'additional_violation_type_ids' =>
                            'Please specify every additional "Other" violation.',
                        ])
                        ->withInput();
                }

                $additionalOtherViolations[] =
                    $otherName;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | ADDITIONAL = OFFICIAL
            |--------------------------------------------------------------------------
            */

            if (
                !ctype_digit(
                    (string) $additionalTypeId
                )
            ) {
                return back()
                    ->withErrors([
                        'additional_violation_type_ids' =>
                        'An invalid additional violation type was selected.',
                    ])
                    ->withInput();
            }

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

            /*
            |--------------------------------------------------------------------------
            | CHECK OFFICIAL VIOLATION EXISTS
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | DO NOT DUPLICATE PRIMARY OFFICIAL VIOLATION
            |--------------------------------------------------------------------------
            */

            if (
                $primaryViolationTypeId !== null &&
                $additionalTypeId ===
                $primaryViolationTypeId
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | DO NOT DUPLICATE ADDITIONAL OFFICIAL VIOLATIONS
            |--------------------------------------------------------------------------
            */

            if (
                !in_array(
                    $additionalTypeId,
                    $officialAdditionalViolationIds,
                    true
                )
            ) {
                $officialAdditionalViolationIds[] =
                    $additionalTypeId;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DATABASE TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $violation,
            $validated,
            $primaryViolationTypeId,
            $primaryOtherViolation,
            $officialAdditionalViolationIds,
            $additionalOtherViolations
        ) {
            /*
            |--------------------------------------------------------------------------
            | UPDATE DRIVER
            |--------------------------------------------------------------------------
            */

            $driver = $violation->driver;

            if ($driver) {
                $driver->first_name =
                    $validated['first_name'];

                $driver->middle_name =
                    $validated['middle_name'] ?? null;

                $driver->last_name =
                    $validated['last_name'];

                $driver->license_number =
                    $validated['license_number'];

                $driver->birth_date =
                    $validated['birth_date'];

                $driver->address =
                    $validated['address'] ?? null;

                $driver->contact_number =
                    $validated['contact_number'] ?? null;

                $driver->save();
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE VEHICLE
            |--------------------------------------------------------------------------
            */

            $vehicle = $violation->vehicle;

            if ($vehicle) {
                if (
                    array_key_exists(
                        'plate_number',
                        $validated
                    ) &&
                    filled(
                        $validated['plate_number']
                    )
                ) {
                    $vehicle->plate_number =
                        $validated['plate_number'];
                }

                if (
                    array_key_exists(
                        'vehicle_type',
                        $validated
                    )
                ) {
                    $vehicle->vehicle_type =
                        $validated['vehicle_type'] ?? null;
                }

                $vehicle->save();
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE PRIMARY VIOLATION
            |--------------------------------------------------------------------------
            |
            | Official:
            | violation_type_id = ID
            | other_violation = NULL
            |
            | Other:
            | violation_type_id = NULL
            | other_violation = custom text
            |--------------------------------------------------------------------------
            */

            $violation->violation_type_id =
                $primaryViolationTypeId;

            $violation->other_violation =
                $primaryOtherViolation;

            /*
            |--------------------------------------------------------------------------
            | UPDATE LOCATION
            |--------------------------------------------------------------------------
            */

            if (
                array_key_exists(
                    'location',
                    $validated
                )
            ) {
                $violation->location =
                    $validated['location'] ?? null;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE REMARKS
            |--------------------------------------------------------------------------
            */

            $violation->remarks =
                $validated['remarks'] ?? null;

            /*
            |--------------------------------------------------------------------------
            | STATUS IS NOT UPDATED
            |--------------------------------------------------------------------------
            |
            | Existing Pending/Settled status remains unchanged.
            |
            */

            $violation->save();

            /*
            |--------------------------------------------------------------------------
            | REMOVE OLD ADDITIONAL OFFICIAL VIOLATIONS
            |--------------------------------------------------------------------------
            */

            $violation
                ->violationTypes()
                ->sync([]);

            /*
            |--------------------------------------------------------------------------
            | REMOVE OLD ADDITIONAL OTHER VIOLATIONS
            |--------------------------------------------------------------------------
            */

            $violation
                ->violationOtherTypes()
                ->delete();

            /*
            |--------------------------------------------------------------------------
            | SAVE CURRENT ADDITIONAL OFFICIAL VIOLATIONS
            |--------------------------------------------------------------------------
            */

            if (
                !empty($officialAdditionalViolationIds)
            ) {
                $violation
                    ->violationTypes()
                    ->sync(
                        $officialAdditionalViolationIds
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE CURRENT ADDITIONAL OTHER VIOLATIONS
            |--------------------------------------------------------------------------
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
        });

        /*
        |--------------------------------------------------------------------------
        | AUDIT LOG
        |--------------------------------------------------------------------------
        */

        AuditLogger::log(
            'UPDATE_VIOLATION',
            'Updated violation ticket ' .
                $violation->ticket_number .
                '.',
            $violation
        );

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'violations.show',
                $violation->id
            )
            ->with(
                'success',
                'Violation record updated successfully.'
            );
    }

    /**
     * Export violation records to Excel.
     */
    public function export(Request $request)
    {
        return Excel::download(
            new ViolationsExport(
                $request->search,
                $request->status,
                $request->date_from,
                $request->date_to
            ),
            'traffic-violation-records.xlsx'
        );
    }
}
