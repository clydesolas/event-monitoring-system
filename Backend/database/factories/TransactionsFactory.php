<?php

namespace Database\Factories;

use App\Models\Transactions;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransactionsFactory extends Factory
{
    protected $model = Transactions::class;

    public function definition()
    {
        $currentYear = now()->year;
        $randomString = Str::random(6);
        $iteration = 1;

        return [
            'title' => $this->faker->sentence,
            'event_id' => $this->faker->sentence,
            'payment' => $this->faker->randomFloat(2, 10, 1000),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'duration_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'academic_year' => $this->faker->randomElement(['A.Y. 2022-2023', 'A.Y. 2023-2024']),
            'semester' => $this->faker->randomElement(['FIRST SEMESTER', 'SECOND SEMESTER']),
            'student_number' => $this->faker->unique()->randomNumber(8),
            'fullname' => $this->faker->name,
            'paid_by' => $this->faker->name,
            'relation' => $this->faker->randomElement(['Parent', 'Sibling', 'Friend']),
            'transaction_id' => "CDPV-{$currentYear}-{$randomString}-{$iteration}",
        ];
    }
}
