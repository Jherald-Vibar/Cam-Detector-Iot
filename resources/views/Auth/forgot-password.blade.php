<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config('app.name')}} | Password Recovery</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                        },
                        secondary: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        },
                        security: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    }
                },
            },
        }
    </script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Flowbite -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-security-50 min-h-screen font-sans">
    <!-- Animated Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-security-50 to-secondary-50"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-primary-100/50 via-transparent to-secondary-100/50"></div>

    <!-- Main Container -->
    <div class="relative z-10 container mx-auto px-4 py-8 min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full">
            <!-- Security Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-2xl shadow-lg mb-4">
                    <i class="fas fa-shield-keyhole text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-security-800 mb-2">Password Recovery</h1>
                <p class="text-security-600">Secure access restoration for your fire detection system</p>
            </div>

            <!-- Security Card -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-security-200/50 overflow-hidden">
                <div class="p-8">
                    <!-- Security Indicator -->
                    <div class="flex items-center justify-between mb-6 p-4 bg-security-50 rounded-lg border border-security-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-primary-500 rounded-full animate-pulse"></div>
                            <span class="text-sm font-medium text-security-700">Secure Recovery</span>
                        </div>
                        <i class="fas fa-lock text-security-500"></i>
                    </div>

                    <form method="POST" action="{{route('resetPass')}}" class="space-y-6">
                        @csrf

                        <!-- Email Input with Security Icon -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-semibold text-security-700">
                                <i class="fas fa-envelope mr-2 text-primary-500"></i>
                                Registered Email Address
                            </label>
                            <div class="relative">
                                <input id="email"
                                    class="w-full px-4 py-3 pl-11 rounded-lg border border-security-300 bg-white focus:ring-2 focus:ring-primary-300 focus:border-primary-500 outline-none transition-all duration-200 shadow-sm"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="security@camfiredetector.com"
                                    required
                                    autofocus
                                />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-shield text-security-400"></i>
                                </div>
                            </div>
                            <p class="text-xs text-security-500">We'll send a secure reset link to your email</p>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full px-4 py-3 text-white font-semibold bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                            <i class="fas fa-paper-plane"></i>
                            <span>Send Secure Reset Link</span>
                        </button>
                    </form>

                    <!-- Additional Security Info -->
                    <div class="mt-6 p-4 bg-primary-50 rounded-lg border border-primary-200">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-info-circle text-primary-500 mt-0.5"></i>
                            <div class="text-sm text-primary-700">
                                <p class="font-medium">Security Notice</p>
                                <p class="mt-1">For security reasons, the reset link will expire in 1 hour and can only be used once.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Back to Login -->
                    <div class="mt-6 text-center">
                        <a href="{{ route('login') }}" class="inline-flex items-center text-security-600 hover:text-security-800 font-medium transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Return to Secure Login
                        </a>
                    </div>
                </div>
            </div>

            <!-- Security Footer -->
            <div class="text-center mt-6">
                <p class="text-xs text-security-500">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Protected by Advanced Security Systems
                </p>
            </div>
        </div>
    </div>

    <!-- Floating Security Elements -->
    <div class="fixed top-10 left-10 w-4 h-4 bg-primary-300 rounded-full opacity-20 animate-pulse"></div>
    <div class="fixed bottom-20 right-16 w-6 h-6 bg-secondary-300 rounded-full opacity-30 animate-pulse" style="animation-delay: 1s;"></div>
    <div class="fixed top-1/3 right-20 w-3 h-3 bg-primary-400 rounded-full opacity-40 animate-pulse" style="animation-delay: 2s;"></div>

    <script>
        // Enhanced alerts with security theme
        @if(session('status'))
            Swal.fire({
                title: '<i class="fas fa-check-circle text-primary-500 text-4xl mb-4"></i>',
                html: '<div class="text-center"><h3 class="text-xl font-semibold text-security-800 mb-2">Recovery Email Sent</h3><p class="text-security-600">{{ session("status") }}</p></div>',
                icon: null,
                confirmButtonText: 'Understood',
                confirmButtonColor: '#ef4444',
                background: '#ffffff',
                backdrop: 'rgba(248, 250, 252, 0.8)'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: '<i class="fas fa-exclamation-triangle text-secondary-500 text-4xl mb-4"></i>',
                html: '<div class="text-center"><h3 class="text-xl font-semibold text-security-800 mb-2">Security Alert</h3><p class="text-security-600">{{ session("error") }}</p></div>',
                icon: null,
                confirmButtonText: 'Acknowledge',
                confirmButtonColor: '#f59e0b',
                background: '#ffffff',
                backdrop: 'rgba(248, 250, 252, 0.8)'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                title: '<i class="fas fa-shield-exclamation text-primary-500 text-4xl mb-4"></i>',
                html: '<div class="text-center"><h3 class="text-xl font-semibold text-security-800 mb-2">Validation Required</h3><p class="text-security-600">{!! implode("<br>", $errors->all()) !!}</p></div>',
                icon: null,
                confirmButtonText: 'Try Again',
                confirmButtonColor: '#ef4444',
                background: '#ffffff',
                backdrop: 'rgba(248, 250, 252, 0.8)'
            });
        @endif

        @if(session('success'))
            Swal.fire({
                title: '<i class="fas fa-shield-check text-secondary-500 text-4xl mb-4"></i>',
                html: '<div class="text-center"><h3 class="text-xl font-semibold text-security-800 mb-2">Success!</h3><p class="text-security-600">{{ session("success") }}</p></div>',
                icon: null,
                confirmButtonText: 'Continue',
                confirmButtonColor: '#10b981',
                background: '#ffffff',
                backdrop: 'rgba(248, 250, 252, 0.8)'
            });
        @endif

        // Add some interactive elements
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');

            emailInput.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-primary-200');
            });

            emailInput.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-primary-200');
            });
        });
    </script>
</body>
</html>
