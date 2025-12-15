@extends('layouts.dashboard')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h2>Edit Lead</h2>
    
    @if($errors->any())
        <div style="background: #fee; color: #c00; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('leads.update', $lead) }}" style="display: flex; flex-direction: column; gap: 1rem;">
        @csrf
        @method('PUT')

        <div>
            <label for="name" style="font-weight: bold;">Name:</label>
            <input type="text" id="name" name="name" value="{{ old('name', $lead->name) }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div>
            <label for="email" style="font-weight: bold;">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email', $lead->email) }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div>
            <label for="phone" style="font-weight: bold;">Phone:</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone', $lead->phone) }}" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div>
            <label for="address" style="font-weight: bold;">Address:</label>
            <textarea id="address" name="address" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; font-family: inherit;">{{ old('address', $lead->address) }}</textarea>
        </div>

        <div>
            <label for="source" style="font-weight: bold;">Source:</label>
            <input type="text" id="source" name="source" value="{{ old('source', $lead->source) }}" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div>
            <label for="status" style="font-weight: bold;">Status:</label>
            <select id="status" name="status" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                <option value="new" {{ old('status', $lead->status) === 'new' ? 'selected' : '' }}>New</option>
                <option value="contacted" {{ old('status', $lead->status) === 'contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="qualified" {{ old('status', $lead->status) === 'qualified' ? 'selected' : '' }}>Qualified</option>
                <option value="proposal" {{ old('status', $lead->status) === 'proposal' ? 'selected' : '' }}>Proposal</option>
            </select>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" style="background: #667eea; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Update Lead</button>
            <a href="{{ route('leads.index') }}" style="padding: 0.75rem 1.5rem; background: #ccc; color: #333; text-decoration: none; border-radius: 4px; display: inline-block;">Cancel</a>
        </div>
    </form>
</div>
@endsection
