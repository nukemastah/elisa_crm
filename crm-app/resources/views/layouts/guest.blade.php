<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PT.Smart CRM</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        main {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
            font-size: 2rem;
            font-weight: bold;
        }
        h2 {
            color: #1a1a1a;
            margin-bottom: 1.5rem;
        }
        .subtitle {
            text-align: center;
            color: #444;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }
        label {
            color: #1a1a1a;
            font-weight: 500;
            display: block;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        input, textarea, select {
            padding: 0.75rem;
            border: 1px solid #b69696ff;
            border-radius: 4px;
            font-size: 1rem;
            color: #a58888ff;
        }
        button {
            padding: 0.75rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            opacity: 0.9;
        }
        .error {
            background: #fee;
            color: #800;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            font-weight: 500;
        }
        p {
            color: #000;
        }
    </style>
</head>
<body>
<main>
    <h1>PT.Smart CRM</h1>
    <p class="subtitle">Sales Management System</p>
    @if(session('status'))<div class="error">{{ session('status') }}</div>@endif
    @yield('content')
</main>
</body>
</html>
