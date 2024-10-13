<?php

use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Index');
});


Route::get('new', [GroupController::class, 'new'])->name('groups.new');
Route::post('groups', [GroupController::class, 'create'])->name('groups.create');
Route::get('groups/{slug}', [GroupController::class, 'show'])->name('groups.show');
Route::put('groups/{id}', [GroupController::class, 'update'])->name('groups.update');
Route::get('groups/{slug}/edit', [GroupController::class, 'show'])->name('groups.edit');
