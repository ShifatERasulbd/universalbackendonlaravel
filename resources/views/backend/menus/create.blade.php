@extends('backend.master')

@section('title', 'Create Menu - NexusAdmin')
@section('page-title', 'Create Menu')

@section('content')

<div style="max-width:700px;">
    <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:2rem;">
        <a href="{{ route('menus.index') }}" style="display:inline-flex; align-items:center; color:var(--text-secondary); text-decoration:none; padding:0.4rem 0.8rem; border-radius:6px; background:var(--input-bg); margin-right:0.5rem;">
            <span class="material-icons-round">arrow_back</span>
        </a>
        <div>
            <h2 style="font-size:1.5rem; font-weight:600; color:var(--text-primary); margin:0;">Create Menu</h2>
            <p style="font-size:0.875rem; color:var(--text-secondary); margin-top:0.25rem;">Add a new menu item to your navigation.</p>
        </div>
    </div>

    <form action="{{ route('menus.store') }}" method="POST">
        @csrf

        <div class="card" style="border-radius:12px; background:var(--card-bg); border:1px solid var(--border-color); padding:2rem; margin-bottom:1.5rem;">
            <!-- Title Field -->
            <div style="margin-bottom:1.75rem;">
                <label style="display:block; font-size:0.9rem; font-weight:600; color:var(--text-primary); margin-bottom:0.7rem;">
                    Menu Title <span style="color:#dc2626;">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Home, About, Contact" 
                    style="width:100%; padding:0.75rem 1rem; border-radius:8px; border:1px solid {{ $errors->has('title') ? '#dc2626' : 'var(--border-color)' }}; background:var(--input-bg, #fff); color:var(--text-primary); font-size:0.95rem; box-sizing:border-box; transition:all 0.2s ease;"
                    onpageFocus="this.style.borderColor='#6366f1';" onblur="this.style.borderColor='var(--border-color)';">
                @error('title')
                    <p style="margin-top:0.5rem; font-size:0.8rem; color:#dc2626; display:flex; align-items:center; gap:0.4rem;">
                        <span class="material-icons-round" style="font-size:0.9rem;">error</span> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- URL Field -->
            <div style="margin-bottom:1.75rem;">
                <label style="display:block; font-size:0.9rem; font-weight:600; color:var(--text-primary); margin-bottom:0.7rem;">URL</label>
                <input type="text" name="url" value="{{ old('url') }}" placeholder="e.g. /about-us or https://example.com" 
                    style="width:100%; padding:0.75rem 1rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg, #fff); color:var(--text-primary); font-size:0.95rem; box-sizing:border-box; transition:all 0.2s ease;"
                    onfocus="this.style.borderColor='#6366f1';" onblur="this.style.borderColor='var(--border-color)';">
                <p style="margin-top:0.4rem; font-size:0.8rem; color:var(--text-secondary);">Leave empty for menu groups with submenu items.</p>
                @error('url')
                    <p style="margin-top:0.5rem; font-size:0.8rem; color:#dc2626; display:flex; align-items:center; gap:0.4rem;">
                        <span class="material-icons-round" style="font-size:0.9rem;">error</span> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Parent Menu Field -->
            <div style="margin-bottom:1.75rem;">
                <label style="display:block; font-size:0.9rem; font-weight:600; color:var(--text-primary); margin-bottom:0.7rem;">Parent Menu</label>
                <select name="parent_id" 
                    style="width:100%; padding:0.75rem 1rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg, #fff); color:var(--text-primary); font-size:0.95rem; box-sizing:border-box; transition:all 0.2s ease;"
                    onfocus="this.style.borderColor='#6366f1';" onblur="this.style.borderColor='var(--border-color)';">
                    <option value="">📌 Top Level Menu</option>
                    @foreach($parentMenus as $id => $title)
                        <option value="{{ $id }}" {{ old('parent_id') == $id ? 'selected' : '' }}>→ {{ $title }}</option>
                    @endforeach
                </select>
                <p style="margin-top:0.4rem; font-size:0.8rem; color:var(--text-secondary);">Select a parent to create a submenu item.</p>
            </div>

            <!-- Icon Field -->
            <div style="margin-bottom:1.75rem;">
                <label style="display:block; font-size:0.9rem; font-weight:600; color:var(--text-primary); margin-bottom:0.7rem;">Icon (Material Icon)</label>
                <div style="display:flex; gap:0.75rem;">
                    <input type="text" name="icon" value="{{ old('icon') }}" placeholder="e.g. home, dashboard, settings" 
                        style="flex:1; padding:0.75rem 1rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg, #fff); color:var(--text-primary); font-size:0.95rem; box-sizing:border-box; transition:all 0.2s ease;"
                        onfocus="this.style.borderColor='#6366f1';" onblur="this.style.borderColor='var(--border-color)';">
                    @if(old('icon'))
                    <div style="display:flex; align-items:center; justify-content:center; width:44px; height:44px; border-radius:8px; background:linear-gradient(135deg, #6366f1, #8b5cf6); color:#fff; font-size:1.5rem; flex-shrink:0;">
                        <span class="material-icons-round">{{ old('icon') }}</span>
                    </div>
                    @endif
                </div>
                <p style="margin-top:0.4rem; font-size:0.8rem; color:var(--text-secondary);">Visit <a href="https://fonts.google.com/icons" target="_blank" style="color:#6366f1; text-decoration:none;">Material Icons</a> to find icon names.</p>
            </div>

            <!-- Order Field -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.75rem;">
                <div>
                    <label style="display:block; font-size:0.9rem; font-weight:600; color:var(--text-primary); margin-bottom:0.7rem;">Display Order</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" 
                        style="width:100%; padding:0.75rem 1rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg, #fff); color:var(--text-primary); font-size:0.95rem; box-sizing:border-box; transition:all 0.2s ease;"
                        onfocus="this.style.borderColor='#6366f1';" onblur="this.style.borderColor='var(--border-color)';">
                    <p style="margin-top:0.4rem; font-size:0.8rem; color:var(--text-secondary);">Lower numbers appear first.</p>
                </div>

                <!-- Active Checkbox -->
                <div style="display:flex; flex-direction:column; justify-content:flex-end;">
                    <label style="display:flex; align-items:center; gap:0.75rem; font-size:0.95rem; color:var(--text-primary); cursor:pointer; padding:0.75rem; border-radius:8px; background:var(--input-bg); transition:all 0.2s ease;"
                        onmouseover="this.style.background='rgba(99,102,241,0.05)';" onmouseout="this.style.background='var(--input-bg)';">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                            style="width:18px; height:18px; cursor:pointer; accent-color:#6366f1;">
                        <span>Publish Immediately</span>
                    </label>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:0.75rem;">
            <button type="submit" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.65rem 1.5rem; font-weight:600;">
                <span class="material-icons-round" style="font-size:1.2rem;">save</span> Create Menu
            </button>
            <a href="{{ route('menus.index') }}" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.65rem 1.5rem; border-radius:8px; border:1px solid var(--border-color); font-size:0.95rem; font-weight:600; color:var(--text-secondary); text-decoration:none; background:var(--input-bg); transition:all 0.2s ease;"
                onmouseover="this.style.background='rgba(99,102,241,0.05)';" onmouseout="this.style.background='var(--input-bg)';">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
