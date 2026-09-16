<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;

class UserManagementController extends Controller
{
    public function index()
    {
        if (auth()->user()->role_id !== 4) {
            abort(403);
        }

        $users = User::with('role')
            ->latest()
            ->paginate(10);

        $roles = Role::all();

        return view('superadmin.users.index', compact('users', 'roles'));
    }
}