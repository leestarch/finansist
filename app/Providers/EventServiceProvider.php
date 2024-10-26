<?php

namespace App\Providers;

use App\Events\ConsignmentSubmitted;
use App\Events\OrderAccepted;
use App\Events\ReserveForOrderFound;
use App\Events\SupplySubmitted;
use App\Listeners\SendConsignmentToCourier;
use App\Listeners\SendOrderMsgToClerk;
use App\Listeners\SendTelegramMsg;
use App\Listeners\SendSupplyToCourier;
use App\Models\Price;
use App\Observers\PriceObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
    }
}
