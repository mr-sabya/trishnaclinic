@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Doctor Schedule'" :breadcrumb="'Doctor Schedule'" />

<livewire:admin.doctor-schedule.index />
@endsection