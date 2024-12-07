<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FrequentlyQuestion;
use Illuminate\Support\Facades\Validator;

class FrequentlyQuestionController extends Controller
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
        $validation = Validator::make($request->all(),[
            'title'         => 'required',
            'question'      => 'required',
            'description'   => 'required',
            'image'         => 'image'
        ]);

        if($validation->fails()){
            return response()->json([
                'status'   => true,
                'message'  => 'Record Not Saved Successfully',
                'error'    => $validation->errors()
            ],422);
        }

        $question_Image = $request->file('image');
        $ext = $question_Image->getClientOriginalExtension();
        $question_image_name = time().'.'.$ext;
        $question_Image->move(public_path('uploads/frequently_question'),$question_image_name);

        $question = new FrequentlyQuestion();
        $question->title = $request->title;
        $question->question = $request->question;
        $question->description = $request->description;
        $question->image = $question_image_name;
        $question->save();

        return response()->json([
            'success'      => true,
            'message'      => 'Record Saved Successfully',
            'question'     => $question,
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
