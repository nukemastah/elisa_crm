@extends('layouts.app')

@section('content')
    <h2>Login</h2>
    @if($errors->any())<div><strong>{{ $errors->first() }}</strong></div>@endif
    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <label>Email <input type="email" name="email" required></label>
        <label>Password <input type="password" name="password" required></label>
        <button type="submit">Login</button>
    </form>
@endsection
