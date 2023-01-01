<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\RecordController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/admin', function () {
    if (Auth::check()) {
        return redirect(route('admin.dashboard.index'));
    } else {
        return Inertia::render('Auth/Login');
    }
});

Route::prefix('admin')
    ->as('admin.')
    ->middleware(['auth:sanctum', 'verified'])
    ->group(function () {

        // Dashboard
        Route::resource('dashboard', AdminDashboardController::class);
        // Branch
        Route::resource('branch', BranchController::class);
        // Course
        Route::resource('course', CourseController::class);
        // User
        Route::resource('user', UserController::class);
        // Record
        Route::resource('record', RecordController::class);
    });

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->name('dashboard');
// });
