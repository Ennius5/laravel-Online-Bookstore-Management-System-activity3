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


// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Book browsing (public)
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

// Order routes (some public, some protected)
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::post('/orders/add', [OrderController::class, 'addToCart'])->name('orders.addtocart');
Route::post('/orders/process', [OrderController::class, 'processOrder'])->name('orders.process');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])
        ->middleware(['verified'])
        ->name('dashboard');

    // Review routes
    Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Order routes (authenticated)
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::delete('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // User Import/Export
    Route::get('/users/import-export', [UserImportExportController::class, 'index'])->name('users.import-export');
    Route::post('/users/import', [UserImportExportController::class, 'import'])->name('users.import');
    Route::get('/users/export', [UserImportExportController::class, 'export'])->name('users.export');

    // Customer routes
    Route::get('/orders/export/my-orders', [OrderExportController::class, 'myOrders'])->name('orders.export.my');
    Route::get('/orders/{order}/invoice', [OrderExportController::class, 'invoice'])->name('orders.invoice');

    // 2FA Settings (authenticated)
    Route::get('/profile/two-factor', [TwoFactorController::class, 'settings'])
        ->name('profile.two-factor');
    Route::post('/profile/two-factor/enable', [TwoFactorController::class, 'enable'])
        ->name('profile.two-factor.enable');
    Route::delete('/profile/two-factor/disable', [TwoFactorController::class, 'disable'])
        ->name('profile.two-factor.disable');
});

// 2FA Challenge Routes - Accessible by both guests (for login) and authenticated users (for enabling)
Route::get('/two-factor-challenge', [TwoFactorController::class, 'index'])
    ->name('two-factor.challenge');
Route::post('/two-factor-challenge', [TwoFactorController::class, 'verify'])
    ->name('two-factor.verify');
Route::post('/two-factor-challenge/resend', [TwoFactorController::class, 'resend'])
    ->name('two-factor.resend');

// Admin-only routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Category management
    Route::get('/categories/create', [CategoryController::class,'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit',[CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class,'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class,'destroy'])->name('categories.destroy');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/{category}/books', [BookController::class, 'getBooks']);

    // Book management
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

    // Admin order management
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    //Import/Export
    Route::get('/books/import-export', [BookImportExportController::class, 'index'])
        ->name('books.import-export');
    Route::post('/books/import', [BookImportExportController::class, 'import'])
        ->name('books.import');
    Route::get('/books/export', [BookImportExportController::class, 'export'])
        ->name('books.export');
    Route::get('/books/template', [BookImportExportController::class, 'downloadTemplate'])
        ->name('books.template');







// Inside admin group
        Route::get('/orders/export', [OrderExportController::class, 'index'])->name('orders.export.index');
        Route::get('/orders/export/download', [OrderExportController::class, 'export'])->name('orders.export.download');
        Route::get('/orders/export/financial', [OrderExportController::class, 'financialReport'])->name('orders.export.financial');
    });

// Debug route (remove in production)
Route::get('/debug-vite', function() {
    echo "<h1>Vite Debug</h1>";

    echo "<h2>Vite Output:</h2>";
    echo htmlspecialchars(
        app(Illuminate\Foundation\Vite::class)(['resources/css/app.css', 'resources/js/app.js'])->toHtml()
    );

    echo "<h2>Manifest Contents:</h2>";
    $manifestPath = public_path('build/manifest.json');
    if (file_exists($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true);
        echo "<pre>" . json_encode($manifest, JSON_PRETTY_PRINT) . "</pre>";
    } else {
        echo "Manifest not found at: " . $manifestPath;
    }

    echo "<h2>Generated Files:</h2>";
    $files = glob(public_path('build/assets/*'));
    foreach ($files as $file) {
        echo basename($file) . " (" . round(filesize($file)/1024, 2) . " KB)<br>";
    }
});
// Disable antivirus for this file if you want to test email sending without it blocking the connection
// Route::get('/debug/mailtest', function() {
//     Mail::raw('Gmail SMTP Test Successful', function ($message) {
// $message->to('e.campomanes101848@gmail.com')
// ->subject('Laravel Gmail SMTP Test');
// });
// });
require __DIR__.'/auth.php';
