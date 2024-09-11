<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mobile = isset($_POST['user']) ? trim($_POST['user']) : '';
    $amount = isset($_POST['am']) ? trim($_POST['am']) : '';

    // Validate the amount
    if ($amount < 100) {
        echo 'Error: Amount must be at least 100.';
        exit;
    }

    // Generate a random 6-digit number for orderNo
    $randomNumber = mt_rand(100000, 999999);
    $orderNo = "GINIX" . $mobile . "XX" . $randomNumber;

    // Prepare the parameters for the API request
    $params = [
        "orderNo" => $orderNo,
        "memberCode" => "172560446532934",
        "passageInCode" => "paystack001",
        "orderAmount" => $amount,
        "notifyurl" => "https://payment.goodtime365.shop/payfiles/Lpay/notify.php",
        "callbackurl" => "https://payment.goodtime365.shop/payfiles/Lpay/callback.php",
        "productName" => "测试商品",
        "datetime" => time(),
        "attach" => "ceshi"
    ];

    // Secret key
    $secret = 'ab6e36aff909432b989c73aa1a0aae36';

    // Generate the signature
    $signature = generateSignature($params, $secret);

    // Convert parameters to JSON
    $jsonParams = json_encode($params);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://admin.tpaycloud.com/v1/inorder/addInOrder',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $jsonParams,
        CURLOPT_HTTPHEADER => array(
            'sign: ' . $signature,
            'User-Agent: Apifox/1.0.0 (https://apifox.com)',
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        echo 'Curl error: ' . curl_error($curl);
    } else {
        $responseData = json_decode($response, true);

        if (isset($responseData['ok']) && $responseData['ok'] === true) {
            $orderUrl = $responseData['data']['orderurl'];

            // Redirect to the order URL
            echo "<script type='text/javascript'>window.location.href = '$orderUrl';</script>";
        } else {
            echo 'Error: ' . $responseData['msg'];
        }
    }

    curl_close($curl);
}

function generateSignature($params, $secret) {
    ksort($params);
    $signString = '';
    foreach ($params as $key => $value) {
        $signString .= "$key=$value&";
    }
    $signString .= "key=$secret";
    return strtoupper(md5($signString));
}
?>
