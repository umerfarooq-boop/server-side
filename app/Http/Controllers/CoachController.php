<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use Illuminate\Http\Request;
use App\Models\SportCategory;
use App\Models\Academy;
use Illuminate\Support\Facades\Validator;

class CoachController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sport = Coach::with('sportCategory')->orderBy('id','desc')->get();
        return response()->json([
            'success' => true,
            'message' => 'Record Found Successfully',
            'coach' => $sport
        ],201);
    }

    // get coach Record that can show in About Page

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    // Show Coach Record on Home Screen of Website
    public function coach_record(){
        $coach = Coach::orderBy('id', 'asc')->limit(6)->get(['name', 'image', 'phone_number', 'coach_location']);
        return response()->json([
            "status" => true,
            "message" => '"Record Get Successfully',
            "coach" => $coach
        ]);
    }

    public function DownloadFile($path,$filename)
    {
        $file = public_path("uploads/{$path}/{$filename}");
        if (file_exists($file)) {
            return response()->download($file);
        }
        return response()->json(['error' => 'File not found.'], 404);
    }
    
    public function changeStatus($id){
        $coach = Coach::find($id);
        if($coach->status == 'acitve'){
            $coach->status = 'block';
        }else{
            $coach->status = 'block';
        }
        $coach->save();
        return response()->json([
            'status' => true,
            'message' => 'Status Updated Successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'category_id' => 'required',
            'experience'  => 'required',            
            'level'  => 'required',            
            'phone_number'  => 'required',            
            'coach_location'  => 'required',            
            'status'  => 'required',            
        ]);
        // return $request;

        if($validation->fails()){
            return response()->json([
                'success' => true,
                'message' => 'Record Not Added',
                'error'   => $validation->errors()
            ]);
        }

        // Certificate of Coach
        
        $certificate = $request->file('certificate');
        $ext = $certificate->getClientOriginalExtension();
        $certificateName = time() . '.' . $ext;
        $certificate->move(public_path('uploads/coach_certificate'),$certificateName);

        // Image of Coach

        $coachImage = $request->file('image');
        $ext = $coachImage->getClientOriginalExtension();
        $coach_image_name = time() . '.' . $ext;
        $coachImage->move(public_path('uploads/coach_image'),$coach_image_name);

        $coach = new Coach();
        $coach->name = $request->name;
        $coach->category_id = $request->category_id;
        $coach->experience = $request->experience;
        $coach->level = $request->level;
        $coach->phone_number = $request->phone_number;
        $coach->coach_location = $request->coach_location;
        $coach->status = $request->status;
        $coach->certificate = $certificateName;
        $coach->image = $coach_image_name;
        $coach->save();

        return response()->json([
            'success' => true,
            'message' => 'Record Added Successfully',
            'coach_record' => $coach,
            'certificate_path' => asset('uploads/coach_certificate/'.$certificateName),
            'coach_image_path' => asset('uploads/coach_image/'.$coach_image_name),
         ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $coach_record = Coach::with(['sportCategory', 'singleAcademy'])
            ->where('id', $id)
            ->get();

        return response()->json([
            'message' => 'Record Get Successfully',
            'coach_record' => $coach_record,
        ], 201);
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
