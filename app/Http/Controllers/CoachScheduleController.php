<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoachSchedule;
use Illuminate\Support\Facades\Validator;

class CoachScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $coach_schedule = CoachSchedule::all();
        return response()->json([
            'success' => true,
            'message' => 'Record get Successfully',
            'coach' => $coach_schedule
        ],201);
    }

    public function coachschedule(){
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
    // Step 1: Validate the incoming data
    $validator = Validator::make($request->all(), [
        'coach_id' => 'required',
        'player_id' => 'required',
        'start_time' => 'required',
        'end_time' => 'required',
        'booking_slot' => 'required',
        'event_name' => 'required',
        'to_date' => 'required|date',
        'from_date' => 'required|date',
        'status' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation errors occurred',
            'error' => $validator->errors(),
        ], 401);
    }

    // Step 2: Check if there is a conflict with another appointment
    $conflict = CoachSchedule::where('coach_id', $request->coach_id)
        ->where('from_date', $request->from_date)
        ->where(function ($query) use ($request) {
            $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                  ->orWhereRaw('? BETWEEN start_time AND end_time', [$request->start_time])
                  ->orWhereRaw('? BETWEEN start_time AND end_time', [$request->end_time]);
        })
        ->exists();

    if ($conflict) {
        return response()->json([
            'status' => false,
            'message' => 'This time slot is already booked. Please choose a different time.',
        ], 409);
    }

    // Step 3: If no conflict, proceed to save the new appointment
    $coach = new CoachSchedule();
    $coach->coach_id = $request->coach_id;
    $coach->player_id = $request->player_id;
    $coach->start_time = $request->start_time;
    $coach->end_time = $request->end_time;
    $coach->booking_slot = $request->booking_slot;
    $coach->to_date = $request->to_date;
    $coach->from_date = $request->from_date;
    $coach->event_name = $request->event_name;
    $coach->status = $request->status;
    $coach->save();


    ///hhfsahdfsad my nmae is 

    return response()->json([
        'status' => true,
        'message' => 'Record Saved Successfully',
        'coach' => $coach,
    ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
