<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'LAZISNU')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
            --green-primary: rgb(64, 154, 32);
            --green-secondary: rgb(66, 192, 16);
            --green-dark: rgb(14, 109, 7);
        }
        
        body {
            background: linear-gradient(135deg, rgb(255, 255, 255) 0%, rgb(200, 200, 200) 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand img {
            height: 40px;
            width: auto;
            margin-right: 10px;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--green-dark), var(--green-dark) 100%) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }
        
        .main-wrapper {
            display: flex;
            min-height: 100vh;
            padding-top: 76px; /* Navbar height */
        }
        
        /* Sidebar Styles */
        .sidebar-container {
            width: 280px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.1);
            padding: 2rem 0;
            position: fixed;
            height: calc(100vh - 76px);
            overflow-y: auto;
            transition: all 0.3s ease;
        }
        
        .sidebar-header {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }
        
        .sidebar-header h5 {
            font-weight: 700;
            color: var(--green-dark);
            margin-bottom: 0.5rem;
        }
        
        .role-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .role-admin {
            background: linear-gradient(135deg, var(--green-primary), var(--green-secondary));
            color: white;
        }
        
        .role-user {
            background: linear-gradient(135deg, var(--primary-color), #0056b3);
            color: white;
        }
        
        .custom-list-group {
            border: none;
            padding: 0 1rem;
        }
        
        .custom-list-item {
            border: none;
            background: transparent;
            padding: 0;
            margin-bottom: 0.5rem;
        }
        
        .menu-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: #495057;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .menu-link:hover {
            background: linear-gradient(135deg, var(--green-primary), var(--green-secondary));
            color: white;
            transform: translateX(5px);
        }
        
        .custom-list-item.active .menu-link {
            background: linear-gradient(135deg, var(--green-primary), var(--green-secondary));
            color: white;
            box-shadow: 0 5px 15px rgba(64, 154, 32, 0.3);
        }
        
        .menu-icon {
            font-size: 1.2rem;
            margin-right: 1rem;
            width: 20px;
            text-align: center;
        }
        
        .menu-text {
            font-size: 0.95rem;
        }
        
        /* Content Area - Default for authenticated users */
        .content-area {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
            min-height: calc(100vh - 76px);
        }
        
        /* Content Area - For guests (no sidebar) */
        .content-area.no-sidebar {
            margin-left: 0;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--green-primary) 0%, var(--green-secondary));
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }
        
        .btn {
            background: linear-gradient(135deg, var(--green-primary) 0%, var(--green-secondary));
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: white;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--green-primary);
            box-shadow: 0 0 0 0.2rem rgba(64, 154, 32, 0.25);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.5rem;
        }
        
        .footer {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            margin-left: 280px;
            text-align: center;
            padding: 1rem;
            background: var(--green-dark);
        }
        
        /* Footer for guests (no sidebar) */
        .footer.no-sidebar {
            margin-left: 0;
        }
        
        /* Contact Dropdown Styles */
        .contact-dropdown .dropdown-item {
            padding: 0.7rem 1rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .contact-dropdown .dropdown-item:hover {
            background: linear-gradient(135deg, var(--green-primary), var(--green-secondary));
            color: white;
            transform: translateX(5px);
        }
        
        .contact-dropdown .dropdown-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .contact-dropdown .dropdown-item span {
            font-size: 0.9rem;
        }
        
        /* Mobile Responsive */
        @media (max-width: 991.98px) {
            .sidebar-container {
                width: 100%;
                position: fixed;
                left: -100%;
                z-index: 1025;
                transition: left 0.3s ease;
            }
            
            .sidebar-container.show {
                left: 0;
            }
            
            .content-area {
                margin-left: 0;
                padding: 1rem;
            }
            
            .footer {
                margin-left: 0;
            }
            
            .sidebar-toggle {
                display: block !important;
            }
            
            .navbar-brand img {
                height: 35px;
                margin-right: 8px;
            }
            
            .navbar-brand {
                font-size: 1.3rem;
            }
        }
        
        .sidebar-toggle {
            display: none;
        }
        
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1020;
        }
        
        .overlay.show {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            @auth
                <button class="btn btn-link sidebar-toggle d-lg-none text-white me-2" type="button" onclick="toggleSidebar()">
                    <i class="bi bi-list fs-4"></i>
                </button>
            @endauth
            <a class="navbar-brand" href="/">
                <img src="{{ asset('storage/images/logo.png') }}" alt="LAZISNU Logo" onerror="this.src='{{ asset('images/logo.png') }}'">
                LAZISNU
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Contact Us Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-telephone me-1"></i>Hubungi Kami
                        </a>
                        <ul class="dropdown-menu contact-dropdown">
                            <li>
                                <a class="dropdown-item" href="https://wa.me/6281515853799">
                                    <i class="bi bi-whatsapp"></i>
                                    <span>WhatsApp: +62 815-1585-3799</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="mailto:lazisnu@gmail.com">
                                    <i class="bi bi-envelope"></i>
                                    <span>Email: lazisnu@gmail.com</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="tel:+6281515853799">
                                    <i class="bi bi-telephone"></i>
                                    <span>Telepon: +62 815-1585-3799</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="sms:+6281515853799">
                                    <i class="bi bi-chat-text"></i>
                                    <span>SMS: +62 815-1585-3799</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>Register
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name ?? 'User' }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Overlay for mobile -->
    @auth
        <div class="overlay" onclick="toggleSidebar()"></div>
    @endauth

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Sidebar Container - Only show for authenticated users -->
        @auth
            <div class="sidebar-container" id="sidebar">
                <!-- Header dengan Role Badge -->
                <div class="sidebar-header">
                    <h5>
                        @can('admin')
                            Dashboard Admin
                        @else
                            Menu Utama
                        @endcan
                    </h5>
                    <span class="role-badge @can('admin') role-admin @else role-user @endcan">
                        @can('admin')
                            Administrator
                        @else
                            User
                        @endcan
                    </span>
                </div>

                <!-- Menu List -->
                <ul class="list-group custom-list-group">
                    @can('admin')
                        <li class="list-group-item custom-list-item {{ Request::routeIs('admin.donations') ? 'active' : '' }}">
                            <a href="{{ route('admin.donations') }}" class="menu-link">
                                <i class="bi bi-coin menu-icon"></i>
                                <span class="menu-text">Pengelolaan Donasi</span>
                            </a>
                        </li>
                        <li class="list-group-item custom-list-item {{ Request::routeIs('admin.submissions') ? 'active' : '' }}">
                            <a href="{{ route('admin.submissions') }}" class="menu-link">
                                <i class="bi bi-file-earmark-check menu-icon"></i>
                                <span class="menu-text">Verifikasi Pengajuan</span>
                            </a>
                        </li>
<li class="list-group-item custom-list-item">
    <a href="{{ route('admin.users') }}" class="menu-link">
        <i class="bi bi-people menu-icon"></i>
        <span class="menu-text">Manajemen User</span>
    </a>
</li>
<li class="list-group-item custom-list-item">
    <a href="{{ route('admin.finance') }}" class="menu-link">
        <i class="bi bi-bar-chart menu-icon"></i>
        <span class="menu-text">Pengelolaan Dana</span>
    </a>
</li>
                    @else
                        <li class="list-group-item custom-list-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}" class="menu-link">
                                <i class="bi bi-house-door menu-icon"></i>
                                <span class="menu-text">Home</span>
                            </a>
                        </li>
                        <li class="list-group-item custom-list-item {{ Request::routeIs('report') ? 'active' : '' }}">
                            <a href="{{ route('report') }}" class="menu-link">
                                <i class="bi bi-file-earmark-bar-graph menu-icon"></i>
                                <span class="menu-text">Laporan</span>
                            </a>
                        </li>
                        <li class="list-group-item custom-list-item {{ Request::routeIs('donate.*') ? 'active' : '' }}">
                            <a href="{{ route('donate.form') }}" class="menu-link">
                                <i class="bi bi-cash-stack menu-icon"></i>
                                <span class="menu-text">Form Donasi</span>
                            </a>
                        </li>
                        <li class="list-group-item custom-list-item {{ Request::routeIs('submissions.*') ? 'active' : '' }}">
                            <a href="{{ route('submissions.index') }}" class="menu-link">
                                <i class="bi bi-card-checklist menu-icon"></i>
                                <span class="menu-text">Pengajuan</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        @endauth

        <!-- Content Area -->
        <div class="content-area @guest no-sidebar @endguest">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Oops!</strong> There were some problems with your input:
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Success Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Error Messages -->
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer @guest no-sidebar @endguest">
        <p class="mb-0">@LAZIZNU</p>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.overlay');
            
            if (sidebar && overlay) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            }
        }
        
        // Close sidebar when clicking on overlay
        const overlay = document.querySelector('.overlay');
        if (overlay) {
            overlay.addEventListener('click', function() {
                toggleSidebar();
            });
        }
        
        // Auto-close sidebar on mobile when clicking menu items
        document.querySelectorAll('.menu-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 991.98) {
                    toggleSidebar();
                }
            });
        });
    </script>
      @stack('scripts')

</body>
</html>