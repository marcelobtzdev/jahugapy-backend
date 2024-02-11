<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Http\Requests\TeamRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::with('members.user')->get();

        return $teams;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  TeamRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeamRequest $request)
    {
        $user = Auth::user();

        if (count($user->teams)) {
            return response()->json([
                'success' => 'error', 
                'message' => 'Solo puedes registrar un equipo por el momento'
            ], 400);
        };

        $inputMembers = $request->members;
        
        $team = Team::create($request->safe()->except('members'));
        User::insert($inputMembers);

        $usernames = array_map(fn($member) => $member['activision_id'], $inputMembers);
        $users = User::whereIn('activision_id', array_values($usernames))->get();
        $members = $users->map(fn($user) => ['user_id' => $user->id])->toArray();
        array_push($members, ['user_id' => $user->id]);
        
        $team->members()->createMany($members);

        $user->teams()->save($team);

        return $team;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        $team->load('members');

        return $team;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TeamRequest  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(TeamRequest $request, Team $team)
    {
        $team->update($request->validated());
        $team->load('members');

        return $team;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $teamId = $team->id;

        $team->members()->each(function ($member) {
            if ($member->user_id !== auth()->user()->id) {
                $member->user->delete();
            };
        });
        $team->delete();

        return $teamId;
    }
}
