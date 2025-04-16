<?php

namespace App\Http\Controllers;

use App\Models\CheckoutForm;
use Illuminate\Http\Request;
use App\Models\CoachSchedule;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\PaymentIntent;
class CheckoutFormCotroller extends Controller
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
        $validator = Validator::make($request->all(), [
            'player_id' => 'required',
            'coach_id' => 'required',
            'booking_id' => 'required',
            'player_name' => 'required',
            'player_email' => 'required|email',
            'player_phone_number' => 'required',
            'player_address' => 'required',
            'coach_name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'to_date' => 'required|date',
            'from_date' => 'required|date',
            'per_hour_charges' => 'required|numeric',
            'total_charges' => 'required|numeric',
            'payment_type' => 'required|in:stripe,paypal,bank',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'error' => $validator->errors()
            ], 422);
        }

        $newRecord = CheckoutForm::create($request->all());
    
        return response()->json([
            'success' => true,
            'message' => 'Record added successfully',
            'data' => $newRecord
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $player_booking = CoachSchedule::with(['player','coach'])->where('player_id',$id)->first();
        if($player_booking){
            return response()->json([
                'success' => true,
                'message' => 'Record Get Successfully',
                'player_booking' => $player_booking
            ],201);
        }if (!$player_booking){
            return response()->json([
                'success' => false,
                'message' => 'Record Not Found',
            ],404);
        }
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
