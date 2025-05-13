<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\PlayerParent;
use Illuminate\Http\Request;
use App\Models\SportCategory;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
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
        $player->image = $playerimage_name ?? null;
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
        $player_record = Player::with(['sportCategory','playerParent'])->find($id);
        return response()->json([
            'success' => true,
            'message' => 'Record Found Successfully',
            'player_record' => $player_record,
        ],201); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $updatePlayerRecord = Player::with(['playerParent,sportCategory'])->find($id);
        return response()->json([
            'success' => true,
            'message' => 'Record Get Successfully',
            'updatePlayerRecord' => $updatePlayerRecord
        ],201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $updatePlayerRecord = Player::with(['playerParent,sportCategory'])->find($id);

        $updatePlayerRecord->player_name = $request->player_name;
        $updatePlayerRecord->player_phonenumber = $request->player_phonenumber;
        $updatePlayerRecord->gender = $request->gender;
        $updatePlayerRecord->cat_id = $request->cat_id;
        if($request->hasFile('image')){
            $player_image = $request->file('image');
            $player_image_name = time().'.'.$player_image->getClientOriginalExtension();
            if($updatePlayerRecord->image && file_exists(public_path('uploads/player_image/'.$updatePlayerRecord->image))){
                unlink(public_path('uploads/player_image/',$updatePlayerRecord->image));
            }
            $player_image->move(public_path('uploads/player_image/'),$player_image_name);
            $updatePlayerRecord->image = $player_image_name;
        }
        $updatePlayerRecord->save();
        return response()->json([
            'success'   => true,
            'message'   => 'Record Update Successfully',
            'UpdatePlayerData' => $updatePlayerRecord
        ],201);
    }

    public function UpdatePassword(Request $request, $id)
    {
        $user = User::find($id);
    
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }
    
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);
    
        $user->password = Hash::make($request->password);
        $user->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully',
        ], 200);
    }

    public function UpdatePlayerData(Request $request, $id)
    {
        $updatePlayerRecord = Player::with(['playerParent', 'sportCategory'])->find($id);
    
        if (!$updatePlayerRecord) {
            return response()->json(['success' => false, 'message' => 'Player not found'], 404);
        }
    
        // Update player data
        $updatePlayerRecord->player_name = $request->player_name;
        $updatePlayerRecord->player_phonenumber = $request->player_phonenumber;
        $updatePlayerRecord->player_gender = $request->player_gender;
        $updatePlayerRecord->cat_id = $request->cat_id;
    
        if ($request->hasFile('image')) {
            $player_image = $request->file('image');
            $player_image_name = time() . '.' . $player_image->getClientOriginalExtension();
            if ($updatePlayerRecord->image && file_exists(public_path('uploads/player_image/' . $updatePlayerRecord->image))) {
                unlink(public_path('uploads/player_image/' . $updatePlayerRecord->image));
            }
            $player_image->move(public_path('uploads/player_image/'), $player_image_name);
            $updatePlayerRecord->image = $player_image_name;
        }
    
        $updatePlayerRecord->save();
    
        // Update parent(s)
        if ($request->has('parent')) {
            foreach ($request->parent as $parentData) {
                $parent = PlayerParent::where('player_id', $id)->first(); // Or use ID if multiple parents
                if ($parent) {
                    $parent->name = $parentData['name'];
                    $parent->cnic = $parentData['cnic'];
                    $parent->phone_number = $parentData['phone_number'];
                    $parent->save();
                }
            }
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Record Updated Successfully',
            'UpdatePlayerData' => $updatePlayerRecord
        ], 200);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
