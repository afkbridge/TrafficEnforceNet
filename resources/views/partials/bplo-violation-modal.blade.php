{{-- ============================================= --}}
{{-- BPLO VIOLATION DETAILS MODAL --}}
{{-- Used by Dashboard and Violation Review --}}
{{-- ============================================= --}}

<div class="violation-modal-overlay" id="violationDetailsModal">

    <div class="violation-modal">

        {{-- HEADER --}}
        <div class="violation-modal-header">

            <h2>Violation Details</h2>

            <button
                type="button"
                class="violation-modal-x"
                id="violationModalX"
                aria-label="Close"
            >
                &times;
            </button>

        </div>


        {{-- BODY --}}
        <div class="violation-modal-body">

            <div class="violation-detail-row">
                <span>Ticket ID</span>
                <strong id="detailTicket"></strong>
            </div>

            <div class="violation-detail-row">
                <span>Name</span>
                <strong id="detailName"></strong>
            </div>

            <div class="violation-detail-row">
                <span>Violation Type</span>
                <strong id="detailViolation"></strong>
            </div>

            <div class="violation-detail-row">
                <span>Officer</span>
                <strong id="detailOfficer"></strong>
            </div>

            <div class="violation-detail-row">
                <span>Date</span>
                <strong id="detailDate"></strong>
            </div>

            <div class="violation-detail-row">
                <span>Status</span>
                <strong id="detailStatus"></strong>
            </div>

        </div>


        {{-- FOOTER --}}
        <div class="violation-modal-footer">

            <button
                type="button"
                class="violation-modal-close"
                id="violationModalClose"
            >
                Close
            </button>

            <button
                type="button"
                class="violation-modal-copy"
                id="violationModalCopy"
            >
                Copy Information
            </button>

        </div>

    </div>

</div>