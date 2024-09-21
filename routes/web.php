<?php

use Botble\Base\Facades\AdminHelper;
use FriendsOfBotble\AbuseIP\Http\Controllers\Settings\AbuseIPSettingController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function () {
    Route::group(['prefix' => 'settings/abuse-ips', 'as' => 'abuse-ip.', 'permission' => 'abuse-ip.settings'], function () {
        Route::get('/', [AbuseIPSettingController::class, 'edit'])->name('settings');
        Route::put('/', [AbuseIPSettingController::class, 'update'])->name('settings.update');
    });
});
