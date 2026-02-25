@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Charge Types'" :breadcrumb="'Charge Types'" />

<livewire:admin.charge.charge-type-index />
@endsection