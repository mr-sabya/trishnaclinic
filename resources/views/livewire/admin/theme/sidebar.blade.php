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

                <!-- tpa -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.tpa.*') ? 'active' : '' }}" href="{{ route('admin.tpa.index') }}" wire:navigate>
                        <i class="bi bi-credit-card"></i> <span data-key="t-tpa">TPA</span>
                    </a>
                </li>

                <!-- patient -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.patient.*') ? 'active' : '' }}" href="{{ route('admin.patient.index') }}" wire:navigate>
                        <i class="bi bi-person"></i> <span data-key="t-patient">Patient</span>
                    </a>
                </li>

                <!-- Billing & Charges Link -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.charge.*') ? 'active' : 'collapsed' }}" href="#chargeManage" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ Route::is('admin.charge.*') ? 'true' : 'false' }}" aria-controls="chargeManage">
                        <i class="bi bi-wallet2"></i> <span data-key="t-charges">Billing & Charges</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.charge.*') ? 'show' : '' }}" id="chargeManage">
                        <ul class="nav nav-sm flex-column">

                            <li class="nav-item">
                                <a href="{{ route('admin.charge.index') }}" class="nav-link {{ Route::is('admin.charge.index') ? 'active' : '' }}" wire:navigate>
                                    Charge Master
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.charge.charge-types') }}" class="nav-link {{ Route::is('admin.charge.charge-types') ? 'active' : '' }}" wire:navigate>
                                    Charge Types
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.charge.charge-categories') }}" class="nav-link {{ Route::is('admin.charge.charge-categories') ? 'active' : '' }}" wire:navigate>
                                    Categories
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.charge.unit') }}" class="nav-link {{ Route::is('admin.charge.unit') ? 'active' : '' }}" wire:navigate>
                                    Units
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.charge.tax-categories') }}" class="nav-link {{ Route::is('admin.charge.tax-categories') ? 'active' : '' }}" wire:navigate>
                                    Tax Categories
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.charge.tpa-charges') }}" class="nav-link {{ Route::is('admin.charge.tpa-charges') ? 'active' : '' }}" wire:navigate>
                                    TPA Price List
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>


                <!-- Doctor Management -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.doctor.*') || Route::is('admin.medical-departments.*') || Route::is('admin.specialist.*') ? 'active' : 'collapsed' }}" href="#doctorManage" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ Route::is('admin.doctor.*') || Route::is('admin.medical-departments.*') || Route::is('admin.specialist.*') ? 'true' : 'false' }}" aria-controls="doctorManage">
                        <i class="bi bi-person-badge"></i> <span data-key="t-doctors">Doctor Management</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.doctor.*') || Route::is('admin.medical-departments.*') || Route::is('admin.specialist.*') ? 'show' : '' }}" id="doctorManage">
                        <ul class="nav nav-sm flex-column">

                            <li class="nav-item">
                                <a href="{{ route('admin.doctor.index') }}" class="nav-link {{ Route::is('admin.doctor.index') || Route::is('admin.doctor.create') || Route::is('admin.doctor.edit') ? 'active' : '' }}" wire:navigate>
                                    Doctor List
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.medical-departments.index') }}" class="nav-link {{ Route::is('admin.medical-departments.index') ? 'active' : '' }}" wire:navigate>
                                    Medical Departments
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.specialist.index') }}" class="nav-link {{ Route::is('admin.specialist.index') ? 'active' : '' }}" wire:navigate>
                                    Specialist
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

                <!-- Appointment & Schedule Management -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.globalshift.*') || Route::is('admin.doctor-schedules.*') ? 'active' : 'collapsed' }}" href="#scheduleManage" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ Route::is('admin.globalshift.*') || Route::is('admin.doctor-schedules.*') ? 'true' : 'false' }}" aria-controls="scheduleManage">
                        <i class="bi bi-calendar3"></i> <span data-key="t-schedules">Doctor Schedule</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.globalshift.*') || Route::is('admin.doctor-schedules.*') ? 'show' : '' }}" id="scheduleManage">
                        <ul class="nav nav-sm flex-column">

                            <!-- Global Shift -->
                            <li class="nav-item">
                                <a href="{{ route('admin.globalshift.index') }}" class="nav-link {{ Route::is('admin.globalshift.index') ? 'active' : '' }}" wire:navigate>
                                    Global Shift
                                </a>
                            </li>

                            <!-- Doctor Schedules -->
                            <li class="nav-item">
                                <a href="{{ route('admin.doctor-schedules.index') }}" class="nav-link {{ Route::is('admin.doctor-schedules.*') ? 'active' : '' }}" wire:navigate>
                                    Doctor Schedule List
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>