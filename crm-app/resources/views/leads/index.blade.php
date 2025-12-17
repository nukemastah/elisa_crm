@extends('layouts.dashboard')
@section('content')
  <h2>Leads</h2>
  <div style="display:flex; gap: 1rem; align-items:center; margin-bottom: 1rem;">
    <a href="{{ route('leads.create') }}">Create Lead</a>
    <form method="GET" action="{{ route('leads.index') }}" style="display:flex; gap:.5rem; align-items:center;">
      <input type="text" name="q" placeholder="Search leads..." value="{{ $q ?? '' }}" />
      <button type="submit">Search</button>
      @if(!empty($q))
        <a href="{{ route('leads.index') }}">Reset</a>
      @endif
    </form>
  </div>
  
  <table>
    <thead>
      <tr>
        <th><a href="{{ route('leads.index', array_merge(request()->query(), ['sort'=>'id','dir'=> ($sort==='id' && $dir==='asc') ? 'desc' : 'asc'])) }}">ID</a></th>
        <th><a href="{{ route('leads.index', array_merge(request()->query(), ['sort'=>'name','dir'=> ($sort==='name' && $dir==='asc') ? 'desc' : 'asc'])) }}">Name</a></th>
        <th><a href="{{ route('leads.index', array_merge(request()->query(), ['sort'=>'phone','dir'=> ($sort==='phone' && $dir==='asc') ? 'desc' : 'asc'])) }}">Phone</a></th>
        <th><a href="{{ route('leads.index', array_merge(request()->query(), ['sort'=>'email','dir'=> ($sort==='email' && $dir==='asc') ? 'desc' : 'asc'])) }}">Email</a></th>
        <th><a href="{{ route('leads.index', array_merge(request()->query(), ['sort'=>'source','dir'=> ($sort==='source' && $dir==='asc') ? 'desc' : 'asc'])) }}">Source</a></th>
        <th><a href="{{ route('leads.index', array_merge(request()->query(), ['sort'=>'status','dir'=> ($sort==='status' && $dir==='asc') ? 'desc' : 'asc'])) }}">Status</a></th>
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
          <form method="POST" action="{{ route('leads.destroy', $lead) }}" style="display:inline" class="js-delete" data-name="lead {{ $lead->name }}">
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
  <div style="margin-top:1rem;">
    {{ $leads->onEachSide(1)->links() }}
  </div>
@endsection
