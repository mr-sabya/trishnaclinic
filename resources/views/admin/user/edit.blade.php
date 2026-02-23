@extends('admin.layouts.app')

@section('content')
<livewire:admin.user.manage userId="{{ $user->id }}" />
@endsection