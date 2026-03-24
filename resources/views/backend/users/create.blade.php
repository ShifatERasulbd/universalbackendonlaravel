@extends('backend.master')

@section('title', 'Create User - NexusAdmin')
@section('page-title', 'Create User')

@section('content')

<div style="max-width:780px;">
    <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.5rem;">
        <a href="{{ route('users.index') }}" style="display:inline-flex; align-items:center; color:var(--text-secondary); text-decoration:none;">
            <span class="material-icons-round">arrow_back</span>
        </a>
        <div>
            <h2 style="font-size:1.25rem; font-weight:600; color:var(--text-primary);">Create User</h2>
            <p style="font-size:0.875rem; color:var(--text-secondary); margin-top:0.2rem;">Add a new user account and assign roles.</p>
        </div>
    </div>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="card" style="border-radius:12px; background:var(--card-bg); border:1px solid var(--border-color); padding:1.5rem; margin-bottom:1.25rem;">
            <div style="display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:1rem;">
                <div style="grid-column:1 / -1;">
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">Name <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1px solid {{ $errors->has('name') ? '#dc2626' : 'var(--border-color)' }}; background:var(--input-bg, #fff); color:var(--text-primary); font-size:0.9rem; box-sizing:border-box;">
                    @error('name')
                        <p style="margin-top:0.4rem; font-size:0.8rem; color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>
                <div style="grid-column:1 / -1;">
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">Email <span style="color:#dc2626;">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1px solid {{ $errors->has('email') ? '#dc2626' : 'var(--border-color)' }}; background:var(--input-bg, #fff); color:var(--text-primary); font-size:0.9rem; box-sizing:border-box;">
                    @error('email')
                        <p style="margin-top:0.4rem; font-size:0.8rem; color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">Password <span style="color:#dc2626;">*</span></label>
                    <input type="password" name="password" style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1px solid {{ $errors->has('password') ? '#dc2626' : 'var(--border-color)' }}; background:var(--input-bg, #fff); color:var(--text-primary); font-size:0.9rem; box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">Confirm Password <span style="color:#dc2626;">*</span></label>
                    <input type="password" name="password_confirmation" style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1px solid var(--border-color); background:var(--input-bg, #fff); color:var(--text-primary); font-size:0.9rem; box-sizing:border-box;">
                </div>
            </div>
            @error('password')
                <p style="margin-top:0.4rem; font-size:0.8rem; color:#dc2626;">{{ $message }}</p>
            @enderror
        </div>

        @can('users.assign-roles')
        <div class="card" style="border-radius:12px; background:var(--card-bg); border:1px solid var(--border-color); padding:1.5rem; margin-bottom:1.5rem;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
                <div>
                    <h3 style="font-size:1rem; font-weight:600; color:var(--text-primary);">Roles</h3>
                    <p style="font-size:0.8rem; color:var(--text-secondary); margin-top:0.2rem;">Assign one or more roles to the new user. The super-admin role is protected and excluded.</p>
                </div>
                <label style="display:inline-flex; align-items:center; gap:0.4rem; font-size:0.8rem; color:var(--text-secondary); cursor:pointer;">
                    <input type="checkbox" id="select-all" style="width:15px; height:15px; cursor:pointer;"> Select All
                </label>
            </div>

            @if($roles->isEmpty())
                <p style="text-align:center; color:var(--text-secondary); padding:1rem 0; font-size:0.875rem;">No assignable roles found.</p>
            @else
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:0.65rem;">
                    @foreach($roles as $role)
                    @php $checked = in_array($role->name, old('roles', [])); @endphp
                    <label style="display:flex; align-items:center; gap:0.65rem; padding:0.65rem 0.85rem; border-radius:8px; cursor:pointer; border:1px solid {{ $checked ? 'rgba(99,102,241,0.4)' : 'var(--border-color)' }}; background:{{ $checked ? 'rgba(99,102,241,0.06)' : 'transparent' }};">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="role-check" {{ $checked ? 'checked' : '' }} style="width:16px; height:16px; cursor:pointer; accent-color:#6366f1;">
                        <span style="font-weight:600; font-size:0.875rem; color:var(--text-primary);">{{ $role->name }}</span>
                    </label>
                    @endforeach
                </div>
            @endif
            @error('roles.*')
                <p style="margin-top:0.75rem; font-size:0.8rem; color:#dc2626;">{{ $message }}</p>
            @enderror
        </div>
        @endcan

        <div style="display:flex; gap:0.75rem;">
            <button type="submit" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:0.4rem;">
                <span class="material-icons-round" style="font-size:1.1rem;">save</span> Create User
            </button>
            <a href="{{ route('users.index') }}" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.55rem 1.25rem; border-radius:8px; border:1px solid var(--border-color); font-size:0.9rem; color:var(--text-secondary); text-decoration:none;">Cancel</a>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.role-check');

    function syncRoleCards() {
        checkboxes.forEach((checkbox) => {
            const label = checkbox.closest('label');
            if (!label) return;
            label.style.borderColor = checkbox.checked ? 'rgba(99,102,241,0.4)' : 'var(--border-color)';
            label.style.background = checkbox.checked ? 'rgba(99,102,241,0.06)' : 'transparent';
        });
    }

    function syncSelectAll() {
        if (!selectAll) return;
        selectAll.checked = checkboxes.length > 0 && [...checkboxes].every((checkbox) => checkbox.checked);
        selectAll.indeterminate = !selectAll.checked && [...checkboxes].some((checkbox) => checkbox.checked);
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach((checkbox) => {
                checkbox.checked = this.checked;
            });
            syncRoleCards();
            syncSelectAll();
        });
    }

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', () => {
            syncRoleCards();
            syncSelectAll();
        });
    });

    syncRoleCards();
    syncSelectAll();
</script>
@endpush
