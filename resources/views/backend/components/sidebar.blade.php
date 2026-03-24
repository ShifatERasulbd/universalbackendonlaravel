<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="logo-container">
        <div class="logo-icon">
            <span class="material-icons-round">dashboard</span>
        </div>
        <span class="logo-text">SSAdmin</span>
    </div>

    <nav class="nav-links">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="material-icons-round">grid_view</span>
            Dashboard
        </a>

        @can('roles.view')
        <a href="{{ route('roles.index') }}" class="nav-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
            <span class="material-icons-round">manage_accounts</span>
            Roles
        </a>
        @endcan

        @can('users.view')
        <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <span class="material-icons-round">group</span>
            Users
        </a>
        @endcan

        @can('menus.view')
        <a href="{{ route('menus.index') }}" class="nav-item {{ request()->routeIs('menus.*') ? 'active' : '' }}">
            <span class="material-icons-round">menu</span>
            Menus
        </a>
        @endcan

        @can('pages.view')
        <a href="{{ route('pages.index') }}" class="nav-item {{ request()->routeIs('pages.*') ? 'active' : '' }}">
            <span class="material-icons-round">description</span>
            Pages
        </a>
        @endcan

        @can('available-websites.view')
        <a href="{{ route('available-websites.index') }}" class="nav-item {{ request()->routeIs('available-websites.*') ? 'active' : '' }}">
            <span class="material-icons-round">language</span>
            Available Websites
        </a>
        @endcan

        <!-- @can('templates.view')
        <a href="{{ route('templates.index') }}" class="nav-item {{ request()->routeIs('templates.*') ? 'active' : '' }}">
            <span class="material-icons-round">view_quilt</span>
            Templates
        </a>
        @endcan -->
    </nav>

    <div class="user-profile">
        <img src="https://picsum.photos/seed/adminUser/100/100" alt="Admin" class="avatar">
        <div class="user-info">
            <h4>Alex Morgan</h4>
            <span>Super Admin</span>
        </div>
        <button class="action-btn" style="margin-left: auto;" onclick="showToast('Logging out...', 'default')">
            <span class="material-icons-round">logout</span>
        </button>
    </div>
</aside>

<script>
    function toggleSidebarSubmenu(id, trigger){
        const menu = document.getElementById(id);
        if(!menu) return;
        const isHidden = menu.style.display === 'none' || !menu.style.display;
        menu.style.display = isHidden ? 'block' : 'none';

        if(trigger){
            if(isHidden){
                trigger.classList.add('open');
            }else{
                trigger.classList.remove('open');
            }
        }
    }
</script>

