<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\GrupoController;
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

Route::get('/', function () {
    return view('auth.login');
});

Route::get("/empleados", function() {
    return view('empleados.index');
});

Route::resource('empleados', EmpleadoController::class)->middleware('auth');
Auth::routes(['register'=>false, 'reset' => false]);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [EmpleadoController::class, 'index'])->name('home');
});
Route::group(['middleware' => 'auth'], function() {
    Route::get('/', [EmpleadoController::class, 'index'])->name('home');
});
Route::get("/alumnos", function() {
    return view('alumnos.index');
});
Route::resource('alumnos', AlumnoController::class)->middleware('auth');
Route::resource('grupos-clases', GrupoController::class)->middleware('auth');
Route::get('/grupos-clases/grupos/{grupo}/mostrar-alumnos', [GrupoController::class, 'mostrarAlumnos'])->name('grupos.mostrar-alumnos')->middleware('auth');

Route::get('/alumnos/{grupo}/mostrar-alumnos', [AlumnoController::class, 'mostrarAlumnos'])->name('alumnos.mostrar-alumnos')->middleware('auth');
Route::get("/grupos-clases/grupos/{grupo}/asignar-alumnos", [GrupoController::class, 'asignarAlumnos'])->name('grupos.asignar-alumnos')->middleware('auth');



Route::resource('grupos-clases/clases', AlumnoController::class);