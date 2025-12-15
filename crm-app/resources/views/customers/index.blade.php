@extends('layouts.app')
@section('content')
  <h2>Customers</h2>
  <a href="{{ route('customers.create') }}">Add Customer</a>
  <ul>
    @foreach($customers as $c)
      <li>{{ $c->name }} — Services: {{ $c->services->pluck('id')->join(', ') }}</li>
    @endforeach
  </ul>
@endsection
