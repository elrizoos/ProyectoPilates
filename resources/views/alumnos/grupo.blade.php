<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    @extends('layouts.app', ['modo' => 'Ver participantes'])
    @section('content')
        <div class="container">
            @if (Session::has('mensaje'))
                {{ Session::get('mensaje') }}
            @endif
            <h1>Participantes -- Grupo {{ $grupo }}</h1>

            <br><br>
            <div class="row">
                <div class="col col-12">

                    <table class="table text-center align-middle table-striped-columns table-responsive fs-6"
                        style="left:5%; width:100%">
                        <thead class="table-light ">
                            <th>Foto</th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>DNI</th>
                            <th>Telefono</th>
                            <th>Email</th>
                            <th>FechaNacimiento</th>
                            <th>Direccion</th>
                            <th>CodigoGrupo</th>
                            <th>Opciones</th>
                        </thead>
                        <tbody>
                            @foreach ($alumnos as $alumno)
                                <tr>
                                    <td>
                                        <img class="img-thumbnail img-fluid" width="70px" height="100px"
                                            src="{{ asset('storage') . '/' . $alumno->foto }}" alt="Imagen del usuario">
                                    </td>
                                    <td>{{ $alumno->id }}</td>
                                    <td>{{ $alumno->nombre }}</td>
                                    <td>{{ $alumno->apellidos }}</td>
                                    <td>{{ $alumno->dni }}</td>
                                    <td>{{ $alumno->telefono }}</td>
                                    <td>{{ $alumno->email }}</td>
                                    <td>{{ $alumno->fechaNacimiento }}</td>
                                    <td>{{ $alumno->direccion }}</td>
                                    <td>{{ $alumno->codigoGrupo }}</td>
                                    <td><a href=" {{ url('/alumnos/' . $alumno->id . '/edit') }}"
                                            class="btn btn-warning d-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path
                                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                <path fill-rule="evenodd"
                                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                            </svg></a>

                                        <form action="{{ url('/alumnos/' . $alumno->id) }}" method="post"
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
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col col-12">
                    <h2>Agregar Participantes</h2>
                    <form action="{{ route('grupos.asignar-alumnos', ['grupo' => $grupo]) }}" method="GET">
                        @csrf
                        <div class="form-group">
                            <label for="alumnos">Selecciona los participantes:</label>
                            <div class="row">
                                @foreach ($alumnosFuera as $alumno)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="alumno_{{ $alumno->id }}"
                                                name="alumnos-seleccionados[]" value="{{ $alumno->id }}">
                                            <label class="form-check-label"
                                                for="alumno_{{ $alumno->id }}">{{ $alumno->nombre }}
                                                {{ $alumno->apellidos }} ---- Dni: {{ $alumno->dni }} ---- Grupo: {{$alumno->codigoGrupo}} </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar Participantes</button>
                    </form>
                </div>
            </div>
        </div>
    @endsection
</body>

</html>
