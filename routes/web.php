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
        Route::prefix('brand')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\BrandController::class, 'index'])->name('admin.asset-brand.index');
                Route::get('/create', [App\Http\Controllers\Admin\BrandController::class, 'create'])->name('admin.asset-brand.create');
                Route::post('/store', [App\Http\Controllers\Admin\BrandController::class, 'store'])->name('admin.asset-brand.store');
                Route::get('/edit/{slug}', [App\Http\Controllers\Admin\BrandController::class, 'edit'])->name('admin.asset-brand.edit');
                Route::put('/update/{id}', [App\Http\Controllers\Admin\BrandController::class, 'update'])->name('admin.asset-brand.update');
                Route::delete('/destroy/{id}', [App\Http\Controllers\Admin\BrandController::class, 'destroy'])->name('admin.asset-brand.destroy');
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
                Route::get('/', [App\Http\Controllers\Admin\RoomController::class, 'index'])->name('admin.asset-room.index');
                Route::get('/create', [App\Http\Controllers\Admin\RoomController::class, 'create'])->name('admin.asset-room.create');
                Route::post('/store', [App\Http\Controllers\Admin\RoomController::class, 'store'])->name('admin.asset-room.store');
                Route::get('/edit/{slug}', [App\Http\Controllers\Admin\RoomController::class, 'edit'])->name('admin.asset-room.edit');
                Route::put('/update/{id}', [App\Http\Controllers\Admin\RoomController::class, 'update'])->name('admin.asset-room.update');
                Route::delete('/destroy/{id}', [App\Http\Controllers\Admin\RoomController::class, 'destroy'])->name('admin.asset-room.destroy');
            });

            
            Route::prefix('building')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\BuildingController::class, 'index'])->name('admin.building.index');
                Route::get('/create', [App\Http\Controllers\Admin\BuildingController::class, 'create'])->name('admin.asset-building.create');
                Route::post('/store', [App\Http\Controllers\Admin\BuildingController::class, 'store'])->name('admin.asset-building.store');
                Route::get('/edit/{slug}', [App\Http\Controllers\Admin\BuildingController::class, 'edit'])->name('admin.asset-building.edit');
                Route::put('/update/{id}', [App\Http\Controllers\Admin\BuildingController::class, 'update'])->name('admin.asset-building.update');
                Route::delete('/destroy/{id}', [App\Http\Controllers\Admin\BuildingController::class, 'destroy'])->name('admin.asset-building.destroy');
            });

<<<<<<< HEAD
           
            Route::prefix('realization')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\AssetRealizationsController::class, 'index'])->name('admin.asset-realization.index');
                Route::get('/create', [App\Http\Controllers\Admin\AssetRealizationsController::class, 'create'])->name('admin.asset-realization.create');
                Route::post('/store', [App\Http\Controllers\Admin\AssetRealizationsController::class, 'store'])->name('admin.asset-realization.store');
                Route::get('/edit/{slug}', [App\Http\Controllers\Admin\AssetRealizationsController::class, 'edit'])->name('admin.asset-realization.edit');
                Route::put('/update/{id}', [App\Http\Controllers\Admin\AssetRealizationsController::class, 'update'])->name('admin.asset-realization.update');
                Route::delete('/destroy/{id}', [App\Http\Controllers\Admin\AssetRealizationsController::class, 'destroy'])->name('admin.asset-realization.destroy');
            });
=======
            Route::prefix('asset-material')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\AssetMaterialController::class, 'index'])->name('admin.asset-material.index');
                Route::get('/create', [App\Http\Controllers\Admin\AssetMaterialController::class, 'create'])->name('admin.asset-material.create');
                Route::post('/store', [App\Http\Controllers\Admin\AssetMaterialController::class, 'store'])->name('admin.asset-material.store');
                Route::get('/edit/{slug}', [App\Http\Controllers\Admin\AssetMaterialController::class, 'edit'])->name('admin.asset-material.edit');
                Route::put('/update/{id}', [App\Http\Controllers\Admin\AssetMaterialController::class, 'update'])->name('admin.asset-material.update');
                Route::delete('/destroy/{id}', [App\Http\Controllers\Admin\AssetMaterialController::class, 'destroy'])->name('admin.asset-material.destroy');
            });
            Route::prefix('asset-detail')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\AssetDetailController::class, 'index'])->name('admin.asset-detail.index');
                Route::get('/create', [App\Http\Controllers\Admin\AssetDetailController::class, 'create'])->name('admin.asset-detail.create');
                Route::post('/store', [App\Http\Controllers\Admin\AssetDetailController::class, 'store'])->name('admin.asset-detail.store');
                Route::get('/edit/{slug}', [App\Http\Controllers\Admin\AssetDetailController::class, 'edit'])->name('admin.asset-detail.edit');
                Route::put('/update/{id}', [App\Http\Controllers\Admin\AssetDetailController::class, 'update'])->name('admin.asset-detail.update');
                Route::delete('/destroy/{id}', [App\Http\Controllers\Admin\AssetDetailController::class, 'destroy'])->name('admin.asset-detail.destroy');
            });


>>>>>>> 92d03adca5be3b0cff380d89682beca8e018300a
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
