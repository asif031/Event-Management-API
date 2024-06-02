<?php

namespace Database\Factories;

use Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Validation\Rules\Unique;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'name'=> fake()->Unique()->sentence(3),
            'description'=> fake()->text,
            'start_time'=> fake()->dateTimeBetween('now','+1 month'),
            'end_time'=> fn(array $attributes) => fake()->dateTimeBetween($attributes['start_time'],'+2 months')
        ];
    }
}
