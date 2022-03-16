<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderStatus extends Model
{
    /**
     * Look up rate
     *
     * @param $request
     * @return array
     */
    static public function getOrderStatus($order_id) {
        $result = [
            'OrderStatus' => 'error',
            'errortext' => ''
        ];

        $sql = "SELECT commerce_cloud_pkg_db.get_cloud_order_Status_fn('" . $order_id . "', 'LIVE') OrderStatus FROM dual";

        $query = DB::select(
            $sql
        );
    $data = "";
	$resultArray = explode('=', $query[0]->orderstatus,2);
        foreach (explode(" ", $resultArray[1]) as $resultData) {
            $data .= "<a>" . $resultData . "<a></br>";
        }
        $result[$resultArray[0]] = $data;

        return $result;
    }
}