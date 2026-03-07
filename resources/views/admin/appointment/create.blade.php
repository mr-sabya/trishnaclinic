@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Create appointment'" :breadcrumb="'Create appointment'" />

<livewire:admin.appointment.manage />
@endsection