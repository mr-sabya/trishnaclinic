@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Patients'" :breadcrumb="'Patients'" />

<livewire:admin.patient.index />
@endsection