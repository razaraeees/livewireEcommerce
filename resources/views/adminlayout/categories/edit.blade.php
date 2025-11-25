@extends('adminlayout.layout')

@section('content')
     @livewire('admin.categories.categories-edit', ['url' => $url])
@endsection