<?php 
if(!defined('IN_PLUGIN'))exit();
if(empty($_POST))die("fail");

require_once(PAY_ROOT."inc/utils.php");
$utils=new Utils;

$data = $_POST;
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