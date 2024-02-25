<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return new EventResource(collect(Event::all()));
        // return response()->json(["message" => "yes" , "test" => "true"]);

        return EventResource::collection(Event::with('user' , 'attendees')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $event = Event::create([

            ...$request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time'

            ]),
            'user_id' => 2
        ]);
        $event->load('user' , 'attendees');
        return new EventResource($event);

    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load('user','attendees');
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $event->update(
            $request->validate([
               'name' => 'required|sometimes|string|max:255',
               'description' => 'nullable|string',
               'start_time' => 'sometimes|date',
               'end_time' => 'sometimes|date'
        ])
    );
    return $event;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json([
            'maseege' => 'This Event Deleted'
        ]);
        // return response(status:204);
    }
}
