<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    use HasFactory;
    public function horarios()
    {
        return $this->hasMany('App\Models\Horario', 'codigoClase', 'id');
    }

}
