<?php

namespace App\Http\Controllers\Api;

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

    //check if user is authenticated
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show', 'update']);
        //$this->middleware('throttle:60,1')->only(['store', 'destroy']);
        $this->middleware('throttle:api')->only(['store', 'update', 'destroy']);
        $this->authorizeResource(AttendeeResource::class, 'attendee');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        //
        $attendees = $this->loadRelationships(
            $event->attendees()->latest(),
            $this->relations
        );
        
        return AttendeeResource::collection(
            $attendees->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        //

        $attendee = $this->loadRelationships(
            $event->attendees()->create([
                'user_id' => $request->user()->id
            ])
        );
        
        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        //
        return new AttendeeResource($this->loadRelationships($attendee, $this->relations));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        //giving the attendee to delete himself
        // $this->authorize('delete-attendee', [$event, $attendee]);

        $attendee->delete();
        return response(status:204);
    }
}
