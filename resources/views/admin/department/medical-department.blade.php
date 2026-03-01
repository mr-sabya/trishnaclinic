@extends('admin.layouts.app')

@section('content')
<!-- page title -->
<livewire:admin.theme.page-title :title="'Medical Departments'" :breadcrumb="'Medical Departments'" />

<livewire:admin.department.medical-department-index />
@endsection