<?php

namespace App\Listeners;

use App\Events\BookReturned;
use App\Jobs\AttachNotificationTOUser;
use App\Models\Notification;
use App\Jobs\SendEmailToAllUsers;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBookReturnedNotification
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
    public function handle(BookReturned $event): void
    {
        $books = $event->books;

        $message = "The following books have been returned:\n";

        foreach ($books as $book) {
            $message .= " - {$book}\n";
        }
        $notification = Notification::create([
            'title' => 'New Books returned',
            'message' => $message,
            'type' => 'book_returned'
        ]);
        AttachNotificationTOUser::dispatch($notification);
        SendEmailToAllUsers::dispatch($message);
    }
}
