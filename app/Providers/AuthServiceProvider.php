<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;
use App\User;
use App\Services\Authorization\PermissionList;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('has-access', function ($user, $permission) {

            $permissionList = new PermissionList($user);

            $permissions = $permissionList->getPermissionList();
            
            return in_array($permission, $permissions);
        });

        //
        Route::group(['middleware' => 'cors'], function () {
            Passport::routes();
        });

        Passport::tokensExpireIn(now()->addHours(10));
    }
}
