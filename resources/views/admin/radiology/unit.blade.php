@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Radiology Units'" :breadcrumb="'Radiology Units'" />

<livewire:admin.radiology.unit-index />
@endsection