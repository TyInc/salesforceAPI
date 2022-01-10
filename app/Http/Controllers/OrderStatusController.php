<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Model\OrderStatus;

class OrderStatusController extends Controller
{

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
     * @param Request $request
     * @param $order_number
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $order_number)
    {
        $result = [
            'status' => 500,
            'message' => ''
        ];

        try {
            // KL: Get the request in array
            $requestObject = json_decode($request->getContent(), true);

            // Validate if we get everything we need
            if (strlen(trim($order_number)) == 0) {
                throw new Exception("missing required field: order number");
            }

            // Now call the store procedure
            $selectedResult = OrderStatus::getOrderStatus($order_number);

            // Format the result
            $result['status'] = 200;
            $result['order'] = $selectedResult;

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
        return response('Order Status MVP');
    }
}
