@extends('admin.layouts.app')

@section('content')
<!-- page title -->
<livewire:admin.theme.page-title :title="'Specialist'" :breadcrumb="'Specialist'" />

<livewire:admin.specialist.index />
@endsection