<?php

namespace App\Http\Controllers;

use App\Models\Vedio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VedioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vedios = Vedio::orderBy('id', 'desc')->get();
        return response()->json([
            'success' => true,
            'message' => 'Record Get Successfully',
            'vedios' => $vedios
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
        $validator = Validator::make($request->all(),[
            'vedio_title' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                "success" => false,
                "message" => 'Record Not Saved',
                $validator->errors(),
            ],422);
        }

        $vedios = $request->file('vedio');
        $ext = $vedios->getClientOriginalExtension();
        $vedio_name = time().'.'.$ext;
        $vedios->move(public_path('uploads/vedio_home_page'),$vedio_name);

        $vedio = new Vedio();
        $vedio->vedio_title = $request->vedio_title;
        $vedio->vedio = $vedio_name;
        $vedio->save();
        return response()->json([
            'success' => true,
            'Message' => "File Upload Successfully",
            'path' => asset('uploads/vedio_home_page'.$vedio_name),
            'vedio' => $vedio
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
