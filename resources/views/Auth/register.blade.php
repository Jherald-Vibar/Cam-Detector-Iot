<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} | Register</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fff1f2',
                            100: '#ffe4e6',
                            200: '#fecdd3',
                            300: '#fda4af',
                            400: '#fb7185',
                            500: '#f43f5e',
                            600: '#e11d48',
                            700: '#be123c',
                            800: '#9f1239',
                            900: '#881337',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Flowbite -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gradient-to-br from-primary-50 to-primary-200 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col lg:flex-row">
        <!-- Left form -->
        <div class="w-full lg:w-1/2 p-8">
            <h1 class="text-2xl font-bold text-primary-700 mb-2">Create an Account</h1>
            <p class="text-gray-600 mb-6 text-sm">Register your Cam Detector account</p>

            <form method="POST" action="{{ route('store') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-500 outline-none transition-all"
                        placeholder="John Doe">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-500 outline-none transition-all"
                        placeholder="you@example.com">
                </div>

                <!-- Contact Number -->
                <div>
                    <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-500 outline-none transition-all"
                        placeholder="+63 912 345 6789">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-500 outline-none transition-all"
                        placeholder="••••••••"
                        onchange="validatePassword()">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-500 outline-none transition-all"
                        placeholder="••••••••"
                        onkeyup="validatePassword()">
                    <p id="password-match-message" class="text-sm text-red-500 mt-1 hidden">Passwords do not match</p>
                </div>

                <!-- Submit -->
                <button id="submit-btn" type="submit"
                    class="w-full py-3 text-white font-medium rounded-lg bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 transition-all shadow-md hover:shadow-lg">
                    Register
                </button>
            </form>

            <p class="text-center text-sm text-gray-600 mt-6">
                Already have an account?
                <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-800 font-medium">Login</a>
            </p>
        </div>

        <!-- Right banner -->
        <div class="hidden lg:block lg:w-1/2 relative bg-gradient-to-tr from-primary-600 to-primary-400">
            <img src="{{ asset('images/cam-register.png') }}" alt="Register" class="w-full h-full object-cover mix-blend-overlay">
            <div class="absolute inset-0 flex items-center justify-center text-center p-8">
                <div>
                    <h2 class="text-white text-3xl font-bold mb-2">Welcome to Cam Detector</h2>
                    <p class="text-white text-opacity-90">Monitor and control your IoT cameras securely.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('success'))
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonColor: '#e11d48'
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    title: 'Validation Error',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    icon: 'error',
                    confirmButtonColor: '#e11d48'
                });
            @endif
        });

        function validatePassword() {
            let pass = document.getElementById("password").value;
            let confirm = document.getElementById("password_confirmation").value;
            let msg = document.getElementById("password-match-message");
            let btn = document.getElementById("submit-btn");

            if (confirm === "") {
                msg.classList.add("hidden");
                btn.disabled = false;
                return;
            }

            if (pass === confirm) {
                msg.classList.add("hidden");
                btn.disabled = false;
            } else {
                msg.classList.remove("hidden");
                btn.disabled = true;
            }
        }
    </script>
</body>
</html>
