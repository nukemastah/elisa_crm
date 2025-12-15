@extends('layouts.dashboard')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h2>Create Product</h2>
    
    @if($errors->any())
        <div style="background: #fee; color: #c00; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('products.store') }}" style="display: flex; flex-direction: column; gap: 1rem;">
        @csrf

        <div>
            <label for="code" style="font-weight: bold;">Product Code:</label>
            <input type="text" id="code" name="code" value="{{ old('code') }}" placeholder="e.g., FTTH-50" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div>
            <label for="name" style="font-weight: bold;">Product Name:</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g., Fiber Internet 50Mbps" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div>
            <label for="description" style="font-weight: bold;">Description:</label>
            <textarea id="description" name="description" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; min-height: 100px;">{{ old('description') }}</textarea>
        </div>

        <div>
            <label for="monthly_price" style="font-weight: bold;">Monthly Price (Rp):</label>
            <input type="number" id="monthly_price" name="monthly_price" value="{{ old('monthly_price') }}" placeholder="e.g., 500000" required step="1000" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" style="background: #667eea; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Create Product</button>
            <a href="{{ route('products.index') }}" style="padding: 0.75rem 1.5rem; background: #ccc; color: #333; text-decoration: none; border-radius: 4px; display: inline-block;">Cancel</a>
        </div>
    </form>
</div>
@endsection
