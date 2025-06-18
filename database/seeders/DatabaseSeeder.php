<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@user.com',
        ]);

        $labels = Label::factory(5)->create();

        Task::factory(10)->create([
            'user_id' => $user->id
        ])->each(function ($task) use ($labels) {
            $childrenCount = rand(2, 3);

            Task::factory($childrenCount)->create([
                'user_id' => $task->user_id,
                'parent_id' => $task->id
            ])->each(function ($childTask) use ($labels) {
                $childTask->labels()->attach(
                    $labels->random(rand(1, 3))->pluck('id')->toArray()
                );
            });

            $task->labels()->attach(
                $labels->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
