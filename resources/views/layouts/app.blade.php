<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Dashboard') - IPAL Maintenance Admin</title>

    <!-- Stisla CSS -->
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/jquery-toast-plugin/jquery.toast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    @stack('styles')
</head>
<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">

            <!-- Navbar -->
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <div class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
                                collapse-btn"><i data-feather="align-justify"></i></a></li>
                    </ul>
                </div>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block">{{ auth()->user()->name ?? 'Super Admin' }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-title">{{ auth()->user()->email ?? '' }}</div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item has-icon text-left w-100 bg-transparent border-0">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
            <!-- /Navbar -->

            <!-- Sidebar -->
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="{{ route('dashboard') }}">IPAL <span>Admin</span></a>
                    </div>
                    <ul class="sidebar-menu">
                        <li class="menu-header">Utama</li>
                        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('dashboard') }}"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
                        </li>

                        <li class="menu-header">Operasional</li>
                        <li class="{{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('bookings.index') }}"><i class="fas fa-clipboard-list"></i> <span>Booking</span></a>
                        </li>
                        <li class="{{ request()->routeIs('services.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('services.index') }}"><i class="fas fa-tools"></i> <span>Layanan</span></a>
                        </li>
                        <li class="{{ request()->routeIs('payments.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('payments.index') }}"><i class="fas fa-money-check-alt"></i> <span>Pembayaran</span></a>
                        </li>

                        <li class="menu-header">Pengguna</li>
                        <li class="{{ request()->routeIs('users.customers') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('users.customers') }}"><i class="fas fa-user"></i> <span>Customer</span></a>
                        </li>
                        <li class="{{ request()->routeIs('users.technicians') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('users.technicians') }}"><i class="fas fa-user-cog"></i> <span>Teknisi</span></a>
                        </li>

                        <li class="menu-header">Lainnya</li>
                        <li class="{{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('notifications.index') }}"><i class="fas fa-bell"></i> <span>Notifikasi</span></a>
                        </li>
                        <li class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('reports.index') }}"><i class="fas fa-chart-pie"></i> <span>Laporan</span></a>
                        </li>
                    </ul>
                </aside>
            </div>
            <!-- /Sidebar -->

            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1>@yield('title', 'Dashboard')</h1>
                        @hasSection('breadcrumb')
                        <div class="section-header-breadcrumb">
                            @yield('breadcrumb')
                        </div>
                        @endif
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0 pl-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    @endif

                    @yield('content')
                </section>
            </div>

            <footer class="main-footer">
                <div class="footer-left">
                    Copyright &copy; {{ date('Y') }} <div class="bullet"></div> IPAL Maintenance Admin
                </div>
            </footer>
        </div>
    </div>

    <!-- Stisla JS -->
    <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/modules/popper.js') }}"></script>
    <script src="{{ asset('assets/modules/tooltip.js') }}"></script>
    <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('assets/modules/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/stisla.js') }}"></script>

    <script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/modules/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/modules/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/modules/chart.min.js') }}"></script>
    <script src="{{ asset('assets/modules/jquery-toast-plugin/jquery.toast.min.js') }}"></script>

    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    @if (session('success'))
    <script>
        $(function () {
            $.toast({ text: "{{ session('success') }}", icon: 'success', position: 'top-right', showHideTransition: 'slide', loader: false });
        });
    </script>
    @endif

    @stack('scripts')
</body>
</html>



