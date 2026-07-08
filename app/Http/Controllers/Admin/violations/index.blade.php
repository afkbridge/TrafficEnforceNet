@extends('layouts.admin')

@section('title', 'Violation Records')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Violation Records</h2>
            <p class="text-muted">
                Manage all recorded traffic violations.
            </p>
        </div>

        <a href="{{ route('violations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Add Violation
        </a>

    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>

                        <tr>

                            <th>Ticket No.</th>

                            <th>Driver</th>

                            <th>Violation</th>

                            <th>Location</th>

                            <th>Status</th>

                            <th>Date</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($violations as $violation)

                            <tr>

                                <td>{{ $violation->ticket_number }}</td>

                                <td>{{ $violation->driver->full_name }}</td>

                                <td>{{ $violation->violationType->name }}</td>

                                <td>{{ $violation->location }}</td>

                                <td>{{ $violation->status }}</td>

                                <td>{{ $violation->violation_date }}</td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-5">

                                    No violation records found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection