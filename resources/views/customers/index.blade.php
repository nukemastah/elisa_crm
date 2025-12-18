@extends('layouts.dashboard')
@section('content')
  <h2>Customers</h2>
  <div style="display:flex; gap: 1rem; align-items:center; margin-bottom: 1rem;">
    <a href="{{ route('customers.create') }}">Add Customer</a>
    <form method="GET" action="{{ route('customers.index') }}" class="live-search-form" style="display:flex; gap:.5rem; align-items:center;">
      <input type="text" name="q" placeholder="Search customers..." value="{{ $q ?? '' }}" />
      <div class="search-spinner"></div>
      @if(!empty($q))
        <a href="{{ route('customers.index') }}">Reset</a>
      @endif
    </form>
  </div>
  
  <table>
    <thead>
      <tr>
        <th><a href="{{ route('customers.index', array_merge(request()->query(), ['sort'=>'id','dir'=> ($sort==='id' && $dir==='asc') ? 'desc' : 'asc'])) }}">ID</a></th>
        <th><a href="{{ route('customers.index', array_merge(request()->query(), ['sort'=>'name','dir'=> ($sort==='name' && $dir==='asc') ? 'desc' : 'asc'])) }}">Name</a></th>
        <th><a href="{{ route('customers.index', array_merge(request()->query(), ['sort'=>'phone','dir'=> ($sort==='phone' && $dir==='asc') ? 'desc' : 'asc'])) }}">Phone</a></th>
        <th><a href="{{ route('customers.index', array_merge(request()->query(), ['sort'=>'email','dir'=> ($sort==='email' && $dir==='asc') ? 'desc' : 'asc'])) }}">Email</a></th>
        <th>Address</th>
        <th>Services Count</th>
        <th><a href="{{ route('customers.index', array_merge(request()->query(), ['sort'=>'joined_at','dir'=> ($sort==='joined_at' && $dir==='asc') ? 'desc' : 'asc'])) }}">Joined At</a></th>
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
          <form method="POST" action="{{ route('customers.destroy', $c) }}" style="display:inline" class="js-delete" data-name="customer {{ $c->name }}">
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
  <div style="margin-top:1rem;">
    {{ $customers->onEachSide(1)->links() }}
  </div>
@endsection
