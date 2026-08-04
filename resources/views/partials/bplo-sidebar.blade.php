<div class="sidebar">


    <div class="logo">

        <img src="{{ asset('images/logo/logo.png') }}">

        <h2>
            TrafficEnforceNet
        </h2>

        <span>
            BPLO SYSTEM
        </span>

    </div>



    <ul class="menu">


        <li class="{{ request()->routeIs('bplo.dashboard') ? 'active' : '' }}">

            <a href="{{ route('bplo.dashboard') }}">

                <i class="fas fa-house"></i>

                Dashboard

            </a>

        </li>



        <li>

            <a href="#">

                <i class="fas fa-file-lines"></i>

                Violation Review

            </a>

        </li>


    </ul>




    <div class="profile">


        <img src="{{ asset('images/avatars/avatar.png') }}">


        <div class="profile-info">

            <h4>
                {{ Auth::user()->name }}
            </h4>

            <small>
                BPLO Personnel
            </small>


        </div>


    </div>



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