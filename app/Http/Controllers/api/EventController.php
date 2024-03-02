<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{

    use CanLoadRelationships;

    private array $relations = ['user', 'attendees', 'attendees.user'];

   public function __construct()
   {
     $this->middleware('auth:sanctum')->except(['index','show']);
   }

    public function index()
    {
        // return new EventResource(collect(Event::all()));
        // return response()->json(["message" => "yes" , "test" => "true"]);
        //    dd($this->includefunctionRelation('user'));
        // $query = Event::query();
        // $relations = ['user','attendees','attendees.user'];
        // foreach($relations as $relation){
        //     $query->when(
        //         $this->includefunctionRelation($relation),
        //         fn($q)=>$q->with($relation)
        //     );
        // }
        // return EventResource::collection(Event::with('user' , 'attendees')->paginate());
        $query = $this->loadRelationships(Event::query());
        return EventResource::collection(

            $query->latest()->paginate()

        );

    }

    protected function includefunctionRelation(string $relation) : bool {

        $include = request()->query('include');
        if(!$include){
           return  false;
        }
        $relations = array_map('trim',explode(',',$include) ) ;

        return in_array($relation , $relations);
        // dd($relations);

    }

    public function store(Request $request)
    {

        $event = Event::create([

            ...$request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time'

            ]),

            'user_id' => $request->user()->id

        ]);

        // $event->load('user' , 'attendees');
        return new EventResource($this->loadRelationships($event));

    }

    public function show(Event $event)
    {

        // $event->load('user','attendees');
        // return new EventResource($this->includefunctionRelation($event));
        return new EventResource(
            $this->loadRelationships($event)
        );

    }


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

      return new EventResource($this->loadRelationships($event));

    }

    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json([
            'maseege' => 'This Event Deleted'
        ]);
        // return response(status:204);
    }
}
