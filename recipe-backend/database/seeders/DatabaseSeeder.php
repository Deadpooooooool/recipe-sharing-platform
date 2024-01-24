<?php

namespace Database\Seeders;

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
        $this->call([
            RecipeSeeder::class,
            TagSeeder::class,
            ProfileSeeder::class,
            RatingSeeder::class,
            FollowerSeeder::class,
            CommentSeeder::class,
            // Other seeders as needed
        ]);
    }
}
