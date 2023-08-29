<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Clase;

class ClaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageClases = request('page-clases', 1);
        $pageGrupos = request('page-grupos', 1);

        $datos['grupos'] = Grupo::paginate(5, ['*'], 'page-grupos', $pageGrupos)->withQueryString();
        $datos['clases'] = Clase::paginate(5, ['*'], 'page-clases', $pageClases)->withQueryString();
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
        $datosClase = request()->except('_token');
        Clase::insert($datosClase);
        return redirect('grupos-clases/clases')->with('mensaje', 'clase agregada con exito');
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
        $clase = Clase::findOrFail($id);
        return view('grupos-clases.clases.edit', compact('clase'));    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $campos = [
            'nombre' => 'required|string|max:100',
            'nivel' => 'required|integer|max:2'

        ];

        $mensaje = ['required' => 'El :attribute es obligatorio'];

        $this->validate($request, $campos, $mensaje);

        $datosClase = request()->except(['_token', '_method']);

        Clase::where('id','=', $id)->update($datosClase);

        $clase = Clase::findOrFail($id);
        return view('grupos-clases.clases.edit', compact('clase'));
    }

    /**
     * Remove the specified resource from storage.
     */ 
    public function destroy($id)
    {
        Clase::destroy($id);
        return redirect('grupos-clases/clases')->with('mensaje', 'clase borrada correctamente');
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