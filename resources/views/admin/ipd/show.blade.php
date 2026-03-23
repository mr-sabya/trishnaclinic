@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'IPD Patient'" :breadcrumb="'IPD Patient'" />

<livewire:admin.ipd.ipd-show id="{{ $opd->id }}" />
@endsection