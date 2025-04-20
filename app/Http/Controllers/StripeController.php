<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\CheckoutForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class StripeController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        try {

            Stripe::setApiKey(env('STRIPE_SECRET'));
    
            $amount = $request->input('amount');
    
            // Stripe requires amount in **cents**
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'transfer_data' => [
                    'destination' => $coachUser->stripe_account_id, // ✅ Send money to coach
                ],
            ]);
    
            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
    
        } catch (\Exception $e) {
            \Log::error('Stripe Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }    

    // Step 2: Store payment after confirmation
    public function storePayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'payment_id' => 'required|string',
            'email' => 'required|email',
        ]);



        Stripe::setApiKey(env('STRIPE_SECRET'));
        $paymentIntent = PaymentIntent::retrieve($request->payment_id);

        if ($paymentIntent->status === 'succeeded') {
            DB::table('payments')->insert([
                'email' => $request->email,
                'amount' => $request->amount,
                'payment_id' => $request->payment_id,
                'status' => 'succeeded',
                'created_at' => now(),
            ]);

            return response()->json(['message' => 'Payment stored successfully']);
        }

        return response()->json(['error' => 'Payment not verified'], 400);
    }
    
}