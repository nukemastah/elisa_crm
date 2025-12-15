@extends('layouts.dashboard')
@section('content')
  <h2>Products</h2>
  <a href="{{ route('products.create') }}">Create Product</a>
  <ul>
  @foreach($products as $p)
    <li>{{ $p->code }} - {{ $p->name }} - Rp {{ $p->monthly_price }}</li>
  @endforeach
  </ul>
@endsection
