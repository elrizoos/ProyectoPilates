<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alumno>
 */
class AlumnoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name,
            'apellidos' => $this->faker->lastName,
            'dni' => $this->faker->unique()->numerify('########A'),
            'telefono' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'fechaNacimiento' => $this->faker->dateTime,
            'direccion' => $this->faker->address,
            'codigoGrupo' => '1',
            'foto' => $this->faker->imageUrl(640, 480, 'people'),
        ];
    }
}
