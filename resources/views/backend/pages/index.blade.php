@extends('backend.master')

@section('title', 'Pages - NexusAdmin')
@section('page-title', 'Page Management')

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <div>
        <h2 style="font-size:1.25rem; font-weight:600; color:var(--text-primary);">Pages</h2>
        <p style="font-size:0.875rem; color:var(--text-secondary); margin-top:0.25rem;">Drag and drop to arrange pages. Click to edit or delete.</p>
    </div>
    @can('pages.create')
    <a href="{{ route('pages.create') }}" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:0.4rem;">
        <span class="material-icons-round" style="font-size:1.1rem;">add</span> New Page
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
    @if($pages->isEmpty())
        <div style="padding:3rem; text-align:center; color:var(--text-secondary);">
            <span class="material-icons-round" style="font-size:2.5rem; display:block; margin-bottom:0.5rem; opacity:0.4;">description</span>
            <p>No pages found. Create your first page to get started.</p>
        </div>
    @else
        <div id="page-list" style="padding:1rem;">
            @foreach($pages as $page)
                @include('backend.pages.page-item', ['page' => $page])
            @endforeach
        </div>
    @endif
</div>

<style>
    .page-item {
        background: #f8f9fa;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        cursor: move;
        transition: all 0.2s ease;
    }
    
    .page-item:hover {
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .page-item.dragging {
        opacity: 0.5;
        background: #e0e7ff;
    }
    
    .page-item.drag-over {
        border: 2px dashed #6366f1;
        background: #f0f4ff;
    }
    
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    
    .page-drag-handle {
        cursor: grab;
        color: #9ca3af;
    }
    
    .page-drag-handle:active {
        cursor: grabbing;
    }
    
    .page-info {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .page-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: linear-gradient(135deg, #ec4899, #f97316);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    
    .page-details {
        flex: 1;
    }
    
    .page-title {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }
    
    .page-meta {
        font-size: 0.8rem;
        color: var(--text-secondary);
        display: flex;
        gap: 1rem;
    }
    
    .page-meta-item {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    .page-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .page-badge.draft {
        background: rgba(156,163,175,0.15);
        color: #6b7280;
    }
    
    .page-badge.published {
        background: rgba(16,185,129,0.15);
        color: #059669;
    }
    
    .page-badge.scheduled {
        background: rgba(59,130,246,0.15);
        color: #2563eb;
    }
    
    .page-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .page-action-btn {
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
    
    .page-action-edit {
        background: rgba(99,102,241,0.1);
        color: #6366f1;
        border: 1px solid rgba(99,102,241,0.2);
    }
    
    .page-action-edit:hover {
        background: rgba(99,102,241,0.2);
    }
    
    .page-action-delete {
        background: rgba(239,68,68,0.1);
        color: #dc2626;
        border: 1px solid rgba(239,68,68,0.2);
    }
    
    .page-action-delete:hover {
        background: rgba(239,68,68,0.2);
    }
</style>

<script>
    let draggedItem = null;
    
    document.addEventListener('dragstart', function(e) {
        if (e.target.closest('.page-item')) {
            draggedItem = e.target.closest('.page-item');
            draggedItem.classList.add('dragging');
        }
    });
    
    document.addEventListener('dragend', function(e) {
        if (draggedItem) {
            draggedItem.classList.remove('dragging');
            document.querySelectorAll('.page-item').forEach(item => {
                item.classList.remove('drag-over');
            });
            draggedItem = null;
        }
    });
    
    document.addEventListener('dragover', function(e) {
        e.preventDefault();
        if (draggedItem) {
            const target = e.target.closest('.page-item');
            if (target && target !== draggedItem) {
                target.classList.add('drag-over');
            }
        }
    });
    
    document.addEventListener('dragleave', function(e) {
        const target = e.target.closest('.page-item');
        if (target && draggedItem && target !== draggedItem) {
            target.classList.remove('drag-over');
        }
    });
    
    document.addEventListener('drop', function(e) {
        e.preventDefault();
        const target = e.target.closest('.page-item');
        if (target && draggedItem && target !== draggedItem) {
            target.classList.remove('drag-over');
            const pageList = document.getElementById('page-list');
            const allItems = Array.from(pageList.querySelectorAll(':scope > .page-item'));
            const draggedIndex = allItems.indexOf(draggedItem);
            const targetIndex = allItems.indexOf(target);
            
            if (draggedIndex < targetIndex) {
                target.after(draggedItem);
            } else {
                target.before(draggedItem);
            }
            
            updatePageOrder();
        }
    });
    
    function updatePageOrder() {
        const pageList = document.getElementById('page-list');
        const pages = Array.from(pageList.querySelectorAll(':scope > .page-item')).map((item, index) => ({
            id: item.dataset.pageId,
            order: index
        }));
        
        fetch('{{ route("pages.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ pages: pages })
        }).then(response => {
            if (response.ok) {
                console.log('Page order updated');
            }
        }).catch(error => console.error('Error:', error));
    }
</script>

@endsection
