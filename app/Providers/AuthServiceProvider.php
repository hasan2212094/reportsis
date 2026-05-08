<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

       Gate::define('financeAdmin', function ($user) {
        return $user->hasRole('admin_finance');
        });

        Gate::define('engineeringAdmin', function ($user) {
        return $user->hasRole('admin_engineering');
         });

       Gate::define('superAdmin', function ($user) {
        return $user->hasRole('super_admin');
         });
        //
    }
}
