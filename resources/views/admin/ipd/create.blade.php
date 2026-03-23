@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Add IPD Patient'" :breadcrumb="'Add IPD Patient'" />

<livewire:admin.ipd.ipd-manage />
@endsection