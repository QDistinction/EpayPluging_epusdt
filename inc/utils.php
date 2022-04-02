<?php

class Utils
{

    /**
     * 除去空值和签名参数
     * @param $para 参数数组
     * return 去掉空值与签名参数后的新数组
     */
    function paraFilter($para)
    {
        $para_filter = array();
        foreach ($para as $key => $val) {
            if ($key == "signature" || $val == "") continue;
            else $para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    /**
     * 字符串拼接 key=val&
     * @param $para 需要拼接的数组
     * return 拼接后字符串
     */
    function createLinkstring($para)
    {
        $arg  = "";
        foreach ($para as $key => $val) {
            $arg .= $key . "=" . $val . "&";
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, -1);

        return $arg;
    }

    /**
     * 签名
     * @param $parameter 参数
     * @param $signkey API认证token
     */
    function Sign($parameter, $key)
    {
        $para = $this->paraFilter($parameter);
        ksort($para);//排序数组
        reset($para);
        return strtolower(md5($this->createLinkstring($para).$key));
    }

    /**
     * 发送POST请求
     * @param $url 完整URL
     * @param $para 数据
     * @param $header header 默认值：application/json
     * return 返回的数据
     */
    function Curl_post($url, $para = '', $header = array('Content-Type: application/json; charset=UTF-8'))
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //SSL证书认证false
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); //严格认证false
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); //设置HTTPHEADER
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 显示输出结果
        curl_setopt($curl, CURLOPT_POST, true); // post传输数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $para); // post传输数据
        $res = curl_exec($curl);
        //var_dump(curl_error($curl));
        curl_close($curl);

        return $res;
    }

    /**
     * 构造跳转用JavaScript
     * @param $url 跳转url
     */
    function make_redirect($url)
    {
        return '<script type="text/javascript">window.location.href="' . $url . '";</script>';
    }
}
