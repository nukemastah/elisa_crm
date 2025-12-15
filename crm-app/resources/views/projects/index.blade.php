@extends('layouts.app')
@section('content')
  <h2>Projects</h2>
  <a href="{{ route('projects.create') }}">Create Project</a>
  <ul>
  @foreach($projects as $proj)
    <li>Project #{{ $proj->id }} - Lead: {{ optional($proj->lead)->name }} - Status: {{ $proj->status }}
      @if(!$proj->manager_approval)
        <form method="POST" action="{{ route('projects.update', $proj) }}" style="display:inline">@csrf @method('PUT')
        <button formaction="{{ url('projects/'.$proj->id.'/approve') }}">Approve</button>
        </form>
      @endif
    </li>
  @endforeach
  </ul>
@endsection
