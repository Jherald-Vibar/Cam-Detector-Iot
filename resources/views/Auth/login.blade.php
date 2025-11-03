<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THEODORE - Authentication</title>
     <link rel="icon" href="{{ asset('theodore_logo.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 450px;
        }

        .auth-container {
            display: none;
        }

        .auth-container.active {
            display: block;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            padding: 40px 35px;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo img {
            width: 160px;
            height: auto;
        }

        .auth-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            text-align: center;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            font-size: 14px;
            color: #666;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }

        .required {
            color: #e50914;
            margin-left: 2px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #333;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #e50914;
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.1);
        }

        .form-input::placeholder {
            color: #999;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-input {
            padding-right: 45px;
        }

        .eye-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            cursor: pointer;
            opacity: 0.5;
            transition: opacity 0.3s ease;
        }

        .eye-icon:hover {
            opacity: 1;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #666;
            cursor: pointer;
        }

        .checkbox-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #e50914;
            cursor: pointer;
        }

        .checkbox-label.terms {
            margin-bottom: 20px;
        }

        .forgot-link {
            font-size: 13px;
            color: #e50914;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: #f40612;
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #e50914 0%, #f40612 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #f40612 0%, #e50914 100%);
            box-shadow: 0 6px 20px rgba(229, 9, 20, 0.4);
            transform: translateY(-2px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .switch-text {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-top: 25px;
        }

        .switch-text a {
            color: #e50914;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .switch-text a:hover {
            color: #f40612;
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 30px 25px;
            }

            .auth-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <!-- Login Form -->
        <div id="loginForm" class="auth-container active">
            <div class="auth-card">
                <div class="logo">
                   <img src="{{ asset('theodore_logo.svg') }}" alt="">
                 </div>

                <h1 class="auth-title">Log in to your Account</h1>
                <p class="auth-subtitle">Welcome back, please enter your details.</p>

                <form method="POST" action="{{ route('authenticate') }}" onsubmit="handleLogin(event)">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-input" placeholder="johndoe@gmail.com" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="loginPassword" class="form-input" placeholder="••••••••••" required>
                            <span class="eye-icon" onclick="togglePassword('loginPassword')" style="font-size: 20px;">👁️</span>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" checked>
                            <span>Remember me</span>
                        </label>
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary">Log in</button>
                </form>

                <p class="switch-text">
                    Don't have an account? <a href="{{ route('register') }}" onclick="switchForm('register')">Sign Up</a>
                </p>
            </div>
        </div>

        <!-- Register Form -->
        <div id="registerForm" class="auth-container">
            <div class="auth-card">
                <div class="logo">
                  <img src="{{ asset('theodore_logo.svg') }}" alt="">
                </div>

                <h1 class="auth-title">Create an Account</h1>
                <p class="auth-subtitle">Sign up now to get started with an account.</p>

                <form method="POST" action="{{ route('store') }}" onsubmit="handleRegister(event)">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Full Name<span class="required">*</span></label>
                        <input type="text" name="name" class="form-input" placeholder="John Doe" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address<span class="required">*</span></label>
                        <input type="email" name="email" class="form-input" placeholder="johndoe@gmail.com" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contact Number<span class="required">*</span></label>
                        <input type="tel" name="contact_number" class="form-input" placeholder="+63 912 345 6789" value="{{ old('contact_number') }}" pattern="[0-9+\s-]+" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password<span class="required">*</span></label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="registerPassword" class="form-input" placeholder="••••••••••" required>
                            <span class="eye-icon" onclick="togglePassword('registerPassword')" style="font-size: 20px;">👁️</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm Password<span class="required">*</span></label>
                        <div class="password-wrapper">
                            <input type="password" name="password_confirmation" id="confirmPassword" class="form-input" placeholder="••••••••••" required>
                            <span class="eye-icon" onclick="togglePassword('confirmPassword')" style="font-size: 20px;">👁️</span>
                        </div>
                    </div>

                    <label class="checkbox-label terms">
                        <input type="checkbox" required>
                        <span>I have read and agree to the <a href="#" style="color: #e50914; font-weight: 600;">Terms of Service</a></span>
                    </label>

                    <button type="submit" class="btn btn-primary">Get Started</button>
                </form>

                <p class="switch-text">
                    Already have an account? <a href="{{ route('login') }}" onclick="switchForm('login')">Log in</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function switchForm(form) {
            event.preventDefault();
            document.querySelectorAll('.auth-container').forEach(container => {
                container.classList.remove('active');
            });

            if (form === 'login') {
                document.getElementById('loginForm').classList.add('active');
            } else {
                document.getElementById('registerForm').classList.add('active');
            }
        }

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
            } else {
                input.type = 'password';
            }
        }

        function handleLogin(e) {
            Swal.fire({
                title: 'Logging in...',
                text: 'Please wait',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        function handleRegister(e) {
            // Validate passwords match
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'Passwords do not match!',
                    confirmButtonColor: '#e50914'
                });
                return false;
            }

            Swal.fire({
                title: 'Creating account...',
                text: 'Please wait',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            const successMsg = "{{ session('success') ?? '' }}";
            if (successMsg) {
                Swal.fire({
                    title: 'Success!',
                    text: successMsg,
                    icon: 'success',
                    confirmButtonColor: '#e50914'
                });
            }

            const errorMsg = "{{ session('error') ?? '' }}";
            if (errorMsg) {
                Swal.fire({
                    title: 'Error!',
                    text: errorMsg,
                    icon: 'error',
                    confirmButtonColor: '#e50914'
                });
            }

            const errors = @json($errors->all() ?? []);
            if (errors.length > 0) {
                Swal.fire({
                    title: 'Validation Error',
                    html: errors.join('<br>'),
                    icon: 'warning',
                    confirmButtonColor: '#e50914'
                });
            }
        });
    </script>
</body>
</html>
