@extends('layouts.dashboard')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h2>Add Customer</h2>
    
    @if($errors->any())
        <div style="background: #fee; color: #c00; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('customers.store') }}" style="display: flex; flex-direction: column; gap: 1rem;" novalidate>
        @csrf

        <div>
            <label for="name" style="font-weight: bold;">Customer Name:</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            @error('name')<small style="color:#c00;">{{ $message }}</small>@enderror
        </div>

        <div>
            <label for="email" style="font-weight: bold;">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            @error('email')<small style="color:#c00;">{{ $message }}</small>@enderror
        </div>

        <div>
            <label for="phone" style="font-weight: bold;">Phone:</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            @error('phone')<small style="color:#c00;">{{ $message }}</small>@enderror
        </div>

        <div>
            <label for="address" style="font-weight: bold;">Address:</label>
            <textarea id="address" name="address" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; font-family: inherit;">{{ old('address') }}</textarea>
            @error('address')<small style="color:#c00;">{{ $message }}</small>@enderror
        </div>

        <div>
            <label for="lead_id" style="font-weight: bold;">Related Lead (Optional):</label>
            <select id="lead_id" name="lead_id" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">-- No Lead --</option>
                @foreach($leads as $lead)
                    <option value="{{ $lead->id }}" {{ old('lead_id') == $lead->id ? 'selected' : '' }}>
                        {{ $lead->name }} ({{ $lead->email }})
                    </option>
                @endforeach
            </select>
            @error('lead_id')<small style="color:#c00;">{{ $message }}</small>@enderror
        </div>

        <div>
            <label for="joined_at" style="font-weight: bold;">Join Date:</label>
            <input type="date" id="joined_at" name="joined_at" value="{{ old('joined_at', date('Y-m-d')) }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            @error('joined_at')<small style="color:#c00;">{{ $message }}</small>@enderror
        </div>

        <fieldset style="border: 1px solid #ddd; padding: 1rem; border-radius: 4px;">
            <legend style="font-weight: bold;">Subscribe to Services</legend>
            <p style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">Select products/services for this customer</p>
            
            @foreach($products as $product)
                <div style="margin-bottom: 1rem; padding: 0.5rem; background: #f9f9f9; border-radius: 4px;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: normal; cursor: pointer;">
                        <input type="checkbox" name="services[{{ $product->id }}][product_id]" value="{{ $product->id }}">
                        <strong>{{ $product->code }} - {{ $product->name }}</strong>
                    </label>
                    <p style="color: #666; font-size: 0.9rem; margin: 0.5rem 0 0 1.5rem;">Monthly: Rp {{ $product->monthly_price }}</p>
                    
                    <div style="margin-left: 1.5rem; margin-top: 0.5rem; display: none;" class="service-fields" id="fields-{{ $product->id }}">
                        <label style="display: block; margin-bottom: 0.5rem;">
                            Start Date:
                            <input type="date" name="services[{{ $product->id }}][start_date]" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                        </label>
                        <label style="display: block; margin-bottom: 0.5rem;">
                            Monthly Fee:
                            <input type="number" name="services[{{ $product->id }}][monthly_fee]" value="{{ $product->monthly_price }}" step="1000" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                        </label>
                        <label style="display: block;">
                            Status:
                            <select name="services[{{ $product->id }}][status]" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </label>
                    </div>
                </div>
            @endforeach
        </fieldset>

        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" style="background: #667eea; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Add Customer</button>
            <button type="button" onclick="window.location.href='{{ route('customers.index') }}'" style="background: #e74c3c; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Cancel</button>
        </div>
    </form>
</div>

<script>
    // Toggle service fields when checkbox is checked
    document.querySelectorAll('input[type="checkbox"][name*="product_id"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const productId = this.value;
            const fieldsDiv = document.getElementById('fields-' + productId);
            if (this.checked) {
                fieldsDiv.style.display = 'block';
            } else {
                fieldsDiv.style.display = 'none';
            }
        });
    });
</script>
@endsection
