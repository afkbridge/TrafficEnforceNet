<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enforcer;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EnforcerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $position = $request->position;
        $status = $request->status;

        $enforcers = Enforcer::query();

        // Search
        if ($search) {
            $enforcers->where(function ($query) use ($search) {
                $query->where('badge_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Position Filter
        if ($position) {
            $enforcers->where('position', $position);
        }

        // Employment Status Filter
        if ($status) {
            $enforcers->where('employment_status', $status);
        }

        $enforcers = $enforcers
            ->orderBy('last_name')
            ->paginate(10)
            ->withQueryString();

        // Dashboard Summary Cards
        $totalEnforcers = Enforcer::count();

        $activeEnforcers = Enforcer::where(
            'employment_status',
            'Active'
        )->count();

        $inactiveEnforcers = Enforcer::where(
            'employment_status',
            'Inactive'
        )->count();

        // Placeholder until login tracking is implemented
        $onlineEnforcers = 0;

        return view('admin.enforcers.index', compact(
            'enforcers',
            'search',
            'position',
            'status',
            'totalEnforcers',
            'activeEnforcers',
            'inactiveEnforcers',
            'onlineEnforcers'
        ));
    }

    public function create()
    {
        return view('admin.enforcers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'badge_number' => 'required|unique:enforcers',
            'first_name' => 'required',
            'last_name' => 'required',
            'position' => 'required',
            'employment_status' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
        ]);


        DB::transaction(function () use ($request) {

            // Create user account first
            $user = User::create([
                'role_id' => 2, // enforcer role (we will verify this later)
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);


            // Create enforcer profile
            Enforcer::create([
                'user_id' => $user->id,
                'badge_number' => $request->badge_number,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'contact_number' => $request->contact_number,
                'email' => $request->email,
                'position' => $request->position,
                'employment_status' => $request->employment_status,
            ]);
        });


        return redirect()
            ->route('enforcers.index')
            ->with('success', 'Enforcer and account created successfully.');
    }

    public function edit(Enforcer $enforcer)
    {
        return view('admin.enforcers.edit', compact('enforcer'));
    }

    public function update(Request $request, Enforcer $enforcer)
    {
        $request->validate([
            'badge_number' => 'required|unique:enforcers,badge_number,' . $enforcer->id,
            'first_name' => 'required',
            'last_name' => 'required',
            'position' => 'required',
            'employment_status' => 'required',
            'email' => 'nullable|email',
        ]);


        // Update enforcer profile
        $enforcer->update([
            'badge_number' => $request->badge_number,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'position' => $request->position,
            'employment_status' => $request->employment_status,
        ]);


        // Update linked user account
        if ($enforcer->user) {

            $enforcer->user->update([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
            ]);
        }


        return redirect()
            ->route('enforcers.index')
            ->with('success', 'Enforcer updated successfully.');
    }

    public function destroy(Enforcer $enforcer)
    {
        $enforcer->delete();

        return redirect()
            ->route('enforcers.index')
            ->with('success', 'Enforcer deleted successfully.');
    }
}
