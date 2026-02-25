@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Charge Categories'" :breadcrumb="'Charge Categories'" />

<livewire:admin.charge.charge-category-index />
@endsection