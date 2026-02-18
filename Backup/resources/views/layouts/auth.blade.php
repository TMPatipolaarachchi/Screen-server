<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Fuel Station') }} - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0f172a;
            --surface-primary: #1e293b;
            --text-primary: #ffffff;
            --text-secondary: #94a3b8;
            --accent-primary: #f59e0b;
            --accent-secondary: #22c55e;
            --border-color: #334155;
            --divider-color: #475569;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --neutral: #64748b;
            --hover-bg: #475569;
            --active-border: #22c55e;
            --disabled: #1e293b;
            --card-primary: #1e293b;
            --card-hover: #334155;
            --gradient-fuel-regular: linear-gradient(135deg, #22c55e, #059669);
            --gradient-fuel-mid: linear-gradient(135deg, #f59e0b, #ea580c);
            --gradient-fuel-premium: linear-gradient(135deg, #3b82f6, #4f46e5);
            --gradient-fuel-diesel: linear-gradient(135deg, #475569, #1e293b);
            --gradient-header-icon: linear-gradient(135deg, #f59e0b, #ea580c);
            --gradient-welcome: linear-gradient(135deg, #d97706, #ea580c);
            --shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary) !important;
            line-height: 1.7;
            min-height: 100vh;
            font-weight: 400;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            transition: var(--transition);
        }

        /* Set text elements to use theme color */
        h1, h2, h3, h4, h5, h6, p, span, div, a, li, td, th, label, input, textarea, select, button, 
        .text-white, .text-light, .text-muted, .text-primary, .text-secondary, .text-success, 
        .text-danger, .text-warning, .text-info, .text-dark, .text-body, .text-black-50, .text-white-50 {
            color: var(--text-primary) !important;
        }

        /* Ensure all content text uses theme color */
        .container, .container-fluid, .card, .card-body, .card-header, .card-title, 
        .navbar, .navbar-brand, .nav-link, .dropdown-item, .list-group-item,
        .table, .table th, .table td, .alert, .badge, .btn {
            color: var(--text-primary) !important;
        }

        /* Links should use theme color */
        a, a:hover, a:focus, a:visited {
            color: var(--accent-primary) !important;
        }
        
        .overlay {
            display: none;
        }
        
        .background-image {
            display: none;
        }
        
        .background-img {
            display: none;
        }

        /* Navbar for auth pages */
        .auth-navbar {
            background-color: var(--surface-primary);
            box-shadow: var(--shadow-sm);
            padding: 0.875rem 0;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .auth-navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--text-primary) !important;
            letter-spacing: -0.025em;
            transition: var(--transition);
        }

        .auth-navbar .navbar-brand:hover {
            color: var(--accent-primary) !important;
        }

        .auth-navbar .navbar-brand i {
            margin-right: 0.5rem;
            color: var(--accent-primary) !important;
        }

        /* Professional Buttons */
        .btn {
            padding: 0.75rem 1.75rem;
            font-weight: 600;
            border-radius: 6px;
            transition: var(--transition);
            border: 1px solid transparent;
            font-size: 0.9375rem;
            line-height: 1.5;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .btn-sm {
            padding: 0.5rem 1.25rem;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: var(--gradient-header-icon);
            color: var(--text-primary) !important;
            border-color: var(--accent-primary);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #d97706, #f59e0b);
            border-color: var(--accent-primary);
            color: var(--text-primary) !important;
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background-color: var(--surface-primary);
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .btn-secondary:hover {
            background-color: var(--hover-bg);
            border-color: var(--hover-bg);
            color: var(--text-primary);
        }

        .btn-warning {
            background-color: var(--warning);
            color: var(--white);
            border-color: var(--warning);
        }

        .btn-warning:hover {
            background-color: #d97706;
            border-color: #d97706;
            color: var(--white);
        }

        .btn-danger {
            background-color: var(--danger);
            color: var(--white);
            border-color: var(--danger);
        }

        .btn-danger:hover {
            background-color: #dc2626;
            border-color: #dc2626;
            color: var(--white);
        }

        .btn-outline-light {
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-primary) !important;
        }

        .btn-outline-light {
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-primary) !important;
        }

        .btn-outline-light:hover {
            background-color: var(--hover-bg);
            border-color: var(--hover-bg);
            color: var(--text-primary) !important;
        }
        
        /* Specific styling to match app theme */
        .btn-auth-signin {
            padding: 0.75rem 1.75rem;
            font-weight: 600;
            border-radius: 6px;
            transition: var(--transition);
            border: 1px solid transparent;
            font-size: 0.9375rem;
            line-height: 1.5;
            background: var(--gradient-header-icon);
            color: var(--text-primary) !important;
            border-color: color-mix(in srgb, var(--accent-primary) 20%, transparent);
        }

        .btn-auth-signin:hover {
            background: linear-gradient(135deg, #d97706, #f59e0b);
            border-color: color-mix(in srgb, var(--accent-primary) 40%, transparent);
            color: var(--text-primary) !important;
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        /* Professional Cards */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            background-color: var(--surface-primary);
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            border-color: var(--hover-bg);
        }

        .card-header {
            background: var(--gradient-header-icon);
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: var(--text-primary) !important;
            font-size: 1.0625rem;
        }
        
        .card-header.bg-light {
            background: var(--gradient-header-icon) !important;
            color: var(--text-primary) !important;
        }
        
        .card-header.border-bottom {
            border-bottom: 1px solid var(--border-color) !important;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Professional Forms */
        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 0.625rem 0.875rem;
            transition: var(--transition);
            font-size: 0.9375rem;
            background-color: var(--surface-primary);
            color: var(--text-primary) !important;
        }

        .form-control:focus {
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
            outline: none;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-primary) !important;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-check-input:checked {
            background-color: var(--accent-primary);
            border-color: var(--accent-primary);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
        }

        /* Error messages */
        .invalid-feedback {
            color: var(--danger) !important;
        }

        .form-check-label {
            color: var(--text-primary) !important;
        }

        /* Professional Alerts */
        .alert {
            border: 1px solid;
            border-radius: 6px;
            padding: 1rem 1.25rem;
            border-left-width: 4px;
        }

        .alert-success {
            background-color: rgba(34, 197, 94, 0.15);
            border-color: var(--success);
            border-left-color: var(--success);
            color: var(--success);
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.15);
            border-color: var(--danger);
            border-left-color: var(--danger);
            color: var(--danger);
        }

        .alert-info {
            background-color: rgba(59, 130, 246, 0.15);
            border-color: var(--info);
            border-left-color: var(--info);
            color: var(--info);
        }

        .alert-custom {
            background-color: rgba(245, 158, 11, 0.15);
            border-color: var(--warning);
            border-left-color: var(--warning);
            color: var(--warning);
        }

        /* Loading states */
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Focus visible */
        *:focus-visible {
            outline: 2px solid var(--accent-primary);
            outline-offset: 2px;
            border-radius: 2px;
        }
        
        /* Auth Navbar Brand */
        .auth-navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--text-primary) !important;
            letter-spacing: -0.025em;
            transition: var(--transition);
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .auth-navbar .navbar-brand i {
            margin-right: 0.5rem;
            color: var(--accent-primary);
        }

        /* Container spacing */
        .container, .container-fluid {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        /* Section spacing */
        .mt-5 {
            margin-top: 3rem !important;
        }

        .mb-5 {
            margin-bottom: 3rem !important;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 25%, #334155 50%, #1e293b 75%, #0f172a 100%);
            background-size: 200% 200%;
            animation: gradientShift 15s ease infinite;
            position: relative;
            overflow: hidden;
        }

        .auth-container::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(245, 158, 11, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(34, 197, 94, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(59, 130, 246, 0.08) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) translateX(0px); }
            25% { transform: translateY(-20px) translateX(10px); }
            50% { transform: translateY(-40px) translateX(-10px); }
            75% { transform: translateY(-20px) translateX(10px); }
        }

        /* Enhanced Login Page Styles */
        .login-card {
            border: none !important;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
            position: relative;
            z-index: 10;
        }

        .login-card:hover {
            box-shadow: 0 25px 60px -12px rgba(245, 158, 11, 0.3) !important;
        }

        .form-control {
            background-color: #334155 !important;
            border: 1px solid #475569 !important;
            color: #ffffff !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .form-control:focus {
            background-color: #334155 !important;
            border-color: #f59e0b !important;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2) !important;
            color: #ffffff !important;
        }

        .form-control::placeholder {
            color: #64748b !important;
        }

        .form-label {
            color: #e2e8f0 !important;
            font-weight: 500 !important;
            font-size: 0.9rem !important;
        }

        .login-wrapper {
            position: relative;
            z-index: 10;
        }
        
        /* Mobile Responsive Styles for Auth Pages */
        @media (max-width: 768px) {
            .auth-container {
                padding: 1.5rem 1rem;
            }
            
            .login-card {
                margin: 0 auto;
            }
            
            .card-body {
                padding: 2rem 1.5rem !important;
            }
            
            .login-card .card-header > div:first-child {
                width: 50px !important;
                height: 50px !important;
            }
            
            .login-card .card-header > div:first-child i {
                font-size: 1.5rem !important;
            }
            
            .login-card .card-header h1 {
                font-size: 1.5rem !important;
            }
            
            .login-card .card-header p {
                font-size: 0.8125rem !important;
            }
            
            h2 {
                font-size: 1.5rem !important;
            }
            
            p {
                font-size: 0.875rem !important;
            }
            
            .form-label {
                font-size: 0.875rem !important;
            }
            
            .form-control {
                font-size: 0.875rem !important;
                padding: 0.625rem 0.75rem 0.625rem 2.5rem !important;
            }
            
            .btn {
                padding: 0.75rem !important;
                font-size: 0.9375rem !important;
            }
        }
        
        @media (max-width: 576px) {
            .auth-container {
                padding: 1rem 0.75rem;
            }
            
            .login-card .card-header {
                padding: 1.5rem !important;
            }
            
            .login-card .card-header > div:first-child {
                width: 45px !important;
                height: 45px !important;
                margin-bottom: 0.75rem !important;
            }
            
            .login-card .card-header > div:first-child i {
                font-size: 1.25rem !important;
            }
            
            .login-card .card-header h1 {
                font-size: 1.375rem !important;
            }
            
            .login-card .card-header p {
                font-size: 0.75rem !important;
            }
            
            .card-body {
                padding: 1.75rem 1.25rem !important;
            }
            
            h2 {
                font-size: 1.25rem !important;
                margin-bottom: 0.375rem !important;
            }
            
            p {
                font-size: 0.8125rem !important;
                margin-bottom: 1.5rem !important;
            }
            
            .form-label {
                font-size: 0.8125rem !important;
            }
            
            .form-control {
                font-size: 0.8125rem !important;
                padding: 0.5rem 0.625rem 0.5rem 2.25rem !important;
            }
            
            .form-control + i,
            .form-control ~ i {
                left: 10px !important;
                font-size: 0.875rem !important;
            }
            
            .btn {
                padding: 0.625rem !important;
                font-size: 0.875rem !important;
            }
            
            .login-card {
                border-radius: 10px !important;
            }
            
            .col-md-6.col-lg-5 {
                padding: 0 !important;
            }
        }
        
        @media (max-width: 400px) {
            .auth-container {
                padding: 0.75rem 0.5rem;
            }
            
            .card-body {
                padding: 1.5rem 1rem !important;
            }
            
            .form-control {
                padding: 0.5rem 0.5rem 0.5rem 2rem !important;
            }
            
            .btn {
                padding: 0.5rem !important;
                font-size: 0.8125rem !important;
            }
        }
    </style>
</head>
<body>
    <!-- Auth Content -->
    <div class="auth-container">
        <div class="container">
            @yield('content')
        </div>
    </div>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>