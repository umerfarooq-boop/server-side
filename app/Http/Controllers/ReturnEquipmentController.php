<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturnEquipment;

class ReturnEquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $validatedData = $request->validate([
            'player_id' => 'nullable',
            'coach_id'  => 'nullable',
            'equipment_name'  => 'nullable',
            'quantity'  => 'nullable|integer',
            'return_note'  => 'nullable|string',
            'return_date_time'  => 'nullable|date',
        ]);
    
        // Store the data
        $returnEquipment = new ReturnEquipment();
        $returnEquipment->player_id = $validatedData['player_id'] ?? null;
        $returnEquipment->coach_id = $validatedData['coach_id'] ?? null;
        $returnEquipment->equipment_name = $validatedData['equipment_name'] ?? null;
        $returnEquipment->quantity = $validatedData['quantity'] ?? 0;
        $returnEquipment->return_note = $validatedData['return_note'] ?? null;
        $returnEquipment->return_date_time = $validatedData['return_date_time'] ?? now();
        $returnEquipment->save();
        
        

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Record Saved Successfully',
            'returnEquipment' => $returnEquipment
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
