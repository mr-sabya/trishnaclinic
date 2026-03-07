@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Edit appointment'" :breadcrumb="'Edit appointment'" />

<livewire:admin.appointment.manage id="{{ $appointment->id }}" />
@endsection