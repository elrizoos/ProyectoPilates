<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Clase;
use App\Models\Empleado;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PhpParser\Comment;
use DateTime;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Carbon::setWeekStartsAt(Carbon::MONDAY);
        Carbon::setWeekEndsAt(Carbon::SUNDAY);

        $semana = $request->get('semana');

        if ($semana) {
            // Extraer el número de semana y el año del valor enviado
            preg_match("/(\d+)-W(\d+)/", $semana, $matches);

            $year = $matches[1] ?? null;
            $weekNumber = $matches[2] ?? null;

            // Usar Carbon para determinar la fecha de inicio y fin de la semana
            $startOfWeek = Carbon::parse("{$year}-W{$weekNumber}-1"); // Lunes de esa semana
            $endOfWeek = Carbon::parse("{$year}-W{$weekNumber}-7"); // Domingo de esa semana

            // Filtrar tus datos basados en la fecha de inicio y fin de la semana
            $horarios = Horario::with('clase')
                ->whereBetween('primerDia', [$startOfWeek, $endOfWeek])
                ->orderBy('primerDia', 'asc')
                ->orderBy('horaInicio', 'asc')
                ->get();

        } else {
            //Esto nos permite recibir una fecha de la semana seleccionada
            $date = $request->input('date', Carbon::now()->toDateString());
            $currentDate = Carbon::parse($date);

            $startOfWeek = $currentDate->startOfWeek();
            $endOfWeek = $currentDate->endOfWeek();

            $horarios = Horario::whereBetween('primerDia', [$startOfWeek, $endOfWeek])->get();

            return view('horarios.index', [
                'horarios' => $horarios,
                'currentDate' => $currentDate,
            ]);
        }

        return view('horarios.index', compact('horarios'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clases = Clase::all();
        $empleados = Empleado::all();
        $grupos = Grupo::all();
        return view('horarios.create', compact('clases', 'empleados', 'grupos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            echo 'tramo: ' . request()->tramoHorario;

            /** Tramos horarios establecidos:
                Tramo 1: 10:00 --- 11:20
                Tramo 2: 11:30 --- 12:50
                Tramo 3: 13:00 --- 14:20
                Tramo 4: 15:00 --- 16:20
                Tramo 5: 16:30 --- 17:50
                Tramo 6: 18:00 --- 19:20
                Tramo 7: 19:30 --- 20:50

         */
            $tramosHorarios = [
                '10:00',
                '11:30',
                '13:00',
                '15:00',
                '16:30',
                '18:00',
                '19:30',
            ];
            $tramosHorarios2 = [
                '11:20',
                '12:50',
                '14:20',
                '16:20',
                '17:50',
                '19:20',
                '20:50',
            ];


            $campos = [
                'codigoClase' => 'required|integer|max:3',
                'codigoEmpleado' => 'required|integer|max:3',
                'codigoGrupo' => 'required|integer|max:3',
                'diaSemana.*' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes',
                'horaInicio' => 'required|in:' . implode(',', $tramosHorarios),
                'horaFin' => 'required|in:' . implode(',', $tramosHorarios2),
                'primerDia' => 'required|date',
                'repetir' => 'required|boolean',
                'repeticiones' => 'required|integer',
            ];

            $mensaje = [
                'required' => 'El :attribute es obligatorio',

            ];

            if (strpos(request()->tramoHorario, ' --- ') === false) {
                return back()->with('error', 'El tramo horario es inválido.');
            }

            $tramo = explode(' --- ', request()->tramoHorario);
            $horaInicio = $tramo[0];
            $horaFin = $tramo[1];

            $this->validate($request, $campos, $mensaje);
            // Datos básicos del horario sin incluir los días, token, ni repetición.
            $datosHorarioBase = request()->except('_token', 'tramoHorario', 'diaSemana', 'repetir', 'repeticiones');
            $datosHorarioBase['horaInicio'] = $horaInicio;
            $datosHorarioBase['horaFin'] = $horaFin;
            // Días seleccionados y número de repeticiones.
            $diasSeleccionados = $request->input('diaSemana');
            $repeticiones = $request->input('repeticiones') ?? 1; // Por defecto será 1 si no se especifica repeticiones.

            // Para cada día seleccionado.
            foreach ($diasSeleccionados as $dia) {
                $daysConversion = [
                    'Lunes' => 'Monday',
                    'Martes' => 'Tuesday',
                    'Miércoles' => 'Wednesday',
                    'Jueves' => 'Thursday',
                    'Viernes' => 'Friday',
                    'Sábado' => 'Saturday',
                    'Domingo' => 'Sunday'
                ];

                $diaInEnglish = $daysConversion[$dia];
                // Establecer la fecha de inicio basado en el día seleccionado.
                $fechaInicio = new DateTime($request->input('primerDia'));

                // Este bucle asegura que la fecha de inicio coincide con el primer día de la semana seleccionado.
                while ($fechaInicio->format('l') != $diaInEnglish) {
                    $fechaInicio->modify('+1 day');
                }

                // Si se desea repetir, se guardan múltiples registros, si no, solo uno.

                for ($i = 0; $i <= $repeticiones; $i++) {
                    $datosHorario = $datosHorarioBase;
                    $datosHorario['primerDia'] = $fechaInicio->format('Y-m-d');
                    $datosHorario['diaSemana'] = $dia;

                    // Aquí nos aseguramos de que las horas se redefinan correctamente.
                    $datosHorario['horaInicio'] = $horaInicio;
                    $datosHorario['horaFin'] = $horaFin;

                    Horario::insert($datosHorario);

                    $fechaInicio->modify('+7 day');
                }


            }
            return redirect('horarios')->with('mensaje', 'El registro del horario de la clase ha sido agregado con éxito');

        } catch (\Exception $e) {
            echo "mensaje de error: " . $e->getMessage();
           
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Horario $horario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Horario $horario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Horario $horario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horario $horario)
    {
        //
    }
}