<?php

use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContactController;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $projects = Project::where('is_visible', true)
        ->orderBy('order')
        ->orderBy('created_at', 'desc')
        ->get();
    $profile = User::first(); // único usuário = perfil público
    return view('welcome', compact('projects', 'profile'));
});

Route::get('/docs/criar-seu-portfolio', fn () => view('docs.criar-seu-portfolio'))->name('docs.criar-seu-portfolio');

Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:10,1')->name('contact.store');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::post('projects/fetch-thumbnail', [ProjectController::class, 'fetchThumbnail'])->name('projects.fetch-thumbnail');
    Route::resource('projects', ProjectController::class);
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});
