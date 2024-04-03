<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Driver;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Driver::class;
    public function definition(): array
    {
        // Генерация случайного номера телефона в заданном формате
        $phoneNumber = '7' . $this->faker->randomElement([6, 7]) . $this->faker->randomNumber(9);

        return [
            'phone' => $phoneNumber,
            'name' => $this->faker->name,
            'balance' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}
