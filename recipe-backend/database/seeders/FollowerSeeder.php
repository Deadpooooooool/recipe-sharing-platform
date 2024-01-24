<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class FollowerSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            // Get a collection of other users
            $otherUsers = $users->reject(function ($u) use ($user) {
                return $u->id == $user->id;
            });

            // Determine the number of users to follow (up to 3 or the number of other users available)
            $countToFollow = min(3, $otherUsers->count());

            foreach ($otherUsers->random($countToFollow) as $follower) {
                $user->followers()->attach($follower);
            }
        }
    }
}
