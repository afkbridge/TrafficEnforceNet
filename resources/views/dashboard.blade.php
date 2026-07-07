@extends('adminlte::page')

@section('title', 'TrafficEnforceNet')

@section('content_header')
    <h1>TrafficEnforceNet Dashboard</h1>
@stop

@section('content')

<div class="row">

<div class="col-md-3">

<div class="small-box bg-info">

<div class="inner">

<h3>0</h3>

<p>Total Violations</p>

</div>

<div class="icon">

<i class="fas fa-file-alt"></i>

</div>

</div>

</div>

<div class="col-md-3">

<div class="small-box bg-success">

<div class="inner">

<h3>0</h3>

<p>Daily Issued Tickets</p>

</div>

<div class="icon">

<i class="fas fa-ticket-alt"></i>

</div>

</div>

</div>

</div>

@endsection