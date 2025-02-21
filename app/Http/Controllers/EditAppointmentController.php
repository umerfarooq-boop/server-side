<?php

namespace App\Http\Controllers;

use App\Models\Attendence;
use Illuminate\Http\Request;
use App\Models\CoachSchedule;
use Illuminate\Support\Carbon;
use App\Models\EditAppointment;

class EditAppointmentController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ShowAppointment = EditAppointment::with(['coach_schedule','player','coach','sportcategory'])->where('coach_id',$id)->get();
        return response()->json([
            'status' => true,
            'message' => 'Record Get Successfully',
            'showAppointment' => $ShowAppointment
        ]);
    }

    public function AcceptEditAppointment($id)
{
    $editAppointment = EditAppointment::where('player_id', $id)->first();

    if (!$editAppointment) {
        return response()->json([
            "status" => false,
            "message" => "EditAppointment not found.",
        ], 404);
    }

    // Update appointment status to "booked"
    $editAppointment->status = "booked";
    $editAppointment->save();

    // Update coach schedule
    $coachSchedule = CoachSchedule::where('player_id', $editAppointment->player_id)->first();

    if (!$coachSchedule) {
        return response()->json([
            "status" => false,
            "message" => "CoachSchedule not found.",
        ], 404);
    }

    $coachSchedule->player_id = $editAppointment->player_id;
    $coachSchedule->coach_id = $editAppointment->coach_id;
    $coachSchedule->to_date = $editAppointment->to_date;
    $coachSchedule->from_date = $editAppointment->from_date;
    $coachSchedule->end_time = $editAppointment->end_time;
    $coachSchedule->booking_slot = $editAppointment->booking_slot;
    $coachSchedule->event_name = $editAppointment->event_name;
    $coachSchedule->start_time = $editAppointment->start_time;
    $coachSchedule->status = $editAppointment->status; // Match status
    $coachSchedule->save();

    // Delete previous attendance records
    Attendence::where('player_id', $id)->delete();

    // Loop to insert attendance records for each date in the range
    $startDate = Carbon::parse($editAppointment->from_date);
    $endDate = Carbon::parse($editAppointment->to_date);

    while ($startDate->lte($endDate)) {
        Attendence::create([
            'date' => $startDate->toDateString(),
            'start_time' => $coachSchedule->start_time,
            'end_time' => $coachSchedule->end_time,
            'to_date' => $coachSchedule->to_date,
            'from_date' => $coachSchedule->from_date,
            'attendance_status' => null, // Default status
            'coach_id' => $coachSchedule->coach_id,
            'appointment_id' => $editAppointment->id,
            'player_id' => $editAppointment->player_id,
        ]);

        $startDate->addDay(); // Move to the next day
    }

    // Delete the edit appointment record
    $editAppointment->delete();

    return response()->json([
        "status" => true,
        "message" => "Appointment accepted, coach schedule updated, and attendance records created successfully.",
    ], 200);
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
