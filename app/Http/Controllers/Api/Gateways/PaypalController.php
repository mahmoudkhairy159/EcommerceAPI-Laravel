<?php

namespace App\Http\Controllers\Api\Gateways;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Gateways\Paypal\CapturePaypalPaymentRequest;
use App\Http\Requests\Api\Gateways\Paypal\StorePaypalPaymentRequest;
use App\Models\Order;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Services\OrderCalculationService;
use App\Services\PurchaseOrderService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    use ApiResponseTrait;


    protected $_config;
    protected $guard;
    protected $paypalSetting;

    protected $provider;
    protected $paypalToken;
    protected $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->guard = 'user-api';
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->middleware('auth:' . $this->guard)->except(['success', 'cancel']);
        $this->paypalSetting = core()->getPaypalSetting();
        $this->provider = new PayPalClient();
        // $this->provider->setApiCredentials(config('paypal'));
        $this->provider->setApiCredentials($this->paypalConfig());
        $this->paypalToken = $this->provider->getAccessToken();
        $this->cartRepository = $cartRepository;
        $this->middleware('auth:' . $this->guard)->except(['success','cancel']);


    }


    public function paypalConfig()
    {

        return [
            'mode' => $this->paypalSetting->mode, // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
            'sandbox' => [
                'client_id' => $this->paypalSetting->client_id,
                'client_secret' => $this->paypalSetting->client_secret,
                'app_id' => $this->paypalSetting->app_id,
            ],
            'live' => [
                'client_id' => $this->paypalSetting->client_id,
                'client_secret' => $this->paypalSetting->client_secret,
                'app_id' => $this->paypalSetting->app_id,
            ],

            'payment_action' => $this->paypalSetting->payment_action, // Can only be 'Sale', 'Authorization' or 'Order'
            'currency' => $this->paypalSetting->currency,
            'notify_url' => $this->paypalSetting->notify_url,
            'locale' => $this->paypalSetting->locale,
            'validate_ssl' => $this->paypalSetting->validate_ssl, // Validate SSL when creating api client.
        ];
    }
    /**
     * Create a payment.
     */
    public function createPayment(StorePaypalPaymentRequest $request,OrderCalculationService $orderCalculationService)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();
            $cart = $this->cartRepository->getCartByUserId($data['user_id']);
            if (!$cart || $cart->cartProducts->isEmpty()) {
                return $this->errorResponse([], "The cart is empty.", statusCode: 404);

            }
            $amount=$orderCalculationService->calculateOrderAmount($data);
            $paymentData = [
                'intent' => 'CAPTURE',
                'application_context' => [
                    'return_url' => route('user-api.paypal.success') .
                        '?user_id=' . $data['user_id']
                        . '&user_address_id=' . $data['user_address_id']
                        . '&shipping_rule_id=' . $data['shipping_rule_id']
                        . '&code=' . $data['code'],

                    'cancel_url' => route('user-api.paypal.cancel'),
                ],
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => $this->paypalSetting->currency ?? 'USD',
                            'value' =>  $amount['amount'],
                        ],
                        'description' => $request->description,
                    ],
                ],
            ];

            $response = $this->provider->createOrder($paymentData);
            if (isset($response['id']) && $response['id'] != null) {
                foreach ($response['links'] as $link) {
                    if ($link['rel'] == 'approve') {
                        return $this->successResponse(
                            ['approval_url' => $link['href']],
                            "Payment created successfully."
                        );
                    }

                }
            }
            return redirect()->route('user-api.paypal.cancel');

            // dd($response);


        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage(), 500);
        }
    }

    /**
     * Capture a payment.
     */
    public function capturePayment(CapturePaypalPaymentRequest $request)
    {
        try {

            $request->validated();
            $orderId = $request->token;
            $response = $this->provider->capturePaymentOrder($orderId);

            return $this->successResponse($response, "Payment captured successfully.");
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage(), 500);
        }
    }

    /**
     * Handle payment success.
     */
    public function success(Request $request, PurchaseOrderService $purchaseOrderService,OrderCalculationService $orderCalculationService)
    {
        //dd($request->all());
        $response = $this->provider->capturePaymentOrder($request->token);
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $data = $request->all();
            $data['payment_method'] = Order::PAYMENT_METHOD_PAYPAL;
            $data['payment_status'] = Order::PAYMENT_STATUS_PAID;
            $created = $purchaseOrderService->purchaseOrder($data, $response['id'],$orderCalculationService);
            // return $this->successResponse([ $response], "Payment completed successfully.");
            return $this->successResponse("Payment completed successfully.");

        }
        return redirect()->route('user-api.paypal.cancel');
    }

    /**
     * Handle payment cancellation.
     */
    public function cancel()
    {
        return $this->errorResponse([], "Payment was cancelled.", 400);
    }






}
