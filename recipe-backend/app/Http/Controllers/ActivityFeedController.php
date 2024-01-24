<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Recipe;

class ActivityFeedController extends Controller
{
    /**
     * Display the activity feed for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();

        // Get the IDs of users that the authenticated user is following
        $followingIds = $user->following()->pluck('users.id')->toArray();

        // Fetch recent recipes from the users being followed
        $feedItems = Recipe::whereIn('user_id', $followingIds)
                           ->with('user') // Eager load the user relationship
                           ->latest() // Order by latest first
                           ->take(10) // Limit the number of results
                           ->get();

        return response()->json(['feed' => $feedItems]);
    }

    // Add any other methods or functionality as needed
}
