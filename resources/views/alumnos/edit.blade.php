

@extends('layouts.app', ['modo'=>'Editando alumno'])

@section('content')
<div class="container">
    
    <form class="row" action="{{url('/alumnos/'.$alumno->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    {{method_field('PATCH')}}
    @include('alumnos.form', ['modo'=>'Editar'])
</form>
</div>
@endsection