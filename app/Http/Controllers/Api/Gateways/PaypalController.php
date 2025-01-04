<?php

namespace App\Http\Controllers\Api\Gateways;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Gateways\Paypal\CapturePaypalPaymentRequest;
use App\Http\Requests\Api\Gateways\Paypal\StorePaypalPaymentRequest;
use App\Repositories\UserRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    use ApiResponseTrait;

    protected $userRepository;

    protected $_config;
    protected $guard;
    protected $provider;
    protected $paypalToken;

    public function __construct(UserRepository $userRepository)
    {
        $this->guard = 'user-api';
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->userRepository = $userRepository;
        $this->middleware('auth:' . $this->guard)->except(['success','cancel']);
        $this->provider = new PayPalClient();
        $this->provider->setApiCredentials(config('paypal'));
        $this->paypalToken = $this->provider->getAccessToken();

    }



    /**
     * Create a payment.
     */
    public function createPayment(StorePaypalPaymentRequest $request)
    {
        try {
            $request->validated();
            $data = [
                'intent' => 'CAPTURE',
                'application_context' => [
                    'return_url' => route('user-api.paypal.success'),
                    'cancel_url' => route('user-api.paypal.cancel'),
                ],
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => $request->currency ?? 'USD',
                            'value' => $request->amount,
                        ],
                        'description' => $request->description,
                    ],
                ],
            ];

            $response = $this->provider->createOrder($data);
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
    public function success(Request $request)
    {
        //dd($request->all());
        $response = $this->provider->capturePaymentOrder($request->token);
        if(isset($response['status']) && $response['status'] =='COMPLETED'){
            // return $this->successResponse([ $response], "Payment completed successfully.");
             return $this->successResponse( "Payment completed successfully.");

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
