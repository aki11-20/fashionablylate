<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use App\Http\Requests\LoginRequest as CustomLoginRequest;
use Laravel\Fortify\Contracts\LoginResponse;
use App\Http\Responses\CustomLoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use App\Http\Responses\CustomRegisterResponse;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use App\Http\Responses\CustomLogoutResponse;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(FortifyLoginRequest::class, CustomLoginRequest::class);
        $this->app->singleton(LoginResponse::class,CustomLoginResponse::class);

        $this->app->singleton(RegisterResponse::class, CustomRegisterResponse::class);

        $this->app->singleton(LogoutResponseContract::class, CustomLogoutResponse::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}

