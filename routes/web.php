<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/term', function () {
    return view('term');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index']);

    Route::get('/my-profile', [App\Http\Controllers\Admin\UserController::class, 'myprofile'])->name('user.myprofile');
    Route::put('/my-profile/update', [App\Http\Controllers\Admin\UserController::class, 'myprofileUpdate'])->name('user.myprofile.update');
    Route::get('/rekap/{id}', [App\Http\Controllers\Admin\RekapController::class, 'user'])->name('rekap.myprofile');

    Route::prefix('admin')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index']);
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard.index');

        // User
        Route::prefix('user')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.user.index');
            Route::get('/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.user.create');
            Route::post('/store', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.user.store');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.user.edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.user.update');
            Route::put('/verified/{id}', [App\Http\Controllers\Admin\UserController::class, 'verified'])->name('admin.user.verified-account');
            // Route::delete('/destroy/{id}', [App\Http\Controllers\Admin\UserController::class, 'soft_destroy'])->name('admin.user.destroy');
            Route::delete('/destroy/{id}', [App\Http\Controllers\Admin\UserController::class, 'delete_two'])->name('admin.user.destroy');
            Route::delete('/reset/{id}', [App\Http\Controllers\Admin\UserController::class, 'reset'])->name('admin.user.reset');
            Route::get('/export', [App\Http\Controllers\Admin\UserController::class, 'export'])->name('admin.user.export');
            Route::get('/restore', [App\Http\Controllers\Admin\UserController::class, 'restore'])->name('admin.user.restore');
            Route::delete('/restore/{id}', [App\Http\Controllers\Admin\UserController::class, 'restore_two'])->name('admin.user.restore-two');
        });

        // Master
        Route::prefix('master')->group(function () {
            Route::get('unit/', [App\Http\Controllers\Admin\UnitController::class, 'index'])->name('admin.unit.index');
        });

        // Asset
        Route::prefix('asset')->group(function () {
            Route::prefix('category')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\AssetCategoryController::class, 'index'])->name('admin.asset-category.index');
                Route::get('/create', [App\Http\Controllers\Admin\AssetCategoryController::class, 'create'])->name('admin.asset-category.create');
                Route::post('/store', [App\Http\Controllers\Admin\AssetCategoryController::class, 'store'])->name('admin.asset-category.store');
                Route::get('/edit/{slug}', [App\Http\Controllers\Admin\AssetCategoryController::class, 'edit'])->name('admin.asset-category.edit');
                Route::put('/update/{id}', [App\Http\Controllers\Admin\AssetCategoryController::class, 'update'])->name('admin.asset-category.update');
                Route::delete('/destroy/{id}', [App\Http\Controllers\Admin\AssetCategoryController::class, 'destroy'])->name('admin.asset-category.destroy');
            });
            Route::prefix('room')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\AssetRoomController::class, 'index'])->name('admin.asset-room.index');
                Route::get('/create', [App\Http\Controllers\Admin\AssetRoomController::class, 'create'])->name('admin.asset-room.create');
                Route::post('/store', [App\Http\Controllers\Admin\AssetRoomController::class, 'store'])->name('admin.asset-room.store');
                Route::get('/edit/{slug}', [App\Http\Controllers\Admin\AssetRoomController::class, 'edit'])->name('admin.asset-room.edit');
                Route::put('/update/{id}', [App\Http\Controllers\Admin\AssetRoomController::class, 'update'])->name('admin.asset-room.update');
                Route::delete('/destroy/{id}', [App\Http\Controllers\Admin\AssetRoomController::class, 'destroy'])->name('admin.asset-room.destroy');
            });

        });



    });
    Route::get('/blank-page', function () {
        return view('comingsoon');
    })->name('admin.blank');
});


Route::get('/getLokasiByUPTD', [App\Http\Controllers\DropdownDataController::class, 'getLokasiByUPTD']);
Route::get('/getLokasiByBidang', [App\Http\Controllers\DropdownDataController::class, 'getLokasiByBidang']);
Route::get('/getAtasanByUnit', [App\Http\Controllers\DropdownDataController::class, 'getAtasanByUnit']);

Route::get('/getPengamatByUPTD', [App\Http\Controllers\DropdownDataController::class, 'getPengamatByUPTD']);
Route::get('/getKSPPJByUPTD', [App\Http\Controllers\DropdownDataController::class, 'getKSPPJByUPTD']);
Route::get('/getMandorByUPTD', [App\Http\Controllers\DropdownDataController::class, 'getMandorByUPTD']);

Route::get('/getPengamatByKSPPJ', [App\Http\Controllers\DropdownDataController::class, 'getPengamatByKSPPJ']);
Route::get('/getMandorByKSPPJ', [App\Http\Controllers\DropdownDataController::class, 'getMandorByKSPPJ']);
Route::get('/getMandorByPengamat', [App\Http\Controllers\DropdownDataController::class, 'getMandorByPengamat']);
