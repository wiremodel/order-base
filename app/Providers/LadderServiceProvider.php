<?php

namespace App\Providers;

use App\Enums\Role;
use Illuminate\Support\ServiceProvider;
use Ladder\Ladder;

class LadderServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Ladder::role(Role::Admin->value, 'Administrator', [
            '*',
        ])->description('Administrator users can perform any action.');

        Ladder::role(Role::DiningRoom->value, 'Dining Room Area', [
            'orders:*',
            'kitchen:read',
        ])->description('Editor users have the ability to read, create, and update.');
    }
}
