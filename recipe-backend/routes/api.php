<?php

use App\Http\Controllers\ActivityFeedController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RecipeInteractionController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);

// Profile Management
Route::middleware('auth:api')->get('user', [ProfileController::class, 'show']);
Route::middleware('auth:api')->put('user', [ProfileController::class, 'update']);
Route::post('/profile/{userId}/follow', [ProfileController::class, 'follow'])->middleware('auth:api');
Route::delete('/profile/{userId}/unfollow', [ProfileController::class, 'unfollow'])->middleware('auth:api');

// Recipe Management
Route::middleware('auth:api')->post('/recipes', [RecipeController::class, 'store']);
Route::middleware('auth:api')->put('/recipes/{recipe}', [RecipeController::class, 'update']);
Route::middleware('auth:api')->delete('/recipes/{recipe}', [RecipeController::class, 'destroy']);
Route::middleware('auth:api')->post('/recipes/{recipe}/rate', [RecipeController::class, 'rate']);

// Recipe Interaction
Route::get('/recipes/search', [RecipeInteractionController::class, 'search']);
Route::middleware('auth:api')->post('/recipes/{recipe}/rate', [RecipeInteractionController::class, 'rate']);
Route::middleware('auth:api')->post('/recipes/{recipe}/like', [RecipeInteractionController::class, 'like']);
Route::middleware('auth:api')->delete('/recipes/{recipe}/like', [RecipeInteractionController::class, 'unlike']);

// Activity Feed
Route::middleware('auth:api')->get('/activity-feed', [ActivityFeedController::class, 'index']);

// Admin User Management (Protected by Admin Middleware)
Route::middleware(['auth:api', 'admin'])->get('/admin/users', [AdminUserController::class, 'index']);
Route::middleware(['auth:api', 'admin'])->delete('/admin/users/{user}', [AdminUserController::class, 'destroy']);

// Tag Management
Route::middleware('auth:api')->post('/tags', [TagController::class, 'store']);
Route::middleware('auth:api')->post('/recipes/{recipe}/tags', [TagController::class, 'attachTag']);
Route::middleware('auth:api')->post('/recipes/{recipe}/tags/{tag}', [TagController::class, 'detachTag']);
Route::get('/tags', [TagController::class, 'index']);
Route::get('/tags/{tag}/recipes', [TagController::class, 'getRecipesByTag']);

// Additional routes can be added as needed
