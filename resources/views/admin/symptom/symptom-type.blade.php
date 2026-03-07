@extends('admin.layouts.app')

@section('content')

<!-- page title -->
<livewire:admin.theme.page-title :title="'Symptom Type'" :breadcrumb="'Symptom Type'" />

<livewire:admin.symptom.symptom-type-index />
@endsection