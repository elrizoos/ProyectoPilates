<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Rules\ValidacionRegistro;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datos['empleados']=Empleado::paginate(5);
        return view('empleados.index', $datos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('empleados.create');
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
            'telefono' => 'required|string|max:100',
            'email' => 'required|string|max:100',
            'fechaNacimiento' => 'required|string|max:100',
            'direccion' => 'required|string|max:100',
            'foto' => 'required|max:10000|mimes:jpeg,png,jpg'
        ];
        
        $mensaje=[
            'required'=>'El :attribute es obligatorio',
            'foto' => 'La foto es requerida'
        ];
        echo "hola";
        $this->validate($request,$campos, $mensaje);
        $datosEmpleado = request()->except('_token');
    /*
    *Comprobamos si existe un archivo en el formulario
    */

    if ($request->hasFile('foto')){
        $datosEmpleado['foto']=$request->file('foto')->store('uploads','public');
    }


        Empleado::insert($datosEmpleado);

       // return response()->json($datosEmpleado);
       return redirect('empleados')->with('mensaje', 'Empleado agregado con exito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Empleado $empleado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('empleados.edit', compact('empleado'));
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
        
        $datosEmpleado = request()->except(['_token','_method']);
        if ($request->hasFile('foto')) {
            $empleado = Empleado::findOrFail($id);
            Storage::delete('public/'.$empleado->foto);
            $datosEmpleado['foto'] = $request->file('foto')->store('uploads', 'public');
        }
        Empleado::where('id','=',$id)->update($datosEmpleado);

        $empleado=Empleado::findOrFail($id);
        return view('empleados.edit', compact('empleado'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $empleado=Empleado::findOrFail($id);

        if(Storage::delete('public/'.$empleado->foto)){
        Empleado::destroy($id);
        }
        return redirect('empleados')->with('mensaje', 'Empleado borrado correctamente');
    }
}