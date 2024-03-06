<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    use CanLoadRelationships;

    private array $relations = ['user'];

    public function __construct()
    {
       $this->middleware('auth:sanctum')->except(['index','show','update']);
      $this->authorizeResource(Attendee::class ,'attendee');
      $this->middleware('throttle:api')->only(['store' , 'destroy']);

    }


    public function index(Event $event)
    {

    //  $attendees = $event->attendees()->latest();
    $attendees = $this->loadRelationships($event->attendees()->latest());

     return AttendeeResource::collection(
        $attendees->paginate()
     );

    }

    public function store(Request $request , Event $event)
    {
        $attendees = $event->attendees()->create([
                  'user_id' => 1
        ]);
        return new AttendeeResource($attendees);
    }

    public function show(Event $event , Attendee $attendee)
    {
        return new AttendeeResource($this->loadRelationships($attendee));
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(Event $event , Attendee $attendee)
    {
        $this->authorize('delete-attendee' , [$event , $attendee]);

         $attendee->delete();
        return   response()->json([
            'massege' => 'attendee is deleted'
        ]);
    }
}
