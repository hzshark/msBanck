<?php
namespace CMBC\Service;

require_once ("CMBC/Lib/php_java.php");
require_once ("CMBC/Utils/basic.class.php");

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
        }else{
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

    private function cmbcAction($SourceData, $URL)
    {
        $result = array(
            'status' => 1,
            'msg' => '',
            'respone'=>array()
        );
        Log::write('CMBC Action:' . $SourceData, 'DEBUG');
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
        $httpreps = http_post_data($URL, $sendMsg);
        if ($httpreps[0] == 200) {
            $repdata = json_decode($httpreps[1], true);
            $reps_encryptedData = $repdata['businessContext'];
            $decryptRes = $this->decryptRespone($reps_encryptedData);
            $backstr = base64_decode($decryptRes);
            $backarray = json_decode($backstr, true);
//             var_dump($backarray['body']);
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
        } else {
            $result['status'] = 3;
            $result['msg'] = 'http request api failed, return http status:'.$httpreps[0];
        }
        return $result;
    }

    public function registerStore($SourceData)
    {
        $URL = C('REG_STORE_URL');
        return $this->cmbcAction($SourceData, $URL);
    }

    private function createCmbcStore($storeid, $cmbcMchntId){
        $cmbc = D('Cmbcstore');
        $data['storeid'] = $storeid;
        $data['cmbcMchntId'] = $cmbcMchntId;
        $cmbc->add($data);
    }

    private function setOderLog($storeid, $sendData, $reponseData){
        $model = D('Orderlog');
        $data['storeid'] = $storeid;
        $data['senddata'] = json_encode($sendData);
        $data['respone'] = json_encode($reponseData);
        $model->add($data);
    }

    public function registerOder($storeId, $senddata, $respone){
            $this->addPayInfo($storeId, $senddata, $respone);
            $res = $respone['respone'];
            $res_body = $res['body'];
            $res = json_decode($res_body, true);
            $cmbcMchntId = $res['cmbcMchntId'];
            $this->createCmbcStore($storeId, $cmbcMchntId);
            $this->setOderLog($storeId, $senddata, $respone);
    }

    public function createStoreAndReturnId($postdata)
    {
        $model = D("store");
        $indate = date('Y-m-d H:i:s', time());
        $data['indate'] = $indate;
        $data['outMchntId'] = $postdata['outMchntId'];
        $data['mchntName'] = $postdata['mchntName'];
        $data['mchntFullName'] = $postdata['mchntFullName'];
        $data['parentMchntId'] = $postdata['parentMchntId'];
        $data['acdCode'] = $postdata['acdCode'];
        $data['province'] = $postdata['province'];
        $data['city'] = $postdata['city'];
        $data['address'] = $postdata['address'];
        $data['isCert'] = $postdata['isCert'];
        $data['licId'] = $postdata['licId'];
        $data['licValidity'] = $postdata['licValidity'];
        $data['corpName'] = $postdata['corpName'];
        $data['idtCard'] = $postdata['idtCard'];
        $data['contactName'] = $postdata['contactName'];
        $data['telephone'] = $postdata['telephone'];
        $data['servTel'] = $postdata['servTel'];
        $data['identification'] = $postdata['identification'];
        $data['autoSettle'] = $postdata['autoSettle'];
        $data['remark'] = $postdata['remark'];
        $data['message'] = $postdata['message'];
        $model->add($data);
        $store = $model->where($data)->find();
        $storeId = $store['id'];
        return $storeId;
    }

    private function queryStoreIdByOutMchntId($outMchntId){
        $model = D("store");
        $where['outMchntId'] = $outMchntId;
        return $model->where($where)->find();
    }

    private function addPayInfo($storeId, $senddata=array() , $reponseData=array()){
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

    public function ModStoreOder($postdata, $reponseData){
        $outMchntId = $postdata['outMchntId'];
        $store = $this->queryStoreIdByOutMchntId($outMchntId);
        $storeId = $store['id'];
        $this->setOderLog($storeId, $postdata, $reponseData);
        $this->addPayInfo($storeId,$postdata, $reponseData);
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

    public function uploadElectronicData()
    {}

    public function collectionElectronicData()
    {}
}
