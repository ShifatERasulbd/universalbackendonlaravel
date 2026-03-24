@extends('backend.master')

@section('title', 'Edit Template - NexusAdmin')
@section('page-title', 'Edit Template')

@section('content')
<div style="max-width:820px;">
    <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.5rem;">
        <a href="{{ route('templates.index', ['available_website_id' => old('available_website_id', $template->available_website_id)]) }}" style="display:inline-flex; align-items:center; color:var(--text-secondary); text-decoration:none;"><span class="material-icons-round">arrow_back</span></a>
        <div>
            <h2 style="font-size:1.25rem; font-weight:600; color:var(--text-primary);">Edit Template</h2>
            <p style="font-size:0.875rem; color:var(--text-secondary); margin-top:0.2rem;">Update template: <strong>{{ $template->name }}</strong></p>
        </div>
    </div>

    <form action="{{ route('templates.update', $template) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card" style="border-radius:12px; background:var(--card-bg); border:1px solid var(--border-color); padding:1.5rem; margin-bottom:1.25rem;">
            <div style="display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:1rem;">
                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">Category (Website) *</label>
                    <select name="available_website_id" style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg,#fff); color:var(--text-primary); font-size:0.9rem; box-sizing:border-box;">
                        <option value="">Select Category</option>
                        @foreach($categories as $id => $name)
                            <option value="{{ $id }}" {{ (string)old('available_website_id', $template->available_website_id) === (string)$id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('available_website_id') <p style="margin-top:0.4rem; font-size:0.8rem; color:#dc2626;">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">Order</label>
                    <input type="number" name="order" value="{{ old('order', $template->order) }}" style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg,#fff); color:var(--text-primary); font-size:0.9rem; box-sizing:border-box;">
                </div>

                <div style="grid-column:1 / -1;">
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">Template Name *</label>
                    <input type="text" name="name" value="{{ old('name', $template->name) }}" style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg,#fff); color:var(--text-primary); font-size:0.9rem; box-sizing:border-box;">
                    @error('name') <p style="margin-top:0.4rem; font-size:0.8rem; color:#dc2626;">{{ $message }}</p> @enderror
                </div>

                <div style="grid-column:1 / -1;">
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $template->slug) }}" placeholder="Auto generated if empty" style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg,#fff); color:var(--text-primary); font-size:0.9rem; box-sizing:border-box;">
                </div>

                <div style="grid-column:1 / -1;">
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">Description</label>
                    <textarea name="description" rows="3" style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg,#fff); color:var(--text-primary); font-size:0.9rem; box-sizing:border-box; resize:vertical;">{{ old('description', $template->description) }}</textarea>
                </div>

                <div style="grid-column:1 / -1;">
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">Template Content</label>
                    <textarea name="content" rows="10" style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg,#fff); color:var(--text-primary); font-size:0.9rem; box-sizing:border-box; resize:vertical;">{{ old('content', $template->content) }}</textarea>
                </div>

                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">Preview Image</label>
                    <input id="preview_image_input" type="file" name="preview_image" accept="image/png,image/jpeg,image/webp" style="width:100%; padding:0.5rem 0.6rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg,#fff); color:var(--text-primary); font-size:0.9rem; box-sizing:border-box;">
                    <p style="margin-top:0.35rem; font-size:0.78rem; color:var(--text-secondary);">Accepted: JPG, PNG, WEBP (max 2MB). Leave empty to keep current image.</p>
                    @error('preview_image') <p style="margin-top:0.35rem; font-size:0.8rem; color:#dc2626;">{{ $message }}</p> @enderror

                    <div id="preview_image_wrapper" style="margin-top:0.6rem; {{ $template->preview_image ? '' : 'display:none;' }}">
                        <p style="font-size:0.78rem; color:var(--text-secondary); margin:0 0 0.35rem 0;">Current / selected image</p>
                        <img id="preview_image_tag" src="{{ $template->preview_image ? asset('storage/' . $template->preview_image) : '' }}" alt="Template Preview" style="width:120px; height:80px; object-fit:cover; border-radius:8px; border:1px solid var(--border-color);">
                    </div>
                </div>

                <div style="display:flex; align-items:flex-end;">
                    <label style="display:inline-flex; align-items:center; gap:0.5rem; font-size:0.9rem; color:var(--text-primary); cursor:pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $template->is_active) ? 'checked' : '' }} style="width:16px; height:16px; cursor:pointer; accent-color:#6366f1;">
                        Active
                    </label>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:0.75rem;">
            <button type="submit" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:0.4rem;"><span class="material-icons-round" style="font-size:1.1rem;">save</span> Update Template</button>
            <a href="{{ route('templates.index', ['available_website_id' => $template->available_website_id]) }}" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.55rem 1.25rem; border-radius:8px; border:1px solid var(--border-color); font-size:0.9rem; color:var(--text-secondary); text-decoration:none;">Cancel</a>
        </div>
    </form>
</div>

<script>
    (function () {
        const input = document.getElementById('preview_image_input');
        const wrapper = document.getElementById('preview_image_wrapper');
        const img = document.getElementById('preview_image_tag');

        if (!input || !wrapper || !img) return;

        input.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                img.src = e.target.result;
                wrapper.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
    })();
</script>
@endsection
