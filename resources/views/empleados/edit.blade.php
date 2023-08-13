

@extends('layouts.app', ['modo'=>'Editando empleado'])

@section('content')
<div class="container">
    
    <form class="row" action="{{url('/empleados/'.$empleado->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    {{method_field('PATCH')}}
    @include('empleados.form', ['modo'=>'Editar'])
</form>
</div>
@endsection