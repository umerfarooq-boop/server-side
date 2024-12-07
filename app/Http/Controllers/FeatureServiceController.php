<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeatureService;
use Illuminate\Support\Facades\Validator;

class FeatureServiceController extends Controller
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
            'title' =>   'required',
            'description' =>   'required',
            'image' =>   'required',
        ]);

        if($validation->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Record Not Found',
                'error' => $validation->erros(),
            ],422);
        }

        $feature_service_image = $request->file('image');
        $ext = $feature_service_image->getClientOriginalExtension();
        $feature_service_image_name = time().'.'.$ext;
        $feature_service_image->move(public_path('uploads/feature_service'),$feature_service_image_name);

        $service = new FeatureService();
        $service->title = $request->title;
        $service->description = $request->description;
        $service->image = $feature_service_image_name;
        $service->save();

        return response()->json([
            'status'   => true,
            'message'  => 'Successfully Added Record',
            'service'  => $service,
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
