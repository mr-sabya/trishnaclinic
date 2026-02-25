@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Tax Categories'" :breadcrumb="'Tax Categories'" />

<livewire:admin.charge.tax-category-index />
@endsection