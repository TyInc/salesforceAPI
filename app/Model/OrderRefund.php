<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderRefund extends Model
{
    const HMAC_KEY = '44782DEF547AAA06C910C43932B1EB0C71FC68D9D0C057550C48EC2ACF6BA056';
    /**
     * Look up rate
     *
     * @param $request
     * @return array
     */
    static public function processRefund($payload) {
        $result = [
            'Refund' => 'error',
            'errortext' => ''
        ];

        // KL: Calculate the HMAC
        $hmac = $payload['additionalData']['hmacSignature'];
        $hmacHashArray = [
            $payload['pspReference'],
            $payload['originalReference'],
            $payload['merchantAccountCode'],
            $payload['merchantReference'],
            $payload['amount']['value'],
            $payload['amount']['currency'],
            $payload['eventCode'],
            $payload['success']
        ];
        $signingString = implode(':', $hmacHashArray);
        $binaryHmacKey = pack("H*" , self::HMAC_KEY);
        $binaryHmac = hash_hmac('sha256', $signingString, $binaryHmacKey, true);
        $signature = base64_encode($binaryHmac);

        if ($hmac != $signature) {
            $result['errortext'] = 'Invalid Signiture';
            return $result;
        }

        // KL: Now save the data
        $defaultPayload = self::refundPayload();

        $defaultPayload['eventCode'] = $payload['eventCode'];
        $defaultPayload['evenDate'] = $payload['evenDate'];
        $defaultPayload['merchantAccountCode'] = $payload['merchantAccountCode'];
        $defaultPayload['merchantReference'] = $payload['merchantReference'];
        $defaultPayload['originalReference'] = ''; // Does not exists from the payload
        $defaultPayload['paymentMethod'] = $payload['paymentMethod'];
        $defaultPayload['pspReference'] = $payload['pspReference'];
        $defaultPayload['reason'] = ''; // Does not exists from the payload
        $defaultPayload['success'] = $payload['success'];

        $sqlBindingValues = [];
        foreach ($defaultPayload as $key => $value) {
            if (is_numeric($value)) {
                $sqlBindingValues[] = $value;
            } else {
                $sqlBindingValues[] = "'" . $value . "'";
            }
        }

        $sql = "SELECT commerce_cloud_pkg_db.cloud_refund_response_fn(" . str_ireplace("'null'", "null", implode(',', $sqlBindingValues)). ") Refund FROM dual";

        $query = DB::select(
            $sql
        );

        $resultArray = explode('&', $query[0]->refund);
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
     static private function refundPayload() {
        return [
            'eventCode' => 'null',
            'evenDate' => 'null',
            'merchantAccountCode' => 'null',
            'merchantReference' => 'null',
            'originalReference' => 'null',
            'paymentMethod' => 'null',
            'pspReference' => 'null',
            'reason' => 'null',
            'success' => 'null',
            'test_live_mode' => 'LIVE'
            ];
    }
}
