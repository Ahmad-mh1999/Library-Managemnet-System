<?php

namespace App\Listeners;

use App\Events\BookBorrowed;
use App\Models\Notification;
use App\Jobs\SendEmailToAllUsers;
use App\Jobs\AttachNotificationTOUser;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBookBorrowedNotification
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
    public function handle(BookBorrowed $event): void
    {
        $books = $event->books;

        $message = "The following books have been borrowed:\n";

        foreach ($books as $book) {
            $message .= " - {$book}\n";
        }
        
        $notification = Notification::create([
            'title' => 'New Books borrowed',
            'message' => $message,
            'type' => 'book_borrowed'
        ]);
        AttachNotificationTOUser::dispatch($notification);
        SendEmailToAllUsers::dispatch($message);
    }
}
