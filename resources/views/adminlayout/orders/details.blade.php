@extends('adminlayout.layout')

@section('content')
    {{-- @dump($id) --}}
    @livewire('admin.orders.order-details', ['id' => $id])
@endsection
