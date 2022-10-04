<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

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

Route::get('/', [ProjectController::class, 'index'])->name('home');
Route::group(['prefix' => 'projects', 'as' => 'projects.'], function () {
    Route::delete('permanent-delete/{id}', [ProjectController::class, 'destroy_permanent'])->name('destroy_permanent');
    Route::get('restore/{id}', [ProjectController::class, 'restore'])->name('restore');
});
Route::resource('projects', ProjectController::class)->except(['index', 'create', 'edit']);
