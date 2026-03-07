@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Appointment'" :breadcrumb="'Appointment'" />

<livewire:admin.appointment.index />
@endsection