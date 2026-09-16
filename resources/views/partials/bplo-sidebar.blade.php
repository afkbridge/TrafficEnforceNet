<aside class="bplo-sidebar">

    {{-- ================= BRAND ================= --}}
    <div class="bplo-brand">

        <h2>
            TrafficEnforceNet
        </h2>

        <span>
            BPLO SYSTEM
        </span>

    </div>


    {{-- ================= NAVIGATION ================= --}}
    <nav class="bplo-navigation">


        {{-- DASHBOARD --}}
        <a
            href="{{ route('bplo.dashboard') }}"
            class="bplo-nav-item {{ request()->routeIs('bplo.dashboard') ? 'active' : '' }}"
        >

            <i class="fa-regular fa-square"></i>

            <span>
                Dashboard
            </span>

        </a>


        {{-- VIOLATION REVIEW --}}
        <a
            href="{{ route('bplo.violations.index') }}"
            class="bplo-nav-item {{ request()->routeIs('bplo.violations.*') ? 'active' : '' }}"
        >

            <i class="fa-solid fa-list"></i>

            <span>
                Violation Review
            </span>

        </a>


    </nav>


    {{-- ================= BOTTOM ================= --}}
    <div class="bplo-sidebar-footer">


        {{-- USER --}}
        <div class="bplo-profile">

            <div class="bplo-profile-icon">

                <i class="fa-solid fa-user"></i>

            </div>


            <div class="bplo-profile-info">

                <strong>
                    {{ auth()->user()->name ?? 'BPLO Admin' }}
                </strong>

                <span>
                    {{ auth()->user()->role->name ?? 'BPLO Personnel' }}
                </span>

            </div>

        </div>


        {{-- LOGOUT --}}
        <form
            method="POST"
            action="{{ route('logout') }}"
        >

            @csrf

            <button
                type="submit"
                class="bplo-logout-btn"
            >

                <i class="fa-solid fa-arrow-right-from-bracket"></i>

                <span>
                    Logout
                </span>

            </button>

        </form>


    </div>

</aside>