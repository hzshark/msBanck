<?php
namespace Manage\Service;

class AlipaymaStores
{
    public function queryAllStores(){
        $store = D("Store");
        return $store->select();
    }

    public function queryStoreinfoById($id){
        $store = D("Store");
        $where['id'] = $id;
        return $store->where($where)->find();
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
        if (isset($cmbcMchntId)){
            $this->createCmbcStore($storeId, $cmbcMchntId);
            $this->createCmbcStore($storeId, $cmbcMchntId);
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
        $model->add($data);
    }

    public function modStore($id, $postdata)
    {
        $model = D("store");
        $where['id'] = $id;
        $data['status'] = 0;
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
    public function setPayment($storeId, $postdata){
        $model = D("Payment");
        $data['storeid'] = $storeId;
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
        $data['indate'] = date('Y-m-d H:i:s', time());
        $model->add($data);
    }
}

?>