<?php
return array(
    // '配置项'=>'配置值'
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        '__CSS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/DEFAULT_THEME/css',
        '__JS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/DEFAULT_THEME/js',
        '__IMG__' => __ROOT__ . '/Public/' . MODULE_NAME . '/DEFAULT_THEME/images',
        '__THEME__' => __ROOT__ . '/Public/' . MODULE_NAME . '/DEFAULT_THEME'
    ),
    'DEFAULT_THEME' => 'Default', // 当模块中没有设置主题，则模块主题会设置为此处设置的主题,主题名和模块名不能重复，如不能采用“Home”

    'SITE_NAME' => '蓝河马-商家管理平台',

    // 上传相关
    'UPLOADER_URL' => 'DealUpload/upload',
    'AMAP' => array(
        'KEY' => '48eaa8c9d43f9451675fca7c18c81dc2'
    ),
    'IMG_PATH' => __ROOT__ . '/Uploads/',
    'MP3_PATH' => __ROOT__ . '/Uploads/',
    'LOG_LEVEL' => 'EMERG,ALERT,CRIT,ERR,WARN,NOTICE,INFO,DEBUG,SQL', // 记录全部错误

    'MANAGE_ROOT_ID' => array(
        2,
        2451
    ),

    'CODE_MODIFIER' => array(
        2
    ),

    'DW_ECHO' => true,

    'CHROME_SHOW_PAGE_TRACE' => false,
    'CHROME_SHOW_PAGE_TRACE_COOKIE' => false,

    /*RESOURCE_VERSION资源版本号*/
    'RESOURCE_VERSION' => '151124_1',
    'RESOURCE_VERSION_SCOPE_STATIC' => false,

    // 授权相关
    'DEAL_AUTH_ENABLE' => true, // 授权开启
    'DEAL_AUTH_WHITE_LIST' => array(
        60,
        6,
        126,
        142,
        58
    ), // 不受 DEAL_AUTH_ENABLE 影响
    'DEAL_DOWNLOAD_STORE_ENABLE' => false, // 开启下载门店
    'DEAL_DOWNLOAD_STORE_WHITE_LIST' => array(
        60,
        6,
        126,
        142,
        58
    ), // 不受 DEAL_DOWNLOAD_STORE_ENABLE 影响

    // CMBC 设置
    'platformId' => 'A00012017040000000934',
    'operId' => '10010A0001',
    'dataSrc' => '2',
    'BASE64P12DATA' => 'MIIDBAIBATBHBgoqgRzPVQYBBAIBBgcqgRzPVQFoBDD1wyWi2YE3uhezDCnssTsZMzI/5zjcF9S2cLosHWl9HlnE/OuFxk+Gd3aiu6lZzEwwggK0BgoqgRzPVQYBBAIBBIICpDCCAqAwggJEoAMCAQICBRCFBBk0MAwGCCqBHM9VAYN1BQAwJTELMAkGA1UEBhMCQ04xFjAUBgNVBAoMDUNGQ0EgU00yIE9DQTEwHhcNMTcwNDEwMDYxODM0WhcNMjIwNDA3MDEzMzIyWjCBhjELMAkGA1UEBhMCQ04xEjAQBgNVBAoMCUNGQ0EgT0NBMTENMAsGA1UECwwEQ01CQzEZMBcGA1UECwwQT3JnYW5pemF0aW9uYWwtMTE5MDcGA1UEAwwwMDMwNUA4OTEzMzAxMTAzNDE4NTgwNTY2QEEwMDAxMjAxNzA0MDAwMDAwMDkzNEAxMFkwEwYHKoZIzj0CAQYIKoEcz1UBgi0DQgAEtw2IzVTTC+L6bL3fYPGMMWK5Emn/x7YnMmWerzm1FaHjp8ngJQey1Nuwju4u8SyuakdaxrbwGxqQF9mxBWFMoKOB/DCB+TAfBgNVHSMEGDAWgBRck1ggWiRzVhAbZFAQ7OmnygdBETBIBgNVHSAEQTA/MD0GCGCBHIbvKgEBMDEwLwYIKwYBBQUHAgEWI2h0dHA6Ly93d3cuY2ZjYS5jb20uY24vdXMvdXMtMTQuaHRtMAkGA1UdEwQCMAAwNgYDVR0fBC8wLTAroCmgJ4YlaHR0cDovL2NybC5jZmNhLmNvbS5jbi9TTTIvY3JsNjE3LmNybDALBgNVHQ8EBAMCA+gwHQYDVR0OBBYEFKyLPrye34FRx9DHZaRs55oayGb3MB0GA1UdJQQWMBQGCCsGAQUFBwMCBggrBgEFBQcDBDAMBggqgRzPVQGDdQUAA0gAMEUCIQCNYrqJGgzjdFG6+Dw7ziYdplc+Npd+W3UCS7vUkymwYAIgMfVMy35BaWvuEAl/4J2//i0X59XzZOEM3i3dbgk7pec=',
    // 私钥密码
    'P12PASSWORD' => '88518503',
    // 民生公钥
    'BASE64X509CERTDATA' => 'MIIClzCCAjugAwIBAgIFECYUJxAwDAYIKoEcz1UBg3UFADAlMQswCQYDVQQGEwJDTjEWMBQGA1UECgwNQ0ZDQSBTTTIgT0NBMTAeFw0xNTAzMjYwNjEwMzFaFw0yMDAzMjYwNjEwMzFaMIGDMQswCQYDVQQGEwJDTjESMBAGA1UECgwJQ0ZDQSBPQ0ExMQ0wCwYDVQQLDARDTUJDMRkwFwYDVQQLDBBPcmdhbml6YXRpb25hbC0xMTYwNAYDVQQDDC0wMzA1QDcxMDAwMTg5ODhA5Lqk5piT6ZO26KGMUDJQ6LWE6YeR5omY566hQDEwWTATBgcqhkjOPQIBBggqgRzPVQGCLQNCAAQ24zsOiNcNELoSdqCtX2Kn0ppIJUZ22WmKcU7Payx0M7tpXY2p6OZUaAzDkPr/Kc4pnTAnMNw4ySuFn5nuxHymo4H2MIHzMB8GA1UdIwQYMBaAFFyTWCBaJHNWEBtkUBDs6afKB0ERMAwGA1UdEwQFMAMBAQAwSAYDVR0gBEEwPzA9BghggRyG7yoBATAxMC8GCCsGAQUFBwIBFiNodHRwOi8vd3d3LmNmY2EuY29tLmNuL3VzL3VzLTE0Lmh0bTA3BgNVHR8EMDAuMCygKqAohiZodHRwOi8vY3JsLmNmY2EuY29tLmNuL1NNMi9jcmwxMTQwLmNybDALBgNVHQ8EBAMCA+gwHQYDVR0OBBYEFBA91H5B/osNLL3dY4BB13cjLl/2MBMGA1UdJQQMMAoGCCsGAQUFBwMCMAwGCCqBHM9VAYN1BQADSAAwRQIgOIofwSmSbkSu5jJw17o+X7JKcH/NpX+PUvlss7le2XUCIQCGHTGqr76HdqCX0ukkt7ORmpmepcTpDG5ng9QW4DqtAw==',
    'SIGNALG1' => 'SM3withSM2', // 加密方式1，用于签名和校验签名
    'SIGNALG2' => 'SM4/CBC/PKCS7Padding', // 加密方式2，用于加密信封和解密信封'
    'REG_STORE_URL' => "https://epay.cmbc.com.cn/appweb/lcbpService/mchntAdd.do",
    'MOD_STORE_URL' => "https://epay.cmbc.com.cn/appweb/lcbpService/mchntUpd.do",
    'BIND_PAY_URL' => "https://epay.cmbc.com.cn/appweb/lcbpService/chnlAdd.do",
    'MOD_PAY_URL' => "https://epay.cmbc.com.cn/appweb/lcbpService/chnlUpd.do",

    'PAY_URL' => "http://wxpay.cmbc.com.cn/mobilePlatform/appserver/lcbpPay.do",
    // 'NOTIFY_URL' => "http://wxpay.cmbc.com.cn/cmbcpaydemo/NoticeServlet?name=notice",
    // 'NOTIFY_URL' => "http://web.alipayma.com/CMBC/NoticeServlet",
    // 'NOTIFY_URL' => "http://web.alipayma.com/CMBC/NoticeServlet",
    'NOTIFY_URL' => "http://web.alipayma.com/CMBCNotice/NoticeServlet",
    'QUERY_TRANSACTION_URL' => 'http://wxpay.cmbc.com.cn/mobilePlatform/appserver/paymentResultSelect.do',
    'CMBC_REFUND_URL' => 'http://wxpay.cmbc.com.cn/mobilePlatform/appserver/cancelTrans.do',
    'CMBC_QUERY_TRADE_URL' => 'http://wxpay.cmbc.com.cn/mobilePlatform/appserver/wddpQuery.do',
    'CMBC_TRADE_URL' => 'http://wxpay.cmbc.com.cn/mobilePlatform/appserver/wddp.do'
)
;
