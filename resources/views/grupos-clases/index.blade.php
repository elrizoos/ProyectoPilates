<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    @extends('layouts.app', ['modo' => 'Grupos y Clases'])
    @section('content')
    
        <div class="container">
            
            @if (Session::has('mensaje'))
                {{ Session::get('mensaje') }}
            @endif
            <div class="row">
                <div class="col col-12">
                        <h3>Grupos</h3>
                        <a href="{{ url('grupos-clases/grupos/create') }}" class="btn btn-success">Crear nuevo grupo</a>
                        <br><br>
                        <table class="table text-center align-middle table-striped-columns table-responsive fs-6"
                            style="left:5%; width:100%">
                            <thead class="table-light ">

                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Máximo Participantes</th>
                                <th>Clase</th>
                                <th>Opciones</th>
                            </thead>
                            <tbody>
                                @foreach ($grupos as $grupo)
                                    <tr>

                                        <td>{{ $grupo->id }}</td>
                                        <td>{{ $grupo->nombre }}</td>
                                        <td>{{ $grupo->maxParticipantes }}</td>
                                        <td>{{ $grupo->codigoClase }}</td>

                                        <td><a href=" {{ url('/grupos-clases/grupos/' . $grupo->id . '/edit') }}"
                                                class="btn btn-warning d-inline-block">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path
                                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd"
                                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                                </svg></a>

                                            <form action="{{ url('/grupos-clases/grupos/' . $grupo->id) }}" method="post"
                                                class="d-inline-block">
                                                @csrf
                                                {{ method_field('DELETE') }}
                                                <input type="submit" value="X" class="btn btn-danger"
                                                    onclick="return confirm('¿Quieres borrar el empleado?')">
                                            </form>
                                            <a href="{{ route('grupos.mostrar-alumnos', [$grupo->id]) }}">Ver
                                                participantes</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            {{ $grupos->appends(['page-grupos' => $grupos->currentPage()])->links('pagination::bootstrap-4') }}
                        </table>
                </div>
            </div>
            <div class="row">
                <div class="col col-12">
                    <h3>Clases</h3>
                    <a href="{{ url('grupos-clases/clases/create') }}" class="btn btn-success">Crear nueva clase</a>
                    <br><br>
                    <table class="table text-center align-middle table-striped-columns table-responsive fs-6"
                        style="position: absolute;left:5%; width:90%">
                        <thead class="table-light ">

                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Nivel</th>
                            
                            <th>Opciones</th>
                        </thead>
                        <tbody>
                            @foreach ($clases as $clase)
                                <tr>

                                    <td>{{ $clase->id }}</td>
                                    <td>{{ $clase->nombre }}</td>
                                    <td>{{ $clase->nivel }}</td>


                                    <td><a href=" {{ url('/grupos-clases/clases/' . $clase->id . '/edit') }}"
                                            class="btn btn-warning d-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path
                                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                <path fill-rule="evenodd"
                                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                            </svg></a>

                                        <form action="{{ url('/grupos-clases/clases/' . $clase->id) }}" method="post"
                                            class="d-inline-block">
                                            @csrf
                                            {{ method_field('DELETE') }}
                                            <input type="submit" value="X" class="btn btn-danger"
                                                onclick="return confirm('¿Quieres borrar el empleado?')">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        {{ $clases->appends(['page-clases' => $clases->currentPage()])->links('pagination::bootstrap-4') }}
                    </table>
                </div>
            </div>
        </div>
    @endsection
</body>

</html>
