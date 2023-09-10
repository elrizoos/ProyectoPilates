@extends('layouts.app', ['modo' => 'Editando horario'])

@section('content')
    <div class="container">

        <form class="row" action="{{ url('/horarios/' . $horario->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{ method_field('PATCH') }}
            @include('horarios.form', ['modo' => 'Editar'])
        </form>
        <form action="{{ route('horarios.destroy', $horario->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Borrar registro</button>
        </form>

    </div>
@endsection
