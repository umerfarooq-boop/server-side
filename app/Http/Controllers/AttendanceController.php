<?php

namespace App\Http\Controllers;

use App\Models\Attendence;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
    public function show(string $id)
    {
        $attendance = Attendence::with(['schedule','player'])->where('coach_id',$id)->get();
        return response()->json([
            'status'       => true,
            'message'      => 'Record get Successfully',
            'attendance'   => $attendance
        ],201);
    }

    public function markAttendance(Request $request, $id)
    {
        // Validate the input
        $validated = $request->validate([
            'attendance_status' => 'required|in:P,A,L', // Allow only Present (P), Absent (A), Late (L)
        ]);

        // Find the attendance record by ID
        $attendance = Attendence::find($id);

        if (!$attendance) {
            return response()->json([
                'status' => false,
                'message' => 'Attendance record not found.'
            ], 404);
        }

        // Check if 15 minutes have passed since the session started
        $sessionStart = Carbon::parse($attendance->start_time);
        $currentTime = Carbon::now();
        if ($currentTime->diffInMinutes($sessionStart) > 15) {
            return response()->json([
                'status' => false,
                'message' => 'Attendance can no longer be marked for this session.'
            ], 403);
        }

        // Update the attendance status
        $attendance->attendance_status = $validated['attendance_status'];
        $attendance->save();

        return response()->json([
            'status' => true,
            'message' => 'Attendance status updated successfully.',
            'attendance' => $attendance
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
