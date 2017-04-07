<?php
namespace Manage\Controller;

// require_once (APP_PATH."Manage/Utils/basic.class.php");
// use Manage\Service\MSBank;
// use Manage\Service\AlipaymaStores;
// use Manage\Service\Areas;
use Think\Controller;

// class CMBCNoticeController extends BaseDealUserController
class CMBCNoticeController extends Controller
{
    public function NoticeServlet()
    {
        header("Content-Type:text/html; charset=utf-8");
        ob_clean();
        echo "SUCCESS";
    }
}
