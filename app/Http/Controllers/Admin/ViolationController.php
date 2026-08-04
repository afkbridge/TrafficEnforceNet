<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use Illuminate\Http\Request;
use App\Exports\ViolationsExport;
use Maatwebsite\Excel\Facades\Excel;

class ViolationController extends Controller
{
    public function index(Request $request)
    {
        $query = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'user'
        ]);


        // Filter today's violations
        if ($request->filter == 'today') {

            $query->whereDate(
                'violation_date',
                today()
            );
        }


        // Filter specific date
        if ($request->date) {

            $query->whereDate(
                'violation_date',
                $request->date
            );
        }


        $violations = $query
            ->latest()
            ->get();


        return view(
            'admin.violations.index',
            compact('violations')
        );
    }



    public function show(string $id)
    {
        $violation = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'user',
            'images'
        ])
            ->findOrFail($id);


        return view(
            'admin.violations.show',
            compact('violation')
        );
    }




    public function edit($id)
    {
        $violation = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'user',
            'images'
        ])
            ->findOrFail($id);


        $violationTypes = \App\Models\ViolationType::all();


        return view(
            'admin.violations.edit',
            compact(
                'violation',
                'violationTypes'
            )
        );
    }





    public function update(Request $request, $id)
    {
        $violation = Violation::with([
            'driver',
            'vehicle'
        ])
            ->findOrFail($id);



        $request->validate([

            // Violation validation
            'violation_type_id' => 'required',
            'status' => 'required',


            // Driver validation
            'first_name' => 'required',
            'last_name' => 'required',
            'license_number' => 'required',
            'address' => 'required',

        ]);



        // Update violation record
        $violation->update([

            'violation_type_id' => $request->violation_type_id,
            'status' => $request->status,
            'remarks' => $request->remarks

        ]);




        // Update driver information
        if ($violation->driver) {

            $violation->driver->update([

                'first_name' => $request->first_name,

                'middle_name' => $request->middle_name,

                'last_name' => $request->last_name,

                'license_number' => $request->license_number,

                'address' => $request->address,

                'contact_number' => $request->contact_number,

                'license_type' => $request->license_type

            ]);
        }



        return redirect()

            ->route('violations.show', $id)

            ->with(
                'success',
                'Violation updated successfully.'
            );
    }





    /**
     * Export violations to Excel
     */
    public function export(Request $request)
    {
        return Excel::download(
            new ViolationsExport(
                $request->filter,
                $request->date
            ),
            'traffic_violation_records.xlsx'
        );
    }
}
