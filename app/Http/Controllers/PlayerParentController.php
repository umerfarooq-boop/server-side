<?php

namespace App\Http\Controllers;
use App\Models\Player;
use App\Models\PlayerParent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayerParentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parent = PlayerParent::with('player')->get();
        return response()->json([
            'success' => true,
            'message' => 'Record Found',
            'parent'  => $parent
        ]);
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
        $validator = Validator::make($request->all(),[
            'cnic' => 'required',
            'name' => 'required',
            'address' => 'required',
            'player_id' => 'required',
            'phone_number' => 'required',
            'location' => 'required',
            'status' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Record Not Added Successfully',
                'error'  => $validator->errors()
            ]);
        }

        $parent = new PlayerParent();
        $parent->cnic = $request->cnic;
        $parent->name = $request->name;
        $parent->email = $request->email;
        $parent->address = $request->address;
        $parent->player_id = $request->player_id;
        $parent->phone_number = $request->phone_number;
        $parent->location = $request->location;
        $parent->status = $request->status;
        $parent->save();

        return response()->json([
            'success' => true,
            'parent' => $parent,
            'message' => 'Record added Successfully'
        ]);
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
