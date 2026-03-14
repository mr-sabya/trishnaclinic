@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Pathology test'" :breadcrumb="'Pathology test'" />

<livewire:admin.pathology.test-index />
@endsection