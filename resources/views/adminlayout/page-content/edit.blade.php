@extends('adminlayout.layout')

@section('content')
    @livewire('admin.page-content.page-edit', ['slug' => $slug])
@endsection