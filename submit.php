<?php
if (!defined('IN_PLUGIN')) exit();

try {
    require_once(PAY_ROOT . "inc/utils.php");
    $utils = new Utils;

    $parameter = array(
        "order_id"    => TRADE_NO,
        "amount"    => (float)$order['realmoney'],
        "notify_url"    => $conf["localurl"] . 'pay/epusdt/notify/' . TRADE_NO . '/',
        "redirect_url"    => $conf["localurl"] . 'pay/epusdt/return/' . TRADE_NO . '/'
    );

    $parameter["signature"] = $utils->Sign($parameter, $channel["appkey"]);
    
    $json = json_encode($parameter, JSON_UNESCAPED_SLASHES);

    $res = json_decode(
        $utils->Curl_post(
            $channel["appurl"] . "/api/v1/order/create-transaction",
            $json,
        ),
        true
    );

    if ($res["status_code"] == 200) {
        $pay_url = $res['data']['payment_url'];
        echo $utils->make_redirect($pay_url);
    } else {
        echo "Error - " . $res["message"];
    }
} catch (\Exception $e) {
    echo "Error - " . $e->getMessage();
}
