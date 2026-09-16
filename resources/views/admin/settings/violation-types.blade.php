@extends('layouts.admin')

@section('title', 'Violation Management')

@section('content')

<div class="container-fluid">

    <div class="page-header mb-4">
        <h2 class="fw-bold mb-1">Violation Management</h2>
        <p class="text-muted mb-0">
            Manage the violation types available in TrafficEnforceNet.
        </p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Add Violation --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-plus-circle me-2"></i>
                Add Violation Type
            </h5>
        </div>

        <div class="card-body">

            <form action="{{ route('admin.violation-types.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">
                            Violation Name
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               placeholder="Enter violation name"
                               value="{{ old('name') }}"
                               required>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">
                            Description
                        </label>

                        <input type="text"
                               name="description"
                               class="form-control"
                               placeholder="Enter description"
                               value="{{ old('description') }}">
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-plus me-1"></i>
                            Add
                        </button>
                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- Violation List --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-list me-2"></i>
                Violation Types
            </h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th class="px-4">#</th>
                            <th>Violation</th>
                            <th>Description</th>
                            <th>Records</th>
                            <th class="text-end px-4">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($violationTypes as $violationType)

                            <tr>

                                <td class="px-4">
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <span class="fw-semibold">
                                        {{ $violationType->name }}
                                    </span>
                                </td>

                                <td>
                                    <span class="text-muted">
                                        {{ $violationType->description ?: 'No description' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $violationType->violations_count }}
                                    </span>
                                </td>

                                <td class="text-end px-4">

                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editViolation{{ $violationType->id }}">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>

                                    @if($violationType->violations_count == 0)

                                        <form action="{{ route('admin.violation-types.destroy', $violationType) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this violation type?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                                Delete
                                            </button>

                                        </form>

                                    @else

                                        <button type="button"
                                                class="btn btn-sm btn-outline-secondary"
                                                disabled
                                                title="This violation type is already used in violation records.">
                                            <i class="fas fa-lock"></i>
                                            Delete
                                        </button>

                                    @endif

                                </td>

                            </tr>

                            {{-- Edit Modal --}}
                            <div class="modal fade"
                                 id="editViolation{{ $violationType->id }}"
                                 tabindex="-1"
                                 aria-hidden="true">

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title fw-bold">
                                                Edit Violation Type
                                            </h5>

                                            <button type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"></button>

                                        </div>

                                        <form action="{{ route('admin.violation-types.update', $violationType) }}"
                                              method="POST">

                                            @csrf
                                            @method('PUT')

                                            <div class="modal-body">

                                                <div class="mb-3">

                                                    <label class="form-label fw-semibold">
                                                        Violation Name
                                                    </label>

                                                    <input type="text"
                                                           name="name"
                                                           class="form-control"
                                                           value="{{ $violationType->name }}"
                                                           required>

                                                </div>

                                                <div class="mb-3">

                                                    <label class="form-label fw-semibold">
                                                        Description
                                                    </label>

                                                    <textarea name="description"
                                                              class="form-control"
                                                              rows="3">{{ $violationType->description }}</textarea>

                                                </div>

                                            </div>

                                            <div class="modal-footer">

                                                <button type="button"
                                                        class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                    Cancel
                                                </button>

                                                <button type="submit"
                                                        class="btn btn-primary">
                                                    <i class="fas fa-save me-1"></i>
                                                    Save Changes
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <tr>
                                <td colspan="5"
                                    class="text-center py-4 text-muted">
                                    No violation types found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="mt-3">
        <a href="{{ route('admin.settings') }}"
           class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Back to Settings
        </a>
    </div>

</div>

@endsection