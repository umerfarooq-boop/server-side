<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Account;
use App\Models\User;
use App\Models\Coach;
use App\Models\Invoice;
use Stripe\AccountLink;
use Stripe\PaymentIntent;
use App\Models\CheckoutForm;
use Illuminate\Http\Request;
use App\Mail\PaymentReceived;
use App\Models\CoachSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf; // if using barryvdh/laravel-dompdf

class StripeController extends Controller
{
   


    // public function createPaymentIntent(Request $request)
    // {
    //     Stripe::setApiKey('sk_test_51RCqLoFVCuVUMzrhZZACHmHJJ9vwpmHkAr8jeVPTudPvR08eqqqdLQsHF4usk0FXulIPCxZsXx9fOS2CBZLMeHKH00rYsIBMHq');
    
    //     $amount = $request->input('amount');
    // $coach_id = $request->input('coach_id');

    // $coach = User::find($coach_id);

    // if (!$coach || !$coach->stripe_account_id) {
    //     return response()->json(['error' => 'Coach not found or Stripe account missing'], 400);
    // }

    // $stripeAccountId = $coach->stripe_account_id;

    // $paymentIntent = PaymentIntent::create([
    //     'amount' => $amount * 100,
    //     'currency' => 'usd',
    //     'application_fee_amount' => 100,
    //     'payment_method_types' => ['card'],
    //     'transfer_data' => [
    //         'destination' => $stripeAccountId,
    //     ],
    // ]);

    // return response()->json([
    //     'clientSecret' => $paymentIntent->client_secret,
    // ]);
    // }
    


    // public function createConnectedAccount(Request $request)
    // {
    //     Stripe::setApiKey('sk_test_51RCqM3FLwCatna2ik8SxyUUYcbizqdBwTjdavv9hkaMF6w5tLK5RAKMYxdcIRqlcc4JUL4VMGwem5yxGvUjsIFkH00GwZqlgEQ');

    //     $account = Account::create([
    //         'type' => 'express',
    //         'email' => $request->coach_email,
    //         'country' => 'US',
    //     ]);

    //     // Save $account->id in your `coaches` table
    //     $coach = Coach::find($request->coach_id);
    //     $coach->stripe_account_id = $account->id;
    //     $coach->save();

    //     // Create onboarding link
    //     $accountLink = AccountLink::create([
    //         'account' => $account->id,
    //         'refresh_url' => url('/reauth'),
    //         'return_url' => url('/dashboard'),
    //         'type' => 'account_onboarding',
    //     ]);

    //     return redirect($accountLink->url);
    // }


    // public function transferToCoach($amount, $coachStripeAccountId)
    // {
    //     \Stripe\Stripe::setApiKey('sk_test_51RCqM3FLwCatna2ik8SxyUUYcbizqdBwTjdavv9hkaMF6w5tLK5RAKMYxdcIRqlcc4JUL4VMGwem5yxGvUjsIFkH00GwZqlgEQ');

    //     \Stripe\Transfer::create([
    //         'amount' => $amount * 100, // in cents
    //         'currency' => 'usd',
    //         'destination' => $coachStripeAccountId,
    //         'transfer_group' => 'COACH_FEES',
    //     ]);
    // }


    // Step 2: Store payment after confirmation
    // public function storePayment(Request $request)
    // {
    //     $request->validate([
    //         'amount' => 'required|numeric',
    //         'payment_id' => 'required|string',
    //         'email' => 'required|email',
    //     ]);

    //     Stripe::setApiKey('sk_test_51RFhFnPIfAW90ynuC40l7peUlbxqxK8uYTYOlz0kWxM0lzpAl1b6lL7oOvoDK1KKeqe3w8LQDDbuMKiLr8y3fxCp00GYl9bxz6');
    //     $paymentIntent = PaymentIntent::retrieve($request->payment_id);

    //     if ($paymentIntent->status === 'succeeded') {
    //         DB::table('payments')->insert([
    //             'email' => $request->email,
    //             'amount' => $request->amount,
    //             'payment_id' => $request->payment_id,
    //             'status' => 'succeeded',
    //             'user_id' => Auth::id(),
    //             'created_at' => now(),
    //         ]);

    //         return response()->json(['message' => 'Payment stored successfully']);
    //     }

    //     return response()->json(['error' => 'Payment not verified'], 400);
    // }



    // public function createPaymentIntent(Request $request)
    // {
    //     try {
    //         \Stripe\Stripe::setApiKey('sk_test_51RCqM3FLwCatna2ik8SxyUUYcbizqdBwTjdavv9hkaMF6w5tLK5RAKMYxdcIRqlcc4JUL4VMGwem5yxGvUjsIFkH00GwZqlgEQ');
    
    //         $amount = $request->input('amount');
    
    //         // Stripe requires amount in **cents**
    //         $paymentIntent = PaymentIntent::create([
    //             'amount' => $amount * 100,
    //             'currency' => 'usd',
    //             'automatic_payment_methods' => [
    //                 'enabled' => true,
    //             ],
    //         ]);
    
    //         return response()->json([
    //             'clientSecret' => $paymentIntent->client_secret,
    //         ]);
    
    //     } catch (\Exception $e) {
    //         \Log::error('Stripe Error: ' . $e->getMessage());
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }    

    // public function createPaymentIntent(Request $request)
    // {
    //     Stripe::setApiKey('sk_test_51RCqM3FLwCatna2ik8SxyUUYcbizqdBwTjdavv9hkaMF6w5tLK5RAKMYxdcIRqlcc4JUL4VMGwem5yxGvUjsIFkH00GwZqlgEQ');

    //     $amount = $request->input('amount');
    //     $coach_id = $request->input('coach_id');

    //     $coach = User::find($coach_id);

    //     if (!$coach || !$coach->stripe_account_id) {
    //         return response()->json(['error' => 'Coach not found or Stripe account missing'], 400);
    //     }

    //     if ($coach->stripe_account_id === 'your_platform_account_id') {
    //         return response()->json(['error' => 'You cannot set your platform account as destination'], 400);
    //     }

    //     $paymentIntent = PaymentIntent::create([
    //         'amount' => $amount * 100,
    //         'currency' => 'usd',
    //         'application_fee_amount' => 100,
    //         'payment_method_types' => ['card'],
    //         'transfer_data' => [
    //             'destination' => $coach->stripe_account_id,
    //         ],
    //     ]);

    //     return response()->json([
    //         'client_secret' => $paymentIntent->client_secret,
    //         'paymentIntentId' => $paymentIntent->id,
    //     ]);
    // }


//     public function createPaymentIntent(Request $request)
// {
//     Stripe::setApiKey('sk_test_51RCqM3FLwCatna2ik8SxyUUYcbizqdBwTjdavv9hkaMF6w5tLK5RAKMYxdcIRqlcc4JUL4VMGwem5yxGvUjsIFkH00GwZqlgEQ');

//     try {
//         $paymentIntent = PaymentIntent::create([
//             'amount' => 1000, // Amount in the smallest currency unit (e.g., cents for USD)
//             'currency' => 'usd',
//         ]);

//         return response()->json([
//             'clientSecret' => $paymentIntent->client_secret,
//         ]);
//     } catch (\Exception $e) {
//         return response()->json([
//             'error' => $e->getMessage(),
//         ], 500);
//     }
// }

public function createPaymentIntent(Request $request)
{
    Stripe::setApiKey('sk_test_51RCqM3FLwCatna2ik8SxyUUYcbizqdBwTjdavv9hkaMF6w5tLK5RAKMYxdcIRqlcc4JUL4VMGwem5yxGvUjsIFkH00GwZqlgEQ');
    // $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));


    $coach = User::find($request->coach_id);
    if (!$coach || !$coach->stripe_account_id) {
        return response()->json(['error' => 'Coach does not have Stripe connected account.'], 400);
    }

    try {
        $paymentIntent = PaymentIntent::create([
            // 'amount' => $request->amount,
            'amount' => 10000,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'application_fee_amount' => 100, // Optional: fee for your platform (e.g., $1)
            'transfer_data' => [
                'destination' => $coach->stripe_account_id,
            ],
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
        ], 500);
    }
}



    public function storePayment(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $request->validate([
            'amount' => 'required|numeric',
            'payment_id' => 'required|string',
            'email' => 'required|email',
            'coach_id' => 'required|exists:coaches,id',
            'coach_user_id' => 'required|exists:users,id',
        ]);

        Stripe::setApiKey('sk_test_51RCqM3FLwCatna2ik8SxyUUYcbizqdBwTjdavv9hkaMF6w5tLK5RAKMYxdcIRqlcc4JUL4VMGwem5yxGvUjsIFkH00GwZqlgEQ');

        try {
            $paymentId = explode('_secret', $request->payment_id)[0];
            $paymentIntent = PaymentIntent::retrieve($paymentId);

            if ($paymentIntent->status === 'succeeded') {
                // FIX SWAPPED VALUES here
                DB::table('payments')->insert([
                    'email'          => $request->email,
                    'amount'         => $request->amount,
                    'coach_id'       => $request->coach_id,        // Correct coach ID from Coaches table
                    'coach_user_id'  => $request->coach_user_id,   // Correct coach user ID from Users table
                    'payment_id'     => $paymentIntent->id,
                    'user_id'        => Auth::id(),
                    'status'         => 'succeeded',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                $schedule = CoachSchedule::where('created_by', Auth::id())->first();
            if ($schedule) {
                $schedule->payment_status = 'paid';
                $schedule->save();
            } else {
                return response()->json(['error' => 'Schedule not found'], 404);
            }

                // Fetch coach and user models
                $coachUser = User::find($request->coach_user_id);
                $coach     = Coach::find($request->coach_id);

                if (!$coachUser || !$coach) {
                    return response()->json(['error' => 'Invalid coach data'], 400);
                }

                // Send invoice
                // $this->sendInvoiceToCoach(Auth::user(), $coachUser, $coach, $request->amount, $paymentIntent->id);

                $this->sendInvoiceToCoach(
                    Auth::user(),
                    $coachUser,
                    $coach,
                    $paymentIntent->amount, // this is the correct amount in cents
                    $paymentIntent->id
                );
                

                return response()->json(['message' => 'Payment stored and invoice sent successfully']);
            }


            



            return response()->json(['error' => 'Payment not verified'], 400);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


 
    protected function sendInvoiceToCoach($player, $coachUser, $coach, $amount, $paymentId)
    {
        $pdf = Pdf::loadView('emails.payment-received', [
            'player'     => $player,
            'coach'      => $coachUser,
            'amount'     => $amount,
            'payment_id' => $paymentId,
        ]);
    
        $playerName = str_replace(' ', '_', strtolower($player->name));
        $fileName   = 'invoice_' . $playerName . '_' . $paymentId . '.pdf';
        $folderPath = public_path('uploads/PDF/PDF_Invoice');
        $filePath   = $folderPath . '/' . $fileName;
    
        $pdf->save($filePath);
    
        Mail::to($coachUser->email)
            ->cc($player->email)
            ->send(new PaymentReceived($player, $coachUser, $amount, $pdf, $paymentId));
    
        DB::table('invoices')->insert([
            'player_id'     => $player->id,
            'coach_user_id' => $coachUser->id,
            'coach_id'      => $coach->id,
            'amount'        => $amount,
            'payment_id'    => $paymentId,
            'file_path'     => 'uploads/PDF/PDF_Invoice/' . $fileName,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
    


    public function GetInvoiceRecord()
    {
        $invoices = Invoice::with(['player', 'coach'])
            ->where('coach_user_id', Auth::id())
            ->get();
    
        return response()->json([
            'success' => true,
            'message' => 'Record Get Successfully',
            'invoice' => $invoices
        ], 200);
    }

    public function ShowSingleInvoice(){
        $singal_invoice = Invoice::with(['player','coach'])->where('player_id',Auth::id())->get();
        return response()->json([
            'success' => true,
            'message' => 'Record Get SUccessfully',
            'invoice' => $singal_invoice
        ],201);
    }
    
    


}
