<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Model\OrderRefund;

class OrderRefundController extends Controller
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
     * Refund
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
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

            // KL: Get payload
            $payload = $requestObject['notificationItems'][0]['NotificationRequestItem'];

            // Now call the store procedure
            $selectedResult = OrderRefund::processRefund($payload);

            if ($selectedResult['Refund'] == 'SUCCESS') {
                $result = '[accepted]';
            } else {
                $result = '[rejected]';
            }

        } catch (\Exception $exception) {
            $result['message'] = $exception->getMessage();
        }

        return response($result, 200);

    }

    /**
     * Return program details
     *
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function info()
    {
        return response('Order Refund MVP');
    }
}
