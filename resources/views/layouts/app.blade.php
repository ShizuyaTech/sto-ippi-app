<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Stock Taking Opname')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 260px;
            background: #8b0000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }
        
        .sidebar.hidden {
            transform: translateX(-100%);
        }
        
        .sidebar-header {
            padding: 1.5rem;
            background: #dc3545;
            color: white;
            font-size: 1.25rem;
            font-weight: bold;
            text-align: center;
            border-bottom: 2px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            border-left-color: #dc3545;
            color: white;
        }
        
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.15);
            border-left-color: #dc3545;
            color: white;
            font-weight: 600;
        }
        
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 1rem;
            background: rgba(0,0,0,0.2);
        }
        
        .sidebar-footer button {
            width: 100%;
            padding: 0.75rem;
            background: #c82333;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s ease;
        }
        
        .sidebar-footer button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        /* Mobile Toggle Button */
        .mobile-toggle {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            cursor: pointer;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .mobile-toggle svg {
            width: 24px;
            height: 24px;
        }
        
        /* Overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }
        
        .sidebar-overlay.active {
            display: block;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 2rem 2rem 1.5rem;
            flex: 1;
            transition: margin-left 0.3s ease;
        }
        
        /* Footer */
        .app-footer {
            margin-left: 260px;
            padding: 1rem 2rem;
            background: white;
            border-top: 1px solid #e9ecef;
            text-align: center;
            font-size: 0.8rem;
            color: #6c757d;
            transition: margin-left 0.3s ease;
        }
        
        .app-footer a {
            color: #dc3545;
            text-decoration: none;
            font-weight: 600;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 12px rgba(0,0,0,0.15);
        }
        
        .card-header {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #dc3545;
        }
        
        .btn {
            padding: 0.625rem 1.25rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 12px;
        }
        
        .btn-primary {
            background: #dc3545;
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(108, 117, 125, 0.4);
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
        }
        
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 193, 7, 0.4);
        }
        
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        table th, table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        
        table th {
            background: #dc3545;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.5px;
        }
        
        table tbody tr {
            transition: background 0.2s ease;
        }
        
        table tbody tr:hover {
            background: rgba(220, 53, 69, 0.05);
        }
        
        table tbody tr:last-child td {
            border-bottom: none;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2d3748;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        /* Mobile Cards for Responsive Tables */
        .mobile-cards {
            display: none;
        }
        
        .mobile-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid;
            border-left-color: #dc3545;
        }
        
        .mobile-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .mobile-card-header strong {
            font-size: 1.1rem;
            color: #2d3748;
        }
        
        .mobile-card-body {
            margin-bottom: 0.75rem;
        }
        
        .mobile-card-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f8f9fa;
        }
        
        .mobile-card-row:last-child {
            border-bottom: none;
        }
        
        .mobile-label {
            font-weight: 600;
            color: #6c757d;
            margin-right: 1rem;
        }
        
        .mobile-card-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .desktop-table {
            display: block;
        }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar {
                width: 220px;
            }
            
            .main-content {
                margin-left: 220px;
            }
            
            .app-footer {
                margin-left: 220px;
            }
        }
        
        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }
            
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 5rem 1rem 1.5rem;
            }
            
            .app-footer {
                margin-left: 0;
                padding: 0.875rem 1rem;
            }
            
            .card {
                padding: 1rem;
            }
            
            /* Hide desktop table, show mobile cards */
            .desktop-table {
                display: none !important;
            }
            
            .mobile-cards {
                display: block !important;
            }
            
            table {
                font-size: 0.875rem;
            }
            
            table th, table td {
                padding: 0.75rem 0.5rem;
            }
            
            /* Responsive form layout */
            .form-group {
                margin-bottom: 1rem;
            }
            
            input, select, textarea {
                font-size: 16px; /* Prevent zoom on iOS */
            }
            
            /* Better button layout on mobile */
            .btn {
                width: 100%;
                text-align: center;
            }
        }
        
        @media (max-width: 480px) {
            .sidebar {
                width: 80%;
            }
            
            .main-content {
                padding: 5rem 0.75rem 1.25rem;
            }
            
            .app-footer {
                padding: 0.75rem;
                font-size: 0.75rem;
            }
            
            .btn {
                padding: 0.75rem 1rem;
                font-size: 14px;
            }
            
            .card {
                padding: 0.875rem;
                margin-bottom: 1rem;
            }
            
            .card-header {
                font-size: 1.25rem;
            }
            
            /* Improve mobile card readability */
            .mobile-card {
                padding: 0.875rem;
            }
            
            .mobile-card-header strong {
                font-size: 1rem;
            }
            
            .mobile-label {
                font-size: 0.875rem;
            }
            
            table {
                font-size: 0.75rem;
                display: block;
                overflow-x: auto;
            }
            
            table th, table td {
                padding: 0.5rem 0.375rem;
            }
        }
        
        .badge {
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-pending {
            background: #ffc107;
            color: #212529;
        }
        
        .badge-in-progress {
            background: #0083b0;
            color: white;
        }
        
        .badge-completed {
            background: #28a745;
            color: white;
        }
        
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        code {
            background: #f4f4f4;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
        }
        
        /* Pulse animation for scan indicator */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.6;
                transform: scale(1.2);
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header" style="display: flex; align-items: center; gap: 0.75rem; text-align: left;">
            <img src="{{ asset('logo-ippi.png') }}" alt="PT. IPPI" style="width: 44px; height: 44px; object-fit: contain; background: white; border-radius: 8px; padding: 4px; flex-shrink: 0;">
            <span>Stock Taking Opname</span>
        </div>
        
        <div class="sidebar-menu">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg style="width: 20px; height: 20px; margin-right: 10px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg style="width: 20px; height: 20px; margin-right: 10px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Users
                    </a>
                    <a href="{{ route('admin.items.index') }}" class="{{ request()->routeIs('admin.items.*') ? 'active' : '' }}">
                        <svg style="width: 20px; height: 20px; margin-right: 10px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Items
                    </a>
                    <a href="{{ route('admin.sessions.index') }}" class="{{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}">
                        <svg style="width: 20px; height: 20px; margin-right: 10px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Sessions
                    </a>
                    <a href="{{ route('admin.tag-numbers.index') }}" class="{{ request()->routeIs('admin.tag-numbers.*') ? 'active' : '' }}">
                        <svg style="width: 20px; height: 20px; margin-right: 10px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Tag Numbers
                    </a>
                @else
                    <a href="{{ route('stock-taking.index') }}" class="{{ request()->routeIs('stock-taking.*') ? 'active' : '' }}">
                        <svg style="width: 20px; height: 20px; margin-right: 10px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        My Sessions
                    </a>
                @endif
            @endauth
        </div>
        
        <div class="sidebar-footer">
            @auth
                <div style="color: rgba(255,255,255,0.8); font-size: 0.875rem; margin-bottom: 0.75rem; text-align: center;">
                    👤 {{ Auth::user()->name }}
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">
                        <svg style="width: 18px; height: 18px; margin-right: 8px; display: inline-block; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    ✕ {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="app-footer">
        &copy; {{ date('Y') }} <strong>Mahardika</strong> &mdash; Stock Taking Opname. All rights reserved.
    </footer>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.querySelector('.sidebar-overlay');
            if (overlay) {
                overlay.addEventListener('click', function() {
                    toggleSidebar();
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
