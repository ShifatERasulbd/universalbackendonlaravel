@extends('backend.master')

@section('title', 'Role Management - NexusAdmin')
@section('page-title', 'Role Management')

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <div>
        <h2 style="font-size:1.25rem; font-weight:600; color:var(--text-primary);">Roles</h2>
        <p style="font-size:0.875rem; color:var(--text-secondary); margin-top:0.25rem;">Manage user roles and their permissions.</p>
    </div>
    @can('roles.create')
    <a href="{{ route('roles.create') }}" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:0.4rem;">
        <span class="material-icons-round" style="font-size:1.1rem;">add</span> New Role
    </a>
    @endcan
</div>

{{-- Flash messages --}}
@if(session('success'))
<div class="alert alert-success" style="display:flex; align-items:center; gap:0.5rem; padding:0.875rem 1rem; border-radius:8px; background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.3); color:#059669; margin-bottom:1.25rem;">
    <span class="material-icons-round" style="font-size:1.1rem;">check_circle</span>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert alert-danger" style="display:flex; align-items:center; gap:0.5rem; padding:0.875rem 1rem; border-radius:8px; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); color:#dc2626; margin-bottom:1.25rem;">
    <span class="material-icons-round" style="font-size:1.1rem;">error</span>
    {{ session('error') }}
</div>
@endif

<div class="card" style="border-radius:12px; background:var(--card-bg); border:1px solid var(--border-color); overflow:hidden;">
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="background:var(--table-header-bg, rgba(0,0,0,0.03)); border-bottom:1px solid var(--border-color);">
                <th style="padding:0.875rem 1.25rem; text-align:left; font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-secondary);">#</th>
                <th style="padding:0.875rem 1.25rem; text-align:left; font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-secondary);">Role Name</th>
                <th style="padding:0.875rem 1.25rem; text-align:left; font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-secondary);">Permissions</th>
                <th style="padding:0.875rem 1.25rem; text-align:left; font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-secondary);">Users</th>
                <th style="padding:0.875rem 1.25rem; text-align:right; font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-secondary);">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
            <tr style="border-bottom:1px solid var(--border-color);">
                <td style="padding:1rem 1.25rem; font-size:0.875rem; color:var(--text-secondary);">{{ $loop->iteration }}</td>
                <td style="padding:1rem 1.25rem;">
                    <span style="font-weight:600; color:var(--text-primary); font-size:0.9rem;">{{ $role->name }}</span>
                    @if($role->name === 'super-admin')
                        <span style="margin-left:0.5rem; font-size:0.7rem; padding:2px 8px; border-radius:20px; background:rgba(99,102,241,0.15); color:#6366f1; font-weight:600;">System</span>
                    @endif
                </td>
                <td style="padding:1rem 1.25rem;">
                    <span style="display:inline-flex; align-items:center; gap:0.35rem; font-size:0.875rem; color:var(--text-secondary);">
                        <span class="material-icons-round" style="font-size:1rem; color:#6366f1;">shield</span>
                        {{ $role->permissions_count }}
                    </span>
                </td>
                <td style="padding:1rem 1.25rem;">
                    <span style="display:inline-flex; align-items:center; gap:0.35rem; font-size:0.875rem; color:var(--text-secondary);">
                        <span class="material-icons-round" style="font-size:1rem; color:#10b981;">group</span>
                        {{ $role->users_count }}
                    </span>
                </td>
                <td style="padding:1rem 1.25rem; text-align:right;">
                    <div style="display:inline-flex; gap:0.5rem;">
                        @can('roles.edit')
                        @if($role->name !== 'super-admin')
                        <a href="{{ route('roles.edit', $role) }}" style="display:inline-flex; align-items:center; gap:0.3rem; padding:0.4rem 0.85rem; border-radius:6px; font-size:0.8rem; font-weight:500; background:rgba(99,102,241,0.1); color:#6366f1; text-decoration:none; border:1px solid rgba(99,102,241,0.2);">
                            <span class="material-icons-round" style="font-size:0.95rem;">edit</span> Edit
                        </a>
                        @endif
                        @endcan
                        @can('roles.delete')
                        @if($role->name !== 'super-admin')
                        <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Delete role \'{{ $role->name }}\'? This cannot be undone.');">
                            @csrf @method('DELETE')
                            <button type="submit" style="display:inline-flex; align-items:center; gap:0.3rem; padding:0.4rem 0.85rem; border-radius:6px; font-size:0.8rem; font-weight:500; background:rgba(239,68,68,0.1); color:#dc2626; border:1px solid rgba(239,68,68,0.2); cursor:pointer;">
                                <span class="material-icons-round" style="font-size:0.95rem;">delete</span> Delete
                            </button>
                        </form>
                        @endif
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:3rem; text-align:center; color:var(--text-secondary);">
                    <span class="material-icons-round" style="font-size:2.5rem; display:block; margin-bottom:0.5rem; opacity:0.4;">manage_accounts</span>
                    No roles found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($roles->hasPages())
    <div style="padding:1rem 1.25rem; border-top:1px solid var(--border-color);">
        {{ $roles->links() }}
    </div>
    @endif
</div>

@endsection
