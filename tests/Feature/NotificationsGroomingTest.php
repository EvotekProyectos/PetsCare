<?php

namespace Tests\Feature;

use App\Models\GroomingStatusHistory;
use App\Models\User;
use App\Notifications\GroomingStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationsGroomingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
       
    }
    
    public function test_send_notification_status_changed(): void
    {
       
        // Notification::fake();

        // $user = User::factory()->create();

        // $groomingStatusHistory = GroomingStatusHistory::factory()->create();

        // $user->notify(new GroomingStatus($groomingStatusHistory));

        // Notification::assertSentTo(
        //     $user,
        //     GroomingStatus::class,
        //     function ($notification, $channels) use ($groomingStatusHistory, $user) {
        //         return in_array('database', $channels) && 
        //                $notification->toDatabase($user)['reception_id'] === $groomingStatusHistory->reception_id;
        //     }
        // );
    }
}
