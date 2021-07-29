<?php

declare(strict_types=1);

use App\Controller\SiteController;
use Yiisoft\Router\Route;
use Yiisoft\Http\Method;
use Yiisoft\Router\Group;
use Yiisoft\Session\SessionMiddleware;
use Middlewares\BasicAuthentication;
use Yiisoft\Auth\Middleware\Authentication;

return [
    
    Route::methods([Method::GET, Method::POST], '/login')->action([SiteController::class, 'actionLogin'])->name('site/login'),
    Route::methods([Method::GET, Method::POST], '/signup')->action([SiteController::class, 'actionSignup'])->name('site/signup'),
    
    Group::create('')
    ->middleware(Authentication::class)
    ->routes(
        
        Route::get('/')
            ->action([SiteController::class, 'index'])
            ->name('home'),
        
        Route::get('/logout')
            ->action([SiteController::class, 'actionLogout'])
            ->name('site/logout'),
        
        Route::methods([Method::GET, Method::POST], '/search')
            ->action([SiteController::class, 'actionSearch'])
            ->name('site/search'),
        
        Route::methods([Method::GET, Method::POST], '/view')
            ->action([SiteController::class, 'actionView'])
            ->name('site/view'),
        
        Route::methods([Method::GET, Method::POST], '/setPreferred')
            ->action([SiteController::class, 'actionSetPreferred'])
            ->name('site/setPreferred'),
        
        Route::methods([Method::GET, Method::POST], '/update')
            ->action([SiteController::class, 'actionUpdate'])
            ->name('site/update'),
        
        Route::methods([Method::GET, Method::POST], '/insert')
            ->action([SiteController::class, 'actionInsert'])
            ->name('site/insert'),
        
        Route::methods([Method::GET, Method::POST], '/delete')
            ->action([SiteController::class, 'actionDelete'])
            ->name('site/delete'),
        
        ),
];
