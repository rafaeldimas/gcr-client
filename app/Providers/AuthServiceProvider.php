<?php

namespace Gcr\Providers;

use Gcr\Policies\ProcessPolicy;
use Gcr\Process;
use Gcr\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Process::class => ProcessPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function (User $user, $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });

        Gate::define('admin', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('edit-process', function (User $user, Process $process) {
            return $user->isAdmin() || $process->isEditing();
        });
    }
}
