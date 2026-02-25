@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Charge'" :breadcrumb="'Charge'" />

<livewire:admin.charge.charge-index />
@endsection