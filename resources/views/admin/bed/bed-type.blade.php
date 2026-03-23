@extends('admin.layouts.app')

@section('content')
<!-- page title -->
<livewire:admin.theme.page-title :title="'Bed Types'" :breadcrumb="'Bed Setup'" />

<livewire:admin.bed.bed-type-index />
@endsection