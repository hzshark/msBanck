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
}

?>