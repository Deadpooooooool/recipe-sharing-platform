<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeInteractionController extends Controller
{
    /**
     * Search for recipes based on query parameters.
     */
    public function search(Request $request)
    {
        // Example: search by title, ingredients, etc.
        $query = $request->input('query');

        $recipes = Recipe::where('title', 'like', "%{$query}%")
                         // Add more search conditions as needed
                         ->get();

        return response()->json($recipes);
    }

    /**
     * Rate a recipe.
     */
    public function rate(Request $request, Recipe $recipe)
    {
        // Validate the rating input
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Save the rating for the recipe
        // Implement the rating logic based on your application's needs

        return response()->json(['message' => 'Recipe rated successfully']);
    }

    /**
     * Like a recipe.
     */
    public function like(Recipe $recipe)
    {
        $user = Auth::user();
        
        // Implement the logic to like the recipe
        // For example, add a record in a likes table

        return response()->json(['message' => 'Recipe liked successfully']);
    }

    /**
     * Unlike a recipe.
     */
    public function unlike(Recipe $recipe)
    {
        $user = Auth::user();
        
        // Implement the logic to unlike the recipe
        // For example, remove the record from a likes table

        return response()->json(['message' => 'Recipe unliked successfully']);
    }

    // Add methods for tagging, untagging, and other interactions as needed
}
