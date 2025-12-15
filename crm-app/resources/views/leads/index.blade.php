@extends('layouts.dashboard')
@section('content')
  <h2>Leads</h2>
  <a href="{{ route('leads.create') }}">Create Lead</a>
  <ul>
  @foreach($leads as $lead)
    <li>{{ $lead->name }} - {{ $lead->status }} - <a href="{{ route('leads.edit', $lead) }}">Edit</a></li>
  @endforeach
  </ul>
@endsection
