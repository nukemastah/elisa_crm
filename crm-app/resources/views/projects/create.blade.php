@extends('layouts.dashboard')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h2>Create Project</h2>
    
    @if($errors->any())
        <div style="background: #fee; color: #c00; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('projects.store') }}" style="display: flex; flex-direction: column; gap: 1rem;">
        @csrf

        <div>
            <label for="lead_id" style="font-weight: bold;">Lead:</label>
            <select id="lead_id" name="lead_id" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">-- Select Lead --</option>
                @foreach($leads as $lead)
                    <option value="{{ $lead->id }}" {{ old('lead_id') == $lead->id ? 'selected' : '' }}>
                        {{ $lead->name }} ({{ $lead->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="product_id" style="font-weight: bold;">Product:</label>
            <select id="product_id" name="product_id" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">-- Select Product --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->code }} - {{ $product->name }} (Rp {{ $product->monthly_price }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="estimated_fee" style="font-weight: bold;">Estimated Fee (Rp):</label>
            <input type="number" id="estimated_fee" name="estimated_fee" value="{{ old('estimated_fee') }}" placeholder="e.g., 2000000" required step="1000" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div>
            <label for="status" style="font-weight: bold;">Status:</label>
            <select id="status" name="status" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" style="background: #667eea; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Create Project</button>
            <a href="{{ route('projects.index') }}" style="padding: 0.75rem 1.5rem; background: #ccc; color: #333; text-decoration: none; border-radius: 4px; display: inline-block;">Cancel</a>
        </div>
    </form>
</div>
@endsection
