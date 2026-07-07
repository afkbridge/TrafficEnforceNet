<div class="sidebar">

    <div class="logo">

        <img src="{{ asset('images/logo.png') }}" alt="Logo">

        <h2>TrafficEnforceNet</h2>

        <span>POSO Admin</span>

    </div>

    <ul class="menu">

        <li class="active">

            <i class="fas fa-home"></i>

            <span>Dashboard</span>

        </li>

        <li>

            <i class="fas fa-file-alt"></i>

            <span>Violation Records</span>

        </li>

        <li>

            <i class="fas fa-user-shield"></i>

            <span>Enforcers</span>

        </li>

        <li>

            <i class="fas fa-chart-bar"></i>

            <span>Reports</span>

        </li>

    </ul>

    <div class="profile">

        <img src="{{ asset('images/avatar.png') }}" alt="Avatar">

        <div>

            <h4>{{ Auth::user()->name }}</h4>

            <small>Administrator</small>

        </div>

    </div>

</div>