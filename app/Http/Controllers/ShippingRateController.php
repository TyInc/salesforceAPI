<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Model\ShippingRate;

class ShippingRateController extends Controller
{
    const REQUIRED_FIELDS = ['method', 'items', 'name', 'street', 'city', 'state', 'country', 'zipcode', 'contact_no'];

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
     * Lookup Shipping Rate
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
                    throw new Exception("missing required field: " . $validationItem);
                }
            }

            // Now call the store procedure
            $selectedResult = ShippingRate::getRate($requestObject);

            // Format the result
            $result['status'] = 200;
            $result['rate'] = $selectedResult;

        } catch (\Exception $exception) {
            $result['message'] = $exception->getMessage();
        }

        return response()->json($result);

    }

    /**
     * Return program details
     *
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function info()
    {
        return response('Shipping Rate Lookup MVP');
    }
}
