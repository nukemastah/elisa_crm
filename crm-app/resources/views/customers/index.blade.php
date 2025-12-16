@extends('layouts.dashboard')
@section('content')
  <h2>Customers</h2>
  <a href="{{ route('customers.create') }}">Add Customer</a>
  
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Address</th>
        <th>Services Count</th>
        <th>Joined At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($customers as $c)
      <tr>
        <td>{{ $c->id }}</td>
        <td><strong>{{ $c->name }}</strong></td>
        <td>{{ $c->phone ?? '-' }}</td>
        <td>{{ $c->email ?? '-' }}</td>
        <td>{{ Str::limit($c->address, 30) ?? '-' }}</td>
        <td style="text-align: center;">
          <span style="background: #667eea; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px;">
            {{ $c->services->count() }}
          </span>
        </td>
        <td>{{ $c->joined_at ? \Illuminate\Support\Carbon::parse($c->joined_at)->format('d M Y') : '-' }}</td>
        <td>
          <a href="{{ route('customers.show', $c) }}" style="margin-right: 10px;">View</a>
          <form method="POST" action="{{ route('customers.destroy', $c) }}" style="display:inline" onsubmit="return confirm('Delete this customer and all their services?')">
            @csrf
            @method('DELETE')
            <button type="submit" style="background: #e74c3c; color: white; padding: 4px 12px; border: none; border-radius: 4px; cursor: pointer;">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="8" style="text-align: center; color: #666;">No customers found</td>
      </tr>
      @endforelse
    </tbody>
  </table>
@endsection
