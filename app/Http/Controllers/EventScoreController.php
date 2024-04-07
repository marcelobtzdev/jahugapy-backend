<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventScoreRequest;
use App\Models\Event;
use App\Models\EventScore;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class EventScoreController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  EventScoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventScoreRequest $request, Event $event)
    {
        $eventScore = $event->eventScores()->create($request->safe()->except(['kills_image', 'position_image']));

        $killsImage = base64_decode($request->kills_image);
        $killsImageName = 'kills_image_'.$event->id.'-'.$request->team_id.'-'.$request->date_number.'-'.$request->match_number;
        $positionImage = base64_decode($request->position_image);
        $positionImageName = 'position_image_'.$event->id.'-'.$request->team_id.'-'.$request->date_number.'-'.$request->match_number;

        Storage::put('public/'.$killsImageName.'.png', $killsImage);
        Storage::put('public/'.$positionImageName.'.png', $positionImage);

        $eventScore->kills_image = $killsImageName;
        $eventScore->position_image = $positionImageName;
        $eventScore->save();

        $event->load('eventScores.team', 'eventTeams.team.members.user');

        return $event;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EventScoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(EventScoreRequest $request, Event $event, EventScore $score)
    {
        $event->eventScores()->findOrFail($score->id)->update($request->safe()->except(['kills_image', 'position_image']));

        $killsImage = base64_decode($request->kills_image);
        $killsImageName = 'kills_image_'.$event->id.'-'.$request->team_id.'-'.$request->date_number.'-'.$request->match_number;
        $positionImage = base64_decode($request->position_image);
        $positionImageName = 'position_image_'.$event->id.'-'.$request->team_id.'-'.$request->date_number.'-'.$request->match_number;

        Storage::put('public/'.$killsImageName.'.png', $killsImage);
        Storage::put('public/'.$positionImageName.'.png', $positionImage);

        $score->kills_image = $killsImageName;
        $score->position_image = $positionImageName;
        $score->save();

        $event->load('eventScores.team', 'eventTeams.team.members.user');

        return $event;
    }

    public function scores(Request $request, Event $event) {
        $date = $request->date;

        $eventScores = EventScore::with('team.members')
                ->where('event_id', $event->id)
                ->where('date_number', $date)
                ->orderBy('team_id')
                ->orderBy('match_number')
                ->get();

        $scores = [];

        foreach ($eventScores as $eventScore) {
            if (!isset($scores[$eventScore->team->id])) {
                $scores[$eventScore->team->id] = [];   
                $scores[$eventScore->team->id]['team'] = $eventScore->team;  
                $scores[$eventScore->team->id]['matchScores'] = [];  
                $scores[$eventScore->team->id]['kills'] = 0;  
                $scores[$eventScore->team->id]['totalPoints'] = 0;  
            };
            
            $scores[$eventScore->team->id]['matchScores'][$eventScore->match_number] = $eventScore;  
            $scores[$eventScore->team->id]['kills'] = $scores[$eventScore->team->id]['kills'] + $eventScore->kills;  
            $scores[$eventScore->team->id]['totalPoints'] = $scores[$eventScore->team->id]['totalPoints'] + $eventScore->points;  
        };

        $scoresCollection = collect([]);

        foreach ($scores as $score) {
            $scoresCollection->push((object)$score);
        };

        return $scoresCollection->sortByDesc('totalPoints');
    }
}
