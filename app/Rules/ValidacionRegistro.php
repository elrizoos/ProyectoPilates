<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class ValidacionRegistro implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    private $horaInicio;
    private $horaFin;
    public function __construct($horaInicio, $horaFin)
    {
        $this->horaInicio = $horaInicio;
        $this->horaFin = $horaFin;
    }

    public function validate(string $attribute, $value, Closure $fail): void
    {
        if (
            DB::table('horarios')
                ->where('primerDia', request('primerDia'))
                ->where('horaInicio', request('horaInicio'))
                ->where('horaFin', request('horaFin'))
                ->exists()
        ) {

            $fail("Ya existe un registro con el mismo primer dia y tramo horario");
            
        }

        
    }

    public function message()
    {
        return "Ya existe un registro con el mismo primer dia y tramo horario";
    }
}
