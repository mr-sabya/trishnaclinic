@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Edit doctor'" :breadcrumb="'Edit doctor'" />

<livewire:admin.doctor.manage doctorId="{{ $doctor->id }}" />
@endsection