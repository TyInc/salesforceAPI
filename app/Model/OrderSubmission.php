<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderSubmission extends Model
{
    /**
     * Look up rate
     *
     * @param $request
     * @return array
     */
    static public function submitOrder($request) {
        $result = [
            'status' => 'error',
            'errortext' => ''
        ];

        $defaultPayload = self::orderPayload();

        foreach ($request as $key => $value) {
            if (isset($defaultPayload[$key])) {
                $defaultPayload[$key] = str_replace("'","''",$value);   //Change ' to '' to match Oracle syntax
            }
        }

        $sqlBindingValues = [];
        foreach ($defaultPayload as $key => $value) {
            if (is_numeric($value) && !in_array($key,['ship_to_zip','bill_to_zip','item_info_string'])) {
                $sqlBindingValues[] = $value;
            } else {
                $sqlBindingValues[] = "'" . $value . "'";
            }
        }

        $sql = "SELECT commerce_cloud_pkg_db.load_order_fn(" . str_ireplace("'null'", "null", implode(',', $sqlBindingValues)). ") Sales FROM dual";

        $query = DB::select(
            $sql
        );

        $resultArray = explode('&', $query[0]->sales);
        foreach ($resultArray as $resultData) {
            $data = explode("=", $resultData);
            $result[$data[0]] = $data[1];
        }

        return $result;
    }

    /**
     * Return default order payload
     *
     * @return array
     */
     static private function orderPayload() {
        return [
            'order_number' => 'null',
            'customer_number' => 'null',
            'addr_code' => 'null',
            'vendor_number' => 'null',
            'customer_email' => 'null',
            'date_created' => 'null',
            'bill_to_first_name' => 'null',
            'bill_to_last_name' => 'null',
            'bill_to_address1' => 'null',
            'bill_to_address2' => 'null',
            'bill_to_address3' => 'null',
            'bill_to_address4' => 'null',
            'bill_to_city' => 'null',
            'bill_to_state' => 'null',
            'bill_to_zip' => 'null',
            'bill_to_country_code' => 'null',
            'bill_to_phone' => 'null',
            'ship_to_first_name' => 'null',
            'ship_to_last_name' => 'null',
            'ship_to_name2' => 'null',
            'ship_to_address1' => 'null',
            'ship_to_address2' => 'null',
            'ship_to_address3' => 'null',
            'ship_to_address4' => 'null',
            'ship_to_city' => 'null',
            'ship_to_state' => 'null',
            'ship_to_zip' => 'null',
            'ship_to_country_code' => 'null',
            'ship_to_phone' => 'null',
            'ship_method' => 'null',
            'customer_birthday' => 'null',
            'customer_gender' => 'null',
            'organization_id' => 'null',
            'control_number' => 'null',
            'freight_amt' => 'null',
            'tax_amt' => 'null',
            'promo_code' => 'null',
            'ord_disc_amt' => 'null',
            'cc_token' => 'null',
            'extra_varchar_1' => 'null',
            'extra_varchar_2' => 'null',
            'extra_varchar_3' => 'null',
            'extra_varchar_4' => 'null',
            'extra_varchar_5' => 'null',
            'extra_varchar_6' => 'null',
            'extra_varchar_7' => 'null',
            'extra_varchar_8' => 'null',
            'extra_varchar_9' => 'null',
            'extra_varchar_10' => 'null',
            'extra_number_1' => 'null',
            'extra_number_2' => 'null',
            'extra_number_3' => 'null',
            'extra_number_4' => 'null',
            'extra_number_5' => 'null',
            'extra_number_6' => 'null',
            'extra_number_7' => 'null',
            'extra_number_8' => 'null',
            'extra_number_9' => 'null',
            'extra_number_10' => 'null',
            'extra_date_1' => 'null',
            'extra_date_2' => 'null',
            'extra_date_3' => 'null',
            'extra_date_4' => 'null',
            'extra_date_5' => 'null',
            'extra_date_6' => 'null',
            'extra_date_7' => 'null',
            'extra_date_8' => 'null',
            'extra_date_9' => 'null',
            'extra_date_10' => 'null',
            'item_info_string' => 'null',
            'test_live_mode' => 'LIVE'
            ];
    }
}
