<?php

require_once("../../Manage/Lib/php_java.php");//����LAJP�ṩ��PHP�ű�

$name ="LAJP";  //����һ������

try
{
  //����Java��hello.HelloClass���е�hello����
  $ret = lajp_call("hello.HelloClass::hello", $name);
  echo "{$ret}<br>";
}
catch(Exception $e)
{
  echo "Err:{$ret}<br>";
  echo "$e";
}
?>

<?php

    Phpinfo();

?>