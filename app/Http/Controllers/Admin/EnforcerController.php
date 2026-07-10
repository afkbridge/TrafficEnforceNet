<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enforcer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EnforcerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $position = $request->position;
        $status = $request->status;

        $enforcers = Enforcer::with('user');

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

        $activeEnforcers = Enforcer::where('employment_status', 'Active')->count();

        $inactiveEnforcers = Enforcer::where('employment_status', 'Inactive')->count();

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
            'badge_number' => 'required|unique:enforcers,badge_number',
            'first_name' => 'required',
            'last_name' => 'required',
            'position' => 'required',
            'employment_status' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        DB::transaction(function () use ($request) {

            // Create User Account
            $user = User::create([
                'role_id' => 2,
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'account_status' => 'Active',
            ]);

            // Create Enforcer Profile
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

    public function account(Enforcer $enforcer)
    {
        $enforcer->load('user');

        return view('admin.enforcers.account', compact('enforcer'));
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

        // Update Enforcer Profile
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

        // Update Linked User Account
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
        DB::transaction(function () use ($enforcer) {

            if ($enforcer->user) {
                $enforcer->user->delete();
            }

            $enforcer->delete();
        });

        return redirect()
            ->route('enforcers.index')
            ->with('success', 'Enforcer deleted successfully.');
    }
    public function resetPassword(Request $request, Enforcer $enforcer)
{
    $request->validate([
        'password' => [
            'required',
            'min:8',
            'confirmed'
        ],
    ]);


    if (!$enforcer->user_id) {

        return back()->with('error', 'No account linked to this enforcer.');

    }


    $user = User::find($enforcer->user_id);


    if (!$user) {

        return back()->with('error', 'User account not found.');

    }


    $user->update([
        'password' => Hash::make($request->password)
    ]);


    return back()->with(
        'success',
        'Password reset successfully.'
    );
}
}
