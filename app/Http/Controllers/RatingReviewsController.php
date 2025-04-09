<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RatingReviews;
use Illuminate\Support\Facades\Validator;

class RatingReviewsController extends Controller
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
        // Validate the input data
        $validator = Validator::make($request->all(), [
            'rating' => '',
            'player_id' => '',
            'coach_id' => '', 
            'reviews' => '', 
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $reviews = RatingReviews::create([
            'rating' => $request->rating,
            'player_id' => $request->player_id,
            'coach_id' => $request->coach_id, 
            'reviews' => $request->reviews,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Rating Added Successfully',
            'reviews' => $reviews,
        ], 201);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reviews = RatingReviews::with(['player','coach'])->where('coach_id',$id)->orderBy('created_at','desc')->get();
        return response()->json([
            'success' => true,
            'message' => 'Record Get Successfully',
            'reviews' => $reviews
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
