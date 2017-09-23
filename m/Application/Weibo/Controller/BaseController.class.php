<?php
/**
 * Created by PhpStorm.
 * User: 王杰 wj@ourstu.com
 * Date: 2016/12/7
 * Time: 8:56
 */
namespace Weibo\Controller;

use Think\Controller;

class BaseController extends Controller
{
    public function _initialize()
    {
        if (!is_login() && CONTROLLER_NAME != 'Member') {
            //todo
        }
    }

    public function is_weixin()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }
}