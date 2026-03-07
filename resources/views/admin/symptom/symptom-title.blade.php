@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Symptom Title'" :breadcrumb="'Symptom Title'" />

<livewire:admin.symptom.symptom-title-index />
@endsection