@extends('adminlayout.layout')

@section('content')
    @livewire('admin.coupons.coupons-edit', ['id' => $id])
@endsection