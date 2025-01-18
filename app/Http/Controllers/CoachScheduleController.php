<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoachSchedule;
use Illuminate\Support\Facades\Validator;
use App\Models\Notification;


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

    public function PlayerRequests($id){
        $coaches = CoachSchedule::with(['coach', 'sportCategory', 'player'])->where('coach_id', $id)->orderBy('id', 'desc')->get();

        if($coaches) {
            return response()->json([
                'status' => true,
                'message' => 'Record Get Successfully',
                'CoachSchedule' => $coaches,
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Record Not Found',
            ], 401);
        }
    }

    public function SinglePlayerRequest($id, $role) {
        $coaches = CoachSchedule::with(['coach', 'sportCategory', 'player'])
            ->where('player_id', $id)
            ->orderBy('id', 'desc')
            ->get();
    
        if ($coaches) {
            return response()->json([
                'status' => true,
                'message' => 'Record Get Successfully',
                'SinglePlayerRequest' => $coaches,
            ], 200); // Use 200 instead of 201 for GET requests
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Record Not Found',
            ], 404); // Use 404 for not found
        }
    }

    public function coachschedule(){
    }

    public function showCoachBookings($id){
        $coach_schedule = CoachSchedule::with(['coach','player'])->where('coach_id',$id)->get();
        return response()->json([
            'success' => true,
            'message' => 'Record get Successfully',
            'coach' => $coach_schedule
        ],201);
    }

    public function AcceptRequest($id){
        $coach = CoachSchedule::find($id);
        if($coach->status === 'processing'){
            $coach->status = 'booked';
        }
        $coach->save();
        return response()->json([
            'status' => true,
            'message' => 'Status Updated Successfully',
            'updateStatus'  => $coach
        ],201);
    }   
    public function RejectRequest($id){
        $coach = CoachSchedule::find($id);
        if($coach->status === 'processing'){
            $coach->status = 'reject';
        }
        $coach->save();
        return response()->json([
            'status' => true,
            'message' => 'Status Updated Successfully',
            'updateStatus'  => $coach
        ],201);
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

        $coach->load('player');

        // Create a notification for the coach
        Notification::create([
            'coach_id' => $request->coach_id,
            'player_id' => $request->player_id,
            'message' => 'You have a new booking from Player ' . $coach->player->player_name,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record Saved Successfully',
            'coach' => $coach,
        ], 201);
    }

    public function fetchBookedSlots(Request $request, $coach_id)
{
    $request->validate([
        'date' => 'required|date',
    ]);

    $date = $request->date;

    $bookedSlots = CoachSchedule::where('coach_id', $coach_id)
        ->whereDate('from_date', '<=', $date)
        ->whereDate('to_date', '>=', $date) // Assuming `to_date` column exists
        ->get(['start_time', 'end_time', 'from_date', 'to_date']);

    return response()->json([
        'status' => true,
        'bookedSlots' => $bookedSlots,
    ]);
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
