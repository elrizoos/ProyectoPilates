<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $fillable = ['codigoClase', 'codigoEmpleado', 'codigoGrupo', 'diaSemana', 'horaInicio', 'horaFin', 'primerDia', 'repetir', 'repeticiones'];

    public function obtenerCodigoClase()
    {
        return $this->belongsTo(Clase::class, 'nombre'); // Asegúrate de que 'codigoClase' sea la columna correcta para la relación.
    }

    public function obtenerCodigoEmpleado()
    {
        return $this->belongsTo(Empleado::class, 'nombre'); // Asegúrate de que 'codigoEmpleado' sea la columna correcta para la relación.
    }
    public function obtenerNombreClase()
    {
        echo "hola";
    }

    public function clase()
    {
        return $this->belongsTo('App\Models\Clase', 'codigoClase', 'id');
    }

}
