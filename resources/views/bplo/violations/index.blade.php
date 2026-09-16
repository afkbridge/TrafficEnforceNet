@extends('layouts.bplo')

@section('title', 'Violation Review')

@section('content')

<div class="review-page">

    {{-- PAGE HEADER --}}
    <div class="review-page-header">
        <div>
            <h1>Violation Review</h1>
            <p>Review and manage recorded traffic violations</p>
        </div>


        </div>


    {{-- =====================================================
         ALL-TIME VIOLATION COUNTER
    ====================================================== --}}
    <div class="review-total-counter">

        <div class="review-total-counter-info">
            <p>All-Time Total Violations</p>

            <h2>
                {{ $allTimeViolations }}
            </h2>

            <span>
                Total violations recorded since system use
            </span>
        </div>

        <div class="review-total-counter-icon">
            <i class="fa-solid fa-list-check"></i>
        </div>

    </div>


    {{-- STATUS TABS --}}
    <div class="review-tabs">

        <a href="{{ route('bplo.violations.index') }}"
           class="review-tab {{ $status === 'all' ? 'active' : '' }}">
            All
        </a>

        <a href="{{ route('bplo.violations.index', ['status' => 'Pending']) }}"
           class="review-tab {{ $status === 'Pending' ? 'active' : '' }}">
            Pending
        </a>

        <a href="{{ route('bplo.violations.index', ['status' => 'Completed']) }}"
           class="review-tab {{ $status === 'Completed' ? 'active' : '' }}">
            Completed
        </a>

    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="review-success">
            {{ session('success') }}
        </div>
    @endif


    {{-- VALIDATION ERROR --}}
    @if($errors->any())
        <div class="review-error">
            {{ $errors->first() }}
        </div>
    @endif


    {{-- TABLE CARD --}}
    <div class="review-table-card">

        {{-- TABLE HEADER --}}
        <div class="review-table-header">

            <div>
                <h2>Violation Review</h2>
                <p>Manually update the violation status</p>
            </div>


            {{-- SEARCH --}}
            <form
                method="GET"
                action="{{ route('bplo.violations.index') }}"
                class="review-search-form"
            >

                @if($status !== 'all')
                    <input
                        type="hidden"
                        name="status"
                        value="{{ $status }}"
                    >
                @endif

                <input
    type="text"
    name="search"
    id="reviewViolationSearch"
    value="{{ $search ?? '' }}"
    placeholder="Search violation..."
    class="review-search"
    autocomplete="off"
>

            </form>

        </div>


        {{-- TABLE --}}
        <div class="review-table-wrapper">

            <table
    class="review-table"
    id="reviewViolationTable"
>

                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Name</th>
                        <th>Violation Type</th>
                        <th>Officer</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>


                <tbody>

                    @forelse($violations as $violation)

                        @php

                            $driverName = $violation->driver
                                ? trim(
                                    $violation->driver->first_name . ' ' .
                                    $violation->driver->last_name
                                )
                                : 'N/A';

                            $violationName =
                                $violation->violationType->name ?? 'N/A';

                            $officerName =
                                $violation->user->name ?? 'N/A';

                            $formattedDate = $violation->violation_date
                                ? \Carbon\Carbon::parse(
                                    $violation->violation_date
                                )->format('M d, Y')
                                : 'N/A';

                        @endphp


                        <tr
    class="review-violation-row"
    data-status="{{ strtolower($violation->status) }}"
>

                            {{-- TICKET ID --}}
                            <td>
                                {{ $violation->ticket_number }}
                            </td>


                            {{-- DRIVER NAME --}}
                            <td>
                                {{ $driverName }}
                            </td>


                            {{-- VIOLATION TYPE --}}
                            <td>
                                {{ $violationName }}
                            </td>


                            {{-- OFFICER --}}
                            <td>
                                {{ $officerName }}
                            </td>


                            {{-- DATE --}}
                            <td>
                                {{ $formattedDate }}
                            </td>


                            {{-- STATUS --}}
                            <td>

                                <form
                                    method="POST"
                                    action="{{ route('bplo.violations.status', $violation->id) }}"
                                    class="review-status-form"
                                >

                                    @csrf
                                    @method('PATCH')


                                    <select name="status">

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
                                        class="review-save-btn"
                                    >
                                        Save
                                    </button>

                                </form>

                            </td>


                            {{-- ACTION --}}
                            <td>

                                <div class="review-actions">

                                    {{-- VIEW --}}
                                    <button
                                        type="button"
                                        class="review-view-btn violation-view-trigger"

                                        data-ticket="{{ $violation->ticket_number }}"
                                        data-name="{{ $driverName }}"
                                        data-violation="{{ $violationName }}"
                                        data-officer="{{ $officerName }}"
                                        data-date="{{ $formattedDate }}"
                                        data-status="{{ $violation->status }}"
                                    >
                                        View
                                    </button>


                                    {{-- COPY --}}
                                    <button
                                        type="button"
                                        class="review-copy-btn"

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

                        <tr>

                            <td
                                colspan="7"
                                class="review-empty"
                            >
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
     JAVASCRIPT
========================================================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | LIVE SEARCH
    |--------------------------------------------------------------------------
    */

    const reviewSearch =
        document.getElementById('reviewViolationSearch');

    const reviewRows =
        document.querySelectorAll(
            '#reviewViolationTable .review-violation-row'
        );


    function filterReviewTable() {

        if (!reviewSearch) {
            return;
        }


        const searchValue =
            reviewSearch.value
                .toLowerCase()
                .trim();


        reviewRows.forEach(function (row) {

            const rowText =
                row.textContent
                    .toLowerCase()
                    .trim();


            const matchesSearch =
                searchValue === '' ||
                rowText.includes(searchValue);


            row.style.display =
                matchesSearch ? '' : 'none';

        });

    }


    if (reviewSearch) {

        reviewSearch.addEventListener(
            'input',
            filterReviewTable
        );

    }



    /*
    |--------------------------------------------------------------------------
    | COPY BUTTON IN TABLE
    |--------------------------------------------------------------------------
    */  

    const copyButtons =
        document.querySelectorAll('.review-copy-btn');


    copyButtons.forEach(function (button) {

        button.addEventListener('click', async function () {

            const information =
`Ticket ID: ${this.dataset.ticket}
Name: ${this.dataset.name}
Violation Type: ${this.dataset.violation}
Officer: ${this.dataset.officer}
Date: ${this.dataset.date}
Status: ${this.dataset.status}`;


            try {

                await navigator.clipboard.writeText(information);

                const originalText =
                    this.textContent.trim();

                this.textContent = 'Copied!';


                setTimeout(() => {

                    this.textContent =
                        originalText;

                }, 1500);


            } catch (error) {

                alert(
                    'Unable to copy violation information.'
                );

            }

        });

    });



    /*
    |--------------------------------------------------------------------------
    | VIOLATION DETAILS MODAL
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

    viewButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            const ticket =
                document.getElementById('detailTicket');

            const name =
                document.getElementById('detailName');

            const violation =
                document.getElementById('detailViolation');

            const officer =
                document.getElementById('detailOfficer');

            const date =
                document.getElementById('detailDate');

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

            if (status) {
                status.textContent =
                    this.dataset.status;
            }


            if (violationModal) {

                violationModal.classList.add('show');

                document.body.classList.add('modal-open');

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
    | CLICK OUTSIDE MODAL
    |--------------------------------------------------------------------------
    */

    if (violationModal) {

        violationModal.addEventListener(
            'click',
            function (event) {

                if (event.target === violationModal) {

                    closeViolationModal();

                }

            }
        );

    }



    /*
    |--------------------------------------------------------------------------
    | ESC KEY
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        function (event) {

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
            async function () {

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

                    await navigator.clipboard.writeText(
                        information
                    );


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