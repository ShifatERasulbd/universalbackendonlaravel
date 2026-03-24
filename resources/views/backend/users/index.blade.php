@extends('backend.master')

@section('title', 'Users - NexusAdmin')
@section('page-title', 'User Management')

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <div>
        <h2 style="font-size:1.25rem; font-weight:600; color:var(--text-primary);">Users</h2>
        <p style="font-size:0.875rem; color:var(--text-secondary); margin-top:0.25rem;">Create, update, delete, and manage roles for each user.</p>
    </div>
    @can('users.create')
    <a href="{{ route('users.create') }}" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:0.4rem;">
        <span class="material-icons-round" style="font-size:1.1rem;">person_add</span> New User
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
@if(session('error'))
<div style="display:flex; align-items:center; gap:0.5rem; padding:0.875rem 1rem; border-radius:8px; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); color:#dc2626; margin-bottom:1.25rem;">
    <span class="material-icons-round" style="font-size:1.1rem;">error</span>
    {{ session('error') }}
</div>
@endif

<div class="card" style="border-radius:12px; background:var(--card-bg); border:1px solid var(--border-color); overflow:hidden;">
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="background:var(--table-header-bg, rgba(0,0,0,0.03)); border-bottom:1px solid var(--border-color);">
                <th style="padding:0.875rem 1.25rem; text-align:left; font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-secondary);">#</th>
                <th style="padding:0.875rem 1.25rem; text-align:left; font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-secondary);">User</th>
                <th style="padding:0.875rem 1.25rem; text-align:left; font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-secondary);">Email</th>
                <th style="padding:0.875rem 1.25rem; text-align:left; font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-secondary);">Roles</th>
                <th style="padding:0.875rem 1.25rem; text-align:right; font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-secondary);">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr style="border-bottom:1px solid var(--border-color);">
                <td style="padding:1rem 1.25rem; font-size:0.875rem; color:var(--text-secondary);">{{ $loop->iteration }}</td>

                <td style="padding:1rem 1.25rem;">
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        <div style="width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg,#6366f1,#8b5cf6); display:flex; align-items:center; justify-content:center; font-size:0.85rem; font-weight:700; color:#fff; flex-shrink:0;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span style="font-weight:600; color:var(--text-primary); font-size:0.9rem;">
                            {{ $user->name }}
                            @if($user->id === auth()->id())
                                <span style="font-size:0.7rem; padding:1px 6px; border-radius:20px; background:rgba(16,185,129,0.15); color:#059669; font-weight:600; margin-left:4px;">You</span>
                            @endif
                        </span>
                    </div>
                </td>

                <td style="padding:1rem 1.25rem; font-size:0.875rem; color:var(--text-secondary);">{{ $user->email }}</td>

                <td style="padding:1rem 1.25rem;">
                    <div style="display:flex; flex-wrap:wrap; gap:0.35rem;">
                        @forelse($user->roles as $role)
                            <span style="font-size:0.72rem; padding:3px 10px; border-radius:20px;
                                background:{{ $role->name === 'super-admin' ? 'rgba(99,102,241,0.15)' : 'rgba(16,185,129,0.12)' }};
                                color:{{ $role->name === 'super-admin' ? '#6366f1' : '#059669' }};
                                font-weight:600; white-space:nowrap;">
                                {{ $role->name }}
                            </span>
                        @empty
                            <span style="font-size:0.8rem; color:var(--text-secondary); font-style:italic;">No role</span>
                        @endforelse
                    </div>
                </td>

                <td style="padding:1rem 1.25rem; text-align:right;">
                    @if($user->hasRole('super-admin'))
                        <span style="font-size:0.78rem; color:var(--text-secondary); font-style:italic; display:inline-flex; align-items:center; gap:0.3rem;">
                            <span class="material-icons-round" style="font-size:1rem;">lock</span> Protected
                        </span>
                    @else
                        <div style="display:inline-flex; gap:0.5rem; align-items:center;">
                            @can('users.edit')
                            <a href="{{ route('users.edit', $user) }}"
                               style="display:inline-flex; align-items:center; gap:0.3rem; padding:0.4rem 0.85rem; border-radius:6px; font-size:0.8rem; font-weight:500; background:rgba(99,102,241,0.1); color:#6366f1; text-decoration:none; border:1px solid rgba(99,102,241,0.2);">
                                <span class="material-icons-round" style="font-size:0.95rem;">edit</span> Edit
                            </a>
                            @endcan
                            @can('users.delete')
                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete user \'{{ $user->name }}\'? This cannot be undone.');" style="display:inline-flex;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="display:inline-flex; align-items:center; gap:0.3rem; padding:0.4rem 0.85rem; border-radius:6px; font-size:0.8rem; font-weight:500; background:rgba(239,68,68,0.1); color:#dc2626; border:1px solid rgba(239,68,68,0.2); cursor:pointer;">
                                    <span class="material-icons-round" style="font-size:0.95rem;">delete</span> Delete
                                </button>
                            </form>
                            @endcan
                        </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:3rem; text-align:center; color:var(--text-secondary);">
                    <span class="material-icons-round" style="font-size:2.5rem; display:block; margin-bottom:0.5rem; opacity:0.4;">group</span>
                    No users found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($users->hasPages())
    <div style="padding:1rem 1.25rem; border-top:1px solid var(--border-color);">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
