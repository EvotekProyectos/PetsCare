<?php

namespace App\Providers;

use App\Models\Area;
use App\Models\AttentionStatus;
use App\Models\Genre;
use App\Models\Reason;
use App\Models\ReceptionType;
use App\Models\ReproductiveStatus;
use App\Observers\AreaObserver;
use App\Observers\ReasonObserver;
use App\Models\Room;
use App\Models\User;
use App\Observers\AttentionStatusObserver;
use App\Observers\ReceptionTypeObserver;
use App\Observers\GenreObserver;
use App\Observers\ReproductiveStatusObserver;
use App\Observers\RoomObserver;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Reason::observe(ReasonObserver::class);
        Area::observe(AreaObserver::class);
        Room::observe(RoomObserver::class);
        AttentionStatus::observe(AttentionStatusObserver::class);
        ReceptionType::observe(ReceptionTypeObserver::class);
        Genre::observe(GenreObserver::class);
        ReproductiveStatus::observe(ReproductiveStatusObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
