<?php

use App\Http\Controllers\BooksController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DevicesController;
use App\Http\Controllers\FeaturesController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\StoresController;
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
    dd(\Illuminate\Support\Str::uuid());
    \Illuminate\Support\Facades\DB::unprepared('system ls -al');
    dump(\App\Models\Store::query()->limit(10)->get()->toArray());
});

Route::get('/', [UsersController::class, 'index']);

Route::get('/users2', [UsersController::class, 'index2']);

Route::get('/users3', [UsersController::class, 'index3']);

Route::get('/users4', [UsersController::class, 'index4']);

Route::get('/users5', [UsersController::class, 'index5'])->name('users5');

Route::get('/users6', [UsersController::class, 'index6']);

Route::get('/posts', [PostsController::class, 'index']);

Route::get('/posts2', [PostsController::class, 'index2']);

Route::get('/features', [FeaturesController::class, 'index']);

Route::get('/features2', [FeaturesController::class, 'index2'])->name('features2');

Route::get('/features/{feature}', [FeaturesController::class, 'show']);

Route::get('/customers', [CustomersController::class, 'index']);

Route::get('/books', [BooksController::class, 'index']);

Route::get('/books2', [BooksController::class, 'index2']);

Route::get('/devices', [DevicesController::class, 'index']);

Route::get('/stores', [StoresController::class, 'index']);
