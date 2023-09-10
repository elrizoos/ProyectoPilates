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

        $tramos = [
            ['10:00', '11:20'],
            ['11:30', '12:50'],
            ['13:00', '14:20'],
            ['15:00', '16:20'],
            ['16:30', '17:50'],
            ['18:00', '19:20'],
            ['19:30', '20:50']
        ];

        $tramoAleatorio = $this->faker->randomElement($tramos);

        return [
            'codigoClase' => $this->faker->numberBetween(1, 10),
            'codigoGrupo' => $this->faker->numberBetween(1, 10),
            'codigoEmpleado' => $this->faker->numberBetween(1, 10),
            'diaSemana' => $this->faker->randomElement(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes']),
            'horaInicio' => $tramoAleatorio[0],
            'horaFin' => $tramoAleatorio[1],
            'primerDia' => $primerDia,
            'repetir' => $this->faker->boolean,
            'repeticiones' => $this->faker->numberBetween(1, 6),
        ];


    }
}
