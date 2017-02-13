<?php
namespace Home\Controller;

use Think\Controller;
use Home\Service\Version;
use Home\Service\Series;
use Home\Service\Toys;
use Home\Service\Home;

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

    

   
}