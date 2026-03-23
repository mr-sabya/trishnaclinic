@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Radiology category'" :breadcrumb="'Radiology category'" />

<livewire:admin.radiology.category-index />
@endsection