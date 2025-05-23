<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\User;
use App\Models\Academy;
use Illuminate\Http\Request;
use App\Models\SportCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;

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

    public function GetCoachAccount(Request $request)
{
    Stripe::setApiKey('sk_test_51RCqM3FLwCatna2ik8SxyUUYcbizqdBwTjdavv9hkaMF6w5tLK5RAKMYxdcIRqlcc4JUL4VMGwem5yxGvUjsIFkH00GwZqlgEQ');

    // Step 1: Get coach and associated user
    $coach = Coach::find($request->id);

    if (!$coach) {
        return response()->json([
            'success' => false,
            'message' => 'Coach not found',
            'redirect_url' => url('/404') // Explicit 404 redirect
        ], 404);
    }

    $user = User::find($coach->created_by);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found',
            'redirect_url' => url('/404')
        ], 404);
    }

    // Step 2: Check if user already has a Stripe account
    if (!$user->stripe_account_id) {
        try {
            $account = Account::create([
                'type' => 'express',
                'country' => 'US',
                'email' => $user->email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
            ]);

            $user->stripe_account_id = $account->id;
            $user->save();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Stripe account creation failed: ' . $e->getMessage(),
                'redirect_url' => url('/error')
            ], 500);
        }
    } else {
        return response()->json([
            'success' => true,
            'message' => 'Stripe account already exists',
            'already_exists' => true,
            'redirect_url' => url('/allcoach')
        ], 200);
    }

    // Step 3: Generate onboarding link with proper URLs
    try {
        $accountLink = AccountLink::create([
            'account' => $user->stripe_account_id,
            'refresh_url' => url('/stripe/refresh'), // Fully qualified URL
           'return_url' => 'http://localhost:5173/allcoach',// Fully qualified URL
            'type' => 'account_onboarding',
        ]);

        // Step 4: Return the onboarding URL
        return response()->json([
            'success' => true,
            'url' => $accountLink->url,
            'redirect_url' => $accountLink->url // Always include redirect_url
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create account link: ' . $e->getMessage(),
            'redirect_url' => url('/error')
        ], 500);
    }
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
        if($coach->status == 'active'){
            $coach->status = 'block';
        }else{
            $coach->status = 'active';
        }
        $coach->save();
        return response()->json([
            'status' => true,
            'message' => 'Status Updated Successfullylll',
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
        $coach->per_hour_charges = $request->per_hour_charges;
        $coach->coach_location = $request->coach_location;
        $coach->status = $request->status;
        $coach->certificate = $certificateName;
        $coach->image = $coach_image_name;
        $coach->created_by = Auth::id();
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
            'academy_certificate' => 'nullable|mimes:pdf|max:2048',
            'per_hour_charges' => 'required',
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
        $coach->per_hour_charges = $request->per_hour_charges;

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
