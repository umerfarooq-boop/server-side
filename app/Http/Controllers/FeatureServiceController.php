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
        $service = FeatureService::all();
        return response()->json([
            'status' => true,
            'message' => 'Record Get Successfully',
            'feature_service' => $service
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

    public function AboutServiceStatus($id){
        $service = FeatureService::find($id);
        if($service->status == 'active'){
            $service->status = 'block';
        }else{
            $service->status = 'active';
        }
        $service->save();
        return response()->json([
            'status'  => true,
            'message' => 'Status Updated Successfully',
            'about_srvice' => $service
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $feature_service = FeatureService::find($id);
        return response()->json([
            'status'   => true,
            'message'  => 'Successfully Get Successfully',
            'feature_service'  => $feature_service,
        ],201);
    }

    public function UpdateFeatureService(Request $request, $id)
{
    // Retrieve the existing record
    $updateService = FeatureService::find($id);

    if (!$updateService) {
        return response()->json([
            'status' => false,
            'message' => 'Service not found',
        ], 404);
    }

    // Validate request data
    $validation = Validator::make($request->all(), [
        'title' => 'required',
        'description' => 'required',
        'image' => 'nullable|image|max:2048', // Image is optional and must be valid
    ]);

    if ($validation->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation errors',
            'errors' => $validation->errors(),
        ], 422);
    }

    // Handle the image upload
    if ($request->hasFile('image')) {
        $feature_service_image = $request->file('image');
        $ext = $feature_service_image->getClientOriginalExtension();

        // Delete the old image if it exists
        $oldImagePath = public_path('uploads/feature_service/' . $updateService->image);
        if ($updateService->image && file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }

        // Save the new image
        $feature_service_image_name = time() . '.' . $ext;
        $feature_service_image->move(public_path('uploads/feature_service'), $feature_service_image_name);

        $updateService->image = $feature_service_image_name;
    }

    // Update the service details
    $updateService->title = $request->title;
    $updateService->description = $request->description;
    $updateService->save();

    return response()->json([
        'status' => true,
        'message' => 'Service updated successfully',
        'service' => $updateService,
    ], 200);
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
