@extends('layouts.dashboard')

@section('content')
  <h2>Dashboard</h2>

  <section style="margin-bottom: 2rem;">
    <h3>Recent Leads</h3>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Source</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($leads as $lead)
        <tr>
          <td>{{ $lead->id }}</td>
          <td><strong>{{ $lead->name }}</strong></td>
          <td>{{ $lead->phone ?? '-' }}</td>
          <td>{{ $lead->email ?? '-' }}</td>
          <td>{{ $lead->source ?? '-' }}</td>
          <td>
            <span style="padding: 2px 8px; border-radius: 3px; background: 
              @if($lead->status === 'new') #e3f2fd
              @elseif($lead->status === 'contacted') #fff3e0
              @elseif($lead->status === 'qualified') #e8f5e9
              @else #f3e5f5
              @endif; color: 
              @if($lead->status === 'new') #1976d2
              @elseif($lead->status === 'contacted') #f57c00
              @elseif($lead->status === 'qualified') #388e3c
              @else #7b1fa2
              @endif">
              {{ ucfirst($lead->status) }}
            </span>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align: center; color: #666;">No leads found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </section>

  <section>
    <h3>Recent Projects</h3>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Lead</th>
          <th>Product</th>
          <th>Estimated Fee</th>
          <th>Status</th>
          <th>Manager Approval</th>
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
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align: center; color: #666;">No projects found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </section>

  <section style="margin-top: 2rem;">
    <h3>Recent Customers</h3>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Services</th>
          <th>Joined At</th>
        </tr>
      </thead>
      <tbody>
        @forelse($customers as $c)
        <tr>
          <td>{{ $c->id }}</td>
          <td><strong>{{ $c->name }}</strong></td>
          <td>{{ $c->phone ?? '-' }}</td>
          <td>{{ $c->email ?? '-' }}</td>
          <td style="text-align: center;">
            <span style="background: #667eea; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px;">
              {{ method_exists($c, 'services') ? $c->services->count() : ($c->services_count ?? 0) }}
            </span>
          </td>
          <td>{{ $c->joined_at ? \Illuminate\Support\Carbon::parse($c->joined_at)->format('d M Y') : '-' }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align: center; color: #666;">No customers found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </section>

@endsection
