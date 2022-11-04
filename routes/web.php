<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimeController;

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

// Project Routes
Route::get('/', [ProjectController::class, 'index'])->name('home');
Route::get('projects/restore/{project_id}', [ProjectController::class, 'restore'])->name('projects.restore');
Route::delete('projects/permanent-delete/{project_id}', [ProjectController::class, 'destroy_permanent'])->name('projects.destroy_permanent');
Route::resource('projects', ProjectController::class)->except(['index', 'create', 'edit']);

// Time Routes
Route::post('{project}/times', [TimeController::class, 'store'])->name('times.store');
Route::get('times/restore/{time_id}', [TimeController::class, 'restore'])->name('times.restore');
Route::delete('times/permanent-delete/{time_id}', [TimeController::class, 'destroy_permanent'])->name('times.destroy_permanent');
Route::resource('times', TimeController::class)->only(['update', 'destroy']);
