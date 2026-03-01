@extends('admin.layouts.app')

@section('content')
<!-- page title -->
<livewire:admin.theme.page-title :title="'TPA Management'" :breadcrumb="'TPA Management'" />

<livewire:admin.tpa.index />
@endsection