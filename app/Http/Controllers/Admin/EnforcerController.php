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

        /*
        |--------------------------------------------------------------------------
        | Enforcer Accounts
        |--------------------------------------------------------------------------
        |
        | Start from the users table so ALL accounts with role_id = 2
        | are displayed, even if they do not yet have an Enforcer profile.
        |
        */

        $enforcers = User::with('enforcer')
            ->where('role_id', 2);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search) {
            $enforcers->where(function ($query) use ($search) {
                $query->where('username', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhereHas('enforcer', function ($profileQuery) use ($search) {
                        $profileQuery
                            ->where('badge_number', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('contact_number', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Position Filter
        |--------------------------------------------------------------------------
        */

        if ($position) {
            $enforcers->whereHas('enforcer', function ($query) use ($position) {
                $query->where('position', $position);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $enforcers = $enforcers
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Dashboard Summary Cards
        |--------------------------------------------------------------------------
        */

        $totalEnforcers = User::where('role_id', 2)->count();

        /*
        |--------------------------------------------------------------------------
        | Online Enforcers
        |--------------------------------------------------------------------------
        */

        $onlineEnforcers = User::where('role_id', 2)
            ->whereNotNull('last_seen_at')
            ->where('last_seen_at', '>=', now()->subMinute())
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Offline Enforcers
        |--------------------------------------------------------------------------
        */

        $offlineEnforcers = $totalEnforcers - $onlineEnforcers;

        /*
        |--------------------------------------------------------------------------
        | Administrator and BPLO Accounts
        |--------------------------------------------------------------------------
        */

        $administrators = User::where('role_id', 1)
            ->orderBy('name')
            ->get();

        $bploUsers = User::where('role_id', 3)
            ->orderBy('name')
            ->get();

        return view(
            'admin.enforcers.index',
            compact(
                'enforcers',
                'search',
                'position',
                'totalEnforcers',
                'onlineEnforcers',
                'offlineEnforcers',
                'administrators',
                'bploUsers'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create Enforcer
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for creating a completely new Enforcer
     * and User account.
     */
    public function create()
    {
        return view('admin.enforcers.create');
    }

    /*
    |--------------------------------------------------------------------------
    | Store Enforcer
    |--------------------------------------------------------------------------
    */

    /**
     * Store a completely new Enforcer and User account.
     *
     * This is ONLY for the normal Create Enforcer page.
     */
    public function store(Request $request)
    {
        $request->validate([
            'badge_number' => [
                'nullable',
                'string',
                'unique:enforcers,badge_number',
            ],

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

            'position' => [
                'required',
                Rule::in([
                    'Traffic Enforcer',
                    'Traffic Aide',
                ]),
            ],

            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username',
            ],

            'password' => [
                'required',
                'min:8',
                'confirmed',
            ],

            'contact_number' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        DB::transaction(function () use ($request) {

            /*
            |--------------------------------------------------------------------------
            | Create New POSO Enforcer User Account
            |--------------------------------------------------------------------------
            */

            $user = User::create([
                'role_id' => 2,
                'name' => trim(
                    $request->first_name . ' ' . $request->last_name
                ),
                'username' => $request->username,
                'email' => null,
                'password' => Hash::make(
                    $request->password
                ),
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
                'email' => null,
                'position' => $request->position,
            ]);
        });

        return redirect()
            ->route('enforcers.index')
            ->with(
                'success',
                'Enforcer and account created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Administrator / BPLO User Management
    |--------------------------------------------------------------------------
    */

    /**
     * Create an Administrator or BPLO Personnel account.
     */
    public function storeStaff(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username',
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

        $roleName = match ((int) $request->role_id) {
            1 => 'Administrator',
            3 => 'BPLO Personnel',
            default => 'User',
        };

        User::create([
            'role_id' => $request->role_id,
            'name' => $request->name,
            'username' => $request->username,
            'email' => null,
            'password' => Hash::make(
                $request->password
            ),
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
    public function resetStaffPassword(
        Request $request,
        User $user
    ) {
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
            'password' => Hash::make(
                $request->password
            ),
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
            $user->name . "'s account is now " . $newStatus . '.'
        );
    }

    /**
     * Delete an Administrator or BPLO account.
     */
    public function destroyStaff(User $user)
    {
        if (!in_array((int) $user->role_id, [1, 3])) {
            return back()->with(
                'error',
                'This account cannot be deleted from User Management.'
            );
        }

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
            $name . "'s account was deleted successfully."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Enforcer Edit / Account Management
    |--------------------------------------------------------------------------
    */

    /**
     * Show the Enforcer account information.
     */
    public function show(Enforcer $enforcer)
    {
        $enforcer->load('user');

        return view(
            'admin.enforcers.account',
            compact('enforcer')
        );
    }

    /**
     * Show the Enforcer edit page.
     *
     * This handles BOTH:
     *
     * 1. Existing Enforcer profiles.
     * 2. Existing POSO Enforcer User accounts without
     *    an Enforcer profile.
     *
     * Both use the SAME edit.blade.php.
     *
     * There is NO create-profile page.
     */
    public function edit($enforcer)
    {
        /*
        |--------------------------------------------------------------------------
        | Existing POSO User Without Enforcer Profile
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | /enforcers/user-15/edit
        |
        */

        if (
            is_string($enforcer) &&
            str_starts_with($enforcer, 'user-')
        ) {
            $userId = (int) substr($enforcer, 5);

            $user = User::findOrFail($userId);

            /*
            |--------------------------------------------------------------------------
            | Verify POSO Enforcer Role
            |--------------------------------------------------------------------------
            */

            if ((int) $user->role_id !== 2) {
                return redirect()
                    ->route('enforcers.index')
                    ->with(
                        'error',
                        'This account is not a POSO Enforcer account.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Profile
            |--------------------------------------------------------------------------
            */

            if ($user->enforcer) {
                return redirect()->route(
                    'enforcers.edit',
                    $user->enforcer->id
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Existing User - No Enforcer Profile
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            |
            | We use the NORMAL edit.blade.php.
            |
            | No new User account is created.
            |
            */

            return view(
                'admin.enforcers.edit',
                [
                    'enforcer' => null,
                    'user' => $user,
                    'creatingProfileForExistingUser' => true,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Existing Enforcer Profile
        |--------------------------------------------------------------------------
        */

        $enforcerModel = Enforcer::with('user')
            ->findOrFail($enforcer);

        return view(
            'admin.enforcers.edit',
            [
                'enforcer' => $enforcerModel,
                'user' => $enforcerModel->user,
                'creatingProfileForExistingUser' => false,
            ]
        );
    }

    /**
     * Show the Enforcer account information.
     */
    public function account(Enforcer $enforcer)
    {
        $enforcer->load('user');

        return view(
            'admin.enforcers.account',
            compact('enforcer')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Enforcer
    |--------------------------------------------------------------------------
    */

    /**
     * Update an existing Enforcer profile.
     *
     * This SAME method also creates the missing Enforcer profile
     * when an existing POSO Enforcer User account does not yet
     * have one.
     *
     * IMPORTANT:
     *
     * No new User account is created.
     */
    public function update(Request $request, $enforcer)
    {
        /*
        |--------------------------------------------------------------------------
        | CASE 1:
        | Existing POSO User Without Enforcer Profile
        |--------------------------------------------------------------------------
        |
        | The Edit button sends:
        |
        | /enforcers/user-{user_id}
        |
        */

        if (
            is_string($enforcer) &&
            str_starts_with($enforcer, 'user-')
        ) {
            $userId = (int) substr($enforcer, 5);

            $user = User::findOrFail($userId);

            /*
            |--------------------------------------------------------------------------
            | Verify POSO Enforcer Role
            |--------------------------------------------------------------------------
            */

            if ((int) $user->role_id !== 2) {
                return redirect()
                    ->route('enforcers.index')
                    ->with(
                        'error',
                        'This account is not a POSO Enforcer account.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Enforcer Profile
            |--------------------------------------------------------------------------
            */

            if ($user->enforcer) {
                return redirect()
                    ->route(
                        'enforcers.edit',
                        $user->enforcer->id
                    )
                    ->with(
                        'error',
                        'This user already has an Enforcer profile.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Validate New Enforcer Profile
            |--------------------------------------------------------------------------
            */

            $request->validate([
                'badge_number' => [
                    'nullable',
                    'string',
                    'unique:enforcers,badge_number',
                ],

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

                'position' => [
                    'required',
                    Rule::in([
                        'Traffic Enforcer',
                        'Traffic Aide',
                    ]),
                ],

                'username' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('users', 'username')
                        ->ignore($user->id),
                ],

                'contact_number' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | Create Enforcer Profile Using EXISTING User
            |--------------------------------------------------------------------------
            */

            DB::transaction(function () use ($request, $user) {

                Enforcer::create([
                    /*
                    |--------------------------------------------------------------------------
                    | IMPORTANT:
                    | This is the EXISTING User ID.
                    |
                    | No new User::create() is performed here.
                    |--------------------------------------------------------------------------
                    */
                    'user_id' => $user->id,

                    'badge_number' => $request->badge_number,

                    'first_name' => $request->first_name,

                    'middle_name' => $request->middle_name,

                    'last_name' => $request->last_name,

                    'contact_number' => $request->contact_number,

                    'email' => null,

                    'position' => $request->position,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Update Existing User Account
                |--------------------------------------------------------------------------
                */

                $user->update([
                    'name' => trim(
                        $request->first_name . ' ' . $request->last_name
                    ),

                    'username' => $request->username,
                ]);
            });

            return redirect()
                ->route('enforcers.index')
                ->with(
                    'success',
                    'Enforcer profile completed successfully.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CASE 2:
        | Existing Enforcer Profile
        |--------------------------------------------------------------------------
        */

        $enforcerModel = Enforcer::with('user')
            ->findOrFail($enforcer);

        /*
        |--------------------------------------------------------------------------
        | Validate Existing Enforcer
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'badge_number' => [
                'nullable',
                'string',
                Rule::unique('enforcers', 'badge_number')
                    ->ignore($enforcerModel->id),
            ],

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

            'position' => [
                'required',
                Rule::in([
                    'Traffic Enforcer',
                    'Traffic Aide',
                ]),
            ],

            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')
                    ->ignore(
                        $enforcerModel->user_id
                    ),
            ],

            'contact_number' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update Existing Enforcer + Existing User
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($request, $enforcerModel) {

            /*
            |--------------------------------------------------------------------------
            | Update Enforcer Profile
            |--------------------------------------------------------------------------
            */

            $enforcerModel->update([
                'badge_number' => $request->badge_number,

                'first_name' => $request->first_name,

                'middle_name' => $request->middle_name,

                'last_name' => $request->last_name,

                'contact_number' => $request->contact_number,

                'email' => null,

                'position' => $request->position,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Linked User Account
            |--------------------------------------------------------------------------
            */

            if ($enforcerModel->user) {
                $enforcerModel->user->update([
                    'name' => trim(
                        $request->first_name . ' ' . $request->last_name
                    ),

                    'username' => $request->username,
                ]);
            }
        });

        return redirect()
            ->route('enforcers.index')
            ->with(
                'success',
                'Enforcer updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Enforcer
    |--------------------------------------------------------------------------
    */

    /**
     * Delete an Enforcer and their linked User account.
     */
    public function destroy(Enforcer $enforcer)
    {
        DB::transaction(function () use ($enforcer) {

            /*
            |--------------------------------------------------------------------------
            | Delete Linked User Account
            |--------------------------------------------------------------------------
            */

            if ($enforcer->user) {
                $enforcer->user->delete();
            }

            /*
            |--------------------------------------------------------------------------
            | Delete Enforcer Profile
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

    /*
    |--------------------------------------------------------------------------
    | Enforcer Password Management
    |--------------------------------------------------------------------------
    */

    /**
     * Reset the password of an Enforcer's account.
     */
    public function resetPassword(
        Request $request,
        Enforcer $enforcer
    ) {
        $request->validate([
            'password' => [
                'required',
                'min:8',
                'confirmed',
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
            'password' => Hash::make(
                $request->password
            ),
        ]);

        return back()->with(
            'success',
            'Password reset successfully.'
        );
    }
}