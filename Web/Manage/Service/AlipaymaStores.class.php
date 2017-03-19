<?php
namespace Manage\Service;

class AlipaymaStores
{
    public $AUDIT_PASS = 1;
    public $AUDIT_FAILED = 2;
    public $UNAUDIT = 0;
    public function queryAllStores(){
        $store = D("Store");
        return $store->select();
    }

    public function queryStoresByStoreId($id){
        $store = D("Store");
        $where['id'] = $id;
        return $store->where($where)->select();
    }

    public function queryStoresByAcdCode($acdCode){
        $store = D("Store");
        $where['acdCode'] = $acdCode;
        return $store->where($where)->select();
    }

    public function queryStoresByAcdCodeAndName($acdCode, $name){
        $store = D("Store");
        $where['acdCode'] = $acdCode;
        $where['_string'] = ' (mchntName like "%'.$name.'%")  OR ( mchntFullName like "%'.$name.'%") ';
        return $store->where($where)->select();
    }

    public function queryStoresByLikeName($name){
        $store = D("Store");
        $where['_string'] = ' (mchntName like "%'.$name.'%")  OR ( mchntFullName like "%'.$name.'%") ';
        return $store->where($where)->select();
    }

    public function queryStoreinfoById($id){
        $store = D("Store");
        $where['id'] = $id;
        return $store->where($where)->find();
    }

    public function delStoreinfoById($id){
        $store = D("Store");
        $where['id'] = $id;
        return $store->where($where)->delete();
    }

    public function queryCMBCIDByStoreId($id){
        $store = D("Cmbcstore");
        $where['storeid'] = $id;
        return $store->where($where)->find();
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
        $paydata['message'] = json_encode($reponseData['respone']);
        $paymodel = D('Payinfo');
        $paymodel->add($paydata);
    }
    private function createCmbcStore($storeid, $cmbcMchntId, $outMchntId){
        $cmbc = D('Cmbcstore');
        $data['storeid'] = $storeid;
        $data['outMchntId'] =
        $data['indate'] = $indate = date('Y-m-d H:i:s', time());
        $data['cmbcMchntId'] = $cmbcMchntId;
        $cmbc->add($data);
    }

    private function queryCmbcStoreByStoreid($storeid){
        $cmbc = D('Cmbcstore');
        $where['storeid'] = $storeid;
        $cmbc->where($where)->find();
    }

    private function setOderLog($storeid, $sendData, $reponseData){
        $model = D('Orderlog');
        $data['storeid'] = $storeid;
        $data['senddata'] = json_encode($sendData);
        $data['respone'] = json_encode($reponseData);
        $model->add($data);
    }

    public function setStoreStatus($id, $status){
        $store = D("Store");
        $where['id'] = $id;
        $data['status'] = $status;
        return $store->where($where)->save($data);
    }
    public function registerOder($storeId, $senddata, $respone){
        $this->addPayInfo($storeId, $senddata, $respone);
        $res = $respone['respone'];
        $res_body = $res['body'];
        $res = json_decode($res_body, true);
        $cmbcMchntId = $res['cmbcMchntId'];
        if (isset($cmbcMchntId)){
            $cmbcStore = $this->queryCmbcStoreByStoreid($storeId);
            if (count($cmbcStore) == 0){
                $this->createCmbcStore($storeId, $cmbcMchntId, $senddata['outMchntId']);
            }
        }
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
        return $model->add($data);
    }

    public function modStore($id, $postdata)
    {
        $model = D("store");
        $where['id'] = $id;
        $data['status'] = $this->UNAUDIT;
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
        $model->where($where)->save($data);
    }

    public function queryPaymentByStoreIdAndApiCode($storeId, $apiCode){
        $model = D("Payment");
        $where['storeid'] = $storeId;
        $where['apiCode'] = $apiCode;
        $model->where($where)->find();
    }

    public function queryPaymentByStoreId($storeId){
        $model = D("Payment");
        $where['storeid'] = $storeId;
        $model->where($where)->find();
    }

    public function setPaymentSignIdByStoreId($storeId, $signId, $apiCode){
        $model = D("Payment");
        $where['storeid'] = $storeId;
        if ($apiCode == '005'){
            $data['wxSignid'] = $signId;
        }elseif ($apiCode == '007'){
            $data['alipaySignid'] = $signId;
        }elseif ($apiCode == '008'){
            $data['qqSignid'] = $signId;
        }
        $model->where($where)->save($data);
    }

    public function setPayment($storeId, $postdata){
        $model = D("Payment");
        $data['apiCode'] = $postdata['apiCode'];
        $data['industryId'] = $postdata['industryId'];
        $data['operateType'] = $postdata['operateType'];
        $data['dayLimit'] = $postdata['dayLimit'];
        $data['monthLimit'] = $postdata['monthLimit'];
        $data['fixFeeRate'] = $postdata['fixFeeRate'];
        $data['specFeeRate'] = $postdata['specFeeRate'];
        $data['account'] = $postdata['account'];
        $data['pbcBankId'] = $postdata['pbcBankId'];
        $data['acctName'] = $postdata['acctName'];
        $data['acctType'] = $postdata['acctType'];
        $data['message'] = $postdata['message'];
        $data['idType'] = $postdata['idType'];
        $data['idCode'] = $postdata['idCode'];
        $data['acctTelephone'] = $postdata['acctTelephone'];

        $payment = $this->queryPaymentByStoreId($storeId);
        if (count($payment)>0){
            $where['storeid'] = $storeId;
            $model->where($where)->save($data);
        }else{
            $data['storeid'] = $storeId;
            $data['indate'] = date('Y-m-d H:i:s', time());
            $model->add($data);
        }


    }

    public function addWXPayInfo($postdata, $status, $payCode){
        $wx = D('Wxpay');
        $data['status'] = $status;
        $data['payCode'] = $payCode;
        $data['amount'] = $postdata['amount'];
        $data['orderInfo'] = $postdata['orderInfo'];
        $data['merchantSeq'] = $postdata['merchantSeq'];
        $data['transDate'] = $postdata['transDate'];
        $data['transTime'] = $postdata['transTime'];
        $data['notifyUrl'] = $postdata['notifyUrl'];
        $data['remark'] = $postdata['remark'];
        $wx->add($data);
    }

    public function addAliPayInfo($postdata, $status, $payCode){
        $alipay = D('Alipay');
        $data['status'] = $status;
        $data['payCode'] = $payCode;
        $data['amount'] = $postdata['amount'];
        $data['orderInfo'] = $postdata['orderInfo'];
        $data['merchantSeq'] = $postdata['merchantSeq'];
        $data['transDate'] = $postdata['transDate'];
        $data['transTime'] = $postdata['transTime'];
        $data['notifyUrl'] = $postdata['notifyUrl'];
        $data['remark'] = $postdata['remark'];
        $alipay->add($data);
    }

    public function addQQPayInfo($postdata, $status, $payCode){
        $QQ = D('Qqpay');
        $data['status'] = $status;
        $data['payCode'] = $payCode;
        $data['amount'] = $postdata['amount'];
        $data['orderInfo'] = $postdata['orderInfo'];
        $data['merchantSeq'] = $postdata['merchantSeq'];
        $data['transDate'] = $postdata['transDate'];
        $data['transTime'] = $postdata['transTime'];
        $data['notifyUrl'] = $postdata['notifyUrl'];
        $data['remark'] = $postdata['remark'];
        $QQ->add($data);
    }


}

?>