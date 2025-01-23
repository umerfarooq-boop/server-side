<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\Academy;
use Illuminate\Http\Request;
use App\Models\SportCategory;
use Illuminate\Support\Facades\DB;
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
            $coach->status = 'active';
        }
        $coach->save();
        return response()->json([
            'status' => true,
            'message' => 'Status Updated Successfully',
            'user'  => $coach
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
       
    }

    public function updateRecord(Request $request, string $id)
    {
        // Fetch coach and academy details using join
        $coach_detail = DB::table('coaches')
            ->join('academies', 'coaches.id', '=', 'academies.coach_id')
            ->where('coaches.id', $id)
            ->select('coaches.*', 'academies.*')
            ->first();

        if (!$coach_detail) {
            return response()->json([
                'success' => false,
                'message' => 'Coach not found.',
            ], 404);
        }

        // Validate inputs for coach and academy
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'level' => 'required|string|max:50',
            'phone_number' => 'required|string|max:15',
            'coach_location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'certificate' => 'nullable|mimes:pdf|max:2048', // Ensure certificate is a PDF
            'academy_name' => 'required|string|max:255',
            'academy_location' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'academy_phonenumber' => 'required|string|max:15',
            'academy_certificate' => 'nullable|mimes:pdf|max:2048', // Ensure academy certificate is a PDF
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validation->errors(),
            ], 422);
        }

        // Update Coach details
        $coach = Coach::find($id);
        if (!$coach) {
            return response()->json([
                'success' => false,
                'message' => 'Coach not found.',
            ], 404);
        }

        $coach->name = $request->name;
        $coach->experience = $request->experience;
        $coach->level = $request->level;
        $coach->phone_number = $request->phone_number;
        $coach->coach_location = $request->coach_location;

        // Handle optional file uploads for coach
        if ($request->hasFile('image')) {
            $coachImage = $request->file('image');
            $coachImageName = time() . '.' . $coachImage->getClientOriginalExtension();
            $coachImage->move(public_path('uploads/coach_image'), $coachImageName);
            $coach->image = $coachImageName;
        }

        if ($request->hasFile('certificate')) {
            $coachCertificate = $request->file('certificate');
            if ($coachCertificate->getClientOriginalExtension() !== 'pdf') {
                return response()->json([
                    'success' => false,
                    'message' => 'Coach certificate must be a PDF.',
                ], 422);
            }
            $coachCertificateName = time() . '.' . $coachCertificate->getClientOriginalExtension();
            $coachCertificate->move(public_path('uploads/coach_certificate'), $coachCertificateName);
            $coach->certificate = $coachCertificateName;
        }

        $coach->save();

        // Update Academy details
        $academy = Academy::where('coach_id', $id)->first();
        if (!$academy) {
            return response()->json([
                'success' => false,
                'message' => 'Academy not found.',
            ], 404);
        }

        $academy->academy_name = $request->academy_name;
        $academy->academy_location = $request->academy_location;
        $academy->address = $request->address;
        $academy->academy_phonenumber = $request->academy_phonenumber;

        // Handle optional file uploads for academy
        if ($request->hasFile('academy_certificate')) {
            $academyCertificate = $request->file('academy_certificate');
            if ($academyCertificate->getClientOriginalExtension() !== 'pdf') {
                return response()->json([
                    'success' => false,
                    'message' => 'Academy certificate must be a PDF.',
                ], 422);
            }
            $academyCertificateName = time() . '.' . $academyCertificate->getClientOriginalExtension();
            $academyCertificate->move(public_path('uploads/academy_certificate'), $academyCertificateName);
            $academy->academy_certificate = $academyCertificateName;
        }

        $academy->save();

        // Return success response with updated details
        return response()->json([
            'success' => true,
            'message' => 'Records updated successfully',
            'coach' => $coach,
            'academy' => $academy,
            'coach_image_path' => isset($coachImageName) ? asset('uploads/coach_image/' . $coachImageName) : null,
            'certificate_path' => isset($coachCertificateName) ? asset('uploads/coach_certificate/' . $coachCertificateName) : null,
            'academy_certificate_path' => isset($academyCertificateName) ? asset('uploads/academy_certificate/' . $academyCertificateName) : null,
        ], 200);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
