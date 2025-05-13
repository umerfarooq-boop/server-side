<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Attendence;
use Illuminate\Http\Request;


class AttendanceController extends Controller
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
    public function show(string $id) {
        $today = Carbon::now()->toDateString();
    
        $attendance = Attendence::with(['schedule', 'player'])
            ->where('coach_id', $id)
            ->where('date', $today) // Filter for today's date
            ->orderBy('start_time') // Show sessions in time order
            ->get();
    
        return response()->json([
            'status' => true,
            'message' => 'Record fetched successfully',
            'attendance' => $attendance
        ], 200);
    }
    

    // public function studentAttendance($id){
    //     $attendance = Attendence::with(['schedule', 'player'])
    //         ->where('player_id', $id)// Filter for today's date
    //         ->orderBy('date','desc') // Show sessions in time order
    //         ->get();
    
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Record fetched successfully',
    //         'attendance' => $attendance
    //     ], 200);
    // }

        public function studentAttendance($id)
    {
        $today = now()->format('Y-m-d');

        $attendance = Attendence::with(['schedule', 'player'])
            ->where('player_id', $id)
            ->whereDate('from_date', '<=', $today)
            ->orderBy('date', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Record fetched successfully',
            'attendance' => $attendance
        ], 200);
    }



    public function markAttendance(Request $request, $id)
    {
        $validated = $request->validate([
            'attendance_status' => 'required|in:P,A,L', // Present, Absent, Late
        ]);
    
        $attendance = Attendence::find($id);
    
        if (!$attendance) {
            return response()->json([
                'status' => false,
                'message' => 'Attendance record not found.',
            ], 404);
        }
    
        $sessionStart = Carbon::parse($attendance->start_time)->setTimezone('Asia/Karachi');
        $currentTime = Carbon::now('Asia/Karachi');
    
        if ($currentTime->lt($sessionStart)) {
            return response()->json([
                'status' => false,
                'message' => 'The session has not started yet. Attendance cannot be marked.',
            ], 400);
        }
    
        // If more than 1 hour has passed since session started, auto mark as Absent
        if ($currentTime->diffInRealMinutes($sessionStart) >= 60 && !$attendance->attendance_status) {
            $attendance->attendance_status = 'A';
            $attendance->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Attendance automatically marked as Absent after 1 hour.',
                'attendance' => $attendance,
            ], 200);
        }
    
        if ($attendance->attendance_status) {
            return response()->json([
                'status' => false,
                'message' => 'Attendance has already been marked.',
            ], 400);
        }
    
        $attendance->attendance_status = $validated['attendance_status'];
        $attendance->save();
    
        return response()->json([
            'status' => true,
            'message' => 'Attendance status updated successfully.',
            'attendance' => $attendance,
        ], 200);
    }
    


    public function EditAttendance($id){
        $editAttendance = Attendence::find($id);
        $editAttendance->attendance_status = null;
        $editAttendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Attendance Edit Successfully',
            'editAttendance' => $editAttendance
        ]);
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
