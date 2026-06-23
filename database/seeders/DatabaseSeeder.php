<?php

namespace Database\Seeders;

use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $alice = User::factory()->create([
            'name'  => 'Alice Johnson',
            'email' => 'alice@example.com',
        ]);

        $bob = User::factory()->create([
            'name'  => 'Bob Smith',
            'email' => 'bob@example.com',
        ]);

        $tags = collect([
            ['name' => 'bug',         'color' => '#ef4444'],
            ['name' => 'feature',     'color' => '#3b82f6'],
            ['name' => 'improvement', 'color' => '#10b981'],
            ['name' => 'docs',        'color' => '#f59e0b'],
            ['name' => 'urgent',      'color' => '#dc2626'],
        ])->map(fn ($t) => Tag::create($t));

        $users = collect([$alice, $bob]);

        foreach ($users as $user) {
            Project::factory(3)
                ->for($user)
                ->create()
                ->each(function (Project $project) use ($tags, $users) {
                    Issue::factory(5)
                        ->for($project)
                        ->create()
                        ->each(function (Issue $issue) use ($tags, $users) {
                            $issue->tags()->attach($tags->random(rand(1, 3)));
                            $issue->assignees()->attach($users->random(rand(1, 2)));
                            $issue->comments()->createMany(
                                \Database\Factories\CommentFactory::new()->count(rand(2, 5))->make()->toArray()
                            );
                        });
                });
        }
    }
}
