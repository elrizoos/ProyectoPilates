<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Clase;
use App\Models\Empleado;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        echo $semana;

        if ($semana) {
            // Extraer el número de semana y el año del valor enviado
            preg_match("/(\d+)-W(\d+)/", $semana, $matches);

            $year = $matches[1] ?? null;
            $weekNumber = $matches[2] ?? null;
            echo "<br>" . $weekNumber;
            echo "<br>" . $year;
            // Usar Carbon para determinar la fecha de inicio y fin de la semana

            $startOfWeek = Carbon::parse("{$year}-W{$weekNumber}-1"); // Lunes de esa semana
            $endOfWeek = Carbon::parse("{$year}-W{$weekNumber}-7"); // Domingo de esa semana



            echo "<br>" . $startOfWeek;
            echo "<br>" . $endOfWeek;
            // Filtrar tus datos basados en la fecha de inicio y fin de la semana
            $horarios = Horario::with('clase')->whereBetween('primerDia', [$startOfWeek, $endOfWeek])->orderBy('primerDia', 'asc')->orderBy('horaInicio', 'asc')->paginate(10)->withQueryString();
        } else {
            $horarios = Horario::orderBy('primerDia', 'asc')->orderBy('horaInicio', 'asc')->paginate(10); // o cualquier lógica predeterminada que desees
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
        $campos = [
            'codigoClase' => 'required|integer|max:3',
            'codigoEmpleado' => 'required|integer|max:3',
            'codigoGrupo' => 'required|integer|max:3',
            'diaSemana' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes',
            'horaInicio' => 'required|date_format:H:i',
            'horaFin' => 'required|date_format:H:i',
            'primerDia' =>  'required|date',
            'repetir' => 'required|boolean',
        ];

        $mensaje = [
            'required' => 'El :attribute es obligatorio',
            
        ];

        $this->validate($request, $campos, $mensaje);
        $datosHorario = request()->except('_token');

        Horario::insert($datosHorario);

        return redirect('horarios')->with('mensaje', 'El registro del horario de la clase ha sido agregado con éxito');
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
