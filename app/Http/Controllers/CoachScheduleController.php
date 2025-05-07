<?php

namespace App\Http\Controllers;

use App\Models\player;
use App\Models\Emergency;
use App\Models\Attendence;
use App\Mail\EmergencyMail;
use App\Models\Notification;
use App\Models\PlayerParent;
use Illuminate\Http\Request;
use App\Models\CoachSchedule;
use Illuminate\Support\Carbon;
use App\Models\EditAppointment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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

        $coaches->to_date > now();
        $coach->delete();
        $coaches->save();
        deleteExpiredSlots();

    }


    public function deleteExpiredSlots()
    {
        try {
            // Get the current date and time
            $currentDate = Carbon::now()->toDateString();
            $currentTime = Carbon::now()->toTimeString();

            // Delete the booking slots where the current date is greater than 'to_date' 
            // and the current time is greater than 'start_time'
            DB::table('coach_schedules') // Replace 'your_table_name' with your actual table name
                ->where('to_date', '<', $currentDate)
                ->orWhere(function ($query) use ($currentDate, $currentTime) {
                    $query->where('to_date', '=', $currentDate)
                        ->where('start_time', '<', $currentTime);
                })
                ->delete();

            return response()->json(['message' => 'Expired booking slots deleted successfully.'], 201);
        } catch (\Exception $e) {
            // Handle errors and return a response
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
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


    // here i can paste the code that is appointment accept table data insert in table

    // public function AcceptRequest($id) {
    //     $coach = CoachSchedule::find($id);
    //     if (!$coach) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Coach schedule not found.'
    //         ], 404);
    //     }
    
    //     if ($coach->status === 'processing') {
    //         $coach->status = 'booked';
    //     } else {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Status update not allowed.'
    //         ], 400);
    //     }
    
    //     $coach->save();
    
    //     $startDate = Carbon::parse($coach->start_date);
    //     $endDate = Carbon::parse($coach->end_date);     
    
    //     for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
    //         Attendence::updateOrCreate(
    //             [
    //                 'date' => $date->toDateString(),
    //                 'coach_id' => $coach->coach_id,
    //                 'player_id' => $coach->player_id,
    //                 'appointment_id' => $coach->id,
    //             ],
    //             [
    //                 'start_time' => Carbon::parse($coach->start_time),
    //                 'end_time' => Carbon::parse($coach->end_time),
    //                 'attendance_status' => null
    //             ]
    //         );
    //     }
    
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Status Updated Successfully',
    //         'updateStatus' => $coach
    //     ], 200);
    // }

    public function AcceptRequest($id)
    {
        $coach = CoachSchedule::find($id);

        if (!$coach) {
            return response()->json([
                'status' => false,
                'message' => 'Coach schedule not found.'
            ], 404);
        }

        if ($coach->status === 'processing') {
            $coach->status = 'booked';
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Status update not allowed.'
            ], 400);
        }

        $coach->save();

        // Delete previous attendance records for the player and coach
        Attendence::where('player_id', $coach->player_id)
            ->where('coach_id', $coach->coach_id)
            ->delete();

        $startDate = Carbon::parse($coach->from_date);
        $endDate = Carbon::parse($coach->to_date);

        // Loop through the date range and create new attendance records
        while ($startDate->lte($endDate)) {
            Attendence::create([
                'date' => $startDate->toDateString(),
                'start_time' => $coach->start_time,
                'end_time' => $coach->end_time,
                'to_date' => $coach->to_date,
                'from_date' => $coach->from_date,
                'attendance_status' => null, // Default status
                'coach_id' => $coach->coach_id,
                'appointment_id' => $coach->id,
                'player_id' => $coach->player_id,
            ]);

            $startDate->addDay(); // Move to the next day
        }

        return response()->json([
            'status' => true,
            'message' => 'Status Updated Successfully',
            'updateStatus' => $coach
        ], 200);
    }

    


    public function RejectRequest($id){
        $coach = CoachSchedule::find($id);

        if (!$coach) {
            return response()->json([
                'status' => false,
                'message' => 'Coach schedule not found.'
            ], 404);
        }
    
        if($coach->status === 'processing'){
            $coach->status = 'reject';
        }else {
            return response()->json([
                'status' => false,
                'message' => 'Status update not allowed.'
            ], 400);
        }
        $coach->save();
        $coach->delete();
        return response()->json([
            'status' => true,
            'message' => 'Status Updated Successfully',
            'updateStatus'  => $coach
        ],201);
    }   

    public function editAppointmentDate($id){
        $coach = CoachSchedule::find($id);
        return response()->json([
            'status'   => true,
            'message'  => 'Record Get Successfully',
            'caoch_schedule'    => $coach
        ],201);
    }

    public function updateAppointmentData(Request $request, $id)
    {
        // Validate request data
        $validated = $request->validate([
            'to_date' => 'required|date',
            'from_date' => 'required|date',
            'end_time' => 'required',
            'booking_slot' => 'required',
            'event_name' => 'required',
            'start_time' => 'required',
        ]);

        // Find the coach schedule
        $coach = CoachSchedule::find($id);

        if (!$coach) {
            return response()->json([
                'status' => false,
                'message' => 'Coach schedule not found.',
            ], 404);
        }

        // Check if an appointment already exists for the player
        $existingAppointment = EditAppointment::where('player_id', $coach->player_id)->first();

        if ($existingAppointment) {
            // Update the existing appointment
            $existingAppointment->coach_id = $coach->coach_id;
            $existingAppointment->to_date = $validated['to_date'];
            $existingAppointment->from_date = $validated['from_date'];
            $existingAppointment->end_time = $validated['end_time'];
            $existingAppointment->booking_slot = $validated['booking_slot'];
            $existingAppointment->event_name = $validated['event_name'];
            $existingAppointment->start_time = $validated['start_time'];
            $existingAppointment->coach_schedule_id = $coach->id;
            $existingAppointment->status = 'processing';
            $existingAppointment->save();

            return response()->json([
                'status' => true,
                'message' => 'Appointment updated successfully!',
                'editcoach' => $existingAppointment,
            ], 200);
        } else {
            // Create a new appointment
            $newAppointment = new EditAppointment();
            $newAppointment->player_id = $coach->player_id;
            $newAppointment->coach_id = $coach->coach_id;
            $newAppointment->to_date = $validated['to_date'];
            $newAppointment->from_date = $validated['from_date'];
            $newAppointment->end_time = $validated['end_time'];
            $newAppointment->booking_slot = $validated['booking_slot'];
            $newAppointment->event_name = $validated['event_name'];
            $newAppointment->start_time = $validated['start_time'];
            $newAppointment->coach_schedule_id = $coach->id;
            $newAppointment->status = 'processing';
            $newAppointment->save();

            return response()->json([
                'status' => true,
                'message' => 'Appointment created successfully!',
                'editcoach' => $newAppointment,
            ], 200);
        }
    }



    // Update fields
        // $coach->to_date = $validated['to_date'];
        // $coach->from_date = $validated['from_date'];
        // $coach->end_time = $validated['end_time'];
        // $coach->booking_slot = $validated['booking_slot'];
        // $coach->event_name = $validated['event_name'];
        // $coach->start_time = $validated['start_time'];
        // $coach->status = 'processing';
        // $coach->save();


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage. Create Appointment For Individual
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

        // Check if the player has already booked an appointment with the coach on the same day
        $existingBooking = CoachSchedule::where('coach_id', $request->coach_id)
            ->where('player_id', $request->player_id)
            ->where('from_date', $request->from_date)
            ->exists();

        if ($existingBooking) {
            return response()->json([
                'status' => false,
                'message' => 'You have already booked an appointment with this coach on this date.',
            ], 403);
        }

        // Check if the coach's daily appointment limit (10) is exceeded
        $dailyBookings = CoachSchedule::where('coach_id', $request->coach_id)
            ->where('from_date', $request->from_date)
            ->count();

        if ($dailyBookings >= 10) {
            return response()->json([
                'status' => false,
                'message' => 'This coach has reached the daily appointment limit.',
            ], 403);
        }

        // Check for time slot conflicts
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
            ], 403);
        }

        // Create a new booking
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
        $coach->created_by = Auth::id();
        $coach->save();

        $coach->load('player');
        $coach->load('coach');
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

    // Create Appointment for Team

    public function TeamBooking(Request $request)
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

        // Rule 1: Check if the player has already booked an appointment with the coach on the same day
        $existingBooking = CoachSchedule::where('coach_id', $request->coach_id)
            ->where('player_id', $request->player_id)
            ->where('from_date', $request->from_date)
            ->exists();

        if ($existingBooking) {
            return response()->json([
                'status' => false,
                'message' => 'You can only book one appointment per day with this coach.',
            ], 403);
        }

        // Rule 2: Check if the total number of players for the same time slot exceeds 2
        $slotPlayerCount = CoachSchedule::where('coach_id', $request->coach_id)
            ->where('from_date', $request->from_date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhereRaw('? BETWEEN start_time AND end_time', [$request->start_time])
                    ->orWhereRaw('? BETWEEN start_time AND end_time', [$request->end_time]);
            })
            ->count();

        if ($slotPlayerCount >= 2) {
            return response()->json([
                'status' => false,
                'message' => 'This time slot is already booked by two players.',
            ], 403);
        }

        // Create a new booking
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
        $coach->created_by = Auth::id();
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

    public function fetchBookedSlotsTeam(Request $request, $coach_id)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = $request->date;

        // Count bookings per slot
        $bookedSlots = CoachSchedule::where('coach_id', $coach_id)
            ->whereDate('from_date', '<=', $date)
            ->whereDate('to_date', '>=', $date)
            ->select('start_time', 'end_time', DB::raw('COUNT(*) as bookings'))
            ->groupBy('start_time', 'end_time')
            ->get();

        return response()->json([
            'status' => true,
            'bookedSlots' => $bookedSlots,
        ]);
    }

    public function FetchEmergencyRecord($coach_id){
        $emergency = CoachSchedule::with(['player','PlayerParent','coach'])->where('coach_id',$coach_id)->get();
        return response()->json([
            'success' => true,
            'message' => 'Record Get Successfully',
            'emergency' => $emergency
        ],201);
    }

    public function StoreEmergencyRecord(Request $request)
    {

        $emergency = new Emergency();
        $emergency->emergencyType = $request->emergencyType;
        $emergency->subemergencyType = $request->subemergencyType || null;
        $emergency->description = $request->description;
        $emergency->player_id = $request->player_id;
        $emergency->parent_id = $request->parent_id;
        $emergency->save();

        $parentrecord = PlayerParent::find($request->parent_id);
        $player_record = Player::find($request->player_id);
        $email = $parentrecord->email;

        Mail::to($email)->send(new EmergencyMail($emergency, $parentrecord, $player_record));
    
        return response()->json([
            'success' => true,
            'message' => 'Record Saved Successfully and Email Sent',
            'emergency' => $emergency,
            'parent_email' => $email
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
        $coach_record = CoachSchedule::with(['player','coach'])->where('coach_id',$id)->get();
        return response()->json([
            'status' => true,
            'message' => 'Record Saved Successfully',
            'coach_record' => $coach_record
        ],201);
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
