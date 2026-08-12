<?php

namespace App\Http\Controllers\Enforcer;

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
    public function index(Request $request)
    {
        $query = Violation::with([
            'driver',
            'vehicle',
            'violationType'
        ])
            ->where('user_id', Auth::id());

        // TODAY FILTER
        if ($request->filter === 'today') {
            $query->whereDate(
                'violation_date',
                now()->setTimezone('Asia/Manila')->toDateString()
            );
        }

        // PICK A DATE FILTER
        if ($request->filled('date')) {
            $query->whereDate('violation_date', $request->date);
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

    public function create()
    {
        $violationTypes = ViolationType::orderBy('name')->get();

        return view(
            'enforcer.issue-ticket',
            compact('violationTypes')
        );
    }

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
                'license_number' => $request->license_number
            ],
            [
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'contact_number' => null,
                'license_type' => null,
                'license_expiration' => null,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | VEHICLE
        |--------------------------------------------------------------------------
        |
        | Current POSO vehicle fields:
        | plate_number
        | vehicle_type
        | region_number
        | owner_name
        |
        */

        $vehicle = Vehicle::firstOrCreate(
            [
                'plate_number' => strtoupper(
                    $request->plate_number
                )
            ],
            [
                'driver_id' => $driver->id,
                'vehicle_type' => $request->vehicle_type,
                'region_number' => $request->region_number,
                'owner_name' => $request->owner_name,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | HANDLE OTHER VIOLATION TYPE
        |--------------------------------------------------------------------------
        */

        if ($request->violation_type_id === 'other') {

            $newViolationType = ViolationType::create([
                'name' => $request->other_violation,
                'description' =>
                'Added by enforcer during citation',
            ]);

            $violationTypeId = $newViolationType->id;
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
                'TN-' . strtoupper(Str::random(8)),

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
            $request->location,

            'latitude' =>
            $request->latitude,

            'longitude' =>
            $request->longitude,

            'remarks' =>
            $request->remarks,

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

                $imagePath = $image->store(
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
    public function show(string $id)
    {
        $violation = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'images',
            'user'
        ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view(
            'enforcer.show',
            compact('violation')
        );
    }

    public function edit(string $id)
    {
        //
    }

    public function update(
        Request $request,
        string $id
    ) {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function success()
    {
        return view('enforcer.success');
    }
}
