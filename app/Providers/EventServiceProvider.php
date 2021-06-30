<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Listeners\LoadUserRolesToSession;
use App\Listeners\GrantAdminToUser;

use App\Events\FinishedProccess;
use App\Listeners\DispatchOrderOnBroker;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            LoadUserRolesToSession::class,
        ],
        Registered::class => [
            GrantAdminToUser::class,
        ],
        FinishedProccess::class => [
            DispatchOrderOnBroker::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
