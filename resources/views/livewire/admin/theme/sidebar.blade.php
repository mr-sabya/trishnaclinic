<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ route('admin.home') }}" class="logo logo-dark">
            <span class="logo-sm"><img src="{{ url('assets/backend/images/logo-sm.png') }}" alt="" height="26"></span>
            <span class="logo-lg"><img src="{{ url('assets/backend/images/logo-dark.png') }}" alt="" height="26"></span>
        </a>
        <a href="{{ route('admin.home') }}" class="logo logo-light">
            <span class="logo-sm"><img src="{{ url('assets/backend/images/logo-sm.png') }}" alt="" height="26"></span>
            <span class="logo-lg"><img src="{{ url('assets/backend/images/logo-light.png') }}" alt="" height="26"></span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">

                <li class="menu-title"><span data-key="t-menu">Main</span></li>
                <li class="nav-item">
                    <a href="{{ route('admin.home') }}" class="nav-link menu-link {{ Route::is('admin.home') ? 'active' : '' }}" wire:navigate>
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                <!-- SECTION: CLINICAL & RECEPTION -->
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-clinical">Clinical & Reception</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.patient.*') ? 'active' : '' }}" href="{{ route('admin.patient.index') }}" wire:navigate>
                        <i class="ri-user-heart-line"></i> <span data-key="t-patient">Patient Master</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.appointment.*') ? 'active' : 'collapsed' }}" href="#appointmentManage" data-bs-toggle="collapse" role="button">
                        <i class="ri-calendar-check-line"></i> <span data-key="t-appointment">Appointments</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.appointment.*') ? 'show' : '' }}" id="appointmentManage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.appointment.index') }}" class="nav-link {{ Route::is('admin.appointment.index') ? 'active' : '' }}" wire:navigate>Appointment List</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.opd.*') ? 'active' : 'collapsed' }}" href="#opdManage" data-bs-toggle="collapse" role="button">
                        <i class="ri-stethoscope-line"></i> <span data-key="t-opd">OPD (Out-Patient)</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.opd.*') ? 'show' : '' }}" id="opdManage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="{{ route('admin.opd.index') }}" class="nav-link {{ Route::is('admin.opd.index') ? 'active' : '' }}" wire:navigate>OPD Patient List</a></li>
                            <li class="nav-item"><a href="{{ route('admin.opd.create') }}" class="nav-link {{ Route::is('admin.opd.create') ? 'active' : '' }}" wire:navigate>Admit Patient</a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.ipd.*') ? 'active' : 'collapsed' }}" href="#ipdManage" data-bs-toggle="collapse" role="button">
                        <i class="ri-hotel-bed-line"></i> <span data-key="t-ipd">IPD (In-Patient)</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.ipd.*') ? 'show' : '' }}" id="ipdManage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="{{ route('admin.ipd.index') }}" class="nav-link {{ Route::is('admin.ipd.index') ? 'active' : '' }}" wire:navigate>IPD Patient List</a></li>
                            <li class="nav-item"><a href="{{ route('admin.ipd.create') }}" class="nav-link {{ Route::is('admin.ipd.create') ? 'active' : '' }}" wire:navigate>Admit Patient</a></li>
                        </ul>
                    </div>
                </li>

                <!-- SECTION: DIAGNOSTICS -->
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-diagnostics">Diagnostics</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.pathology.*') ? 'active' : 'collapsed' }}" href="#pathologyManage" data-bs-toggle="collapse" role="button">
                        <i class="ri-flask-line"></i> <span data-key="t-pathology">Pathology</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.pathology.*') ? 'show' : '' }}" id="pathologyManage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="{{ route('admin.pathology.test') }}" class="nav-link {{ Route::is('admin.pathology.test') ? 'active' : '' }}" wire:navigate>Pathology Tests</a></li>
                            <li class="nav-item"><a href="{{ route('admin.pathology.parameter') }}" class="nav-link {{ Route::is('admin.pathology.parameter') ? 'active' : '' }}" wire:navigate>Parameters</a></li>
                            <li class="nav-item"><a href="{{ route('admin.pathology.category') }}" class="nav-link {{ Route::is('admin.pathology.category') ? 'active' : '' }}" wire:navigate>Categories</a></li>
                            <li class="nav-item"><a href="{{ route('admin.pathology.unit') }}" class="nav-link {{ Route::is('admin.pathology.unit') ? 'active' : '' }}" wire:navigate>Units</a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.radiology.*') ? 'active' : 'collapsed' }}" href="#radiologyManage" data-bs-toggle="collapse" role="button">
                        <i class="ri-pulse-line"></i> <span data-key="t-radiology">Radiology</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.radiology.*') ? 'show' : '' }}" id="radiologyManage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="{{ route('admin.radiology.test') }}" class="nav-link {{ Route::is('admin.radiology.test') ? 'active' : '' }}" wire:navigate>Radiology Tests</a></li>
                            <li class="nav-item"><a href="{{ route('admin.radiology.parameter') }}" class="nav-link {{ Route::is('admin.radiology.parameter') ? 'active' : '' }}" wire:navigate>Parameters</a></li>
                            <li class="nav-item"><a href="{{ route('admin.radiology.category') }}" class="nav-link {{ Route::is('admin.radiology.category') ? 'active' : '' }}" wire:navigate>Categories</a></li>
                            <li class="nav-item"><a href="{{ route('admin.radiology.unit') }}" class="nav-link {{ Route::is('admin.radiology.unit') ? 'active' : '' }}" wire:navigate>Units</a></li>
                        </ul>
                    </div>
                </li>

                <!-- SECTION: FINANCE & TPA -->
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-billing">Billing & Finance</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.charge.*') ? 'active' : 'collapsed' }}" href="#chargeManage" data-bs-toggle="collapse" role="button">
                        <i class="ri-money-dollar-circle-line"></i> <span data-key="t-charges">Charge Setup</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.charge.*') ? 'show' : '' }}" id="chargeManage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="{{ route('admin.charge.index') }}" class="nav-link {{ Route::is('admin.charge.index') ? 'active' : '' }}" wire:navigate>Charge Master</a></li>
                            <li class="nav-item"><a href="{{ route('admin.charge.charge-categories') }}" class="nav-link {{ Route::is('admin.charge.charge-categories') ? 'active' : '' }}" wire:navigate>Categories</a></li>
                            <li class="nav-item"><a href="{{ route('admin.charge.charge-types') }}" class="nav-link {{ Route::is('admin.charge.charge-types') ? 'active' : '' }}" wire:navigate>Charge Types</a></li>
                            <li class="nav-item"><a href="{{ route('admin.charge.unit') }}" class="nav-link {{ Route::is('admin.charge.unit') ? 'active' : '' }}" wire:navigate>Charge Units</a></li>
                            <li class="nav-item"><a href="{{ route('admin.charge.tax-categories') }}" class="nav-link {{ Route::is('admin.charge.tax-categories') ? 'active' : '' }}" wire:navigate>Tax Categories</a></li>
                            <li class="nav-item"><a href="{{ route('admin.charge.tpa-charges') }}" class="nav-link {{ Route::is('admin.charge.tpa-charges') ? 'active' : '' }}" wire:navigate>TPA Charges</a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.tpa.*') ? 'active' : '' }}" href="{{ route('admin.tpa.index') }}" wire:navigate>
                        <i class="ri-shield-cross-line"></i> <span data-key="t-tpa">TPA Management</span>
                    </a>
                </li>

                <!-- SECTION: HUMAN RESOURCES -->
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-hr">Human Resources</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.doctor.*') || Route::is('admin.medical-departments.*') || Route::is('admin.specialist.*') || Route::is('admin.doctor-schedules.*') || Route::is('admin.globalshift.*') ? 'active' : 'collapsed' }}" href="#doctorManage" data-bs-toggle="collapse" role="button">
                        <i class="ri-nurse-line"></i> <span data-key="t-doctors">Doctors & Schedules</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.doctor.*') || Route::is('admin.medical-departments.*') || Route::is('admin.specialist.*') || Route::is('admin.doctor-schedules.*') || Route::is('admin.globalshift.*') ? 'show' : '' }}" id="doctorManage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="{{ route('admin.doctor.index') }}" class="nav-link {{ Route::is('admin.doctor.index') ? 'active' : '' }}" wire:navigate>Doctor List</a></li>
                            <li class="nav-item"><a href="{{ route('admin.doctor-schedules.index') }}" class="nav-link {{ Route::is('admin.doctor-schedules.*') ? 'active' : '' }}" wire:navigate>Schedules</a></li>
                            <li class="nav-item"><a href="{{ route('admin.globalshift.index') }}" class="nav-link {{ Route::is('admin.globalshift.index') ? 'active' : '' }}" wire:navigate>Shifts Setup</a></li>
                            <li class="nav-item"><a href="{{ route('admin.medical-departments.index') }}" class="nav-link {{ Route::is('admin.medical-departments.index') ? 'active' : '' }}" wire:navigate>Medical Dept.</a></li>
                            <li class="nav-item"><a href="{{ route('admin.specialist.index') }}" class="nav-link {{ Route::is('admin.specialist.index') ? 'active' : '' }}" wire:navigate>Specialists</a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.users.*') || Route::is('admin.admin-departments.*') ? 'active' : 'collapsed' }}" href="#userManage" data-bs-toggle="collapse" role="button">
                        <i class="ri-team-line"></i> <span data-key="t-users">Staff Management</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.users.*') || Route::is('admin.admin-departments.*') ? 'show' : '' }}" id="userManage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link {{ Route::is('admin.users.*') ? 'active' : '' }}" wire:navigate>Staff List</a></li>
                            <li class="nav-item"><a href="{{ route('admin.admin-departments.index') }}" class="nav-link {{ Route::is('admin.admin-departments.*') ? 'active' : '' }}" wire:navigate>Administrative Dept.</a></li>
                        </ul>
                    </div>
                </li>

                <!-- SECTION: BED & WARD SETUP -->
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-inventory">Inventory & Beds</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.bed.*') ? 'active' : 'collapsed' }}" href="#bedManage" data-bs-toggle="collapse" role="button">
                        <i class="ri-hospital-line"></i> <span data-key="t-bed">Bed Management</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.bed.*') ? 'show' : '' }}" id="bedManage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="{{ route('admin.bed.index') }}" class="nav-link {{ Route::is('admin.bed.index') ? 'active' : '' }}" wire:navigate>Bed List</a></li>
                            <li class="nav-item"><a href="{{ route('admin.bed.group') }}" class="nav-link {{ Route::is('admin.bed.group') ? 'active' : '' }}" wire:navigate>Bed Groups (Wards)</a></li>
                            <li class="nav-item"><a href="{{ route('admin.bed.type') }}" class="nav-link {{ Route::is('admin.bed.type') ? 'active' : '' }}" wire:navigate>Bed Types</a></li>
                            <li class="nav-item"><a href="{{ route('admin.bed.floor') }}" class="nav-link {{ Route::is('admin.bed.floor') ? 'active' : '' }}" wire:navigate>Floor Setup</a></li>
                        </ul>
                    </div>
                </li>

                <!-- SECTION: SYSTEM MASTER -->
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-settings">Master Config</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.symptom-types.*') || Route::is('admin.symptom-titles.*') ? 'active' : 'collapsed' }}" href="#symptomManage" data-bs-toggle="collapse" role="button">
                        <i class="ri-mental-health-line"></i> <span data-key="t-symptoms">Symptom Setup</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.symptom-types.*') || Route::is('admin.symptom-titles.*') ? 'show' : '' }}" id="symptomManage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="{{ route('admin.symptom-types.index') }}" class="nav-link {{ Route::is('admin.symptom-types.index') ? 'active' : '' }}" wire:navigate>Symptom Types</a></li>
                            <li class="nav-item"><a href="{{ route('admin.symptom-titles.index') }}" class="nav-link {{ Route::is('admin.symptom-titles.index') ? 'active' : '' }}" wire:navigate>Symptom Titles</a></li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    </div>
    <div class="sidebar-background"></div>
</div>