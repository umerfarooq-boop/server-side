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
        $question = FrequentlyQuestion::all();
        return response()->json([
            'status'    => true,
            'message'   => 'Record Get Successfully',
            'question'  => $question
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

    public function UpdateFrequentlyQuestion(Request $request, $id)
    {
        $frequentlyQuestion = FrequentlyQuestion::find($id);

        if (!$frequentlyQuestion) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found'
            ], 404);
        }

        $validation = Validator::make($request->all(), [
            'title'       => 'required',
            'question'    => 'required',
            'description' => 'required',
            'image'       => 'nullable|image'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validation->errors()
            ], 422);
        }

        if ($request->hasFile('image')) {
            $questionImage = $request->file('image');
            $ext = $questionImage->getClientOriginalExtension();

            if ($frequentlyQuestion->image && file_exists(public_path('uploads/frequently_question/' . $frequentlyQuestion->image))) {
                unlink(public_path('uploads/frequently_question/' . $frequentlyQuestion->image));
            }

            $questionImageName = time() . '.' . $ext;
            $questionImage->move(public_path('uploads/frequently_question'), $questionImageName);

            $frequentlyQuestion->image = $questionImageName;
        }
        $frequentlyQuestion->title = $request->title;
        $frequentlyQuestion->question = $request->question;
        $frequentlyQuestion->description = $request->description;
        $frequentlyQuestion->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Record updated successfully',
            'data'     => $frequentlyQuestion
        ], 200);
    }

    public function UpdateFeatureStatus($id){
        $question = FrequentlyQuestion::find($id);
        if($question->status == 'active'){
            $question->status = 'block';
        }else{
            $question->status = 'active';
        }
        $question->save();
        return response()->json([
            'success'      => true,
            'message'      => 'Record Saved Successfully',
            'question_status'     => $question,
        ],201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $single_question = FrequentlyQuestion::find($id);
        return response()->json([
            'status'    => true,
            'message'   => 'Record Get Successfully',
            'frequentlyquestion'  => $single_question

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
