<div class="page-item" draggable="true" data-page-id="{{ $page->id }}">
    <div class="page-header">
        <div class="page-drag-handle" title="Drag to reorder">
            <span class="material-icons-round">drag_indicator</span>
        </div>
        
        <div class="page-info">
            <div class="page-icon">
                <span class="material-icons-round">description</span>
            </div>
            
            <div class="page-details">
                <div class="page-title">{{ $page->title }}</div>
                <div class="page-meta">
                    <span class="page-meta-item">
                        <span class="material-icons-round" style="font-size:0.9rem;">person</span>
                        By {{ $page->creator?->name ?? 'System' }}
                    </span>
                    <span class="page-meta-item">
                        <span class="material-icons-round" style="font-size:0.9rem;">schedule</span>
                        {{ $page->created_at->format('M d, Y') }}
                    </span>
                    @if($page->published_at)
                    <span class="page-meta-item">
                        <span class="material-icons-round" style="font-size:0.9rem;">publish</span>
                        Published {{ $page->published_at->format('M d, Y') }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
        
        <span class="page-badge {{ $page->status }}">
            {{ ucfirst($page->status) }}
        </span>
        
        <div class="page-actions">
            @can('pages.edit')
            <a href="{{ route('pages.edit', $page) }}" class="page-action-btn page-action-edit">
                <span class="material-icons-round" style="font-size:0.95rem;">edit</span> Edit
            </a>
            @endcan
            @can('pages.delete')
            <form action="{{ route('pages.destroy', $page) }}" method="POST" onsubmit="return confirm('Delete page \'{{ $page->title }}\'? This cannot be undone.');" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="page-action-btn page-action-delete">
                    <span class="material-icons-round" style="font-size:0.95rem;">delete</span> Delete
                </button>
            </form>
            @endcan
        </div>
    </div>
</div>
