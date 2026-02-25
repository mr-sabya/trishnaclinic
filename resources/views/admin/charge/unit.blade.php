@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Charge Units'" :breadcrumb="'Charge Units'" />

<livewire:admin.charge.unit-index />
@endsection