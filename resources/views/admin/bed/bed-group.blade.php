@extends('admin.layouts.app')

@section('content')
<!-- page title -->
<livewire:admin.theme.page-title :title="'Bed Groups / Wards'" :breadcrumb="'Bed Setup'" />

<livewire:admin.bed.bed-group-index />
@endsection