@extends('adminlayout.layout')

@section('content')
    @livewire('admin.variants.variants-edit', [ 'slug' => $slug])
@endsection