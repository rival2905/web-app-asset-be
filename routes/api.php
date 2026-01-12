<?php

use App\Http\Controllers\API\AbsensiController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DinasLuarController;
use App\Http\Controllers\API\IzinController;
use App\Http\Controllers\API\RuasJalanController;
use App\Http\Controllers\API\UtilsController;
use App\Http\Controllers\API\RekapController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/cancel-verifikasi', [AuthController::class, 'cancelVerifikasi']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/update-password', [AuthController::class, 'updatePassword']);

    //absensi
    Route::post('/absensi', [AbsensiController::class, 'absensi']);
    Route::post('/absensi-admin/{id}', [AbsensiController::class, 'absensiFromAdmin']);

    //Dinas Luar
    Route::post('/dinas-luar', [DinasLuarController::class, 'createDinasLuar']);
    Route::get('/dinas-luar', [DinasLuarController::class, 'getDinasLuar']);
    Route::delete('/dinas-luar/{id}', [DinasLuarController::class, 'deleteDinasLuar']);
    Route::post('/approval-dinas-luar', [DinasLuarController::class, 'approvalDinasLuar']);

    //Izin
    Route::post('/izin', [IzinController::class, 'createIzin']);
    Route::get('/izin', [IzinController::class, 'getIzin']);
    Route::delete('/izin/{id}', [IzinController::class, 'deleteIzin']);
    Route::post('/approval-izin', [IzinController::class, 'approvalIzin']);


    //Utils
    Route::get('/master-lokasi-kerja', [UtilsController::class, 'getMasterLokasiKerja']);
    Route::get('/nearby-point', [UtilsController::class, 'getNearbyPoint']);
    Route::get('/rekap-absensi/{id}/{periode}', [UtilsController::class, 'rekapAbsen']);
    Route::get('/pekerja', [UtilsController::class, 'getPekerja']);
    Route::get('/reset-user/{id}', [UtilsController::class, 'resetUser']);
    Route::post('/error-logs', [UtilsController::class, 'errorLogs']);
    Route::get('/progress-bulanan', [UtilsController::class, 'progressBulanan']);
    Route::get('/send-notif/{id}', [UtilsController::class, 'sendNotif']);


    Route::get('/push-notif', [UtilsController::class, 'pushNotif']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::get('/generate-pin-photo/{id}', [UtilsController::class, 'generatePinPhoto']);
Route::get('/checkingup', [RekapController::class, 'checkus']);
