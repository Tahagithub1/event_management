<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Attendee;
use App\Models\Event;
use App\Policies\EventPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Event::class => EventPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Gate::define('update-event' , function($user,Event $event){
        //     return $user->id === $event->user_id;
        // });
        // Gate::define('delete-attendee',function($user , Event $event , Attendee $attendee){
        //      return $user->id === $attendee->user_id ||
        //      $user->id === $event->user_id;
        // });
    }
}
