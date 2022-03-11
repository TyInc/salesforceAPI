<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShippingRate extends Model
{
    /**
     * Look up rate
     *
     * @param $request
     * @return array
     */
    static public function getRate($request) {
        $result = [
            'status' => 'error',
            'freight' => null,
            'errortext' => ''
        ];

        $query = DB::select(
            "
SELECT commerce_cloud_pkg_db.get_freight_fn(
    '',
    ?,
    ?,
    ?,
    '',
    ?,
    '',
    ?,
    ?,
    ?,
    ?,
    '',
    ?,
    '',
    '',
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null) RATE
 FROM dual",
            [
                $request['method'],
                $request['items'],
                $request['name'],
                $request['street'],
                $request['city'],
                $request['state'],
		$request['zipcode'],
		$request['country'],
                $request['contact_no']
            ]
    );

        $resultArray = explode('&', $query[0]->rate);
        foreach ($resultArray as $resultData) {
		$data = explode("=", $resultData);
		$result[$data[0]] = $data[1];
        }
        return $result;
    }
}
