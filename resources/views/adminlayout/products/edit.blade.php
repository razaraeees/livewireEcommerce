@extends('adminlayout.layout')

@section('content')
    @livewire('admin.products.products-edit', [ 'slug' => $slug])
@endsection