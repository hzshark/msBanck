<?php
require_once("../../Manage/Lib/php_java.php");//引用LAJP提供的PHP脚本
try
{
   $signAlg = $_REQUEST['signAlg'];
   $base64SourceData = $_REQUEST['base64SourceData'];
   $base64P12Data = $_REQUEST['base64P12Data'];
   $p12Password = $_REQUEST['p12Password'];

   $ret = lajp_call("cfca.sadk.api.SignatureKit::P1SignMessage",  $signAlg,$base64SourceData, $base64P12Data,$p12Password);
   echo "{$ret}<br>";
}
catch(Exception $e)
{
  echo "Err:{$e}<br>";
}
?>
<a href="index.html">return</a>