<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    @extends('layouts.app', ['modo' => 'Horarios'])
    @section('content')
        <div class="container">

            <form action="{{ url('/horarios/') }}" method="GET">
                <label for="semana">Selecciona la semana:</label>
                <input type="week" name="semana" value="">
                <button type="submit">Mostrar</button>
            </form>
            <table class="table text-center align-middle table-striped-columns table-responsive fs-6"
                style="position: absolute;left:5%; width:90%">
                <thead class="thead-light">
                    <tr>
                        <th>Dia</th>
                        <th>Horas</th>
                        <th>Lunes</th>
                        <th>Martes</th>
                        <th>Miercoles</th>
                        <th>Jueves</th>
                        <th>Viernes</th>
                        <th>Sábado</th>
                        <th>Domingo</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($horarios as $horario)
                        <tr>
                            <td>{{ $horario->primerDia }}</td>
                            <td>{{ $horario->horaInicio }}/{{ $horario->horaFin }}</td>

                            @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                                <td>
                                    @if ($horario->diaSemana === $dia)
                                        {{ $horario->clase ? $horario->clase->nombre : 'Clase no encontrada' }}
                                    @else
                                        <!-- celda vacía -->
                                        x
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                    </tr>
                </tfoot>
                {{ $horarios->links('pagination::bootstrap-4') }}
            </table>
        </div>
    @endsection
</body>

</html>
