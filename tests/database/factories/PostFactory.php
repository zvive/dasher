<?php

namespace Dasher\Tests\Database\Factories;

use Dasher\Tests\Models\Post;
use Dasher\Tests\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'author_id' => User::factory(),
            'content' => $this->faker->paragraph(),
            'tags' => $this->faker->words(),
            'title' => $this->faker->sentence(),
        ];
    }
}
