@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Edit Patient'" :breadcrumb="'Edit Patients'" />

<livewire:admin.patient.manage id="{{ $patient->id }}" />
@endsection