@extends('admin.layouts.app')

@section('content')
<!-- page title -->
<livewire:admin.theme.page-title :title="'Global Shift'" :breadcrumb="'Global Shift'" />

<livewire:admin.global-shift.index />
@endsection