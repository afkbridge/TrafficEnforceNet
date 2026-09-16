@extends('layouts.superadmin')

@section('title', 'User Management')

@section('content')

<div class="container-fluid px-4 pt-3">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="page-title mb-1">
                User Management
            </h2>

            <p class="text-muted mb-0">
                Manage system user accounts and their assigned roles.
            </p>

        </div>

        {{-- Create Account --}}
        <button
            type="button"
            class="btn btn-primary"
        >
            <i class="fa-solid fa-user-plus me-1"></i>
            Create Account
        </button>

    </div>


    {{-- ========================================================= --}}
    {{-- USER TABLE --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm border-0">


        {{-- ===================================================== --}}
        {{-- CARD HEADER --}}
        {{-- ===================================================== --}}

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-0">
                        System Users
                    </h5>

                    <small class="text-muted">
                        Registered accounts in the system
                    </small>

                </div>

                <span class="text-muted small">
                    {{ $users->total() }} user(s)
                </span>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- TABLE --}}
        {{-- ===================================================== --}}

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-4">
                                Name
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Role
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Created
                            </th>

                            <th class="text-end px-4">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($users as $user)

                            <tr>


                                {{-- ================================================= --}}
                                {{-- NAME --}}
                                {{-- ================================================= --}}

                                <td class="px-4">

                                    <div class="fw-semibold">
                                        {{ $user->name }}
                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- EMAIL --}}
                                {{-- ================================================= --}}

                                <td>

                                    <span class="text-muted">
                                        {{ $user->email }}
                                    </span>

                                </td>


                                {{-- ================================================= --}}
                                {{-- ROLE --}}
                                {{-- ================================================= --}}

                                <td>

                                    @php

                                        $roleName = $user->role->name ?? 'No Role';

                                        $roleClass = match ($roleName) {

                                            'Super Administrator'
                                                => 'role-superadmin',

                                            'Administrator'
                                                => 'role-administrator',

                                            'POSO Enforcer'
                                                => 'role-enforcer',

                                            'BPLO Personnel'
                                                => 'role-bplo',

                                            default
                                                => 'role-default',

                                        };

                                    @endphp


                                    <span class="role-badge {{ $roleClass }}">

                                        @if($roleName === 'Super Administrator')

                                            <i class="fa-solid fa-shield-halved"></i>

                                        @elseif($roleName === 'Administrator')

                                            <i class="fa-solid fa-user-gear"></i>

                                        @elseif($roleName === 'POSO Enforcer')

                                            <i class="fa-solid fa-user-shield"></i>

                                        @elseif($roleName === 'BPLO Personnel')

                                            <i class="fa-solid fa-building"></i>

                                        @else

                                            <i class="fa-solid fa-user"></i>

                                        @endif

                                        {{ $roleName }}

                                    </span>

                                </td>


                                {{-- ================================================= --}}
                                {{-- STATUS --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if(strtolower($user->account_status) === 'active')

                                        <span class="badge bg-success">

                                            <i class="fa-solid fa-circle-check me-1"></i>

                                            Active

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            <i class="fa-solid fa-circle-minus me-1"></i>

                                            {{ ucfirst($user->account_status) }}

                                        </span>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- CREATED --}}
                                {{-- ================================================= --}}

                                <td>

                                    <span class="text-muted">

                                        {{ $user->created_at?->format('M d, Y') }}

                                    </span>

                                </td>


                                {{-- ================================================= --}}
                                {{-- ACTIONS --}}
                                {{-- ================================================= --}}

                                <td class="text-end px-4">

                                    @if($user->role->name !== 'Super Administrator')

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-secondary"
                                        >

                                            <i class="fa-solid fa-ellipsis-vertical"></i>

                                        </button>

                                    @else

                                        <span class="text-muted small">
                                            Current Account
                                        </span>

                                    @endif

                                </td>

                            </tr>


                        @empty


                            {{-- ================================================= --}}
                            {{-- EMPTY STATE --}}
                            {{-- ================================================= --}}

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i
                                            class="fa-solid fa-users-slash fa-2x mb-3"
                                        ></i>

                                        <div class="fw-semibold">
                                            No users found.
                                        </div>

                                        <small>
                                            There are currently no registered user accounts.
                                        </small>

                                    </div>

                                </td>

                            </tr>


                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PAGINATION --}}
    {{-- ========================================================= --}}

    @if($users->hasPages())

        <div class="mt-3">

            {{ $users->links() }}

        </div>

    @endif

</div>

@endsection