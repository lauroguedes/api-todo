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

        $user2 = User::factory()->create([
            'name' => 'Test User 2',
            'email' => 'test2@user.com',
        ]);

        $labels = Label::factory(5)->create();

        Task::factory(5)
            ->for($user)
            ->create()
            ->each(function ($task) use ($labels) {
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

        Task::factory(5)
            ->for($user2)
            ->create()
            ->each(function ($task) use ($labels) {
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
