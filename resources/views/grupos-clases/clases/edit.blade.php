

@extends('layouts.app', ['modo'=>'Editando grupo'])

@section('content')
<div class="container">
    
    <form class="row" action="{{url('/grupos-clases/clases/'.$clase->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    {{method_field('PATCH')}}
    @include('grupos-clases.clases.form', ['modo'=>'Editar'])
</form>
</div>
@endsection