@extends('layouts.superadmin')

@section('title', 'User Management')

@section('content')

<div class="container-fluid px-4 pt-3">


{{-- ========================================================= --}}
{{-- FLASH MESSAGES --}}
{{-- ========================================================= --}}

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>
        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i>
        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">

        <div class="fw-semibold mb-1">
            <i class="fa-solid fa-circle-exclamation me-2"></i>
            Please check the following:
        </div>

        <ul class="mb-0 ps-4">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>

    </div>
@endif


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

    {{-- CREATE ACCOUNT --}}
    <button
        type="button"
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#createAccountModal"
    >
        <i class="fa-solid fa-user-plus me-1"></i>
        Create Account
    </button>

</div>


{{-- ========================================================= --}}
{{-- USER TABLE --}}
{{-- ========================================================= --}}

<div class="card shadow-sm border-0">

    {{-- CARD HEADER --}}
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


    {{-- TABLE --}}
    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th class="px-4">
                            Name
                        </th>

                        <th>
                            Username
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
                            {{-- USERNAME --}}
                            {{-- ================================================= --}}

                            <td>

                                <span class="fw-semibold">
                                    {{ $user->username ?? '-' }}
                                </span>

                            </td>


                            {{-- ================================================= --}}
                            {{-- ROLE --}}
                            {{-- ================================================= --}}

                            <td>

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

                                @if($user->role_id !== 4)

                                    <div class="dropdown">

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            title="Account Actions"
                                        >
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>


                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">


                                            {{-- EDIT --}}
                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editAccountModal{{ $user->id }}"
                                                >
                                                    <i class="fa-solid fa-pen-to-square me-2"></i>
                                                    Edit Account
                                                </button>

                                            </li>


                                            {{-- RESET PASSWORD --}}
                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#resetPasswordModal{{ $user->id }}"
                                                >
                                                    <i class="fa-solid fa-key me-2"></i>
                                                    Reset Password
                                                </button>

                                            </li>


                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>


                                            {{-- ENABLE / DISABLE --}}
                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#statusModal{{ $user->id }}"
                                                >

                                                    @if(strtolower($user->account_status) === 'active')

                                                        <i class="fa-solid fa-user-slash me-2"></i>
                                                        Disable Account

                                                    @else

                                                        <i class="fa-solid fa-user-check me-2"></i>
                                                        Enable Account

                                                    @endif

                                                </button>

                                            </li>


                                            {{-- DELETE --}}
                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item text-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteAccountModal{{ $user->id }}"
                                                >
                                                    <i class="fa-solid fa-trash me-2"></i>
                                                    Delete Account
                                                </button>

                                            </li>

                                        </ul>

                                    </div>

                                @else

                                    <span class="text-muted small">
                                        Current Account
                                    </span>

                                @endif

                            </td>

                        </tr>


                        {{-- ===================================================== --}}
                        {{-- EDIT ACCOUNT MODAL --}}
                        {{-- ===================================================== --}}

                        @if($user->role_id !== 4)

                            <div
                                class="modal fade"
                                id="editAccountModal{{ $user->id }}"
                                tabindex="-1"
                                aria-hidden="true"
                            >

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content">

                                        <form
                                            action="{{ route('super-admin.users.update', $user) }}"
                                            method="POST"
                                        >

                                            @csrf
                                            @method('PUT')


                                            <div class="modal-header">

                                                <h5 class="modal-title">
                                                    <i class="fa-solid fa-user-pen me-2"></i>
                                                    Edit Account
                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"
                                                ></button>

                                            </div>


                                            <div class="modal-body">

                                                <div class="mb-3">

                                                    <label class="form-label fw-semibold">
                                                        Name
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="name"
                                                        class="form-control"
                                                        value="{{ $user->name }}"
                                                        required
                                                    >

                                                </div>


                                                <div class="mb-3">

                                                    <label class="form-label fw-semibold">
                                                        Username
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="username"
                                                        class="form-control"
                                                        value="{{ $user->username }}"
                                                        required
                                                    >

                                                </div>


                                                <div class="mb-3">

                                                    <label class="form-label fw-semibold">
                                                        Role
                                                    </label>

                                                    <select
                                                        name="role_id"
                                                        class="form-select"
                                                        required
                                                    >

                                                        @foreach($roles as $role)

                                                            @if($role->id !== 4)

                                                                <option
                                                                    value="{{ $role->id }}"
                                                                    @selected($user->role_id == $role->id)
                                                                >
                                                                    {{ $role->name }}
                                                                </option>

                                                            @endif

                                                        @endforeach

                                                    </select>

                                                </div>


                                                <div class="mb-0">

                                                    <label class="form-label fw-semibold">
                                                        Account Status
                                                    </label>

                                                    <select
                                                        name="account_status"
                                                        class="form-select"
                                                        required
                                                    >

                                                        <option
                                                            value="Active"
                                                            @selected(strtolower($user->account_status) === 'active')
                                                        >
                                                            Active
                                                        </option>

                                                        <option
                                                            value="Inactive"
                                                            @selected(strtolower($user->account_status) !== 'active')
                                                        >
                                                            Inactive
                                                        </option>

                                                    </select>

                                                </div>

                                            </div>


                                            <div class="modal-footer">

                                                <button
                                                    type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal"
                                                >
                                                    Cancel
                                                </button>

                                                <button
                                                    type="submit"
                                                    class="btn btn-primary"
                                                >
                                                    <i class="fa-solid fa-floppy-disk me-1"></i>
                                                    Save Changes
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- RESET PASSWORD MODAL --}}
                            {{-- ================================================= --}}

                            <div
                                class="modal fade"
                                id="resetPasswordModal{{ $user->id }}"
                                tabindex="-1"
                                aria-hidden="true"
                            >

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content">

                                        <form
                                            action="{{ route('super-admin.users.reset-password', $user) }}"
                                            method="POST"
                                        >

                                            @csrf
                                            @method('PATCH')


                                            <div class="modal-header">

                                                <h5 class="modal-title">
                                                    <i class="fa-solid fa-key me-2"></i>
                                                    Reset Password
                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"
                                                ></button>

                                            </div>


                                            <div class="modal-body">

                                                <p class="mb-0">

                                                    Are you sure you want to reset the password
                                                    for

                                                    <strong>
                                                        {{ $user->name }}
                                                    </strong>?

                                                </p>

                                                <div class="alert alert-warning mt-3 mb-0">

                                                    <i class="fa-solid fa-triangle-exclamation me-2"></i>

                                                    A new temporary password will be generated
                                                    after the reset.

                                                </div>

                                            </div>


                                            <div class="modal-footer">

                                                <button
                                                    type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal"
                                                >
                                                    Cancel
                                                </button>

                                                <button
                                                    type="submit"
                                                    class="btn btn-warning"
                                                >
                                                    <i class="fa-solid fa-key me-1"></i>
                                                    Reset Password
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- STATUS MODAL --}}
                            {{-- ================================================= --}}

                            <div
                                class="modal fade"
                                id="statusModal{{ $user->id }}"
                                tabindex="-1"
                                aria-hidden="true"
                            >

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content">

                                        <form
                                            action="{{ route('super-admin.users.toggle-status', $user) }}"
                                            method="POST"
                                        >

                                            @csrf
                                            @method('PATCH')


                                            <div class="modal-header">

                                                <h5 class="modal-title">

                                                    @if(strtolower($user->account_status) === 'active')

                                                        <i class="fa-solid fa-user-slash me-2"></i>
                                                        Disable Account

                                                    @else

                                                        <i class="fa-solid fa-user-check me-2"></i>
                                                        Enable Account

                                                    @endif

                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"
                                                ></button>

                                            </div>


                                            <div class="modal-body">

                                                @if(strtolower($user->account_status) === 'active')

                                                    <p class="mb-0">

                                                        Are you sure you want to disable

                                                        <strong>
                                                            {{ $user->name }}
                                                        </strong>'s account?

                                                    </p>

                                                    <div class="alert alert-warning mt-3 mb-0">

                                                        The user will no longer be able to use
                                                        the account while it is inactive.

                                                    </div>

                                                @else

                                                    <p class="mb-0">

                                                        Are you sure you want to enable

                                                        <strong>
                                                            {{ $user->name }}
                                                        </strong>'s account?

                                                    </p>

                                                @endif

                                            </div>


                                            <div class="modal-footer">

                                                <button
                                                    type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal"
                                                >
                                                    Cancel
                                                </button>


                                                @if(strtolower($user->account_status) === 'active')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-warning"
                                                    >
                                                        <i class="fa-solid fa-user-slash me-1"></i>
                                                        Disable Account
                                                    </button>

                                                @else

                                                    <button
                                                        type="submit"
                                                        class="btn btn-success"
                                                    >
                                                        <i class="fa-solid fa-user-check me-1"></i>
                                                        Enable Account
                                                    </button>

                                                @endif

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- DELETE MODAL --}}
                            {{-- ================================================= --}}

                            <div
                                class="modal fade"
                                id="deleteAccountModal{{ $user->id }}"
                                tabindex="-1"
                                aria-hidden="true"
                            >

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content">

                                        <form
                                            action="{{ route('super-admin.users.destroy', $user) }}"
                                            method="POST"
                                        >

                                            @csrf
                                            @method('DELETE')


                                            <div class="modal-header">

                                                <h5 class="modal-title text-danger">

                                                    <i class="fa-solid fa-trash me-2"></i>
                                                    Delete Account

                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"
                                                ></button>

                                            </div>


                                            <div class="modal-body">

                                                <p class="mb-0">

                                                    Are you sure you want to permanently delete

                                                    <strong>
                                                        {{ $user->name }}
                                                    </strong>'s account?

                                                </p>

                                                <div class="alert alert-danger mt-3 mb-0">

                                                    <i class="fa-solid fa-triangle-exclamation me-2"></i>

                                                    This action cannot be undone.

                                                </div>

                                            </div>


                                            <div class="modal-footer">

                                                <button
                                                    type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal"
                                                >
                                                    Cancel
                                                </button>

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                >
                                                    <i class="fa-solid fa-trash me-1"></i>
                                                    Delete Account
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @endif

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

{{-- ================================================================= --}}
{{-- CREATE ACCOUNT MODAL --}}
{{-- ================================================================= --}}

<div
    class="modal fade"
    id="createAccountModal"
    tabindex="-1"
    aria-hidden="true"
>


<div class="modal-dialog modal-dialog-centered">

    <div class="modal-content">

        <form
            action="{{ route('super-admin.users.store') }}"
            method="POST"
        >

            @csrf


            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="fa-solid fa-user-plus me-2"></i>
                    Create Account

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <div class="modal-body">

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="Enter full name"
                        value="{{ old('name') }}"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Username
                    </label>

                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        placeholder="Enter username"
                        value="{{ old('username') }}"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Enter password"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Confirm password"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Role
                    </label>

                    <select
                        name="role_id"
                        class="form-select"
                        required
                    >

                        <option value="" selected disabled>
                            Select role
                        </option>

                        @foreach($roles as $role)

                            @if($role->id !== 4)

                                <option
                                    value="{{ $role->id }}"
                                    @selected(old('role_id') == $role->id)
                                >
                                    {{ $role->name }}
                                </option>

                            @endif

                        @endforeach

                    </select>

                </div>


                <div class="mb-0">

                    <label class="form-label fw-semibold">
                        Account Status
                    </label>

                    <select
                        name="account_status"
                        class="form-select"
                        required
                    >

                        <option
                            value="Active"
                            @selected(old('account_status', 'Active') === 'Active')
                        >
                            Active
                        </option>

                        <option
                            value="Inactive"
                            @selected(old('account_status') === 'Inactive')
                        >
                            Inactive
                        </option>

                    </select>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="fa-solid fa-user-plus me-1"></i>
                    Create Account
                </button>

            </div>

        </form>

    </div>

</div>


</div>

{{-- ========================================================= --}}
{{-- REOPEN CREATE MODAL AFTER VALIDATION ERROR --}}
{{-- ========================================================= --}}

@if($errors->any() && old('_form') === 'create') <script>
document.addEventListener('DOMContentLoaded', function () {
const modal = new bootstrap.Modal(
document.getElementById('createAccountModal')
);


        modal.show();
    });
</script>


@endif

@endsection
