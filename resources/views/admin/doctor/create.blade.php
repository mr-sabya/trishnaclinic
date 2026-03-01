@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Create doctor'" :breadcrumb="'Create doctor'" />

<livewire:admin.doctor.manage />
@endsection