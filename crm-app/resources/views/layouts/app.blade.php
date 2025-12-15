<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PT.Smart CRM</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<body>
<header>
    <h1>PT.Smart CRM</h1>
    <nav>
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('leads.index') }}">Leads</a>
        <a href="{{ route('products.index') }}">Products</a>
        <a href="{{ route('projects.index') }}">Projects</a>
        <a href="{{ route('customers.index') }}">Customers</a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline">@csrf <button type="submit">Logout</button></form>
    </nav>
</header>
<main>
    @if(session('status'))<div>{{ session('status') }}</div>@endif
    @yield('content')
</main>
<footer>
    <small>PT.Smart CRM - Minimal Demo</small>
</footer>
</body>
</html>
