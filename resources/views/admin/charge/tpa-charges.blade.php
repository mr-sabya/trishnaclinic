@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'TPA Charges'" :breadcrumb="'TPA Charges'" />

<livewire:admin.charge.tpa-charge-index />
@endsection