@extends('layouts.admin')

@section('title', 'Violation Records')

@section('content')

    <div class="container-fluid">


        <!-- PAGE HEADER -->
        <!-- PAGE HEADER -->
        <div class="mb-4 d-flex justify-content-between align-items-center">

            <div>
                <h2 class="fw-bold">
                    Violation Records
                </h2>

                <p class="text-muted">
                    Monitor and review recorded traffic violations.
                </p>
            </div>


            <div>

                <a href="{{ route('admin.violations.export', [
                    'filter' => request('filter'),
                    'date' => request('date'),
                ]) }}"
                    class="btn btn-success">

                    <i class="fa-solid fa-file-excel"></i>
                    Export Excel

                </a>

            </div>

        </div>

        <div class="card shadow-sm mb-3">

            <div class="card-body">

                <form method="GET" action="{{ route('violations.index') }}" class="row g-3 align-items-end">


                    <div class="col-md-3">

                        <label class="form-label">
                            Filter
                        </label>

                        <select name="filter" class="form-select">

                            <option value="">
                                All Records
                            </option>

                            <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>
                                Today
                            </option>

                        </select>

                    </div>



                    <div class="col-md-3">

                        <label class="form-label">
                            Specific Date
                        </label>

                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">

                    </div>



                    <div class="col-md-2">

                        <button class="btn btn-primary w-100">

                            <i class="fa-solid fa-filter"></i>
                            Filter

                        </button>

                    </div>



                    <div class="col-md-2">

                        <a href="{{ route('violations.index') }}" class="btn btn-light w-100">

                            Reset

                        </a>

                    </div>


                </form>

            </div>

        </div>

        <!-- TABLE CARD -->
        <div class="card shadow-sm">

            <div class="card-body">


                <div class="table-responsive">

                    <table class="table table-hover align-middle">


                        <thead>

                            <tr>

                                <th>
                                    Ticket No.
                                </th>

                                <th>
                                    Driver
                                </th>

                                <th>
                                    Address
                                </th>

                                <th>
                                    Vehicle
                                </th>

                                <th>
                                    Violation
                                </th>

                                <th>
                                    Location
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>



                        <tbody>


                            @forelse($violations as $violation)
                                <tr>


                                    <td>
                                        {{ $violation->ticket_number }}
                                    </td>



                                    <td>
                                        {{ $violation->driver->full_name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $violation->driver->address ?? 'N/A' }}
                                    </td>

                                    <td>

                                        @if ($violation->vehicle)
                                            {{ $violation->vehicle->plate_number }}
                                        @else
                                            N/A
                                        @endif

                                    </td>



                                    <td>

                                        @if ($violation->violationType)
                                            {{ $violation->violationType->name }}
                                        @else
                                            N/A
                                        @endif

                                    </td>



                                    <td>
                                        {{ $violation->location }}
                                    </td>



                                    <td>
                                        {{ $violation->violation_date }}
                                    </td>



                                    <td>

                                        <span class="badge bg-warning">

                                            {{ $violation->status }}

                                        </span>

                                    </td>



                                    <td>

                                        <a href="{{ route('violations.show', $violation->id) }}"
                                            class="btn btn-sm btn-primary">

                                            View

                                        </a>

                                    </td>


                                </tr>



                            @empty


                                <tr>

                                    <td colspan="9" class="text-center">

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
