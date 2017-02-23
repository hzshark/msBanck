<?php
namespace MSBank\Service;

class StoreInfo
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
}

?>