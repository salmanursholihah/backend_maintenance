<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">

        <div class="sidebar-brand">
            <a href="{{ url('/dashboard') }}">Maintenance</a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ url('/dashboard') }}">MT</a>
        </div>

        <ul class="sidebar-menu">

            <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <a href="{{ url('/dashboard') }}" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="{{ request()->is('customers') ? 'active' : '' }}">
                <a href="{{ url('/customers') }}" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
            </li>

            <li class="{{ request()->is('technicians') ? 'active' : '' }}">
                <a href="{{ url('/technicians') }}" class="nav-link">
                    <i class="fas fa-user-cog"></i>
                    <span>Technicians</span>
                </a>
            </li>

            <li class="{{ request()->is('bookings*') ? 'active' : '' }}">
                <a href="{{ url('/bookings') }}" class="nav-link">
                    <i class="fas fa-calendar"></i>
                    <span>Bookings</span>
                </a>
            </li>

            <li class="{{ request()->is('services') ? 'active' : '' }}">
                <a href="{{ url('/services') }}" class="nav-link">
                    <i class="fas fa-tools"></i>
                    <span>Services</span>
                </a>
            </li>

            <li class="{{ request()->is('payments') ? 'active' : '' }}">
                <a href="{{ url('/payments') }}" class="nav-link">
                    <i class="fas fa-money-bill"></i>
                    <span>Payments</span>
                </a>
            </li>

            <li class="{{ request()->is('reports') ? 'active' : '' }}">
                <a href="{{ url('/reports') }}" class="nav-link">
                    <i class="fas fa-file-alt"></i>
                    <span>Reports</span>
                </a>
            </li>

            <li class="{{ request()->is('notifications') ? 'active' : '' }}">
                <a href="{{ url('/notifications') }}" class="nav-link">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                </a>
            </li>

        </ul>

        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-danger btn-lg btn-block">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>

    </aside>
</div>
