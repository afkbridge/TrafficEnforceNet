@extends('layouts.admin')

@section('title','Dashboard')

@section('content')

<div class="container-fluid">


<!-- HEADER -->

<div class="dashboard-header mb-4">

    <h2 class="page-title">
        Dashboard
    </h2>

    <p class="page-subtitle">
        Monitor traffic violations and enforcement activities.
    </p>

</div>



<!-- SUMMARY CARDS -->

<div class="row g-3 mb-4">


<div class="col-lg-3 col-md-6">

<div class="dashboard-card stat-card">

<div>

<span class="stat-title">
Total Violations
</span>

<h3 class="stat-number">
    {{ $totalViolations }}
</h3>

</div>


<div class="card-icon blue">

<i class="fas fa-file-circle-exclamation"></i>

</div>

</div>

</div>



<div class="col-lg-3 col-md-6">

<div class="dashboard-card stat-card">

<div>

<span class="stat-title">
Today's Tickets
</span>

<h3 class="stat-number">
    {{ $todayTickets }}
</h3>

</div>


<div class="card-icon green">

<i class="fas fa-ticket"></i>

</div>

</div>

</div>



<div class="col-lg-3 col-md-6">

<div class="dashboard-card stat-card">

<div>

<span class="stat-title">
Pending Cases
</span>

<h3 class="stat-number">
    {{ $pendingCases }}
</h3>

</div>


<div class="card-icon orange">

<i class="fas fa-hourglass-half"></i>

</div>

</div>

</div>



<div class="col-lg-3 col-md-6">

<div class="dashboard-card stat-card">

<div>

<span class="stat-title">
Active Enforcers
</span>

<h3 class="stat-number">
    {{ $activeEnforcers }}
</h3>

</div>


<div class="card-icon purple">

<i class="fas fa-user-shield"></i>

</div>

</div>

</div>


</div>





<!-- CHARTS -->

<div class="row g-3 mb-4">


<div class="col-lg-8">


<div class="dashboard-card chart-card">


<div class="section-header">

<h5>
Monthly Violations
</h5>

</div>


<canvas id="monthlyChart"></canvas>


</div>


</div>





<div class="col-lg-4">


<div class="dashboard-card chart-card">


<div class="section-header">

<h5>
Violation Distribution
</h5>

</div>


<canvas id="distributionChart"></canvas>


</div>


</div>



</div>






<!-- MAP AND TABLE -->


<div class="row g-3">


<div class="col-lg-5">


<div class="dashboard-card map-card">


<div class="section-header">

<h5>
Violation Heat Map
</h5>

</div>



<div id="map">

Heat Map Placeholder


</div>



</div>


</div>







<div class="col-lg-7">


<div class="dashboard-card table-card">


<div class="section-header">


<h5>
Recent Violation Records
</h5>


</div>



<div class="table-responsive">


<table class="table">


<thead>

<tr>

<th>Ticket No.</th>

<th>Violator</th>

<th>Violation</th>

<th>Status</th>

<th>Date</th>

</tr>

</thead>


<tbody>


<tr>

<td colspan="5" class="text-center py-5">

No records found.

</td>

</tr>


</tbody>


</table>



</div>


</div>


</div>


</div>



</div>


@endsection