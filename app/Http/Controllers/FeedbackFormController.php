<?php

namespace App\Http\Controllers;

use App\Models\FeedbackForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

class FeedbackFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = FeedbackForm::with('Profile')->get();
        return response()->json([
            'status'   => true,
            'message'  => 'Records retrieved successfully',
            'feedback' => $query,
        ], 201);
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
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'message' => 'required'
        ]);

        if($validation->fails()){
            return response()->json([
                'status'    => true,
                'message'   => 'Error in Code',
                'error'     => $validation->errors(),
            ],422);
        }

        $newFeedback = new FeedbackForm();
        $newFeedback->name = $request->name;
        $newFeedback->email = $request->email;
        $newFeedback->message = $request->message;
        $newFeedback->user_id = $request->user_id;
        $newFeedback->save();

        return response()->json([
            'stauts' => true,
            'message' => 'Record Store Successfully',
            'feedback' => $newFeedback
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $feedbackform = FeedbackForm::find($id);
        return response()->json([
            'status'  => true,
            'message' => 'Record get Successfully',
            'feeback_form' => $feedbackform
        ]);
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
