<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
TrafficEnforceNet | Office Portal
</title>


@vite([
'resources/css/app.css',
'resources/js/app.js'
])


</head>


<body class="bg-[#F1F5F9] text-[#1E293B]">


<section class="min-h-screen flex items-center justify-center px-6">


<div class="max-w-5xl w-full">


<div class="bg-white rounded-2xl shadow-lg overflow-hidden">


<!-- Header -->

<div class="bg-[#005fbf] text-white p-10 text-center">


<div class="w-20 h-20 bg-white/20 rounded-full 
mx-auto flex items-center justify-center text-4xl">

🚦

</div>


<h1 class="text-4xl font-bold mt-6">

TrafficEnforceNet

</h1>


<p class="mt-2 text-blue-100">

Office Portal

</p>


<p class="mt-4 text-sm text-blue-100">

Public Order and Safety Office
<br>
Tarlac City

</p>


</div>



<!-- Content -->

<div class="p-10 text-center">


<h2 class="text-2xl font-bold">

Authorized Personnel Access

</h2>


<p class="mt-3 text-[#64748B]">

This portal is intended for authorized POSO administrators,
traffic enforcers, and personnel responsible for traffic
violation management.

</p>



<a href="{{ route('login') }}"

class="inline-block mt-8 bg-[#005fbf]
text-white px-10 py-3 rounded-xl
font-semibold hover:bg-[#004a99]
transition">


Proceed to Login


</a>


</div>



<!-- Modules -->

<div class="bg-[#F1F5F9] p-8">


<h3 class="text-center font-bold text-xl mb-6">

System Modules

</h3>


<div class="grid md:grid-cols-3 gap-5">



<div class="bg-white rounded-xl p-5 shadow-sm">

<h4 class="font-bold text-[#005fbf]">

Violation Management

</h4>

<p class="text-sm mt-2 text-[#64748B]">

Record and manage traffic violation information.

</p>

</div>



<div class="bg-white rounded-xl p-5 shadow-sm">

<h4 class="font-bold text-[#005fbf]">

Enforcer Operations

</h4>

<p class="text-sm mt-2 text-[#64748B]">

Monitor authorized traffic personnel.

</p>

</div>



<div class="bg-white rounded-xl p-5 shadow-sm">

<h4 class="font-bold text-[#005fbf]">

Monitoring Dashboard

</h4>

<p class="text-sm mt-2 text-[#64748B]">

View system records and reports.

</p>

</div>



</div>


</div>


</div>


<p class="text-center text-sm text-gray-500 mt-6">

Restricted access. Authorized personnel only.

</p>


</div>


</section>


</body>

</html>