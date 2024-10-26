<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Index');
})->name('index');

Route::get('/about-us', function () {
    return inertia('About-Us');
})->name('about-us');

Route::get('/terms-of-service', function () {
    return inertia('Terms-Of-Service');
})->name('terms-of-service');

Route::get('/privacy-policy', function () {
    return inertia('Privacy-Policy');
})->name('privacy-policy');

Route::get('/contact-us', function () {
    return inertia('Contact-Us');
})->name('contact-us');


Route::get('new', [GroupController::class, 'new'])->name('groups.new');
Route::get('groups', function () {
    return redirect()->route('index');
});
Route::post('groups', [GroupController::class, 'create'])->name('groups.create');
Route::get('groups/{slug}', [GroupController::class, 'show'])->name('groups.show');
Route::put('groups/{id}', [GroupController::class, 'update'])->name('groups.update');
Route::get('groups/{slug}/edit', [GroupController::class, 'show'])->name('groups.edit');
Route::post('groups/{id}/settle', [GroupController::class, 'settle'])->name('groups.settle');
Route::get('groups/{slug}/add-bill', [BillController::class, 'new'])->name('bills.new');
Route::post('groups/{id}/add-bill', [BillController::class, 'create'])->name('bills.create');
Route::get('groups/{slug}/bills', function () {
    return redirect()->route('groups.show', request()->slug);
});
Route::get('groups/{slug}/bills/{billId}', [BillController::class, 'show'])->name('bills.show');
Route::put('groups/{id}/bills/{billId}', [BillController::class, 'update'])->name('bills.update');
Route::delete('groups/{slug}/bills/{billId}', [BillController::class, 'delete'])->name('bills.delete');
Route::get('groups/{slug}/bills/{billId}/edit', [BillController::class, 'show'])->name('bills.edit');
