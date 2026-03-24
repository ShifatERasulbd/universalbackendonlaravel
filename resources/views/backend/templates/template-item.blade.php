<div class="template-item" draggable="true" data-template-id="{{ $template->id }}">
    <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem;">
        <div style="display:flex; align-items:center; gap:0.8rem; min-width:0; flex:1;">
            <span class="material-icons-round" style="font-size:1rem; color:var(--text-secondary);">drag_indicator</span>
            @if($template->preview_image)
                <img src="{{ asset('storage/' . $template->preview_image) }}" alt="Template Preview" style="width:38px; height:38px; border-radius:10px; object-fit:cover; border:1px solid rgba(99,102,241,0.25); flex-shrink:0;">
            @else
                <div style="width:38px; height:38px; border-radius:10px; background:linear-gradient(135deg,#8b5cf6,#6366f1); color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <span class="material-icons-round" style="font-size:1rem;">view_quilt</span>
                </div>
            @endif
            <div style="min-width:0;">
                <div style="font-weight:600; color:var(--text-primary);">{{ $template->name }}</div>
                <div style="font-size:0.8rem; color:var(--text-secondary); margin-top:0.15rem;">
                    {{ $template->availableWebsite?->name ?? 'No Category' }} • {{ $template->slug }}
                </div>
            </div>
        </div>

        <div style="display:flex; align-items:center; gap:0.5rem;">
            <span style="display:inline-block; padding:3px 10px; border-radius:20px; font-size:0.72rem; font-weight:600; background:{{ $template->is_active ? 'rgba(16,185,129,0.15)' : 'rgba(107,114,128,0.15)' }}; color:{{ $template->is_active ? '#059669' : '#6b7280' }};">
                {{ $template->is_active ? 'Active' : 'Inactive' }}
            </span>

            @can('templates.edit')
            <a href="{{ route('templates.edit', $template) }}" style="display:inline-flex; align-items:center; gap:0.3rem; padding:0.4rem 0.75rem; border-radius:6px; font-size:0.8rem; font-weight:500; background:rgba(99,102,241,0.1); color:#6366f1; text-decoration:none; border:1px solid rgba(99,102,241,0.2);">
                <span class="material-icons-round" style="font-size:0.95rem;">edit</span> Edit
            </a>
            @endcan

            @can('templates.delete')
            <form action="{{ route('templates.destroy', $template) }}" method="POST" onsubmit="return confirm('Delete template \'{{ $template->name }}\'? This cannot be undone.');" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" style="display:inline-flex; align-items:center; gap:0.3rem; padding:0.4rem 0.75rem; border-radius:6px; font-size:0.8rem; font-weight:500; background:rgba(239,68,68,0.1); color:#dc2626; border:1px solid rgba(239,68,68,0.2); cursor:pointer;">
                    <span class="material-icons-round" style="font-size:0.95rem;">delete</span> Delete
                </button>
            </form>
            @endcan
        </div>
    </div>

    @if($template->description)
    <p style="margin:.75rem 0 0 1.9rem; color:var(--text-secondary); font-size:.85rem;">{{ $template->description }}</p>
    @endif
</div>
