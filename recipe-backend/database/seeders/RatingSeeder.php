<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rating;
use App\Models\Recipe;
use App\Models\User;

class RatingSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $recipes = Recipe::all();

        foreach ($recipes as $recipe) {
            foreach ($users as $user) {
                Rating::create([
                    'user_id' => $user->id,
                    'recipe_id' => $recipe->id,
                    'rating' => rand(1, 5),
                ]);
            }
        }
    }
}
