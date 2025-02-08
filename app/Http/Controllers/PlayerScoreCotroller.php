<?php

namespace App\Http\Controllers;

use App\Models\PlayerScore;
use Illuminate\Http\Request;

class PlayerScoreCotroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'player_id' => 'nullable|integer',
            'coach_id' => 'nullable',
            'player_type' => 'string',
            'played_over' => 'nullable|integer',
            'today_give_wickets' => 'nullable|integer',
            'through_over' => 'nullable|integer',
            'today_taken_wickets' => 'nullable|integer',
            'score_status' => 'nullable|string',
        ]);
        if (empty($validated['date'])) {
            $validated['date'] = now()->toDateString(); // Sets current date if no date is provided
        }

        $validated['coach_id'] = $request->coach_id;
        $validated['player_id'] = $request->player_id;

        $existingRecord = PlayerScore::where('player_id', $validated['player_id'])
        ->whereDate('date', $validated['date'])
        ->first();

    if ($existingRecord) {
        return response()->json([
            "message" => "A record for this player already exists today.",
        ], 400);
    }


        $playerScore = PlayerScore::create($validated);

        return response()->json(["message" => "Player Score created successfully", "data" => $playerScore]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $playerScore = PlayerScore::with('player','coach')->where('player_id',$id)->orderBy('id','desc')->get();
        return response()->json([
            'status' => true,
            'message' => 'Record Store Successfully',
            'playerScore' => $playerScore
        ],201);
    }

    public function EditPlayerRecord($player_id){
        $playerScore = PlayerScore::where('player_id', $player_id)->first();

        if (!$playerScore) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Record Get successfully',
            'playerScore' => $playerScore
        ]);
    }

    public function UpdateScore(Request $request, string $id)
    {
        $validated = $request->validate([
            'player_type' => 'required|string',
            'played_over' => 'nullable|integer',
            'today_give_wickets' => 'nullable|integer',
            'through_over' => 'nullable|integer',
            'today_taken_wickets' => 'nullable|integer',
        ]);

        $playerScore = PlayerScore::where('player_id', $id)->first();

        if (!$playerScore) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        if ($validated['player_type'] === "bowler") {
            $playerScore->through_over = $validated['through_over'];
            $playerScore->today_taken_wickets = $validated['today_taken_wickets'];
            $playerScore->played_over = null;
            $playerScore->today_give_wickets = null;
        } elseif ($validated['player_type'] === "batsman") {
            $playerScore->played_over = $validated['played_over'];
            $playerScore->today_give_wickets = $validated['today_give_wickets'];
            $playerScore->through_over = null;
            $playerScore->today_taken_wickets = null;
        } elseif ($validated['player_type'] === "allrounder") {
            $playerScore->played_over = $validated['played_over'];
            $playerScore->today_give_wickets = $validated['today_give_wickets'];
            $playerScore->through_over = $validated['through_over'];
            $playerScore->today_taken_wickets = $validated['today_taken_wickets'];
        } else {
            return response()->json(['message' => 'Invalid player type'], 400);
        }

        $playerScore->save();

        return response()->json(['message' => 'Player Score updated successfully', 'UpdateScore' => $playerScore]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
