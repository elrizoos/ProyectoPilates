<!DOCTYPE html>
<html lang="en">
<?php
use Carbon\Carbon;

?>

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
            <a href="{{ url('horarios/create') }}">Crear nuevo registro</a>
            <form action="{{ url('/horarios/') }}" method="GET">
                <label for="semana">Selecciona la semana:</label>
                <input type="week" name="semana">
                <button type="submit">Mostrar</button>
            </form>
            @if (isset($currentDate))
                <div class="navigation-buttons">
                    <a href="{{ url('/horarios?date=' .$currentDate->copy()->subWeek()->toDateString()) }}">Semana
                        Anterior</a>
                    <span>Semana del {{ $currentDate->startOfWeek()->toFormattedDateString() }} al
                        {{ $currentDate->endOfWeek()->toFormattedDateString() }}</span>
                    <a href="{{ url('/horarios?date=' .$currentDate->copy()->addWeek()->toDateString()) }}">Semana
                        Posterior</a>
                </div>
            @endif
            <h3>Semana seleccionada</h3>
            <table class="table text-center align-middle table-striped-columns table-responsive fs-6"
                style="left:5%; width:100%">
                <thead>
                    <tr>
                        <th>Tramos Horarios</th>
                        @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $index => $dia)
                            <th>
                                {{ $dia }}<br>
                                {{ $currentDate->startOfWeek()->addDays($index)->format('Y-m-d') }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach (['10:00 --- 11:20', '11:30 --- 12:50', '13:00 --- 14:20', '15:00 --- 16:20', '16:30 --- 17:50', '18:00 --- 19:20', '19:30 --- 20:50'] as $tramo)
                        <tr>
                            <td>{{ $tramo }}</td>
                            @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $indexDia => $dia)
                                <td>
                                    @php
                                        $fechaDia = $currentDate
                                            ->copy()
                                            ->startOfWeek()
                                            ->addDays($indexDia)
                                            ->format('Y-m-d');
                                        $matchedHorario = $horarios->first(function ($horario) use ($dia, $tramo) {
                                            return $horario->diaSemana == $dia && substr($horario->horaInicio, 0, 5) . ' --- ' . substr($horario->horaFin, 0, 5) == $tramo;
                                        });
                                    @endphp

                                    @if ($matchedHorario)
                                    <?php
                                        $idHorario = $matchedHorario->id
                                    ?>
                                        <a href="{{ url('horarios/edit', ['dia' => $dia,'tramo' => $tramo,'fecha' => $fechaDia, 'id' => $idHorario])}}">
                                        {{ $matchedHorario->clase->nombre }}
                                        </a>
                                    @else
                                        <a href="{{ url('horarios/create', ['dia' => $dia,'tramo' => $tramo,'fecha' => $fechaDia]) }}">Crear</a>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach

                </tbody>


            </table>{{ $horarios }}
        </div>
    @endsection
</body>





</html>
