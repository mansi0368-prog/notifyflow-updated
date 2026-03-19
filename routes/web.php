<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ContactListController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('campaigns', CampaignController::class);
    Route::resource('contact-lists', ContactListController::class);
    Route::get('contact-lists/{contactList}/upload', [ContactListController::class, 'uploadForm'])->name('contact-lists.upload');
    Route::post('contact-lists/{contactList}/upload', [ContactListController::class, 'uploadCsv'])->name('contact-lists.upload.csv');
});

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('campaigns', CampaignController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
