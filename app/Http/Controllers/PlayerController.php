<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use App\Models\SportCategory;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $player = Player::with('sportCategory')->get();
        return response()->json([
            'success' => true,
            'message' => 'Record Store Successfully',
            'player' => $player
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
            'player_name' => 'required',
            'cat_id' => 'required',
            'playwith' => 'required',
            'player_gender' => 'required',
            'player_phonenumber' => 'required',
            'player_dob' => 'required',
            'player_address' => 'required',
            'player_location' => 'required',
            'status' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => true,
                'message' => 'Record Not Stored',
                'error'   => $validator->errors()
            ],401);
        }

        $playerimage = $request->file('image');
        $ext = $playerimage->getClientOriginalExtension();
        $playerimage_name = time().'.'.$ext;
        $playerimage->move(public_path('uploads/player_image'),$playerimage_name);

        $player = new Player();
        $player->player_name = $request->player_name;
        $player->cat_id = $request->cat_id;
        $player->playwith = $request->playwith;
        $player->player_gender = $request->player_gender;
        $player->player_phonenumber = $request->player_phonenumber;
        $player->player_dob = $request->player_dob;
        $player->player_location = $request->player_location;
        $player->player_address = $request->player_address;
        $player->status = $request->status;
        $player->image = $playerimage_name;
        $player->save();

        return response()->json([
            'success' => true,
            'message' => 'Record Add Successfully',
            'player' => $player,
            'path'  => asset('uploads/player_image/'.$playerimage_name)
        ],201);

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
