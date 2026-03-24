@extends('backend.master')

@section('title', 'Page Builder - NexusAdmin')
@section('page-title', 'Page Builder')

@section('content')

<div style="max-width:1200px;">
    <form action="{{ route('pages.builder.update', $page) }}" method="POST" id="page-builder-form">
        @csrf
        @method('PUT')

        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; gap:1rem;">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <a href="{{ route('pages.index') }}" style="display:inline-flex; align-items:center; color:var(--text-secondary); text-decoration:none; padding:0.35rem 0.7rem; border-radius:6px; border:1px solid var(--border-color); background:var(--input-bg); font-size:0.8rem;">
                    <span class="material-icons-round" style="font-size:1rem; margin-right:0.2rem;">arrow_back</span> Back
                </a>
                <div>
                    <h2 style="font-size:1.4rem; font-weight:600; color:var(--text-primary); margin:0;">Edit Page</h2>
                    <p style="font-size:0.875rem; color:var(--text-secondary); margin-top:0.1rem;">Modify page details and content</p>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:0.5rem;">
                <select style="padding:0.45rem 0.7rem; border-radius:6px; border:1px solid var(--border-color); background:var(--input-bg); color:var(--text-primary); font-size:0.8rem;">
                    <option>English</option>
                </select>
                <button type="submit" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:0.35rem;">
                    <span class="material-icons-round" style="font-size:1rem;">save</span> Save Changes
                </button>
            </div>
        </div>

        @if($errors->any())
            <div style="display:flex; align-items:flex-start; gap:0.5rem; padding:0.875rem 1rem; border-radius:8px; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); color:#dc2626; margin-bottom:1rem;">
                <span class="material-icons-round" style="font-size:1rem; margin-top:0.05rem;">error</span>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="builder-layout">
            <aside class="builder-widgets card">
                <h3>Widgets</h3>
                <p class="widgets-subtitle">Drag or click a widget to add it to the page</p>
                <div id="widget-palette" class="widget-grid"></div>
                <textarea name="excerpt" rows="3" placeholder="Short page summary" style="margin-top:0.8rem; width:100%; padding:0.65rem 0.8rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg,#fff); color:var(--text-primary); font-size:0.85rem; box-sizing:border-box; resize:vertical;">{{ old('excerpt', $page->excerpt) }}</textarea>
            </aside>

            <section class="builder-canvas card">
                <div id="drop-zone" class="drop-zone">Drop widgets here to build this page</div>
                <div id="builder-canvas" class="canvas-list"></div>
            </section>
        </div>

        <textarea id="content-json" name="content" style="display:none;">{{ old('content', $page->content) }}</textarea>
    </form>
</div>

<style>
    .builder-layout {
        display: grid;
        grid-template-columns: 310px 1fr;
        gap: 1rem;
        align-items: start;
    }

    .builder-widgets,
    .builder-canvas {
        border-radius: 16px;
        border: 1px solid var(--border-color);
        background: var(--card-bg);
        padding: 1rem;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.05);
    }

    .builder-widgets {
        max-height: 78vh;
        overflow: auto;
    }

    .builder-widgets h3 {
        margin: 0;
        font-size: 1.1rem;
        line-height: 1;
        font-weight: 600;
        color: var(--text-primary);
    }

    .widgets-subtitle {
        margin: 0.5rem 0 0.75rem;
        font-size: 0.78rem;
        color: var(--text-secondary);
    }

    .widget-grid {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .widget-item {
        border: 1px solid var(--accent-border, var(--border-color));
        border-radius: 12px;
        background: var(--accent-bg, var(--input-bg));
        padding: 0.65rem 0.7rem;
        text-align: left;
        cursor: grab;
        transition: all .15s ease;
        user-select: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.6rem;
    }

    .widget-item:hover {
        border-color: var(--accent-border, #cfd8e3);
        transform: translateY(-1px);
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.06);
    }

    .widget-icon {
        width: 28px;
        height: 28px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--accent-icon-bg, rgba(0,0,0,0.08));
        color: var(--accent-icon, var(--text-primary));
    }

    .widget-main {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        min-width: 0;
    }

    .widget-item .material-icons-round {
        font-size: 1.1rem;
        color: inherit;
    }

    .widget-item span {
        font-size: 0.8rem;
        color: var(--text-primary);
    }

    .widget-add {
        color: #3b82f6;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .builder-canvas {
        min-height: 78vh;
    }

    .drop-zone {
        border: 1px dashed var(--border-color);
        color: var(--text-secondary);
        border-radius: 8px;
        padding: 1.1rem;
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
        text-align: center;
    }

    .drop-zone.over {
        border-color: #3b82f6;
        background: rgba(59,130,246,0.06);
    }

    .canvas-list {
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
    }

    .canvas-block {
        border: 1px solid var(--accent-border, var(--border-color));
        border-radius: 12px;
        background: var(--accent-bg, var(--input-bg));
        border-left: 5px solid var(--accent-solid, #94a3b8);
        padding: 0.7rem 0.85rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.8rem;
        cursor: grab;
        transition: all .15s ease;
    }

    .canvas-block:hover {
        border-color: var(--accent-border, #cfd8e3);
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.06);
    }

    .canvas-block.dragging {
        opacity: 0.55;
    }

    .canvas-block.over {
        border-color: #3b82f6;
        background: rgba(59,130,246,0.05);
    }

    .canvas-left {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        min-width: 0;
    }

    .canvas-title {
        font-size: 0.86rem;
        font-weight: 600;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .canvas-actions {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .canvas-btn {
        border: none;
        background: transparent;
        color: var(--text-secondary);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 6px;
    }

    .canvas-btn:hover {
        background: rgba(0,0,0,0.05);
    }

    .canvas-editor {
        margin-top: 0.55rem;
        display: none;
    }

    .canvas-editor textarea {
        width: 100%;
        min-height: 100px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--card-bg);
        color: var(--text-primary);
        padding: 0.6rem 0.7rem;
        font-size: 0.82rem;
        resize: vertical;
        box-sizing: border-box;
    }

    .builder-widgets textarea {
        margin-top: 0.9rem !important;
    }

    @media (max-width: 1100px) {
        .builder-layout {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    const widgetDefinitions = [
        { type: 'slider', label: 'Slider', icon: 'image' },
        { type: 'search', label: 'Search', icon: 'search' },
        { type: 'about-us', label: 'About Us', icon: 'menu_book' },
        { type: 'property-type', label: 'Property Type', icon: 'home_work' },
        { type: 'property-city', label: 'Property By City', icon: 'location_city' },
        { type: 'why-choose-us', label: 'Why Choose Us', icon: 'task_alt' },
        { type: 'featured-property', label: 'Feature Property', icon: 'star' },
        { type: 'how-it-works', label: 'How it Works', icon: 'settings_suggest' },
        { type: 'recent-property', label: 'Recent Property', icon: 'history' },
        { type: 'advertisement', label: 'Advertisement', icon: 'campaign' },
        { type: 'testimonial', label: 'Testimonial', icon: 'format_quote' },
        { type: 'trusted-partners', label: 'Trusted Partners', icon: 'handshake' }
    ];

    const palette = document.getElementById('widget-palette');
    const canvas = document.getElementById('builder-canvas');
    const dropZone = document.getElementById('drop-zone');
    const contentField = document.getElementById('content-json');
    let blocks = [];
    let dragSourceId = null;

    function getAccent(type) {
        const paletteMap = {
            'slider': { bg: 'rgba(59,130,246,0.08)', border: 'rgba(59,130,246,0.35)', solid: '#3b82f6', iconBg: 'rgba(59,130,246,0.15)', icon: '#2563eb' },
            'search': { bg: 'rgba(16,185,129,0.08)', border: 'rgba(16,185,129,0.35)', solid: '#10b981', iconBg: 'rgba(16,185,129,0.15)', icon: '#059669' },
            'about-us': { bg: 'rgba(99,102,241,0.08)', border: 'rgba(99,102,241,0.35)', solid: '#6366f1', iconBg: 'rgba(99,102,241,0.15)', icon: '#4f46e5' },
            'property-type': { bg: 'rgba(245,158,11,0.09)', border: 'rgba(245,158,11,0.35)', solid: '#f59e0b', iconBg: 'rgba(245,158,11,0.15)', icon: '#d97706' },
            'property-city': { bg: 'rgba(14,165,233,0.09)', border: 'rgba(14,165,233,0.35)', solid: '#0ea5e9', iconBg: 'rgba(14,165,233,0.15)', icon: '#0284c7' },
            'why-choose-us': { bg: 'rgba(236,72,153,0.08)', border: 'rgba(236,72,153,0.35)', solid: '#ec4899', iconBg: 'rgba(236,72,153,0.15)', icon: '#db2777' },
            'featured-property': { bg: 'rgba(234,179,8,0.09)', border: 'rgba(234,179,8,0.35)', solid: '#eab308', iconBg: 'rgba(234,179,8,0.15)', icon: '#ca8a04' },
            'how-it-works': { bg: 'rgba(168,85,247,0.08)', border: 'rgba(168,85,247,0.35)', solid: '#a855f7', iconBg: 'rgba(168,85,247,0.15)', icon: '#9333ea' },
            'recent-property': { bg: 'rgba(20,184,166,0.08)', border: 'rgba(20,184,166,0.35)', solid: '#14b8a6', iconBg: 'rgba(20,184,166,0.15)', icon: '#0f766e' },
            'advertisement': { bg: 'rgba(239,68,68,0.08)', border: 'rgba(239,68,68,0.35)', solid: '#ef4444', iconBg: 'rgba(239,68,68,0.15)', icon: '#dc2626' },
            'testimonial': { bg: 'rgba(132,204,22,0.1)', border: 'rgba(132,204,22,0.35)', solid: '#84cc16', iconBg: 'rgba(132,204,22,0.15)', icon: '#65a30d' },
            'trusted-partners': { bg: 'rgba(251,146,60,0.09)', border: 'rgba(251,146,60,0.35)', solid: '#fb923c', iconBg: 'rgba(251,146,60,0.15)', icon: '#ea580c' }
        };

        return paletteMap[type] || { bg: 'rgba(148,163,184,0.08)', border: 'rgba(148,163,184,0.35)', solid: '#94a3b8', iconBg: 'rgba(148,163,184,0.2)', icon: '#475569' };
    }

    function buildPalette() {
        palette.innerHTML = '';
        widgetDefinitions.forEach(widget => {
            const el = document.createElement('div');
            el.className = 'widget-item';
            el.draggable = true;
            el.dataset.widgetType = widget.type;
            const accent = getAccent(widget.type);
            el.style.setProperty('--accent-bg', accent.bg);
            el.style.setProperty('--accent-border', accent.border);
            el.style.setProperty('--accent-icon-bg', accent.iconBg);
            el.style.setProperty('--accent-icon', accent.icon);
            el.innerHTML = `
                <div class="widget-main">
                    <span class="widget-icon"><span class="material-icons-round">${widget.icon}</span></span>
                    <span>${widget.label}</span>
                </div>
                <span class="widget-add">Add</span>
            `;

            el.addEventListener('dragstart', e => {
                e.dataTransfer.setData('source', 'palette');
                e.dataTransfer.setData('widgetType', widget.type);
            });

            el.addEventListener('click', () => {
                createBlock(widget.type);
            });

            palette.appendChild(el);
        });
    }

    function parseInitialBlocks() {
        const raw = contentField.value || '';
        if (!raw.trim()) {
            blocks = [];
            return;
        }

        try {
            const parsed = JSON.parse(raw);
            if (parsed && Array.isArray(parsed.blocks)) {
                blocks = parsed.blocks.map((block, index) => ({
                    id: block.id || `block-${Date.now()}-${index}`,
                    type: block.type || 'custom',
                    label: block.label || 'Custom Block',
                    body: block.body || ''
                }));
                return;
            }
        } catch (error) {
        }

        blocks = [{
            id: `block-${Date.now()}-legacy`,
            type: 'legacy-content',
            label: 'Legacy Content',
            body: raw
        }];
    }

    function getWidget(type) {
        return widgetDefinitions.find(w => w.type === type) || { type, label: type, icon: 'widgets' };
    }

    function createBlock(widgetType) {
        const widget = getWidget(widgetType);
        blocks.push({
            id: `block-${Date.now()}-${Math.random().toString(36).slice(2, 7)}`,
            type: widget.type,
            label: widget.label,
            body: ''
        });
        renderCanvas();
        syncContentJson();
    }

    function removeBlock(id) {
        blocks = blocks.filter(block => block.id !== id);
        renderCanvas();
        syncContentJson();
    }

    function toggleEditor(id) {
        const panel = document.querySelector(`[data-editor-id="${id}"]`);
        if (!panel) return;
        panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
    }

    function updateBody(id, value) {
        const block = blocks.find(item => item.id === id);
        if (!block) return;
        block.body = value;
        syncContentJson();
    }

    function moveBlock(sourceId, targetId) {
        if (!sourceId || !targetId || sourceId === targetId) return;

        const sourceIndex = blocks.findIndex(item => item.id === sourceId);
        const targetIndex = blocks.findIndex(item => item.id === targetId);
        if (sourceIndex === -1 || targetIndex === -1) return;

        const [moved] = blocks.splice(sourceIndex, 1);
        blocks.splice(targetIndex, 0, moved);

        renderCanvas();
        syncContentJson();
    }

    function renderCanvas() {
        canvas.innerHTML = '';

        if (blocks.length === 0) {
            dropZone.style.display = 'block';
        } else {
            dropZone.style.display = 'none';
        }

        blocks.forEach(block => {
            const widget = getWidget(block.type);
            const row = document.createElement('div');
            row.className = 'canvas-block';
            row.draggable = true;
            row.dataset.blockId = block.id;
            const accent = getAccent(block.type);
            row.style.setProperty('--accent-bg', accent.bg);
            row.style.setProperty('--accent-border', accent.border);
            row.style.setProperty('--accent-solid', accent.solid);

            row.innerHTML = `
                <div style="flex:1; min-width:0;">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:0.8rem;">
                        <div class="canvas-left">
                            <span class="material-icons-round" style="font-size:1rem; color:var(--text-secondary);">drag_indicator</span>
                            <span class="material-icons-round" style="font-size:1rem; color:var(--text-secondary);">${widget.icon}</span>
                            <span class="canvas-title">${block.label}</span>
                        </div>
                        <div class="canvas-actions">
                            <button type="button" class="canvas-btn" data-action="toggle" title="Edit block">
                                <span class="material-icons-round" style="font-size:1rem;">expand_more</span>
                            </button>
                            <button type="button" class="canvas-btn" data-action="delete" title="Delete block" style="color:#ef4444;">
                                <span class="material-icons-round" style="font-size:1rem;">delete</span>
                            </button>
                        </div>
                    </div>
                    <div class="canvas-editor" data-editor-id="${block.id}">
                        <textarea placeholder="Write content for ${block.label}...">${(block.body || '').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</textarea>
                    </div>
                </div>
            `;

            row.addEventListener('dragstart', e => {
                dragSourceId = block.id;
                e.dataTransfer.setData('source', 'canvas');
                e.dataTransfer.setData('blockId', block.id);
                row.classList.add('dragging');
            });

            row.addEventListener('dragend', () => {
                dragSourceId = null;
                row.classList.remove('dragging');
                document.querySelectorAll('.canvas-block').forEach(item => item.classList.remove('over'));
            });

            row.addEventListener('dragover', e => {
                e.preventDefault();
                if (dragSourceId && dragSourceId !== block.id) {
                    row.classList.add('over');
                }
            });

            row.addEventListener('dragleave', () => {
                row.classList.remove('over');
            });

            row.addEventListener('drop', e => {
                e.preventDefault();
                row.classList.remove('over');

                const source = e.dataTransfer.getData('source');
                if (source === 'palette') {
                    const widgetType = e.dataTransfer.getData('widgetType');
                    const insertIndex = blocks.findIndex(item => item.id === block.id);
                    const widget = getWidget(widgetType);
                    const newBlock = {
                        id: `block-${Date.now()}-${Math.random().toString(36).slice(2, 7)}`,
                        type: widget.type,
                        label: widget.label,
                        body: ''
                    };
                    blocks.splice(insertIndex, 0, newBlock);
                    renderCanvas();
                    syncContentJson();
                    return;
                }

                if (source === 'canvas') {
                    const fromId = e.dataTransfer.getData('blockId');
                    moveBlock(fromId, block.id);
                }
            });

            row.querySelector('[data-action="delete"]').addEventListener('click', () => removeBlock(block.id));
            row.querySelector('[data-action="toggle"]').addEventListener('click', () => toggleEditor(block.id));
            row.querySelector('textarea').addEventListener('input', e => updateBody(block.id, e.target.value));

            canvas.appendChild(row);
        });
    }

    function syncContentJson() {
        const payload = {
            builder: true,
            blocks: blocks.map((block, index) => ({
                id: block.id,
                type: block.type,
                label: block.label,
                body: block.body,
                order: index + 1
            }))
        };

        contentField.value = JSON.stringify(payload);
    }

    dropZone.addEventListener('dragover', e => {
        e.preventDefault();
        dropZone.classList.add('over');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('over');
    });

    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('over');
        const source = e.dataTransfer.getData('source');

        if (source === 'palette') {
            createBlock(e.dataTransfer.getData('widgetType'));
        }
    });

    buildPalette();
    parseInitialBlocks();
    renderCanvas();
    syncContentJson();
</script>

@endsection
