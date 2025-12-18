@extends('layouts.dashboard')
@section('content')
  <h2>Users</h2>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Created At</th>
      </tr>
    </thead>
    <tbody>
      @forelse($users as $u)
      <tr>
        <td>{{ $u->id }}</td>
        <td><strong>{{ $u->name }}</strong></td>
        <td>{{ $u->email }}</td>
        <td>{{ \Illuminate\Support\Carbon::parse($u->created_at)->format('d M Y H:i') }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="4" style="text-align: center; color: #666;">No users found</td>
      </tr>
      @endforelse
    </tbody>
  </table>
@endsection
