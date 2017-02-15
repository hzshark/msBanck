<?php
namespace Home\Controller;


use Think\Controller;
use Home\Service\Home;
use Home\Service\MSBank;

class ApiController extends Controller
{

    private $IS_ISO = '1';

    private $IS_ANDROID = '0';

    private $SUCCESS = 0;

    private $ERROR = 1;

    private function getIPaddress()

    {
        $IPaddress = '';

        if (isset($_SERVER)) {

            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {

                $IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else
                if (isset($_SERVER["HTTP_CLIENT_IP"])) {

                    $IPaddress = $_SERVER["HTTP_CLIENT_IP"];
                } else {

                    $IPaddress = $_SERVER["REMOTE_ADDR"];
                }
        } else {

            if (getenv("HTTP_X_FORWARDED_FOR")) {

                $IPaddress = getenv("HTTP_X_FORWARDED_FOR");
            } else
                if (getenv("HTTP_CLIENT_IP")) {

                    $IPaddress = getenv("HTTP_CLIENT_IP");
                } else {

                    $IPaddress = getenv("REMOTE_ADDR");
                }
        }

        return $IPaddress;
    }

    public function RegisterStore(){
        $txnSeq = '100860001111111000';// 流水号, 调用方生成，确保唯一
        $platformId = 'A00002016120000000294';// 平台号, 民生银行生成
        $operId = '10010A0001';//拓展人员编号
        $dataSrc = '2';// 进件渠道, 填固定值2
        $outMchntId = 'O931T20170214220101820'; //外部商户号, 商户自己生成，确保唯一
        $mchntName = 'Demo进件测试商户'; //商户简称|
        $mchntFullName = '中国移动';//商户全称, 请填写营业执照上的全称
        $parentMchntId = ''; //父商户,  非必输
        $devType = '1'; //拓展模式, 类型代码对应：1-第三方,2-民生银行
        $acdCode = '350203';
        $province = '河北省';// 省份
        $city = '石家庄市';//城市
        $address = '新华区华西路53号';// 地址
        $isCert = '1'; //是否持证,0-非持证商户,1-持证商户
        $licId = '35020320160831'; // 营业执照号, 若没有，可填默认值-
        $licValidity = '20301231'; //营业执照有效期,若没有，可填默认值-
        $corpName = '唐门';// 法人/联系人
        $idtCard = '130105187808235612'; //法人/联系人证件号,若没有，可填默认值-
        $contactName = '唐三角'; //负责人,
        $telephone = '13880880808';// 负责人手机号
        $servTel = '13839795841';//客服电话
        $identification = ''; //客户识别码
        $autoSettle = '1';// 结算方式, 类型代码对应：1-自动结算,2-手工提现
        $remark='备注';//备注
        $message = '';// 备用字段

        $postdata = array('txnSeq'=>$txnSeq,
            'platformId'=>$platformId,
            'operId' => $operId,
            'dataSrc'=>$dataSrc,
            'outMchntId'=>$outMchntId,
            'mchntName'=>$mchntName,
            'mchntFullName'=>$mchntFullName,
            'parentMchntId'=>$parentMchntId,
            'devType'=>$devType,
            'acdCode'=>$acdCode,
            'province'=>$province,
            'city'=>$city,
            'address'=>$address,
            'isCert'=>$isCert,
            'licId'=>$licId,
            'licValidity'=>$licValidity,
            'corpName'=>$corpName,
            'idtCard'=>$idtCard,
            'contactName'=>$contactName,
            'telephone'=>$telephone,
            'servTel'=>$servTel,
            'identification'=>$identification,
            'autoSettle'=>$autoSettle,
            'remark'=>$remark,
            'message'=>$message
        );

        $SourceData = json_encode($postdata);
        $msbank = new MSBank();
        $msbank->registerStore($SourceData);
    }


}