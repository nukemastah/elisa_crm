@extends('layouts.dashboard')
@section('content')
  <h2>Leads</h2>
  <a href="{{ route('leads.create') }}">Create Lead</a>
  
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Source</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($leads as $lead)
      <tr>
        <td>{{ $lead->id }}</td>
        <td>{{ $lead->name }}</td>
        <td>{{ $lead->phone ?? '-' }}</td>
        <td>{{ $lead->email ?? '-' }}</td>
        <td>{{ $lead->source ?? '-' }}</td>
        <td>
          <span style="padding: 2px 8px; border-radius: 3px; background: 
            @if($lead->status === 'new') #e3f2fd
            @elseif($lead->status === 'contacted') #fff3e0
            @elseif($lead->status === 'qualified') #e8f5e9
            @else #ffebee
            @endif; color: 
            @if($lead->status === 'new') #1976d2
            @elseif($lead->status === 'contacted') #f57c00
            @elseif($lead->status === 'qualified') #388e3c
            @else #c62828
            @endif">
            {{ ucfirst($lead->status) }}
          </span>
        </td>
        <td>
          <a href="{{ route('leads.edit', $lead) }}" style="margin-right: 10px;">Edit</a>
          <form method="POST" action="{{ route('leads.destroy', $lead) }}" style="display:inline" onsubmit="return confirm('Delete this lead?')">
            @csrf
            @method('DELETE')
            <button type="submit" style="background: #e74c3c; color: white; padding: 4px 12px; border: none; border-radius: 4px; cursor: pointer;">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="8" style="text-align: center; color: #666;">No leads found</td>
      </tr>
      @endforelse
    </tbody>
  </table>
@endsection
