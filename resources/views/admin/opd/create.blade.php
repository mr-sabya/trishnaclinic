@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Add OPD Patient'" :breadcrumb="'Add OPD Patient'" />

<livewire:admin.opd.opd-manage />
@endsection