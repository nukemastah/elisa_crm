@extends('layouts.dashboard')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h2>Edit Product</h2>
    
    @if($errors->any())
        <div style="background: #fee; color: #c00; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('products.update', $product) }}" style="display: flex; flex-direction: column; gap: 1rem;" novalidate>
        @csrf
        @method('PUT')

        <div>
            <label for="code" style="font-weight: bold;">Product Code:</label>
            <input type="text" id="code" name="code" value="{{ old('code', $product->code) }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            @error('code')<small style="color:#c00;">{{ $message }}</small>@enderror
        </div>

        <div>
            <label for="name" style="font-weight: bold;">Product Name:</label>
            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            @error('name')<small style="color:#c00;">{{ $message }}</small>@enderror
        </div>

        <div>
            <label for="description" style="font-weight: bold;">Description:</label>
            <textarea id="description" name="description" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; min-height: 100px;">{{ old('description', $product->description) }}</textarea>
            @error('description')<small style="color:#c00;">{{ $message }}</small>@enderror
        </div>

        <div>
            <label for="monthly_price" style="font-weight: bold;">Monthly Price (Rp):</label>
            <input type="number" id="monthly_price" name="monthly_price" value="{{ old('monthly_price', $product->monthly_price) }}" required step="1000" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            @error('monthly_price')<small style="color:#c00;">{{ $message }}</small>@enderror
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" style="background: #667eea; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Update Product</button>
            <button type="button" onclick="window.location.href='{{ route('products.index') }}'" style="background: #e74c3c; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Cancel</button>
        </div>
    </form>
</div>
@endsection
