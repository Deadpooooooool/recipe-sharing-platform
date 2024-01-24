<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the profile for the authenticated user.
     */
    public function show()
    {
        $user = Auth::user();

        // Eager load the profile, recipes, followers, and following
        $user->load('profile', 'recipes', 'followers', 'following');

        // Return the user's profile along with recipes, followers, and following
        return response()->json([
            'user' => $user,
            'profile' => $user->profile,
            'recipes' => $user->recipes,
            'followers' => $user->followers,
            'following' => $user->following
        ]);
    }

    /**
     * Update the profile for the authenticated user.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        // Validate and update the profile data
        $validatedData = $request->validate([
            'bio' => 'string|nullable',
            'profile_picture' => 'string|nullable',
            // other validation rules as necessary
        ]);

        $profile->update($validatedData);

        // Return the updated profile
        return response()->json(['message' => 'Profile updated successfully', 'profile' => $profile]);
    }
    
    public function follow(Request $request, $userId)
    {
        $user = Auth::user();
        $userToFollow = User::findOrFail($userId);

        // Assuming there's a many-to-many relationship set up in the User model
        $user->following()->attach($userToFollow);

        return response()->json(['message' => 'Successfully followed the user']);
    }

    public function unfollow(Request $request, $userId)
    {
        $user = Auth::user();
        $userToUnfollow = User::findOrFail($userId);

        // Assuming there's a many-to-many relationship set up in the User model
        $user->following()->detach($userToUnfollow);

        return response()->json(['message' => 'Successfully unfollowed the user']);
    }
    // Add any additional methods or logic as required
}
