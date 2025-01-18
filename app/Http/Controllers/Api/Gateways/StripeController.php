<?php

namespace App\Http\Controllers\Api\Gateways;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Gateways\Stripe\CaptureStripePaymentRequest;
use App\Http\Requests\Api\Gateways\Stripe\StoreStripePaymentRequest;
use App\Models\Order;
use App\Repositories\CartRepository;
use App\Services\PurchaseOrderService;
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

    protected $cartRepository;

    protected $_config;
    protected $guard;
    protected $provider;
    protected $stripeToken;
    protected $stripeSetting;

    public function __construct(CartRepository $cartRepository)
    {
        $this->guard = 'user-api';
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->cartRepository = $cartRepository;
        $this->middleware('auth:' . $this->guard)->except(['success', 'cancel']);
        $this->stripeSetting = core()->getStripeSetting();
        $this->provider = new Stripe();
        // $this->provider->setApiKey(config('stripe.sk'));
        $this->provider->setApiKey($this->stripeSetting->client_secret);


    }



    /**
     * Create a payment.
     */
    public function createPayment(StoreStripePaymentRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();
            $cart = $this->cartRepository->getCartByUserId($data['user_id']);
            if (!$cart || $cart->cartProducts->isEmpty()) {
                return $this->errorResponse([], "The cart is empty.", statusCode: 404);

            }
            $data = [
                'payment_method_types' => ['card'], // Only card payments for now
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => $this->stripeSetting->currency ?? 'usd',
                            'product_data' => [
                                'name' => 'Gimme money !!!!',
                            ],
                            'unit_amount' => $data['amount'] * 100, // Stripe expects the amount in cents
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment', // Use 'payment' mode for one-time payments
                'success_url' => route('user-api.stripe.success')
                    . '?session_id={CHECKOUT_SESSION_ID}'
                    . '&user_id=' . $data['user_id']
                    . '&user_address_id=' . $data['user_address_id']
                    . '&shipping_rule_id=' . $data['shipping_rule_id']
                    . '&code=' . $data['code'],
                'cancel_url' => route('user-api.stripe.cancel'),
            ];


            $response = Session::create($data);
            if (isset($response['url']) && $response['url'] != null) {
                return $this->successResponse(
                    ['approval_url' => $response['url']],
                    "Payment created successfully."
                );
            }
            return redirect()->route('cart-api.stripe.cancel');

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
                return redirect()->route('cart-api.stripe.cancel');
            }

        } catch (Exception $e) {
            // Handle any exceptions that may occur during the capture process
            return $this->errorResponse([], $e->getMessage(), 500);
        }
    }

    /**
     * Handle payment success.
     */
    public function success(Request $request, PurchaseOrderService $purchaseOrderService)
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
                $data = $request->all();
                $data['payment_method'] = Order::PAYMENT_METHOD_PAYPAL;
                $data['payment_status'] = Order::PAYMENT_STATUS_PAID;
                $created = $purchaseOrderService->purchaseOrder($data, $sessionId);
                // Payment was successful
                return $this->successResponse("Payment completed successfully.");
            } else {
                // Payment was not successful
                return redirect()->route('cart-api.stripe.cancel');
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
