<div class="topbar">

    <!-- ========================= -->
    <!-- Left Side -->
    <!-- ========================= -->

    <div class="topbar-left">

        <h3>
            POSO Administrator
        </h3>

        <p>
            TrafficEnforceNet Management System
        </p>

    </div>

    <!-- ========================= -->
    <!-- Right Side -->
    <!-- ========================= -->

    <div class="topbar-right">

        <!-- Search -->

        <div class="search-box">

            <i class="fas fa-search"></i>

            <input
                type="text"
                placeholder="Search..."
            >

        </div>

        <!-- Notifications -->

        <button class="top-icon">

            <i class="fas fa-bell"></i>

        </button>

        <!-- Current Date -->

        <div class="current-date">

            {{ now()->format('F d, Y') }}

        </div>

        <!-- User -->

        <div class="user-info">

            <img
                src="{{ asset('images/avatars/avatar.png') }}"
                alt="Admin">

            <div>

                <h6>

                    {{ Auth::user()->name }}

                </h6>

                <small>

                    Administrator

                </small>

            </div>

        </div>

    </div>

</div>