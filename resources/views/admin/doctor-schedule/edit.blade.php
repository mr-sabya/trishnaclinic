@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Edit Doctor Schedule'" :breadcrumb="'Edit Doctor Schedule'" />

<livewire:admin.doctor-schedules.manage id="{{ $patient->id }}" />
@endsection