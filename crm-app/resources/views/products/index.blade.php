@extends('layouts.dashboard')
@section('content')
  <h2>Products</h2>
  <a href="{{ route('products.create') }}">Create Product</a>
  
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Code</th>
        <th>Name</th>
        <th>Description</th>
        <th>Monthly Price</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($products as $p)
      <tr>
        <td>{{ $p->id }}</td>
        <td><strong>{{ $p->code }}</strong></td>
        <td>{{ $p->name }}</td>
        <td>{{ Str::limit($p->description, 50) ?? '-' }}</td>
        <td style="text-align: right;">Rp {{ number_format($p->monthly_price, 0, ',', '.') }}</td>
        <td>
          <a href="{{ route('products.edit', $p) }}" style="margin-right: 10px;">Edit</a>
          <form method="POST" action="{{ route('products.destroy', $p) }}" style="display:inline" onsubmit="return confirm('Delete this product?')">
            @csrf
            @method('DELETE')
            <button type="submit" style="background: #e74c3c; color: white; padding: 4px 12px; border: none; border-radius: 4px; cursor: pointer;">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6" style="text-align: center; color: #666;">No products found</td>
      </tr>
      @endforelse
    </tbody>
  </table>
@endsection
