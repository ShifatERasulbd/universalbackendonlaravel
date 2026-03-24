@extends('backend.master')

@section('title', 'Available Websites - NexusAdmin')
@section('page-title', 'Available Websites')

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <div>
        <h2 style="font-size:1.25rem; font-weight:600; color:var(--text-primary);">Available Websites</h2>
        <p style="font-size:0.875rem; color:var(--text-secondary); margin-top:0.25rem;">Manage available websites and drag to reorder display priority.</p>
    </div>
    @can('available-websites.create')
    <a href="{{ route('available-websites.create') }}" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:0.4rem;">
        <span class="material-icons-round" style="font-size:1.1rem;">add</span> New Website
    </a>
    @endcan
</div>

@if(session('success'))
<div style="display:flex; align-items:center; gap:0.5rem; padding:0.875rem 1rem; border-radius:8px; background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.3); color:#059669; margin-bottom:1.25rem;">
    <span class="material-icons-round" style="font-size:1.1rem;">check_circle</span>
    {{ session('success') }}
</div>
@endif

<div class="card" style="border-radius:12px; background:var(--card-bg); border:1px solid var(--border-color); overflow:hidden;">
    @if($websites->isEmpty())
        <div style="padding:3rem; text-align:center; color:var(--text-secondary);">
            <span class="material-icons-round" style="font-size:2.5rem; display:block; margin-bottom:0.5rem; opacity:0.4;">language</span>
            <p>No websites found. Create your first website entry.</p>
        </div>
    @else
        <div id="website-list" style="padding:1rem;">
            @foreach($websites as $website)
                @include('backend.available-websites.website-item', ['website' => $website])
            @endforeach
        </div>
    @endif
</div>

<style>
    .website-item {
        background: #f8f9fa;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        cursor: move;
        transition: all 0.2s ease;
    }
    .website-item:hover { background:#fff; box-shadow:0 2px 8px rgba(0,0,0,.08); }
    .website-item.dragging { opacity:.5; background:#eef2ff; }
    .website-item.drag-over { border:2px dashed #6366f1; background:#f5f7ff; }
</style>

<script>
    let draggedItem = null;

    document.addEventListener('dragstart', function(e) {
        if (e.target.closest('.website-item')) {
            draggedItem = e.target.closest('.website-item');
            draggedItem.classList.add('dragging');
        }
    });

    document.addEventListener('dragend', function() {
        if (draggedItem) {
            draggedItem.classList.remove('dragging');
            document.querySelectorAll('.website-item').forEach(item => item.classList.remove('drag-over'));
            draggedItem = null;
        }
    });

    document.addEventListener('dragover', function(e) {
        e.preventDefault();
        if (draggedItem) {
            const target = e.target.closest('.website-item');
            if (target && target !== draggedItem) target.classList.add('drag-over');
        }
    });

    document.addEventListener('dragleave', function(e) {
        const target = e.target.closest('.website-item');
        if (target && draggedItem && target !== draggedItem) target.classList.remove('drag-over');
    });

    document.addEventListener('drop', function(e) {
        e.preventDefault();
        const target = e.target.closest('.website-item');
        if (target && draggedItem && target !== draggedItem) {
            target.classList.remove('drag-over');
            const websiteList = document.getElementById('website-list');
            const allItems = Array.from(websiteList.querySelectorAll(':scope > .website-item'));
            const draggedIndex = allItems.indexOf(draggedItem);
            const targetIndex = allItems.indexOf(target);

            if (draggedIndex < targetIndex) {
                target.after(draggedItem);
            } else {
                target.before(draggedItem);
            }

            updateWebsiteOrder();
        }
    });

    function updateWebsiteOrder() {
        const websiteList = document.getElementById('website-list');
        const websites = Array.from(websiteList.querySelectorAll(':scope > .website-item')).map((item, index) => ({
            id: item.dataset.websiteId,
            order: index
        }));

        fetch('{{ route("available-websites.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ websites })
        });
    }
</script>

@endsection
