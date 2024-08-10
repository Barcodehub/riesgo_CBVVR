@extends('cliente.dashboard')

@section('content')

<div class="card m-4">
    <div class="card-header">
        <h4 class="card-title">Bienvenido {{ Auth::user()->nombre }}!</h4>
    </div>
        
</div>

@endsection
