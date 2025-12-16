@extends('layouts.dashboard')
@section('content')
  <h2>Projects</h2>
  <a href="{{ route('projects.create') }}">Create Project</a>
  
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Lead</th>
        <th>Product</th>
        <th>Estimated Fee</th>
        <th>Status</th>
        <th>Manager Approval</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($projects as $proj)
      <tr>
        <td>{{ $proj->id }}</td>
        <td>{{ optional($proj->lead)->name ?? '-' }}</td>
        <td>{{ optional($proj->product)->name ?? '-' }}</td>
        <td style="text-align: right;">Rp {{ number_format($proj->estimated_fee, 0, ',', '.') }}</td>
        <td>
          <span style="padding: 2px 8px; border-radius: 3px; background: 
            @if($proj->status === 'pending') #fff3e0
            @elseif($proj->status === 'approved') #e8f5e9
            @elseif($proj->status === 'rejected') #ffebee
            @else #e3f2fd
            @endif; color: 
            @if($proj->status === 'pending') #f57c00
            @elseif($proj->status === 'approved') #388e3c
            @elseif($proj->status === 'rejected') #c62828
            @else #1976d2
            @endif">
            {{ ucfirst($proj->status) }}
          </span>
        </td>
        <td style="text-align: center;">
          @if($proj->status === 'approved')
            <span style="color: #388e3c; font-weight: bold;">✓ Approved</span>
          @elseif($proj->status === 'rejected')
            <span style="color: #c62828; font-weight: bold;">✗ Rejected</span>
          @else
            <span style="color: #f57c00;">Pending</span>
          @endif
        </td>
        <td>
          @if($proj->status === 'pending')
            <form method="POST" action="{{ route('projects.approve', $proj) }}" style="display:inline; margin-right: 10px;">
              @csrf
              <input type="hidden" name="approved" value="1">
              <button type="submit" style="background: #667eea; color: white; padding: 4px 12px; border: none; border-radius: 4px; cursor: pointer;">Approve</button>
            </form>
            <form method="POST" action="{{ route('projects.approve', $proj) }}" style="display:inline; margin-right: 10px;">
              @csrf
              <input type="hidden" name="approved" value="0">
              <button type="submit" style="background: #e67e22; color: white; padding: 4px 12px; border: none; border-radius: 4px; cursor: pointer;">Reject</button>
            </form>
          @endif
          <form method="POST" action="{{ route('projects.destroy', $proj) }}" style="display:inline" onsubmit="return confirm('Delete this project?')">
            @csrf
            @method('DELETE')
            <button type="submit" style="background: #e74c3c; color: white; padding: 4px 12px; border: none; border-radius: 4px; cursor: pointer;">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" style="text-align: center; color: #666;">No projects found</td>
      </tr>
      @endforelse
    </tbody>
  </table>
@endsection
