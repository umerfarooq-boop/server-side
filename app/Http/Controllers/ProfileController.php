<?php

namespace App\Http\Controllers;
use auth;
use App\Models\User;
use App\Models\Coach;
use App\Models\Player;
use App\Models\Academy;
use App\Models\Profile;
use Illuminate\Support\Str;
use App\Models\PlayerParent;
use Illuminate\Http\Request;
use App\Mail\SendPasswordParent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profile = Profile::with(['sportCategory','coach','player','playerProfile'])->get();
        return response()->json([
            'success' => true,
            'message' => 'Record Found Successfully',
            'profile' => $profile
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
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(),[
    //         'dob' => 'required',
    //         'gender' => 'required',
    //         'role' => 'required',
    //         'cat_id' => 'required',
    //         'coach_id' => 'required',
    //         'player_id' => 'required',
    //         'parent_id' => 'required',
    //         'location' => 'required',
    //         'address' => 'required',
    //     ]);

    //     if($validator->fails()){
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Record Not Found',
    //             'errors' => $validator->errors()
    //         ]);
    //     }

    //     $profile = new Profile();
    //     $profile->dob = $request->dob;
    //     $profile->gender = $request->gender;
    //     $profile->role = $request->role;
    //     // $profile->save();
    //     $profile->cat_id = $request->cat_id;
    //     // $profile->coach_id = $request->coach_id;
    //     // $profile->player_id = $request->player_id;
    //     // $profile->parent_id = $request->parent_id;
    //     $profile->location = $request->location;
    //     $profile->address = $request->address;
    //     $profile->save();
    //     // Player Code if Role is Player


    //     // Start Coach Role

    //     return response()->json([
    //         'success' => true,
    //         'profile' => $profile,
    //         'message' => 'Record added Successfully'
    //     ]);

    // }



    // public function store(Request $request)
    // {
    //     // Validate basic fields
    //     $request->validate([
    //         'dob' => 'required|date',
    //         'gender' => 'required|string',
    //         'role' => 'required|string',
    //         'location' => 'required|string',
    //         'address' => 'required|string',
    //         // 'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     $profile = new Profile();
    //     $profile->dob = $request->dob;
    //     $profile->gender = $request->gender;
    //     $profile->role = $request->role;
    //     $profile->location = $request->location;
    //     $profile->address = $request->address;

    //     $profile->save();

    //     if ($request->role === 'coach' && $request->hasAccademy === 'yes') {
            
    //             $coach = new Coach();
    //             $coach->name = $request->name;
    //             $coach->category_id = $request->category_id;
    //             $coach->experience = $request->experience;
    //             $coach->level = $request->level;
    //             $coach->phone_number = $request->phone_number;
    //             $coach->location = $request->location;
                

    //             // if ($request->hasFile('academy_certificate')) {
    //                 $certificateName = time() . '_' . $request->file('academy_certificate')->getClientOriginalName();
    //                 $certificatePath = $request->file('academy_certificate')->storeAs('academy_certificates', $certificateName, 'public');
    //                 $profile->academy_certificate = $certificatePath;

    //                 $coachCertificate = time() . '_' . $request->file('coach_certificate')->getClientOriginalName();
    //                 $coachcertificatePath = $request->file('academy_certificate')->storeAs('academy_certificates', $coachCertificate, 'public');
    //                 // $profile->academy_certificate = $certificatePath;

    //                $coach->certificate = $certificateName;

    //                $coachImage = time() . '_' . $request->file('academy_certificate')->getClientOriginalName();
    //                $coachPath = $request->file('academy_certificate')->storeAs('academy_certificates', $coachImage, 'public');
    //                $profile->academy_certificate = $coachPath;

    //                $coach->save();
    //                $profile->coach_id = $coach->id;
    //                 $profile->save();
                
    //         } else {
    //             $request->validate(['location' => 'required|string']);
    //             $profile->location = $request->location;
    //         }
        

    //     // Handling player-specific fields

        

    //     if ($request->role === 'player') {
    //         $request->validate([
    //             'name' => 'required|string',
    //             'cat_id' => 'required',
    //             'playwith' => 'required',
    //             'gender' => 'required',
    //             'phone_number' => 'required',
    //             'dob' => 'required',
    //             'status' => 'required',
    //         ]);
    //         $player = new Player();

    //         $playerImage = $request->file('image');
    //         $ext = $playerImage->getClientOriginalExtension();
    //         $playerImage_name = time(). '.'.$ext;
    //         $img->move(public_path('uploads\academy_certificate'),$playerImage_name);

    //         $player->name = $request->name;
    //         $player->cat_id = $request->cat_id;
    //         $player->playwith = $request->playwith;
    //         $player->gender = $request->gender;
    //         $player->phone_number = $request->phone_number;
    //         $player->dob = $request->dob;
    //         // $profile->player_id = $player->id;
    //         $profile->cat_id = $player->cat_id;
    //         // $profile->save();

    //         $parent = new PlayerParent();
    //         $parent->cnic = $request->cnic;
    //         $parent->name = $request->name;
    //         $parent->address = $request->address;
    //         $parent->player_id = $request->player_id;
    //         $parent->phone_number = $request->phone_number;
    //         $parent->location = $request->location;
    //         $parent->status = $request->status;

    //         $profile->player_id = $player->id;
    //         $profile->parent_id = $parent->id;
    //         $parent->save();

    //         // $profile->player_position = $request->player_position;
    //         // $profile->category_id = $request->cat_id;
    //     }

    //     $profile->save();

    //     return response()->json([
    //         'message' => 'Profile created successfully!',
    //         'success' => true,
    //         'profile' => $profile,
    //     ], 201);
    // }

    public function store(Request $request)
    {
        // Validate common fields for all roles
        $request->validate([
            'dob' => 'required|date',
            'gender' => 'required|string',
            'role' => 'required|string',
            'profile_location' => 'required|string',
            'address' => 'required|string',
        ]);

        // Create a profile record
        $profile = new Profile();
        $profile->dob = $request->dob;
        $profile->gender = $request->gender;
        $profile->role = $request->role;
        $profile->profile_location = $request->profile_location;
        $profile->address = $request->address;
        $profile->save();

        // If the role is 'coach' and they have an academy
        if ($request->role === 'coach') {
            $request->validate([
                'name' => 'required|string',
                'category_id' => 'required|integer',
                'experience' => 'required|string',
                'level' => 'required|string',
                'phone_number' => 'required|string',
                'coach_location' => 'required',
                'image' => 'required|file|mimes:jpeg,jpg,png|max:180', // 180 KB for image
                'certificate' => 'required|file|mimes:pdf|max:51200', // 50 MB for PDF
            ]);
            
            // Handling image upload for coach image
            $coachimage = $request->file('image');
            $cimagename = time() . '.' . $coachimage->getClientOriginalExtension();
            $coachimage->move(public_path('uploads/coach_image'), $cimagename);
            
            // Handling file upload for coach certificate
            $coachcertificate = $request->file('certificate');
            $ccertificatename = time() . '.' . $coachcertificate->getClientOriginalExtension();
            $coachcertificate->move(public_path('uploads/coach_certificate'), $ccertificatename);
            
            // Create a new coach record
            $coach = new Coach();
            $coach->name = $request->name;
            $coach->category_id = $request->category_id;
            $coach->experience = $request->experience;
            $coach->level = $request->level;
            $coach->phone_number = $request->phone_number;
            $coach->coach_location = $request->coach_location;
            $coach->image = $cimagename; // Store the image name in the database
            $coach->certificate = $ccertificatename; // Store the certificate name in the database
            // $coach->save();


            if ($request->hasAccademy === 'yes') {
                // Validate and store academy-specific fields
                $request->validate([
                    'academy_name' => 'required|string',
                    'academy_certificate' => 'required',
                    'academy_location' => 'required|string',
                    'address' => 'required|string',
                    'academy_phonenumber' => 'required|string',
                ]);

                $academyCertificate = $request->file('academy_certificate');
                if ($academyCertificate->getClientOriginalExtension() !== 'pdf') {
                    return back()->withErrors(['academy_certificate' => 'The file must be a PDF.']);
                }
                $academyCertificateName = time() . '.' . $academyCertificate->getClientOriginalExtension();
                $academyCertificate->move(public_path('uploads/academy_certificate'), $academyCertificateName);

                $newacademy = new Academy();
                $newacademy->academy_name = $request->academy_name;
                $newacademy->academy_location = $request->academy_location;
                $newacademy->address = $request->address;
                $newacademy->academy_phonenumber = $request->academy_phonenumber;
                $newacademy->academy_certificate = $academyCertificateName;
                $newacademy->save();
                $profile->academy_id = $newacademy->id;
                // $coach->academy_id = $newacademy->id;
            }
            $coach->save();
            $newacademy->coach_id = $coach->id;
            $newacademy->save();
            $coach->academy_id = $newacademy->id;
            $coach->save();
            $profile->coach_id = $coach->id;
            $profile->user_id = $coach->id;

        }

        // If the role is 'player'
        if ($request->role === 'player') {
            $request->validate([
                'player_name' => 'required|string',
                'cat_id' => 'required|integer',
                'playwith' => 'required|string',
                'player_gender' => 'required|string',
                'player_phonenumber' => 'required|string',
                'player_location' => 'required|string',
                'player_address' => 'required|string',
                'player_dob' => 'required|date',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:180', // 180 KB max size
            ]);
        
            // Create a new player record
            $player = new Player();
            $player->player_name = $request->player_name;
            $player->cat_id = $request->cat_id;
            $player->playwith = $request->playwith;
            $player->player_gender = $request->player_gender;
            $player->player_phonenumber = $request->player_phonenumber;
            $player->player_dob = $request->player_dob;
            $player->player_location = $request->player_location;
            $player->player_address = $request->player_address;
        
            $playerimage = $request->file('image');
            $playerext = $playerimage->getClientOriginalExtension(); // Use getClientOriginalExtension for the extension
            $playerimageName = time().'.'.$playerext;
            $playerimage->move(public_path('uploads/player_image'), $playerimageName);
        
            // Save the player and link it to the profile
            $player->image = $playerimageName;
            $player->save();
            $profile->player_id = $player->id;
            $profile->user_id = $player->id;
        
            $parent = new PlayerParent();
            $parent->cnic = $request->cnic;
            $parent->name = $request->name;
            $parent->email = $request->email;
            $parent->address = $request->address;
            $parent->player_id = $player->id;
            $parent->phone_number = $request->phone_number;
            $parent->location = $request->location;
            $parent->save();
            $profile->parent_id = $parent->id;

            $user = new User();
            $user->name = $parent->name;
            $user->email = $parent->email;
            $user->role = 'parent';

            // Generate random password
            $parentPassword = Str::random(6);
            $user->password = bcrypt($parentPassword);
            $user->save();
            Mail::to($user->email)->send(new SendPasswordParent($user, $player, $parentPassword));

        }
        

        // Save the profile with associated player or coach IDs
        $profile->save();

        return response()->json([
            'message' => 'Profile created successfully!',
            'success' => true,
            'profile' => $profile,
            // 'academy' => $newacademy,
            'coach'   => $profile->coach_id ?? null,
            'location' => $profile->profile_location ?? null,
        ], 201);
    }


  // Decode user info from JWT token
    public function getProfileData($id,$role)
    {
        $user = Profile::with(['user', 'coach', 'player', 'academy','playerParent'])
        ->where('user_id', $id)
        ->where('role', $role)
        ->first();

        return response()->json([
            'success' => true,
            'message' => 'Record retrieved successfully',
            'user' => $user,
        ]);
    }



// store role and get record from user_id when creating the profile so please set this code




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
        // $coach_detail = 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
