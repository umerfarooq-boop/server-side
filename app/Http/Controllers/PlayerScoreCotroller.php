<?php

namespace App\Http\Controllers;

use App\Models\Attendence;
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
        'player_id' => 'required|integer', // Player ID is required
        'coach_id' => 'required|integer', // Coach ID is required
        'player_type' => 'required|string', // Player type is required
        'played_over' => 'nullable|integer',
        'today_give_wickets' => 'nullable|integer',
        'through_over' => 'nullable|integer',
        'today_taken_wickets' => 'nullable|integer',
        'score_status' => 'nullable|string',
    ]);

    // Set default date to today
    $date = now()->toDateString();

    // 🔹 Check if attendance exists and attendance_status is set
    $attendance = Attendence::where('player_id', $validated['player_id'])
        ->whereDate('date', $date)
        ->whereNotNull('attendance_status')
        ->where('attendance_status', '!=', '') // Ensure it's not an empty string
        ->first();

    if (!$attendance) {
        return response()->json([
            'success' => false,
            'message' => "Attendance is missing! Please mark today's attendance first.",
        ], 402);
    }

    // 🔹 Check if a score record already exists for today
    $existingRecord = PlayerScore::where('player_id', $validated['player_id'])
        ->whereDate('date', $date)
        ->first();

    if ($existingRecord) {
        return response()->json([
            'message' => 'A score record for this player already exists today.',
        ], 400);
    }

    // 🔹 Create and save the player score record
    $playerEnterScore = new PlayerScore();
    $playerEnterScore->player_id = $validated['player_id'];
    $playerEnterScore->coach_id = $validated['coach_id'];
    $playerEnterScore->player_type = $validated['player_type'];
    $playerEnterScore->played_over = $request->played_over;
    $playerEnterScore->today_give_wickets = $request->today_give_wickets;
    $playerEnterScore->through_over = $request->through_over;
    $playerEnterScore->today_taken_wickets = $request->today_taken_wickets;
    $playerEnterScore->score_status = $request->score_status;
    $playerEnterScore->date = $date;
    $playerEnterScore->save();

    return response()->json([
        'message' => 'Player Score created successfully.',
        'data' => $playerEnterScore,
    ]);
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
