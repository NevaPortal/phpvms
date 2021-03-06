<?php

namespace Modules\Vacentral\Providers;

use App\Events\PirepAccepted;
use Modules\Vacentral\Listeners\PirepAcceptedEventListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     */
    protected $listen = [
        PirepAccepted::class => [PirepAcceptedEventListener::class],
    ];
}
