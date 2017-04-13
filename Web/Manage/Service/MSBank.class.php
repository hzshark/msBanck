<?php
namespace Manage\Service;

require_once ("Web/Manage/Lib/php_java.php");
require_once ("Web/Manage/Utils/basic.class.php");

use Think\Log;

class MSBank
{

    private function getSign($base64SourceData)
    {
        $base64P12Data = C('BASE64P12DATA');
        $p12Password = C('P12PASSWORD'); // 私钥密码
        $base64X509CertData = C('BASE64X509CERTDATA');
        $signAlg1 = C('SIGNALG1'); // 加密方式1，用于签名和校验签名
        $signAlg2 = C('SIGNALG2'); // 加密方式2，用于加密信封和解密信封
        Log::write('Generate Sign ...', 'DEBUG');
        $sign = lajp_call("cfca.sadk.api.SignatureKit::P1SignMessage", $signAlg1, $base64SourceData, $base64P12Data, $p12Password);
        $sign = json_decode($sign, true);
        if (array_key_exists('Base64SignatureData', $sign)) {
            if ("90000000" == $sign['Code']) {
                return $sign['Base64SignatureData'];
            }
            Log::write("generate sign failed:" . $sign['Base64SignatureData'], 'ERROR');
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
            if ("90000000" == $sendstr['Code']) {
                return $sendstr['Base64EnvelopeMessage'];
            }
        }
        Log::write("Encrypted Send Data failed:" . $sendstr['Base64SignatureData'], 'ERROR');
        return null;
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
        $backstr = lajp_call("cfca.sadk.api.EnvelopeKit::openEvelopedMessage", $encryptedData, $signAlg2, $base64P12Data, $p12Password);
        $backstr = json_decode($backstr, true);
        if (array_key_exists('Base64SourceString', $backstr)) {
            if ("90000000" == $backstr['Code']) {
                return $backstr['Base64SourceString'];
            }
        } else {
            Log::write("Encrypted Send Data failed:" . $backstr['Base64SignatureData'], 'ERROR');
            return null;
        }
    }

    private function check_sign($SourceData)
    {
        $signAlg1 = C('SIGNALG1');
        $base64X509CertData = C('BASE64X509CERTDATA');
        return lajp_call("cfca.sadk.api.SignatureKit::P1VerifyMessage", $signAlg1, base64_encode($SourceData['body']), $base64X509CertData, $SourceData['sign']);
    }
    
    function replace_unicode_escape_sequence($match) {
        return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
    }

    private function cmbcAction($SourceData, $URL)
    {
        $result = array(
            'status' => 1,
            'msg' => '',
            'respone' => array()
        );
        $base64SourceData = base64_encode($SourceData); // 原文BASE64
        $sign = $this->getSign($base64SourceData);
        if ($sign == null) {
            $result['status'] = 2;
            $result['msg'] = 'Generate Sign Failed.';
            return $result;
        }
        $SourceData = addslashes($SourceData);
        //$SourceData = mb_convert_encoding($SourceData, 'gbk', 'utf-8');
        //$SourceData = iconv('UTF-8', 'GB2312', $SourceData); //将字符串的编码从UTF-8转到GB2312
        $SourceData = '{"sign":"' . $sign . '","body":"' . $SourceData . '"}'; // 拼凑后的原文
        //$SourceData = '{"sign":"MEUCIAWXPTiFDICBaG4eAJA95d5b8J2RWy2MZcKnzGFlQWGSAiEAkVDxdwWNFJZAnAuyOXIcebxnkSCHSdR97Yt59uc6jSI=","body":"{\"txnSeq\":\"100860001111111000\",\"platformId\":\"A00002016120000000294\",\"operId\":\"10086A0001\",\"outMchntId\":\"O010020160700000006jjh\",\"cmbcMchntId\":\"M29002017030000014137\",\"industryId\":\"102\",\"dayLimit\":\"2\",\"monthLimit\":\"10\",\"fixFeeRate\":\"0.38\",\"specFeeRate\":\"\",\"account\":\"6226223380006109\",\"pbcBankId\":\"305526061005\",\"acctName\":\"测试1247850073\",\"message\":\"\",\"idCode\":\"330422197709272758\",\"acctTelephone\":\"13900001111\",\"apiCode\":\"0005\",\"operateType\":\"1\",\"acctType\":\"1\",\"idType\":\"01\"}"}';
        Log::write('CMBC Action SEND DATA:' . $SourceData, 'DEBUG');
        //var_dump($SourceData);

        $base64SourceData = base64_encode($SourceData); // 拼凑后的原文Base64
        $encryptedData = $this->encryptedSendData($base64SourceData);
        $sendMsg = $this->generateSendMessage($encryptedData);
        $httpreps = http_post_data($URL, $sendMsg);
        if ($httpreps[0] == 200) {
            $repdata = json_decode($httpreps[1], true);
            Log::write('CMBC Action RESPONES DATA:' . json_encode($repdata), 'DEBUG');
            $reps_encryptedData = $repdata['businessContext'];
            $decryptRes = $this->decryptRespone($reps_encryptedData);
            $backstr = base64_decode($decryptRes);
            $backarray = json_decode($backstr, true);
            $result_body = $backarray['body'];
            $result_bodyarray = json_decode($result_body, true);
//             var_dump($result_bodyarray);
            Log::write('CMBC Action RESPONES encrypte DATA:' . $result_body, 'DEBUG');
            if ($result_bodyarray['respCode'] != '0000') {
                $result['status'] = 5;
                $result['msg'] = 'CMBC Action Failed. Result:' . preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $result_bodyarray['errorMsg']);
            } else {
                $check_ret = $this->check_sign($backarray);
                $ret = json_decode($check_ret, true);
                if ($ret['Code'] == '90000000') {
                    $result['status'] = 0;
                    $result['respone'] = $backarray;
                    $result['msg'] = 'CMBC Action Successfully. Chenk Sign Result:' . $ret['Result'];
                } else {
                    $result['status'] = 4;
                    $result['msg'] = 'CMBC Action Failed. Chenk Sign Result:' . $ret['Result'];
                }
            }
        } else {
            $result['status'] = 3;
            $result['msg'] = 'http request api failed, return http status:' . $httpreps[0];
        }
        return $result;
    }

    public function registerStore($SourceData)
    {
        $URL = C('REG_STORE_URL');
        return $this->cmbcAction($SourceData, $URL);
    }

    private function queryStoreIdByOutMchntId($outMchntId)
    {
        $model = D("store");
        $where['outMchntId'] = $outMchntId;
        return $model->where($where)->find();
    }

    private function addPayInfo($storeId, $senddata = array(), $reponseData = array())
    {
        $paydata['storeid'] = $storeId;
        $paydata['txnSeq'] = $senddata['txnSeq'];
        $paydata['platformId'] = $senddata['platformId'];
        $paydata['operId'] = $senddata['operId'];
        $paydata['dataSrc'] = $senddata['dataSrc'];
        $paydata['devType'] = $senddata['devType'];
        $indate = date('Y-m-d H:i:s', time());
        $paydata['indate'] = $indate;
        $paydata['status'] = $reponseData['status'];
        $paydata['message'] = $reponseData['msg'];
        $paymodel = D('Payinfo');
        $paymodel->add($paydata);
    }

    public function modStoreInfo($SourceData)
    {
        $URL = C('MOD_STORE_URL');
        return $this->cmbcAction($SourceData, $URL);
    }

    public function queryStoreInfo($SourceData)
    {
        $URL = C('MOD_STORE_URL');
        return $this->cmbcAction($SourceData, $URL);
    }

    public function bindPayment($SourceData)
    {
        $URL = C('BIND_PAY_URL');
        return $this->cmbcAction($SourceData, $URL);
    }

    public function modPaumentInfo($SourceData)
    {
        $URL = C('MOD_PAY_URL');
        return $this->cmbcAction($SourceData, $URL);
    }

    public function pay($SourceData)
    {
        $URL = C('PAY_URL');
        return $this->cmbcAction($SourceData, $URL);
    }

    public function queryTransaction($SourceData){
        $URL = C('QUERY_TRANSACTION_URL');
        return $this->cmbcAction($SourceData, $URL);
    }

    public function Refund($SourceData){
        $URL = C('REFUND_URL');
        return $this->cmbcAction($SourceData, $URL);
    }

    public function QueryTrade($SourceData){
        $URL = C('QUERY_TRADE_URL');
        return $this->cmbcAction($SourceData, $URL);
    }

    public function Trade($SourceData){
        $URL = C('TRADE_URL');
        return $this->cmbcAction($SourceData, $URL);
    }

    public function Notification($SourceData){
//         $URL = C('REFUND_URL');
//         return $this->cmbcAction($SourceData, $URL);
    }

    public function uploadElectronicData()
    {}

    public function collectionElectronicData()
    {}
}
