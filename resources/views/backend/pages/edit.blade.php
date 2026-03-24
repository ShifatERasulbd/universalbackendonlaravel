@extends('backend.master')

@section('title', 'Edit Page - NexusAdmin')
@section('page-title', 'Edit Page')

@section('content')

<div style="max-width:900px;">
    <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:2rem;">
        <a href="{{ route('pages.index') }}" style="display:inline-flex; align-items:center; color:var(--text-secondary); text-decoration:none; padding:0.4rem 0.8rem; border-radius:6px; background:var(--input-bg); margin-right:0.5rem;">
            <span class="material-icons-round">arrow_back</span>
        </a>
        <div>
            <h2 style="font-size:1.5rem; font-weight:600; color:var(--text-primary); margin:0;">Edit Page</h2>
            <p style="font-size:0.875rem; color:var(--text-secondary); margin-top:0.25rem;">Update page: <strong>{{ $page->title }}</strong></p>
        </div>
    </div>

    <form action="{{ route('pages.update', $page) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card" style="border-radius:12px; background:var(--card-bg); border:1px solid var(--border-color); padding:2rem; margin-bottom:1.5rem;">
            <!-- Title Field -->
            <div style="margin-bottom:1.75rem;">
                <label style="display:block; font-size:0.9rem; font-weight:600; color:var(--text-primary); margin-bottom:0.7rem;">
                    Page Title <span style="color:#dc2626;">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title', $page->title) }}" placeholder="e.g. About Us, Contact, Privacy Policy" 
                    style="width:100%; padding:0.75rem 1rem; border-radius:8px; border:1px solid {{ $errors->has('title') ? '#dc2626' : 'var(--border-color)' }}; background:var(--input-bg, #fff); color:var(--text-primary); font-size:0.95rem; box-sizing:border-box; transition:all 0.2s ease;"
                    onfocus="this.style.borderColor='#6366f1';" onblur="this.style.borderColor='var(--border-color)';">
                @error('title')
                    <p style="margin-top:0.5rem; font-size:0.8rem; color:#dc2626; display:flex; align-items:center; gap:0.4rem;">
                        <span class="material-icons-round" style="font-size:0.9rem;">error</span> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Slug Field -->
            <div style="margin-bottom:1.75rem;">
                <label style="display:block; font-size:0.9rem; font-weight:600; color:var(--text-primary); margin-bottom:0.7rem;">URL Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" placeholder="Auto-generated from title if left empty" 
                    style="width:100%; padding:0.75rem 1rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg, #fff); color:var(--text-primary); font-size:0.95rem; box-sizing:border-box; transition:all 0.2s ease;"
                    onfocus="this.style.borderColor='#6366f1';" onblur="this.style.borderColor='var(--border-color)';">
                <p style="margin-top:0.4rem; font-size:0.8rem; color:var(--text-secondary);">Used in the page URL (e.g., /about-us)</p>
            </div>

            <!-- Excerpt Field -->
            <div style="margin-bottom:1.75rem;">
                <label style="display:block; font-size:0.9rem; font-weight:600; color:var(--text-primary); margin-bottom:0.7rem;">Excerpt (Short Description)</label>
                <textarea name="excerpt" placeholder="Brief summary of the page content (max 500 characters)" rows="2"
                    style="width:100%; padding:0.75rem 1rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg, #fff); color:var(--text-primary); font-size:0.95rem; box-sizing:border-box; resize:vertical; transition:all 0.2s ease;"
                    onfocus="this.style.borderColor='#6366f1';" onblur="this.style.borderColor='var(--border-color)';">{{ old('excerpt', $page->excerpt) }}</textarea>
            </div>

            <!-- Content Field -->
            <div style="margin-bottom:1.75rem;">
                <label style="display:block; font-size:0.9rem; font-weight:600; color:var(--text-primary); margin-bottom:0.7rem;">Content</label>
                <textarea name="content" placeholder="Page content (supports HTML and markdown)" rows="8"
                    style="width:100%; padding:0.75rem 1rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg, #fff); color:var(--text-primary); font-size:0.95rem; box-sizing:border-box; font-family:monospace; resize:vertical; transition:all 0.2s ease;"
                    onfocus="this.style.borderColor='#6366f1';" onblur="this.style.borderColor='var(--border-color)';">{{ old('content', $page->content) }}</textarea>
            </div>

            <!-- Status Field -->
            <div style="margin-bottom:1.75rem;">
                <label style="display:block; font-size:0.9rem; font-weight:600; color:var(--text-primary); margin-bottom:0.7rem;">Publication Status <span style="color:#dc2626;">*</span></label>
                <select name="status" 
                    style="width:100%; padding:0.75rem 1rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg, #fff); color:var(--text-primary); font-size:0.95rem; box-sizing:border-box; transition:all 0.2s ease;"
                    onfocus="this.style.borderColor='#6366f1';" onblur="this.style.borderColor='var(--border-color)';">
                    <option value="draft" {{ old('status', $page->status) === 'draft' ? 'selected' : '' }}>📝 Draft (Not visible)</option>
                    <option value="published" {{ old('status', $page->status) === 'published' ? 'selected' : '' }}>✓ Published (Visible now)</option>
                    <option value="scheduled" {{ old('status', $page->status) === 'scheduled' ? 'selected' : '' }}>📅 Scheduled (For later)</option>
                </select>
            </div>

            <!-- SEO Section -->
            <div style="border-top:1px solid var(--border-color); padding-top:1.5rem; margin-bottom:1.75rem;">
                <h4 style="font-size:0.95rem; font-weight:600; color:var(--text-primary); margin-bottom:1rem;">SEO Settings (Optional)</h4>
                
                <div style="margin-bottom:1.75rem;">
                    <label style="display:block; font-size:0.9rem; font-weight:600; color:var(--text-primary); margin-bottom:0.7rem;">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" placeholder="Page title for search engines (max 255 chars)" 
                        style="width:100%; padding:0.75rem 1rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg, #fff); color:var(--text-primary); font-size:0.95rem; box-sizing:border-box; transition:all 0.2s ease;"
                        onfocus="this.style.borderColor='#6366f1';" onblur="this.style.borderColor='var(--border-color)';">
                </div>

                <div style="margin-bottom:1.75rem;">
                    <label style="display:block; font-size:0.9rem; font-weight:600; color:var(--text-primary); margin-bottom:0.7rem;">Meta Description</label>
                    <textarea name="meta_description" placeholder="Brief description for search results (max 500 chars)" rows="2"
                        style="width:100%; padding:0.75rem 1rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg, #fff); color:var(--text-primary); font-size:0.95rem; box-sizing:border-box; resize:vertical; transition:all 0.2s ease;"
                        onfocus="this.style.borderColor='#6366f1';" onblur="this.style.borderColor='var(--border-color)';">{{ old('meta_description', $page->meta_description) }}</textarea>
                </div>

                <div style="margin-bottom:0;">
                    <label style="display:block; font-size:0.9rem; font-weight:600; color:var(--text-primary); margin-bottom:0.7rem;">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords) }}" placeholder="Comma-separated keywords" 
                        style="width:100%; padding:0.75rem 1rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg, #fff); color:var(--text-primary); font-size:0.95rem; box-sizing:border-box; transition:all 0.2s ease;"
                        onfocus="this.style.borderColor='#6366f1';" onblur="this.style.borderColor='var(--border-color)';">
                </div>
            </div>

            <!-- Display Order -->
            <div>
                <label style="display:block; font-size:0.9rem; font-weight:600; color:var(--text-primary); margin-bottom:0.7rem;">Display Order</label>
                <input type="number" name="order" value="{{ old('order', $page->order) }}" 
                    style="width:100%; padding:0.75rem 1rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg, #fff); color:var(--text-primary); font-size:0.95rem; box-sizing:border-box; transition:all 0.2s ease;"
                    onfocus="this.style.borderColor='#6366f1';" onblur="this.style.borderColor='var(--border-color)';">
                <p style="margin-top:0.4rem; font-size:0.8rem; color:var(--text-secondary);">Lower numbers appear first.</p>
            </div>

            <!-- Active Checkbox -->
            <div style="margin-top:1.5rem; display:flex; flex-direction:column;">
                <label style="display:flex; align-items:center; gap:0.75rem; font-size:0.95rem; color:var(--text-primary); cursor:pointer; padding:0.75rem; border-radius:8px; background:var(--input-bg); transition:all 0.2s ease;"
                    onmouseover="this.style.background='rgba(99,102,241,0.05)';" onmouseout="this.style.background='var(--input-bg)';">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }} 
                        style="width:18px; height:18px; cursor:pointer; accent-color:#6366f1;">
                    <span>Make this page active</span>
                </label>
            </div>
        </div>

        <div style="display:flex; gap:0.75rem;">
            <button type="submit" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.65rem 1.5rem; font-weight:600;">
                <span class="material-icons-round" style="font-size:1.2rem;">save</span> Update Page
            </button>
            <a href="{{ route('pages.index') }}" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.65rem 1.5rem; border-radius:8px; border:1px solid var(--border-color); font-size:0.95rem; font-weight:600; color:var(--text-secondary); text-decoration:none; background:var(--input-bg); transition:all 0.2s ease;"
                onmouseover="this.style.background='rgba(99,102,241,0.05)';" onmouseout="this.style.background='var(--input-bg)';">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
