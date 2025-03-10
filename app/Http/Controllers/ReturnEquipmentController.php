<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturnEquipment;
use App\Models\Request_Equipment;

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
            'player_id' => 'required|exists:players,id',
            'coach_id'  => 'required|exists:coaches,id',
            'equipment_name'  => 'required|string',
            'quantity'  => 'required|integer|min:1',
            'return_note'  => 'nullable|string',
            'return_date_time'  => 'nullable|date',
        ]);
    
        // Get the original request from Request_Equipment table
        $equipmentRequest = Request_Equipment::where('player_id', $validatedData['player_id'])
                            ->where('equipment_name_id', $validatedData['equipment_name'])
                            ->first();
    
        if (!$equipmentRequest) {
            return response()->json([
                'success' => false,
                'message' => 'No matching equipment request found.',
            ], 404);
        }
    
        if ($validatedData['quantity'] > $equipmentRequest->equipment_quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Returned quantity cannot be more than borrowed quantity.',
            ], 400);
        }
    
        // Store the return record
        $returnEquipment = new ReturnEquipment();
        $returnEquipment->player_id = $validatedData['player_id'];
        $returnEquipment->coach_id = $validatedData['coach_id'];
        $returnEquipment->equipment_name = $validatedData['equipment_name'];
        $returnEquipment->quantity = $validatedData['quantity'];
        $returnEquipment->return_note = $validatedData['return_note'];
        $returnEquipment->equipment_request_id = $request->equipment_request_id;
        $returnEquipment->return_date_time = $validatedData['return_date_time'] ?? now();
        $returnEquipment->save();
    
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
