@extends('layouts.dashboard')

@section('content')
  <h2>Dashboard</h2>
  <section>
    <h3>Recent Leads</h3>
    <ul>
      @foreach($leads as $lead)
        <li>{{ $lead->name }} - {{ $lead->status }}</li>
      @endforeach
    </ul>
  </section>

  <section>
    <h3>Recent Projects</h3>
    <ul>
      @foreach($projects as $p)
        <li>{{ $p->id }} - {{ $p->status }}</li>
      @endforeach
    </ul>
  </section>

@endsection
