<?php

namespace App\Http\Controllers;

use App\Models\AssignEquipment;
use App\Models\Request_Equipment;
use Illuminate\Http\Request;
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
            'player_id' => '',
            'coach_id' => 'required',
            'equipment' => 'required|array',
            'equipment.*.equipment_name_id' => 'required|exists:assign_equipment,id',
            'equipment.*.equipment_quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    preg_match('/\d+/', $attribute, $matches);
                    $index = $matches[0];
                    $equipmentId = $request->equipment[$index]['equipment_name_id'];
                    $availableQuantity = AssignEquipment::where('id', $equipmentId)->value('equipment_quantity');
                    if ($value > $availableQuantity) {
                        $fail("The $attribute must not exceed the available quantity ($availableQuantity).");
                    }
                },
            ],
            'return_date_time' => 'required|date_format:Y-m-d H:i:s|after:now',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validation->errors(),
            ], 422);
        }

        foreach ($request->equipment as $equip) {
            Request_Equipment::create([
                'player_id' => $request->player_id,
                'coach_id' => $request->coach_id,
                'equipment_name_id' => $equip['equipment_name_id'],
                'equipment_quantity' => $equip['equipment_quantity'],
                'equipment_status' => "reject",
                'now_date_time' => now(),
                'return_date_time' => $request->return_date_time,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Equipment requested successfully.',
        ], 201);
    }

    public function AcceptEquipmentRequest($id)
    {
        $acceptRequest = Request_Equipment::find($id);
        if (!$acceptRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Equipment request not found.',
            ], 404);
        }

        if ($acceptRequest->equipment_status === 'active') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been accepted.',
            ], 400);
        }

        $equipment = AssignEquipment::where('id', $acceptRequest->equipment_name_id)->first();

        // Check if the equipment exists
        if (!$equipment) {
            return response()->json([
                'success' => false,
                'message' => 'Assigned equipment not found.',
            ], 404);
        }

        // Check if there's enough equipment available
        if ($equipment->equipment_quantity < $acceptRequest->equipment_quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient equipment quantity available.',
            ], 400);
        }

        // Reduce the equipment quantity by the requested amount
        $equipment->equipment_quantity -= $acceptRequest->equipment_quantity;
        $equipment->save();

        // Mark the request as active
        $acceptRequest->equipment_status = 'active';
        $acceptRequest->save();

        // Return the response
        return response()->json([
            'success' => true,
            'message' => 'Your request has been accepted.',
            'acceptRequest' => $acceptRequest,
            'equipment' => $equipment,
        ], 200);
    }

    public function show_return_equipment($id){
        $equipment_return = Request_Equipment::with(['player','coach','equipment'])->where('id',$id)->first();
        return response()->json([
            'success' => true,
            'message' => 'Record Found',
            'equipment_return'  => $equipment_return
        ],201);
    }


    public function ReturnEquipment($id, Request $request)
    {
        $equipmentRequest = Request_Equipment::find($id); // here it get id of mysql table request__equipment mean match id
    
        if (!$equipmentRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Record Not Found',
            ], 404);
        }
    
        $returnedQuantity = $request->input('equipment_quantity');
        if ($returnedQuantity > $equipmentRequest->equipment_quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Returned quantity exceeds the requested quantity.',
            ], 400);
        }
    
        // Find the assigned equipment based on equipment_name_id
        $assignedEquipment = AssignEquipment::where('id', $equipmentRequest->equipment_name_id)->first();
    
        if ($assignedEquipment) {
            // Update the quantity in AssignEquipment
            $assignedEquipment->equipment_quantity += $returnedQuantity;
            $assignedEquipment->save();
        }
    
        // Update the remaining quantity in Request_Equipment
        $remainingQuantity = $equipmentRequest->equipment_quantity - $returnedQuantity;
        if ($remainingQuantity > 0) {
            $equipmentRequest->equipment_quantity = $remainingQuantity;
            $equipmentRequest->save();
        } else {
            // Delete the request if all equipment is returned
            $equipmentRequest->delete();
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Equipment returned successfully',
            'updated_quantity' => $assignedEquipment ? $assignedEquipment->equipment_quantity : null,
            'remaining_quantity' => $remainingQuantity,
        ], 200);
    }
    

    

    public function DeleteEquipmentRequest($id)
    {
        $equipment = Request_Equipment::find($id);
        $equipment->delete();
        return response()->json([
            'success' => true,
            'message' => 'Record Delete Successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $equipment = Request_Equipment::with(['coach', 'player', 'equipment'])->where('coach_id', $id)->get();
        return response()->json([
            'success' => true,
            'message' => 'Record Get Successfully',
            'requestequipment' => $equipment,
        ], 201);
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
