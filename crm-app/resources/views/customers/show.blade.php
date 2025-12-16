@extends('layouts.dashboard')
@section('content')
  <h2>Customer Detail: {{ $customer->name }}</h2>
  
  <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
    <h3>Basic Information</h3>
    <table style="width: 100%;">
      <tr>
        <td style="width: 200px; font-weight: bold;">Customer ID:</td>
        <td>{{ $customer->id }}</td>
      </tr>
      <tr>
        <td style="font-weight: bold;">Name:</td>
        <td>{{ $customer->name }}</td>
      </tr>
      <tr>
        <td style="font-weight: bold;">Phone:</td>
        <td>{{ $customer->phone ?? '-' }}</td>
      </tr>
      <tr>
        <td style="font-weight: bold;">Email:</td>
        <td>{{ $customer->email ?? '-' }}</td>
      </tr>
      <tr>
        <td style="font-weight: bold;">Address:</td>
        <td>{{ $customer->address ?? '-' }}</td>
      </tr>
      <tr>
        <td style="font-weight: bold;">Joined At:</td>
        <td>{{ $customer->joined_at ? \Illuminate\Support\Carbon::parse($customer->joined_at)->format('d F Y') : '-' }}</td>
      </tr>
      <tr>
        <td style="font-weight: bold;">From Lead:</td>
        <td>{{ optional($customer->lead)->name ?? 'Direct Customer' }}</td>
      </tr>
    </table>
  </div>

  <h3>Subscribed Services</h3>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Product</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Monthly Fee</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($customer->services as $service)
      <tr>
        <td>{{ $service->id }}</td>
        <td><strong>{{ optional($service->product)->name ?? '-' }}</strong></td>
        <td>{{ $service->start_date ? \Carbon\Carbon::parse($service->start_date)->format('d M Y') : '-' }}</td>
        <td>{{ $service->end_date ? \Carbon\Carbon::parse($service->end_date)->format('d M Y') : '-' }}</td>
        <td style="text-align: right;">Rp {{ number_format($service->monthly_fee, 0, ',', '.') }}</td>
        <td>
          <span style="padding: 2px 8px; border-radius: 3px; background: 
            @if($service->status === 'active') #e8f5e9
            @elseif($service->status === 'suspended') #fff3e0
            @else #ffebee
            @endif; color: 
            @if($service->status === 'active') #388e3c
            @elseif($service->status === 'suspended') #f57c00
            @else #c62828
            @endif">
            {{ ucfirst($service->status) }}
          </span>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6" style="text-align: center; color: #666;">No services subscribed</td>
      </tr>
      @endforelse
      @if($customer->services->count() > 0)
      <tr style="background: #f5f5f5; font-weight: bold;">
        <td colspan="4" style="text-align: right;">Total Monthly Revenue:</td>
        <td style="text-align: right;">Rp {{ number_format($customer->services->sum('monthly_fee'), 0, ',', '.') }}</td>
        <td></td>
      </tr>
      @endif
    </tbody>
  </table>

  <div style="margin-top: 20px;">
    <a href="{{ route('customers.index') }}" style="text-decoration: none;">
      <button type="button" style="background: #95a5a6; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">
        Back to List
      </button>
    </a>
  </div>
@endsection
