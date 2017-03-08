<?php
namespace Manage\Controller;

require_once (APP_PATH."Manage/Utils/basic.class.php");
use Manage\Service\MSBank;
use Manage\Service\AlipaymaStores;
use Manage\Service\Areas;

class CMBCController extends BaseDealUserController
{
    public function __construct(){

        parent::__construct();
    }

    private $SUCCESS = 0;

    private $ERROR = 1;

    public function Index()
    {
        header("Content-Type:text/html; charset=utf-8");
        $stores = new AlipaymaStores();
        $storesInfo = $stores->queryAllStores();
        // var_dump($storesInfo);
        $this->assign("stores", $storesInfo);

        $this->display('index', 'utf-8');
    }

    public function Register()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->display('register', 'utf-8');
    }

    public function VerifyStore()
    {
        header("Content-Type:text/html; charset=utf-8");
        $txnSeq = generateOrderno(); // 流水号, 调用方生成，确保唯一
        $platformId = C('platformId'); // 平台号, 民生银行生成
        $operId = C('operId'); // 拓展人员编号
        $dataSrc = C('dataSrc'); // 进件渠道, 填固定值2

        $devType = 1; // 拓展模式,类型代码对应： 1-第三方 2-民生银行
        $postdata = array(
            'txnSeq' => $txnSeq,
            'platformId' => $platformId,
            'operId' => $operId,
            'dataSrc' => $dataSrc,
            'devType' => '' . $devType
        );

        $id = isset($_GET['id']) ? $_GET['id'] : '';
        if ($id == '') {
            $this->error("缺少参数,商家id");
        }
        $stores = new AlipaymaStores();

        $ret = $stores->queryStoreinfoById($id);
        $postdata['outMchntId'] = $ret['outmchntid'];
        $postdata['mchntName'] = $ret['mchntname'];
        $postdata['mchntFullName'] = $ret['mchntfullname'];
        $postdata['parentMchntId'] = $ret['parentmchntId'];
        $postdata['acdCode'] = $ret['acdcode'];
        $postdata['province'] = $ret['province'];
        $postdata['city'] = $ret['city'];
        $postdata['address'] = $ret['address'];
        $postdata['isCert'] = $ret['iscert'];
        $postdata['licId'] = $ret['licid'];
        $postdata['licValidity'] = $ret['licvalidity'];
        $postdata['corpName'] = $ret['corpname'];
        $postdata['idtCard'] = $ret['idtcard'];
        $postdata['contactName'] = $ret['contactname'];
        $postdata['telephone'] = $ret['telephone'];
        $postdata['servTel'] = $ret['servtel'];
        $postdata['identification'] = $ret['identification'];
        $postdata['autoSettle'] = $ret['autosettle'];
        $postdata['remark'] = $ret['remark'];
        $postdata['message'] = $ret['message'];
        $sourceData = json_encode($postdata);
        $cmbc = new MSBank();
        $ret = $cmbc->registerStore($sourceData);
        $stores->registerOder($id, $postdata, $ret);
        if ($ret['status'] == 0) {
            $this->success("商户入驻成功,返回结果:<br />" . json_encode($ret['respone']));
        } else {
            $this->show("商户入驻失败,失败原因:" . $ret['msg'], 10);
        }
    }

    public function RegisterStore()
    {
        header("Content-Type:text/html; charset=utf-8");

        if (IS_POST) {
            $outMchntId = generate_guid(); // 外部商户号, 商户自己生成，确保唯一
//             $outMchntId = 'o29002017030000013925'; // 外部商户号, 商户自己生成，确保唯一
            $mchntName = isset($_POST['mchntName']) ? $_POST['mchntName'] : ''; // 商户简称
            $mchntFullName = isset($_POST['mchntFullName']) ? $_POST['mchntFullName'] : ''; // 商户全称,请填写营业执照上的全称
            $parentMchntId = isset($_POST['parentMchntId']) ? $_POST['parentMchntId'] : '';
            $area = new Areas();
            $province = isset($_POST['province_code']) ? $_POST['province_code'] : '';
            $city = isset($_POST['city_code']) ? $_POST['city_code'] : '';
            $acdCode = isset($_POST['district_code']) ? $_POST['district_code'] : '';
            $addr = isset($_POST['addr']) ? $_POST['addr'] : '';
            $province_name = $area->queryNameByAreaCode($province);
            $city_name = $area->queryNameByAreaCode($city);
            $area_name = $area->queryNameByAreaCode($acdCode);
            $address = $area_name['name'] . $addr; // 地址
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
                'outMchntId' => $outMchntId,
                'mchntName' => $mchntName,
                'mchntFullName' => $mchntFullName,
                'parentMchntId' => $parentMchntId,
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
            $stores = new AlipaymaStores();
            $stores->createStoreAndReturnId($postdata);
            $this->show("商家入驻成功");
        }
    }

    public function VerifyModStore()
    {
        header("Content-Type:text/html; charset=utf-8");
        $txnSeq = generateOrderno(); // 流水号, 调用方生成，确保唯一
        $platformId = C('platformId'); // 平台号, 民生银行生成
        $operId = C('operId'); // 拓展人员编号
        $dataSrc = C('dataSrc'); // 进件渠道, 填固定值2
        $devType = 1; // 拓展模式,类型代码对应： 1-第三方 2-民生银行
        $postdata = array(
            'txnSeq' => $txnSeq,
            'platformId' => $platformId,
            'operId' => $operId,
            'dataSrc' => $dataSrc,
            'devType' => '' . $devType
        );

        $id = isset($_GET['id']) ? $_GET['id'] : '';
        if ($id == '') {
            $this->error("缺少参数,商家id");
        }
        $stores = new AlipaymaStores();
        $storeInfo = $stores->queryStoreinfoById($id);
        $cmbcInfo = $stores->queryCMBCIDByStoreId($id);
        $postdata['outMchntId'] = $storeInfo['outmchntid'];
        $postdata['mchntName'] = $storeInfo['mchntname'];
        $postdata['cmbcMchntId'] = $cmbcInfo['cmbcmchntid'];
        $postdata['mchntFullName'] = $storeInfo['mchntfullname'];
        $postdata['parentMchntId'] = $storeInfo['parentmchntId'];
        $postdata['acdCode'] = $storeInfo['acdcode'];
        $postdata['province'] = $storeInfo['province'];
        $postdata['city'] = $storeInfo['city'];
        $postdata['address'] = $storeInfo['address'];
        $postdata['isCert'] = $storeInfo['iscert'];
        $postdata['licId'] = $storeInfo['licid'];
        $postdata['licValidity'] = $storeInfo['licvalidity'];
        $postdata['corpName'] = $storeInfo['corpname'];
        $postdata['idtCard'] = $storeInfo['idtcard'];
        $postdata['contactName'] = $storeInfo['contactname'];
        $postdata['telephone'] = $storeInfo['telephone'];
        $postdata['servTel'] = $storeInfo['servtel'];
        $postdata['identification'] = $storeInfo['identification'];
        $postdata['autoSettle'] = $storeInfo['autosettle'];
        $postdata['remark'] = $storeInfo['remark'];
        $postdata['message'] = $storeInfo['message'];
        $sourceData = json_encode($postdata);
        $cmbc = new MSBank();
        $ret = $cmbc->modStoreInfo($sourceData);
        $stores->registerOder($id, $postdata, $ret);
        if ($ret['status'] == 0) {
            $this->show("商户信息修改成功,返回结果:<br />" . json_encode($ret['respone']));
        } else {
            $this->show("商户信息修改失败,失败原因:" . $ret['msg'], 10);
        }
    }

    public function ModStore()
    {
        header("Content-Type:text/html; charset=utf-8");
        if (IS_GET) {
            $store = new AlipaymaStores();
            $id = isset($_GET['id']) ? $_GET['id'] : '';
            $storeInfo = $store->queryStoreinfoById($id);
            $this->assign("store", $storeInfo);
            $this->display('modstore', 'utf-8');
        } else {
            $mchntName = isset($_POST['mchntName']) ? $_POST['mchntName'] : ''; // 商户简称
            $mchntFullName = isset($_POST['mchntFullName']) ? $_POST['mchntFullName'] : ''; // 商户全称,请填写营业执照上的全称
            $parentMchntId = isset($_POST['parentMchntId']) ? $_POST['parentMchntId'] : '';
            $area = new Areas();
            $province = isset($_POST['province_code']) ? $_POST['province_code'] : '';
            $city = isset($_POST['city_code']) ? $_POST['city_code'] : '';
            $acdCode = isset($_POST['district_code']) ? $_POST['district_code'] : '';
            $addr = isset($_POST['addr']) ? $_POST['addr'] : '';
            $province_name = $area->queryNameByAreaCode($province);
            $city_name = $area->queryNameByAreaCode($city);
            $area_name = $area->queryNameByAreaCode($acdCode);
            $address = $area_name['name'] . $addr; // 地址
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
                'mchntName' => $mchntName,
                'mchntFullName' => $mchntFullName,
                'parentMchntId' => $parentMchntId,
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

            $stores = new AlipaymaStores();

            $stores->modStore($id, $postdata);
            $this->show("商家入驻成功");
        }
    }

    public function BindPayment()
    {
        header("Content-Type:text/html; charset=utf-8");
        if (IS_GET) {
            $stores = new AlipaymaStores();
            $id = isset($_GET['id']) ? $_GET['id'] : '';
            $storeInfo = $stores->queryStoreinfoById($id);
            $txnSeq = generateOrderno(); // 流水号, 调用方生成，确保唯一
            $this->assign("txnSeq", $txnSeq);
            $this->assign("store", $storeInfo);
            $this->display('bindPayment', 'utf-8');
        } else {
            $txnSeq = generateOrderno(); // 流水号, 调用方生成，确保唯一
            $platformId = C('platformId'); // 平台号, 民生银行生成
            $operId = '10086A0001'; // 拓展人员编号

            $outMchntId = 'o29002017030000013925'; // 外部商户号, 商户自己生成，确保唯一
            $cmbcMchntId = 'M01002017030000013951';

            $apiCode = '0005'; // 支付通道, 类型代码对应： 0005-微信 0007-支付宝 0008-QQ钱包
            $industryId = '102';
            $operateType = '1'; // 接入类型,类型代码对应： 1-间联 2-直联
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
                'message' => $message,
                'idCode' => '',
                "acctTelephone" =>'',
                'idType'=>'99'
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
