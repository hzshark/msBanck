<?php
namespace Home\Service;

require_once ("../Lib/php_java.php");
require_once ("../Utils/basic.class.php");

class MSBanck
{

    private function getSign($base64SourceData)
    {
        $base64P12Data = C('BASE64P12DATA');
        $p12Password = C('P12PASSWORD'); // 私钥密码
        $base64X509CertData = C('BASE64X509CERTDATA');
        $signAlg1 = C('SIGNALG1'); // 加密方式1，用于签名和校验签名
        $signAlg2 = C('SIGNALG2'); // 加密方式2，用于加密信封和解密信封
        
        $sign = lajp_call("cfca.sadk.api.SignatureKit::P1SignMessage", $signAlg1, $base64SourceData, $base64P12Data, $p12Password);
        $sign = json_decode($sign, true);
//         var_dump($sign);
        if (array_key_exists('Base64SignatureData', $sign)){
            return $sign['Base64SignatureData'];
        }else{
            return null;
        }
        
    }

    public function registerStore()
    {}

    public function modStoreInfo()
    {}

    public function queryStoreInfo()
    {}

    public function bindPayment()
    {}

    public function modPaumentInfo()
    {}

    public function uploadElectronicData()
    {}

    public function collectionElectronicData()
    {}
}
