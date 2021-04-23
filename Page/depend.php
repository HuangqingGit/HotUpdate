<?php
require_once __DIR__ . '/../../../../usr/themes/Joe/core/function.php';
/**
 * 发起http请求
 * @param string $req_url 请求地址
 * @param array $req_data 键值对数据
 * @return object
 */
function send_request($req_url, $req_data)
{
    $postdata = http_build_query($req_data);
    $options = array(
        'http' => array(
            'method' => 'GET',
            'header' => 'Content-type:application/json;charset=UTF-8',
            'content' => $postdata,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        )
    );
    $context = stream_context_create($options);
    return $result = file_get_contents($req_url, false, $context);
}
?>