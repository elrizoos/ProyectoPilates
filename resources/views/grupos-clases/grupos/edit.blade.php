

@extends('layouts.app', ['modo'=>'Editando grupo'])

@section('content')
<div class="container">
    
    <form class="row" action="{{url('/grupos/'.$grupo->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    {{method_field('PATCH')}}
    @include('grupos.form', ['modo'=>'Editar'])
</form>
</div>
@endsection