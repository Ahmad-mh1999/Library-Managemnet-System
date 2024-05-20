<?php

namespace App\Listeners;

use App\Events\BookAdded;
use App\Models\Notification;
use App\Jobs\SendEmailToAllUsers;
use App\Jobs\AttachNotificationTOUser;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBookAddedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookAdded $event): void
    {
        $notification = Notification::create([
            'title' => 'New Book Added',
            'message' => "A new book titled '{$event->book->title}' has been added.",
            'type' => 'book_added'
        ]);
        $content = $notification->message;
        AttachNotificationTOUser::dispatch($notification);
        SendEmailToAllUsers::dispatch($content);
    }
}
