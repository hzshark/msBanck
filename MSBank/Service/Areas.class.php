<?php
namespace MSBank\Service;

class AreaService
{
    public function getAreas(){

    }

    private function queryAreas(){
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