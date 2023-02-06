<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Feature;
use App\Models\User;
use Database\Factories\CommentFactory;
use Database\Factories\CompanyFactory;
use Database\Factories\CustomerFactory;
use Database\Factories\FeatureFactory;
use Database\Factories\LoginFactory;
use Database\Factories\PostFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // seed companies and users
        CompanyFactory::new()
            ->count(5000)
            ->create()
            ->each(
                fn (Company $company) => $company
                    ->users()
                    ->createMany(
                        UserFactory::new()->count(10)->make()->map->getAttributes()
                    )
            );

        // seed posts
        User::query()
            ->inRandomOrder()
            ->take(50)
            ->get()
            ->each(
                fn(User $user) => $user->posts()->createMany(
                    PostFactory::new()->count(5)->make()->toArray()
                )
            );

        // seed logins
        User::query()
            ->inRandomOrder()
            ->take(10000)
            ->get()
            ->each(
                fn(User $user) => $user
                    ->logins()
                    ->createMany(
                        LoginFactory::new()->count(5)->make()->toArray()
                    )
            );

        // seed customers
        User::query()
            ->inRandomOrder()
            ->take(10000)
            ->get()
            ->each(
                fn(User $user) => $user
                    ->customers()
                    ->createMany(
                        CustomerFactory::new()->count(5)->make()->toArray()
                    )
            );

        // seed features and comments
        $randomUsers = User::query()->inRandomOrder()->limit(250)->get();
        FeatureFactory::new()->count(60)->create()->each(function(Feature $feature) use($randomUsers) {
            $feature->comments()->createMany(
                CommentFactory
                    ::new()
                    ->count(rand(1, 50))
                    ->make()
                    ->each(function(Comment $comment) use($randomUsers) {
                        $comment->user_id = $randomUsers->random()->id;
                    })
                    ->toArray()
            );
        });

        // specific seeds
        /** @var \App\Models\User $user */
        $user = User::query()->find(10000);
        $user->update([
            'first_name' => 'Bill',
            'last_name' => 'Gates',
            'email' => 'bill.gates@microsoft.com',
        ]);
        $user->company->update([
            'name' => 'Microsoft Corporation',
        ]);

        /** @var \App\Models\User $user */
        $user = User::query()->find(20000);
        $user->update([
            'first_name' => 'Tim',
            'last_name' => 'O\'Reilly',
            'email' => 'tim@oreilly.com',
        ]);
        $user->company->update([
            'name' => 'O\'Reilly Media Inc.',
        ]);

        $user1 = UserFactory::new()
            ->create([
                'first_name' => 'Ted',
                'last_name' => 'Bossman',
                'is_owner' => true,
                'gender' => 0,
                'company_id' => Company::query()->inRandomOrder()->first()->id,
            ]);
        $user2 = UserFactory::new()
            ->create([
                'first_name' => 'Sarah',
                'last_name' => 'Seller',
                'gender' => 1,
                'company_id' => Company::query()->inRandomOrder()->first()->id,
            ]);
        $user3 = UserFactory::new()
            ->create([
                'first_name' => 'Chase',
                'last_name' => 'Indeals',
                'gender' => 0,
                'company_id' => Company::query()->inRandomOrder()->first()->id,
            ]);

        foreach ([$user1, $user2, $user3] as $us) {
            $us->customers()
                ->createMany(
                    CustomerFactory::new()->count(25)->make()->toArray()
                );
        }
    }
}
