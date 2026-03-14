@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Pathology Units'" :breadcrumb="'Pathology Units'" />

<livewire:admin.pathology.unit-index />
@endsection