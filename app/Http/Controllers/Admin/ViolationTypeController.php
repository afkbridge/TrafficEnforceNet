<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ViolationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViolationTypeController extends Controller
{
    /**
     * Display all violation types.
     */
    public function index()
    {
        $violationTypes = ViolationType::withCount('violations')
            ->orderBy('name')
            ->get();

        return view(
            'admin.settings.violation-types',
            compact('violationTypes')
        );
    }

    /**
     * Store a new violation type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:violation_types,name',
            'description' => 'nullable|string|max:1000',
        ]);

        ViolationType::create($validated);

        return redirect()
            ->route('admin.violation-types.index')
            ->with('success', 'Violation type added successfully.');
    }

    /**
     * Update an existing violation type.
     */
    public function update(
        Request $request,
        ViolationType $violationType
    ) {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:violation_types,name,' . $violationType->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $violationType->update($validated);

        return redirect()
            ->route('admin.violation-types.index')
            ->with('success', 'Violation type updated successfully.');
    }

    /**
     * Delete a violation type.
     */
    public function destroy(ViolationType $violationType)
    {
        /*
        |--------------------------------------------------------------------------
        | CHECK PRIMARY VIOLATION RECORDS
        |--------------------------------------------------------------------------
        | Checks if this violation type is being used directly in:
        |
        | violations.violation_type_id
        |
        */
        $isUsedAsPrimary = $violationType
            ->violations()
            ->exists();

        /*
        |--------------------------------------------------------------------------
        | CHECK ADDITIONAL VIOLATION RECORDS
        |--------------------------------------------------------------------------
        | Additional violations are stored in the pivot table:
        |
        | violation_violation_type
        |
        | If this violation type exists in that table, it is still being used
        | by an existing violation record.
        |
        */
        $isUsedAsAdditional = DB::table('violation_violation_type')
            ->where('violation_type_id', $violationType->id)
            ->exists();

        /*
        |--------------------------------------------------------------------------
        | PREVENT DELETION IF USED
        |--------------------------------------------------------------------------
        */
        if ($isUsedAsPrimary || $isUsedAsAdditional) {
            return redirect()
                ->route('admin.violation-types.index')
                ->with(
                    'error',
                    'This violation type cannot be deleted because it is already used in violation records.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE UNUSED VIOLATION TYPE
        |--------------------------------------------------------------------------
        */
        $violationType->delete();

        return redirect()
            ->route('admin.violation-types.index')
            ->with(
                'success',
                'Violation type deleted successfully.'
            );
    }
}