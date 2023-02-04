<?php

use App\Http\Controllers\FeaturesController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/---test', function() {
    dd(\Database\Factories\UserFactory::new()->count(5)->make()->toArray());
});

Route::get('/', [UsersController::class, 'index']);

Route::get('/posts', [PostsController::class, 'index']);

Route::get('/features', [FeaturesController::class, 'index']);

Route::get('/features/{feature}', [FeaturesController::class, 'show']);
