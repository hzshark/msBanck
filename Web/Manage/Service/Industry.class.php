<?php
namespace Manage\Service;

class Industry
{
    public function queryIndustryWx($storetype, $industry){
        $modle = D('Industrywx');
        if (isset($storetype) && $storetype != ''&& !isset($industry)){
            $where['storetype'] = $storetype;
            return $modle->field('industry')->where($where)->group('industry')->select();
        }elseif (isset($industry) && $industry != '') {
            $where['industry'] = $industry;
            $where['storetype'] = $storetype;
            return $modle->field('category,apicode')->where($where)->group('category')->select();
        }else{
            return $modle->field('storetype')->group('storetype')->select();
        }
    }

    public function queryIndustryQQ($industry){
        $modle = D('Industryqq');
        if (isset($industry)&& $industry != '') {
            $where['industry'] = $industry;
            return $modle->field('category,apicode')->where($where)->group('category')->select();
        }else{
            return $modle->field('industry')->group('industry')->select();
        }
    }

    public function queryIndustryAlipay($storetype, $industry){
    $modle = D('Industryalipay');
        if (isset($storetype) && $storetype != ''&& !isset($industry)){
            $where['storetype'] = $storetype;
            return $modle->field('industry')->where($where)->group('industry')->select();
        }elseif (isset($industry)&& $industry != '') {
            $where['industry'] = $industry;
            $where['storetype'] = $storetype;
            return $modle->field('category,apicode')->where($where)->group('category')->select();
        }else{
            return $modle->field('storetype')->group('storetype')->select();
        }
    }
}

?>