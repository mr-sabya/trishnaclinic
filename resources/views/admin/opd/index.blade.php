@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'OPD Patient'" :breadcrumb="'OPD Patient'" />

<livewire:admin.opd.opd-index />
@endsection