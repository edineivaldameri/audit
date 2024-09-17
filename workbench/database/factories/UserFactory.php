<?php

declare(strict_types=1);

namespace Workbench\EdineiValdameri\Laravel\Audit\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Workbench\EdineiValdameri\Laravel\Audit\App\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
