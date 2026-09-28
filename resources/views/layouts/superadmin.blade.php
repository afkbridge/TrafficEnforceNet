<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'TrafficEnforceNet')
    </title>

    {{-- ========================================================= --}}
    {{-- Bootstrap --}}
    {{-- ========================================================= --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- ========================================================= --}}
    {{-- Google Font --}}
    {{-- ========================================================= --}}
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    {{-- ========================================================= --}}
    {{-- Font Awesome --}}
    {{-- ========================================================= --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    >

    {{-- ========================================================= --}}
    {{-- Vite --}}
    {{-- ========================================================= --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ========================================================= --}}
    {{-- Admin Custom CSS --}}
    {{-- ========================================================= --}}
    <link
        rel="stylesheet"
        href="{{ asset('css/admin.css') }}"
    >

    {{-- ========================================================= --}}
    {{-- Super Admin Styles --}}
    {{-- ========================================================= --}}
    <style>

        /* =========================================================
           GLOBAL
        ========================================================= */

        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f8;
            color: #1e293b;
        }


        /* =========================================================
           SUPER ADMIN NAVBAR
        ========================================================= */

        .superadmin-navbar {
            background: #005fbf;
            min-height: 70px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }


        .superadmin-brand {
            color: #ffffff;
            font-size: 1.2rem;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s ease;
        }


        .superadmin-brand:hover {
            color: #ffffff;
            opacity: 0.9;
        }


        /* =========================================================
           SUPER ADMIN USER INFORMATION
        ========================================================= */

        .superadmin-user {
            color: #ffffff;
            font-size: 0.9rem;
            line-height: 1.3;
        }


        .superadmin-user small {
            opacity: 0.8;
            font-size: 0.75rem;
        }


        /* =========================================================
           NAVIGATION LINKS
        ========================================================= */

        .superadmin-nav-link {
            color: #ffffff;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 6px;
            transition: 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }


        .superadmin-nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }


        /* =========================================================
           MY ACCOUNT LINK
        ========================================================= */

        .superadmin-account-link {
            color: #ffffff;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 6px;
            transition: 0.2s ease;
            display: inline-flex;
            align-items: center;
        }


        .superadmin-account-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }


        .superadmin-account-link.active {
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff;
        }


        /* =========================================================
           MAIN CONTENT
        ========================================================= */

        .superadmin-content {
            padding: 30px;
            min-height: calc(100vh - 70px);
        }


        /* =========================================================
           ROLE BADGES
        ========================================================= */

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            white-space: nowrap;
        }


        /* Super Administrator */

        .role-superadmin {
            background-color: #6f42c1;
            color: #ffffff;
        }


        /* Administrator */

        .role-administrator {
            background-color: #005fbf;
            color: #ffffff;
        }


        /* POSO Enforcer */

        .role-enforcer {
            background-color: #198754;
            color: #ffffff;
        }


        /* BPLO Personnel */

        .role-bplo {
            background-color: #fd7e14;
            color: #ffffff;
        }


        /* Unknown / No Role */

        .role-default {
            background-color: #6c757d;
            color: #ffffff;
        }


        /* =========================================================
           ROLE BADGE ICONS
        ========================================================= */

        .role-badge i {
            font-size: 0.7rem;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 768px) {

            .superadmin-content {
                padding: 20px 15px;
            }


            .superadmin-user {
                display: none;
            }


            .superadmin-brand {
                font-size: 1.05rem;
            }


            .superadmin-account-link,
            .superadmin-nav-link {
                padding: 8px 10px;
            }


            .superadmin-account-link span,
            .superadmin-nav-link span {
                display: none;
            }

        }

    </style>

</head>


<body>

    {{-- ========================================================= --}}
    {{-- SUPER ADMIN NAVIGATION --}}
    {{-- ========================================================= --}}

    <nav class="navbar superadmin-navbar">

        <div class="container-fluid px-4">

            {{-- ================================================= --}}
            {{-- BRAND --}}
            {{-- ================================================= --}}

            <a
                href="{{ route('super-admin.users') }}"
                class="superadmin-brand"
            >
                <i class="fa-solid fa-shield-halved me-2"></i>
                TrafficEnforceNet
            </a>


            {{-- ================================================= --}}
            {{-- RIGHT SIDE --}}
            {{-- ================================================= --}}

            <div class="d-flex align-items-center gap-2">

                {{-- User Information --}}

                <div class="superadmin-user text-end me-2">

                    <div class="fw-semibold">
                        {{ auth()->user()->name }}
                    </div>

                    <small>
                        Super Administrator
                    </small>

                </div>


                {{-- ================================================= --}}
                {{-- MY ACCOUNT --}}
                {{-- ================================================= --}}

                <a
                    href="{{ route('super-admin.profile') }}"
                    class="superadmin-account-link {{ request()->routeIs('super-admin.profile*') ? 'active' : '' }}"
                    title="My Account"
                >
                    <i class="fa-solid fa-user-cog me-1"></i>
                    <span>My Account</span>
                </a>


                {{-- ================================================= --}}
                {{-- LOGOUT --}}
                {{-- ================================================= --}}

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="m-0"
                >

                    @csrf

                    <button
                        type="submit"
                        class="superadmin-nav-link border-0 bg-transparent"
                        title="Logout"
                    >
                        <i class="fa-solid fa-right-from-bracket me-1"></i>
                        <span>Logout</span>
                    </button>

                </form>

            </div>

        </div>

    </nav>


    {{-- ========================================================= --}}
    {{-- MAIN CONTENT --}}
    {{-- ========================================================= --}}

    <main class="superadmin-content">

        @yield('content')

    </main>


    {{-- ========================================================= --}}
    {{-- BOOTSTRAP JS --}}
    {{-- ========================================================= --}}

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>


    {{-- ========================================================= --}}
    {{-- PAGE SPECIFIC SCRIPTS --}}
    {{-- ========================================================= --}}

    @yield('scripts')


</body>

</html>