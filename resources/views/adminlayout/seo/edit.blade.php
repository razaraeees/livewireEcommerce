
@extends('adminlayout.layout')
@section('content')
    @livewire('admin.seo.seo-edit', ['id' => $id])
@endsection