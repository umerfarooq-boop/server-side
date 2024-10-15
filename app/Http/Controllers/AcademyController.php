<?php

namespace App\Http\Controllers;

use App\Models\Academy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AcademyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $academy = Academy::all();
        return response()->json([
            'message' => 'Record Found Successfully',
            'academy' => $academy
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
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'academy_name' => 'required|string|max:255',
            'academy_location' => 'required|string|max:255',
            'status' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'academy_phonenumber' => 'required|string|max:15',
            'academy_certificate' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Check for validation failures
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please fix these errors',
                'errors' => $validator->errors()
            ], 422); 
        }

    
        $img = $request->file('academy_certificate');
        $ext = $img->getClientOriginalExtension();
        $imageName = time() . '.' . $ext;
        $img->move(public_path('uploads/academy_certificate'), $imageName);

        
        $academy = new Academy();
        $academy->academy_name = $request->academy_name; 
        $academy->academy_location = $request->academy_location;
        $academy->status = $request->status;
        $academy->address = $request->address;
        $academy->academy_phonenumber = $request->academy_phonenumber;
        $academy->academy_certificate = $imageName;
        $academy->save();

        return response()->json([
            'success' => true,
            'message' => 'Record Saved Successfully',
            'path' => asset('uploads/academy_certificate/'.$imageName),
            'academy' => $academy
        ], 201); 
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
