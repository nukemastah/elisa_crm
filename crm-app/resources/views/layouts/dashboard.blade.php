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
            color: #000;
        }
        h1 { color: #f5f5f5
        } 
        h2, h3, h4, h5, h6, p, a, label, input, textarea, select {
            color: #000;
        }
        /* Keep sidebar h2 white */
        .sidebar h2 {
            color: white !important;
        }
        input, textarea, select {
            color: #faf4f4ff;
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
            display: flex;
            flex-direction: column;
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
            flex: 1;
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
        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar-footer form {
            margin: 0;
        }
        .sidebar-footer button {
            width: 100%;
            background: #e74c3c;
            color: white;
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }
        .sidebar-footer button:hover {
            background: #c0392b;
        }
        /* Live search form styling */
        .live-search-form input {
            padding: 0.5rem 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.95rem;
        }
        .live-search-form input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
        }
        .live-search-form .search-spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #667eea;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 0.5rem;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        /* Override link colors to blue - except sidebar and header */
        main a {
            color: #3498db !important;
            text-decoration: underline !important;
        }
        main a:visited {
            color: #2980b9 !important;
        }
        main a:hover {
            color: #2980b9 !important;
        }
        /* Keep sidebar links white */
        .sidebar a {
            color: #ecf0f1 !important;
        }
        .sidebar a:visited {
            color: #ecf0f1 !important;
        }
        /* Keep header title white */
        header h1 {
            color: white !important;
        }
        main {
            padding: 2rem;
            background: #f5f5f5;
            min-height: calc(100vh - 80px);
        }
        /* Toast */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #d4edda;
            color: #155724;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            z-index: 200;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity .3s ease, transform .3s ease;
        }
        .toast.show { opacity: 1; transform: translateY(0); }
        /* Modal */
        .modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 300; display: none; }
        .modal { position: fixed; inset: 0; display: none; align-items: center; justify-content: center; z-index: 301; }
        .modal .dialog { background: #fff; border-radius: 8px; width: 90%; max-width: 420px; box-shadow: 0 10px 30px rgba(0,0,0,.2); }
        .modal .dialog header { padding: 1rem 1.25rem; border-bottom: 1px solid #eee; font-weight: 600; }
        .modal .dialog .body { padding: 1rem 1.25rem; color: #333; }
        .modal .dialog footer { padding: .75rem 1.25rem; display: flex; gap: .5rem; justify-content: flex-end; border-top: 1px solid #eee; }
        .btn { padding: .5rem .9rem; border-radius: 6px; border: none; cursor: pointer; }
        .btn-danger { background: #e74c3c; color: #fff; }
        .btn-secondary { background: #e5e7eb; color: #111827; }
        /* Table styling: white background with subtle borders */
        table {
            width: 100%;
            background: #ffffff;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #e5e7eb; /* light gray border */
            padding: 0.6rem 0.8rem;
            vertical-align: top;
        }
        thead th {
            background: #fafafa; /* very light header */
            font-weight: 600;
        }
        tbody tr:nth-child(even),
        tbody tr:nth-child(odd) {
            background: transparent; /* remove zebra striping */
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
    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</aside>

<div class="main-container" id="mainContainer">
    <header>
        <button class="burger-btn" id="burgerBtn"></button>
        <h1>PT.Smart CRM</h1>
    </header>
    <main>
        @if(session('status'))<div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">{{ session('status') }}</div>@endif
        <div id="toast" class="toast" style="display:none;">{{ session('success') }}</div>
        @yield('content')
    </main>
</div>

<!-- Delete confirm modal -->
<div class="modal-backdrop" id="confirmBackdrop"></div>
<div class="modal" id="confirmModal" role="dialog" aria-modal="true" aria-labelledby="confirmTitle">
    <div class="dialog">
        <header id="confirmTitle">Confirm Deletion</header>
        <div class="body">
            <p id="confirmMessage">Are you sure?</p>
        </div>
        <footer>
            <button class="btn btn-secondary" id="confirmCancel" type="button">Cancel</button>
            <button class="btn btn-danger" id="confirmOk" type="button">Delete</button>
        </footer>
    </div>
  
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

    // Toast display
    const toast = document.getElementById('toast');
    if (toast && toast.textContent.trim().length > 0) {
        toast.style.display = 'block';
        requestAnimationFrame(() => toast.classList.add('show'));
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.style.display = 'none', 300);
        }, 3000);
    }

    // Live search - auto submit form when typing
    const searchForms = document.querySelectorAll('.live-search-form');
    searchForms.forEach(form => {
        const searchInput = form.querySelector('input[name="q"]');
        const spinner = form.querySelector('.search-spinner');
        let searchTimeout;

        if (searchInput) {
            searchInput.addEventListener('input', () => {
                if (spinner) spinner.style.display = 'inline-block';
                
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    form.submit();
                }, 300); // Delay 300ms to avoid too many requests
            });
        }
    });

    // Delete confirm modal (custom)
    const deleteForms = document.querySelectorAll('form.js-delete');
    const confirmModal = document.getElementById('confirmModal');
    const confirmBackdrop = document.getElementById('confirmBackdrop');
    const confirmMessage = document.getElementById('confirmMessage');
    const btnOk = document.getElementById('confirmOk');
    const btnCancel = document.getElementById('confirmCancel');
    let pendingForm = null;

    function openConfirm(message) {
        confirmMessage.textContent = message;
        confirmBackdrop.style.display = 'block';
        confirmModal.style.display = 'flex';
    }
    function closeConfirm() {
        confirmBackdrop.style.display = 'none';
        confirmModal.style.display = 'none';
        pendingForm = null;
    }
    deleteForms.forEach(f => {
        f.addEventListener('submit', (e) => {
            e.preventDefault();
            pendingForm = f;
            const name = f.getAttribute('data-name') || 'this item';
            openConfirm(`Delete ${name}? This action cannot be undone.`);
        });
    });
    btnCancel.addEventListener('click', closeConfirm);
    confirmBackdrop.addEventListener('click', closeConfirm);
    btnOk.addEventListener('click', () => {
        if (pendingForm) {
            // Prevent double-submit button presses
            const btn = pendingForm.querySelector('button[type="submit"]');
            if (btn) btn.disabled = true;
            pendingForm.submit();
            closeConfirm();
        }
    });

    // Disable submit buttons on form submit to prevent duplicates
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', (e) => {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) { btn.disabled = true; }
        });
    });
</script>
</body>
</html>
