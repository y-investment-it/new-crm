<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Lead;
use App\Models\LeadComment;
use App\Models\LeadHistory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        foreach (['admin', 'manager', 'agent'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $admin->assignRole('admin');

        $managers = User::factory()->count(2)->create([
            'role' => 'manager',
        ]);
        $managers->each(fn ($manager) => $manager->assignRole('manager'));

        $agents = User::factory()->count(5)->create([
            'role' => 'agent',
        ]);
        $agents->each(fn ($agent) => $agent->assignRole('agent'));

        $users = $managers->concat($agents)->push($admin);

        Lead::factory()->count(150)->create()->each(function (Lead $lead) use ($users, $faker) {
            $lead->update([
                'owner_id' => $users->random()->id,
                'created_by' => $users->random()->id,
                'stage_date' => now()->subDays(rand(0, 30)),
            ]);

            $commentCount = rand(1, 5);
            for ($i = 0; $i < $commentCount; $i++) {
                $user = $users->random();
                $comment = LeadComment::create([
                    'lead_id' => $lead->id,
                    'user_id' => $user->id,
                    'comment' => $faker->sentence(),
                    'created_at' => now()->subDays(rand(0, 20)),
                ]);

                LeadHistory::create([
                    'lead_id' => $lead->id,
                    'user_id' => $user->id,
                    'action' => 'commented',
                    'payload' => ['comment_id' => $comment->id],
                    'created_at' => $comment->created_at,
                ]);
            }

            LeadHistory::create([
                'lead_id' => $lead->id,
                'user_id' => $lead->owner_id,
                'action' => 'assigned',
                'payload' => ['owner_id' => $lead->owner_id],
                'created_at' => now()->subDays(rand(0, 20)),
            ]);
        });

        $collections = Collection::factory()->count(3)->create();
        $products = Product::factory()->count(12)->create();

        foreach ($products as $product) {
            $product->collections()->attach($collections->random(rand(1, 2))->pluck('id'));
        }
    }
}
