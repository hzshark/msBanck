<?php
//民生银行支付相关
class MinpayAction{
	public $platformId="A00002016120000000294";
	//public $merchantNo="M07002017010000011738";//自己申请的
	public $merchantNo="M29002016120000008515";
	public $notifyUrl_wx='http://111.205.207.103/cmbcpaydemo/NoticeServlet?name=notice';
	public function _initialize(){
	}
	public function index(){
		 //exit($this->cer_sm2());//直接读取私钥内容
		 //exit($this->cer_cust_min());//直接读取民生公钥内容
		 require_once("../Lib/php_java.php");
		 require_once("../Utils/basic.class.php");

		 $SourceData='{"amount":"1","defaultTradeType":"H5_WXJSAPI","merchantName":"XXXXXX公众号","merchantNo":"M01002016090000001273","merchantSeq":"A00002016120000000294T143301951","notifyUrl":"http://111.205.207.103/cmbcpaydemo/NoticeServlet?name=notice","orderInfo":"公众号跳转支付订单信息","platformId":"A00002016120000000294","redirectUrl":"http://111.205.207.103/cmbcpaydemo/","remark":"","selectTradeType":"H5_WXJSAPI","subAppId":"","subOpenId":"","transDate":"20170117","transTime":"20170117143301951"}';//原文
	     $base64SourceData = base64_encode($SourceData);//原文BASE64
	     $base64P12Data = 'MIIDfgIBATBHBgoqgRzPVQYBBAIBBgcqgRzPVQFoBDClyXV+hNlZh3J71DfGgmQuXE/Meyqo68/F
Ik7Zb+vaJhsa7RCwhkA5Xn/IRezDQ7AwggMuBgoqgRzPVQYBBAIBBIIDHjCCAxowggK9oAMCAQIC
BTABAnBYMAwGCCqBHM9VAYN1BQAwKzELMAkGA1UEBhMCQ04xHDAaBgNVBAoME0NGQ0EgU00yIFRF
U1QgT0NBMjEwHhcNMTUwMzA2MDMwMzMyWhcNMTYwMzA2MDMwMzMyWjB0MQswCQYDVQQGEwJDTjEN
MAsGA1UECgwEQ01CQzESMBAGA1UECwwJQ01CQ19EQ01TMRUwEwYDVQQLDAxJbmRpdmlkdWFsLTEx
KzApBgNVBAMMIjAzMDVAMDk1MDA1ODAwNjcxNjEzMUB1c2VyNTQ5NTM3QDEwWTATBgcqhkjOPQIB
BggqgRzPVQGCLQNCAATHK/KpRVf8e4pUFs2ov+hAjgoasBbxSi4yhEP3u0h9xlDaTw4fZyKExwbm
ADS+NXythbCpkIP/clhcibf3x2m2o4IBgTCCAX0wHwYDVR0jBBgwFoAU4n62ELuU6xXmrtEVCv/o
16BXOZ0wSAYDVR0gBEEwPzA9BghggRyG7yoCAjAxMC8GCCsGAQUFBwIBFiNodHRwOi8vd3d3LmNm
Y2EuY29tLmNuL3VzL3VzLTEzLmh0bTCBzgYDVR0fBIHGMIHDMIGSoIGPoIGMhoGJbGRhcDovLzIx
MC43NC40Mi4xMDozODkvY249Y3JsMjUsT1U9U00yLE9VPUNSTCxPPUNGQ0EgU00yIFRFU1QgT0NB
MjEsQz1DTj9jZXJ0aWZpY2F0ZVJldm9jYXRpb25MaXN0P2Jhc2U/b2JqZWN0Y2xhc3M9Y1JMRGlz
dHJpYnV0aW9uUG9pbnQwLKAqoCiGJmh0dHA6Ly8yMTAuNzQuNDIuMy9PQ0EyMS9TTTIvY3JsMjUu
Y3JsMAsGA1UdDwQEAwID6DAdBgNVHQ4EFgQUWZTc4hyNnBa1uRKdcptd3JDjKBkwEwYDVR0lBAww
CgYIKwYBBQUHAwIwDAYIKoEcz1UBg3UFAANJADBGAiEAsKWbuI3Kd4sujhzRXwbesYKjT74vIFYM
rvLLDVYe2YQCIQC/RjQoNpJXclt+xbUSpqYuK1rC7LEKxEN1YanXJ3SxMg==';//私钥
		 $p12Password = '123123';//私钥密码
		 $base64X509CertData='MIIDGjCCAr2gAwIBAgIFMAE1UAQwDAYIKoEcz1UBg3UFADArMQswCQYDVQQGEwJD
TjEcMBoGA1UECgwTQ0ZDQSBTTTIgVEVTVCBPQ0EyMTAeFw0xNTEyMDQwODU4MDla
Fw0xNjEyMDQwODU4MDlaMHQxCzAJBgNVBAYTAkNOMQ0wCwYDVQQKDARDTUJDMRIw
EAYDVQQLDAlDTUJDX0RDTVMxFTATBgNVBAsMDEluZGl2aWR1YWwtMTErMCkGA1UE
AwwiMDMwNUAwMzE4NzQ0ODYyNzc5ODM3QHVzZXIwMDMyNDRAMTBZMBMGByqGSM49
AgEGCCqBHM9VAYItA0IABMaqALdw4tIJEcYg2Bv6TMNj8Odv9cmK5+QVnrjjwxnL
MOoXuzn17R0E7YBAe7j87Krhxqstx6qLcuvGh2jJGUOjggGBMIIBfTAfBgNVHSME
GDAWgBTifrYQu5TrFeau0RUK/+jXoFc5nTBIBgNVHSAEQTA/MD0GCGCBHIbvKgIC
MDEwLwYIKwYBBQUHAgEWI2h0dHA6Ly93d3cuY2ZjYS5jb20uY24vdXMvdXMtMTMu
aHRtMIHOBgNVHR8EgcYwgcMwgZKggY+ggYyGgYlsZGFwOi8vMjEwLjc0LjQyLjEw
OjM4OS9jbj1jcmw5NSxvdT1TTTIsT1U9Q1JMLE89Q0ZDQSBTTTIgVEVTVCBPQ0Ey
MSxDPUNOP2NlcnRpZmljYXRlUmV2b2NhdGlvbkxpc3Q/YmFzZT9vYmplY3RjbGFz
cz1jUkxEaXN0cmlidXRpb25Qb2ludDAsoCqgKIYmaHR0cDovLzIxMC43NC40Mi4z
L09DQTIxL1NNMi9jcmw5NS5jcmwwCwYDVR0PBAQDAgPoMB0GA1UdDgQWBBS1x/e/
puEJbLQnTtwm2y/+fP1b2jATBgNVHSUEDDAKBggrBgEFBQcDAjAMBggqgRzPVQGD
dQUAA0kAMEYCIQCba06exmipgEcbA1uhsM19ldoVY6QDlIqx1+8fjy7xAgIhALbo
idd/NmhTdWKgbs8FRyaYuEbHuu6BNWYeC46GWzTD';//民生公钥
		 $signAlg1 = 'SM3withSM2';//加密方式1，用于签名和校验签名
		 $signAlg2 = 'SM4/CBC/PKCS7Padding';//加密方式2，用于加密信封和解密信封

		 echo'原文：'.$SourceData.'<br><br>';
		 echo'原文Base64：'.$base64SourceData.'<br><br>';

	     $sign = lajp_call("cfca.sadk.api.SignatureKit::P1SignMessage",  $signAlg1,$base64SourceData, $base64P12Data,$p12Password);
		 $sign = json_decode($sign,true);$sign =$sign['Base64SignatureData'];
		 echo'签名：'.$sign.'<br><br>';


		 //$SourceData='{"sign":"'.$sign.'","body":"'.$SourceData.'"}';//拼凑后的原文
		 $SourceData = addslashes($SourceData);
		 $SourceData='{"sign":"'.$sign.'","body":"'.$SourceData.'"}';//拼凑后的原文
		 $base64SourceData = base64_encode($SourceData);//拼凑后的原文Base64
		 echo'拼凑后的原文：'.$SourceData.'<br><br>';
		 echo'拼凑后的原文Base64：'.$base64SourceData.'<br><br>';


		 $sendstr = lajp_call("cfca.sadk.api.EnvelopeKit::envelopeMessage",  $base64SourceData,$signAlg2, $base64X509CertData);
		 $sendstr=json_decode($sendstr,true);$sendstr =$sendstr['Base64EnvelopeMessage'];
		 echo "拼凑后原文加密：$sendstr<br><br>";
		 $sendstrUrl = 'http://wxpay.cmbc.com.cn/mobilePlatform/cmbcPayweb.do?context='.$sendstr;
		 echo'---------------------------------------------------------------------<br>';
		 echo "<a href='".$sendstrUrl."'>将此加密内容发送到接口</a><br>";
		 echo'---------------------------------------------------------------------<br>';
		 $businessContext ='{"businessContext":"'.$sendstr.'","MerchantNo":"","merchantSeq":"","reserve1":"","reserve2":"","reserve3":"","reserve4":"","reserve5":"","reserveJson":"","securityType":"","sessionId":"","source":"","transCode":"","transDate":"","transTime":"","version":""}';
//     $businessContext = '{"businessContext":"MIICeAYKKoEcz1UGAQQCA6CCAmgwggJkAgECMYGdMIGaAgECgBRZlNziHI2cFrW5Ep1ym13ckOMoGTANBgkqgRzPVQGCLQMFAARwblrKrOfe//oc8k5F9/DLppFncGulTFoQHY0TaJ1+WNo7SrhyvQP1Ii0cR4cQNmhrBd9y7XK96ZSLlPNahXCm5AYDXTGD8IdRnnelwGYp7o7yP+ybBgFABw1P7OX/O2fPLXsnS4Ywv9hwDwg2yHimQzCCAb0GCiqBHM9VBgEEAgEwGwYHKoEcz1UBaAQQFFEmLZcBxEUZLkV04QtCYYCCAZBXI5OIhtPlGhNHfUtNOgCihUyAo/y2HpTnHupZSIvQ4qYVscciLOrriJ31aQofH+RWlfojF8wa3fZBuR1vZ2/LJgUNQT+Q8YlhDDdrUh6trC6hb05eWCUqOoGe11v+LsxS/bOZCuhvsLy7dwXQKt7TOVCxy2s8fukF7RyfeJgp1EArHPWQpGIEf6kVmE/tgZrfhABjTFJJBbC+jKFeBukdfkXXPnKoRAz3ZXysn+gl6rvb+td75AzgO9Ro2jaqHRyCYxVuFF2Va76UhemGaNV2yhNpp5AG9pRJd+1R8JazhQ2p9DsLs76g/y3CZtlx3Ssx/cO9gv+bBfZkPdx+poSyvGlJnS31Tr0KcPQjUa3oRGWVoomrfgtVpx5ccjl9SbbDDJhmU+36v1oYeqdA6MC4Og2WzkT3Rl79SiS8+mYdzmgEjQzEki/maL25LnLMwVxz0j6CX5qB1BmSbXAgoYpMJm32VhqLOIFg32DL9UbEZCuU8v432FOAFQJwBTQpMXYLJ7VirzveMW+FoMtQdXtJ","gateReturnCode":"","gateReturnMessage":"","gateReturnType":"S","gateSeq":"20170215221456266","gateTransDate":"20170215","gateTransTime":"20170215221456266","merchantSeq":"","reserve1":"","reserve2":"","reserve3":"","reserveJson":"","transCode":""}';
		 $ret1 = http_post_data('http://wxpay.cmbc.com.cn/cmbcpaydemo/MerchantIncomServlet?name=merchantIncomAdd?name=merchantIncomAdd', $businessContext);
		 var_dump($ret1);
		 echo'---------------------------------------------------------------------<br>';

		 $backstr = lajp_call("cfca.sadk.api.EnvelopeKit::openEvelopedMessage",  $sendstr, $signAlg2, $base64P12Data, $p12Password);
		 var_dump($backstr);
		 $backstr=json_decode($backstr,true);

		 $backstr =$backstr['Base64SourceString'];
		 $base64SourceData=$backstr;
		 $SourceData=base64_decode($backstr);
		 echo "解密后的原文BASE64：$base64SourceData<br><br>";
		 echo "解密后的拼凑原文：".$SourceData."<br><br>";
		 $SourceData=json_decode($SourceData,true);$body =$SourceData['body'];
		 echo "解密后的原文：".$SourceData['body']."<br><br>";
		 echo "解密后的sign：".$SourceData['sign']."<br><br>";

		 $ret = lajp_call("cfca.sadk.api.SignatureKit::P1VerifyMessage", $signAlg1,base64_encode($SourceData['body']), $base64X509CertData,$sign);
    	 echo "校验签名：{$ret}<br><br>";
		 exit;







		 $oldstr='Y2ZjYTEyMzQ=';
		 $base64P12Data=$this->cer_sm2();
		 $p12Password='123123';
		 $sign = lajp_call("cfca.sadk.api.SignatureKit::P1SignMessage",$signAlg1,$oldstr, $this->cer_sm2(),$p12Password);
		 echo '签名：'.$sign.'<br>';exit;
		 $sign = json_decode($sign,true);$sign =$sign['Base64SignatureData'];


		 $sendstr='{"sign":"'.$sign.'","body":"'.$backstr.'"}';
		 echo 'BODY:'.$sendstr.'<br>';

		 $sendstr=lajp_call("cfca.sadk.api.EnvelopeKit::envelopeMessage",  base64_encode($sendstr),'DESede/CBC/PKCS7Padding',$this->cer_cust_min());
		 $sendstr=json_decode($sendstr,true);$sendstr =$sendstr['Base64EnvelopeMessage'];
		 echo '加密BODY:'.$sendstr.'<br>';
		 //go('http://wxpay.cmbc.com.cn/mobilePlatform/cmbcPayweb.do?context='.$sendstr);
		 $ret = lajp_call("cfca.sadk.api.EnvelopeKit::openEvelopedMessage", $sendstr,'DESede/CBC/PKCS7Padding', $this->cer_sm2(), $p12Password);
		 echo $ret;
	}
	//私钥
	public function cer_sm2(){
		return'MIIDfgIBATBHBgoqgRzPVQYBBAIBBgcqgRzPVQFoBDClyXV+hNlZh3J71DfGgmQuXE/Meyqo68/F
Ik7Zb+vaJhsa7RCwhkA5Xn/IRezDQ7AwggMuBgoqgRzPVQYBBAIBBIIDHjCCAxowggK9oAMCAQIC
BTABAnBYMAwGCCqBHM9VAYN1BQAwKzELMAkGA1UEBhMCQ04xHDAaBgNVBAoME0NGQ0EgU00yIFRF
U1QgT0NBMjEwHhcNMTUwMzA2MDMwMzMyWhcNMTYwMzA2MDMwMzMyWjB0MQswCQYDVQQGEwJDTjEN
MAsGA1UECgwEQ01CQzESMBAGA1UECwwJQ01CQ19EQ01TMRUwEwYDVQQLDAxJbmRpdmlkdWFsLTEx
KzApBgNVBAMMIjAzMDVAMDk1MDA1ODAwNjcxNjEzMUB1c2VyNTQ5NTM3QDEwWTATBgcqhkjOPQIB
BggqgRzPVQGCLQNCAATHK/KpRVf8e4pUFs2ov+hAjgoasBbxSi4yhEP3u0h9xlDaTw4fZyKExwbm
ADS+NXythbCpkIP/clhcibf3x2m2o4IBgTCCAX0wHwYDVR0jBBgwFoAU4n62ELuU6xXmrtEVCv/o
16BXOZ0wSAYDVR0gBEEwPzA9BghggRyG7yoCAjAxMC8GCCsGAQUFBwIBFiNodHRwOi8vd3d3LmNm
Y2EuY29tLmNuL3VzL3VzLTEzLmh0bTCBzgYDVR0fBIHGMIHDMIGSoIGPoIGMhoGJbGRhcDovLzIx
MC43NC40Mi4xMDozODkvY249Y3JsMjUsT1U9U00yLE9VPUNSTCxPPUNGQ0EgU00yIFRFU1QgT0NB
MjEsQz1DTj9jZXJ0aWZpY2F0ZVJldm9jYXRpb25MaXN0P2Jhc2U/b2JqZWN0Y2xhc3M9Y1JMRGlz
dHJpYnV0aW9uUG9pbnQwLKAqoCiGJmh0dHA6Ly8yMTAuNzQuNDIuMy9PQ0EyMS9TTTIvY3JsMjUu
Y3JsMAsGA1UdDwQEAwID6DAdBgNVHQ4EFgQUWZTc4hyNnBa1uRKdcptd3JDjKBkwEwYDVR0lBAww
CgYIKwYBBQUHAwIwDAYIKoEcz1UBg3UFAANJADBGAiEAsKWbuI3Kd4sujhzRXwbesYKjT74vIFYM
rvLLDVYe2YQCIQC/RjQoNpJXclt+xbUSpqYuK1rC7LEKxEN1YanXJ3SxMg==';
	}
	//公钥
	public function cer_cust(){
		$d=file_get_contents('./PigCms/Lib/Action/Pay/cer/cust0001.cer');
		$d=str_replace(array("\n","\r"),"",$d);
		return base64_encode(trim($d));
	}
	//民生公钥
	public function cer_cust_min(){
		return'MIIDGjCCAr2gAwIBAgIFMAE1UAQwDAYIKoEcz1UBg3UFADArMQswCQYDVQQGEwJD
TjEcMBoGA1UECgwTQ0ZDQSBTTTIgVEVTVCBPQ0EyMTAeFw0xNTEyMDQwODU4MDla
Fw0xNjEyMDQwODU4MDlaMHQxCzAJBgNVBAYTAkNOMQ0wCwYDVQQKDARDTUJDMRIw
EAYDVQQLDAlDTUJDX0RDTVMxFTATBgNVBAsMDEluZGl2aWR1YWwtMTErMCkGA1UE
AwwiMDMwNUAwMzE4NzQ0ODYyNzc5ODM3QHVzZXIwMDMyNDRAMTBZMBMGByqGSM49
AgEGCCqBHM9VAYItA0IABMaqALdw4tIJEcYg2Bv6TMNj8Odv9cmK5+QVnrjjwxnL
MOoXuzn17R0E7YBAe7j87Krhxqstx6qLcuvGh2jJGUOjggGBMIIBfTAfBgNVHSME
GDAWgBTifrYQu5TrFeau0RUK/+jXoFc5nTBIBgNVHSAEQTA/MD0GCGCBHIbvKgIC
MDEwLwYIKwYBBQUHAgEWI2h0dHA6Ly93d3cuY2ZjYS5jb20uY24vdXMvdXMtMTMu
aHRtMIHOBgNVHR8EgcYwgcMwgZKggY+ggYyGgYlsZGFwOi8vMjEwLjc0LjQyLjEw
OjM4OS9jbj1jcmw5NSxvdT1TTTIsT1U9Q1JMLE89Q0ZDQSBTTTIgVEVTVCBPQ0Ey
MSxDPUNOP2NlcnRpZmljYXRlUmV2b2NhdGlvbkxpc3Q/YmFzZT9vYmplY3RjbGFz
cz1jUkxEaXN0cmlidXRpb25Qb2ludDAsoCqgKIYmaHR0cDovLzIxMC43NC40Mi4z
L09DQTIxL1NNMi9jcmw5NS5jcmwwCwYDVR0PBAQDAgPoMB0GA1UdDgQWBBS1x/e/
puEJbLQnTtwm2y/+fP1b2jATBgNVHSUEDDAKBggrBgEFBQcDAjAMBggqgRzPVQGD
dQUAA0kAMEYCIQCba06exmipgEcbA1uhsM19ldoVY6QDlIqx1+8fjy7xAgIhALbo
idd/NmhTdWKgbs8FRyaYuEbHuu6BNWYeC46GWzTD';
	}
}
$test = new MinpayAction();
$test->index();
