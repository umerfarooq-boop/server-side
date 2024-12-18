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

    public function slidder_record()
    {
        $homeslides = HomeSlidder::all();
        return response()->json([
            'status' => true,
            'message' => 'Record Get Successfully',
            'slidder' => $homeslides,
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
        $validation = Validator::make($request->all(), [
            'slidder_image' => 'required',
            'slidder_text' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'error' => $validation->errors(),
            ], 422);
        }

        $slidder_image = $request->file('slidder_image');
        $ext = $slidder_image->getClientOriginalExtension();
        $slidder_image_name = time() . '.' . $ext;
        $slidder_image->move(public_path('uploads/slidder_image'), $slidder_image_name);

        $slidder = new HomeSlidder();
        $slidder->slidder_image = $slidder_image_name;
        $slidder->slidder_text = $request->slidder_text;
        $slidder->save();
        return response()->json([
            'status' => true,
            'message' => 'Record Added Successfully',
            'slidder' => $slidder,
        ], 201);

    }

    public function change_status($id)
    {
        $sliderStatus = HomeSlidder::find($id);
        if ($sliderStatus->status == 'active') {
            $sliderStatus->status = 'block';
        } else {
            $sliderStatus->status = 'active';
        }
        $sliderStatus->save();
        return response()->json([
            'status' => true,
            'message' => 'Record Get Successfully',
            'slideStatus' => $sliderStatus,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $homeSlide = HomeSlidder::find($id);
        if ($homeSlide) {
            return response()->json([
                'status' => true,
                'message' => 'Record Get Successfully',
                'slide' => $homeSlide,
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Record Not Found',
                'error' => 'No error',
            ], 401);
        }
    }

    public function updateSlidder(Request $request, $id)
    {
        $updateSlide = HomeSlidder::find($id);

        if (!$updateSlide) {
            return response()->json([
                'success' => false,
                'message' => 'Slide not found',
            ], 404);
        }

        $validation = Validator::make($request->all(), [
            'slidder_image' => 'nullable|image',
            'slidder_text' => 'required|string|max:255',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'error' => $validation->errors(),
            ], 422);
        }

        $slidder_image_name = $updateSlide->slidder_image;

        if ($request->hasFile('slidder_image')) {
            $slidder_image = $request->file('slidder_image');
            $ext = $slidder_image->getClientOriginalExtension();

            if ($updateSlide->slidder_image && file_exists(public_path('uploads/slidder_image/' . $updateSlide->slidder_image))) {
                unlink(public_path('uploads/slidder_image/' . $updateSlide->slidder_image));
            }

            $slidder_image_name = time() . '.' . $ext;
            $slidder_image->move(public_path('uploads/slidder_image'), $slidder_image_name);
        }

        // Update the existing record
        $updateSlide->slidder_image = $slidder_image_name;
        $updateSlide->slidder_text = $request->slidder_text;
        $updateSlide->save();

        return response()->json([
            'status' => true,
            'message' => 'Record Updated Successfully',
            'slidder' => $updateSlide,
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

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
