<?php

namespace App\Providers;

use App\Models\Admin\Admin;
use App\Models\Admin\Permission;
use App\Models\Admin\Role;
use App\Policies\AdminPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //'App\Models\Model' => 'App\Policies\ModelPolicy',
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
        Admin::class => AdminPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('brows_admin', fn(Admin $admin) => $admin->hasPermission('brows_admin'));
        Gate::define('administrator', fn(Admin $admin) => $admin->hasPermission('administrator'));
        Gate::define('banned', fn(Admin $admin) => $admin->hasPermission('banned'));
    }
}
