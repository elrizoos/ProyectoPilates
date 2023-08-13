<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Http\Controllers\GrupoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datos['alumnos'] = Alumno::paginate(5);
        return view('alumnos.index', $datos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('alumnos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $campos = [
            'nombre' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'dni' => 'required|string|max:100',
            'email' => 'required|email',
            'fechaNacimiento' => 'required|string|max:100',
            'foto' => 'required|max:10000|mimes:jpeg,png,jpg'
        ];

        $mensaje = [
            'required' => 'El :attribute es obligatorio',
            'foto' => 'La foto es requerida'
        ];
        echo "hola";
        $this->validate($request, $campos, $mensaje);
        $datosalumno = request()->except('_token');
        /*
         *Comprobamos si existe un archivo en el formulario
         */

        if ($request->hasFile('foto')) {
            $datosalumno['foto'] = $request->file('foto')->store('uploads', 'public');
        }


        Alumno::insert($datosalumno);

        // return response()->json($datosalumno);
        return redirect('alumnos')->with('mensaje', 'alumno agregado con exito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alumno $alumno)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $alumno = Alumno::findOrFail($id);
        return view('alumnos.edit', compact('alumno'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $campos = [
            'nombre' => 'required|string|max:100',
            'apellidoPrimero' => 'required|string|max:100',
            'apellidoSegundo' => 'required|string|max:100',
            'dni' => 'required|string|max:100',
            'email' => 'required|email',
            'fechaNacimiento' => 'required|string|max:100',
            'calle' => 'required|string|max:100',
            'numero' => 'required|string|max:100',
            'bloque' => 'required|string|max:100',
            'piso' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'provincia' => 'required|string|max:100'
        ];

        $mensaje = [
            'required' => 'El :attribute es obligatorio'
        ];

        if ($request->hasFile('foto')) {
            $campos = ['foto' > 'required|max:10000|mimes:jpeg,png,jpg'];
            $mensaje = ['foto.required' => 'La foto es requerida'];
        }


        $this->validate($request, $campos, $mensaje);

        $datosalumno = request()->except(['_token', '_method']);
        if ($request->hasFile('foto')) {
            $alumno = alumno::findOrFail($id);
            Storage::delete('public/' . $alumno->foto);
            $datosalumno['foto'] = $request->file('foto')->store('uploads', 'public');
        }
        Alumno::where('id', '=', $id)->update($datosalumno);

        $alumno = Alumno::findOrFail($id);
        return view('alumnos.edit', compact('alumno'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $alumno = Alumno::findOrFail($id);

        if (Storage::delete('public/' . $alumno->foto)) {
            Alumno::destroy($id);
        }
        return redirect('alumnos')->with('mensaje', 'alumno borrado correctamente');
    }

    /** 
     * Mostrar alumnos correspondientes a una clase
     */

     public function mostrarAlumnos($grupo){

        $alumnosClase = Alumno::where('codigoGrupo', $grupo)->get();
        $alumnosFuera = Alumno::whereNOTIn('codigoGrupo', [$grupo])->get();
        return view('alumnos.grupo',['alumnos'=> $alumnosClase, 'modo' => 'ver', 'grupo' => $grupo, 'alumnosFuera' => $alumnosFuera]);
     }
}