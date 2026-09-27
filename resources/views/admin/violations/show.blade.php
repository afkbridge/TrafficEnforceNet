@extends('layouts.admin')

@section('title', 'Violation Details')

@section('content')

<div class="container-fluid violation-details-page">

    {{-- PAGE HEADER --}}
    <div class="page-header mb-3 d-flex justify-content-between align-items-center">

        <div>
            <h2 class="page-title mb-1">
                Violation Details
            </h2>

            <p class="page-subtitle mb-0">
                View the recorded citation and violator information.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('violations.index') }}"
               class="btn btn-light btn-sm">

                <i class="fa-solid fa-arrow-left me-1"></i>
                Back

            </a>

            <a href="{{ route('violations.edit', $violation->id) }}"
               class="btn btn-warning btn-sm">

                <i class="fa-solid fa-pen me-1"></i>
                Edit

            </a>

        </div>

    </div>


    {{-- TICKET NUMBER / STATUS --}}
    <div class="detail-card mb-3">

        <div class="ticket-header">

            <div>

                <div class="label-text">
                    Citation Ticket Number
                </div>

                <div class="ticket-number">
                    {{ $violation->ticket_number ?? 'N/A' }}
                </div>

            </div>

            <div class="text-end">

                <div class="label-text">
                    Status
                </div>

                <span class="status-badge
                    {{ strtolower($violation->status ?? '') === 'settled'
                        ? 'status-settled'
                        : 'status-pending' }}">

                    {{ $violation->status ?? 'N/A' }}

                </span>

            </div>

        </div>

    </div>


    {{-- VIOLATOR INFORMATION --}}
    <div class="detail-card mb-3">

        <div class="section-header">

            <div>

                <h5 class="section-title mb-0">

                    <i class="fa-solid fa-user me-2"></i>

                    Violator Information

                </h5>

                <span class="section-subtitle">
                    Personal and license information of the violator.
                </span>

            </div>

        </div>


        <div class="info-grid">

            {{-- LAST NAME --}}
            <div class="info-item">

                <span class="info-label">
                    Last Name
                </span>

                <span class="info-value">
                    {{ $violation->driver->last_name ?? 'N/A' }}
                </span>

            </div>


            {{-- FIRST NAME --}}
            <div class="info-item">

                <span class="info-label">
                    First Name
                </span>

                <span class="info-value">
                    {{ $violation->driver->first_name ?? 'N/A' }}
                </span>

            </div>


            {{-- MIDDLE NAME --}}
            <div class="info-item">

                <span class="info-label">
                    Middle Name
                </span>

                <span class="info-value">
                    {{ $violation->driver->middle_name ?? 'N/A' }}
                </span>

            </div>


            {{-- LICENSE NUMBER --}}
            <div class="info-item">

                <span class="info-label">
                    License Number
                </span>

                <span class="info-value license-value">
                    {{ $violation->driver->license_number ?? 'N/A' }}
                </span>

            </div>


            {{-- HOME ADDRESS --}}
            <div class="info-item info-item-full">

                <span class="info-label">
                    Home Address
                </span>

                <span class="info-value">
                    {{ $violation->driver->address ?? 'N/A' }}
                </span>

            </div>


            {{-- BIRTH DATE --}}
            <div class="info-item">

                <span class="info-label">
                    Birth Date
                </span>

                <span class="info-value">
                    {{ $violation->driver->birth_date ?? 'N/A' }}
                </span>

            </div>

        </div>

    </div>


    {{-- VIOLATION INFORMATION --}}
    <div class="detail-card mb-3">

        <div class="section-header">

            <div>

                <h5 class="section-title mb-0">

                    <i class="fa-solid fa-file-circle-exclamation me-2"></i>

                    Violation Information

                </h5>

                <span class="section-subtitle">
                    Details of the recorded traffic violation.
                </span>

            </div>

        </div>


        {{-- MAIN VIOLATION CONTENT --}}
        <div class="violation-layout">

            {{-- LEFT SIDE --}}
            <div class="violation-main-content">

                {{-- BASIC VIOLATION INFORMATION --}}
                <div class="info-grid violation-basic-info">

                    {{-- DATE --}}
                    <div class="info-item">

                        <span class="info-label">
                            Date of Violation
                        </span>

                        <span class="info-value">
                            {{ $violation->violation_date ?? 'N/A' }}
                        </span>

                    </div>


                    {{-- TIME --}}
                    <div class="info-item">

                        <span class="info-label">
                            Time of Violation
                        </span>

                        <span class="info-value">
                            {{ $violation->violation_time ?? 'N/A' }}
                        </span>

                    </div>


                    {{-- PLATE NUMBER --}}
                    <div class="info-item">

                        <span class="info-label">
                            Plate Number
                        </span>

                        <span class="info-value plate-value">
                            {{ $violation->vehicle->plate_number ?? 'N/A' }}
                        </span>

                    </div>

                </div>


                {{-- PLACE OF VIOLATION --}}
                <div class="location-highlight">

                    <div class="location-icon">

                        <i class="fa-solid fa-location-dot"></i>

                    </div>

                    <div class="location-content">

                        <span class="location-label">
                            Place of Violation
                        </span>

                        <div class="location-value">
                            {{ $violation->location ?? 'Location not available' }}
                        </div>

                    </div>

                </div>


                {{-- VIOLATIONS --}}
                <div class="violations-highlight">

                    <div class="violations-heading">

                        <div class="violations-icon">

                            <i class="fa-solid fa-triangle-exclamation"></i>

                        </div>

                        <div>

                            <div class="violations-title">
                                Violation/s
                            </div>

                            <div class="violations-subtitle">
                                Recorded traffic violation/s for this citation.
                            </div>

                        </div>

                    </div>


                    @php

                        $displayedViolations = [];

                        /*
                        |--------------------------------------------------------------------------
                        | PRIMARY OFFICIAL VIOLATION
                        |--------------------------------------------------------------------------
                        */

                        if ($violation->violationType) {

                            $displayedViolations[] =
                                $violation->violationType->name;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | PRIMARY CUSTOM "OTHER" VIOLATION
                        |--------------------------------------------------------------------------
                        */

                        if (!empty($violation->other_violation)) {

                            $displayedViolations[] =
                                $violation->other_violation;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | ADDITIONAL OFFICIAL VIOLATIONS
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $violation->violationTypes &&
                            $violation->violationTypes->count()
                        ) {

                            foreach (
                                $violation->violationTypes as $type
                            ) {

                                if (
                                    !empty($type->name) &&
                                    !in_array(
                                        $type->name,
                                        $displayedViolations,
                                        true
                                    )
                                ) {

                                    $displayedViolations[] =
                                        $type->name;

                                }

                            }

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | ADDITIONAL CUSTOM "OTHER" VIOLATIONS
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $violation->violationOtherTypes &&
                            $violation->violationOtherTypes->count()
                        ) {

                            foreach (
                                $violation->violationOtherTypes as $otherType
                            ) {

                                if (
                                    !empty($otherType->name) &&
                                    !in_array(
                                        $otherType->name,
                                        $displayedViolations,
                                        true
                                    )
                                ) {

                                    $displayedViolations[] =
                                        $otherType->name;

                                }

                            }

                        }

                    @endphp


                    @if (count($displayedViolations))

                        <div class="violation-items">

                            @foreach ($displayedViolations as $index => $violationName)

                                <div class="violation-highlight-item">

                                    <div class="violation-number">
                                        {{ $index + 1 }}
                                    </div>

                                    <div class="violation-text">
                                        {{ $violationName }}
                                    </div>

                    

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="no-violation">

                            <i class="fa-solid fa-circle-info me-2"></i>

                            No violation recorded.

                        </div>

                    @endif

                </div>

            </div>


            {{-- RIGHT SIDE PHOTO EVIDENCE --}}
            <div class="photo-evidence-box">

                <div class="photo-evidence-header">

                    <div class="photo-evidence-icon">

                        <i class="fa-solid fa-camera"></i>

                    </div>

                    <div>

                        <div class="photo-evidence-title">
                            Photo Evidence
                        </div>

                        <div class="photo-evidence-subtitle">
                            Attached to this citation
                        </div>

                    </div>

                </div>


                @if ($violation->images && $violation->images->count())

                    <div class="photo-evidence-grid">

                        @foreach ($violation->images as $image)

                            <button
                                type="button"
                                class="evidence-photo-button"
                                onclick="openEvidenceModal(
                                    '{{ asset('storage/' . $image->image_path) }}'
                                )"
                            >

                                <img
                                    src="{{ asset('storage/' . $image->image_path) }}"
                                    class="evidence-thumbnail"
                                    alt="Violation evidence"
                                >

                                <span class="photo-view-overlay">

                                    <i class="fa-solid fa-magnifying-glass-plus"></i>

                                </span>

                            </button>

                        @endforeach

                    </div>

                    <div class="photo-evidence-count">

                        <i class="fa-solid fa-images me-1"></i>

                        {{ $violation->images->count() }}
                        {{ $violation->images->count() === 1 ? 'photo' : 'photos' }}

                    </div>

                @else

                    <div class="no-photo-evidence">

                        <div class="no-photo-icon">

                            <i class="fa-regular fa-image"></i>

                        </div>

                        <div class="no-photo-title">
                            No photo evidence
                        </div>

                        <div class="no-photo-text">
                            No evidence photo was attached to this citation.
                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- APPREHENDING OFFICER --}}
    <div class="detail-card mb-3">

        <div class="section-header">

            <div>

                <h5 class="section-title mb-0">

                    <i class="fa-solid fa-user-shield me-2"></i>

                    Apprehending Officer

                </h5>

                <span class="section-subtitle">
                    Personnel associated with the citation record.
                </span>

            </div>

        </div>


        <div class="officer-evidence-layout">

            {{-- OFFICER INFORMATION --}}
            <div class="officer-row">

                <div class="officer-icon">

                    <i class="fa-solid fa-user-shield"></i>

                </div>

                <div>

                    <div class="info-label">
                        Officer
                    </div>

                    <div class="officer-name">
                        {{ $violation->user->name ?? 'N/A' }}
                    </div>

                </div>


        </div>

    </div>


    {{-- REMARKS --}}
    @if (!empty($violation->remarks))

        <div class="detail-card">

            <div class="section-header">

                <div>

                    <h5 class="section-title mb-0">

                        <i class="fa-solid fa-note-sticky me-2"></i>

                        Remarks

                    </h5>

                </div>

            </div>

            <div class="remarks-box">
                {{ $violation->remarks }}
            </div>

        </div>

    @endif

</div>


{{-- =========================================================
     PHOTO PREVIEW MODAL
     ========================================================= --}}

<div
    class="evidence-modal"
    id="evidenceModal"
    onclick="closeEvidenceModal(event)"
>

    <div class="evidence-modal-content">

        <button
            type="button"
            class="evidence-modal-close"
            onclick="closeEvidenceModal()"
        >
            <i class="fa-solid fa-xmark"></i>
        </button>

        <img
            id="evidenceModalImage"
            src=""
            alt="Evidence photo preview"
            class="evidence-modal-image"
        >

    </div>

</div>


<style>

    /* =========================================================
       MAIN PAGE
       ========================================================= */

    .violation-details-page {
        padding-bottom: 30px;
    }


    /* =========================================================
       PAGE HEADER
       ========================================================= */

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #212529;
    }

    .page-subtitle {
        font-size: 14px;
        color: #6c757d;
    }

    .page-header .btn {
        border-radius: 7px;
        padding: 7px 13px;
    }


    /* =========================================================
       DETAIL CARD
       ========================================================= */

    .detail-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 18px 20px;
    }


    /* =========================================================
       TICKET HEADER
       ========================================================= */

    .ticket-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .label-text {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .ticket-number {
        font-size: 19px;
        font-weight: 700;
        color: #212529;
    }


    /* =========================================================
       STATUS
       ========================================================= */

    .status-badge {
        display: inline-block;
        padding: 5px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-settled {
        background: #d1e7dd;
        color: #0f5132;
    }


    /* =========================================================
       SECTION HEADER
       ========================================================= */

    .section-header {
        padding-bottom: 12px;
        margin-bottom: 4px;
        border-bottom: 1px solid #f0f0f0;
    }

    .section-title {
        font-size: 15px;
        font-weight: 700;
        color: #212529;
    }

    .section-title i {
        color: #495057;
        font-size: 14px;
    }

    .section-subtitle {
        display: block;
        margin-top: 3px;
        font-size: 12px;
        color: #8a8f98;
    }


    /* =========================================================
       VIOLATION LAYOUT
       ========================================================= */

    .violation-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 300px;
        gap: 20px;
        align-items: stretch;
    }

    .violation-main-content {
        min-width: 0;
    }


    /* =========================================================
       INFORMATION GRID
       ========================================================= */

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        column-gap: 30px;
        row-gap: 0;
    }

    .info-item {
        padding: 11px 0;
        border-bottom: 1px solid #f1f1f1;
    }

    .info-item-full {
        grid-column: 1 / -1;
    }

    .info-label {
        display: block;
        font-size: 12px;
        color: #737980;
        margin-bottom: 3px;
    }

    .info-value {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #212529;
        word-break: break-word;
    }

    .license-value {
        font-weight: 600;
        letter-spacing: .02em;
    }

    .plate-value {
        font-weight: 700;
        letter-spacing: .04em;
    }


    /* =========================================================
       PLACE OF VIOLATION
       ========================================================= */

    .location-highlight {
        display: flex;
        align-items: center;
        gap: 14px;

        margin-top: 14px;
        padding: 15px 16px;

        background: #f8fafc;

        border: 1px solid #e2e8f0;
        border-left: 4px solid #2563eb;

        border-radius: 9px;
    }

    .location-icon {
        width: 42px;
        height: 42px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #e8f0fe;
        color: #2563eb;

        border-radius: 9px;

        font-size: 18px;
    }

    .location-content {
        min-width: 0;
    }

    .location-label {
        display: block;

        font-size: 11px;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: .05em;

        color: #64748b;

        margin-bottom: 3px;
    }

    .location-value {
        font-size: 15px;
        font-weight: 600;

        line-height: 1.45;

        color: #1e293b;

        word-break: break-word;
    }


    /* =========================================================
       VIOLATIONS HIGHLIGHT
       ========================================================= */

    .violations-highlight {
        margin-top: 16px;

        padding: 17px;

        background: #fffaf0;

        border: 1px solid #f1dfb5;

        border-radius: 10px;
    }

    .violations-heading {
        display: flex;
        align-items: center;
        gap: 11px;

        margin-bottom: 13px;
    }

    .violations-icon {
        width: 40px;
        height: 40px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #fff0c2;
        color: #b7791f;

        border-radius: 8px;

        font-size: 17px;
    }

    .violations-title {
        font-size: 15px;
        font-weight: 700;

        color: #7c4a03;
    }

    .violations-subtitle {
        margin-top: 2px;

        font-size: 12px;

        color: #92754a;
    }


    /* =========================================================
       INDIVIDUAL VIOLATION
       ========================================================= */

    .violation-items {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .violation-highlight-item {
        display: flex;
        align-items: center;

        min-height: 45px;

        padding: 8px 11px;

        background: #ffffff;

        border: 1px solid #eadfca;

        border-radius: 7px;

        transition: .15s ease;
    }

    .violation-highlight-item:hover {
        border-color: #d8c9a9;
    }

    .violation-number {
        width: 28px;
        height: 28px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #f59e0b;
        color: #ffffff;

        border-radius: 50%;

        font-size: 12px;
        font-weight: 700;
    }

    .violation-text {
        flex: 1;

        padding: 0 12px;

        font-size: 14px;
        font-weight: 600;

        color: #292524;

        line-height: 1.4;
    }

    .violation-check {
        color: #16a34a;

        font-size: 15px;

        padding-right: 4px;
    }

    .no-violation {
        padding: 11px 13px;

        background: #ffffff;

        border: 1px dashed #d6d3d1;

        border-radius: 7px;

        font-size: 13px;

        color: #78716c;
    }


    /* =========================================================
       PHOTO EVIDENCE BOX
       ========================================================= */

    .photo-evidence-box {
        display: flex;
        flex-direction: column;

        min-height: 100%;

        padding: 15px;

        background: #f8fafc;

        border: 1px solid #dfe5ec;

        border-radius: 10px;
    }

    .photo-evidence-header {
        display: flex;
        align-items: center;

        gap: 10px;

        padding-bottom: 11px;

        border-bottom: 1px solid #e8edf2;
    }

    .photo-evidence-icon {
        width: 36px;
        height: 36px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        background: #e8f0fe;

        color: #2563eb;

        border-radius: 8px;

        font-size: 15px;
    }

    .photo-evidence-title {
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
    }

    .photo-evidence-subtitle {
        margin-top: 2px;

        font-size: 11px;
        color: #7b8490;
    }


    /* =========================================================
       PHOTO GRID
       ========================================================= */

    .photo-evidence-grid {
        display: grid;

        grid-template-columns: repeat(2, minmax(0, 1fr));

        gap: 8px;

        margin-top: 13px;
    }

    .evidence-photo-button {
        position: relative;

        display: block;

        width: 100%;
        height: 105px;

        padding: 0;

        border: 0;

        background: transparent;

        border-radius: 7px;

        overflow: hidden;

        cursor: pointer;
    }

    .evidence-thumbnail {
        display: block;

        width: 100%;
        height: 100%;

        object-fit: cover;

        border-radius: 7px;

        border: 1px solid #dce2e8;

        transition: transform .2s ease;
    }

    .evidence-photo-button:hover .evidence-thumbnail {
        transform: scale(1.04);
    }

    .photo-view-overlay {
        position: absolute;

        inset: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        background: rgba(0, 0, 0, .42);

        color: #ffffff;

        font-size: 18px;

        opacity: 0;

        transition: opacity .2s ease;
    }

    .evidence-photo-button:hover .photo-view-overlay {
        opacity: 1;
    }

    .photo-evidence-count {
        margin-top: auto;

        padding-top: 11px;

        font-size: 11px;

        color: #6b7280;

        text-align: right;
    }


    /* =========================================================
       NO PHOTO EVIDENCE
       ========================================================= */

    .no-photo-evidence {
        flex: 1;

        display: flex;
        flex-direction: column;

        align-items: center;
        justify-content: center;

        min-height: 180px;

        text-align: center;

        padding: 20px 10px;
    }

    .no-photo-icon {
        width: 45px;
        height: 45px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #eef1f4;

        color: #8a929c;

        border-radius: 50%;

        font-size: 18px;

        margin-bottom: 9px;
    }

    .no-photo-title {
        font-size: 13px;
        font-weight: 600;
        color: #5f6670;
    }

    .no-photo-text {
        max-width: 190px;

        margin-top: 4px;

        font-size: 11px;
        line-height: 1.45;

        color: #9299a2;
    }


    /* =========================================================
       APPREHENDING OFFICER
       ========================================================= */

    .officer-evidence-layout {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;
    }

    .officer-row {
        display: flex;
        align-items: center;
        gap: 12px;

        padding-top: 8px;
    }

    .officer-icon {
        width: 38px;
        height: 38px;

        border-radius: 8px;

        background: #f1f3f5;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #495057;

        font-size: 15px;
    }

    .officer-name {
        font-size: 14px;
        font-weight: 600;

        color: #212529;
    }


    /* =========================================================
       OFFICER EVIDENCE SUMMARY
       ========================================================= */

    .officer-evidence-summary {
        display: flex;
        align-items: center;

        gap: 10px;

        min-width: 210px;

        padding: 10px 13px;

        background: #f8fafc;

        border: 1px solid #e3e7ec;

        border-radius: 8px;
    }

    .officer-evidence-icon {
        width: 34px;
        height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        background: #e8f0fe;

        color: #2563eb;

        border-radius: 7px;

        font-size: 14px;
    }

    .officer-evidence-label {
        font-size: 10px;

        text-transform: uppercase;

        letter-spacing: .04em;

        color: #7a828c;
    }

    .officer-evidence-value {
        margin-top: 2px;

        font-size: 12px;

        font-weight: 600;

        color: #343a40;
    }


    /* =========================================================
       PHOTO PREVIEW MODAL
       ========================================================= */

    .evidence-modal {
        position: fixed;

        inset: 0;

        z-index: 9999;

        display: none;

        align-items: center;
        justify-content: center;

        padding: 25px;

        background: rgba(0, 0, 0, .75);
    }

    .evidence-modal.show {
        display: flex;
    }

    .evidence-modal-content {
        position: relative;

        max-width: 900px;
        max-height: 90vh;

        width: auto;

        background: #ffffff;

        border-radius: 10px;

        padding: 8px;

        box-shadow: 0 15px 50px rgba(0, 0, 0, .3);
    }

    .evidence-modal-image {
        display: block;

        max-width: 100%;

        max-height: 85vh;

        width: auto;
        height: auto;

        object-fit: contain;

        border-radius: 6px;
    }

    .evidence-modal-close {
        position: absolute;

        top: -14px;
        right: -14px;

        width: 34px;
        height: 34px;

        border: 0;

        border-radius: 50%;

        background: #ffffff;

        color: #343a40;

        display: flex;
        align-items: center;
        justify-content: center;

        cursor: pointer;

        box-shadow: 0 3px 12px rgba(0, 0, 0, .25);

        z-index: 2;
    }

    .evidence-modal-close:hover {
        background: #f1f3f5;
    }


    /* =========================================================
       REMARKS
       ========================================================= */

    .remarks-box {
        padding: 12px 14px;

        margin-top: 10px;

        background: #f8f9fa;

        border-radius: 7px;

        font-size: 14px;

        line-height: 1.6;

        color: #343a40;

        white-space: pre-line;
    }


    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 1000px) {

        .violation-layout {
            grid-template-columns: 1fr;
        }

        .photo-evidence-box {
            min-height: auto;
        }

        .photo-evidence-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .evidence-photo-button {
            height: 100px;
        }

    }


    @media (max-width: 768px) {

        .page-header {
            align-items: flex-start !important;

            flex-direction: column;

            gap: 12px;
        }

        .page-header > div:last-child {
            width: 100%;
        }

        .page-header .btn {
            flex: 1;
        }

        .ticket-header {
            align-items: flex-start;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .info-item-full {
            grid-column: auto;
        }

        .detail-card {
            padding: 15px;
        }

        .location-highlight {
            align-items: flex-start;
        }

        .violations-highlight {
            padding: 14px;
        }

        .violation-highlight-item {
            align-items: flex-start;
        }

        .violation-number {
            margin-top: 1px;
        }

        .violation-text {
            font-size: 13px;
        }

        .photo-evidence-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .officer-evidence-layout {
            align-items: flex-start;

            flex-direction: column;
        }

        .officer-evidence-summary {
            width: 100%;
        }

        .evidence-modal {
            padding: 15px;
        }

        .evidence-modal-content {
            max-width: 100%;
        }

    }

</style>


<script>

    /* =========================================================
       OPEN PHOTO EVIDENCE
       ========================================================= */

    function openEvidenceModal(imageUrl) {

        const modal = document.getElementById('evidenceModal');
        const image = document.getElementById('evidenceModalImage');

        if (!modal || !image) {
            return;
        }

        image.src = imageUrl;

        modal.classList.add('show');

        document.body.style.overflow = 'hidden';
    }


    /* =========================================================
       CLOSE PHOTO EVIDENCE
       ========================================================= */

    function closeEvidenceModal(event) {

        const modal = document.getElementById('evidenceModal');

        if (!modal) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | If clicking the dark background, close it.
        |--------------------------------------------------------------------------
        */

        if (
            !event ||
            event.target === modal
        ) {

            modal.classList.remove('show');

            document.body.style.overflow = '';

            const image =
                document.getElementById('evidenceModalImage');

            if (image) {
                image.src = '';
            }

        }

    }


    /* =========================================================
       ESC KEY
       ========================================================= */

    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape') {

            const modal =
                document.getElementById('evidenceModal');

            if (
                modal &&
                modal.classList.contains('show')
            ) {

                closeEvidenceModal();

            }

        }

    });

</script>

@endsection