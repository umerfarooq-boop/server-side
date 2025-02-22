<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssignEquipment;
use App\Models\Request_Equipment;
use Illuminate\Support\Facades\Validator;

class Request_EquipmentController extends Controller
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
        // Validate input data
        $validation = Validator::make($request->all(), [
            'player_id' => 'required',
            'coach_id' => 'required',
            'equipment_name_id' => 'required',
            'equipment_quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    $availableQuantity = AssignEquipment::where('id', $request->equipment_name_id)->value('equipment_quantity');
                    if ($value > $availableQuantity) {
                        $fail("The $attribute must not exceed the available quantity ($availableQuantity).");
                    }
                },
            ],
            'equipment_status' => 'required',
            'return_date_time' => 'required|date_format:Y-m-d H:i:s|after:now',
        ]);
    
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Record not added successfully.',
                'errors' => $validation->errors(),
            ], 422);
        }
    
        $equipment = new Request_Equipment();
        $equipment->player_id = $request->player_id;
        $equipment->coach_id = $request->coach_id;
        $equipment->equipment_name_id = $request->equipment_name_id;
        $equipment->equipment_quantity = $request->equipment_quantity;
        $equipment->equipment_status = "reject";
 
        $equipment->now_date_time = now(); 
    
        $equipment->return_date_time = $request->return_date_time;
    
        if ($equipment->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Record saved successfully.',
                'equipment' => $equipment,
            ], 201);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'Failed to save the record. Please try again later.',
        ], 500);
    }
    
    
    public function AcceptEquipmentRequest($id)
    {
        $acceptRequest = Request_Equipment::find($id);
        if (!$acceptRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Equipment request not found.'
            ], 404);
        }
    
        if ($acceptRequest->equipment_status === 'active') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been accepted.'
            ], 400);
        }
    
        $equipment = AssignEquipment::where('id', $acceptRequest->equipment_name_id)->first();
    
        // Check if the equipment exists
        if (!$equipment) {
            return response()->json([
                'success' => false,
                'message' => 'Assigned equipment not found.'
            ], 404);
        }
    
        if ($equipment->equipment_quantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient equipment quantity available.'
            ], 400);
        }
    
        // Update the equipment quantity by reducing it by 1
        $equipment->equipment_quantity -= 1;
        $equipment->save();
    
        $acceptRequest->equipment_status = 'active';
    
        // Save the request status change
        $acceptRequest->save();
    
        // Return the response
        return response()->json([
            'success' => true,
            'message' => 'Your request has been accepted.',
            'acceptRequest' => $acceptRequest,
            'equipment' => $equipment
        ], 200);
    }
    

    public function DeleteEquipmentRequest($id){
        $equipment = Request_Equipment::find($id);
        $equipment->delete();
        return response()->json([
            'success'  => true,
            'message'  => 'Record Delete Successfully'
        ],201);
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $equipment = Request_Equipment::with(['coach','player','equipment'])->where('coach_id',$id)->get();
        return response()->json([
            'success'   => true,
            'message'   => 'Record Get Successfully',
            'requestequipment' => $equipment
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
