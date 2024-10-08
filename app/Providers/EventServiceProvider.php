<?php

namespace App\Providers;

use App\Models\AdmissionType;
use App\Models\Area;
use App\Models\AttentionStatus;
use App\Models\CoverArea;
use App\Models\FamClassification;
use App\Models\Family;
use App\Models\Genre;
use App\Models\Pet;
use App\Models\PetClassification;
use App\Models\PetsStatus;
use App\Models\Prescription;
use App\Models\Reason;
use App\Models\Reception;
use App\Models\ReceptionStatusHistory;
use App\Models\ReceptionType;
use App\Models\ReproductiveStatus;
use App\Observers\AreaObserver;
use App\Observers\ReasonObserver;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\User;
use App\Observers\AdmissionTypeObserver;
use App\Observers\AttentionStatusObserver;
use App\Observers\CoverAreaObserver;
use App\Observers\FamClassificationObserver;
use App\Observers\FamilyObserver;
use App\Observers\ReceptionTypeObserver;
use App\Observers\GenreObserver;
use App\Observers\PetClassificationObserver;
use App\Observers\PetObserver;
use App\Observers\PetsStatusObserver;
use App\Observers\prescriptionsObserver;
use App\Observers\ReceptionObserver;
use App\Observers\receptionStatusHistoryObserver;
use App\Observers\ReproductiveStatusObserver;
use App\Observers\RoomObserver;
use App\Observers\ScheduleObserver;
use App\Observers\ShiftObserver;
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
        AdmissionType::observe(AdmissionTypeObserver::class);
        FamClassification::observe(FamClassificationObserver::class);
        PetClassification::observe(PetClassificationObserver::class);
        Shift::observe(ShiftObserver::class);
        PetsStatus::observe(PetsStatusObserver::class);
        Family::observe(FamilyObserver::class);
        Pet::observe(PetObserver::class);
        CoverArea::observe(CoverAreaObserver::class);
        Schedule::observe(ScheduleObserver::class);
        Reception::observe(ReceptionObserver::class);
        ReceptionStatusHistory::observe(receptionStatusHistoryObserver::class);
        Prescription::observe(prescriptionsObserver::class);
        
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
