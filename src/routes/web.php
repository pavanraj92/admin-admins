<?php

use Illuminate\Support\Facades\Route;
use admin\admins\Controllers\AdminManagerController;

Route::name('admin.')->middleware(['web','admin.auth'])->group(function () {  
    Route::resource('admins', AdminManagerController::class);
    Route::post('admins/updateStatus', [AdminManagerController::class, 'updateStatus'])->name('admins.updateStatus');
});
