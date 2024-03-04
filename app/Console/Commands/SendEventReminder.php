<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Str;

class SendEventReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $events = \App\Models\Event::with('attendees.user')
        ->whereBetween('start_time' , [now() , now()->addDay()])->get();
        $eventCount = $events->count();
        $eventLable = Str::plural('event', $eventCount);
        $this->info("found {$eventCount}{$eventLable}");


        $events->each(
            fn($event) => $event->attendees->each(
                fn($attendee) => $this->info("Notification user {$attendee->user->id}")
            )
        );

       $this->info('Reminder notification send successfully');
    }
}
