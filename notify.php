<?php
require_once(PAY_ROOT."inc/utils.php");
if(!defined('IN_PLUGIN'))exit();

$data = $_POST;
if (empty($data))die("fail");

$utils=new Utils;

$signature = $utils->Sign($data, $channel["appkey"]);
if ($data['signature'] != $signature) { //不合法的数据
    echo 'fail';
} else {
    //合法的数据
    $trade_no = daddslashes($_POST["order_id"]);
    $api_trade_no = daddslashes($_POST["trade_id"]);
    if($DB->exec("update `pay_order` set `status` ='1' where `trade_no`='$trade_no'")){
        $DB->exec("update `pay_order` set `api_trade_no` ='$api_trade_no',`endtime` ='$date',`date` =NOW() where `trade_no`='$trade_no'");
        processOrder($order);
    }
    echo 'ok';
}