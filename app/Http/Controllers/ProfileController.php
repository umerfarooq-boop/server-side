<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Coach;
use App\Models\Player;
use App\Models\Academy;
use App\Models\Profile;
use Illuminate\Support\Str;
use App\Models\PlayerParent;
use Illuminate\Http\Request;
use App\Mail\SendPasswordParent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\Exception\ApiErrorException;



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
        $profile->user_id = $request->user_id;
        $profile->save();

        // If the role is 'coach' and they have an academy
        if ($request->role === 'coach' || $request->role === 'admin') {
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
            $coach->per_hour_charges = $request->per_hour_charges;
            $coach->phone_number = $request->phone_number;
            $coach->coach_location = $request->coach_location;
            $coach->image = $cimagename; // Store the image name in the database
            $coach->certificate = $ccertificatename; 
            $coach->created_by = $request->created_by;
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
            $profile->user_id = $coach->created_by;
            // here send ID from REQuest form

            // $profile->user_id = $coach->id;
            // $profile->user_id = $request->user_id;

            Stripe::setApiKey('sk_test_51RCqM3FLwCatna2ik8SxyUUYcbizqdBwTjdavv9hkaMF6w5tLK5RAKMYxdcIRqlcc4JUL4VMGwem5yxGvUjsIFkH00GwZqlgEQ');

        $user = User::find($profile->user_id); // 👈 get user from profile
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        // Create Stripe Express Account if not exists
        if (!$user->stripe_account_id) {
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
        } else {
            $account = Account::retrieve($user->stripe_account_id);
        }
        
        // Generate onboarding link
        $accountLink = AccountLink::create([
            'account' => $account->id,
            'refresh_url' => url('/stripe/refresh'),
            'return_url' => url('/stripe/return'),
            'type' => 'account_onboarding',
        ]);

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
            // $profile->user_id = $player->id;
            
        
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
        
        // Stripe Account
            

        
        

        // Stripe Account


        // Save the profile with associated player or coach IDs
        $profile->save();

        return response()->json([
            'message' => 'Profile created successfully!',
            'success' => true,
            'profile' => $profile,
            // 'academy' => $newacademy,
            'coach'   => $profile->coach_id ?? null,
            'player'   => $profile->player_id ?? null,
            'location' => $profile->profile_location ?? null,
            'profile' => $profile,
            'url' => $profile->role === 'coach' ? $accountLink->url : null,
        ], 201);
    }


  // Decode user info from JWT token
    public function getProfileData($id,$role)
    {
        // $user = Profile::with(['user', 'coach', 'player', 'academy', 'playerParent'])
        // ->where(function ($query) use ($id) {
        //     $query->where('user_id', $id)
        //         ->orWhere('coach_id', $id)
        //         ->orWhere('player_id', $id);
        // })
        // ->where('role', $role) // Corrected `andWhere` to `where`
        // ->first();


        $user = Profile::with(['user', 'coach', 'player', 'academy', 'playerParent'])
        ->where(function ($query) use ($id) {
            $query->where('user_id', $id)
                ->orWhere('coach_id', $id)
                ->orWhere('player_id', $id);
        })
        ->where('role', $role) // Corrected `andWhere` to `where`
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
