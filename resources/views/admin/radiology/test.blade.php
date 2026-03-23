@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Radiology test'" :breadcrumb="'Rathology test'" />

<livewire:admin.radiology.test-index />
@endsection