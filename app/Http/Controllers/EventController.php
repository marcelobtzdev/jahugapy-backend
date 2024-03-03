<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\EventRequest;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::with([
            'eventTeams' => [
                'team.members.user'
            ]
        ])->get();

        return $events;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  EventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return $event;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EventRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        
    }
}
