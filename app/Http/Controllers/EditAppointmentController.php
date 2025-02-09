<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
