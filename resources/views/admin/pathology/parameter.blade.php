@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Pathology parameter'" :breadcrumb="'Pathology parameter'" />

<livewire:admin.pathology.parameter-index />
@endsection