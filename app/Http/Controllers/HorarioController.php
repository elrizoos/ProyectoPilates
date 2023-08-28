<?php

namespace App\Http\Controllers;

use App\Models\Horario;
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
            $horarios = Horario::with('clase')->whereBetween('primerDia', [$startOfWeek, $endOfWeek])->get();
        } else {
            $horarios = Horario::all(); // o cualquier lógica predeterminada que desees
        }

        return view('horarios.index', compact('horarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
