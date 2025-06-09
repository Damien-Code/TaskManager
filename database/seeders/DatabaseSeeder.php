<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\TaskFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->has(Task::factory(10))->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory(10)->has(Task::factory(10))->create();
        $teams = Team::factory(10)->create();

        User::all()->each(function ($user) use ($teams) {
            $user->teams()->attach($teams->random(rand(1, 3))->pluck('id')->toArray());
        });
//        Task::factory()->count(100)->create();
    }
}
