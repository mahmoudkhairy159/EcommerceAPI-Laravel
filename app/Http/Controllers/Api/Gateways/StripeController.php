<?php

namespace App\Http\Controllers\Api\Gateways;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Gateways\Stripe\CaptureStripePaymentRequest;
use App\Http\Requests\Api\Gateways\Stripe\StoreStripePaymentRequest;
use App\Repositories\UserRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeController extends Controller
{
    use ApiResponseTrait;

    protected $userRepository;

    protected $_config;
    protected $guard;
    protected $provider;
    protected $stripeToken;

    public function __construct(UserRepository $userRepository)
    {
        $this->guard = 'user-api';
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->userRepository = $userRepository;
        $this->middleware('auth:' . $this->guard)->except(['success','cancel']);
        $this->provider = new Stripe();
        $this->provider->setApiKey(config('stripe.sk'));


    }



    /**
     * Create a payment.
     */
    public function createPayment(StoreStripePaymentRequest $request)
    {
        try {
            $request->validated();
            $data = [
                'payment_method_types' => ['card'], // Only card payments for now
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => $request->currency ?? 'usd',
                            'product_data' => [
                                'name' => 'Gimme money !!!!',
                            ],
                            'unit_amount' => $request->amount * 100, // Stripe expects the amount in cents
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment', // Use 'payment' mode for one-time payments
                'success_url' => route('user-api.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('user-api.stripe.cancel'),
            ];


           $response= Session::create($data);
            if (isset($response['url']) && $response['url'] != null) {
                        return $this->successResponse(
                            ['approval_url' =>$response['url']],
                            "Payment created successfully."
                        );
            }
            return redirect()->route('user-api.stripe.cancel');

            // dd($response);


        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage(), 500);
        }
    }



public function capturePayment(CaptureStripePaymentRequest $request)
{
    try {
        // Ensure session_id is passed from the request
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return $this->errorResponse([], 'Session ID is missing', 400);
        }


        // Retrieve the session details from Stripe using the session_id
        $session = Session::retrieve($sessionId);

        // Check if the payment has been successfully completed
        if ($session->payment_status == 'paid') {
            // The payment was successful
            // You can update your database, send notifications, or perform other actions
            return $this->successResponse("Payment captured successfully.");
        } else {
            // The payment was not successful
            return redirect()->route('user-api.stripe.cancel');
        }

    } catch (Exception $e) {
        // Handle any exceptions that may occur during the capture process
        return $this->errorResponse([], $e->getMessage(), 500);
    }
}

    /**
     * Handle payment success.
     */
    public function success(Request $request)
{
    try {
        // Ensure session_id is present in the query parameters
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return $this->errorResponse([], 'Session ID is missing', 400);
        }

        // Retrieve the session details from Stripe using the session ID
        $session = Session::retrieve($sessionId);

        // Check the session's payment status
        if ($session->payment_status == 'paid') {
            // Payment was successful
            return $this->successResponse("Payment completed successfully.");
        } else {
            // Payment was not successful
            return redirect()->route('user-api.stripe.cancel');
        }

    } catch (Exception $e) {
        // Handle errors (e.g., invalid session ID, network issues)
        return $this->errorResponse([], $e->getMessage(), 500);
    }
}
    /**
     * Handle payment cancellation.
     */
    public function cancel()
    {
        return $this->errorResponse([], "Payment was cancelled.", 400);
    }

}
