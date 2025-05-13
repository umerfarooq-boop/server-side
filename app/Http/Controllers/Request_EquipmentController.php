<?php

namespace App\Http\Controllers;

use App\Models\player;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\AssignEquipment;
use App\Models\Request_Equipment;
use App\Models\PlayerNotification;
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
        $player = player::find($request->player_id);
        Notification::create([
            'coach_id' => $request->coach_id,
            'player_id' => $request->player_id,
            'message' => 'Your Have New Equipment From ' . $player->player_name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Equipment requested successfully.',
        ], 201);
    }

    public function AcceptEquipmentRequest(Request $request,$id)
    {
        $acceptRequest = Request_Equipment::find($id);
        // return $acceptRequest;
        if (!$acceptRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Equipment request not found.',
            ], 404);
        }
    
        // Check if already accepted
        if ($acceptRequest->equipment_status === 'active') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been accepted.',
            ], 400);
        }
    
        $equipment = AssignEquipment::where('id', $acceptRequest->equipment_name_id)->first();
    
        if (!$equipment) {
            return response()->json([
                'success' => false,
                'message' => 'Assigned equipment not found.',
            ], 404);
        }
    
        // Check if enough equipment is available
        if ($equipment->equipment_quantity < $acceptRequest->equipment_quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient equipment quantity available.',
            ], 400);
        }
    
        // Deduct the requested quantity
        $equipment->equipment_quantity -= $request->equipment_quantity;
        $equipment->save();
    
        // Mark the request as accepted
        $acceptRequest->equipment_status = 'active';
        $acceptRequest->equipment_quantity = $request->equipment_quantity;
        $acceptRequest->save();



        PlayerNotification::create([
                'coach_id' => $acceptRequest->coach_id,
                'player_id' => $acceptRequest->player_id,
                'message' => 'Equipment request is accepted ' . $acceptRequest->coach->name,
            ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Equipment request has been accepted.',
            'acceptRequest' => [
                'id' => $acceptRequest->id,
                'player_name' => $acceptRequest->player->player_name,
                'equipment_name' => $equipment->equipment_name,
                'equipment_quantity' => $acceptRequest->equipment_quantity,
                'return_date_time' => $acceptRequest->return_date_time,
                'equipment_status' => $acceptRequest->equipment_status,
            ],
            'remaining_equipment_quantity' => $equipment->equipment_quantity,
        ], 200);
    }
    

    public function show_return_equipment($id) {
        $equipment_return = Request_Equipment::with(['player', 'coach', 'equipment'])
            ->where('id', $id)
            ->first();
    
        if (!$equipment_return) {
            return response()->json([
                'success' => false,
                'message' => 'Equipment not found.',
            ], 404);
        }
    //// 
    /// kinhg
        return response()->json([
            'success' => true,
            'message' => 'Record Found',
            'equipment_return' => [
                'id' => $equipment_return->id,
                'player_id' => $equipment_return->player_id,
                'player_name' => $equipment_return->player->player_name, // Show player name
                'coach_id' => $equipment_return->coach_id,
                'coach_name' => $equipment_return->coach->name,
                'equipment_name_id' => $equipment_return->equipment_name_id, // Store equipment ID
                'equipment_name' => $equipment_return->equipment->equipment_name, // Show equipment name
                'equipment_quantity' => $equipment_return->equipment_quantity,
                'equipment_status' => $equipment_return->equipment_status,
                'return_date_time' => $equipment_return->return_date_time,
                'created_at' => $equipment_return->created_at,
                'updated_at' => $equipment_return->updated_at,
            ],
        ], 201);
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
        // Get equipment with player and coach
        $equipment = Request_Equipment::with(['player', 'coach'])->find($id);
    
        if (!$equipment) {
            return response()->json([
                'success' => false,
                'message' => 'Equipment request not found',
            ], 404);
        }
    
        $coach = $equipment->coach;
        $player = $equipment->player;
    
        // Ensure both coach and player exist
        if (!$coach || !$player) {
            return response()->json([
                'success' => false,
                'message' => 'Coach or Player not found for this equipment request',
            ], 400);
        }
    
        // Create player notification using correct IDs
        PlayerNotification::create([
            'coach_id' => $coach->id, // ✅ Use `id` instead of `coach_id`
            'player_id' => $player->id, // ✅ Use `id` instead of `player_id`
            'message' => 'Equipment request is Rejected by ' . $coach->name,
        ]);
    
        // Delete the equipment request
        $equipment->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully',
        ], 200);
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $equipment = Request_Equipment::with(['coach', 'player', 'equipment'])
            ->where('coach_id', $id)
            ->orWhere('id', $id)->orWhere('player_id',$id)
            ->get();
    
        return response()->json([
            'success' => true,
            'message' => 'Record retrieved successfully',
            'requestequipment' => $equipment->map(function ($item) {
                return [
                    'id' => $item->id,
                    'player_id' => $item->player_id,
                    'player_name' => $item->player->player_name, // Show player name
                    'coach_id' => $item->coach_id,
                    'equipment_name_id' => $item->equipment_name_id, // Store equipment ID
                    'equipment_name' => $item->equipment->equipment_name, // Show equipment name
                    'equipment_quantity' => $item->equipment_quantity,
                    'equipment_status' => $item->equipment_status,
                    'return_date_time' => $item->return_date_time,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            }),
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
