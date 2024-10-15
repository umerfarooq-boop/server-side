<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SportCategory;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class SportCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $category = SportCategory::all();
            return response()->json([
                'message' => 'Record Get Successfully',
                'category' => $category
        ]);

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
        $validatedData = $request->validate([
            'name' => 'required|string',
            'status' => 'required|string'
        ]);
    
        // $validatedData['created_by'] = $request->user()->id;
        $validatedData['created_by'] = $request->created_by;;
        $category = SportCategory::create($validatedData);
        return response()->json([
            'message' => 'Record Saved Successfully',
            'category' => $category
        ]);
        
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
