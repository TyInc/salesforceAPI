<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Model\OrderSubmission;
use Illuminate\Support\Facades\Log;

class OrderSubmissionController extends Controller
{
    const REQUIRED_FIELDS = [
        'order_number', 'customer_number', 'addr_code', 'vendor_number',
        'customer_email', 'date_created',

        'bill_to_first_name', 'bill_to_last_name', 'bill_to_address1', 'bill_to_city',
        'bill_to_state', 'bill_to_zip', 'bill_to_country_code', 'bill_to_phone',

        'ship_to_first_name', 'ship_to_last_name', 'ship_to_address1', 'ship_to_city',
        'ship_to_state', 'ship_to_zip', 'ship_to_country_code', 'ship_to_phone',

        'ship_method',

        // Do we really need this?
        //'customer_birthday', 'customer_gender',

        'organization_id', 'control_number',
        'freight_amt', 'tax_amt', 'promo_code', 'ord_disc_amt', 'cc_token',

        'item_info_string'
        ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Submit Order
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $result = [
            'status' => 500,
            'message' => ''
        ];

        try {
            // KL: Get the request in array
            $requestObject = json_decode($request->getContent(), true);

            // Validate if we get everything we need
            foreach (self::REQUIRED_FIELDS as $validationItem) {
                if (!array_key_exists($validationItem, $requestObject)) {
                    $result['status'] = 400;
                    throw new Exception("missing required field: " . $validationItem);
                }
            }

            // Now call the store procedure
            $selectedResult = OrderSubmission::submitOrder($requestObject);

            // Format the result
            $result['status'] = 200;
            $result['order'] = $selectedResult;

        } catch (\Exception $exception) {
            $result['message'] = $exception->getMessage();
            Log::error($exception);
        }

        return response()->json($result, $result['status']);

    }

    /**
     * Return program details
     *
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function info()
    {
        return response('Order Submission MVP');
    }
}
