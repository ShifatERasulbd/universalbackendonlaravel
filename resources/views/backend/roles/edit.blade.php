@extends('backend.master')

@section('title', 'Edit Role - NexusAdmin')
@section('page-title', 'Edit Role')

@section('content')

<div style="max-width:760px;">

    <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.5rem;">
        <a href="{{ route('roles.index') }}" style="display:inline-flex; align-items:center; color:var(--text-secondary); text-decoration:none;">
            <span class="material-icons-round">arrow_back</span>
        </a>
        <div>
            <h2 style="font-size:1.25rem; font-weight:600; color:var(--text-primary);">Edit Role: <span style="color:#6366f1;">{{ $role->name }}</span></h2>
            <p style="font-size:0.875rem; color:var(--text-secondary); margin-top:0.2rem;">Update role name and permission assignments.</p>
        </div>
    </div>

    <form action="{{ route('roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Role Name --}}
        <div class="card" style="border-radius:12px; background:var(--card-bg); border:1px solid var(--border-color); padding:1.5rem; margin-bottom:1.25rem;">
            <label style="display:block; font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">
                Role Name <span style="color:#dc2626;">*</span>
            </label>
            <input
                type="text"
                name="name"
                value="{{ old('name', $role->name) }}"
                {{ $role->name === 'super-admin' ? 'readonly' : '' }}
                style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1px solid {{ $errors->has('name') ? '#dc2626' : 'var(--border-color)' }}; background:{{ $role->name === 'super-admin' ? 'var(--table-header-bg, rgba(0,0,0,0.04))' : 'var(--input-bg, #fff)' }}; color:var(--text-primary); font-size:0.9rem; outline:none; box-sizing:border-box; {{ $role->name === 'super-admin' ? 'cursor:not-allowed;' : '' }}"
            >
            @if($role->name === 'super-admin')
                <p style="margin-top:0.4rem; font-size:0.78rem; color:var(--text-secondary);">The super-admin role name cannot be changed.</p>
            @endif
            @error('name')
                <p style="margin-top:0.4rem; font-size:0.8rem; color:#dc2626;">{{ $message }}</p>
            @enderror
        </div>

        {{-- Permissions --}}
        @if($permissions->isNotEmpty())
        <div class="card" style="border-radius:12px; background:var(--card-bg); border:1px solid var(--border-color); padding:1.5rem; margin-bottom:1.5rem;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem;">
                <div>
                    <h3 style="font-size:1rem; font-weight:600; color:var(--text-primary);">Permissions</h3>
                    <p style="font-size:0.8rem; color:var(--text-secondary); margin-top:0.2rem;">
                        {{ count($rolePermissions) }} of {{ $permissions->flatten()->count() }} permissions assigned.
                    </p>
                </div>
                <label style="display:inline-flex; align-items:center; gap:0.4rem; font-size:0.8rem; color:var(--text-secondary); cursor:pointer;">
                    <input type="checkbox" id="select-all" style="width:15px; height:15px; cursor:pointer;"> Select All
                </label>
            </div>

            @foreach($permissions as $group => $groupPerms)
            <div style="margin-bottom:1.25rem;">
                <p style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-secondary); margin-bottom:0.6rem; padding-bottom:0.4rem; border-bottom:1px solid var(--border-color);">{{ $group }}</p>
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:0.5rem;">
                    @foreach($groupPerms as $permission)
                    <label style="display:inline-flex; align-items:center; gap:0.5rem; font-size:0.875rem; color:var(--text-primary); cursor:pointer; padding:0.4rem 0.5rem; border-radius:6px; border:1px solid {{ in_array($permission->name, $rolePermissions) ? 'rgba(99,102,241,0.4)' : 'var(--border-color)' }}; background:{{ in_array($permission->name, $rolePermissions) ? 'rgba(99,102,241,0.05)' : 'transparent' }};">
                        <input
                            type="checkbox"
                            name="permissions[]"
                            value="{{ $permission->name }}"
                            class="perm-check"
                            {{ in_array($permission->name, old('permissions', $rolePermissions)) ? 'checked' : '' }}
                            style="width:15px; height:15px; cursor:pointer;"
                        >
                        {{ $permission->name }}
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div style="display:flex; gap:0.75rem;">
            <button type="submit" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:0.4rem;">
                <span class="material-icons-round" style="font-size:1.1rem;">save</span> Update Role
            </button>
            <a href="{{ route('roles.index') }}" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.55rem 1.25rem; border-radius:8px; border:1px solid var(--border-color); font-size:0.9rem; color:var(--text-secondary); text-decoration:none;">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.perm-check');

    function syncSelectAll() {
        selectAll.checked = [...checkboxes].every(c => c.checked);
        selectAll.indeterminate = !selectAll.checked && [...checkboxes].some(c => c.checked);
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
        checkboxes.forEach(cb => cb.addEventListener('change', syncSelectAll));
        syncSelectAll();
    }
</script>
@endpush
