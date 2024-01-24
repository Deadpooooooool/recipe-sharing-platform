<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request, including the image field if necessary
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'ingredients' => 'required',
            'steps' => 'required',
            'cooking_time' => 'integer|nullable',
            'difficulty_level' => 'string|nullable',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // other validations
        ]);

        // Handle the image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('recipe_images', 'public');
            // $validatedData['image'] = $imagePath;
            $validatedData['image'] = Storage::url($imagePath); // Get the public URL
        }

        // Create the recipe with the validated data
        $recipe = auth()->user()->recipes()->create($validatedData);

        // Return the newly created recipe and its image path
        return response()->json(['message' => 'Recipe created successfully', 'recipe' => $recipe]);
    }

    public function update(Request $request, Recipe $recipe)
    {
        // Ensure the authenticated user is the owner of the recipe
        if ($recipe->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validate the request
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'ingredients' => 'required',
            'steps' => 'required',
            'cooking_time' => 'integer|nullable',
            'difficulty_level' => 'string|nullable',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // other validations as necessary
        ]);

        // Handle the image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($recipe->image) {
                Storage::delete($recipe->image);
            }

            $imagePath = $request->file('image')->store('recipe_images', 'public');
            $validatedData['image'] = $imagePath;
        }

        // Update the recipe
        $recipe->update($validatedData);

        return response()->json(['message' => 'Recipe updated successfully', 'recipe' => $recipe]);
    }

    /**
     * Remove the specified recipe.
     */
    public function destroy(Recipe $recipe)
    {
        // Ensure the authenticated user is the owner of the recipe
        if ($recipe->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete the recipe
        $recipe->delete();

        return response()->json(['message' => 'Recipe deleted successfully']);
    }

    public function rate(Request $request, Recipe $recipe)
    {
        $request->validate(['rating' => 'required|integer|between:1,5']);

        $rating = new Rating();
        $rating->user_id = Auth::id();
        $rating->recipe_id = $recipe->id;
        $rating->rating = $request->rating;
        $rating->save();

        return response()->json(['message' => 'Recipe rated successfully']);
    }

    public function index()
    {
        $recipes = Recipe::with('ratings')->paginate(10); // 10 items per page
        return response()->json($recipes);
    }

    public function show(Recipe $recipe)
    {
        $recipe->load('ratings');
        return response()->json($recipe);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $recipes = Recipe::with('ratings')
            ->where('title', 'like', "%{$query}%")
            ->paginate(10); // 10 items per page
        return response()->json($recipes);
    }

    // Add other methods as necessary, such as show (for a single recipe) and index (for listing recipes)
}
