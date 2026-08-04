@extends('layouts.bplo')


@section('title', 'BPLO Dashboard')


@section('content')


<div class="bplo-dashboard">


    {{-- HEADER --}}
    <div class="bplo-header">

        <h1>
            BPLO Dashboard
        </h1>


        <p>
            Traffic Violation Management and Monitoring
        </p>

    </div>




    {{-- SUMMARY CARDS --}}
    <div class="bplo-cards">


        <div class="bplo-card">

            <h5>
                Total Violations Received
            </h5>


            <h2>
                {{ $totalViolations }}
            </h2>

        </div>





        <div class="bplo-card pending">

            <h5>
                Pending Verification
            </h5>


            <h2>
                {{ $pendingViolations }}
            </h2>

        </div>





        <div class="bplo-card completed">

            <h5>
                Completed Violations
            </h5>


            <h2>
                {{ $reviewedViolations }}
            </h2>

        </div>



    </div>







    {{-- VIOLATION PREVIEW --}}
    <div class="bplo-table-card">


        <div class="table-header">


            <h3>
                Violation Review
            </h3>


        </div>





        <div class="table-responsive">


            <table>


                <thead>


                    <tr>


                        <th>
                            Ticket ID
                        </th>


                        <th>
                            Violator
                        </th>


                        <th>
                            Vehicle
                        </th>


                        <th>
                            Violation Type
                        </th>


                        <th>
                            Officer
                        </th>


                        <th>
                            Date & Time
                        </th>


                        <th>
                            Location
                        </th>


                        <th>
                            Remarks
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



                @forelse($recentViolations as $violation)


                    <tr>


                        {{-- Ticket Number --}}
                        <td>

                            {{ $violation->ticket_number }}

                        </td>





                        {{-- Driver --}}
                        <td>


                            @if($violation->driver)


                                {{ $violation->driver->first_name }}

                                {{ $violation->driver->last_name }}


                            @else


                                N/A


                            @endif


                        </td>





                        {{-- Vehicle --}}
                        <td>


                            @if($violation->vehicle)


                                <strong>

                                    {{ $violation->vehicle->plate_number }}

                                </strong>


                                <br>


                                <small>


                                    {{ $violation->vehicle->brand }}

                                    {{ $violation->vehicle->model }}


                                </small>


                            @else


                                N/A


                            @endif


                        </td>






                        {{-- Violation Type --}}
                        <td>


                            {{ $violation->violationType->name ?? 'N/A' }}


                        </td>







                        {{-- Officer --}}
                        <td>


                            {{ $violation->user->name ?? 'N/A' }}


                        </td>







                        {{-- Date and Time --}}
                        <td>


                            {{ \Carbon\Carbon::parse($violation->violation_date)->format('M d, Y') }}


                            <br>


                            <small>


                                {{ \Carbon\Carbon::parse($violation->violation_time)->format('h:i A') }}


                            </small>


                        </td>







                        {{-- Location --}}
                        <td>


                            {{ $violation->location ?? 'N/A' }}


                        </td>








                        {{-- Remarks --}}
                        <td>


                            {{ $violation->remarks ?? 'No remarks' }}


                        </td>








                        {{-- Status --}}
                        <td>


                            <span class="status {{ strtolower($violation->status) }}">


                                {{ $violation->status }}


                            </span>


                        </td>








                        {{-- Action --}}
                        <td>


                            <a href="#" class="view-btn">


                                <i class="fa fa-eye"></i>


                            </a>


                        </td>





                    </tr>




                @empty



                    <tr>


                        <td colspan="10" class="text-center">


                            No violation records available.


                        </td>


                    </tr>



                @endforelse



                </tbody>




            </table>



        </div>



    </div>





</div>



@endsection