@extends('admin.layouts.app')

@section('content')
<!-- page title -->
<livewire:admin.theme.page-title :title="'Bed List'" :breadcrumb="'Bed Management'" />

<livewire:admin.bed.bed-index />
@endsection