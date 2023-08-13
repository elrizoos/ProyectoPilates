<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Clase;

class GrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datos['grupos'] = Grupo::paginate(5);
        $datos['clases'] = Clase::paginate(5);
        return view('grupos-clases.index', $datos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('grupos-clases.clases.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datosGrupo = request()->except('_token');
        Grupo::insert($datosGrupo);
    }

    /**
     * Display the specified resource.
     */
    public function show(Grupo $grupo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $grupo = Grupo::findOrFail($id);
        return view('grupos-clases.clases.edit', compact('grupo'));    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grupo $grupo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */ 
    public function destroy(Grupo $grupo)
    {
        //
    }

    /**
     * Mostrar los alumnos de la clase
     */
    
    public function mostrarAlumnos( $grupo){
        $clase = 1;
        $url =  route('alumnos.mostrar-alumnos',['clase' => $clase, 'grupo' => $grupo]);
        return redirect($url);
    }
 
    /**
     * Asignar alumno a la lista de alumnos de la clase
     */
    public function asignarAlumnos(Request $request, Grupo $grupo){
        //Recoger la lista de alumnos seleccionados para asignar al grupo correspondiente
        $alumnosSeleccionados = $request->input('alumnos-seleccionados',[]);
        //Actualizar el grupo de cada alumno con el nuevo grupo
        Alumno::whereIn('id', $alumnosSeleccionados)->update(['codigoGrupo' => $grupo->id]);
         $alumnosGrupo = Alumno::whereIn('codigoGrupo', [$grupo->id])->get();

        $alumnosFuera = Alumno::whereNOTIn('codigoGrupo', [$grupo->id])->get();

        //Actualizar la tabla de participantes del grupo y la lista de participantes disponibles para añadir
       
        return view('alumnos.grupo', ['grupo' => $grupo->id, 'alumnos' => $alumnosGrupo, 'alumnosFuera' => $alumnosFuera]);


    }
}