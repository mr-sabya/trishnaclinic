@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Create Patient'" :breadcrumb="'Create Patients'" />

<livewire:admin.patient.manage />
@endsection