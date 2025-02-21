<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssignEquipment;
use Illuminate\Support\Facades\Validator;

class AssignEquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assign_equipment = AssignEquipment::all();
        return response()->json([
            'success'   => true,
            'message'   => 'Record Get Successfully',
            'allequipment' => $assign_equipment
        ],201);
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
    $validator = Validator::make($request->all(), [
        'equipment_name' => 'required',
        'equipment_quantity' => 'required',
        'coach_id' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            "success" => false,
            'message' => 'Record Not Saved',
            'error' => $validator->errors(),
        ], 422);
    }

    // Check if equipment already exists for the same coach
    $exists = AssignEquipment::where('equipment_name', $request->equipment_name)
        ->where('coach_id', $request->coach_id)
        ->exists();

    if ($exists) {
        return response()->json([
            'success' => false,
            'message' => 'Equipment Already Exists',
        ], 422);
    }

    $equipment = new AssignEquipment();
    $equipment->equipment_name = $request->equipment_name;
    $equipment->equipment_quantity = $request->equipment_quantity;
    $equipment->coach_id = $request->coach_id;
    $equipment->status = "active";
    $equipment->save();

    return response()->json([
        'success' => true,
        'message' => 'Record Saved Successfully',
        'equipment' => $equipment,
    ], 201);
}

    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $equipment = AssignEquipment::with('coach')->where('coach_id',$id)->get();
        return response()->json([
            'success'   => true,
            'message'   => 'Record Get Successfully',
            'equipment' => $equipment
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
