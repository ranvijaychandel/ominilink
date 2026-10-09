<?php

use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('shortUrlList');
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //Short urls
    Route::get('short-urls', [ShortUrlController::class, 'index'])->name('shortUrlList');
    Route::get('short-urls/create', [ShortUrlController::class, 'create'])->name('shortUrlCreate');
    Route::post('short-urls/store', [ShortUrlController::class, 'store'])->name('ShortUrlStore');
});
Route::middleware(['auth', 'superAdmin'])->group(function () {
    Route::get('clients', [TenantController::class, 'index'])->name('clients');
});
//Invitation Routes
Route::middleware(['auth', 'canInvite'])->group(function () {
    //can invite only super-admin and admin
    Route::get('invitation/create', [InvitationController::class, 'create'])->name('invitationCreate');
    Route::post('invitation/store', [InvitationController::class, 'store'])->name('invitationStore');
});

require __DIR__.'/auth.php';

// Catch-all short URL redirect. Must stay last so specific routes match first.
Route::get('/{code}', [ShortUrlController::class, 'redirect'])->where('code', '[A-Za-z0-9]+');
Route::get('invitation/accept/{id}', [InvitationController::class, 'invitationAccept'])->name('invitationAccept');
