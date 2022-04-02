<?php

sleep(2); //等待异步回调

if ($order["status"]){
    $url=creat_callback($order);
    returnTemplate($url['return']);
}
else{
    sysmsg('校验失败');
}