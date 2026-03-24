<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - SSAdmin</title>
    <!-- Using Google Fonts 'Inter' to match the Dashboard -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Using Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    
    <style>
        :root {
            /* Matching the Dashboard Variables */
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-body: #f1f5f9;
            --bg-card: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius: 12px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* Login Container */
        .login-wrapper {
            background: var(--bg-card);
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            display: flex;
            min-height: 600px;
            border: 1px solid var(--border);
        }

        /* Left Side: Visual/Branding */
        .login-visual {
            flex: 1;
            background: linear-gradient(135deg, var(--primary) 0%, #818cf8 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px;
            color: white;
            overflow: hidden;
        }

        /* Decorative Circles */
        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }
        .c1 { width: 300px; height: 300px; top: -50px; left: -50px; }
        .c2 { width: 200px; height: 200px; bottom: 50px; right: -50px; }

        .visual-content {
            position: relative;
            z-index: 10;
        }

        .visual-content h2 {
            font-size: 2.5rem;
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .visual-content p {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        /* Right Side: Form */
        .login-form-side {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 32px;
            text-align: center;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 24px;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary);
        }

        .form-header h1 {
            font-size: 1.8rem;
            margin-bottom: 8px;
        }

        .form-header p {
            color: var(--text-muted);
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--text-main);
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px 12px 40px; /* Left padding for icon */
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 1rem;
            font-family: inherit;
            transition: var(--transition);
            outline: none;
            background: #f8fafc;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 20px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            cursor: pointer;
            font-size: 20px;
            background: none;
            border: none;
        }

        .toggle-password:hover {
            color: var(--text-main);
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 0.9rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--text-muted);
        }

        .checkbox-label input {
            accent-color: var(--primary);
            width: 16px;
            height: 16px;
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Spinner for loading state */
        .spinner {
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top: 3px solid white;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: none;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Social Login Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .divider span {
            padding: 0 10px;
        }

        .social-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .btn-social {
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 500;
            color: var(--text-main);
            transition: var(--transition);
        }

        .btn-social:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
                min-height: auto;
            }
            .login-visual {
                padding: 40px 20px;
                text-align: center;
            }
            .login-form-side {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <!-- Left Side: Design & Branding -->
        <div class="login-visual">
            <div class="circle c1"></div>
            <div class="circle c2"></div>
            <div class="visual-content">
                <h2>SSAdmin</h2>
                <p>Streamline your e-commerce operations with our powerful analytics and inventory management tools.</p>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="login-form-side">
            <div class="form-header">
                <div class="logo">
                    <span class="material-icons-round">dashboard</span>
                    SSAdmin
                </div>
                <h1>Welcome Back</h1>
                <p>Please enter your details to sign in.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email Field -->
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <div class="input-wrapper">
                        <span class="material-icons-round input-icon">email</span>
                        <input type="email" id="email" name="email" class="form-control" placeholder="admin@nexus.com" required autofocus value="{{ old('email') }}">
                    </div>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrapper">
                        <span class="material-icons-round input-icon">lock</span>
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <span class="material-icons-round" id="eyeIcon">visibility_off</span>
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit" id="submitBtn">
                    <span class="spinner" id="btnSpinner"></span>
                    <span id="btnText">Sign In</span>
                </button>

                @if ($errors->any())
                    <div style="color: #e53e3e; margin-top: 16px; text-align:center;">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            </form>
        </div>
    </div>

    <script>
        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerText = 'visibility';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerText = 'visibility_off';
            }
        }

        // Handle Login Logic
        function handleLogin(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const spinner = document.getElementById('btnSpinner');

            // Basic Validation Simulation
            if(email && password) {
                // Set Loading State
                btn.disabled = true;
                btnText.style.display = 'none';
                spinner.style.display = 'block';
                btn.style.opacity = '0.8';

                // Simulate API Call
                setTimeout(() => {
                    // Success State
                    alert(`Welcome back, ${email}!\n\n(In a real app, you would be redirected to dashboard.html now)`);
                    
                    // Reset Button
                    btn.disabled = false;
                    btnText.style.display = 'block';
                    spinner.style.display = 'none';
                    btn.style.opacity = '1';
                    
                    // Here you would redirect:
                    // window.location.href = 'dashboard.html';
                    
                }, 1500);
            }
        }
    </script>
</body>
</html>