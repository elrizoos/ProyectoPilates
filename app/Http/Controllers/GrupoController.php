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
        return view('grupos-clases.grupos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datosGrupo = request()->except('_token');
        Grupo::insert($datosGrupo);
        return redirect('grupos-clases/grupos')->with('mensaje', 'grupo agregado con exito');
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
        return view('grupos-clases.grupos.edit', compact('grupo'));    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $campos = [
            'nombre' => 'required|string|max:100', 
            'maxParticipantes' => 'required|string|max:3',
            'codigoClase' => 'required|integer|max:10',
        ];

        $mensaje = [
            'required' => 'El :attribute es obligatorio'
        ];

        $this->validate($request, $campos, $mensaje);

        $datosGrupo = request()->except(['_token', '_method']);

        Grupo::where('id','=',$id)->update($datosGrupo);

        $grupo = Grupo::findOrFail($id);
        return view('grupos-clases.grupos.edit', compact('grupo'));
    }

    /**
     * Remove the specified resource from storage.
     */ 
    public function destroy($id)
    {
        Grupo::destroy($id);
        return redirect('grupos-clases/grupos')->with('mensaje', 'grupo borrado correctamente');
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