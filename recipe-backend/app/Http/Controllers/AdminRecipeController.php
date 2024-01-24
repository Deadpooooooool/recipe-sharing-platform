<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Recipe;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityFeedController extends Controller
{
    /**
     * Display the activity feed for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();

        // Get the users that the authenticated user is following
        $following = $user->following()->pluck('users.id');

        // Fetch recent recipes from the users being followed
        $feedItems = Recipe::whereIn('user_id', $following)
            ->with('user') // Load the user data for each recipe
            ->orderBy('created_at', 'desc') // Order by latest first
            ->take(10) // Limit the number of results
            ->get();

        return response()->json($feedItems);
    }

    /**
     * Block a user.
     */
    public function blockUser(User $user)
    {
        $user->update(['is_blocked' => true]);
        return response()->json(['message' => 'User has been blocked.']);
    }

    /**
     * Unblock a user.
     */
    public function unblockUser(User $user)
    {
        $user->update(['is_blocked' => false]);
        return response()->json(['message' => 'User has been unblocked.']);
    }

    /**
     * Get reports submitted by users.
     */
    public function getReports()
    {
        // Assuming there is a Report model with user_id and content fields
        $reports = Report::all();
        return response()->json($reports);
    }

    /**
     * Delete inappropriate content.
     */
    public function deleteContent($contentId)
    {
        // This would depend on the type of content. Assuming a Content model for simplicity.
        $content = Content::findOrFail($contentId);
        $content->delete();
        return response()->json(['message' => 'Content has been deleted.']);
    }

    // Add any additional methods or functionality as required
}
