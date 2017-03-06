<?php
namespace Manage\Service;

class Areas
{
    public function getAreas(){

    }

    public function queryNameByAreaCode($area_code) {
        $area = D("Areas");
        $where['area_code'] = $area_code;
        return $area->where($where)->field('name')->find();
    }

    public function queryAreas($conent){
        $help = D("Areas");
        $data['info'] = $conent;
        if ($id > 0){
            $where['id'] = $id;
            $help->where($where)->save($data);
        }else{
            $help->add($data);
        }
    }
}

?>