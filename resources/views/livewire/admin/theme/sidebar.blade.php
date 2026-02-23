<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ url('assets/backend/images/logo-sm.png') }}" alt="" height="26">
            </span>
            <span class="logo-lg">
                <img src="{{ url('assets/backend/images/logo-dark.png') }}" alt="" height="26">
            </span>
        </a>
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ url('assets/backend/images/logo-sm.png') }}" alt="" height="26">
            </span>
            <span class="logo-lg">
                <img src="{{ url('assets/backend/images/logo-light.png') }}" alt="" height="26">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">

                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a href="{{ route('admin.home') }}" class="nav-link menu-link {{ Route::is('admin.home') ? 'active' : '' }}" wire:navigate>
                        <i class="bi bi-speedometer2"></i> <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Pages</span></li>





                <!-- post -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.users.*') || Route::is('admin.admin-departments.*') ? 'active collapsed' : '' }}" href="#userManage" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarPages">
                        <i class="bi bi-people"></i> <span data-key="t-posts">User</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.users.*') || Route::is('admin.admin-departments.*') ? 'show' : '' }}" id="userManage">
                        <ul class="nav nav-sm flex-column">

                            <li class="nav-item">
                                <a href="{{ route('admin.users.index') }}" class="nav-link {{ Route::is('admin.users.*') ? 'active' : '' }}" data-key="t-users" wire:navigate> Users </a>
                            </li>
                            <!-- admin depar -->
                            <li class="nav-item">
                                <a href="{{ route('admin.admin-departments.index') }}" class="nav-link {{ Route::is('admin.admin-departments.*') ? 'active' : '' }}" data-key="t-departments" wire:navigate> Departments </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- testimonial -->
                <li class="nav-item">
                    <a class="nav-link menu-link " href="">
                        <i class="bi bi-chat-quote"></i> <span data-key="t-testimonials">Testimonials</span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>