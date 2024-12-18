<?php

namespace App\Http\Controllers;

use App\Models\HomeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $service = HomeService::all();
        return response()->json([
            'status'  => true,
            'message' => 'Record Get Successfully',
            'service' => $service
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
            'service_image' => 'required',
            'service_text'  => 'required'
        ]);
        if($validation->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'error'   =>  $validation->errors(),
            ],422);
        }

        $service_image = $request->file('service_image');
        $ext = $service_image->getClientOriginalExtension();
        $service_image_name = time().'.'.$ext;
        $service_image->move(public_path('uploads/service_image'),$service_image_name);
        
        $service = new HomeService();
        $service->service_image = $service_image_name;
        $service->service_text = $request->service_text;
        $service->save();

        return response()->json([
            'success'   => true,
            'message'   => 'Record Saved Successfully',
            'service'   => $service
        ],201);
    }

    public function ServiceStatus($id){
        $service = HomeService::find($id);
        if($service->status == 'active'){
            $service->status = 'block';
        }else{
            $service->status = 'active';
        }
        $service->save();
        return response()->json([
            'status'  => true,
            'message' => 'Status Updated Successfully',
            'service_status' => $service
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $service = HomeService::find($id);
        return response()->json([
            'status'  => true,
            'message' => 'Record Get Successfully',
            'service' => $service
        ],201);
    }

    public function UpdateService(Request $request, $id)
{
    $updateService = HomeService::find($id);
    if (!$updateService) {
        return response()->json([
            'success' => false,
            'message' => 'Record Not Found',
        ], 404); // Corrected status code
    }

    $validation = Validator::make($request->all(), [
        'service_image' => 'sometimes|file', // 'sometimes' allows optional file upload
        'service_text'  => 'required|string'
    ]);
    if ($validation->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation Error',
            'error'   => $validation->errors(),
        ], 422);
    }

    if ($request->hasFile('service_image')) {
        $service_image = $request->file('service_image');
        $ext = $service_image->getClientOriginalExtension();

        // Delete old file if it exists
        if ($updateService->service_image && file_exists(public_path('uploads/service_image/' . $updateService->service_image))) {
            unlink(public_path('uploads/service_image/' . $updateService->service_image));
        }

        $service_image_name = time() . '.' . $ext;
        $service_image->move(public_path('uploads/service_image'), $service_image_name);

        $updateService->service_image = $service_image_name;
    }

    $updateService->service_text = $request->service_text;
    $updateService->save();

    return response()->json([
        'success' => true,
        'message' => 'Record Updated Successfully',
        'service' => $updateService
    ], 200); // Corrected status code
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
