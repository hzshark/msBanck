<?php
namespace Manage\Service;
class Areas
{
    public function queryNameByAreaCode($area_code) {
        $area = D("Areas");
        $where['area_code'] = $area_code;
        return $area->where($where)->field('name')->find();
    }

    public function queryAreaInfo($area_code) {
        $area = D("Areas");
        $where['area_code'] = $area_code;
        return $area->where($where)->find();
    }

    public function queryAreas($root_code, $p_code, $level){
        $model = D("Areas");
        $where['p_code'] = $p_code;
        $where['level'] = $level;
        $where['root_code'] = $root_code;
        return $model->where($where)->select();
    }

}

?>