<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\GrupoController;

use App\Http\Controllers\ClaseController;
use App\Http\Controllers\HorarioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Para que si ponemos la ruta principal haya que iniciar sesion en la pagina
Route::get('/', function () {
    return view('auth.login');
});


//Cuando escribamos en la url empleados nos redirige a la vista index de empleados
Route::get("/empleados", function() {
    return view('empleados.index');
});


//Obtenemos automaticamente las rutas asociadas a empleados
Route::resource('empleados', EmpleadoController::class)->middleware('auth');

//Hacemos que desaparezca el boton de registro de usuario y de restablecer la contraseña
Auth::routes(['register'=>true, 'reset' => false]);


//Hacemos que se redirija al index global cuando se inicie sesion
Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', function(){
        return view('index');
    })->name('home');
});
Route::group(['middleware' => 'auth'], function() {
    Route::get('/', function () {
        return view('index');
    })->name('home');
});

Route::resource('alumnos', AlumnoController::class)->middleware('auth');

Route::resource('grupos-clases/clases', ClaseController::class)->middleware('auth');

Route::resource('grupos-clases/grupos', GrupoController::class)->middleware('auth');

Route::resource('grupos-clases', GrupoController::class)->middleware('auth');

Route::resource('horarios', HorarioController::class)->middleware('auth');

Route::get('/grupos-clases/grupos/{grupo}/mostrar-alumnos', [GrupoController::class, 'mostrarAlumnos'])->name('grupos.mostrar-alumnos')->middleware('auth');

Route::get('/alumnos/{grupo}/mostrar-alumnos', [AlumnoController::class, 'mostrarAlumnos'])->name('alumnos.mostrar-alumnos')->middleware('auth');

Route::get("/grupos-clases/grupos/{grupo}/asignar-alumnos", [GrupoController::class, 'asignarAlumnos'])->name('grupos.asignar-alumnos')->middleware('auth');



//Asignando ruta para la busqueda de alumno
Route::get("/alumnos/search", function() {
    return view('alumnos.search');
})->name('alumnos.search');

//Para acceder a la funcion asociada a buscar el alumno
/** Route::get('/alumnos/searchAlumno', [AlumnoController::class, 'buscarAlumno']);


//Para asociar todas las rutas de alumnos
Route::resource('alumnos', AlumnoController::class)->middleware('auth');


//Para asociar todas las rutas de grupos

Route::get('/grupos-clases/grupos/{grupo}/mostrar-alumnos', [GrupoController::class, 'mostrarAlumnos'])->name('grupos.mostrar-alumnos')->middleware('auth');
Route::get('/alumnos/{grupo}/mostrar-alumnos', [AlumnoController::class, 'mostrarAlumnos'])->name('alumnos.mostrar-alumnos')->middleware('auth');
Route::get("/grupos-clases/grupos/{grupo}/asignar-alumnos", [GrupoController::class, 'asignarAlumnos'])->name('grupos.asignar-alumnos')->middleware('auth');
Route::get("/grupos-clases/grupos/{grupo}/edit", [GrupoController::class, 'edit'])->name('grupos.edit')->middleware('auth');



Route::resource('grupos-clases/clases', AlumnoController::class);

//Redireccionar a la vista de horarios
Route::get('/horarios', function() {
    return view('horarios');
});*/