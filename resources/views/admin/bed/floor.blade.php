@extends('admin.layouts.app')

@section('content')
<!-- page title -->
<livewire:admin.theme.page-title :title="'Hospital Floors'" :breadcrumb="'Bed Setup'" />

<livewire:admin.bed.floor-index />
@endsection