@extends('layouts.guest')

@section('content')
    <h2>Login</h2>
    @if($errors->any())<div class="error"><strong>{{ $errors->first() }}</strong></div>@endif
    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <label>Email:</label>
        <input type="email" name="email" required autofocus>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
@endsection
