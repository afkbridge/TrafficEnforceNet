<div class="topbar">

    <div>

        <h2>Dashboard</h2>

        <p>Welcome back, {{ Auth::user()->name }}</p>

    </div>

    <div class="top-actions">

        <input
            type="text"
            placeholder="Search..."
        >

        <button>

            <i class="fas fa-bell"></i>

        </button>

        <img src="{{ asset('images/avatar.png') }}">

    </div>

</div>