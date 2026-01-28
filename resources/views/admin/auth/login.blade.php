<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - 360WinEstate</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --navy: #0F1A3C;
            --gold: #E4B400;
        }
        
        body {
            background: linear-gradient(135deg, var(--navy) 0%, #1a2847 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--navy), #2a3a5f);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .login-header p {
            margin: 0;
            opacity: 0.9;
        }
        
        .login-body {
            padding: 40px 30px;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--navy);
            margin-bottom: 8px;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 0.2rem rgba(228, 180, 0, 0.25);
        }
        
        .input-group-text {
            background: white;
            border: 2px solid #e0e0e0;
            border-right: none;
            border-radius: 8px 0 0 8px;
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--gold), #f5c400);
            border: none;
            color: var(--navy);
            font-weight: 700;
            padding: 14px;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #f5c400, var(--gold));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(228, 180, 0, 0.3);
            color: var(--navy);
        }
        
        .alert {
            border-radius: 8px;
            border: none;
        }
        
        .form-check-input:checked {
            background-color: var(--gold);
            border-color: var(--gold);
        }
        
        .admin-badge {
            display: inline-block;
            background: var(--gold);
            color: var(--navy);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .back-link a:hover {
            color: var(--gold);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <div class="admin-badge">
                    <i class="bi bi-shield-lock-fill me-1"></i>ADMIN ACCESS
                </div>
                <h1>360WinEstate</h1>
                <p>Administrative Portal</p>
            </div>

            <!-- Body -->
            <div class="login-body">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        @foreach($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i>Email Address
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-person-fill text-muted"></i>
                            </span>
                            <input 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus
                                placeholder="admin@example.com"
                            >
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock me-1"></i>Password
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-key-fill text-muted"></i>
                            </span>
                            <input 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                id="password" 
                                name="password" 
                                required
                                placeholder="Enter your password"
                            >
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="remember" 
                                id="remember"
                            >
                            <label class="form-check-label" for="remember">
                                Remember me on this device
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login to Dashboard
                    </button>
                </form>

                <!-- Security Notice -->
                <div class="alert alert-info mt-4 mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    <small>This is a secure admin area. All login attempts are logged.</small>
                </div>
            </div>
        </div>

        <!-- Back to Main Site -->
        <div class="back-link">
            <a href="{{ url('/') }}">
                <i class="bi bi-arrow-left me-1"></i>Back to Main Site
            </a>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
