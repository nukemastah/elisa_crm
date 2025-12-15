<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PT.Smart CRM</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            display: flex;
            min-height: 100vh;
            font-family: system-ui, -apple-system, sans-serif;
        }
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            overflow-y: auto;
            transition: left 0.3s ease;
        }
        .sidebar.hidden {
            left: -250px;
        }
        .sidebar h2 {
            text-align: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        .sidebar nav {
            display: flex;
            flex-direction: column;
        }
        .sidebar a {
            padding: 1rem 1.5rem;
            color: #ecf0f1;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
            display: block;
        }
        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: #3498db;
        }
        .sidebar a.active {
            background: rgba(52, 152, 219, 0.2);
            border-left-color: #3498db;
        }
        .burger-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 1rem;
        }
        .burger-btn.active::after {
            content: "✕";
        }
        .burger-btn::after {
            content: "☰";
        }
        .main-container {
            flex: 1;
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }
        .main-container.sidebar-hidden {
            margin-left: 0;
        }
        header {
            background: white;
            border-bottom: 1px solid #e0e0e0;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        header h1 {
            font-size: 1.5rem;
            color: #2c3e50;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .header-right form {
            margin: 0;
        }
        .header-right button {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }
        .header-right button:hover {
            background: #c0392b;
        }
        main {
            padding: 2rem;
            background: #f5f5f5;
            min-height: calc(100vh - 80px);
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99;
        }
        .sidebar-overlay.active {
            display: block;
        }
        @media (max-width: 768px) {
            .burger-btn {
                display: block;
            }
            .sidebar {
                z-index: 100;
            }
            .main-container {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="sidebar">
    <h2>PT.Smart CRM</h2>
    <nav>
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('leads.index') }}" class="{{ request()->routeIs('leads.*') ? 'active' : '' }}">Leads</a>
        <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Products</a>
        <a href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.*') ? 'active' : '' }}">Projects</a>
        <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">Customers</a>
    </nav>
</aside>

<div class="main-container" id="mainContainer">
    <header>
        <button class="burger-btn" id="burgerBtn"></button>
        <h1>PT.Smart CRM</h1>
        <div class="header-right">
            <form action="{{ route('logout') }}" method="POST">@csrf <button type="submit">Logout</button></form>
        </div>
    </header>
    <main>
        @if(session('status'))<div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">{{ session('status') }}</div>@endif
        @yield('content')
    </main>
</div>

<script>
    const burgerBtn = document.getElementById('burgerBtn');
    const sidebar = document.getElementById('sidebar');
    const mainContainer = document.getElementById('mainContainer');
    const overlay = document.getElementById('sidebarOverlay');

    function toggleSidebar() {
        sidebar.classList.toggle('hidden');
        mainContainer.classList.toggle('sidebar-hidden');
        burgerBtn.classList.toggle('active');
        overlay.classList.toggle('active');
    }

    burgerBtn.addEventListener('click', toggleSidebar);
    overlay.addEventListener('click', toggleSidebar);

    // Close sidebar when a link is clicked on mobile
    if (window.innerWidth <= 768) {
        sidebar.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', toggleSidebar);
        });
    }
</script>
</body>
</html>
