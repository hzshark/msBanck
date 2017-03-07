<?php
function getMillisecond()
{
    list ($s1, $s2) = explode(' ', microtime());
    return (float) sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
}

function generate_guid($namespace = '')
{
    static $guid = '';
    $uid = uniqid("", true);
    $data = $namespace;
    $data .= $_SERVER['REQUEST_TIME'];
    $data .= $_SERVER['HTTP_USER_AGENT'];
    $data .= $_SERVER['LOCAL_ADDR'];
    $data .= $_SERVER['LOCAL_PORT'];
    $data .= $_SERVER['REMOTE_ADDR'];
    $data .= $_SERVER['REMOTE_PORT'];
    $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
    return $hash;
}

function generateOrderno()
{
    $fdate = F("FDATE");
    $endtoday = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;
    if ($fdate['time'] != $endtoday) {
        $fdate['time'] = $endtoday;
        $fdate['code'] = '1';
    }
    if ($fdate['code'] < 1 || $fdate['code'] > 999) {
        $fdate['code'] = '1';
    }

    $date['orderstatus'] = '1';
    $date['orderno'] = date('Ymd') . str_pad($fdate['code'], 3, "0", STR_PAD_LEFT);
    $fdate['code'] = $fdate['code'] + 1;
    F("FDATE", $fdate);
    return $date['orderno'];
}

function get($url, $header = array(), $timeout = 10)
{
    // 初始化
    $curl = curl_init();
    // 设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    if (count($header) > 0) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    }
    // 设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, false);
    // 设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); // 30秒超时
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

    // 执行命令
    $data = curl_exec($curl);
    // 关闭URL请求
    curl_close($curl);
    // 获得的数据
    return $data;
}

function get_proxy($url, $proxy, $header = array(), $timeout = 10)
{
    // 初始化
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_PROXY, $proxy);
    // 设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    if (count($header) > 0) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    }
    // 设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, false);
    // 设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); // 30秒超时
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

    // 执行命令
    $data = curl_exec($curl);
    // 关闭URL请求
    curl_close($curl);
    // 获得的数据
    return $data;
}

function get_ssl_proxy($apiurl, $proxy, $header = array(), $timeout = 30)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_URL, $apiurl);
    if (count($header) > 0) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    // 设置头文件的信息作为数据流输出
    curl_setopt($ch, CURLOPT_HEADER, false);
    // 设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // 30秒超时
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    // 不验证https证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
        'Accept: application/json'
    ));
    // 发送数据
    $response = curl_exec($ch);
    // 不要忘记释放资源
    curl_close($ch);
    return $response;
}

function get_ssl($apiurl, $header = array(), $timeout = 30)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiurl);
    if (count($header) > 0) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    // 设置头文件的信息作为数据流输出
    curl_setopt($ch, CURLOPT_HEADER, false);
    // 设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // 30秒超时
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    // 不验证https证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
        'Accept: application/json'
    ));
    // 发送数据
    $response = curl_exec($ch);
    // 不要忘记释放资源
    curl_close($ch);
    return $response;
}

function get1($url, $header = array(), $timeout = 10)
{
    // 初始化
    $curl = curl_init();
    // 设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    if (count($header) > 0) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    }
    // 设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, true);
    // 设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); // 30秒超时
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

    // 执行命令
    $data = curl_exec($curl);
    // 关闭URL请求
    curl_close($curl);
    // 获得的数据
    return $data;
}

/*
 * -----------------------------------------------------------
 * 函数名称：isPhone
 * 简要描述：检查输入的是否为电话
 * 输入：string
 * 输出：boolean
 * 修改日志：------
 * -----------------------------------------------------------
 */
function isPhone($val)
{
    if (preg_match("/^1[34578]\d{9}$/", $val))
        return true;
    return false;
}

function is_numericStart($str)
{
    if (is_numeric(substr($str, 0, 1))) {
        return true;
    }
    return false;
}

function get_jsondata($string)
{
    $string = trim($string);
    $strlen = strlen($string);
    return substr($string, 1, $strlen - 2);
}

function post($apiurl, array $params = array(), $timeout = 30)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiurl);
    // 以返回的形式接收信息
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 设置为POST方式
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    // 不验证https证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
        'Accept: application/json'
    ));
    // 发送数据
    $response = curl_exec($ch);
    // 不要忘记释放资源
    curl_close($ch);
    return $response;
}

function post_proxy($apiurl, $proxy, array $params = array(), $timeout = 30)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_URL, $apiurl);
    // 以返回的形式接收信息
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 设置为POST方式
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    // 不验证https证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
        'Accept: application/json'
    ));
    // 发送数据
    $response = curl_exec($ch);
    // 不要忘记释放资源
    curl_close($ch);
    return $response;
}

function http_post_data($url, $data_string)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Content-Length: ' . strlen($data_string)
    ));
    ob_start();
    curl_exec($ch);
    $return_content = ob_get_contents();
    ob_end_clean();

    $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    return array(
        $return_code,
        $return_content
    );
}
