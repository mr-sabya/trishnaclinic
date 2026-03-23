@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Edit IPD Patient'" :breadcrumb="'Edit IPD Patient'" />

<livewire:admin.ipd.ipd-manage id="{{ $ipd->id }}" />
@endsection