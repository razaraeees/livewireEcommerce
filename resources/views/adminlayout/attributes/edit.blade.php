@extends('adminlayout.layout')

@section('content')
    @livewire('admin.attributes.attributes-edit',  ['slug' => $slug])
@endsection