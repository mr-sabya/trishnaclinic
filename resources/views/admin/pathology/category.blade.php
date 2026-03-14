@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Pathology category'" :breadcrumb="'Pathology category'" />

<livewire:admin.pathology.category-index />
@endsection