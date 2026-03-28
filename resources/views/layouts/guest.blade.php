<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                background: #ffffff;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            
            .auth-container {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 1.5rem;
            }
            
            .auth-logo {
                margin-bottom: 2rem;
                animation: float 3s ease-in-out infinite;
            }
            
            @keyframes float {
                0%, 100% {
                    transform: translateY(0px);
                }
                50% {
                    transform: translateY(-10px);
                }
            }
            
            .auth-card {
                width: 100%;
                max-width: 450px;
                background: white;
                border-radius: 20px;
                padding: 2.5rem;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                animation: slideIn 0.5s ease-out;
            }
            
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .auth-card h2 {
                color: #1a202c;
                font-size: 1.875rem;
                font-weight: 700;
                text-align: center;
                margin-bottom: 1.5rem;
            }
            
            .auth-card .form-group {
                margin-bottom: 1.25rem;
            }
            
            .auth-card label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 600;
                color: #2d3748;
                font-size: 0.95rem;
            }
            
            .auth-card input[type="email"],
            .auth-card input[type="password"],
            .auth-card input[type="text"] {
                width: 100%;
                padding: 0.75rem;
                border: 2px solid #e9ecef;
                border-radius: 8px;
                font-size: 14px;
                transition: all 0.3s ease;
                background: white;
            }
            
            .auth-card input[type="email"]:focus,
            .auth-card input[type="password"]:focus,
            .auth-card input[type="text"]:focus {
                outline: none;
                border-color: #dc3545;
                box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
            }
            
            .auth-card .btn {
                padding: 0.75rem 1.25rem;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                font-size: 1rem;
                font-weight: 600;
                transition: all 0.3s ease;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            
            .auth-card .btn-primary {
                background: #dc3545;
                color: white;
            }
            
            .auth-card .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
            }
            
            @media (max-width: 640px) {
                .auth-card {
                    padding: 1.5rem;
                    border-radius: 15px;
                }
                
                .auth-container {
                    padding: 1rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <div class="auth-logo">
                <div style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                    <div style="width: 100px; height: 100px; background: white; border-radius: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3); padding: 8px;">
                        <img src="{{ asset('logo-ippi.png') }}" alt="PT. IPPI" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 1.1rem; font-weight: 800; color: #dc3545; letter-spacing: 0.5px;">Stock Taking Opname</div>
                        {{-- <div style="font-size: 0.7rem; color: #6b7280; font-weight: 500; letter-spacing: 0.3px;">Stock Taking Opname</div> --}}
                    </div>
                </div>
            </div>

            <div class="auth-card">
                {{ $slot }}
            </div>

            <p style="margin-top: 1.5rem; font-size: 0.78rem; color: #9ca3af; text-align: center;">
                &copy; {{ date('Y') }} <strong style="color: #6b7280;">Mahardika</strong> &mdash; Stock Taking Opname
            </p>
        </div>
    </body>
</html>
