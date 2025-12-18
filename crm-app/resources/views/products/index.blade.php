@extends('layouts.dashboard')
@section('content')
  <h2>Products</h2>
  <div style="display:flex; gap: 1rem; align-items:center; margin-bottom: 1rem;">
    <a href="{{ route('products.create') }}">Create Product</a>
    <form method="GET" action="{{ route('products.index') }}" class="live-search-form" style="display:flex; gap:.5rem; align-items:center;">
      <input type="text" name="q" placeholder="Search products..." value="{{ $q ?? '' }}" />
      <div class="search-spinner"></div>
      @if(!empty($q))
        <a href="{{ route('products.index') }}">Reset</a>
      @endif
    </form>
  </div>
  
  <table>
    <thead>
      <tr>
        <th><a href="{{ route('products.index', array_merge(request()->query(), ['sort'=>'id','dir'=> ($sort==='id' && $dir==='asc') ? 'desc' : 'asc'])) }}">ID</a></th>
        <th><a href="{{ route('products.index', array_merge(request()->query(), ['sort'=>'code','dir'=> ($sort==='code' && $dir==='asc') ? 'desc' : 'asc'])) }}">Code</a></th>
        <th><a href="{{ route('products.index', array_merge(request()->query(), ['sort'=>'name','dir'=> ($sort==='name' && $dir==='asc') ? 'desc' : 'asc'])) }}">Name</a></th>
        <th>Description</th>
        <th><a href="{{ route('products.index', array_merge(request()->query(), ['sort'=>'monthly_price','dir'=> ($sort==='monthly_price' && $dir==='asc') ? 'desc' : 'asc'])) }}">Monthly Price</a></th>
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
          <form method="POST" action="{{ route('products.destroy', $p) }}" style="display:inline" class="js-delete" data-name="product {{ $p->name }}">
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
  <div style="margin-top:1rem;">
    {{ $products->onEachSide(1)->links() }}
  </div>
@endsection
