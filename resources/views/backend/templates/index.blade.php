@extends('backend.master')

@section('title', 'Templates - NexusAdmin')
@section('page-title', 'Templates')

@section('content')

<div style="display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:1.5rem; gap:1rem; flex-wrap:wrap;">
    <div>
        <h2 style="font-size:1.25rem; font-weight:600; color:var(--text-primary);">Templates</h2>
        <p style="font-size:0.875rem; color:var(--text-secondary); margin-top:0.25rem;">Manage templates and drag to reorder within listing.</p>
    </div>

    <div style="display:flex; align-items:center; gap:0.6rem;">
        <form method="GET" action="{{ route('templates.index') }}" style="display:flex; align-items:center; gap:0.5rem;">
            <select name="available_website_id" onchange="this.form.submit()" style="padding:0.55rem 0.75rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg,#fff); color:var(--text-primary); min-width:220px;">
                <option value="">All Categories</option>
                @foreach($categories as $id => $name)
                    <option value="{{ $id }}" {{ (string)$selectedCategory === (string)$id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </form>

        @can('templates.create')
        <a href="{{ route('templates.create', ['available_website_id' => $selectedCategory]) }}" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:0.4rem;">
            <span class="material-icons-round" style="font-size:1.1rem;">add</span> New Template
        </a>
        @endcan
    </div>
</div>

@if(session('success'))
<div style="display:flex; align-items:center; gap:0.5rem; padding:0.875rem 1rem; border-radius:8px; background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.3); color:#059669; margin-bottom:1.25rem;">
    <span class="material-icons-round" style="font-size:1.1rem;">check_circle</span>
    {{ session('success') }}
</div>
@endif

<div class="card" style="border-radius:12px; background:var(--card-bg); border:1px solid var(--border-color); overflow:hidden;">
    @if($templates->isEmpty())
        <div style="padding:3rem; text-align:center; color:var(--text-secondary);">
            <span class="material-icons-round" style="font-size:2.5rem; display:block; margin-bottom:0.5rem; opacity:0.4;">view_quilt</span>
            <p>No templates found for the selected category.</p>
        </div>
    @else
        <div id="template-list" style="padding:1rem;">
            @foreach($templates as $template)
                @include('backend.templates.template-item', ['template' => $template])
            @endforeach
        </div>
    @endif
</div>

<style>
    .template-item {
        background:#f8f9fa;
        border:1px solid #e5e7eb;
        border-radius:10px;
        padding:1rem;
        margin-bottom:.75rem;
        cursor:move;
        transition:all .2s ease;
    }
    .template-item:hover { background:#fff; box-shadow:0 2px 8px rgba(0,0,0,.08); }
    .template-item.dragging { opacity:.5; background:#eef2ff; }
    .template-item.drag-over { border:2px dashed #6366f1; background:#f5f7ff; }
</style>

<script>
    let draggedItem = null;

    document.addEventListener('dragstart', function(e) {
        if (e.target.closest('.template-item')) {
            draggedItem = e.target.closest('.template-item');
            draggedItem.classList.add('dragging');
        }
    });

    document.addEventListener('dragend', function() {
        if (draggedItem) {
            draggedItem.classList.remove('dragging');
            document.querySelectorAll('.template-item').forEach(item => item.classList.remove('drag-over'));
            draggedItem = null;
        }
    });

    document.addEventListener('dragover', function(e) {
        e.preventDefault();
        if (draggedItem) {
            const target = e.target.closest('.template-item');
            if (target && target !== draggedItem) target.classList.add('drag-over');
        }
    });

    document.addEventListener('dragleave', function(e) {
        const target = e.target.closest('.template-item');
        if (target && draggedItem && target !== draggedItem) target.classList.remove('drag-over');
    });

    document.addEventListener('drop', function(e) {
        e.preventDefault();
        const target = e.target.closest('.template-item');
        if (target && draggedItem && target !== draggedItem) {
            target.classList.remove('drag-over');
            const templateList = document.getElementById('template-list');
            const allItems = Array.from(templateList.querySelectorAll(':scope > .template-item'));
            const draggedIndex = allItems.indexOf(draggedItem);
            const targetIndex = allItems.indexOf(target);

            if (draggedIndex < targetIndex) {
                target.after(draggedItem);
            } else {
                target.before(draggedItem);
            }

            updateTemplateOrder();
        }
    });

    function updateTemplateOrder() {
        const templateList = document.getElementById('template-list');
        const templates = Array.from(templateList.querySelectorAll(':scope > .template-item')).map((item, index) => ({
            id: item.dataset.templateId,
            order: index
        }));

        fetch('{{ route("templates.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ templates })
        });
    }
</script>

@endsection
