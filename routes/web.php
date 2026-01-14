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
        // Route::get('/', function () {
        //     return view('admin.dashboard.index');
        // });
        // Route::get('/dashboard', function () {
        //     return view('admin.dashboard.index');
        // })->name('admin.dashboard.index');
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index']);
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard.index');

    
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

        Route::prefix('recapitulation')->group(function () {
            Route::get('/daily', [App\Http\Controllers\Admin\RekapController::class, 'daily'])->name('admin.rekap.daily');
            Route::get('/daily/absence', [App\Http\Controllers\Admin\RekapController::class, 'daily_absence'])->name('admin.rekap.daily_absence');
            Route::get('/daily/export/{desc}', [App\Http\Controllers\Admin\RekapController::class, 'export_daily'])->name('admin.rekap.daily.export');
            Route::get('/user/{id}', [App\Http\Controllers\Admin\RekapController::class, 'user'])->name('admin.rekap.user');
            Route::get('/user-anulir/{id}', [App\Http\Controllers\Admin\RekapController::class, 'user_anulir'])->name('admin.data-rekap.user-anulir');

            Route::get('anulir/user/{user_id}/{id}', [App\Http\Controllers\Admin\RekapController::class, 'data_anulir'])->name('admin.rekap.user-anulir');
            Route::get('restore-anulir/user/{user_id}/{id}', [App\Http\Controllers\Admin\RekapController::class, 'restore_data_anulir'])->name('admin.rekap.user-restore-anulir');


            Route::get('/monthly', [App\Http\Controllers\Admin\RekapController::class, 'monthly'])->name('admin.rekap.monthly');
            Route::get('/monthly/export', [App\Http\Controllers\Admin\RekapController::class, 'export_monthly'])->name('admin.rekap.monthly.export');
            Route::get('/monthly/export/{category}', [App\Http\Controllers\Admin\RekapController::class, 'periode'])->name('admin.rekap.monthly.category');

            Route::get('/advanced', [App\Http\Controllers\Admin\RekapController::class, 'index'])->name('admin.rekap.advanced1');
            Route::get('/coming-soon', function () {
                return view('comingsoon');
            })->name('admin.rekap.advanced');
            
            Route::post('/help_presensi', [App\Http\Controllers\Admin\RekapController::class, 'help_presensi'])->name('admin.help_presensi.store');
            
        });

    });
    Route::get('/blank-page', function () {
        return view('comingsoon');
    })->name('admin.blank');
});


/**
 * data dropdown
 */
Route::get('/getLokasiByUPTD', [App\Http\Controllers\DropdownDataController::class, 'getLokasiByUPTD']);
Route::get('/getLokasiByBidang', [App\Http\Controllers\DropdownDataController::class, 'getLokasiByBidang']);
Route::get('/getAtasanByUnit', [App\Http\Controllers\DropdownDataController::class, 'getAtasanByUnit']);

Route::get('/getPengamatByUPTD', [App\Http\Controllers\DropdownDataController::class, 'getPengamatByUPTD']);
Route::get('/getKSPPJByUPTD', [App\Http\Controllers\DropdownDataController::class, 'getKSPPJByUPTD']);
Route::get('/getMandorByUPTD', [App\Http\Controllers\DropdownDataController::class, 'getMandorByUPTD']);

Route::get('/getPengamatByKSPPJ', [App\Http\Controllers\DropdownDataController::class, 'getPengamatByKSPPJ']);
Route::get('/getMandorByKSPPJ', [App\Http\Controllers\DropdownDataController::class, 'getMandorByKSPPJ']);
Route::get('/getMandorByPengamat', [App\Http\Controllers\DropdownDataController::class, 'getMandorByPengamat']);
