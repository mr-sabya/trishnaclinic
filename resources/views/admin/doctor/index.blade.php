@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Doctors'" :breadcrumb="'Doctors'" />

<livewire:admin.doctor.index />
@endsection