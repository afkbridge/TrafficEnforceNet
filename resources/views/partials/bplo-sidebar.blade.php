<div class="sidebar">


<!-- Logo -->

<div class="logo">

    <img src="{{ asset('images/logo/logo.png') }}" alt="TrafficEnforceNet Logo">

    <h2>TrafficEnforceNet</h2>

    <span>BPLO System</span>

</div>

<!-- Navigation -->

<ul class="menu">

    <li class="{{ request()->routeIs('bplo.dashboard') ? 'active' : '' }}">

        <a href="{{ route('bplo.dashboard') }}">

            <i class="fas fa-house"></i>

            <span>Dashboard</span>

        </a>

    </li>

    <li class="{{ request()->routeIs('bplo.violations.*') ? 'active' : '' }}">

        <a href="{{ route('bplo.violations.index') }}">

            <i class="fa-solid fa-file-lines"></i>

            <span>Violation Review</span>

        </a>

    </li>

    <!-- Settings -->

    <li class="{{ request()->routeIs('bplo.settings*') ? 'active' : '' }}">

        <a href="{{ route('bplo.settings') }}">

            <i class="fas fa-gear"></i>

            <span>Settings</span>

        </a>

    </li>

</ul>

<!-- Profile -->

<div class="profile">

    <img src="{{ asset('images/avatars/avatar.png') }}" alt="BPLO Personnel">

    <div class="profile-info">

        <h4>{{ Auth::user()->name }}</h4>

        <small>BPLO Personnel</small>

    </div>

</div>

<!-- Logout -->

<div class="logout">

    <form method="POST" action="{{ route('logout') }}">

        @csrf

        <button type="submit">

            <i class="fas fa-right-from-bracket"></i>

            Logout

        </button>

    </form>

</div>


</div>
