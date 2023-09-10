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
        //dump(request()->all());
        try {
            //VALIDACION DE DATOS ENVIADOS

            //Tramos horarios existentes, futuro configurables
            $tramos = [
                ['10:00', '11:20'],
                ['11:30', '12:50'],
                ['13:00', '14:20'],
                ['15:00', '16:20'],
                ['16:30', '17:50'],
                ['18:00', '19:20'],
                ['19:30', '20:50']
            ];

            //Convierto el array a String separado por ' --- '
            $tramoString = array_map(function ($tramo) {
                return implode(' --- ', $tramo);
            }, $tramos);

            //Requisitos de los campos del formulario
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

            //Mensaje en caso de error de verificacion
            $mensaje = [
                'required' => 'El :attribute es obligatorio',

            ];

            //Validacion del tramo horario enviado
            if (strpos(request()->tramoHorario, ' --- ') === false) {
                return back()->with('error', 'El tramo horario es inválido.');
            }

            //Validacion de los datos con sus requisitos y sus mensajes de validacion
            $this->validate($request, $campos, $mensaje);


            //Arrays necesarios
            $daysConversion = [
                'Lunes' => 'Monday',
                'Martes' => 'Tuesday',
                'Miércoles' => 'Wednesday',
                'Jueves' => 'Thursday',
                'Viernes' => 'Friday',
                'Sábado' => 'Saturday',
                'Domingo' => 'Sunday'
            ];

            $diasEspañol = [
                'Monday' => 'Lunes',
                'Tuesday' => 'Martes',
                'Wednesday' => 'Miércoles',
                'Thursday' => 'Jueves',
                'Friday' => 'Viernes',
                'Saturday' => 'Sábado',
                'Sunday' => 'Domingo'
            ];

            $daysOfWeek = [
                'Monday' => 0,
                'Tuesday' => 1,
                'Wednesday' => 2,
                'Thursday' => 3,
                'Friday' => 4,
                'Saturday' => 5,
                'Sunday' => 6,
            ];
            //Informacion del tramo horario
            $tramo = explode(' --- ', request()->tramoHorario);

            //Asociando cada parte a su hora correspondiente
            $horaInicio = $tramo[0];
            $horaFin = $tramo[1];

            // Datos básicos del horario sin incluir los días, token, ni repetición.
            $datosHorarioBase = request()->except('_token', 'tramoHorario', 'diaSemana', 'repetir', 'repeticiones');

            //Establece las horas de inicio y fin obtenidas
            $datosHorarioBase['horaInicio'] = $horaInicio;
            $datosHorarioBase['horaFin'] = $horaFin;


            // Días seleccionados y número de repeticiones.
            $diasSeleccionados = $request->input('diaSemana');
            //dump($diasSeleccionados);
            $repeticiones = $request->input('repeticiones');

            //El numero de registros que se va a guardar en la base de datos
            $numeroRegistros = $repeticiones + 1;



            $fecha = new DateTime($request->input('primerDia')); //Obtener la fecha seleccionada como primer dia
            $fechaInicio = clone $fecha; //Clona la variable para no perder su valor en el futuro
            $fechaDia = $fechaInicio->format('N'); //Devuelve en forma numerica Lunes:1 Domingo:7

            $primerDia = $fecha->modify('-' . ($fechaDia - 1) . ' days'); //Obtiene el valor del primer dia de la semana
            $primerDia = $primerDia->format('Y-m-d'); //Pasa a formato fecha
            //dump($fechaInicio);
            $primerDiaSemana = new DateTime($primerDia); //Creamos el valor de primer dia de la semana






            //Obtiene el dia correspondiente a esa fecha
            $diaIngresado = $fechaInicio->format('l');
            //debug
            //dump($diaIngresado);

            //Convierte los dias seleccionados (dados en español) al ingles
            $diasSeleccionadosInEnglish = array_map(function ($dia) use ($daysConversion) {
                return $daysConversion[$dia];
            }, $diasSeleccionados);
            //Valida que la fecha sea uno de los dias seleccionados
            if (!in_array($diaIngresado, $diasSeleccionadosInEnglish)) {
                return back()->with('error', 'La fecha seleccionada no coincide con ninguno de los días seleccionados.');
            }

            //dump($diaIngresado !== $diasSeleccionadosInEnglish[0]);
            //Comprobamos si el primer dia seleccionado no coincide con la fecha dada
            if ($diaIngresado !== $diasSeleccionadosInEnglish[0]) {
                $datosHorario = $datosHorarioBase; //Asigna los datos base a la variable nueva
                $cumplido = false; //variable para el flujo de la funcion

                foreach ($diasSeleccionadosInEnglish as $dia) {
                    $repeticiones = $request->input('repeticiones');
                    $numeroRegistros = $repeticiones + 1;
                    //dump($repeticiones, $numeroRegistros);
                    //dump($dia);
                    //Creamos array para cada dia semanal
                    //Averiguamos el dia del foreach para asignar al nombre del array
                    $arrayNombre = "array" . $dia;
                    //dump($arrayNombre);
                    $$arrayNombre = [];
                    $numeroDia = $daysOfWeek[$dia];
                    ////dump($numeroDia);
                    $diaUnoSemana = clone $primerDiaSemana; //Clona para no perder el valor
                    $fechaDiaCorrespondiente = $diaUnoSemana->modify('+' . $numeroDia . ' days');
                    //dump($diaUnoSemana);

                    //dump($fechaDiaCorrespondiente);
                    //si el dia no conincide con la fecha y aun no se ha cumplido
                    if ($dia != $diaIngresado && !$cumplido) {
                        $cloneFechaCorrespondiente = clone $fechaDiaCorrespondiente;
                        //dump($cloneFechaCorrespondiente);
                        $fechaSiguiente = $cloneFechaCorrespondiente->modify('+1 week');
                        //dump($fechaSiguiente);

                        array_push($$arrayNombre, clone $fechaSiguiente); // Clonamos el objeto antes de añadirlo al array
                        //dump($$arrayNombre);

                        while ($repeticiones > 0) {
                            $fechaSiguiente = $fechaSiguiente->modify('+1 week');
                            //dump($fechaSiguiente);
                            array_push($$arrayNombre, clone $fechaSiguiente); // Clonamos el objeto antes de añadirlo al array
                            //dump($$arrayNombre);
                            $repeticiones--;
                        }

                        /**
                         * do {

                            $fechaSemanaSiguiente = $cloneFechaCorrespondiente->modify('+1 week');
                            array_push($$arrayNombre, $fechaSemanaSiguiente);
                            $repeticiones--;
                        } while ($repeticiones > 0);
                         */
                        //dump($$arrayNombre);
                        for ($i = 0; $i < $numeroRegistros; $i++) {
                            //dump($$arrayNombre[$i]);
                            $datosHorario['primerDia'] = $$arrayNombre[$i]->format('Y-m-d');
                            $datosHorario['diaSemana'] = $diasEspañol[$dia];
                            $datosHorario['horaInicio'] = $horaInicio;
                            $datosHorario['horaFin'] = $horaFin;
                            Horario::insert($datosHorario);
                        }

                    } else {
                        $cloneFechaCorrespondiente = clone $fechaDiaCorrespondiente;
                        //dump($cloneFechaCorrespondiente);
                        $fechaSiguiente = $cloneFechaCorrespondiente;
                        //dump($fechaSiguiente);

                        array_push($$arrayNombre, clone $fechaSiguiente); // Clonamos el objeto antes de añadirlo al array
                        //dump($$arrayNombre);

                        while ($repeticiones > 0) {
                            $fechaSiguiente = $fechaSiguiente->modify('+1 week');
                            //dump($fechaSiguiente);
                            array_push($$arrayNombre, clone $fechaSiguiente); // Clonamos el objeto antes de añadirlo al array
                            //dump($$arrayNombre);
                            $repeticiones--;
                        }


                        //Insertamos registros en la base de datos


                        for ($i = 0; $i < $numeroRegistros; $i++) {
                            //dump($$arrayNombre);
                            $datosHorario['primerDia'] = $$arrayNombre[$i]->format('Y-m-d');
                            $datosHorario['diaSemana'] = $diasEspañol[$dia];
                            $datosHorario['horaInicio'] = $horaInicio;
                            $datosHorario['horaFin'] = $horaFin;
                            Horario::insert($datosHorario);
                        }
                        $cumplido = true;
                    }
                    ////dump($datosHorario);
                }
                //dd("hola");


            } else {
                // Para cada día seleccionado.
                foreach ($diasSeleccionados as $dia) {
                    $repeticiones = $request->input('repeticiones');
                    $diaInEnglish = $daysConversion[$dia];
                    //dump($diaInEnglish);
                    // Establecer la fecha de inicio basado en el día seleccionado.
                    $fechaInicio = new DateTime($request->input('primerDia'));
                    //dump($fechaInicio);
                    // Este bucle asegura que la fecha de inicio coincide con el primer día de la semana seleccionado.
                    while ($fechaInicio->format('l') != $diaInEnglish) {
                        $fechaInicio->modify('+1 day');
                    }
                    //dump($fechaInicio);
                    // Si se desea repetir, se guardan múltiples registros, si no, solo uno.

                    do {
                        $datosHorario = $datosHorarioBase;
                        $datosHorario['primerDia'] = $fechaInicio->format('Y-m-d');
                        //dump($dia);
                        $datosHorario['diaSemana'] = $dia;

                        // Aquí nos aseguramos de que las horas se redefinan correctamente.
                        $datosHorario['horaInicio'] = $horaInicio;
                        $datosHorario['horaFin'] = $horaFin;

                        Horario::insert($datosHorario);

                        $fechaInicio->modify('+7 day');

                        $repeticiones--;
                    } while ($repeticiones >= 0);



                }

            }
            //dd("hola");
            return redirect('horarios')->with('mensaje', 'El registro del horario de la clase ha sido agregado con éxito');



        } catch (\Exception $e) {
            echo "mensaje de error: " . $e->getMessage() . ' ' . $e->getLine();

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