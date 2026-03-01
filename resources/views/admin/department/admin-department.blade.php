@extends('admin.layouts.app')

@section('content')
<!-- page title -->
<livewire:admin.theme.page-title :title="'Admin Departments'" :breadcrumb="'Admin Departments'" />

<livewire:admin.department.admin-department-index />
@endsection