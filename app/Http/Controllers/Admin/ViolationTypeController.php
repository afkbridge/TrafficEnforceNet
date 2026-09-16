<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ViolationType;
use Illuminate\Http\Request;

class ViolationTypeController extends Controller
{
    public function index()
    {
        $violationTypes = ViolationType::withCount('violations')
            ->orderBy('name')
            ->get();

        return view('admin.settings.violation-types', compact('violationTypes'));
    }

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

    public function update(Request $request, ViolationType $violationType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:violation_types,name,' . $violationType->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $violationType->update($validated);

        return redirect()
            ->route('admin.violation-types.index')
            ->with('success', 'Violation type updated successfully.');
    }

    public function destroy(ViolationType $violationType)
    {
        if ($violationType->violations()->exists()) {
            return redirect()
                ->route('admin.violation-types.index')
                ->with('error', 'This violation type cannot be deleted because it is already used in violation records.');
        }

        $violationType->delete();

        return redirect()
            ->route('admin.violation-types.index')
            ->with('success', 'Violation type deleted successfully.');
    }
}