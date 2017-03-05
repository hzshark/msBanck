<?php
namespace MSBank\Controller;

require_once ("Web/MSBank/Utils/basic.class.php");
use Think\Controller;
use MSBank\Service\MSBank;
use MSBank\Service\StoreInfo;
use MSBank\Service\Areas;

class IndexController extends Controller
{

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

    public function Index()
    {
        header("Content-Type:text/html; charset=utf-8");
        $store = new StoreInfo();
        $storesInfo = $store->queryAllStores();
        // var_dump($storesInfo);
        $this->assign("stores", $storesInfo);
        $this->display('index', 'utf-8');
    }

    public function Register()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->display('register', 'utf-8');
    }

    public function RegisterStore()
    {
        header("Content-Type:text/html; charset=utf-8");
        $txnSeq = generateOrderno(); // 流水号, 调用方生成，确保唯一
        $platformId = C('platformId'); // 平台号, 民生银行生成
        $operId = C('operId'); // 拓展人员编号
        $dataSrc = C('dataSrc'); // 进件渠道, 填固定值2
        $outMchntId = generate_guid(); // 外部商户号, 商户自己生成，确保唯一
        $devType = 1;//拓展模式,类型代码对应： 1-第三方 2-民生银行
        if (IS_POST) {
            $store = new StoreInfo();
            $area = new Areas();
            $mchntName = isset($_POST['mchntName']) ? $_POST['mchntName'] : '';//商户简称
            $mchntFullName = isset($_POST['mchntFullName']) ? $_POST['mchntFullName'] : '';// 商户全称,请填写营业执照上的全称
            $parentMchntId = isset($_POST['parentMchntId']) ? $_POST['parentMchntId'] : '';

            $province = isset($_POST['province']) ? $_POST['province'] : '';
            $city = isset($_POST['city']) ? $_POST['city'] : '';
            $acdCode = isset($_POST['area']) ? $_POST['area'] : '';
            $addr = isset($_POST['addr']) ? $_POST['addr'] : '';
            $province_name = $area->queryNameByAreaCode($province);
            $city_name = $area->queryNameByAreaCode($city);
            $area_name = $area->queryNameByAreaCode($acdCode);
            $address = $area_name['name'].$addr; // 地址
            $isCert = isset($_POST['isCert']) ? $_POST['isCert'] : '0';
            $licId = isset($_POST['licId']) ? $_POST['licId'] : '-'; // 营业执照号, 若没有，可填默认值-
            $licValidity = isset($_POST['licValidity']) ? $_POST['licValidity'] : '-'; // 营业执照有效期,若没有，可填默认值-
            $corpName = isset($_POST['corpName']) ? $_POST['corpName'] : ''; // 法人/联系人
            $idtCard = isset($_POST['idtCard ']) ? $_POST['idtCard '] : '-'; // 法人/联系人证件号,若没有，可填默认值-
            $contactName = isset($_POST['contactName']) ? $_POST['contactName'] : ''; // 负责人,
            $telephone = isset($_POST['telephone']) ? $_POST['telephone'] : ''; // 负责人手机号
            $servTel = isset($_POST['servTel']) ? $_POST['servTel'] : ''; // 客服电话
            $identification = isset($_POST['identification']) ? $_POST['identification'] : ''; // 客户识别码
            $autoSettle = isset($_POST['autoSettle']) ? $_POST['autoSettle'] : ''; // 结算方式, 类型代码对应：1-自动结算,2-手工提现
            $remark = isset($_POST['remark']) ? $_POST['remark'] : ''; // 备注
            $message = isset($_POST['message']) ? $_POST['message'] : ''; // 备用字段

            $postdata = array(
                'txnSeq' => $txnSeq,
                'platformId' => $platformId,
                'operId' => $operId,
                'dataSrc' => $dataSrc,
                'outMchntId' => $outMchntId,
                'mchntName' => $mchntName,
                'mchntFullName' => $mchntFullName,
                'parentMchntId' => $parentMchntId,
                'devType' => $devType,
                'acdCode' => $acdCode,
                'province' => $province_name['name'],
                'city' => $city_name['name'],
                'address' => $address,
                'isCert' => $isCert,
                'licId' => $licId,
                'licValidity' => $licValidity,
                'corpName' => $corpName,
                'idtCard' => $idtCard,
                'contactName' => $contactName,
                'telephone' => $telephone,
                'servTel' => $servTel,
                'identification' => $identification,
                'autoSettle' => $autoSettle,
                'remark' => $remark,
                'message' => $message
            );
            $SourceData = json_encode($postdata);
            $msbank = new MSBank();
            $ret = $msbank->registerStore($SourceData);
            if ($ret['status'] == 0) {
                $storeId = $msbank->createStoreAndReturnId($postdata);
                $msbank->registerOder($storeId, $postdata, $ret);
            }
            $this->success("商家入驻成功", "Index", 1);
//             $this->show(json_encode($ret));
        }
    }

    public function ModStore()
    {
        header("Content-Type:text/html; charset=utf-8");
        if (IS_GET) {
            $store = new StoreInfo();
            $id = isset($_GET['id']) ? $_GET['id'] : '';
            $storeInfo = $store->queryStoreinfoById($id);
            $this->assign("store", $storeInfo);
            $this->display('modstore', 'utf-8');
        } else {
            $txnSeq = generateOrderno(); // 流水号, 调用方生成，确保唯一
            $platformId = C('platformId'); // 平台号, 民生银行生成
            $operId = C('operId'); // 拓展人员编号

            $outMchntId = 'B0E6109E6CC3C2CB1622C3F881D60663'; // 外部商户号, 商户自己生成，确保唯一
            $cmbcMchntId = 'M29002017020000012885';

            $mchntName = 'Demo修改测试商户'; // 商户简称|
            $mchntFullName = '中国移动'; // 商户全称, 请填写营业执照上的全称
            $address = '河北省石家庄市新华区华西路53号'; // 地址
            $isCert = '0'; // 是否持证,0-非持证商户,1-持证商户
            $licId = '35020320160831'; // 营业执照号, 若没有，可填默认值-
            $licValidity = '20201231'; // 营业执照有效期,若没有，可填默认值-
            $corpName = '唐门'; // 法人/联系人
            $idtCard = '130105187808235612'; // 法人/联系人证件号,若没有，可填默认值-
            $contactName = '唐三角'; // 负责人,
            $telephone = '13880880808'; // 负责人手机号
            $servTel = '13839795841'; // 客服电话
            $identification = ''; // 客户识别码
            $autoSettle = '1'; // 结算方式, 类型代码对应：1-自动结算,2-手工提现
            $remark = '备注'; // 备注
            $message = ''; // 备用字段
            $postdata = array(
                'txnSeq' => $txnSeq,
                'platformId' => $platformId,
                'operId' => $operId,
                'outMchntId' => $outMchntId,
                'cmbcMchntId' => $cmbcMchntId,
                'mchntName' => $mchntName,
                'mchntFullName' => $mchntFullName,
                'address' => $address,
                'isCert' => $isCert,
                'licId' => $licId,
                'licValidity' => $licValidity,
                'corpName' => $corpName,
                'idtCard' => $idtCard,
                'contactName' => $contactName,
                'telephone' => $telephone,
                'servTel' => $servTel,
                'identification' => $identification,
                'autoSettle' => $autoSettle,
                'remark' => $remark,
                'message' => $message
            );
            $SourceData = json_encode($postdata);
            $msbank = new MSBank();
            $ret = $msbank->modStoreInfo($SourceData);
            if ($ret['status'] == 0) {
                $msbank->ModStoreOder($postdata, $ret);
            }
            $this->show(json_encode($ret));
        }
    }

    public function BindPayment()
    {
        header("Content-Type:text/html; charset=utf-8");
        if (IS_GET) {
            $store = new StoreInfo();
            $id = isset($_GET['id']) ? $_GET['id'] : '';
            $storeInfo = $store->queryStoreinfoById($id);
            $txnSeq = generateOrderno(); // 流水号, 调用方生成，确保唯一
            $this->assign("txnSeq", $txnSeq);
            $this->assign("store", $storeInfo);
            $this->display('bindPayment', 'utf-8');
        } else {
            $txnSeq = generateOrderno(); // 流水号, 调用方生成，确保唯一
            $platformId = C('platformId'); // 平台号, 民生银行生成
            $operId = '10086A0001'; // 拓展人员编号

            $outMchntId = 'B0E6109E6CC3C2CB1622C3F881D60663'; // 外部商户号, 商户自己生成，确保唯一
            $cmbcMchntId = 'M29002017020000012885';

            $apiCode = '0005'; // 支付通道, 类型代码对应： 0005-微信 0007-支付宝 0008-QQ钱包
            $industryId = '102';
            $operateType = '2'; // 接入类型,类型代码对应： 1-间联 2-直联
            $dayLimit = '10'; // 日限额, 精确到分
            $monthLimit = '30'; // 月限额,精确到分
            $fixFeeRate = '0.38'; // 固定比例费率 , 5%：0.50，小数点后精确到2位。两种费率二选一
            $specFeeRate = ''; // 特殊费率
            $account = '6226223380006109'; // 结算账号
            $pbcBankId = '305526061005'; // 开户行号,人民银行大小额支付行号
            $acctName = '测试1247850073'; // 开户人
            $acctType = '2'; // 账户类型,类型代码对应： 1-对私 2-对公
            $message = ''; // 通道其他信息|message: JSON格式字符串
            $postdata = array(
                'txnSeq' => $txnSeq,
                'platformId' => $platformId,
                'operId' => $operId,
                'outMchntId' => $outMchntId,
                'cmbcMchntId' => $cmbcMchntId,
                'apiCode' => $apiCode,
                'industryId' => $industryId,
                'operateType' => $operateType,
                'dayLimit' => $dayLimit,
                'monthLimit' => $monthLimit,
                'fixFeeRate' => $fixFeeRate,
                'specFeeRate' => $specFeeRate,
                'account' => $account,
                'pbcBankId' => $pbcBankId,
                'acctName' => $acctName,
                'acctType ' => $acctType,
                'message' => $message
            );
            $SourceData = json_encode($postdata);
            $msbank = new MSBank();
            $ret = $msbank->bindPayment($SourceData);
            if ($ret['status'] == 0) {
                $msbank->ModStoreOder($postdata, $ret);
            }
            $this->show(json_encode($ret));
        }
    }

    public function ModPayment()
    {
        header("Content-Type:text/html; charset=utf-8");
        $txnSeq = generateOrderno(); // 流水号, 调用方生成，确保唯一
        $platformId = C('platformId'); // 平台号, 民生银行生成
        $operId = '10086A0001'; // 拓展人员编号

        $outMchntId = 'B0E6109E6CC3C2CB1622C3F881D60663'; // 外部商户号, 商户自己生成，确保唯一
        $cmbcMchntId = 'M29002017020000012885';
        $cmbcSignId = 'S29002017020000312739';
        $apiCode = '0007'; // 支付通道, 类型代码对应： 0005-微信 0007-支付宝 0008-QQ钱包

        $operateType = '2'; // 接入类型,类型代码对应： 1-间联 2-直联
        $dayLimit = '10'; // 日限额, 精确到分
        $monthLimit = '30'; // 月限额,精确到分
        $fixFeeRate = '0.38'; // 固定比例费率 , 5%：0.50，小数点后精确到2位。两种费率二选一
        $specFeeRate = ''; // 特殊费率
        $account = '6226223380006109'; // 结算账号
        $pbcBankId = '305526061005'; // 开户行号,人民银行大小额支付行号
        $acctName = '测试1247850073'; // 开户人
        $acctType = '2'; // 账户类型,类型代码对应： 1-对私 2-对公
        $message = ''; // 通道其他信息|message: JSON格式字符串
        $postdata = array(
            'txnSeq' => $txnSeq,
            'platformId' => $platformId,
            'operId' => $operId,
            'outMchntId' => $outMchntId,
            'cmbcMchntId' => $cmbcMchntId,
            'apiCode' => $apiCode,
            'cmbcSignId' => $cmbcSignId,
            'operateType' => $operateType,
            'dayLimit' => $dayLimit,
            'monthLimit' => $monthLimit,
            'fixFeeRate' => $fixFeeRate,
            'specFeeRate' => $specFeeRate,
            'account' => $account,
            'pbcBankId' => $pbcBankId,
            'acctName' => $acctName,
            'acctType ' => $acctType,
            'message' => $message
        );
        $SourceData = json_encode($postdata);
        $msbank = new MSBank();
        $ret = $msbank->modPaumentInfo($SourceData);
        if ($ret['status'] == 0) {
            $msbank->ModStoreOder($postdata, $ret);
        }
        $this->show(json_encode($ret));
    }

    public function pay()
    {
        header("Content-Type:text/html; charset=utf-8");
        $platformId = C('platformId'); // 平台号, 民生银行生成
        $operId = '10086A0001'; // 拓展人员编号
        $merchantNo = "M29002017020000012847"; // 该订单对应的民生统一商户号
        $selectTradeType = 'API_WXQRCODE'; // 支付类型的标识信息
                                           // 类型代码对应：
                                           // 微信 微信正扫：API_WXQRCODE 微信反扫：API_WXSCAN 微信公众号：H5_WXJSAPI
                                           // 支付宝 支付宝正扫：API_ZFBQRCODE 支付宝反扫：API_ZFBSCAN 支付宝服务窗：H5_ZFBJSAPI
                                           // QQ钱包 QQ钱包正扫：API_QQQRCODE QQ钱包反扫：API_QQSCAN QQ钱包公众号：H5_QQJSAPI
        $payment_code = "12312414";
        $amount = '1'; // 交易金额，以分为单位
        $orderInfo = '统一下单API测试-' . $selectTradeType;
        $merchantSeq = $platformId . generateOrderno(); // 流水号, 调用方生成，确保唯一
        $transDate = date('Ymd', time()); // 格式：yyyyMMdd
        $transTime = date('YmdHis', time()) . "000"; // 格式：yyyyMMddHHmmssSSS
        $notifyUrl = C('NOTIFY_URL'); // 户实现的接收异步通知的url地址
        $remark = base64_encode($payment_code); // 备注
        $postdata = array(
            'platformId' => $platformId,
            'operId' => $operId,
            'selectTradeType' => $selectTradeType,
            'amount' => $amount,
            'orderInfo' => $orderInfo,
            'merchantSeq' => $merchantSeq,
            'transDate' => $transDate,
            'transTime' => $transTime,
            'notifyUrl' => $notifyUrl,
            'remark ' => $remark
        );
        $SourceData = json_encode($postdata);
        $msbank = new MSBank();
        $ret = $msbank->pay($SourceData);
        if ($ret['status'] == 0) {
            $msbank->ModStoreOder($postdata, $ret);
        }
        $this->show(json_encode($ret));
    }

    public function area()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->display('area', 'utf-8');
    }
}