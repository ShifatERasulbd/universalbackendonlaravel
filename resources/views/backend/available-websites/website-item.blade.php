<div class="website-item" draggable="true" data-website-id="{{ $website->id }}">
    <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem;">
        <div style="display:flex; align-items:center; gap:0.8rem; min-width:0; flex:1;">
            <span class="material-icons-round" style="font-size:1rem; color:var(--text-secondary);">drag_indicator</span>
            <div style="width:38px; height:38px; border-radius:10px; background:linear-gradient(135deg,#06b6d4,#3b82f6); color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span class="material-icons-round" style="font-size:1rem;">{{ $website->icon ?: 'language' }}</span>
            </div>
            <div style="min-width:0;">
                <div style="font-weight:600; color:var(--text-primary);">{{ $website->name }}</div>
                <a href="{{ $website->url }}" target="_blank" style="display:inline-block; font-size:0.8rem; color:#3b82f6; text-decoration:none; margin-top:0.15rem;">{{ $website->url }}</a>
            </div>
        </div>

        <div style="display:flex; align-items:center; gap:0.5rem;">
            <span style="display:inline-block; padding:3px 10px; border-radius:20px; font-size:0.72rem; font-weight:600; background:{{ $website->is_active ? 'rgba(16,185,129,0.15)' : 'rgba(107,114,128,0.15)' }}; color:{{ $website->is_active ? '#059669' : '#6b7280' }};">
                {{ $website->is_active ? 'Active' : 'Inactive' }}
            </span>

            @can('templates.view')
            <a href="{{ route('templates.index', ['available_website_id' => $website->id]) }}" style="display:inline-flex; align-items:center; gap:0.3rem; padding:0.4rem 0.75rem; border-radius:6px; font-size:0.8rem; font-weight:500; background:rgba(14,165,233,0.12); color:#0284c7; text-decoration:none; border:1px solid rgba(14,165,233,0.28);">
                <span class="material-icons-round" style="font-size:0.95rem;">visibility</span> View Templates
            </a>
            @endcan

            @can('available-websites.edit')
            <a href="{{ route('available-websites.edit', $website) }}" style="display:inline-flex; align-items:center; gap:0.3rem; padding:0.4rem 0.75rem; border-radius:6px; font-size:0.8rem; font-weight:500; background:rgba(99,102,241,0.1); color:#6366f1; text-decoration:none; border:1px solid rgba(99,102,241,0.2);">
                <span class="material-icons-round" style="font-size:0.95rem;">edit</span> Edit
            </a>
            @endcan

            @can('available-websites.delete')
            <form action="{{ route('available-websites.destroy', $website) }}" method="POST" onsubmit="return confirm('Delete website \'{{ $website->name }}\'? This cannot be undone.');" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" style="display:inline-flex; align-items:center; gap:0.3rem; padding:0.4rem 0.75rem; border-radius:6px; font-size:0.8rem; font-weight:500; background:rgba(239,68,68,0.1); color:#dc2626; border:1px solid rgba(239,68,68,0.2); cursor:pointer;">
                    <span class="material-icons-round" style="font-size:0.95rem;">delete</span> Delete
                </button>
            </form>
            @endcan
        </div>
    </div>

    @if($website->description)
    <p style="margin:.75rem 0 0 1.9rem; color:var(--text-secondary); font-size:.85rem;">{{ $website->description }}</p>
    @endif
</div>
