<div class="menu-item" draggable="true" data-menu-id="{{ $menu->id }}">
    <div class="menu-header">
        <div class="menu-drag-handle" title="Drag to reorder">
            <span class="material-icons-round">drag_indicator</span>
        </div>
        
        <div class="menu-info">
            <div class="menu-icon">
                @if($menu->icon)
                    <span class="material-icons-round">{{ $menu->icon }}</span>
                @else
                    <span class="material-icons-round">link</span>
                @endif
            </div>
            
            <div class="menu-details">
                <div class="menu-title">{{ $menu->title }}</div>
                <div class="menu-meta">
                    @if($menu->url)
                        <strong>URL:</strong> {{ $menu->url }}
                    @else
                        <em style="color:#9ca3af;">No URL assigned</em>
                    @endif
                </div>
            </div>
        </div>
        
        <span class="menu-badge {{ $menu->is_active ? 'active' : 'inactive' }}">
            {{ $menu->is_active ? 'Active' : 'Inactive' }}
        </span>
        
        <div class="menu-actions">
            @can('menus.edit')
            <a href="{{ route('menus.edit', $menu) }}" class="menu-action-btn menu-action-edit">
                <span class="material-icons-round" style="font-size:0.95rem;">edit</span> Edit
            </a>
            @endcan
            @can('menus.delete')
            <form action="{{ route('menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Delete menu \'{{ $menu->title }}\'? This cannot be undone.');" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="menu-action-btn menu-action-delete">
                    <span class="material-icons-round" style="font-size:0.95rem;">delete</span> Delete
                </button>
            </form>
            @endcan
        </div>
    </div>
    
    @if($menu->children->isNotEmpty())
    <div class="menu-children">
        @foreach($menu->children->sortBy('order') as $child)
            @include('backend.menus.menu-item', ['menu' => $child, 'level' => ($level ?? 0) + 1])
        @endforeach
    </div>
    @endif
</div>
