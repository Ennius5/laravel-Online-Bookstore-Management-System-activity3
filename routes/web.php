<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\BookImportExportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\OrderExportController;
use App\Http\Controllers\Admin\UserImportExportController;

/*
|--------------------------------------------------------------------------
| Public Routes (Guest Visitors) – 30 req/min per IP
|--------------------------------------------------------------------------
*/
Route::middleware(['throttle:public'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

    // 2FA Challenge – accessible without login (for login flow) and with login (for enable)
    Route::get('/two-factor-challenge', [TwoFactorController::class, 'index'])
        ->name('two-factor.challenge');
    Route::post('/two-factor-challenge', [TwoFactorController::class, 'verify'])
        ->name('two-factor.verify');
    Route::post('/two-factor-challenge/resend', [TwoFactorController::class, 'resend'])
        ->name('two-factor.resend');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes – dynamic limit (default 60/min)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'throttle:api'])->group(function () {

    // Profile & Dashboard
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])
        ->middleware(['verified'])
        ->name('dashboard');

    // 2FA Settings
    Route::get('/profile/two-factor', [TwoFactorController::class, 'settings'])
        ->name('profile.two-factor');
    Route::post('/profile/two-factor/enable', [TwoFactorController::class, 'enable'])
        ->name('profile.two-factor.enable');
    Route::delete('/profile/two-factor/disable', [TwoFactorController::class, 'disable'])
        ->name('profile.two-factor.disable');

    // Reviews
    Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Orders (customer)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/add', [OrderController::class, 'addToCart'])->name('orders.addtocart');
    Route::post('/orders/process', [OrderController::class, 'processOrder'])->name('orders.process');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::delete('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Customer order export / invoice
    Route::get('/orders/export/my-orders', [OrderExportController::class, 'myOrders'])->name('orders.export.my');
    Route::get('/orders/{order}/invoice', [OrderExportController::class, 'invoice'])->name('orders.invoice');

    Route::get('/books/exportCatalogue', [BookImportExportController::class, 'userIndex'])->name('books.exportCatalogue');
    // User Import/Export (admin only? originally inside auth without admin check – we'll keep for authenticated users)
    // Route::get('/users/import-export', [UserImportExportController::class, 'index'])->name('users.import-export');
    // Route::post('/users/import', [UserImportExportController::class, 'import'])->name('users.import');
    // Route::get('/users/export', [UserImportExportController::class, 'export'])->name('users.export');


});

/*
|--------------------------------------------------------------------------
| Admin Routes – 1000 req/min (with admin role check)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'is_admin', 'throttle:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/backup', [App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/trigger', [App\Http\Controllers\Admin\BackupController::class, 'trigger'])->name('backup.trigger');
    Route::get('/audit', [App\Http\Controllers\Admin\AuditController::class, 'index'])->name('audit.index');
    Route::get('/audit/{id}', [App\Http\Controllers\Admin\AuditController::class, 'show'])->name('audit.show');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/categories/{category}/books', [BookController::class, 'getBooks']); // no name needed? keep as is

    // Books
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

    // Book Import/Export
    Route::get('/books/import-export', [BookImportExportController::class, 'index'])->name('books.import-export');
    Route::post('/books/import', [BookImportExportController::class, 'import'])->name('books.import');
    Route::get('/books/export', [BookImportExportController::class, 'export'])->name('books.export');
    Route::get('/books/template', [BookImportExportController::class, 'downloadTemplate'])->name('books.template');

    // Orders (admin management)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Order Export
    Route::get('/orders/export', [OrderExportController::class, 'index'])->name('orders.export.index');
    Route::get('/orders/export/download', [OrderExportController::class, 'export'])->name('orders.export.download');
    Route::get('/orders/export/financial', [OrderExportController::class, 'financialReport'])->name('orders.export.financial');

    //user
    Route::get('/users/import-export', [UserImportExportController::class, 'index'])->name('users.import-export');
    Route::post('/users/import', [UserImportExportController::class, 'import'])->name('users.import');
    Route::get('/users/export', [UserImportExportController::class, 'export'])->name('users.export');
});


// Debug route (unchanged)
Route::get('/debug-vite', function() {
    echo "<h1>Vite Debug</h1>";
    // ... keep your debug code
});

require __DIR__.'/auth.php';
