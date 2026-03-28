<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\StockTakingSessionController;
use App\Http\Controllers\Admin\TagNumberController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\StockTakingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('stock-taking.index');
    }

    return redirect()->route('login');
});

Route::middleware(['auth', 'prevent-direct-access'])->group(function () {
    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('items', ItemController::class);
        Route::get('/items-import', [ItemController::class, 'showImport'])->name('items.import');
        Route::post('/items-import', [ItemController::class, 'import'])->name('items.import.process');
        Route::get('/items-template', [ItemController::class, 'downloadTemplate'])->name('items.template');
        Route::resource('sessions', StockTakingSessionController::class);
        Route::get('/sessions/{session}/export', [StockTakingSessionController::class, 'export'])->name('sessions.export');
        Route::get('/tag-numbers', [TagNumberController::class, 'index'])->name('tag-numbers.index');
        Route::post('/tag-numbers/generate', [TagNumberController::class, 'generate'])->name('tag-numbers.generate');
        Route::post('/tag-numbers/download-excel', [TagNumberController::class, 'downloadExcel'])->name('tag-numbers.download-excel');
    });

    // User stock taking routes
    Route::middleware('user')->prefix('stock-taking')->name('stock-taking.')->group(function () {
        Route::get('/', [StockTakingController::class, 'index'])->name('index');
        Route::get('/{session}', [StockTakingController::class, 'show'])->name('show');
        Route::post('/{session}/start', [StockTakingController::class, 'start'])->name('start');
        Route::post('/{session}/save-detail', [StockTakingController::class, 'saveDetail'])->name('save-detail');
        Route::post('/{session}/complete', [StockTakingController::class, 'complete'])->name('complete');
    });
});

require __DIR__.'/auth.php';
