@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Hospital Overview</h2>
            <p class="text-muted">Welcome back, Admin. Here is what's happening today.</p>
        </div>
        <div>
            <button class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-2"></i>New Appointment
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary-soft p-3 rounded-3">
                            <i class="fas fa-user-injured fa-2x text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Patients</h6>
                            <h3 class="mb-0 fw-bold">1,284</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success-soft p-3 rounded-3">
                            <i class="fas fa-bed fa-2x text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Available Beds</h6>
                            <h3 class="mb-0 fw-bold">42 / 150</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info-soft p-3 rounded-3">
                            <i class="fas fa-calendar-check fa-2x text-info"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Today's Appts</h6>
                            <h3 class="mb-0 fw-bold">28</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning-soft p-3 rounded-3">
                            <i class="fas fa-dollar-sign fa-2x text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Revenue (MTD)</h6>
                            <h3 class="mb-0 fw-bold">$12,450</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Patient Statistics Chart -->
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Patient Admissions Trend</h5>
                    <select class="form-select form-select-sm w-auto">
                        <option>Last 7 Days</option>
                        <option>Last 30 Days</option>
                    </select>
                </div>
                <div class="card-body">
                    <!-- Placeholder for Chart (e.g. ApexCharts or Chart.js) -->
                    <div id="patientChart" style="min-height: 300px;" class="bg-light rounded d-flex align-items-center justify-content-center text-muted">
                        Chart Loading...
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Distribution -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Patients by Dept.</h5>
                </div>
                <div class="card-body pt-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center border-0 py-3">
                            <span><i class="fas fa-heart text-danger me-2"></i> Cardiology</span>
                            <span class="badge bg-primary rounded-pill">35%</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center border-0 py-3">
                            <span><i class="fas fa-brain text-info me-2"></i> Neurology</span>
                            <span class="badge bg-primary rounded-pill">20%</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center border-0 py-3">
                            <span><i class="fas fa-baby text-warning me-2"></i> Pediatrics</span>
                            <span class="badge bg-primary rounded-pill">25%</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center border-0 py-3">
                            <span><i class="fas fa-x-ray text-secondary me-2"></i> Radiology</span>
                            <span class="badge bg-primary rounded-pill">20%</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Appointments Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Recent Appointments</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4">Patient</th>
                                <th class="border-0">Doctor</th>
                                <th class="border-0">Service</th>
                                <th class="border-0">Time</th>
                                <th class="border-0 text-center">Status</th>
                                <th class="border-0 text-end px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary rounded-circle me-2" style="width: 32px; height: 32px;"></div>
                                        <div>
                                            <div class="fw-bold">John Doe</div>
                                            <small class="text-muted">#P-00124</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Dr. Sarah Smith</td>
                                <td>Checkup</td>
                                <td>09:30 AM</td>
                                <td class="text-center"><span class="badge bg-success-soft text-success px-3">Confirmed</span></td>
                                <td class="text-end px-4">
                                    <button class="btn btn-sm btn-light border"><i class="fas fa-eye"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary rounded-circle me-2" style="width: 32px; height: 32px;"></div>
                                        <div>
                                            <div class="fw-bold">Jane Roe</div>
                                            <small class="text-muted">#P-00125</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Dr. Michael Lee</td>
                                <td>Cardiology</td>
                                <td>11:15 AM</td>
                                <td class="text-center"><span class="badge bg-warning-soft text-warning px-3">Pending</span></td>
                                <td class="text-end px-4">
                                    <button class="btn btn-sm btn-light border"><i class="fas fa-eye"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Medical Color Palette */
    .bg-primary-soft {
        background-color: #e8f1ff;
    }

    .bg-success-soft {
        background-color: #e7f7ed;
    }

    .bg-info-soft {
        background-color: #e0f7fa;
    }

    .bg-warning-soft {
        background-color: #fff9e6;
    }

    .card {
        transition: transform 0.2s ease;
        border-radius: 12px;
    }

    .card:hover {
        transform: translateY(-3px);
    }

    .text-primary {
        color: #0d6efd !important;
    }

    .text-success {
        color: #198754 !important;
    }

    .text-info {
        color: #0dcaf0 !important;
    }

    .text-warning {
        color: #ffc107 !important;
    }

    .badge.bg-success-soft {
        color: #198754;
        background: #d1e7dd;
    }

    .badge.bg-warning-soft {
        color: #856404;
        background: #fff3cd;
    }
</style>
@endsection