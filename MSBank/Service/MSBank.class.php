<?php
namespace Home\Service;

require_once ("MSBank/Lib/php_java.php");
require_once ("MSBank/Utils/basic.class.php");

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
        // var_dump($sign);
        if (array_key_exists('Base64SignatureData', $sign)) {
            return $sign['Base64SignatureData'];
        } else {
            return null;
        }
    }

    private function encryptedSendData($base64SourceData)
    {
        $signAlg2 = C('SIGNALG2');
        $base64X509CertData = C('BASE64X509CERTDATA');
        $sendstr = lajp_call("cfca.sadk.api.EnvelopeKit::envelopeMessage", $base64SourceData, $signAlg2, $base64X509CertData);
        $sendstr = json_decode($sendstr, true);
        if (array_key_exists('Base64EnvelopeMessage', $sendstr)) {
            return $sendstr['Base64SignatureData'];
        } else {
            return null;
        }
    }

    private function generateSendMessage($sendstr)
    {
        return '{"businessContext":"' . $sendstr . '","MerchantNo":"","merchantSeq":"","reserve1":"","reserve2":"","reserve3":"","reserve4":"","reserve5":"","reserveJson":"","securityType":"","sessionId":"","source":"","transCode":"","transDate":"","transTime":"","version":""}';
    }

    private function decryptRespone($encryptedData)
    {
        $signAlg2 = C('SIGNALG2');
        $base64P12Data = C('BASE64P12DATA');
        $p12Password = C('P12PASSWORD');
        $backstr = lajp_call("cfca.sadk.api.EnvelopeKit::openEvelopedMessage",  $encryptedData, $signAlg2, $base64P12Data, $p12Password);
        __PUBLIC__;
        $backstr=json_decode($backstr,true);
        
        $backstr =$backstr['Base64SourceString'];
        $base64SourceData=$backstr;
        $SourceData=base64_decode($backstr);
//         echo "解密后的原文BASE64：".$base64SourceData.PHP_EOL;
//         echo "解密后的拼凑原文：".$SourceData.PHP_EOL;
        $SourceData=json_decode($SourceData,true);
        return  $SourceData;        
    }
    
    private function check_sign($SourceData){
        $signAlg1 = C('SIGNALG1');
        $base64X509CertData = C('BASE64X509CERTDATA');
        return lajp_call("cfca.sadk.api.SignatureKit::P1VerifyMessage", $signAlg1,base64_encode($SourceData['body']), $base64X509CertData,$SourceData['sign']);
    }

    public function registerStore($SourceData)
    {
        $result = array(
            'status' => 1,
            'msg' => ''
        );
        $base64SourceData = base64_encode($SourceData); // 原文BASE64
        $sign = $this->getSign($base64SourceData);
        if ($sign == null) {
            $result['status'] = 2;
            $result['msg'] = 'Generate Sign Failed.';
            return $result;
        }
        $SourceData = addslashes($SourceData);
        $SourceData = '{"sign":"' . $sign . '","body":"' . $SourceData . '"}'; // 拼凑后的原文
        $base64SourceData = base64_encode($SourceData); // 拼凑后的原文Base64
        
        $encryptedData = $this->encryptedSendData($base64SourceData);
        $sendMsg = $this->generateSendMessage($encryptedData);
        $regURL = "http://wxpay.cmbc.com.cn/mobilePlatform/lcbpService/mchntAdd.do";
        $httpreps = http_post_data($regURL, $sendMsg);
        if ($httpreps[0] == 200){
            
            $repdata = json_decode($httpreps[1], true);
            $reps_encryptedData = $repdata['businessContext'];
            $decryptRes = $this->decryptRespone($reps_encryptedData);
            $check_ret = $this->check_sign(decryptRes);
            $ret = json_decode($check_ret, true);
            if ($ret['Result'] == 'True'){
                $result['status'] = 0;
                $result['msg'] = 'Register Store Successfully. And Return Code:'.$ret['Code'];
            }else{
                $result['status'] = 0;
                $result['msg'] = 'Register Store Failed. And Return Code:'.$ret['Code'];
            }
        }else{
            $result['status'] = 3;
            $result['msg'] = 'http request api failed.';
            
        }
        return $result;
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
