<?php

namespace App\Http\Controllers;

use App\Models\HomeSlidder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeSlidderController extends Controller
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
            'slidder_image' => 'required',
            'slidder_text'  => 'required',
        ]);
        if($validation->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'error'  => $validation->errors(),
            ],422);
        }

        $slidder_image = $request->file('slidder_image');
        $ext = $slidder_image->getClientOriginalExtension();
        $slidder_image_name = time() .'.'.$ext;
        $slidder_image->move(public_path('uploads/slidder_image'),$slidder_image_name);

        $slidder = new HomeSlidder();
        $slidder->slidder_image = $slidder_image_name;
        $slidder->slidder_text = $request->slidder_text;
        $slidder->save();
        return response()->json([
            'status' => true,
            'message' => 'Record Added Successfully',
            'slidder' => $slidder
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
