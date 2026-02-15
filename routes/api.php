<?php
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Use regular routes instead of apiResource to avoid conflicts
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{category}', [CategoryController::class, 'update']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
    Route::get('categories/{category}/books', [BookController::class, 'getBooks']);
});
