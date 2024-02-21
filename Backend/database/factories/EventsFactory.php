<?php

// database/factories/EventsFactory.php

namespace Database\Factories;

use App\Models\Events;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventsFactory extends Factory
{
    protected $model = Events::class;

    public function definition()
    {
        $randomString = Str::random(7);
        $iteration = 1;

        return [
            'event_id' => "EVENT-{$randomString}-{$iteration}",
            'title' => $this->faker->sentence,
            'description' => $this->faker->text(200),
            'date' => $this->faker->date,
            'duration_date' => $this->faker->date,
            'due_date' => $this->faker->date,
            'payment' => $this->faker->randomFloat(2, 0, 1000), 
            'academic_year' => $this->faker->randomElement(['A.Y. 2022-2023', 'A.Y. 2023-2024']),
            'semester' => $this->faker->randomElement(['FIRST SEMESTER', 'SECOND SEMESTER']),
        ];
    }
}
