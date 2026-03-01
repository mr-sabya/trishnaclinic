@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Create Doctor Schedule'" :breadcrumb="'Create Doctor Schedule'" />

<livewire:admin.doctor-schedule.manage />
@endsection