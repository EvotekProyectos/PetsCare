<?php

namespace App\Providers;

use App\Models\AdmissionType;
use App\Models\AdvancePayment;
use App\Models\Appointment;
use App\Models\Area;
use App\Models\AttentionStatus;
use App\Models\CmType;
use App\Models\ControlDate;
use App\Models\CoverArea;
use App\Models\Cremation;
use App\Models\FamClassification;
use App\Models\Family;
use App\Models\FollowupIntern;
use App\Models\FollowupsCritic;
use App\Models\FollowupSurgical;
use App\Models\Format;
use App\Models\FormatType;
use App\Models\Genre;
use App\Models\Grooming;
use App\Models\GroomingStatus;
use App\Models\GroomingStatusHistory;
use App\Models\Hospitalization;
use App\Models\Hotel;
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
use App\Models\Service;
use App\Models\Shift;
use App\Models\Surgery;
use App\Models\SurgerySchedule;
use App\Models\TagType;
use App\Models\User;
use App\Models\VaccineCertificate;
use App\Models\Voucher;
use App\Observers\AdmissionTypeObserver;
use App\Observers\AdvancePaymentObserver;
use App\Observers\AppointmentObserver;
use App\Observers\AttentionStatusObserver;
use App\Observers\CmTypeObserver;
use App\Observers\ControlDateObserver;
use App\Observers\CoverAreaObserver;
use App\Observers\CremationObserver;
use App\Observers\FamClassificationObserver;
use App\Observers\FamilyObserver;
use App\Observers\FollowupInternObserver;
use App\Observers\FollowupsCriticObserver;
use App\Observers\FollowupSurgicalObserver;
use App\Observers\FormatTypesObserver;
use App\Observers\FormatsObserver;
use App\Observers\ReceptionTypeObserver;
use App\Observers\GenreObserver;
use App\Observers\GroomingObserver;
use App\Observers\GroomingStatusHistoryObserver;
use App\Observers\GroomingStatusObserver;
use App\Observers\HospitalizationObserver;
use App\Observers\HotelObserver;
use App\Observers\PetClassificationObserver;
use App\Observers\PetObserver;
use App\Observers\PetsStatusObserver;
use App\Observers\prescriptionsObserver;
use App\Observers\ReceptionObserver;
use App\Observers\receptionStatusHistoryObserver;
use App\Observers\ReproductiveStatusObserver;
use App\Observers\RoomObserver;
use App\Observers\ScheduleObserver;
use App\Observers\ServiceObserver;
use App\Observers\ShiftObserver;
use App\Observers\SurgeryObserver;
use App\Observers\SurgeryPackObserver;
use App\Observers\SurgeryScheduleObserver;
use App\Observers\TagTypeObserver;
use App\Observers\UserObserver;
use App\Observers\VaccineCertificateObserver;
use App\Observers\VoucherObserver;
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
        Appointment::observe(AppointmentObserver::class);
        Prescription::observe(prescriptionsObserver::class);
        Service::observe(ServiceObserver::class);
        VaccineCertificate::observe(VaccineCertificateObserver::class);
        Hospitalization::observe(HospitalizationObserver::class);
        Surgery::observe(SurgeryObserver::class);
        FormatType::observe(FormatTypesObserver::class);
        Format::observe(FormatsObserver::class);
        FollowupIntern::observe(FollowupInternObserver::class);
        FollowupSurgical::observe(FollowupSurgicalObserver::class);
        FollowupsCritic::observe(FollowupsCriticObserver::class);
        GroomingStatus::observe(GroomingStatusObserver::class);
        GroomingStatusHistory::observe(GroomingStatusHistoryObserver::class);
        Grooming::observe(GroomingObserver::class);
        CmType::observe(CmTypeObserver::class);
        TagType::observe(TagTypeObserver::class);
        Cremation::observe(CremationObserver::class);
        SurgerySchedule::observe(SurgeryScheduleObserver::class);
        Hotel::observe(HotelObserver::class);
        ControlDate::observe(ControlDateObserver::class);
        AdvancePayment::observe(AdvancePaymentObserver::class);
        Voucher::observe(VoucherObserver::class);
        
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
