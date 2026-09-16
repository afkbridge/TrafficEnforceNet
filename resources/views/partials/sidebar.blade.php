<div class="sidebar">

    <!-- ========================= -->
    <!-- Logo -->
    <!-- ========================= -->

    <div class="logo">

        <img src="{{ asset('images/logo/logo.png') }}" alt="TrafficEnforceNet Logo">

        <h2>TrafficEnforceNet</h2>

        <span>POSO Administration</span>

    </div>

    <!-- ========================= -->
    <!-- Navigation -->
    <!-- ========================= -->

    <ul class="menu">

        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fas fa-house"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('violations.index') ? 'active' : '' }}">
            <a href="{{ route('violations.index') }}">
                <i class="fa-solid fa-file-lines"></i>
                <span>Violation Records</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('enforcers.*') ? 'active' : '' }}">
            <a href="{{ route('enforcers.index') }}">
                <i class="fas fa-user-shield"></i>
                <span>Enforcer Management</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">

            <a href="{{ route('admin.reports.index') }}">

                <i class="fas fa-chart-bar"></i>

                <span>Reports</span>

            </a>

        </li>

        <li class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <a href="{{ route('admin.settings') }}">
                <i class="fas fa-gear"></i>
                <span>Settings</span>
            </a>
        </li>

    </ul>

    <!-- ========================= -->
    <!-- Admin Profile -->
    <!-- ========================= -->

    <div class="profile">

        <img src="{{ asset('images/avatars/avatar.png') }}" alt="Admin">

        <div class="profile-info">

            <h4>{{ Auth::user()->name }}</h4>

            <small>POSO Administrator</small>

        </div>

    </div>

    <!-- ========================= -->
    <!-- Logout -->
    <!-- ========================= -->

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
