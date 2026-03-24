<!-- Header -->
<header>
    <div class="header-left" style="display: flex; align-items: center;">
        <button class="hamburger" onclick="toggleSidebar()">
            <span class="material-icons-round">menu</span>
        </button>
        <h2 id="page-title">@yield('page-title', 'Dashboard')</h2>
    </div>
    <div class="header-right">
        <div class="search-bar">
            <span class="material-icons-round" style="color: var(--text-muted); font-size: 18px;">search</span>
            <input type="text" placeholder="Search orders, products...">
        </div>
        <button class="icon-btn" onclick="showToast('No new notifications', 'default')">
            <span class="material-icons-round">notifications</span>
            <span class="badge">3</span>
        </button>
        <button class="icon-btn" onclick="()">
            <span class="material-icons-round">add</span>
        </button>
    </div>
</header>

