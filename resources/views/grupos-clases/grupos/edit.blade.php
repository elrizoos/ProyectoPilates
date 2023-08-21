

@extends('layouts.app', ['modo'=>'Editando grupo'])

@section('content')
<div class="container">
    
    <form class="row" action="{{url('/grupos-clases/grupos/'.$grupo->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    {{method_field('PATCH')}}
    @include('grupos-clases.grupos.form', ['modo'=>'Editar'])
</form>
</div>
@endsection