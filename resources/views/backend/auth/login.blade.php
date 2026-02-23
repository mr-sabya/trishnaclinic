@extends('backend.layouts.guest')

@section('content')
<section class="auth-page-wrapper-2 py-4 position-relative d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="container">
        <div class="row g-0 align-items-center justify-content-center">

            <div class="col-lg-7">
                <div class="card mb-0 rounded-0 rounded-end border-0">
                    <div class="card-body p-4 p-sm-5 m-lg-4">
                        <div class="text-center mt-2">
                            <!-- logo -->
                            <div class="avatar-md mx-auto">
                                <div class="avatar-title bg-light rounded-circle text-primary h1">
                                    <img src="{{ asset('assets/backend/images/logo.svg') }}" alt="" class="rounded-circle" height="34">
                                </div>
                            </div>
                            <h5 class="text-primary fs-22">Welcome Back !</h5>
                            <p class="text-muted">Sign in to continue to Trisha Clinic.</p>
                        </div>
                        <livewire:backend.auth.login />
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>
        </div>
    </div>
</section>
@endsection