@extends('layouts.admin')

@section('title', 'Violation Details')

@section('content')

    <div class="container-fluid">


        <div class="page-header mb-4 d-flex justify-content-between align-items-center">

            <div>
                <h2 class="fw-bold">
                    Violation Details
                </h2>

                <p class="text-muted">
                    Review recorded traffic violation information.
                </p>
            </div>


            <div class="d-flex gap-2">

                <a href="{{ route('violations.index') }}" class="btn btn-light rounded-pill">

                    ← Back to Records

                </a>


                <a href="{{ route('violations.edit', $violation->id) }}" class="btn btn-warning rounded-pill">

                    <i class="fa-solid fa-pen"></i>
                    Edit Violation

                </a>

            </div>

        </div>


        <!-- TICKET SUMMARY -->

        <div class="detail-box mb-3">


            <h5 class="section-title">
                Violation Information
            </h5>


            <table class="info-table">

                <tr>

                    <th>Ticket Number</th>

                    <td>
                        {{ $violation->ticket_number }}
                    </td>


                    <th>Date</th>

                    <td>
                        {{ $violation->violation_date }}
                    </td>


                </tr>



                <tr>
                    <th>Violation Type</th>
                    <td>
                        @if ($violation->violationTypes->count())
                            @foreach ($violation->violationTypes as $type)
                                <div>{{ $type->name }}</div>
                            @endforeach
                        @else
                            {{ $violation->violationType->name ?? 'N/A' }}
                        @endif
                    </td>

                    <th>Status</th>

                    <td>
                        <span class="status">

                            {{ $violation->status }}

                        </span>
                    </td>


                </tr>


            </table>


        </div>







        <!-- PERSON / VEHICLE INFORMATION -->

        <div class="detail-box mb-3">


            <h5 class="section-title">
                Recorded Information
            </h5>



            <table class="info-table">


                <tr>

                    <th colspan="2">
                        Driver Information
                    </th>

                </tr>


                <tr>

                    <td>Name</td>

                    <td>
                        {{ $violation->driver->full_name ?? 'N/A' }}
                    </td>

                </tr>



                <tr>

                    <td>License Number</td>

                    <td>
                        {{ $violation->driver->license_number ?? 'N/A' }}
                    </td>

                </tr>

                <tr>
                    <td>Address</td>
                    <td>{{ $violation->driver->address ?? 'N/A' }}</td>
                </tr>


                <tr>

                    <th colspan="2">
                        Vehicle Information
                    </th>

                </tr>



                <tr>

                    <td>Plate Number</td>

                    <td>
                        {{ $violation->vehicle->plate_number ?? 'N/A' }}
                    </td>

                </tr>



                <tr>

                    <td>Vehicle Type</td>

                    <td>
                        {{ $violation->vehicle->vehicle_type ?? 'N/A' }}
                    </td>

                </tr>




                <tr>

                    <th colspan="2">
                        Enforcer Information
                    </th>

                </tr>



                <tr>

                    <td>Encoded By</td>

                    <td>
                        {{ $violation->user->name ?? 'N/A' }}
                    </td>

                </tr>



            </table>


        </div>








        <!-- LOCATION + IMAGE -->

        <div class="row g-3">


            <div class="col-lg-6">


                <div class="detail-box h-100">


                    <h5 class="section-title">
                        Location Information
                    </h5>


                    <table class="info-table">



                        <tr>

                            <td>Location</td>

                            <td>
                                {{ $violation->location ?? 'N/A' }}
                            </td>

                        </tr>


                    </table>


                </div>


            </div>





            <div class="col-lg-6">


                <div class="detail-box">


                    <h5 class="section-title">
                        Evidence Photos
                    </h5>


                    @if ($violation->images && $violation->images->count())


                        <div class="row g-2">


                            @foreach ($violation->images as $image)
                                <div class="col-6">


                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="evidence">


                                </div>
                            @endforeach


                        </div>
                    @else
                        <p class="text-muted">
                            No evidence uploaded.
                        </p>

                    @endif


                </div>


            </div>


        </div>



    </div>




    <style>
        .detail-box {

            background: white;

            border-radius: 20px;

            padding: 22px;

            box-shadow: 0 4px 15px rgba(0, 0, 0, .05);

        }



        .section-title {

            font-size: 17px;

            font-weight: 700;

            margin-bottom: 18px;

        }



        .info-table {

            width: 100%;

            border-collapse: collapse;

        }



        .info-table th {

            background: #f8f9fc;

            font-weight: 700;

        }



        .info-table th,
        .info-table td {

            padding: 12px 15px;

            border-bottom: 1px solid #eeeeee;

        }



        .info-table td:first-child {

            color: #6c757d;

            width: 35%;

        }



        .status {

            background: #fff3cd;

            color: #856404;

            padding: 6px 14px;

            border-radius: 50px;

            font-size: 13px;

        }



        .evidence {

            width: 100%;

            height: 150px;

            object-fit: cover;

            border-radius: 15px;

        }
    </style>


@endsection
