@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Edit OPD Patient'" :breadcrumb="'Edit OPD Patient'" />

<livewire:admin.opd.opd-manage id="{{ $opd->id }}" />
@endsection