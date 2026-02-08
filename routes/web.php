<?php

use App\Http\Controllers\Admin\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $projects = \App\Models\Project::where('is_visible', true)
        ->orderBy('order')
        ->orderBy('created_at', 'desc')
        ->get();
    return view('welcome', compact('projects'));
});

// Mini CMS – CRUD de projetos (proteja com auth quando tiver login)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::post('projects/fetch-thumbnail', [ProjectController::class, 'fetchThumbnail'])->name('projects.fetch-thumbnail');
    Route::resource('projects', ProjectController::class);
});
