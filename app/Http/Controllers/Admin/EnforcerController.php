<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enforcer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EnforcerController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Enforcer Management
    |--------------------------------------------------------------------------
    */

    /**
     * Display the Enforcer Management page.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $position = $request->position;
        $status = $request->status;

        /*
        |--------------------------------------------------------------------------
        | Enforcers
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Dashboard Summary Cards
        |--------------------------------------------------------------------------
        */

        // Total registered POSO enforcers
        $totalEnforcers = Enforcer::count();

        // An enforcer is considered ONLINE when their
        // last heartbeat was received within the last 1 minute.
        $onlineEnforcers = Enforcer::whereHas('user', function ($query) {
            $query->whereNotNull('last_seen_at')
                ->where('last_seen_at', '>=', now()->subMinute());
        })->count();

        // Everyone else is considered OFFLINE.
        $offlineEnforcers = $totalEnforcers - $onlineEnforcers;

        /*
        |--------------------------------------------------------------------------
        | Administrator and BPLO Accounts
        |--------------------------------------------------------------------------
        */

        // Administrator accounts
        $administrators = User::where('role_id', 1)
            ->orderBy('name')
            ->get();

        // BPLO Personnel accounts
        $bploUsers = User::where('role_id', 3)
            ->orderBy('name')
            ->get();

        return view('admin.enforcers.index', compact(
            'enforcers',
            'search',
            'position',
            'status',
            'totalEnforcers',
            'onlineEnforcers',
            'offlineEnforcers',
            'administrators',
            'bploUsers'
        ));
    }


    /**
     * Show the form for creating a new enforcer.
     */
    public function create()
    {
        return view('admin.enforcers.create');
    }


    /**
     * Store a newly created enforcer and user account.
     */
    public function store(Request $request)
    {
        $request->validate([
            'badge_number' => 'required|unique:enforcers,badge_number',
            'first_name' => 'required',
            'last_name' => 'required',
            'position' => 'required',
            'employment_status' => 'required',

            'email' => 'required|email|unique:users,email',

            'password' => [
                'required',
                'min:8',
                'confirmed'
            ],
        ]);

        DB::transaction(function () use ($request) {

            /*
            |--------------------------------------------------------------------------
            | Create POSO Enforcer User Account
            |--------------------------------------------------------------------------
            */

            $user = User::create([
                'role_id' => 2,
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'account_status' => 'Active',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Create Enforcer Profile
            |--------------------------------------------------------------------------
            */

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


    /*
    |--------------------------------------------------------------------------
    | ADMINISTRATOR / BPLO USER MANAGEMENT
    |--------------------------------------------------------------------------
    */

    /**
     * Create an Administrator or BPLO Personnel account.
     *
     * This method is used by the User Management section
     * at the bottom of the Enforcer Management page.
     */
    public function storeStaff(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'role_id' => [
                'required',
                Rule::in([1, 3]),
            ],

            'password' => [
                'required',
                'min:8',
                'confirmed',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Determine Role Name
        |--------------------------------------------------------------------------
        */

        $roleName = match ((int) $request->role_id) {
            1 => 'Administrator',
            3 => 'BPLO Personnel',
            default => 'User',
        };

        /*
        |--------------------------------------------------------------------------
        | Create Account
        |--------------------------------------------------------------------------
        */

        User::create([
            'role_id' => $request->role_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'account_status' => 'Active',
        ]);

        return redirect()
            ->route('enforcers.index')
            ->with(
                'success',
                $roleName . ' account created successfully.'
            );
    }


    /**
     * Reset password for an Administrator or BPLO account.
     */
    public function resetStaffPassword(Request $request, User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | Only Administrator and BPLO accounts can use this method.
        |--------------------------------------------------------------------------
        */

        if (!in_array((int) $user->role_id, [1, 3])) {
            return back()->with(
                'error',
                'This account cannot be managed from User Management.'
            );
        }

        $request->validate([
            'password' => [
                'required',
                'min:8',
                'confirmed',
            ],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with(
            'success',
            'Password reset successfully.'
        );
    }


    /**
     * Disable or enable an Administrator or BPLO account.
     */
    public function toggleStaffStatus(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | Only Administrator and BPLO accounts can be managed here.
        |--------------------------------------------------------------------------
        */

        if (!in_array((int) $user->role_id, [1, 3])) {
            return back()->with(
                'error',
                'This account cannot be managed from User Management.'
            );
        }

        $newStatus = $user->account_status === 'Active'
            ? 'Disabled'
            : 'Active';

        $user->update([
            'account_status' => $newStatus,
        ]);

        return back()->with(
            'success',
            $user->name . '\'s account is now ' . $newStatus . '.'
        );
    }


    /**
     * Delete an Administrator or BPLO account.
     */
    public function destroyStaff(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | Only Administrator and BPLO accounts can be deleted here.
        |--------------------------------------------------------------------------
        */

        if (!in_array((int) $user->role_id, [1, 3])) {
            return back()->with(
                'error',
                'This account cannot be deleted from User Management.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent deleting the currently logged-in administrator
        |--------------------------------------------------------------------------
        */

        if (auth()->id() === $user->id) {
            return back()->with(
                'error',
                'You cannot delete your own administrator account.'
            );
        }

        $name = $user->name;

        $user->delete();

        return back()->with(
            'success',
            $name . '\'s account was deleted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ENFORCER EDIT / ACCOUNT MANAGEMENT
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for editing an enforcer.
     */
    public function edit(Enforcer $enforcer)
    {
        return view('admin.enforcers.edit', compact('enforcer'));
    }


    /**
     * Show the enforcer account information.
     */
    public function account(Enforcer $enforcer)
    {
        $enforcer->load('user');

        return view(
            'admin.enforcers.account',
            compact('enforcer')
        );
    }


    /**
     * Update an enforcer and their linked user account.
     */
    public function update(Request $request, Enforcer $enforcer)
    {
        $request->validate([
            'badge_number' => [
                'required',
                'unique:enforcers,badge_number,' . $enforcer->id
            ],

            'first_name' => 'required',
            'last_name' => 'required',
            'position' => 'required',
            'employment_status' => 'required',

            'email' => 'nullable|email',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update Enforcer Profile
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Update Linked User Account
        |--------------------------------------------------------------------------
        */

        if ($enforcer->user) {
            $enforcer->user->update([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
            ]);
        }

        return redirect()
            ->route('enforcers.index')
            ->with(
                'success',
                'Enforcer updated successfully.'
            );
    }


    /**
     * Delete an enforcer and their linked user account.
     */
    public function destroy(Enforcer $enforcer)
    {
        DB::transaction(function () use ($enforcer) {

            /*
            |--------------------------------------------------------------------------
            | Delete linked user account
            |--------------------------------------------------------------------------
            */

            if ($enforcer->user) {
                $enforcer->user->delete();
            }

            /*
            |--------------------------------------------------------------------------
            | Delete enforcer profile
            |--------------------------------------------------------------------------
            */

            $enforcer->delete();
        });

        return redirect()
            ->route('enforcers.index')
            ->with(
                'success',
                'Enforcer deleted successfully.'
            );
    }


    /**
     * Reset the password of an enforcer's account.
     */
    public function resetPassword(
        Request $request,
        Enforcer $enforcer
    ) {
        $request->validate([
            'password' => [
                'required',
                'min:8',
                'confirmed'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Check Linked Account
        |--------------------------------------------------------------------------
        */

        if (!$enforcer->user_id) {
            return back()->with(
                'error',
                'No account linked to this enforcer.'
            );
        }

        $user = User::find($enforcer->user_id);

        if (!$user) {
            return back()->with(
                'error',
                'User account not found.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Reset Password
        |--------------------------------------------------------------------------
        */

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with(
            'success',
            'Password reset successfully.'
        );
    }
}