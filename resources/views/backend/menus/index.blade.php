@extends('backend.master')

@section('title', 'Menus - NexusAdmin')
@section('page-title', 'Menu Management')

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <div>
        <h2 style="font-size:1.25rem; font-weight:600; color:var(--text-primary);">Menus</h2>
        <p style="font-size:0.875rem; color:var(--text-secondary); margin-top:0.25rem;">Drag and drop to arrange menus. Click to edit or delete.</p>
    </div>
    @can('menus.create')
    <a href="{{ route('menus.create') }}" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:0.4rem;">
        <span class="material-icons-round" style="font-size:1.1rem;">add</span> New Menu
    </a>
    @endcan
</div>

{{-- Flash messages --}}
@if(session('success'))
<div style="display:flex; align-items:center; gap:0.5rem; padding:0.875rem 1rem; border-radius:8px; background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.3); color:#059669; margin-bottom:1.25rem;">
    <span class="material-icons-round" style="font-size:1.1rem;">check_circle</span>
    {{ session('success') }}
</div>
@endif

<div class="card" style="border-radius:12px; background:var(--card-bg); border:1px solid var(--border-color); overflow:hidden;">
    @if($menus->isEmpty())
        <div style="padding:3rem; text-align:center; color:var(--text-secondary);">
            <span class="material-icons-round" style="font-size:2.5rem; display:block; margin-bottom:0.5rem; opacity:0.4;">menu</span>
            <p>No menus found. Create your first menu to get started.</p>
        </div>
    @else
        <div id="menu-list" style="padding:1rem;">
            @foreach($menus as $menu)
                @include('backend.menus.menu-item', ['menu' => $menu, 'level' => 0])
            @endforeach
        </div>
    @endif
</div>

<style>
    .menu-item {
        background: #f8f9fa;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        cursor: move;
        transition: all 0.2s ease;
    }
    
    .menu-item:hover {
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .menu-item.dragging {
        opacity: 0.5;
        background: #e0e7ff;
    }
    
    .menu-item.drag-over {
        border: 2px dashed #6366f1;
        background: #f0f4ff;
    }
    
    .menu-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    
    .menu-drag-handle {
        cursor: grab;
        color: #9ca3af;
    }
    
    .menu-drag-handle:active {
        cursor: grabbing;
    }
    
    .menu-info {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .menu-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    
    .menu-details {
        flex: 1;
    }
    
    .menu-title {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }
    
    .menu-meta {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }
    
    .menu-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .menu-badge.active {
        background: rgba(16,185,129,0.15);
        color: #059669;
    }
    
    .menu-badge.inactive {
        background: rgba(107,114,128,0.15);
        color: #6b7280;
    }
    
    .menu-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .menu-action-btn {
        padding: 0.4rem 0.85rem;
        border-radius: 6px;
        border: none;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    .menu-action-edit {
        background: rgba(99,102,241,0.1);
        color: #6366f1;
        border: 1px solid rgba(99,102,241,0.2);
    }
    
    .menu-action-edit:hover {
        background: rgba(99,102,241,0.2);
    }
    
    .menu-action-delete {
        background: rgba(239,68,68,0.1);
        color: #dc2626;
        border: 1px solid rgba(239,68,68,0.2);
    }
    
    .menu-action-delete:hover {
        background: rgba(239,68,68,0.2);
    }
    
    .menu-children {
        margin-top: 0.75rem;
        margin-left: 2rem;
        border-left: 2px solid #e5e7eb;
        padding-left: 0.75rem;
    }
    
    .menu-children .menu-item {
        margin-left: 1rem;
    }
</style>

<script>
    let draggedItem = null;
    
    document.addEventListener('dragstart', function(e) {
        if (e.target.closest('.menu-item')) {
            draggedItem = e.target.closest('.menu-item');
            draggedItem.classList.add('dragging');
        }
    });
    
    document.addEventListener('dragend', function(e) {
        if (draggedItem) {
            draggedItem.classList.remove('dragging');
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('drag-over');
            });
            draggedItem = null;
        }
    });
    
    document.addEventListener('dragover', function(e) {
        e.preventDefault();
        if (draggedItem) {
            const target = e.target.closest('.menu-item');
            if (target && target !== draggedItem) {
                target.classList.add('drag-over');
            }
        }
    });
    
    document.addEventListener('dragleave', function(e) {
        const target = e.target.closest('.menu-item');
        if (target && draggedItem && target !== draggedItem) {
            target.classList.remove('drag-over');
        }
    });
    
    document.addEventListener('drop', function(e) {
        e.preventDefault();
        const target = e.target.closest('.menu-item');
        if (target && draggedItem && target !== draggedItem) {
            target.classList.remove('drag-over');
            const menuList = document.getElementById('menu-list');
            const allItems = Array.from(menuList.querySelectorAll(':scope > .menu-item'));
            const draggedIndex = allItems.indexOf(draggedItem);
            const targetIndex = allItems.indexOf(target);
            
            if (draggedIndex < targetIndex) {
                target.after(draggedItem);
            } else {
                target.before(draggedItem);
            }
            
            updateMenuOrder();
        }
    });
    
    function updateMenuOrder() {
        const menuList = document.getElementById('menu-list');
        const menus = Array.from(menuList.querySelectorAll(':scope > .menu-item')).map((item, index) => ({
            id: item.dataset.menuId,
            order: index
        }));
        
        fetch('{{ route("menus.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ menus: menus })
        }).then(response => {
            if (response.ok) {
                console.log('Menu order updated');
            }
        }).catch(error => console.error('Error:', error));
    }
</script>

@endsection
