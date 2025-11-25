@extends('adminlayout.layout')

@section('content')
    {{-- <div class="alert alert-warning">
        <strong>Debug:</strong> ID = {{ $id ?? 'NOT SET' }}
    </div> --}}
    
    @if(isset($id))
        @livewire('admin.banner.banner-edit', ['id' => $id])
    @else
        <div class="alert alert-danger">ID missing from view!</div>
    @endif
@endsection