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
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        html {
            overflow-x: hidden;
        }

        body {
            font-family: 'Poppins', 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 25%, #334155 50%, #1e293b 75%, #0f172a 100%);
            background-size: 200% 200%;
            animation: gradientShift 15s ease infinite;
            color: var(--text-primary);
            line-height: 1.7;
            min-height: 100vh;
            font-weight: 400;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            transition: var(--transition);
            overflow-x: hidden;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(245, 158, 11, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(34, 197, 94, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(59, 130, 246, 0.06) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
            z-index: 1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) translateX(0px); }
            25% { transform: translateY(-20px) translateX(10px); }
            50% { transform: translateY(-40px) translateX(-10px); }
            75% { transform: translateY(-20px) translateX(10px); }
        }
        
        /* White background for specific pages */
        .page-dashboard .main-content,
        .page-users .main-content,
        .page-suppliers .main-content,
        .page-categories .main-content,
        .page-items .main-content,
        .page-banks .main-content,
        .page-settings .main-content,
        .page-profile .main-content {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e8eef7 100%);
            color: #000000;
            min-height: 100vh;
            position: relative;
        }
        
        .page-dashboard .main-content::before,
        .page-users .main-content::before,
        .page-suppliers .main-content::before,
        .page-categories .main-content::before,
        .page-items .main-content::before,
        .page-banks .main-content::before,
        .page-settings .main-content::before,
        .page-profile .main-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 90% 10%, rgba(245, 158, 11, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 10% 90%, rgba(34, 197, 94, 0.04) 0%, transparent 50%);
            pointer-events: none;
        }
        
        /* Override text color for white background sections */
        .page-dashboard .main-content *,
        .page-users .main-content *,
        .page-suppliers .main-content *,
        .page-categories .main-content *,
        .page-settings .main-content *,
        .page-profile .main-content * {
            color: #000000 !important;
        }
        
        .page-dashboard .main-content .card,
        .page-users .main-content .card,
        .page-suppliers .main-content .card,
        .page-categories .main-content .card,
        .page-items .main-content .card,
        .page-banks .main-content .card,
        .page-settinggories .main-content .card,
        .page-items .main-content .card,
        .page-banks .main-content .card,
        .page-profile .main-content .card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }
        
        .page-dashboard .main-content .card::before,
        .page-users .main-content .card::before,
        .page-suppliers .main-content .card::before,
        .page-categories .main-content .card::before,
        .page-settings .main-content .card::before,
        .page-items .main-content .card::before,
        .page-banks .main-content .card::before,
        .page-profile .main-content .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(245, 158, 11, 0.3), transparent);
        }
        
        .page-dashboard .main-content .card:hover,
        .page-users .main-content .card:hover,
        .page-suppliers .main-content .card:hover,
        .page-categories .main-content .card:hover,
        .page-items .main-content .card:hover,
        .page-banks .main-content .card:hover,
        .page-profile .main-content .card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 12px 35px rgba(245, 158, 11, 0.15);
            transform: translateY(-4px);
            border-left: 4px solid #f59e0b;
        }
        
        .page-dashboard .main-content .card-header,
        .page-users .main-content .card-header,
        .page-suppliers .main-content .card-header,
        .page-categories .main-content .card-header,
        .page-items .main-content .card-header,
        .page-settings .main-content .card-header,
        .page-banks .main-content .card-header,
        .page-profile .main-content .card-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #ffffff !important;
            font-weight: 600;
            border: none;
            padding: 1.25rem 1.5rem;
            font-size: 1.0625rem;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* SVG Pattern Styles */
        .absolute {
            position: absolute;
        }
        
        .top-0 {
            top: 0px;
        }
        
        .left-\[max\(50\%\2c 25rem\)\] {
            left: max(50%, 25rem);
        }
        
        .h-256 {
            height: 256px;
        }
        
        .w-512 {
            width: 512px;
        }
        
        .-translate-x-1\/2 {
            --tw-translate-x: -50%;
            transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y));
        }
        
        .mask-\[radial-gradient\(64rem_64rem_at_top\2c white\2c transparent\)\] {
            -webkit-mask: radial-gradient(64rem 64rem at top,white,transparent);
            mask: radial-gradient(64rem 64rem at top,white,transparent);
        }
        
        .stroke-gray-200 {
            stroke: var(--gray-200);
        }
        
        .overflow-visible {
            overflow: visible;
        }
        
        .fill-gray-50 {
            fill: var(--gray-50);
        }
        
        .strokeWidth-0,
        [stroke-width="0"] {
            stroke-width: 0;
        }
        
        /* New SVG Pattern Styles */
        .inset-0 {
            top: 0px;
            right: 0px;
            bottom: 0px;
            left: 0px;
        }
        
        .w-full {
            width: 100%;
        }
        
        .h-full {
            height: 100%;
        }
        
        .stroke-gray-300 {
            stroke: var(--gray-300);
        }
        
        .opacity-60 {
            opacity: 0.6;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e293b 0%, #1a1f2e 50%, #0f172a 100%);
            color: var(--text-primary);
            box-shadow: 8px 0 32px rgba(0, 0, 0, 0.4);
            z-index: 1000;
            transition: var(--transition);
            overflow-y: auto;
            padding-bottom: 2rem;
            border-right: 1px solid var(--border-color);
            backdrop-filter: blur(10px);
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(ellipse at center, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0) 70%);
            pointer-events: none;
        }

        .sidebar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(245, 158, 11, 0.3), transparent);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* Hover to temporarily expand collapsed sidebar */
        .sidebar.collapsed:hover {
            width: var(--sidebar-width);
            box-shadow: 12px 0 40px rgba(0, 0, 0, 0.5);
        }

        .sidebar.collapsed:hover .nav-link span,
        .sidebar.collapsed:hover .sidebar-brand span,
        .sidebar.collapsed:hover .user-info span {
            display: inline;
        }

        .sidebar.collapsed:hover .sidebar-brand {
            justify-content: flex-start;
            padding: 1.5rem;
        }

        .sidebar.collapsed:hover .sidebar-brand i {
            margin-right: 0.75rem;
        }

        .sidebar.collapsed:hover .nav-link i {
            margin-right: 0.75rem;
        }

        .sidebar.collapsed:hover .user-actions {
            display: block;
        }

        .sidebar-header {
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--text-primary) !important;
            text-decoration: none;
            position: relative;
            z-index: 2;
            background: linear-gradient(135deg, #ffffff 0%, #f3f4f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.025em;
        }

        .sidebar-brand i {
    margin-right: 0.75rem;
    font-size: 1.5rem;
    color: var(--accent-primary);
    transition: var(--transition);
    -webkit-text-fill-color: var(--accent-primary) !important;
    background: none !important;
    -webkit-background-clip: border-box !important;
}

.sidebar-brand:hover i {
    color: #f59e0b;
    -webkit-text-fill-color: #f59e0b !important;
    transform: scale(1.1) rotate(10deg);
    filter: drop-shadow(0 0 8px rgba(245, 158, 11, 0.5));
}

        .sidebar-collapse-btn {
            background: var(--surface-primary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            cursor: pointer;
            font-size: 1.25rem;
            padding: 0.5rem;
            border-radius: 8px;
            transition: var(--transition);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 2;
        }

        .sidebar-collapse-btn:hover {
            background-color: var(--hover-bg);
            color: var(--accent-primary);
            transform: scale(1.05);
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin: 0.25rem 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: var(--text-primary) !important;
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
            border-left: 4px solid transparent;
            position: relative;
            overflow: hidden;
            border-radius: 0 8px 8px 0;
            margin-right: 1rem;
            background: transparent;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05), transparent);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: -1;
        }

        .nav-link:hover {
            background: rgba(245, 158, 11, 0.15);
            border-left-color: var(--accent-primary);
            color: var(--text-primary) !important;
            transform: translateX(4px);
        }

        .nav-link:hover::before {
            transform: translateX(0);
        }

        .nav-link.active {
            background: linear-gradient(90deg, rgba(245, 158, 11, 0.2), rgba(245, 158, 11, 0.1), transparent);
            border-left-color: var(--accent-primary);
            color: var(--accent-primary) !important;
            font-weight: 600;
            box-shadow: inset 0 0 20px rgba(245, 158, 11, 0.1);
        }

        .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            transition: var(--transition);
            width: 20px;
            text-align: center;
        }

        .nav-link:hover i {
            color: var(--accent-primary);
            transform: scale(1.15);
        }

        .nav-link.active i {
            color: var(--accent-primary);
            transform: scale(1.2);
        }

        .nav-link span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        .sidebar.collapsed .sidebar-brand span {
            display: none;
        }

        .sidebar.collapsed .sidebar-brand {
            justify-content: center;
            padding: 1.5rem 0;
        }

        .sidebar.collapsed .sidebar-brand i {
            margin-right: 0;
        }

        .sidebar-user {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-color);
            margin-top: auto;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info i {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }

        .user-actions {
            padding: 0.5rem 0;
        }

        .user-actions .btn {
            padding: 0.5rem;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
            background: var(--surface-primary) !important;
            transition: var(--transition);
            border-radius: 6px;
        }

        .user-actions .btn:hover {
            background: var(--hover-bg) !important;
            color: var(--accent-primary) !important;
            border-color: var(--active-border) !important;
            transform: translateX(3px);
        }

        .sidebar.collapsed .user-info span {
            display: none;
        }

        .sidebar.collapsed .user-actions {
            display: none;
        }
        
        .manage-submenu {
            display: none;
            padding-left: 1.5rem;
            border-left: 2px solid var(--border-color);
            margin-left: 1rem;
        }
        
        .manage-submenu .nav-link {
            padding: 0.75rem 1.5rem !important;
            margin: 0.125rem 0;
            border-radius: 6px !important;
            border-left: 2px solid transparent !important;
            background-color: color-mix(in srgb, var(--surface-primary) 80%, var(--accent-primary)) !important;
        }
        
        .manage-submenu .nav-link:hover {
            background-color: var(--hover-bg) !important;
            border-left-color: var(--accent-primary) !important;
            transform: translateX(3px) !important;
        }
        
        .manage-submenu .nav-link.active {
            background: var(--hover-bg) !important;
            border-left-color: var(--accent-primary) !important;
        }
        
        .manage-toggle {
            position: relative;
        }
        
        .manage-toggle::after {
            content: '▼';
            position: absolute;
            right: 1rem;
            font-size: 0.75rem;
            transition: transform 0.3s ease;
        }
        
        .manage-toggle.expanded::after {
            transform: rotate(180deg);
        }

        /* Purchase Submenu Styles */
        .purchase-submenu {
            display: none;
            padding-left: 1.5rem;
            border-left: 2px solid var(--border-color);
            margin-left: 1rem;
        }
        
        .purchase-submenu .nav-link {
            padding: 0.75rem 1.5rem !important;
            margin: 0.125rem 0;
            border-radius: 6px !important;
            border-left: 2px solid transparent !important;
            background-color: color-mix(in srgb, var(--surface-primary) 80%, var(--accent-primary)) !important;
        }
        
        .purchase-submenu .nav-link:hover {
            background-color: var(--hover-bg) !important;
            border-left-color: var(--accent-primary) !important;
            transform: translateX(3px) !important;
        }
        
        .purchase-submenu .nav-link.active {
            background: var(--hover-bg) !important;
            border-left-color: var(--accent-primary) !important;
        }
        
        .purchase-toggle {
            position: relative;
        }
        
        .purchase-toggle::after {
            content: '▼';
            position: absolute;
            right: 1rem;
            font-size: 0.75rem;
            transition: transform 0.3s ease;
        }
        
        .purchase-toggle.expanded::after {
            transform: rotate(180deg);
        }

        /* Stock Submenu Styles */
        .stock-submenu {
            display: none;
            padding-left: 1.5rem;
            border-left: 2px solid var(--border-color);
            margin-left: 1rem;
        }
        
        .stock-submenu .nav-link {
            padding: 0.75rem 1.5rem !important;
            margin: 0.125rem 0;
            border-radius: 6px !important;
            border-left: 2px solid transparent !important;
            background-color: color-mix(in srgb, var(--surface-primary) 80%, var(--accent-primary)) !important;
        }
        
        .stock-submenu .nav-link:hover {
            background-color: var(--hover-bg) !important;
            border-left-color: var(--accent-primary) !important;
            transform: translateX(3px) !important;
        }
        
        .stock-submenu .nav-link.active {
            background: var(--hover-bg) !important;
            border-left-color: var(--accent-primary) !important;
        }
        
        .stock-toggle {
            position: relative;
        }
        
        .stock-toggle::after {
            content: '▼';
            position: absolute;
            right: 1rem;
            font-size: 0.75rem;
            transition: transform 0.3s ease;
        }
        
        .stock-toggle.expanded::after {
            transform: rotate(180deg);
        }

        /* Chart of Account Submenu Styles */
        .chart-submenu {
            display: none;
            padding-left: 1.5rem;
            border-left: 2px solid var(--border-color);
            margin-left: 1rem;
        }
        
        .chart-submenu .nav-link {
            padding: 0.75rem 1.5rem !important;
            margin: 0.125rem 0;
            border-radius: 6px !important;
            border-left: 2px solid transparent !important;
            background-color: color-mix(in srgb, var(--surface-primary) 80%, var(--accent-primary)) !important;
        }
        
        .chart-submenu .nav-link:hover {
            background-color: var(--hover-bg) !important;
            border-left-color: var(--accent-primary) !important;
            transform: translateX(3px) !important;
        }
        
        .chart-submenu .nav-link.active {
            background: var(--hover-bg) !important;
            border-left-color: var(--accent-primary) !important;
        }
        
        .chart-toggle {
            position: relative;
        }
        
        .chart-toggle::after {
            content: '▼';
            position: absolute;
            right: 1rem;
            font-size: 0.75rem;
            transition: transform 0.3s ease;
        }
        
        .chart-toggle.expanded::after {
            transform: rotate(180deg);
        }

        /* Sales Submenu Styles */
        .sales-submenu {
            display: none;
            padding-left: 1.5rem;
            border-left: 2px solid var(--border-color);
            margin-left: 1rem;
        }
        
        .sales-submenu .nav-link {
            padding: 0.75rem 1.5rem !important;
            margin: 0.125rem 0;
            border-radius: 6px !important;
            border-left: 2px solid transparent !important;
            background-color: color-mix(in srgb, var(--surface-primary) 80%, var(--accent-primary)) !important;
        }
        
        .sales-submenu .nav-link:hover {
            background-color: var(--hover-bg) !important;
            border-left-color: var(--accent-primary) !important;
            transform: translateX(3px) !important;
        }
        
        .sales-submenu .nav-link.active {
            background: var(--hover-bg) !important;
            border-left-color: var(--accent-primary) !important;
        }
        
        .sales-toggle {
            position: relative;
        }
        
        .sales-toggle::after {
            content: '▼';
            position: absolute;
            right: 1rem;
            font-size: 0.75rem;
            transition: transform 0.3s ease;
        }
        
        .sales-toggle.expanded::after {
            transform: rotate(180deg);
        }

        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            min-height: 100vh;
            padding-top: 56px;
            position: relative;
            z-index: 2;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* DateTime Display Widget */
        .datetime-widget {
            position: fixed;
            top: 12px;
            right: 20px;
            border: 1px solid rgba(245, 158, 11, 0.15);
            border-radius: 6px;
            padding: 5px 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            z-index: 10;
            backdrop-filter: blur(6px);
            min-width: 150px;
            transition: all 0.3s ease;
            pointer-events: none;
            opacity: 0.8;
        }

        .datetime-widget:hover {
            opacity: 0.9;
        }

        .datetime-date {
            color: #f59e0b;
            font-size: 0.6rem;
            font-weight: 600;
            letter-spacing: 0.2px;
            margin-bottom: 1px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .datetime-date i {
            font-size: 0.55rem;
        }

        .datetime-time {
            color: rgba(30, 41, 59, 0.75);
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            font-family: 'Roboto', monospace;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .datetime-day {
            color: #94a3b8;
            font-size: 0.55rem;
            font-weight: 500;
            margin-top: 1px;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }

        /* Ensure content has space for datetime widget */
        .main-content > .container-fluid:first-child,
        .main-content > .container:first-child {
            padding-top: 0.5rem;
        }

        /* Ensure datetime widget doesn't interfere with page headers and buttons */
        .main-content .container-fluid > .row:first-child,
        .main-content .container > .row:first-child {
            margin-top: 0;
        }

        @media (max-width: 1200px) {
            .datetime-widget {
                right: 12px;
                min-width: 140px;
            }
        }

        @media (max-width: 768px) {
            .datetime-widget {
                top: 8px;
                right: 8px;
                padding: 4px 8px;
                min-width: 120px;
            }

            .datetime-time {
                font-size: 0.75rem;
            }

            .datetime-date {
                font-size: 0.5rem;
            }

            .datetime-date i {
                font-size: 0.45rem;
            }

            .datetime-day {
                font-size: 0.45rem;
            }
        }

        /* Top Navbar */
        .top-navbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: 64px;
            background: linear-gradient(90deg, #f59e0b 0%, #ea580c 100%);
            box-shadow: 0 8px 32px rgba(245, 158, 11, 0.3);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 999;
            transition: var(--transition);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .top-navbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
            pointer-events: none;
            border-radius: 0 0 8px 0;
        }

        .top-navbar.expanded {
            left: var(--sidebar-collapsed-width);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            color: #000 !important;
            letter-spacing: -0.025em;
            transition: var(--transition);
            display: none;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-dropdown .dropdown-toggle {
            background: none;
            border: none;
            padding: 0.5rem;
            color: var(--gray-700);
            cursor: pointer;
        }

        .user-dropdown .dropdown-toggle:hover {
            color: var(--primary);
        }

        /* Professional Navbar */
        .navbar {
            background-color: var(--white);
            box-shadow: var(--shadow-sm);
            padding: 0.875rem 0;
            border-bottom: 1px solid var(--gray-200);
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(8px);
            background-color: rgba(255, 255, 255, 0.95);
            display: none;
        }

        /* Professional Buttons */
        .btn {
            padding: 0.875rem 1.75rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            font-size: 0.9375rem;
            line-height: 1.5;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            z-index: 1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-transform: none;
            letter-spacing: 0.5px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: 0.5s;
            z-index: -1;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn:active {
            transform: translateY(-1px);
        }

        .btn-sm {
            padding: 0.5rem 1.25rem;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #ffffff;
            border: none;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
            color: #ffffff;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: #ffffff;
            border: none;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
            box-shadow: 0 8px 25px rgba(6, 182, 212, 0.4);
            color: #ffffff;
        }

        .btn-success {
            background: linear-gradient(135deg, #22c55e 0%, #059669 100%);
            color: #ffffff;
            border: none;
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #16a34a 0%, #065f46 100%);
            box-shadow: 0 8px 25px rgba(34, 197, 94, 0.4);
            color: #ffffff;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            color: #ffffff;
            border: none;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
            color: #ffffff;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff;
            border: none;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
            color: #ffffff;
        }

        .btn-info {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #ffffff;
            border: none;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-info:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
            color: #ffffff;
        }

        .btn-outline-primary {
            color: #f59e0b;
            border: 2px solid #f59e0b;
            background-color: transparent;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-color: #d97706;
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3);
        }

        .btn-outline-secondary {
            color: #06b6d4;
            border: 2px solid #06b6d4;
            background-color: transparent;
        }

        .btn-outline-secondary:hover {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            border-color: #0891b2;
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(6, 182, 212, 0.3);
        }

        .btn-outline-danger {
            color: #ef4444;
            border: 2px solid #ef4444;
            background-color: transparent;
        }

        .btn-outline-danger:hover {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border-color: #dc2626;
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
        }

        .btn-outline-success {
            color: #22c55e;
            border: 2px solid #22c55e;
            background-color: transparent;
        }

        .btn-outline-success:hover {
            background: linear-gradient(135deg, #22c55e 0%, #059669 100%);
            border-color: #059669;
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.3);
        }

        .btn-text {
            background: none;
            border: none;
            color: #f59e0b;
            text-decoration: none;
            padding: 0.5rem 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: none;
        }

        .btn-text:hover {
            color: #d97706;
            background-color: #fef3c7;
            border-radius: 6px;
        }

        /* Additional Button Styles */
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
            background-color: transparent;
        }

        .btn-outline-primary:hover {
            background-color: #dbeafe;
            border-color: var(--primary);
            color: var(--primary);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 10%, transparent);
        }

        .btn-outline-secondary {
            color: var(--gray-700);
            border-color: var(--gray-300);
            background-color: white;
        }

        .btn-outline-secondary:hover {
            background-color: #f1f5f9;
            border-color: var(--gray-400);
            color: var(--gray-900);
        }

        .btn-outline-danger {
            color: var(--danger);
            border-color: var(--danger);
            background-color: transparent;
        }

        .btn-outline-danger:hover {
            background-color: #fee2e2;
            border-color: var(--danger);
            color: var(--danger);
        }

        .btn-outline-success {
            color: var(--success);
            border-color: var(--success);
            background-color: transparent;
        }

        .btn-outline-success:hover {
            background-color: #dcfce7;
            border-color: var(--success);
            color: var(--success);
        }

        .btn-text {
            background: none;
            border: none;
            color: var(--primary);
            text-decoration: none;
            padding: 0.5rem 0.75rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-text:hover {
            color: #4f46e5;
            background-color: #dbeafe;
            border-radius: 6px;
        }

        .btn-sm.btn-primary,
        .btn-sm.btn-secondary,
        .btn-sm.btn-danger,
        .btn-sm.btn-warning {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Form Controls Enhancement */
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.875rem 1.125rem;
            transition: all 0.3s ease;
            font-size: 0.9375rem;
            background-color: #ffffff;
            color: var(--gray-900);
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .form-control:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1), inset 0 1px 3px rgba(0, 0, 0, 0.05);
            outline: none;
            background-color: #fffbf0;
        }

        .form-control:hover:not(:focus) {
            border-color: #cbd5e1;
            background-color: #f8fafc;
        }

        .form-control:disabled {
            background-color: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
            border-color: #e5e7eb;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .form-label {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.75rem;
            font-size: 0.9375rem;
            display: block;
        }

        .form-label strong {
            color: #ef4444;
            margin-left: 0.25rem;
        }

        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.875rem 2.5rem 0.875rem 1.125rem;
            transition: all 0.3s ease;
            font-size: 0.9375rem;
            background-color: white;
            color: var(--gray-900);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23f59e0b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 16px 12px;
        }

        .form-select:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
            outline: none;
        }

        .form-check {
            padding-left: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            border: 2px solid #d1d5db;
            border-radius: 5px;
            margin-left: -2rem;
            margin-top: 0;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: #ffffff;
            flex-shrink: 0;
            position: relative;
        }

        .form-check-input:hover {
            border-color: #f59e0b;
            background-color: #fffbf0;
            transform: scale(1.05);
        }

        .form-check-input:checked {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-color: #f59e0b;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='white' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }

        .form-check-input:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15);
            outline: none;
        }

        .form-check-input:active {
            transform: scale(0.95);
        }

        .form-check-label {
            color: #000000;
            cursor: pointer;
            margin-bottom: 0;
            font-weight: 500;
            user-select: none;
            line-height: 1.5;
        }

        .form-check-label:hover {
            color: #f59e0b;
        }

        .form-text {
            color: var(--gray-600);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: block;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        /* Professional Cards */
        .card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 12px;
            padding: 1px;
            background: linear-gradient(45deg, rgba(245, 158, 11, 0.1), rgba(34, 197, 94, 0.05), rgba(59, 130, 246, 0.05));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: destination-out;
            mask-composite: exclude;
            z-index: -1;
            pointer-events: none;
        }

        .card:hover {
            box-shadow: 0 12px 35px rgba(245, 158, 11, 0.15);
            border-color: #f59e0b;
            transform: translateY(-6px);
        }

        .card-header {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            border-bottom: none;
            padding: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            font-size: 1.125rem;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.6s;
        }

        .card-header:hover::before {
            left: 100%;
        }

        .card-header.bg-light {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
            color: #ffffff !important;
        }
        
        .card-header.border-bottom {
            border-bottom: none !important;
        }

        .card-body {
            padding: 1.75rem;
        }

        .card-footer {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-top: 2px solid #e2e8f0;
            padding: 1.25rem 1.75rem;
        }

        /* Professional Hero Section */
        .hero-section {
            background: var(--gradient-blue-5);
            color: #000;
            padding: 4rem 20px;
            position: relative;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--gray-200);
        }

        .hero-section .container {
            position: relative;
            z-index: 1;
        }

        .hero-section h1 {
            font-size: clamp(2rem, 4vw, 2.75rem);
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
            letter-spacing: -0.025em;
        }

        .hero-section .lead {
            font-size: 1.125rem;
            opacity: 0.9;
            font-weight: 400;
            line-height: 1.6;
        }

        /* Professional Badges */
        .badge, .badge-role {
            display: inline-block;
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
            font-weight: 700;
            line-height: 1;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .badge-role {
            background: linear-gradient(135deg, #22c55e 0%, #059669 100%);
            color: #ffffff;
        }

        .badge-admin {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff;
        }

        .badge-user {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: #ffffff;
        }

        .badge.bg-success {
            background: linear-gradient(135deg, #22c55e 0%, #059669 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .badge.bg-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .badge.bg-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .badge.bg-info {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .badge.bg-primary {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        /* Status Badge Enhancement */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .status-badge.active {
            background: linear-gradient(135deg, #22c55e 0%, #059669 100%);
            color: #ffffff;
        }

        .status-badge.inactive {
            background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
            color: #ffffff;
        }

        .status-badge.pending {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            color: #ffffff;
        }

        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .status-badge.active .status-dot {
            background-color: #ffffff;
        }

        .status-badge.inactive .status-dot {
            background-color: #ffffff;
        }

        .status-badge.pending .status-dot {
            background-color: #ffffff;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.6;
            }
        }

        /* Professional Forms */
        .form-control {
            border: 1px solid color-mix(in srgb, var(--primary) 30%, var(--gray-300));
            border-radius: 8px;
            padding: 0.875rem 1rem;
            transition: var(--transition);
            font-size: 0.9375rem;
            background: linear-gradient(145deg, color-mix(in srgb, var(--primary) 5%, white), white);
            color: var(--gray-900);
            position: relative;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.025);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 10%, transparent), inset 0 1px 2px rgba(0, 0, 0, 0.05);
            outline: none;
            background: linear-gradient(145deg, color-mix(in srgb, var(--primary) 8%, white), white);
        }

        .form-control:hover {
            border-color: color-mix(in srgb, var(--primary) 50%, var(--gray-400));
        }

        .form-label {
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 10%, transparent);
        }

        /* Professional Alerts */
        .alert {
            border: none;
            border-radius: 10px;
            padding: 1.25rem 1.5rem;
            border-left: 5px solid;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .alert::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
        }

        .alert-success {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border-left-color: #22c55e;
            color: #166534;
        }

        .alert-success strong {
            color: #166534;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-left-color: #ef4444;
            color: #991b1b;
        }

        .alert-danger strong {
            color: #991b1b;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fef08a 100%);
            border-left-color: #f59e0b;
            color: #92400e;
        }

        .alert-warning strong {
            color: #92400e;
        }

        .alert-info {
            background: linear-gradient(135deg, #cffafe 0%, #a5f3fc 100%);
            border-left-color: #06b6d4;
            color: #164e63;
        }

        .alert-info strong {
            color: #164e63;
        }

        .alert-custom {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border-left-color: #f59e0b;
            color: var(--gray-900);
        }

        .alert:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, #cffafe 0%, #a5f3fc 100%);
            border-left: 5px solid #06b6d4;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            color: #164e63;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.15);
            transition: all 0.3s ease;
        }

        .info-box.warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fef08a 100%);
            border-left-color: #f59e0b;
            color: #92400e;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.15);
        }

        .info-box.success {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border-left-color: #22c55e;
            color: #166534;
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.15);
        }

        .info-box.danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-left-color: #ef4444;
            color: #991b1b;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.15);
        }

        .info-box strong {
            color: inherit;
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .info-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        /* Professional Footer */
        footer {
            background: linear-gradient(135deg, var(--gray-800), var(--gray-900));
            color: var(--gray-400);
            margin-top: 5rem;
            padding: 2.5rem 20px;
            text-align: center;
            border-top: 1px solid var(--gray-700);
            box-shadow: 0 -5px 15px -10px rgba(0, 0, 0, 0.1);
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
        }

        footer p {
            margin: 0.25rem 0;
            font-size: 0.875rem;
        }

        /* Professional Tables */
        .table {
            background-color: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }

        .table thead {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
        }

        .table thead th {
            border-bottom: none;
            padding: 1.25rem 1rem;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.1em;
            color: #ffffff;
            background-color: transparent;
            position: relative;
        }

        .table thead th:first-child {
            border-top-left-radius: 12px;
        }

        .table thead th:last-child {
            border-top-right-radius: 12px;
        }

        .table tbody tr {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-bottom: 1px solid #e2e8f0;
        }

        .table tbody tr:last-child {
            border-bottom: none;
        }

        .table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 12px;
        }

        .table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 12px;
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, #fef3c7 0%, #fef08a 100%);
            transform: scale(1.02);
            box-shadow: inset 0 0 0 2px #f59e0b;
        }

        .table tbody td {
            padding: 1.25rem 1rem;
            color: var(--gray-900);
            vertical-align: middle;
            font-weight: 500;
        }

        .table-hover tbody tr:hover {
            background-color: transparent;
        }

        /* Alternative table styling with stripes */
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9fafb;
        }

        .table-striped tbody tr:hover {
            background: linear-gradient(135deg, #fef3c7 0%, #fef08a 100%);
        }

        /* Pagination Styles */
        .pagination {
            gap: 0.75rem;
        }

        .pagination .page-link {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            color: var(--gray-700);
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 0 2px;
            background-color: #ffffff;
            padding: 0.75rem 1rem;
            min-width: 40px;
            text-align: center;
        }

        .pagination .page-link:hover {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-color: #d97706;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-color: #d97706;
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3);
        }

        .pagination .page-item.disabled .page-link {
            color: #9ca3af;
            border-color: #e5e7eb;
            background-color: #f9fafb;
            cursor: not-allowed;
        }

        /* Utilities */
        .text-muted {
            color: var(--gray-600) !important;
        }

        .text-success {
            color: var(--success) !important;
        }

        .text-warning {
            color: var(--warning) !important;
        }

        .text-danger {
            color: var(--danger) !important;
        }

        .fw-bold {
            font-weight: 700 !important;
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
            outline: 2px solid var(--primary);
            outline-offset: 2px;
            border-radius: 2px;
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

        /* Professional Section Headers */
        h1, h2 {
            color: var(--gray-900);
            font-weight: 700;
            letter-spacing: -0.025em;
            margin-bottom: 1.5rem;
        }

        h3, h4, h5, h6 {
            color: var(--gray-800);
            font-weight: 600;
        }

        /* Page Title Styling */
        .page-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 2rem 0 1.5rem;
            border-bottom: 2px solid #f1f5f9;
            margin-bottom: 2rem;
        }

        .page-title h2 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-title-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary) 0%, #4f46e5 100%);
            color: white;
            font-size: 1.25rem;
        }

        /* Content Section Spacing */
        .content-section {
            margin-bottom: 3rem;
        }

        .content-section:last-child {
            margin-bottom: 2rem;
        }

        /* Professional List Items */
        .list-group-item {
            border: 2px solid #e2e8f0;
            padding: 1.25rem;
            transition: all 0.3s ease;
            background-color: white;
            border-radius: 8px;
            margin-bottom: 0.75rem;
        }

        .list-group-item:hover {
            background: linear-gradient(135deg, #fffbf0 0%, #f8fafc 100%);
            border-color: #f59e0b;
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.15);
            transform: translateX(4px);
        }

        /* Professional Page Wrapper */
        .page-container {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        /* Section Title with Color */
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid #f59e0b;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
        }

        .section-title::before {
            content: '';
            position: absolute;
            left: 0;
            bottom: -3px;
            height: 3px;
            width: 60px;
            background: linear-gradient(90deg, #f59e0b, #22c55e, #06b6d4);
            border-radius: 2px;
        }

        .section-title i {
            color: #f59e0b;
            font-size: 1.75rem;
        }

        /* Data Display Card */
        .data-card {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .data-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #f59e0b 0%, #ea580c 100%);
            border-radius: 12px 0 0 12px;
        }

        .data-card:hover {
            box-shadow: 0 12px 35px rgba(245, 158, 11, 0.2);
            border-color: #f59e0b;
            transform: translateY(-4px);
        }

        .data-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .data-card-title {
            font-weight: 700;
            color: var(--gray-900);
            font-size: 1.125rem;
        }

        .data-card-body {
            color: var(--gray-700);
            line-height: 1.6;
        }

        .data-card-footer {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px solid #f1f5f9;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        /* Info Box with Gradients */
        .info-box {
            background: linear-gradient(135deg, #cffafe 0%, #a5f3fc 100%);
            border-left: 5px solid #06b6d4;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            color: #164e63;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.15);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .info-box::before {
            content: '';
            position: absolute;
            top: 0;
            right: -50px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .info-box.warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fef08a 100%);
            border-left-color: #f59e0b;
            color: #92400e;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.15);
        }

        .info-box.success {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border-left-color: #22c55e;
            color: #166534;
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.15);
        }

        .info-box.danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-left-color: #ef4444;
            color: #991b1b;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.15);
        }

        .info-box strong {
            color: inherit;
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .info-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .status-badge.active {
            background: linear-gradient(135deg, #22c55e 0%, #059669 100%);
            color: #ffffff;
        }

        .status-badge.inactive {
            background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
            color: #ffffff;
        }

        .status-badge.pending {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            color: #ffffff;
        }

        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .status-badge.active .status-dot {
            background-color: #ffffff;
        }

        .status-badge.inactive .status-dot {
            background-color: #ffffff;
        }

        .status-badge.pending .status-dot {
            background-color: #ffffff;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.6;
            }
        }

        /* Professional Divider */
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 2rem 0;
            border: none;
        }

        .divider.thin {
            margin: 1rem 0;
            height: 1px;
        }

        /* Content Padding */
        .content-wrapper {
            padding: 1.5rem 0;
        }

        /* Button Group */
        .btn-group.flex {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        /* Link Button */
        a.link-button {
            color: #f59e0b;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
        }

        a.link-button:hover {
            color: #d97706;
            text-decoration: underline;
            padding-left: 1rem;
        }

        a.link-button i {
            transition: transform 0.3s ease;
        }

        a.link-button:hover i {
            transform: translateX(4px);
        }

        /* Professional Typography */
        .text-primary-dark {
            color: var(--gray-900);
            font-weight: 700;
        }

        .text-secondary-dark {
            color: var(--gray-700);
            font-weight: 600;
        }

        .text-tertiary {
            color: var(--gray-600);
            font-weight: 400;
        }

        /* Loading Spinner */
        .spinner-border {
            color: #f59e0b;
        }

        /* Utility Classes */
        .text-muted {
            color: var(--gray-600) !important;
        }

        .text-success {
            color: #22c55e !important;
        }

        .text-warning {
            color: #f59e0b !important;
        }

        .text-danger {
            color: #ef4444 !important;
        }

        .text-info {
            color: #06b6d4 !important;
        }

        .fw-bold {
            font-weight: 700 !important;
        }

        .fw-semibold {
            font-weight: 600 !important;
        }

        .fs-larger {
            font-size: 1.125rem !important;
        }

        .fs-small {
            font-size: 0.875rem !important;
        }

        /* Action Buttons in Tables */
        .btn-group-action {
            display: flex;
            gap: 0.5rem;
        }

        /* Search/Filter Bar */
        .search-bar {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .search-bar .form-group {
            flex: 1;
            min-width: 250px;
            margin-bottom: 0;
        }

        /* Modal Enhancement */
        .modal-content {
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, #4f46e5 100%);
            color: white;
            border-bottom: none;
            border-radius: 12px 12px 0 0;
            padding: 1.5rem;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 2rem 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid var(--gray-200);
            padding: 1.5rem;
            background-color: #f8fafc;
            border-radius: 0 0 12px 12px;
        }

        /* Breadcrumb Enhancement */
        .breadcrumb {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--gray-200);
        }

        .breadcrumb-item {
            color: var(--gray-600);
        }

        .breadcrumb-item.active {
            color: var(--gray-900);
            font-weight: 500;
        }

        .breadcrumb-item a {
            color: var(--primary);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: #4f46e5;
            text-decoration: underline;
        }

        /* Detail Section */
        .detail-item {
            padding: 1rem 0;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: var(--gray-700);
            min-width: 150px;
        }

        .detail-value {
            color: var(--gray-900);
            font-weight: 500;
        }

        /* Status Badge Enhancement */
        .badge {
            padding: 0.5rem 0.875rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: capitalize;
        }

        .badge.bg-success {
            background-color: #dcfce7 !important;
            color: #166534 !important;
        }

        .badge.bg-warning {
            background-color: #fef3c7 !important;
            color: #92400e !important;
        }

        .badge.bg-danger {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
        }

        .badge.bg-info {
            background-color: #cffafe !important;
            color: #164e63 !important;
        }

        .badge.bg-primary {
            background-color: #dbeafe !important;
            color: #1e40af !important;
        }

        /* Form Group Enhancement */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.75rem;
            font-size: 0.9375rem;
        }

        /* Card Shadow Enhancement */
        .card {
            border: 1px solid #e2e8f0;
        }

        .card:hover {
            border-color: #cbd5e1;
        }

        /* Responsive Improvements */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 1002;
                width: 280px;
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.5);
            }

            .sidebar.active {
                transform: translateX(0);
            }
            
            /* Always show full content on mobile - override collapsed state */
            .sidebar .nav-link span,
            .sidebar .sidebar-brand span,
            .sidebar .user-info span {
                display: inline !important;
            }
            
            .sidebar .sidebar-brand {
                justify-content: flex-start !important;
                padding: 1.5rem !important;
            }
            
            .sidebar .sidebar-brand i {
                margin-right: 0.75rem !important;
            }
            
            .sidebar .nav-link i {
                margin-right: 0.75rem !important;
            }
            
            .sidebar .user-actions {
                display: block !important;
            }
            
            /* Disable hover expansion on mobile */
            .sidebar.collapsed:hover {
                width: 280px;
            }
            
            /* Disable collapsed state on mobile */
            .sidebar.collapsed {
                width: 280px;
                transform: translateX(-100%);
            }
            
            .sidebar.collapsed.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding-top: 64px;
            }

            .main-content.sidebar-open {
                transform: none;
            }
            
            .main-content.expanded {
                margin-left: 0;
            }

            .top-navbar {
                left: 0;
                padding-left: 60px;
                z-index: 1001;
            }
            
            .top-navbar.expanded {
                left: 0;
            }
            
            .mobile-menu-toggle {
                z-index: 1003;
            }

            .hero-section {
                padding: 2rem 15px;
            }

            .card {
                margin-bottom: 1rem;
            }

            .navbar-brand {
                font-size: 1.125rem;
            }

            .page-header {
                padding: 1.25rem 1rem;
                margin-bottom: 1.25rem;
            }

            .page-header h1,
            .page-header h2 {
                font-size: 1.375rem;
                gap: 0.5rem;
            }
            
            /* Table Responsiveness */
            .table-responsive {
                margin: 0 -0.5rem;
                border-radius: 0;
            }

            .table {
                font-size: 0.8125rem;
                min-width: 100%;
            }

            .table thead th,
            .table tbody td {
                padding: 0.625rem 0.4rem;
                white-space: nowrap;
            }
            
            .table thead th:first-child,
            .table tbody td:first-child {
                padding-left: 0.75rem;
            }
            
            .table thead th:last-child,
            .table tbody td:last-child {
                padding-right: 0.75rem;
            }

            .btn-group.flex {
                flex-direction: row;
                flex-wrap: wrap;
                gap: 0.25rem;
            }

            .btn-group.flex .btn {
                flex: 1;
                min-width: auto;
            }

            .action-bar {
                flex-direction: column;
                gap: 0.75rem;
            }

            .action-bar-left,
            .action-bar-right {
                width: 100%;
                flex-direction: row;
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            
            .action-bar-left .btn,
            .action-bar-right .btn {
                flex: 1;
                min-width: 120px;
            }

            .modal-dialog {
                margin: 0.5rem;
            }

            .modal-body {
                padding: 1.25rem 1rem;
            }

            .filter-section {
                padding: 0.875rem;
            }

            .data-card {
                padding: 1rem;
            }

            .section-title {
                font-size: 1.0625rem;
            }

            .detail-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .detail-label {
                margin-bottom: 0.375rem;
            }
            
            /* Card Layout Adjustments */
            .row > [class*='col-'] {
                margin-bottom: 1rem;
            }
            
            .supplier-card,
            .dashboard-card {
                margin-bottom: 1rem;
            }
            
            /* Form Responsiveness */
            .form-row,
            .row.mb-3 {
                margin-left: -0.5rem;
                margin-right: -0.5rem;
            }
            
            .form-row > [class*='col-'],
            .row.mb-3 > [class*='col-'] {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            
            /* Button Group Responsiveness */
            .btn-toolbar {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            
            .btn-group {
                flex-wrap: wrap;
            }
            
            /* Pagination */
            .pagination {
                flex-wrap: wrap;
                gap: 0.25rem;
                justify-content: center;
            }
            
            .pagination .page-link {
                padding: 0.375rem 0.625rem;
                font-size: 0.875rem;
            }
        }

        @media (max-width: 576px) {
            .container-fluid {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .card-body {
                padding: 0.875rem;
            }

            .page-header {
                padding: 0.875rem 0.75rem;
                margin-bottom: 0.875rem;
            }

            .page-header h1,
            .page-header h2 {
                font-size: 1.125rem;
            }

            .btn-sm {
                padding: 0.375rem 0.625rem;
                font-size: 0.75rem;
            }
            
            .btn {
                padding: 0.625rem 1rem;
                font-size: 0.8125rem;
            }

            .table {
                font-size: 0.75rem;
            }
            
            .table thead th,
            .table tbody td {
                padding: 0.5rem 0.3rem;
            }

            .section-title {
                font-size: 0.9375rem;
                margin-bottom: 0.75rem;
            }

            .badge {
                font-size: 0.625rem;
                padding: 0.275rem 0.45rem;
            }

            .alert {
                padding: 0.625rem 0.75rem;
                font-size: 0.8125rem;
            }
            
            /* Forms on small screens */
            .form-control,
            .form-select {
                padding: 0.625rem 0.75rem;
                font-size: 0.8125rem;
            }
            
            .form-label {
                font-size: 0.8125rem;
                margin-bottom: 0.375rem;
            }
            
            /* Smaller inputs for mobile */
            input[type="date"],
            input[type="time"],
            input[type="number"] {
                font-size: 0.875rem;
            }
            
            /* Action buttons stacked */
            .action-bar-left,
            .action-bar-right {
                flex-direction: column;
            }
            
            .action-bar-left .btn,
            .action-bar-right .btn,
            .action-bar-left .btn-group,
            .action-bar-right .btn-group {
                width: 100%;
            }
            
            /* Card adjustments */
            .card {
                margin-bottom: 0.875rem;
                border-radius: 8px;
            }
            
            .card-header {
                padding: 0.75rem 0.875rem;
                font-size: 0.875rem;
            }
            
            /* Top navbar adjustments */
            .top-navbar {
                padding: 0 50px 0 50px;
                height: 56px;
            }
            
            .nav-right {
                gap: 0.5rem;
            }
            
            .nav-right .btn {
                padding: 0.5rem 0.75rem;
                font-size: 0.75rem;
            }
            
            /* Table action buttons */
            .table .btn-sm {
                padding: 0.25rem 0.4rem;
                font-size: 0.7rem;
            }
            
            .table .btn-group {
                gap: 0.125rem;
            }
            
            /* Modal adjustments */
            .modal-dialog {
                margin: 0.25rem;
                max-width: calc(100% - 0.5rem);
            }
            
            .modal-header {
                padding: 0.875rem 1rem;
            }
            
            .modal-title {
                font-size: 1rem;
            }
            
            .modal-body {
                padding: 0.875rem;
            }
            
            .modal-footer {
                padding: 0.75rem;
                gap: 0.5rem;
            }
            
            .modal-footer .btn {
                padding: 0.5rem 1rem;
                font-size: 0.8125rem;
            }
            
            /* Stat cards */
            .card-stat {
                padding: 0.875rem;
            }
            
            .card-stat-number {
                font-size: 1.5rem;
            }
            
            .card-stat-icon {
                font-size: 1.75rem;
            }
            
            .card-stat-label {
                font-size: 0.75rem;
            }
            
            /* Breadcrumb */
            .breadcrumb {
                padding: 0.625rem 0.875rem;
                font-size: 0.8125rem;
            }
            
            /* Info boxes */
            .info-box {
                padding: 0.875rem;
                font-size: 0.8125rem;
            }
            
            .data-card {
                padding: 0.75rem;
            }
            
            .data-card-title {
                font-size: 0.9375rem;
            }
            
            /* Status badges */
            .status-badge {
                padding: 0.3rem 0.625rem;
                font-size: 0.7rem;
            }
            
            /* Supplier/Bank cards on home */
            .supplier-card .card-body,
            .balance-card .card-body {
                padding: 0.875rem;
            }
            
            .supplier-card h6,
            .balance-card h6 {
                font-size: 0.875rem;
            }
            
            .supplier-card h3,
            .balance-card h3 {
                font-size: 1.25rem;
            }
            
            /* Filter buttons */
            .btn-group.flex {
                width: 100%;
            }
            
            .btn-group.flex .btn {
                flex: 1;
                padding: 0.5rem 0.5rem;
                font-size: 0.75rem;
            }
            
            /* Row spacing */
            .row {
                margin-left: -0.375rem;
                margin-right: -0.375rem;
            }
            
            .row > [class*='col-'] {
                padding-left: 0.375rem;
                padding-right: 0.375rem;
            }
            
            /* Typography */
            h1 {
                font-size: 1.375rem !important;
            }
            
            h2 {
                font-size: 1.125rem !important;
            }
            
            h3 {
                font-size: 1rem !important;
            }
            
            h4 {
                font-size: 0.9375rem !important;
            }
            
            h5 {
                font-size: 0.875rem !important;
            }
            
            h6 {
                font-size: 0.8125rem !important;
            }
            
            /* Sidebar on very small screens */
            .sidebar {
                width: 85%;
                max-width: 280px;
            }
            
            .sidebar.active {
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.6);
            }
            
            /* Ensure sidebar scrolls properly on small screens */
            .sidebar {
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            /* Disable collapsed hover on mobile */
            .sidebar.collapsed:hover {
                width: 85%;
                max-width: 280px;
            }
            
            /* Hide some secondary info on very small screens */
            .table .text-muted.small {
                display: none;
            }
            
            /* Pagination compact */
            .pagination .page-item .page-link {
                padding: 0.3rem 0.5rem;
                font-size: 0.75rem;
            }
        }

        /* Mobile sidebar overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 1001;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }
        
        /* Mobile Toggle Button */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 12px;
            left: 12px;
            z-index: 1003;
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 12px;
            color: white;
            font-size: 1.25rem;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            -webkit-tap-highlight-color: transparent;
        }
        
        .mobile-menu-toggle:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(245, 158, 11, 0.5);
        }
        
        .mobile-menu-toggle:active {
            transform: scale(0.95);
        }
        
        .mobile-menu-toggle i {
            transition: transform 0.3s ease;
        }
        
        .mobile-menu-toggle.active i {
            transform: rotate(90deg);
        }
        
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .top-navbar {
                left: 0;
                padding-left: 60px;
            }
            
            .top-navbar.expanded {
                left: 0;
            }
        }
        
        /* Responsive Utility Classes */
        @media (max-width: 768px) {
            .d-md-none-mobile {
                display: none !important;
            }
            
            .mobile-full-width {
                width: 100% !important;
            }
            
            .mobile-text-center {
                text-align: center !important;
            }
            
            .mobile-mt-3 {
                margin-top: 1rem !important;
            }
            
            .mobile-mb-3 {
                margin-bottom: 1rem !important;
            }
            
            /* Touch-friendly elements */
            .btn, .nav-link, a, button, input[type="submit"], input[type="button"] {
                -webkit-tap-highlight-color: rgba(245, 158, 11, 0.2);
                touch-action: manipulation;
            }
            
            /* Minimum touch target size */
            .btn, .nav-link {
                min-height: 44px;
                min-width: 44px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            
            /* Better form controls on mobile */
            .form-control, .form-select {
                min-height: 44px;
            }
            
            /* Larger checkboxes and radio buttons */
            input[type="checkbox"],
            input[type="radio"] {
                min-width: 20px;
                min-height: 20px;
            }
        }
        
        @media (max-width: 576px) {
            .d-sm-none-mobile {
                display: none !important;
            }
            
            .mobile-p-2 {
                padding: 0.5rem !important;
            }
        }
        
        /* Improved Table Overflow Handling */
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                border-radius: 8px;
            }
            
            .table-responsive::-webkit-scrollbar {
                height: 6px;
            }
            
            .table-responsive::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 3px;
            }
            
            .table-responsive::-webkit-scrollbar-thumb {
                background: #f59e0b;
                border-radius: 3px;
            }
            
            .table-responsive::-webkit-scrollbar-thumb:hover {
                background: #d97706;
            }
        }
        
        /* Dropdown menu styles */
        .dropdown-menu {
            background-color: var(--surface-primary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: var(--shadow-lg);
            color: var(--text-primary);
        }
        
        .dropdown-item {
            color: var(--text-primary);
            padding: 0.75rem 1.5rem;
            transition: var(--transition);
            border-radius: 6px;
            margin: 2px 8px;
        }
        
        .dropdown-item:hover {
            background-color: var(--hover-bg);
            color: var(--accent-primary);
        }
        
        .dropdown-item.active {
            background-color: var(--hover-bg);
            color: var(--accent-primary);
            font-weight: 500;
        }
        
        .dropdown-divider {
            border-top: 1px solid var(--divider-color);
            margin: 0.5rem 0;
        }

        /* ====== PROFESSIONAL PAGE STYLING ====== */

        /* Main Content Container Enhancement */
        .main-content {
            background-color: #f8fafc;
        }

        /* Page Container */
        .page-wrapper {
            padding: 0;
        }

        /* Professional Heading with Icon Support */
        .page-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            padding: 2rem 1.5rem;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 2rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .page-header h1,
        .page-header h2 {
            margin: 0 0 0.5rem 0;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-header .subtitle {
            color: var(--gray-600);
            font-size: 0.9375rem;
            margin: 0;
        }

        /* Section Title */
        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i {
            color: var(--primary);
            font-size: 1.5rem;
        }

        /* Card Enhancement */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            border-color: #cbd5e1;
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
            color: white;
            font-weight: 600;
            padding: 1.25rem 1.5rem;
            border: none;
            font-size: 1.0625rem;
        }

        .card-header.bg-light {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
            color: var(--gray-900) !important;
            border-bottom: 2px solid #e2e8f0;
        }

        .card-body {
            padding: 1.75rem;
        }

        .card-footer {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
            color: var(--gray-600);
        }

        .empty-state i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
            display: block;
        }

        .empty-state h4 {
            color: var(--gray-900);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        /* Grid Row Spacing */
        .row {
            margin-bottom: 1.5rem;
        }

        .col-md-6,
        .col-md-4,
        .col-lg-3 {
            margin-bottom: 1.5rem;
        }

        /* Information Row */
        .info-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Action Bar */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .action-bar-left,
        .action-bar-right {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        /* Filter Section */
        .filter-section {
            background-color: white;
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            margin-bottom: 2rem;
        }

        .filter-section .form-group {
            margin-bottom: 1rem;
        }

        .filter-section .form-group:last-child {
            margin-bottom: 0;
        }

        /* Data Display Card */
        .data-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .data-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: #cbd5e1;
        }

        .data-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .data-card-title {
            font-weight: 600;
            color: var(--gray-900);
            font-size: 1.0625rem;
        }

        .data-card-body {
            color: var(--gray-700);
        }

        .data-card-footer {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, #dbeafe 0%, #e0f2fe 100%);
            border-left: 4px solid #0ea5e9;
            padding: 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            color: var(--gray-900);
        }

        .info-box.warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fef08a 100%);
            border-left-color: var(--warning);
        }

        .info-box.success {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border-left-color: var(--success);
        }

        .info-box.danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-left-color: var(--danger);
        }

        .info-box strong {
            color: var(--gray-900);
            display: block;
            margin-bottom: 0.25rem;
        }

        /* Status Indicator */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .status-badge.active {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-badge.inactive {
            background-color: #f3f4f6;
            color: #6b7280;
        }

        .status-badge.pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .status-badge.active .status-dot {
            background-color: #16a34a;
        }

        .status-badge.inactive .status-dot {
            background-color: #9ca3af;
        }

        .status-badge.pending .status-dot {
            background-color: #f59e0b;
        }

        /* Professional Divider */
        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 2rem 0;
        }

        .divider.thin {
            margin: 1rem 0;
        }

        /* Content Padding */
        .content-wrapper {
            padding: 1.5rem 0;
        }

        /* Button Group */
        .btn-group.flex {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* Link Button */
        a.link-button {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        a.link-button:hover {
            color: #4f46e5;
            text-decoration: underline;
        }

        /* Professional Typography */
        .text-primary-dark {
            color: var(--gray-900);
            font-weight: 600;
        }

        .text-secondary-dark {
            color: var(--gray-700);
            font-weight: 500;
        }

        .text-tertiary {
            color: var(--gray-600);
            font-weight: 400;
        }

        /* Loading Spinner */
        .spinner-border {
            color: var(--primary);
        }

        /* Advanced Animations */
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        .card {
            animation: fadeInScale 0.4s ease-out;
        }

        .alert {
            animation: slideInDown 0.3s ease-out;
        }

        .badge {
            animation: fadeInScale 0.3s ease-out;
        }

        /* Enhanced Hover Effects */
        .btn {
            position: relative;
        }

        .btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:active::after {
            width: 300px;
            height: 300px;
        }

        /* Professional Spacing */
        .container-fluid {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .mt-6 {
            margin-top: 4rem !important;
        }

        .mb-6 {
            margin-bottom: 4rem !important;
        }

        .px-6 {
            padding-left: 2.5rem !important;
            padding-right: 2.5rem !important;
        }

        .py-6 {
            padding-top: 2.5rem !important;
            padding-bottom: 2.5rem !important;
        }

        /* Professional Text Utilities */
        .text-uppercase-sm {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .text-lead {
            font-size: 1.25rem;
            line-height: 1.6;
            color: var(--gray-700);
        }

        /* Advanced Card Layouts */
        .card-group-horizontal {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .card-stat {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.75rem;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-stat:hover {
            box-shadow: 0 12px 35px rgba(245, 158, 11, 0.2);
            border-color: #f59e0b;
            transform: translateY(-8px);
        }

        .card-stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .card-stat-label {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .card-stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #f59e0b;
        }

        /* Table Enhancements */
        .table-hover tbody tr:hover {
            background: linear-gradient(135deg, #fef3c7 0%, #fef08a 100%);
        }

        .table thead {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* Modal Enhancements */
        .modal-header {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            color: white;
            border: none;
            padding: 1.75rem;
        }

        .modal-title {
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            background-color: #f8fafc;
            border-top: 2px solid #e2e8f0;
            padding: 1.5rem;
        }

        .modal-content {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .close {
            color: white !important;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .close:hover {
            opacity: 1;
        }

        /* Breadcrumb Enhancement */
        .breadcrumb {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 8px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            border: 2px solid #e2e8f0;
            font-size: 0.9375rem;
        }

        .breadcrumb-item {
            color: var(--gray-600);
        }

        .breadcrumb-item.active {
            color: var(--gray-900);
            font-weight: 600;
        }

        .breadcrumb-item a {
            color: #f59e0b;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: #d97706;
            text-decoration: underline;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.25rem 1rem;
                margin-bottom: 1.25rem;
                border-radius: 8px;
            }

            .page-header h1,
            .page-header h2 {
                font-size: 1.375rem;
            }

            .action-bar {
                flex-direction: column;
                align-items: stretch;
                gap: 0.75rem;
            }

            .action-bar-left,
            .action-bar-right {
                width: 100%;
                justify-content: flex-start;
            }

            .action-bar-left button,
            .action-bar-right button,
            .action-bar-left a,
            .action-bar-right a {
                flex: 1;
                min-width: 120px;
            }

            .info-row {
                grid-template-columns: 1fr;
                gap: 0.875rem;
            }

            .table {
                font-size: 0.8125rem;
            }

            .table thead th {
                padding: 0.625rem 0.4rem;
            }

            .table tbody td {
                padding: 0.625rem 0.4rem;
            }

            .detail-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.375rem;
            }

            .detail-label {
                margin-bottom: 0;
            }

            .filter-section {
                padding: 0.875rem;
                border-radius: 8px;
            }

            .data-card {
                padding: 0.875rem;
            }

            .card-group-horizontal {
                grid-template-columns: 1fr;
                gap: 0.875rem;
            }

            .card-stat {
                padding: 1.125rem;
            }

            .card-stat-number {
                font-size: 1.875rem;
            }

            .modal-body {
                padding: 1.25rem 1rem;
            }

            .modal-header {
                padding: 1.25rem 1rem;
            }

            h1 {
                font-size: 1.625rem !important;
            }

            h2 {
                font-size: 1.375rem !important;
            }

            h3 {
                font-size: 1.125rem !important;
            }

            .container-fluid {
                padding-left: 0.875rem;
                padding-right: 0.875rem;
            }
            
            /* Dashboard specific */
            .dashboard-stat-card {
                margin-bottom: 1rem;
            }
            
            /* Status filter buttons */
            .btn-group[role="group"] {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            
            .btn-group[role="group"] .btn {
                flex: 1;
                min-width: 100px;
            }
        }

        @media (max-width: 576px) {
            .container-fluid {
                padding-left: 0.625rem;
                padding-right: 0.625rem;
            }

            .card-body {
                padding: 0.875rem;
            }

            .card-header {
                padding: 0.875rem;
                font-size: 0.875rem;
            }

            .page-header {
                padding: 0.875rem;
                margin-bottom: 0.875rem;
                border-radius: 6px;
            }

            .page-header h1,
            .page-header h2 {
                font-size: 1.125rem;
            }

            .btn-sm {
                padding: 0.3rem 0.625rem;
                font-size: 0.75rem;
            }

            .btn {
                padding: 0.625rem 1rem;
                font-size: 0.8125rem;
            }

            .table {
                font-size: 0.7rem;
            }

            .table thead th,
            .table tbody td {
                padding: 0.4rem 0.25rem;
                font-size: 0.7rem;
            }

            .section-title {
                font-size: 0.9375rem;
            }

            .badge {
                font-size: 0.625rem;
                padding: 0.25rem 0.45rem;
            }

            .alert {
                padding: 0.625rem 0.875rem;
                font-size: 0.8125rem;
            }

            .form-control,
            .form-select {
                padding: 0.625rem;
                font-size: 0.8125rem;
            }

            .form-label {
                font-size: 0.8125rem;
            }

            h1 {
                font-size: 1.25rem !important;
            }

            h2 {
                font-size: 1.0625rem !important;
            }

            h3 {
                font-size: 0.9375rem !important;
            }

            .data-card {
                padding: 0.75rem;
            }

            .data-card-title {
                font-size: 0.875rem;
            }

            .info-box {
                padding: 0.875rem;
                font-size: 0.8125rem;
            }

            .status-badge {
                padding: 0.3rem 0.625rem;
                font-size: 0.7rem;
            }

            .btn-group.flex .btn {
                padding: 0.4rem 0.75rem;
                font-size: 0.75rem;
            }

            .modal-body {
                padding: 0.875rem;
            }

            .modal-footer {
                padding: 0.625rem;
            }

            .card-stat {
                padding: 0.875rem;
            }

            .card-stat-number {
                font-size: 1.5rem;
            }

            .card-stat-icon {
                font-size: 1.75rem;
            }

            .breadcrumb {
                padding: 0.625rem 0.875rem;
                font-size: 0.8125rem;
            }
            
            /* Compact action buttons */
            .action-bar-left,
            .action-bar-right {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .action-bar .btn {
                width: 100%;
                justify-content: center;
            }
            
            /* Mobile table actions */
            .table .btn-group {
                flex-direction: column;
                gap: 0.125rem;
            }
            
            .table .btn-group .btn {
                width: 100%;
            }
            
            /* Compact cards */
            .card {
                margin-bottom: 0.75rem;
            }
            
            /* Responsive grid */
            .row > .col-md-3,
            .row > .col-md-4,
            .row > .col-md-6 {
                margin-bottom: 0.75rem;
            }
        }

        /* Professional Checkbox Styling - Global */
        .custom-checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .custom-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #f59e0b;
            border: 2px solid #d1d5db;
            border-radius: 4px;
        }

        .custom-checkbox:checked {
            background-color: #f59e0b;
            border-color: #f59e0b;
        }

        .custom-checkbox:hover {
            border-color: #f59e0b;
        }

        .custom-checkbox:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
        }

        .custom-checkbox-label {
            cursor: pointer;
            user-select: none;
            font-weight: 500;
            color: #000000;
        }

        /* Apply to all checkboxes globally */
        input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #f59e0b;
        }

        input[type="checkbox"]:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
        }

        /* Add border to all input elements */
        input {
            border: 1px solid #ced4da !important;
        }
    </style>
</head>
<body class="@yield('body_class')">
    <!-- Mobile Menu Toggle Button -->
    <button class="mobile-menu-toggle" id="mobileSidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="/" class="sidebar-brand">
                <i class="fas fa-gas-pump"></i>
                <span>Fuel Station</span>
            </a>
            <button class="sidebar-collapse-btn" id="sidebarCollapse">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            @auth
                @if (auth()->user()->role === 'admin')
                    <div class="nav-item">
                        <a class="nav-link manage-toggle" href="#" role="button" onclick="toggleManageItems(); return false;">
                            <i class="fas fa-cog"></i>
                            <span>Management</span>
                        </a>
                        <div id="manageItems" class="manage-submenu">
                            <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" style="padding-left: 2.5rem;">
                                <i class="fas fa-users"></i>
                                <span>Users</span>
                            </a>
                            <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers*') ? 'active' : '' }}" style="padding-left: 2.5rem;">
                                <i class="fas fa-truck"></i>
                                <span>Suppliers</span>
                            </a>
                            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories*') ? 'active' : '' }}" style="padding-left: 2.5rem;">
                                <i class="fas fa-tags"></i>
                                <span>Categories</span>
                            </a>
                            <a href="{{ route('items.index') }}" class="nav-link {{ request()->routeIs('items*') ? 'active' : '' }}" style="padding-left: 2.5rem;">
                                <i class="fas fa-box"></i>
                                <span>Items</span>
                            </a>
                            <a href="{{ route('banks.index') }}" class="nav-link {{ request()->routeIs('banks*') ? 'active' : '' }}" style="padding-left: 2.5rem;">
                                <i class="fas fa-university"></i>
                                <span>Banks</span>
                            </a>
                            <a href="{{ route('vehicles.index') }}" class="nav-link {{ request()->routeIs('vehicles*') ? 'active' : '' }}" style="padding-left: 2.5rem;">
                                <i class="fas fa-car"></i>
                                <span>Vehicles</span>
                            </a>
                            <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees*') ? 'active' : '' }}" style="padding-left: 2.5rem;">
                                <i class="fas fa-users"></i>
                                <span>Employees</span>
                            </a>
                            <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers*') ? 'active' : '' }}" style="padding-left: 2.5rem;">
                                <i class="fas fa-user-friends"></i>
                                <span>Customers</span>
                            </a>
                            <a href="{{ route('tanks.index') }}" class="nav-link {{ request()->routeIs('tanks*') ? 'active' : '' }}" style="padding-left: 2.5rem;">
                                <i class="fas fa-oil-can"></i>
                                <span>Tanks</span>
                            </a>
                            <a href="{{ route('meters.index') }}" class="nav-link {{ request()->routeIs('meters*') ? 'active' : '' }}" style="padding-left: 2.5rem;">
                                <i class="fas fa-gauge"></i>
                                <span>Meters</span>
                            </a>
                            <a href="{{ route('pumps.index') }}" class="nav-link {{ request()->routeIs('pumps*') ? 'active' : '' }}" style="padding-left: 2.5rem;">
                                <i class="fas fa-gas-pump"></i>
                                <span>Pumps</span>
                            </a>
                        </div>
                    </div>

                    <div class="nav-item">
                        <a class="nav-link purchase-toggle" href="#" role="button" onclick="togglePurchaseItems(); return false;">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Purchase</span>
                        </a>
                        <div id="purchaseItems" class="purchase-submenu">
                            <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments*') ? 'active' : '' }}" style="padding-left: 2.5rem;">
                                <i class="fas fa-credit-card"></i>
                                <span>Payment</span>
                            </a>
                            <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.index') || request()->routeIs('invoices.show') || request()->routeIs('invoices.create') || request()->routeIs('invoices.edit') ? 'active' : '' }}" style="padding-left: 2.5rem;">
                                <i class="fas fa-file-invoice"></i>
                                <span>Invoice</span>
                            </a>
                            <a href="{{ route('invoices.setoffManagement') }}" class="nav-link {{ request()->routeIs('invoices.setoffManagement') || request()->routeIs('invoices.showSetoff') || request()->routeIs('invoices.processSetoff') ? 'active' : '' }}" style="padding-left: 2.5rem;">
                                <i class="fas fa-exchange-alt"></i>
                                <span>Setoff</span>
                            </a>
                        </div>
                    </div>


                    <!-- Chart of Account Section -->
                    <div class="nav-item">
                        <a class="nav-link chart-toggle" href="#" role="button" onclick="toggleChartItems(); return false;">
                            <i class="fas fa-chart-line"></i>
                            <span>Chart of Account</span>
                        </a>
                        <div id="chartItems" class="chart-submenu">
                            @php
                                $chartSuppliers = \App\Models\Supplier::orderBy('name')->get();
                            @endphp
                            @foreach($chartSuppliers as $chartSupplier)
                                <a href="{{ route('chart-of-account.show', $chartSupplier->id) }}" 
                                   class="nav-link {{ request()->routeIs('chart-of-account.show') && request()->route('supplier') == $chartSupplier->id ? 'active' : '' }}" 
                                   style="padding-left: 2.5rem;">
                                    <i class="fas fa-user-tie"></i>
                                    <span>{{ $chartSupplier->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>


                    <!-- Sales Section -->
                    <div class="nav-item">
                        <a class="nav-link sales-toggle" href="#" role="button" onclick="toggleSalesItems(); return false;">
                            <i class="fas fa-cash-register"></i>
                            <span>Sales</span>
                        </a>
                        <div id="salesItems" class="sales-submenu">
                            <a href="{{ route('sales.create') }}" class="nav-link {{ request()->routeIs('sales.create') ? 'active' : '' }}" style="padding-left: 2.5rem;">
                                <i class="fas fa-plus-circle"></i>
                                <span>Create</span>
                            </a>
                            <a href="{{ route('sales.status') }}" class="nav-link {{ request()->routeIs('sales.status') || request()->routeIs('sales.show') ? 'active' : '' }}" style="padding-left: 2.5rem;">
                                <i class="fas fa-list-alt"></i>
                                <span>Status</span>
                            </a>
                        </div>
                    </div>


                    <div class="nav-item">
                        <a class="nav-link stock-toggle" href="#" role="button" onclick="toggleStockItems(); return false;">
                            <i class="fas fa-warehouse"></i>
                            <span>Stock</span>
                        </a>
                        <div id="stockItems" class="stock-submenu">
                            @php
                                $stockCategories = \App\Models\Category::active()->orderBy('name')->get();
                            @endphp
                            @foreach($stockCategories as $stockCategory)
                                <a href="{{ route('stock.category', $stockCategory->id) }}" 
                                   class="nav-link {{ request()->routeIs('stock.category') && request()->route('categoryId') == $stockCategory->id ? 'active' : '' }}" 
                                   style="padding-left: 2.5rem;">
                                    <i class="fas fa-box"></i>
                                    <span>{{ $stockCategory->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    
    
                    
                    <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                @endif
            @endauth


        </nav>

        <div class="sidebar-user">
            @auth
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span>{{ auth()->user()->name }}</span>
            </div>
            <div class="user-actions mt-2">
                
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light w-100">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            </div>
            @else
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span>Guest User</span>
            </div>
            <div class="user-actions mt-2">
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light w-100 mb-2">
                    <i class="fas fa-sign-in-alt me-1"></i> Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-sm btn-outline-light w-100">
                    <i class="fas fa-user-plus me-1"></i> Register
                </a>
            </div>
            @endauth
        </div>
    </aside>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- DateTime Widget -->
    <div class="datetime-widget">
        <div class="datetime-date">
            <i class="fas fa-calendar-alt"></i>
            <span id="currentDate"></span>
        </div>
        <div class="datetime-time" id="currentTime"></div>
        <div class="datetime-day" id="currentDay"></div>
    </div>

    <!-- Top Navbar - REMOVED AS REQUESTED -->
    <!-- <nav class="top-navbar">
        <div class="navbar-brand">
            <i class="fas fa-gas-pump"></i> Fuel Station
        </div>
        <div class="nav-right">
            @auth
            <div class="user-dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle fa-lg"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item" type="submit">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @else
            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                <i class="fas fa-sign-in-alt me-1"></i> Login
            </a>
            @endauth
        </div>
    </nav> -->

    <!-- Main Content -->
    <main class="main-content">
        <!-- Alert Messages -->
        <div class="container-fluid mt-4">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <strong>Error!</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show alert-custom text-black" role="alert">
                    <i class="fas fa-check-circle text-black"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-times-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <div class="container-fluid mt-3">
            @yield('content')
        </div>

     
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleManageItems() {
            const submenu = document.getElementById('manageItems');
            const toggleBtn = document.querySelector('.manage-toggle');
            submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
            
            // Toggle the expanded class on the button
            if (submenu.style.display === 'block') {
                toggleBtn.classList.add('expanded');
                // Store the expanded state
                localStorage.setItem('manageSubmenuExpanded', 'true');
            } else {
                toggleBtn.classList.remove('expanded');
                // Clear the stored state
                localStorage.removeItem('manageSubmenuExpanded');
            }
        }

        function togglePurchaseItems() {
            const submenu = document.getElementById('purchaseItems');
            const toggleBtn = document.querySelector('.purchase-toggle');
            submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
            
            // Toggle the expanded class on the button
            if (submenu.style.display === 'block') {
                toggleBtn.classList.add('expanded');
                // Store the expanded state
                localStorage.setItem('purchaseSubmenuExpanded', 'true');
            } else {
                toggleBtn.classList.remove('expanded');
                // Clear the stored state
                localStorage.removeItem('purchaseSubmenuExpanded');
            }
        }

        function toggleStockItems() {
            const submenu = document.getElementById('stockItems');
            const toggleBtn = document.querySelector('.stock-toggle');
            submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
            
            // Toggle the expanded class on the button
            if (submenu.style.display === 'block') {
                toggleBtn.classList.add('expanded');
                // Store the expanded state
                localStorage.setItem('stockSubmenuExpanded', 'true');
            } else {
                toggleBtn.classList.remove('expanded');
                // Clear the stored state
                localStorage.removeItem('stockSubmenuExpanded');
            }
        }

        function toggleChartItems() {
            const submenu = document.getElementById('chartItems');
            const toggleBtn = document.querySelector('.chart-toggle');
            submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
            
            // Toggle the expanded class on the button
            if (submenu.style.display === 'block') {
                toggleBtn.classList.add('expanded');
                // Store the expanded state
                localStorage.setItem('chartSubmenuExpanded', 'true');
            } else {
                toggleBtn.classList.remove('expanded');
                // Clear the stored state
                localStorage.removeItem('chartSubmenuExpanded');
            }
        }

        function toggleSalesItems() {
            const submenu = document.getElementById('salesItems');
            const toggleBtn = document.querySelector('.sales-toggle');
            submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
            
            // Toggle the expanded class on the button
            if (submenu.style.display === 'block') {
                toggleBtn.classList.add('expanded');
                // Store the expanded state
                localStorage.setItem('salesSubmenuExpanded', 'true');
            } else {
                toggleBtn.classList.remove('expanded');
                // Clear the stored state
                localStorage.removeItem('salesSubmenuExpanded');
            }
        }
        
        // Close submenu when clicking outside
        document.addEventListener('click', function(event) {
            const manageToggle = document.querySelector('.manage-toggle');
            const submenu = document.getElementById('manageItems');
            
            // Check if the clicked element is not within the submenu container or the toggle button itself
            if (!submenu.contains(event.target) && !manageToggle.contains(event.target)) {
                submenu.style.display = 'none';
                manageToggle.classList.remove('expanded');
                // Clear the stored state
                localStorage.removeItem('manageSubmenuExpanded');
            }

            const purchaseToggle = document.querySelector('.purchase-toggle');
            const purchaseSubmenu = document.getElementById('purchaseItems');
            
            // Check if the clicked element is not within the purchase submenu or toggle button
            if (purchaseSubmenu && purchaseToggle && !purchaseSubmenu.contains(event.target) && !purchaseToggle.contains(event.target)) {
                purchaseSubmenu.style.display = 'none';
                purchaseToggle.classList.remove('expanded');
                // Clear the stored state
                localStorage.removeItem('purchaseSubmenuExpanded');
            }

            const stockToggle = document.querySelector('.stock-toggle');
            const stockSubmenu = document.getElementById('stockItems');
            
            // Check if the clicked element is not within the stock submenu or toggle button
            if (stockSubmenu && stockToggle && !stockSubmenu.contains(event.target) && !stockToggle.contains(event.target)) {
                stockSubmenu.style.display = 'none';
                stockToggle.classList.remove('expanded');
                // Clear the stored state
                localStorage.removeItem('stockSubmenuExpanded');
            }

            const chartToggle = document.querySelector('.chart-toggle');
            const chartSubmenu = document.getElementById('chartItems');
            
            // Check if the clicked element is not within the chart submenu or toggle button
            if (chartSubmenu && chartToggle && !chartSubmenu.contains(event.target) && !chartToggle.contains(event.target)) {
                chartSubmenu.style.display = 'none';
                chartToggle.classList.remove('expanded');
                // Clear the stored state
                localStorage.removeItem('chartSubmenuExpanded');
            }

            const salesToggle = document.querySelector('.sales-toggle');
            const salesSubmenu = document.getElementById('salesItems');
            
            // Check if the clicked element is not within the sales submenu or toggle button
            if (salesSubmenu && salesToggle && !salesSubmenu.contains(event.target) && !salesToggle.contains(event.target)) {
                salesSubmenu.style.display = 'none';
                salesToggle.classList.remove('expanded');
                // Clear the stored state
                localStorage.removeItem('salesSubmenuExpanded');
            }
        });
        
        // Restore submenu state on page load
        function restoreManageSubmenuState() {
            const submenu = document.getElementById('manageItems');
            const toggleBtn = document.querySelector('.manage-toggle');
            
            // Check if submenu should be expanded based on stored state
            const isExpanded = localStorage.getItem('manageSubmenuExpanded') === 'true';
            
            // Also check if we're on a manage-related page
            const currentPath = window.location.pathname;
            const isManagePage = currentPath.includes('/admin/users') || currentPath.includes('/suppliers') || currentPath.includes('/categories') || currentPath.includes('/items') || currentPath.includes('/banks') || currentPath.includes('/vehicles') || currentPath.includes('/employees') || currentPath.includes('/customers') || currentPath.includes('/tanks') || currentPath.includes('/meters') || currentPath.includes('/pumps');
            
            if (isExpanded || isManagePage) {
                submenu.style.display = 'block';
                toggleBtn.classList.add('expanded');
                // Ensure the state is stored
                localStorage.setItem('manageSubmenuExpanded', 'true');
            }
        }

        function restorePurchaseSubmenuState() {
            const submenu = document.getElementById('purchaseItems');
            const toggleBtn = document.querySelector('.purchase-toggle');
            
            if (!submenu || !toggleBtn) return;
            
            // Check if submenu should be expanded based on stored state
            const isExpanded = localStorage.getItem('purchaseSubmenuExpanded') === 'true';
            
            // Also check if we're on a purchase-related page
            const currentPath = window.location.pathname;
            const isPurchasePage = currentPath.includes('/payments') || currentPath.includes('/invoices');
            
            if (isExpanded || isPurchasePage) {
                submenu.style.display = 'block';
                toggleBtn.classList.add('expanded');
                // Ensure the state is stored
                localStorage.setItem('purchaseSubmenuExpanded', 'true');
            }
        }

        function restoreStockSubmenuState() {
            const submenu = document.getElementById('stockItems');
            const toggleBtn = document.querySelector('.stock-toggle');
            
            if (!submenu || !toggleBtn) return;
            
            // Check if submenu should be expanded based on stored state
            const isExpanded = localStorage.getItem('stockSubmenuExpanded') === 'true';
            
            // Also check if we're on a stock-related page
            const currentPath = window.location.pathname;
            const isStockPage = currentPath.includes('/stock');
            
            if (isExpanded || isStockPage) {
                submenu.style.display = 'block';
                toggleBtn.classList.add('expanded');
                // Ensure the state is stored
                localStorage.setItem('stockSubmenuExpanded', 'true');
            }
        }

        function restoreChartSubmenuState() {
            const submenu = document.getElementById('chartItems');
            const toggleBtn = document.querySelector('.chart-toggle');
            
            if (!submenu || !toggleBtn) return;
            
            // Check if submenu should be expanded based on stored state
            const isExpanded = localStorage.getItem('chartSubmenuExpanded') === 'true';
            
            // Also check if we're on a chart-related page
            const currentPath = window.location.pathname;
            const isChartPage = currentPath.includes('/chart-of-account');
            
            if (isExpanded || isChartPage) {
                submenu.style.display = 'block';
                toggleBtn.classList.add('expanded');
                // Ensure the state is stored
                localStorage.setItem('chartSubmenuExpanded', 'true');
            }
        }

        function restoreSalesSubmenuState() {
            const submenu = document.getElementById('salesItems');
            const toggleBtn = document.querySelector('.sales-toggle');
            
            if (!submenu || !toggleBtn) return;
            
            // Check if submenu should be expanded based on stored state
            const isExpanded = localStorage.getItem('salesSubmenuExpanded') === 'true';
            
            // Also check if we're on a sales-related page
            const currentPath = window.location.pathname;
            const isSalesPage = currentPath.includes('/sales');
            
            if (isExpanded || isSalesPage) {
                submenu.style.display = 'block';
                toggleBtn.classList.add('expanded');
                // Ensure the state is stored
                localStorage.setItem('salesSubmenuExpanded', 'true');
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Restore submenu state when page loads
            restoreManageSubmenuState();
            restorePurchaseSubmenuState();
            restoreStockSubmenuState();
            restoreChartSubmenuState();
            restoreSalesSubmenuState();
            
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            const sidebarCollapse = document.getElementById('sidebarCollapse');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            // Toggle sidebar
            sidebarCollapse.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                
                // Update icon
                const icon = sidebarCollapse.querySelector('i');
                if (sidebar.classList.contains('collapsed')) {
                    icon.classList.remove('fa-chevron-left');
                    icon.classList.add('fa-chevron-right');
                } else {
                    icon.classList.remove('fa-chevron-right');
                    icon.classList.add('fa-chevron-left');
                }
            });

            // Optional: Add tooltip hints for collapsed sidebar icons
            if (sidebar.classList.contains('collapsed')) {
                const navLinks = sidebar.querySelectorAll('.nav-link');
                navLinks.forEach(link => {
                    const text = link.querySelector('span')?.textContent;
                    if (text) {
                        link.setAttribute('title', text);
                    }
                });
            }

            // Mobile sidebar toggle
            const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
            if (mobileSidebarToggle) {
                mobileSidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isActive = sidebar.classList.toggle('active');
                    sidebarOverlay.classList.toggle('active');
                    mobileSidebarToggle.classList.toggle('active');
                    
                    // Change icon
                    const icon = mobileSidebarToggle.querySelector('i');
                    if (isActive) {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-times');
                    } else {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                    
                    // Prevent body scroll when sidebar is open on mobile
                    if (isActive) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                });
            }

            // Close sidebar on overlay click (mobile)
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                mobileSidebarToggle.classList.remove('active');
                document.body.style.overflow = '';
                
                // Reset icon
                const icon = mobileSidebarToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });

            // Close sidebar on mobile when clicking a nav link
            const navLinks = sidebar.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.remove('active');
                        sidebarOverlay.classList.remove('active');
                        mobileSidebarToggle.classList.remove('active');
                        document.body.style.overflow = '';
                        
                        // Reset icon
                        const icon = mobileSidebarToggle.querySelector('i');
                        if (icon) {
                            icon.classList.remove('fa-times');
                            icon.classList.add('fa-bars');
                        }
                    }
                });
            });
            
            // Handle window resize - close mobile menu and restore scroll
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth > 768) {
                        sidebar.classList.remove('active');
                        sidebarOverlay.classList.remove('active');
                        mobileSidebarToggle.classList.remove('active');
                        document.body.style.overflow = '';
                        
                        // Reset icon
                        const icon = mobileSidebarToggle.querySelector('i');
                        if (icon) {
                            icon.classList.remove('fa-times');
                            icon.classList.add('fa-bars');
                        }
                    }
                }, 250);
            });
        });

        // Custom Confirmation Modal System
        let confirmCallback = null;

        function showConfirmModal(message, callback) {
            confirmCallback = callback;
            document.getElementById('confirmModalMessage').textContent = message;
            const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            modal.show();
        }

        function confirmAction() {
            if (confirmCallback) {
                confirmCallback();
                confirmCallback = null;
            }
            const modal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
            modal.hide();
        }

        function cancelAction() {
            confirmCallback = null;
            const modal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
            modal.hide();
        }

        // Handle all status toggle forms
        document.addEventListener('DOMContentLoaded', function() {
            const statusForms = document.querySelectorAll('form[data-confirm-message]');
            statusForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const message = this.getAttribute('data-confirm-message');
                    showConfirmModal(message, () => {
                        this.submit();
                    });
                });
            });
        });
    </script>

    <!-- Custom Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border: 2px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);">
                <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%); border: none; padding: 1.75rem 2rem;">
                    <h5 class="modal-title d-flex align-items-center" id="confirmModalLabel" style="font-weight: 700; color: white; font-size: 1.25rem;">
                        <i class="fas fa-exclamation-triangle me-3" style="font-size: 1.5rem;"></i>
                        Confirm Action
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1); opacity: 0.9;"></button>
                </div>
                <div class="modal-body" style="padding: 2.5rem 2rem; background: white;">
                    <p id="confirmModalMessage" style="color: #1e293b; font-size: 1.0625rem; line-height: 1.7; margin: 0; font-weight: 500;"></p>
                </div>
                <div class="modal-footer" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-top: 2px solid #e2e8f0; padding: 1.5rem 2rem; gap: 1rem;">
                    <button type="button" class="btn btn-secondary" onclick="cancelAction()" style="
                        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
                        border: none;
                        padding: 0.625rem 1.75rem;
                        font-weight: 600;
                        border-radius: 8px;
                        transition: all 0.3s ease;
                        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                    ">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-warning" onclick="confirmAction()" style="
                        background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
                        border: none;
                        padding: 0.625rem 1.75rem;
                        font-weight: 600;
                        color: white;
                        border-radius: 8px;
                        transition: all 0.3s ease;
                        box-shadow: 0 2px 8px rgba(249, 115, 22, 0.3);
                    ">
                        <i class="fas fa-check me-2"></i>Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Screensaver -->
    <div id="screensaver" style="
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #000000;
        z-index: 9999;
        display: none;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        cursor: pointer;
    ">
        <div style="text-align: center;">
            <!-- Analog Clock -->
            <svg id="analog-clock" width="400" height="400" viewBox="0 0 400 400" style="margin-bottom: 2rem; filter: drop-shadow(0 0 30px rgba(255, 255, 255, 0.2));">
                <!-- Clock face -->
                <circle cx="200" cy="200" r="190" fill="none" stroke="#ffffff" stroke-width="4" opacity="0.3"/>
                <circle cx="200" cy="200" r="180" fill="none" stroke="#ffffff" stroke-width="2" opacity="0.2"/>
                
                <!-- Hour markers -->
                <g id="hour-markers">
                    <!-- 12 -->
                    <line x1="200" y1="30" x2="200" y2="50" stroke="#ffffff" stroke-width="4" stroke-linecap="round"/>
                    <!-- 1 -->
                    <line x1="252" y1="41" x2="245" y2="59" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
                    <!-- 2 -->
                    <line x1="296" y1="71" x2="282" y2="85" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
                    <!-- 3 -->
                    <line x1="370" y1="200" x2="350" y2="200" stroke="#ffffff" stroke-width="4" stroke-linecap="round"/>
                    <!-- 4 -->
                    <line x1="296" y1="329" x2="282" y2="315" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
                    <!-- 5 -->
                    <line x1="252" y1="359" x2="245" y2="341" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
                    <!-- 6 -->
                    <line x1="200" y1="370" x2="200" y2="350" stroke="#ffffff" stroke-width="4" stroke-linecap="round"/>
                    <!-- 7 -->
                    <line x1="148" y1="359" x2="155" y2="341" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
                    <!-- 8 -->
                    <line x1="104" y1="329" x2="118" y2="315" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
                    <!-- 9 -->
                    <line x1="30" y1="200" x2="50" y2="200" stroke="#ffffff" stroke-width="4" stroke-linecap="round"/>
                    <!-- 10 -->
                    <line x1="104" y1="71" x2="118" y2="85" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
                    <!-- 11 -->
                    <line x1="148" y1="41" x2="155" y2="59" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
                </g>
                
                <!-- Minute markers -->
                <g id="minute-markers" opacity="0.4">
                    <circle cx="200" cy="25" r="2" fill="#ffffff"/>
                    <circle cx="241" cy="33" r="2" fill="#ffffff"/>
                    <circle cx="278" cy="53" r="2" fill="#ffffff"/>
                    <circle cx="308" cy="81" r="2" fill="#ffffff"/>
                    <circle cx="331" cy="115" r="2" fill="#ffffff"/>
                    <circle cx="347" cy="153" r="2" fill="#ffffff"/>
                    <circle cx="355" cy="194" r="2" fill="#ffffff"/>
                    <circle cx="375" cy="200" r="2" fill="#ffffff"/>
                    <circle cx="355" cy="206" r="2" fill="#ffffff"/>
                    <circle cx="347" cy="247" r="2" fill="#ffffff"/>
                    <circle cx="331" cy="285" r="2" fill="#ffffff"/>
                    <circle cx="308" cy="319" r="2" fill="#ffffff"/>
                    <circle cx="278" cy="347" r="2" fill="#ffffff"/>
                    <circle cx="241" cy="367" r="2" fill="#ffffff"/>
                    <circle cx="200" cy="375" r="2" fill="#ffffff"/>
                    <circle cx="159" cy="367" r="2" fill="#ffffff"/>
                    <circle cx="122" cy="347" r="2" fill="#ffffff"/>
                    <circle cx="92" cy="319" r="2" fill="#ffffff"/>
                    <circle cx="69" cy="285" r="2" fill="#ffffff"/>
                    <circle cx="53" cy="247" r="2" fill="#ffffff"/>
                    <circle cx="45" cy="206" r="2" fill="#ffffff"/>
                    <circle cx="25" cy="200" r="2" fill="#ffffff"/>
                    <circle cx="45" cy="194" r="2" fill="#ffffff"/>
                    <circle cx="53" cy="153" r="2" fill="#ffffff"/>
                    <circle cx="69" cy="115" r="2" fill="#ffffff"/>
                    <circle cx="92" cy="81" r="2" fill="#ffffff"/>
                    <circle cx="122" cy="53" r="2" fill="#ffffff"/>
                    <circle cx="159" cy="33" r="2" fill="#ffffff"/>
                </g>
                
                <!-- Clock hands -->
                <g id="clock-hands">
                    <!-- Hour hand -->
                    <line id="hour-hand" x1="200" y1="200" x2="200" y2="110" 
                          stroke="#ffffff" stroke-width="8" stroke-linecap="round" 
                          style="transform-origin: 200px 200px; transition: transform 0.5s cubic-bezier(0.4, 0.0, 0.2, 1);"/>
                    
                    <!-- Minute hand -->
                    <line id="minute-hand" x1="200" y1="200" x2="200" y2="70" 
                          stroke="#ffffff" stroke-width="6" stroke-linecap="round" 
                          style="transform-origin: 200px 200px; transition: transform 0.5s cubic-bezier(0.4, 0.0, 0.2, 1);"/>
                    
                    <!-- Second hand -->
                    <line id="second-hand" x1="200" y1="200" x2="200" y2="60" 
                          stroke="#f59e0b" stroke-width="3" stroke-linecap="round" 
                          style="transform-origin: 200px 200px; transition: transform 0.1s linear;"/>
                </g>
                
                <!-- Center dot -->
                <circle cx="200" cy="200" r="12" fill="#ffffff"/>
                <circle cx="200" cy="200" r="8" fill="#000000"/>
                <circle cx="200" cy="200" r="4" fill="#f59e0b"/>
            </svg>
            
            <!-- Digital time and date -->
            <div id="screensaver-time" style="
                font-size: 3.5rem;
                font-weight: 300;
                color: #ffffff;
                font-family: 'Roboto', sans-serif;
                letter-spacing: 0.1em;
                margin-bottom: 1rem;
                text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
            "></div>
            <div id="screensaver-date" style="
                font-size: 1.5rem;
                font-weight: 300;
                color: #ffffff;
                font-family: 'Poppins', sans-serif;
                letter-spacing: 0.05em;
                opacity: 0.8;
            "></div>
        </div>
    </div>

    <script>
        // Screensaver functionality
        (function() {
            let inactivityTimer;
            const INACTIVITY_TIMEOUT = 60000; // 1 minute in milliseconds
            const screensaver = document.getElementById('screensaver');
            const screensaverTime = document.getElementById('screensaver-time');
            const screensaverDate = document.getElementById('screensaver-date');
            const hourHand = document.getElementById('hour-hand');
            const minuteHand = document.getElementById('minute-hand');
            const secondHand = document.getElementById('second-hand');
            let screensaverInterval;

            function updateScreensaverClock() {
                const now = new Date();
                const hours = now.getHours();
                const minutes = now.getMinutes();
                const seconds = now.getSeconds();
                
                // Calculate angles for analog clock
                const secondAngle = (seconds * 6); // 360/60 = 6 degrees per second
                const minuteAngle = (minutes * 6) + (seconds * 0.1); // 6 degrees per minute + smooth movement
                const hourAngle = (hours % 12) * 30 + (minutes * 0.5); // 30 degrees per hour + smooth movement
                
                // Update analog clock hands
                if (secondHand) secondHand.style.transform = `rotate(${secondAngle}deg)`;
                if (minuteHand) minuteHand.style.transform = `rotate(${minuteAngle}deg)`;
                if (hourHand) hourHand.style.transform = `rotate(${hourAngle}deg)`;
                
                // Format digital time (HH:MM:SS)
                const hoursStr = String(hours).padStart(2, '0');
                const minutesStr = String(minutes).padStart(2, '0');
                const secondsStr = String(seconds).padStart(2, '0');
                screensaverTime.textContent = `${hoursStr}:${minutesStr}:${secondsStr}`;
                
                // Format date (Day, Month DD, YYYY)
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                screensaverDate.textContent = now.toLocaleDateString('en-US', options);
            }

            function showScreensaver() {
                screensaver.style.display = 'flex';
                updateScreensaverClock();
                screensaverInterval = setInterval(updateScreensaverClock, 1000);
            }

            function hideScreensaver() {
                screensaver.style.display = 'none';
                if (screensaverInterval) {
                    clearInterval(screensaverInterval);
                }
            }

            function resetInactivityTimer() {
                clearTimeout(inactivityTimer);
                
                // Hide screensaver if it's showing
                if (screensaver.style.display === 'flex') {
                    hideScreensaver();
                }
                
                // Set new timer
                inactivityTimer = setTimeout(showScreensaver, INACTIVITY_TIMEOUT);
            }

            // Listen for user activity
            const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
            events.forEach(event => {
                document.addEventListener(event, resetInactivityTimer, true);
            });

            // Click on screensaver to hide it
            screensaver.addEventListener('click', function() {
                hideScreensaver();
                resetInactivityTimer();
            });

            // Start the inactivity timer
            resetInactivityTimer();
        })();

        // DateTime Widget Update
        function updateDateTime() {
            const now = new Date();
            
            // Format date (e.g., "February 3, 2026")
            const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = now.toLocaleDateString('en-US', dateOptions);
            
            // Format time (e.g., "02:45:30 PM")
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            const timeStr = now.toLocaleTimeString('en-US', timeOptions);
            
            // Format day (e.g., "MONDAY")
            const dayOptions = { weekday: 'long' };
            const dayStr = now.toLocaleDateString('en-US', dayOptions);
            
            // Update DOM elements
            document.getElementById('currentDate').textContent = dateStr;
            document.getElementById('currentTime').textContent = timeStr;
            document.getElementById('currentDay').textContent = dayStr;
        }
        
        // Update immediately when page loads
        updateDateTime();
        
        // Update every second
        setInterval(updateDateTime, 1000);
    </script>
</body>
</html>