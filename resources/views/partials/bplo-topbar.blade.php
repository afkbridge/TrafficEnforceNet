{{-- =========================================================
     BPLO TOPBAR
========================================================= --}}

<header class="bplo-topbar">

    <div class="topbar-left">
        {{-- Intentionally empty.
             Dashboard title is displayed in dashboard.blade.php --}}
    </div>


    <div class="topbar-right">

        {{-- ================= NOTIFICATION BELL ================= --}}
        <div class="bplo-notification">

            <button
                type="button"
                class="notification-bell"
                id="notificationBell"
                aria-label="Open notifications"
            >
                <i class="fa-solid fa-bell"></i>
@if(isset($pendingViolations) && $pendingViolations > 0)
    <span
        class="notification-badge"
        id="notificationBadge"
    >
        {{ $pendingViolations > 99 ? '99+' : $pendingViolations }}
    </span>
@endif
            </button>


            {{-- ================= DROPDOWN ================= --}}
            <div
                class="notification-dropdown"
                id="notificationDropdown"
            >

                <div class="notification-dropdown-header">

                    <h4>Notifications</h4>

                    <button
                        type="button"
                        id="markAllRead"
                        class="mark-read-btn"
                    >
                        Mark all as read
                    </button>

                </div>


                <div class="notification-list">

                    @if(isset($recentViolations))

                        @php
                            $notifications = $recentViolations
                                ->where('status', 'Pending')
                                ->take(5);
                        @endphp


                        
@forelse($notifications as $notification)

    @php
        $notificationDriver = $notification->driver
            ? trim(
                $notification->driver->first_name . ' ' .
                $notification->driver->last_name
            )
            : 'N/A';

        $notificationVehicle = $notification->vehicle
            ? collect([
                $notification->vehicle->plate_number,
                $notification->vehicle->vehicle_type,
            ])->filter()->implode(' - ')
            : 'N/A';

        $notificationVehicle = $notificationVehicle ?: 'N/A';

        $notificationViolation =
            $notification->violationType->name ?? 'N/A';

        $notificationDate = $notification->violation_date
            ? \Carbon\Carbon::parse(
                $notification->violation_date
            )->format('M d, Y')
            : 'N/A';

        $notificationTime = $notification->violation_time
            ? \Carbon\Carbon::parse(
                $notification->violation_time
            )->format('h:i A')
            : 'N/A';
    @endphp

    <a
        href="{{ route('bplo.violations.index', [
            'search' => $notification->ticket_number
        ]) }}"
        class="notification-item unread"
    >
        <span class="notification-dot"></span>

        <div class="notification-content">

            <strong>
                {{ $notificationViolation }}
            </strong>

            <p>
                Ticket: {{ $notification->ticket_number }}
            </p>

            <p>
                Violator: {{ $notificationDriver }}
            </p>

            <p>
                Vehicle: {{ $notificationVehicle }}
            </p>

            <small>
                {{ $notificationDate }} • {{ $notificationTime }}
            </small>

        </div>
    </a>

@empty

    <div class="notification-empty">
        <i class="fa-regular fa-bell"></i>
        <p>No new notifications</p>
    </div>

@endforelse

                    @else

                        <div class="notification-empty">
                            <i class="fa-regular fa-bell"></i>

                            <p>
                                No new notifications
                            </p>
                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- ================= CURRENT DATE ================= --}}
        <div class="topbar-date">

            <i class="fa-regular fa-calendar"></i>

            <span>
    {{ now()->format('F d, Y') }}
</span>

        </div>


        {{-- ================= CURRENT USER ================= --}}
        <div class="topbar-user">

            <div class="topbar-avatar">
                <i class="fa-solid fa-user"></i>
            </div>


            <div class="topbar-user-info">

                <strong>
                    {{ auth()->user()->name ?? 'BPLO Admin' }}
                </strong>

                <span>
                    {{ auth()->user()->role->name ?? 'BPLO Personnel' }}
                </span>

            </div>

        </div>

    </div>

</header>


{{-- =========================================================
     NOTIFICATION JAVASCRIPT
========================================================= --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    const bell =
        document.getElementById('notificationBell');

    const dropdown =
        document.getElementById('notificationDropdown');

    const markAllRead =
        document.getElementById('markAllRead');

    const badge =
        document.getElementById('notificationBadge');


    /*
    |--------------------------------------------------------------------------
    | OPEN / CLOSE NOTIFICATION DROPDOWN
    |--------------------------------------------------------------------------
    */

    if (bell && dropdown) {

        bell.addEventListener('click', function (event) {

            event.stopPropagation();

            dropdown.classList.toggle('show');

        });


        dropdown.addEventListener('click', function (event) {

            event.stopPropagation();

        });


        document.addEventListener('click', function () {

            dropdown.classList.remove('show');

        });

    }


    /*
    |--------------------------------------------------------------------------
    | MARK ALL AS READ
    |--------------------------------------------------------------------------
    |
    | For now this changes the interface only.
    | Later we can save read/unread status to the database.
    |
    */

    if (markAllRead) {

        markAllRead.addEventListener('click', function () {

            document
                .querySelectorAll('.notification-item.unread')
                .forEach(function (item) {

                    item.classList.remove('unread');

                });


            if (badge) {
                badge.style.display = 'none';
            }

        });

    }

});
</script>