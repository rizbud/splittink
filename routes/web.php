<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Index');
})->name('index');


Route::get('new', [GroupController::class, 'new'])->name('groups.new');
Route::get('groups', function () {
    return redirect()->route('index');
});
Route::post('groups', [GroupController::class, 'create'])->name('groups.create');
Route::get('groups/{slug}', [GroupController::class, 'show'])->name('groups.show');
Route::put('groups/{id}', [GroupController::class, 'update'])->name('groups.update');
Route::get('groups/{slug}/edit', [GroupController::class, 'show'])->name('groups.edit');
Route::get('groups/{slug}/add-bill', [BillController::class, 'new'])->name('bills.new');
Route::post('groups/{id}/add-bill', [BillController::class, 'create'])->name('bills.create');
Route::get('groups/{slug}/bills', function () {
    return redirect()->route('groups.show', request()->slug);
});
Route::get('groups/{slug}/bills/{billId}', [BillController::class, 'show'])->name('bills.show');
Route::put('groups/{id}/bills/{billId}', [BillController::class, 'update'])->name('bills.update');
Route::delete('groups/{id}/bills/{billId}', [BillController::class, 'delete'])->name('bills.delete');
Route::get('groups/{slug}/bills/{billId}/edit', [BillController::class, 'show'])->name('bills.edit');
