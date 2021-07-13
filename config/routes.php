<?php

declare(strict_types=1);

use App\Controller\SiteController;
use Yiisoft\Router\Route;
use Yiisoft\Http\Method;

return [
    Route::get('/')->action([SiteController::class, 'index'])->name('home'),
    Route::methods([Method::GET, Method::POST], '/login')->action([SiteController::class, 'actionLogin'])->name('site/login'),
    Route::get('/logout')->action([SiteController::class, 'actionLogout'])->name('site/logout'),
    Route::get(''),
];
