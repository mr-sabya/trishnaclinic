@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Radiology parameter'" :breadcrumb="'Radiology parameter'" />

<livewire:admin.radiology.parameter-index />
@endsection