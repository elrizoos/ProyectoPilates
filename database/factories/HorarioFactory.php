<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Horario>
 */
class HorarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        do {
            $primerDia = $this->faker->dateTimeBetween($startDate = '-1 month', $endDate = 'now');
        } while ($primerDia->format('N') >= 6); // 6 y 7 representan sábado y domingo respectivamente

        return [
            'codigoClase' => $this->faker->numberBetween(1, 10),
            'codigoGrupo' => $this->faker->numberBetween(1, 10),
            'codigoEmpleado' => $this->faker->numberBetween(1, 10),
            'diaSemana' => $this->faker->randomElement(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes']),
            'horaInicio' => $this->faker->time,
            'horaFin' => $this->faker->time,
            'primerDia' => $primerDia,
            'repetir' => $this->faker->boolean,
            'repeticiones' => $this->faker->numberBetween(1,6),
        ];
    }
}
