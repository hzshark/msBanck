<?php
namespace Home\Service;

require_once ("../Lib/php_java.php");
require_once ("../Utils/basic.class.php");

class MSBank
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

    private function encryptedSendData($base64SourceData){
        $signAlg2 = C('SIGNALG2');
        $base64X509CertData = C('BASE64X509CERTDATA');
        $sendstr = lajp_call("cfca.sadk.api.EnvelopeKit::envelopeMessage",  $base64SourceData,$signAlg2, $base64X509CertData);
        $sendstr=json_decode($sendstr,true);
        if (array_key_exists('Base64EnvelopeMessage', $sendstr)){
            return $sendstr['Base64SignatureData'];
        }else{
            return null;
        }
    }

    private function decryptRespone(){

    }

    public function registerStore($SourceData)
    {
        $result = array('status'=>1,'msg'=>'');
        $base64SourceData = base64_encode($SourceData);//原文BASE64
        $sign = $this->getSign($base64SourceData);
        if ($sign == null){
            $result['status'] = 2;
            $result['msg'] = 'Generate Sign Failed.';
            return $result;
        }
        $SourceData = addslashes($SourceData);
        $SourceData='{"sign":"'.$sign.'","body":"'.$SourceData.'"}';//拼凑后的原文
        $base64SourceData = base64_encode($SourceData);//拼凑后的原文Base64

        $encryptedData = $this->encryptedSendData($base64SourceData);
        $regURL = "http://wxpay.cmbc.com.cn/mobilePlatform/lcbpService/mchntAdd.do";
    }

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
