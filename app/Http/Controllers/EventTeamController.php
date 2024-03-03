<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventTeamRequest;
use App\Models\Event;

class EventTeamController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  EventTeamRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventTeamRequest $request, Event $event)
    {
        $event->eventTeams()->create($request->validated());

        $event->load('eventTeams.team.members.user');

        return $event;
    }
}
