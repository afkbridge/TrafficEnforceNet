@extends('layouts.bplo')

@section('title', 'BPLO Dashboard')

@section('content')

<div class="bplo-dashboard">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}
    <div class="bplo-page-header">

        <div>
            <h1>BPLO Dashboard</h1>

            <p>
                Traffic Violation Management and Monitoring
            </p>
        </div>

    </div>


    {{-- =====================================================
         SUMMARY CARDS
    ====================================================== --}}
    <div class="bplo-summary">

        {{-- TOTAL --}}
        <div class="summary-card total-card">

            <h5>
                {{ strtoupper(now()->format('F Y')) }}
            </h5>

            <p>Total Violations</p>

            <h2>
                {{ $totalViolations }}
            </h2>

        </div>


        {{-- PENDING --}}
        <div class="summary-card pending-card">

            <p>Pending Review</p>

            <h2>
                {{ $pendingViolations }}
            </h2>

        </div>


        {{-- COMPLETED --}}
        <div class="summary-card completed-card">

            <p>Completed</p>

            <h2>
                {{ $reviewedViolations }}
            </h2>

        </div>

    </div>


    {{-- =====================================================
         VIOLATION RECORDS
    ====================================================== --}}
    <div
        class="violation-card"
        id="violationRecords"
    >

        {{-- TABLE HEADER --}}
        <div class="violation-card-header">

            <div class="violation-title">

                <h3>
                    Violation Records
                </h3>

                <p>
                    Recently recorded traffic violations
                </p>

            </div>

{{-- DATE FILTER --}}
<form
    method="GET"
    action="{{ route('bplo.dashboard') }}"
    class="dashboard-date-filter"
>
    <button
        type="submit"
        class="date-filter-btn"
    >
        <i class="fa-solid fa-filter"></i>
        <span>Filter</span>
    </button>

    <select
        name="date_filter"
        id="dateFilter"
        class="date-filter-select"
    >
        <option value="all" {{ $dateFilter === 'all' ? 'selected' : '' }}>
            All Dates
        </option>

        <option value="today" {{ $dateFilter === 'today' ? 'selected' : '' }}>
            Today
        </option>

        <option value="yesterday" {{ $dateFilter === 'yesterday' ? 'selected' : '' }}>
            Yesterday
        </option>

        <option value="this_week" {{ $dateFilter === 'this_week' ? 'selected' : '' }}>
            This Week
        </option>

        <option value="this_month" {{ $dateFilter === 'this_month' ? 'selected' : '' }}>
            This Month
        </option>

        <option value="specific_date" {{ $dateFilter === 'specific_date' ? 'selected' : '' }}>
            Specific Date
        </option>
    </select>

    <input
        type="date"
        name="specific_date"
        id="specificDate"
        class="specific-date-input"
        value="{{ $specificDate ?? '' }}"
        title="Specific Date"
    >

    <a
        href="{{ route('bplo.dashboard') }}"
        class="date-reset-btn"
    >
        <i class="fa-solid fa-rotate-left"></i>
        <span>Reset</span>
    </a>
</form>
            {{-- SEARCH + FILTER --}}
            <div class="violation-tools">

                <input
                    type="text"
                    id="violationSearch"
                    placeholder="Search violation..."
                >

                <select id="statusFilter">

                    <option value="all">
                        All Status
                    </option>

                    <option value="pending">
                        Pending
                    </option>

                    <option value="completed">
                        Completed
                    </option>

                </select>

            </div>

        </div>


        {{-- TABLE --}}
        <div class="violation-table-wrapper">

            <table
                class="violation-table"
                id="violationTable"
            >

                <thead>

       <tr>
    <th>Ticket ID</th>
    <th>Violator</th>
    <th>Vehicle</th>
    <th>Violation Type</th>
    <th>Officer</th>
    <th>Date & Time</th>
    <th>Location</th>
    <th>Remarks</th>
    <th>Status</th>
    <th>Action</th>
</tr>

                </thead>


                <tbody>

                @forelse($recentViolations as $violation)

                    @php

                        $driverName = $violation->driver
                            ? trim(
                                $violation->driver->first_name
                                . ' ' .
                                $violation->driver->last_name
                            )
                            : 'N/A';


                        $violationName =
                            $violation->violationType->name
                            ?? 'N/A';


                        $officerName =
                            $violation->user->name
                            ?? 'N/A';


                        $formattedDate = $violation->violation_date
    ? \Carbon\Carbon::parse(
        $violation->violation_date
    )->format('M d, Y')
    : 'N/A';

$formattedTime = $violation->violation_time
    ? \Carbon\Carbon::parse(
        $violation->violation_time
    )->format('h:i A')
    : 'N/A';

$vehicleInfo = $violation->vehicle
    ? collect([
        $violation->vehicle->plate_number,
        $violation->vehicle->vehicle_type,
    ])->filter()->implode(' - ')
    : 'N/A';

$vehicleInfo = $vehicleInfo ?: 'N/A';

$locationInfo =
    $violation->location ?: 'N/A';

$remarksInfo =
    $violation->remarks ?: 'N/A';

@endphp


                    <tr
                        data-status="{{ strtolower($violation->status) }}"
                    >

                        {{-- TICKET --}}
<td>
    {{ $violation->ticket_number }}
</td>

{{-- VIOLATOR --}}
<td>
    {{ $driverName }}
</td>

{{-- VEHICLE --}}
<td>
    {{ $vehicleInfo }}
</td>

{{-- VIOLATION TYPE --}}
<td>
    {{ $violationName }}
</td>

{{-- OFFICER --}}
<td>
    {{ $officerName }}
</td>

{{-- DATE & TIME --}}
<td>
    {{ $formattedDate }}
    <br>
    <small>{{ $formattedTime }}</small>
</td>

{{-- LOCATION --}}
<td>
    {{ $locationInfo }}
</td>

{{-- REMARKS --}}
<td>
    {{ $remarksInfo }}
</td>

{{-- STATUS --}}
<td>

    <form
        method="POST"
        action="{{ route('bplo.violations.status', $violation->id) }}"
        class="status-controls"
    >

        @csrf
        @method('PATCH')

        <select
            name="status"
            class="status-select"
        >

            <option
                value="Pending"
                {{ $violation->status === 'Pending' ? 'selected' : '' }}
            >
                Pending
            </option>

            <option
                value="Completed"
                {{ $violation->status === 'Completed' ? 'selected' : '' }}
            >
                Completed
            </option>

        </select>

        <button
            type="submit"
            class="save-status-btn"
        >
            Save
        </button>

    </form>

</td>

                        {{-- ACTION --}}
                        <td>

                            <div class="table-actions">

                                {{-- VIEW --}}
<button
    type="button"
    class="view-violation-btn violation-view-trigger"
    data-ticket="{{ $violation->ticket_number }}"
    data-name="{{ $driverName }}"
    data-vehicle="{{ $vehicleInfo }}"
    data-violation="{{ $violationName }}"
    data-officer="{{ $officerName }}"
    data-date="{{ $formattedDate }}"
    data-time="{{ $formattedTime }}"
    data-location="{{ $locationInfo }}"
    data-remarks="{{ $remarksInfo }}"
    data-status="{{ $violation->status }}"
>
    View
</button>


                                {{-- COPY --}}
                                <button
                                    type="button"
                                    class="copy-violation-btn"
                                    data-ticket="{{ $violation->ticket_number }}"
                                    data-name="{{ $driverName }}"
                                    data-violation="{{ $violationName }}"
                                    data-officer="{{ $officerName }}"
                                    data-date="{{ $formattedDate }}"
                                    data-status="{{ $violation->status }}"
                                >
                                    Copy
                                </button>

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr class="empty-table-row">

                        <td colspan="10">
                            No violation records available.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- =========================================================
     SHARED VIOLATION DETAILS MODAL
========================================================= --}}

@include('partials.bplo-violation-modal')


{{-- =========================================================
     DASHBOARD JAVASCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | DATE FILTER
    |--------------------------------------------------------------------------
    */

    const dashboardDateFilter =
        document.getElementById('dateFilter');

    const dashboardSpecificDate =
        document.getElementById('specificDate');

    const dashboardFilterForm =
        document.querySelector('.dashboard-date-filter');

    if (
        dashboardDateFilter &&
        dashboardSpecificDate &&
        dashboardFilterForm
    ) {

        /*
         * Choosing a date automatically means
         * Specific Date.
         */
        dashboardSpecificDate.addEventListener(
            'input',
            function () {

                if (this.value !== '') {
                    dashboardDateFilter.value = 'specific_date';
                }

            }
        );


        /*
         * Choosing All Dates, Today, Yesterday,
         * This Week, or This Month clears
         * the specific date.
         */
        dashboardDateFilter.addEventListener(
            'change',
            function () {

                if (this.value !== 'specific_date') {
                    dashboardSpecificDate.value = '';
                }

            }
        );


        /*
         * Final check before submitting.
         * If a date exists, force Specific Date.
         */
        dashboardFilterForm.addEventListener(
            'submit',
            function () {

                if (dashboardSpecificDate.value !== '') {
                    dashboardDateFilter.value = 'specific_date';
                }

            }
        );

    }
        /*
    |--------------------------------------------------------------------------
    | SEARCH + STATUS FILTER
    |--------------------------------------------------------------------------
    */

    const searchInput =
        document.getElementById('violationSearch');

    const statusFilter =
        document.getElementById('statusFilter');


    function filterViolationTable() {

        // Get what the user typed
        const searchValue = searchInput
            ? searchInput.value.toLowerCase().trim()
            : '';

        // Get selected status
        const selectedStatus = statusFilter
            ? statusFilter.value.toLowerCase()
            : 'all';


        // Get all actual violation rows
        const rows = document.querySelectorAll(
            '#violationTable tbody tr[data-status]'
        );


        rows.forEach(function (row) {

            /*
             * Search only the displayed violation information.
             * This includes:
             * Ticket ID
             * Driver Name
             * Violation Type
             * Officer
             * Date
             * Status
             */

            const rowText =
                row.textContent.toLowerCase();

            const rowStatus =
                (row.dataset.status || '').toLowerCase();


            // Check search
            const matchesSearch =
                searchValue === '' ||
                rowText.includes(searchValue);


            // Check status filter
            const matchesStatus =
                selectedStatus === 'all' ||
                rowStatus === selectedStatus;


            // Show only matching rows
            if (matchesSearch && matchesStatus) {

                row.style.display = '';

            } else {

                row.style.display = 'none';

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH WHILE TYPING
    |--------------------------------------------------------------------------
    */

    if (searchInput) {

        searchInput.addEventListener(
            'input',
            filterViolationTable
        );

    }


    /*
    |--------------------------------------------------------------------------
    | STATUS FILTER
    |--------------------------------------------------------------------------
    */

    if (statusFilter) {

        statusFilter.addEventListener(
            'change',
            filterViolationTable
        );

    }



    /*
    |--------------------------------------------------------------------------
    | COPY VIOLATION FROM TABLE
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.copy-violation-btn')
        .forEach(function(button) {

            button.addEventListener(
                'click',
                async function() {

                    const information =
`Ticket ID: ${this.dataset.ticket}
Name: ${this.dataset.name}
Violation Type: ${this.dataset.violation}
Officer: ${this.dataset.officer}
Date: ${this.dataset.date}
Status: ${this.dataset.status}`;


                    try {

                        await navigator.clipboard
                            .writeText(information);


                        const originalText =
                            this.textContent.trim();


                        this.textContent =
                            'Copied!';


                        this.classList.add(
                            'copied'
                        );


                        setTimeout(() => {

                            this.textContent =
                                originalText;


                            this.classList.remove(
                                'copied'
                            );

                        }, 1500);


                    } catch (error) {

                        alert(
                            'Unable to copy violation information.'
                        );

                    }

                }
            );

        });



    /*
    |--------------------------------------------------------------------------
    | VIEW VIOLATION DETAILS MODAL
    |--------------------------------------------------------------------------
    */

    const violationModal =
        document.getElementById('violationDetailsModal');

    const viewButtons =
        document.querySelectorAll('.violation-view-trigger');

    const modalClose =
        document.getElementById('violationModalClose');

    const modalX =
        document.getElementById('violationModalX');

    const modalCopy =
        document.getElementById('violationModalCopy');


    /*
    |--------------------------------------------------------------------------
    | CLOSE MODAL FUNCTION
    |--------------------------------------------------------------------------
    */

    function closeViolationModal()
    {
        if (!violationModal) {
            return;
        }

        violationModal.classList.remove('show');

        document.body.classList.remove('modal-open');
    }



    /*
    |--------------------------------------------------------------------------
    | OPEN MODAL
    |--------------------------------------------------------------------------
    */

        viewButtons.forEach(function(button) {

        button.addEventListener('click', function() {

            const ticket =
                document.getElementById('detailTicket');

            const name =
                document.getElementById('detailName');

            const vehicle =
                document.getElementById('detailVehicle');

            const violation =
                document.getElementById('detailViolation');

            const officer =
                document.getElementById('detailOfficer');

            const date =
                document.getElementById('detailDate');

            const time =
                document.getElementById('detailTime');

            const location =
                document.getElementById('detailLocation');

            const remarks =
                document.getElementById('detailRemarks');

            const status =
                document.getElementById('detailStatus');


            if (ticket) {
                ticket.textContent =
                    this.dataset.ticket;
            }

            if (name) {
                name.textContent =
                    this.dataset.name;
            }

            if (vehicle) {
                vehicle.textContent =
                    this.dataset.vehicle;
            }

            if (violation) {
                violation.textContent =
                    this.dataset.violation;
            }

            if (officer) {
                officer.textContent =
                    this.dataset.officer;
            }

            if (date) {
                date.textContent =
                    this.dataset.date;
            }

            if (time) {
                time.textContent =
                    this.dataset.time;
            }

            if (location) {
                location.textContent =
                    this.dataset.location;
            }

            if (remarks) {
                remarks.textContent =
                    this.dataset.remarks;
            }

            if (status) {
                status.textContent =
                    this.dataset.status;
            }


            if (violationModal) {

                violationModal.classList.add('show');

                document.body.classList.add(
                    'modal-open'
                );

            }

        });

    });



    /*
    |--------------------------------------------------------------------------
    | CLOSE BUTTON
    |--------------------------------------------------------------------------
    */

    if (modalClose) {

        modalClose.addEventListener(
            'click',
            closeViolationModal
        );

    }



    /*
    |--------------------------------------------------------------------------
    | X BUTTON
    |--------------------------------------------------------------------------
    */

    if (modalX) {

        modalX.addEventListener(
            'click',
            closeViolationModal
        );

    }



    /*
    |--------------------------------------------------------------------------
    | CLICK OUTSIDE MODAL TO CLOSE
    |--------------------------------------------------------------------------
    */

    if (violationModal) {

        violationModal.addEventListener(
            'click',
            function(event) {

                if (event.target === violationModal) {

                    closeViolationModal();

                }

            }
        );

    }



    /*
    |--------------------------------------------------------------------------
    | ESC KEY TO CLOSE
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        function(event) {

            if (event.key === 'Escape') {

                closeViolationModal();

            }

        }
    );



    /*
    |--------------------------------------------------------------------------
    | COPY INFORMATION FROM MODAL
    |--------------------------------------------------------------------------
    */

    if (modalCopy) {

        modalCopy.addEventListener(
            'click',
            async function() {

                const ticket =
                    document.getElementById('detailTicket')
                        ?.textContent ?? '';

                const name =
                    document.getElementById('detailName')
                        ?.textContent ?? '';

                const violation =
                    document.getElementById('detailViolation')
                        ?.textContent ?? '';

                const officer =
                    document.getElementById('detailOfficer')
                        ?.textContent ?? '';

                const date =
                    document.getElementById('detailDate')
                        ?.textContent ?? '';

                const status =
                    document.getElementById('detailStatus')
                        ?.textContent ?? '';


                const information =
`Ticket ID: ${ticket}
Name: ${name}
Violation Type: ${violation}
Officer: ${officer}
Date: ${date}
Status: ${status}`;


                try {

                    await navigator.clipboard
                        .writeText(information);


                    const originalText =
                        this.textContent.trim();


                    this.textContent =
                        'Copied!';


                    setTimeout(() => {

                        this.textContent =
                            originalText;

                    }, 1500);


                } catch (error) {

                    alert(
                        'Unable to copy violation information.'
                    );

                }

            }
        );

    }


});

</script>

@endsection