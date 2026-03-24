@extends('backend.master')

@section('title', 'Edit Website - NexusAdmin')
@section('page-title', 'Edit Available Website')

@section('content')
<div style="max-width:760px;">
    <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.5rem;">
        <a href="{{ route('available-websites.index') }}" style="display:inline-flex; align-items:center; color:var(--text-secondary); text-decoration:none;"><span class="material-icons-round">arrow_back</span></a>
        <div>
            <h2 style="font-size:1.25rem; font-weight:600; color:var(--text-primary);">Edit Website</h2>
            <p style="font-size:0.875rem; color:var(--text-secondary); margin-top:0.2rem;">Update website: <strong>{{ $website->name }}</strong></p>
        </div>
    </div>

    <form action="{{ route('available-websites.update', $website) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card" style="border-radius:12px; background:var(--card-bg); border:1px solid var(--border-color); padding:1.5rem; margin-bottom:1.25rem;">
            <div style="display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:1rem;">
                <div style="grid-column:1 / -1;">
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $website->name) }}" style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg,#fff); color:var(--text-primary); font-size:0.9rem; box-sizing:border-box;">
                    @error('name') <p style="margin-top:0.4rem; font-size:0.8rem; color:#dc2626;">{{ $message }}</p> @enderror
                </div>
                <div style="grid-column:1 / -1;">
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">URL *</label>
                    <input type="url" name="url" value="{{ old('url', $website->url) }}" placeholder="https://example.com" style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg,#fff); color:var(--text-primary); font-size:0.9rem; box-sizing:border-box;">
                    @error('url') <p style="margin-top:0.4rem; font-size:0.8rem; color:#dc2626;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">Icon (material name)</label>
                    <input type="text" name="icon" value="{{ old('icon', $website->icon) }}" placeholder="language" style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg,#fff); color:var(--text-primary); font-size:0.9rem; box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">Order</label>
                    <input type="number" name="order" value="{{ old('order', $website->order) }}" style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg,#fff); color:var(--text-primary); font-size:0.9rem; box-sizing:border-box;">
                </div>
                <div style="grid-column:1 / -1;">
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">Description</label>
                    <textarea name="description" rows="3" style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg,#fff); color:var(--text-primary); font-size:0.9rem; box-sizing:border-box; resize:vertical;">{{ old('description', $website->description) }}</textarea>
                </div>
                <div style="grid-column:1 / -1;">
                    <label style="display:inline-flex; align-items:center; gap:0.5rem; font-size:0.9rem; color:var(--text-primary); cursor:pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $website->is_active) ? 'checked' : '' }} style="width:16px; height:16px; cursor:pointer; accent-color:#6366f1;">
                        Active
                    </label>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:0.75rem;">
            <button type="submit" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:0.4rem;"><span class="material-icons-round" style="font-size:1.1rem;">save</span> Update Website</button>
            <a href="{{ route('available-websites.index') }}" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.55rem 1.25rem; border-radius:8px; border:1px solid var(--border-color); font-size:0.9rem; color:var(--text-secondary); text-decoration:none;">Cancel</a>
        </div>
    </form>
</div>
@endsection
