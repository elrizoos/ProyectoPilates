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

        //Recogemos el valor del input de la semana escogida
        $semana = $request->input('semana');
        $currentDate = Carbon::parse($semana);
        if ($semana) {

            // Para encontrar el inicio de la semana (Lunes)
            $startOfWeek = date('Y-m-d', strtotime('monday this week', strtotime($semana)));
            $endOfWeek = date('Y-m-d', strtotime('sunday this week', strtotime($semana)));

            //Debug
            //echo "Inicio de la semana: $startOfWeek";
            //echo "Fin de la semana: $endOfWeek";

            $horarios = Horario::with('clase')
                ->whereBetween('primerDia', [$startOfWeek, $endOfWeek])
                ->orderBy('primerDia', 'asc')
                ->orderBy('horaInicio', 'asc')
                ->get();

            //dd($horarios);
            return view('horarios.index', [
                'horarios' => $horarios,
                'currentDate' => $currentDate,
            ]);
        } else {
            // Tomamos la fecha del request o la fecha actual si no se provee ninguna
            $date = $request->input('date', Carbon::now()->toDateString());
            $currentDate = Carbon::parse($date);

            $startOfWeek = clone $currentDate;
            $startOfWeek->startOfWeek();
            $endOfWeek = clone $currentDate;
            $endOfWeek->endOfWeek();

            // Verifica las fechas antes de hacer la consulta
            //dd($currentDate->toDateString(), $startOfWeek->toDateString(), $endOfWeek->toDateString());

            $horarios = Horario::with('clase')
                ->whereBetween('primerDia', [$startOfWeek, $endOfWeek])
                ->orderBy('primerDia', 'asc')
                ->orderBy('horaInicio', 'asc')
                ->get();

            return view('horarios.index', [
                'horarios' => $horarios,
                'currentDate' => $currentDate,
            ]);
        }


    }





    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clases = Clase::all();
        $empleados = Empleado::all();
        $grupos = Grupo::all();
        return view('horarios.create', compact('clases', 'empleados', 'grupos', ));
    }


    public function createPredefinido($dia, $tramo, $fecha)
    {
        //Decodificar la variable tramo enviada por url
        $tramo = urldecode($tramo);

        //Agrupar las variables en un array
        $datos = [
            'dia' => $dia,
            'tramo' => $tramo,
            'fecha' => $fecha
        ];


        $clases = Clase::all();
        $empleados = Empleado::all();
        $grupos = Grupo::all();
        return view('horarios.create', compact('clases', 'empleados', 'grupos', 'datos'));
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
            /*$tramosHorarios = [
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
*/

            $tramos = [
                ['10:00', '11:20'],
                ['11:30', '12:50'],
                ['13:00', '14:20'],
                ['15:00', '16:20'],
                ['16:30', '17:50'],
                ['18:00', '19:20'],
                ['19:30', '20:50']
            ];

            //Convertimos el array a String
            $tramoString = array_map(function ($tramo) {
                return implode(' --- ', $tramo);
            }, $tramos);


            $campos = [
                'codigoClase' => 'required|integer',
                'codigoEmpleado' => 'required|integer',
                'codigoGrupo' => 'required|integer',
                'diaSemana.*' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes',
                'tramoHorario' => 'required|in:' . implode(',', $tramoString),
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
    public function edit($dia, $tramo, $fecha, $id)
    {
        //Decodificar la variable tramo enviada por url
        $tramo = urldecode($tramo);

        //Agrupar las variables en un array
        $datos = [
            'dia' => $dia,
            'tramo' => $tramo,
            'fecha' => $fecha
        ];

        //Buscamos el horarios en la BD
        $horario = Horario::findOrFail($id);

        $clases = Clase::all();
        $empleados = Empleado::all();
        $grupos = Grupo::all();
        return view('horarios.edit', compact('clases', 'empleados', 'grupos', 'datos', 'horario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tramos = [
            ['10:00', '11:20'],
            ['11:30', '12:50'],
            ['13:00', '14:20'],
            ['15:00', '16:20'],
            ['16:30', '17:50'],
            ['18:00', '19:20'],
            ['19:30', '20:50']
        ];

        //Convertimos el array a String
        $tramoString = array_map(function ($tramo) {
            return implode(' --- ', $tramo);
        }, $tramos);

        $campos = [
            'codigoClase' => 'required|integer',
            'codigoEmpleado' => 'required|integer',
            'codigoGrupo' => 'required|integer',
            'diaSemana.*' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes',
            'tramoHorario' => 'required|in:' . implode(',', $tramoString),
            'primerDia' => 'required|date',
            'repetir' => 'required|boolean',
            'repeticiones' => 'required|integer',
        ];

        $mensaje = ['required' => 'El :attribute es obligatorio'];
        if (strpos(request()->tramoHorario, ' --- ') === false) {
            return back()->with('error', 'El tramo horario es inválido.');
        }

        $tramo = explode(' --- ', request()->tramoHorario);
        $horaInicio = $tramo[0];
        $horaFin = $tramo[1];

        $this->validate($request, $campos, $mensaje);
        // Datos básicos del horario sin incluir los días, token, ni repetición.
        $datosHorarioBase = request()->except('_method', '_token', 'tramoHorario');
        $datosHorarioBase['horaInicio'] = $horaInicio;
        $datosHorarioBase['horaFin'] = $horaFin;






        Horario::where('id', '=', $id)->update($datosHorarioBase);

        $horario = Horario::findOrFail($id);

        $dia = $horario->diaSemana;
        $tramo = $horario->horaInicio . ' --- ' . $horario->horaFin;
        $fecha = $horario->primerDia;

        $datos = [
            'dia' => $dia,
            'tramo' => $tramo,
            'fecha' => $fecha
        ];
        $clases = Clase::all();
        $empleados = Empleado::all();
        $grupos = Grupo::all();
        return view('horarios.edit', compact('clases', 'empleados', 'datos', 'grupos', 'horario'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Horario::destroy($id);

        return redirect('horarios')->with('mensaje', 'horario borrado correctamente');
    }
}