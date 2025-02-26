<?php

use App\Http\Controllers\AisDataController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\FotoKlinikController;
use App\Http\Controllers\FotoPerpustakaanController;
use App\Http\Controllers\FotoRwController;
use App\Http\Controllers\FotoSambangController;
use App\Http\Controllers\FotoSapuController;
use App\Http\Controllers\GakkumController;
use App\Http\Controllers\GraphicController;
use App\Http\Controllers\KiaController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PoldaController;
use App\Http\Controllers\ReportKlinikController;
use App\Http\Controllers\ReportPerpustakaanController;
use App\Http\Controllers\ReportRwController;
use App\Http\Controllers\ReportSambangController;
use App\Http\Controllers\ReportSapuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TargetPUController;
use App\Http\Controllers\UsersController;
use App\Models\report_sapu;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GlobalsController;
use Inertia\Inertia;

Route::middleware('guest')->group(function () {
//    Route::get('register', [RegisteredUserController::class, 'create'])
//                ->name('register');
//
//    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

//    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
//                ->name('password.request');
//
//    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
//                ->name('password.email');
//
//    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
//                ->name('password.reset');
//
//    Route::post('reset-password', [NewPasswordController::class, 'store'])
//                ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

//    pages
    Route::get('/sambang', function () {
        return Inertia::render('Report/SambangNusa');
    })->name('sambang');
    Route::get('/sapu', function () {
        return Inertia::render('Report/SapuBersih');
    })->name('sapu');
    Route::get('/polisiRw', function () {
        return Inertia::render('Report/PolisiRW');
    })->name('polisiRw');
    Route::get('/perpustakaan', function () {
        return Inertia::render('Report/Perpustakaan');
    })->name('perpustakaan');
    Route::get('/klinik', function () {
        return Inertia::render('Report/Klinik');
    })->name('klinik');
    Route::get('/setting/lokasi', function () {
        return Inertia::render('Setting/Lokasi');
    })->name('setting-lokasi');
    Route::get('/setting/polda', function () {
        return Inertia::render('Setting/Polda');
    })->name('setting-polda');
    Route::get('/setting/role', function () {
        return Inertia::render('Setting/Role');
    })->name('setting-role');
    Route::get('/setting/user', function () {
        return Inertia::render('Setting/User');
    })->name('setting-user');
    Route::get('/setting/target', function () {
        return Inertia::render('Setting/TargetPU');
    })->name('setting-target');
    Route::get('/patroli/gakkum', function () {
        return Inertia::render('Report/Gakkum');
    })->name('gakkum');
    Route::get('/patroli/kia', function () {
        return Inertia::render('Report/Kia');
    })->name('kia');
    Route::get('/patroli/global', function () {
        return Inertia::render('Global/Global');
    })->name('polisi-global');
//    end pages

//    api
    Route::prefix('api')->group(function () {

//        Target Program Unggulan
        Route::get('target/tahun', [TargetPUController::class, 'getModelByTahun']);
        Route::get('target/page', [TargetPUController::class, 'getModelPage']);
        Route::delete('target/destroy/{id}', [TargetPUController::class, 'destroy']);
        Route::post('target/create', [TargetPUController::class, 'createUpdate']);

//        role
        Route::get('role/all', [RoleController::class, 'getRole']);
        Route::get('role/find/{id}', [RoleController::class, 'getRoleById']);
        Route::post('role/create', [RoleController::class, 'createUpdate']);
        Route::get('role/page', [RoleController::class, 'getRolePage']);
        Route::delete('role/destroy/{id}', [RoleController::class, 'destroy']);

//        lokasi
        Route::get('lokasi/all', [LokasiController::class, 'getLokasi']);
        Route::get('lokasi/find/{id}', [LokasiController::class, 'getLokasiById']);
        Route::post('lokasi/create', [LokasiController::class, 'createUpdate']);
        Route::get('lokasi/page', [LokasiController::class, 'getLokasiPage']);
        Route::delete('lokasi/destroy/{id}', [LokasiController::class, 'destroy']);

//        user
        Route::get('user/all', [UsersController::class, 'getUser']);
        Route::get('user/find/{id}', [UsersController::class, 'getUserById']);
        Route::post('user/create', [UsersController::class, 'createUpdate']);
        Route::get('user/page', [UsersController::class, 'getUserPage']);
        Route::delete('user/destroy/{id}', [UsersController::class, 'destroy']);

//        polda
        Route::get('polda/all', [PoldaController::class, 'getPolda']);
        Route::get('polda/find/{id}', [PoldaController::class, 'getPoldaById']);
        Route::post('polda/create', [PoldaController::class, 'createUpdate']);
        Route::get('polda/page', [PoldaController::class, 'getPoldaPage']);
        Route::delete('polda/destroy/{id}', [PoldaController::class, 'destroy']);

//        Report Sambang
        Route::get('report/sambang/all', [ReportSambangController::class, 'getSambang']);
        Route::get('report/sambang/find/{id}', [ReportSambangController::class, 'getSambangById']);
        Route::post('report/sambang/create', [ReportSambangController::class, 'createUpdate']);
        Route::get('report/sambang/binaan/page', [ReportSambangController::class, 'getSambangBinaanPage']);
        Route::get('report/sambang/sentuhan/page', [ReportSambangController::class, 'getSambangSentuhanPage']);
        Route::get('report/sambang/pantauan/page', [ReportSambangController::class, 'getSambangPantauanPage']);
        Route::delete('report/sambang/destroy/{id}', [ReportSambangController::class, 'destroy']);
        Route::get('report/sambang/foto/{id}', [FotoSambangController::class, 'getFotoById']);
        Route::get('report/sambang/download', [ReportSambangController::class, 'download']);
        Route::post('report/sambang/foto', [FotoSambangController::class, 'createUpdate']);
        Route::delete('report/sambang/foto/{id}/{urutan}', [FotoSambangController::class, 'destroy']);
        Route::get('report/sambang/download/check', [ReportSambangController::class, 'checkCount']);

//        Report Sapu
        Route::get('report/sapu/all', [ReportSapuController::class, 'getSapu']);
        Route::get('report/sapu/find/{id}', [ReportSapuController::class, 'getSapuById']);
        Route::post('report/sapu/create', [ReportSapuController::class, 'createUpdate']);
        Route::get('report/sapu/page', [ReportSapuController::class, 'getSapuPage']);
        Route::delete('report/sapu/destroy/{id}', [ReportSapuController::class, 'destroy']);
        Route::get('report/sapu/foto/{id}', [FotoSapuController::class, 'getFotoById']);
        Route::get('report/sapu/download', [ReportSapuController::class, 'download']);
        Route::post('report/sapu/foto', [FotoSapuController::class, 'createUpdate']);
        Route::delete('report/sapu/foto/{id}/{urutan}', [FotoSapuController::class, 'destroy']);
        Route::get('report/sapu/download/check', [ReportSapuController::class, 'checkCount']);

//        Report Perpustakaan
        Route::get('report/perpustakaan/all', [ReportPerpustakaanController::class, 'getPerpustakaan']);
        Route::get('report/perpustakaan/find/{id}', [ReportPerpustakaanController::class, 'getPerpustakaanById']);
        Route::post('report/perpustakaan/create', [ReportPerpustakaanController::class, 'createUpdate']);
        Route::get('report/perpustakaan/page', [ReportPerpustakaanController::class, 'getPerpustakaanPage']);
        Route::delete('report/perpustakaan/destroy/{id}', [ReportPerpustakaanController::class, 'destroy']);
        Route::get('report/perpustakaan/foto/{id}', [FotoPerpustakaanController::class, 'getFotoById']);
        Route::get('report/perpustakaan/download', [ReportPerpustakaanController::class, 'download']);
        Route::post('report/perpustakaan/foto', [FotoPerpustakaanController::class, 'createUpdate']);
        Route::delete('report/perpustakaan/foto/{id}/{urutan}', [FotoPerpustakaanController::class, 'destroy']);
        Route::get('report/perpustakaan/download/check', [ReportPerpustakaanController::class, 'checkCount']);

//        Report Klinik
        Route::get('report/klinik/all', [ReportKlinikController::class, 'getKlinik']);
        Route::get('report/klinik/find/{id}', [ReportKlinikController::class, 'getKlinikById']);
        Route::post('report/klinik/create', [ReportKlinikController::class, 'createUpdate']);
        Route::get('report/klinik/page', [ReportKlinikController::class, 'getKlinikPage']);
        Route::delete('report/klinik/destroy/{id}', [ReportKlinikController::class, 'destroy']);
        Route::get('report/klinik/foto/{id}', [FotoKlinikController::class, 'getFotoById']);
        Route::get('report/klinik/download', [ReportKlinikController::class, 'download']);
        Route::post('report/klinik/foto', [FotoKlinikController::class, 'createUpdate']);
        Route::delete('report/klinik/foto/{id}/{urutan}', [FotoKlinikController::class, 'destroy']);
        Route::get('report/klinik/download/check', [ReportKlinikController::class, 'checkCount']);

//        Report Polisi Rw
        Route::get('report/rw/all', [ReportRwController::class, 'getRw']);
        Route::get('report/rw/find/{id}', [ReportRwController::class, 'getRwById']);
        Route::post('report/rw/create', [ReportRwController::class, 'createUpdate']);
        Route::get('report/rw/page', [ReportRwController::class, 'getRwPage']);
        Route::delete('report/rw/destroy/{id}', [ReportRwController::class, 'destroy']);
        Route::get('report/rw/foto/{id}', [FotoRwController::class, 'getFotoById']);
        Route::get('report/rw/download', [ReportRwController::class, 'download']);
        Route::post('report/rw/foto', [FotoRwController::class, 'createUpdate']);
        Route::delete('report/rw/foto/{id}/{urutan}', [FotoRwController::class, 'destroy']);
        Route::get('report/rw/download/check', [ReportRwController::class, 'checkCount']);

//        graphic
        Route::get('/graphic/giat', [GraphicController::class, 'giatGraphicByTahunAndLokasi']);
        Route::get('/graphic/sampah', [GraphicController::class, 'sampahGraphicByTahunAndPolda']);
        Route::get('/graphic/klinik', [GraphicController::class, 'klinikGraphicByTahunAndPolda']);
        Route::get('/graphic/perpustakaan', [GraphicController::class, 'perpustakaanGraphicByTahunAndPolda']);
        Route::get('map/gempa', [MapController::class, 'gempa']);
        Route::get('map/inflow/{baseRun}/{dTime}/{depth}', [MapController::class, 'inFlow']);
        Route::get('map/inaWaves/{baseRun}/{dTime}', [MapController::class, 'inaWaves']);
        Route::get('graphic/program/polda', [GraphicController::class, 'programUnggulanPerPolda']);

//      Gakkum
        Route::get('gakkum/page', [GakkumController::class, 'page']);
        Route::post('gakkum/create', [GakkumController::class, 'createUpdate']);
        Route::delete('gakkum/destroy/{id}', [GakkumController::class, 'destroy']);
        Route::get('gakkum/download', [GakkumController::class, 'download']);

//      Kia
        Route::get('kia/page', [KiaController::class, 'page']);
        Route::post('kia/create', [KiaController::class, 'createUpdate']);
        Route::delete('kia/destroy/{id}', [KiaController::class, 'destroy']);
        Route::get('kia/download', [KiaController::class, 'download']);


        // Global
        Route::get('global/all', [GlobalsController::class, 'getGlobal']);
        Route::get('global/page', [GlobalsController::class, 'getPage']);
        Route::post('global/create', [GlobalsController::class, 'createUpdate']);
        Route::get('global/find/{id}', [GlobalsController::class, 'getById']);
        Route::delete('global/destroy/{id}', [GlobalsController::class, 'destroy']);
        Route::get('global/download', [GlobalsController::class, 'download']);

    });
});
