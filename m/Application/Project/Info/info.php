<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-7-12
 * Time: 上午10:12
 * @author 钱枪枪<8314028@qq.com>
 */


return array(
    //模块名
    'name' => 'Project',
    //别名
    'alias' => '项目管理',
    //版本号
    'version' => '0.0.1',
    //是否商业模块,1是，0，否
    'is_com' => 1,
    //是否显示在导航栏内？  1是，0否
    'show_nav' => 1,
    //模块描述
    'summary' => '项目管理模块',
    //开发者
    'developer' => '嘉兴想天信息科技有限公司',
    //开发者网站
    'website' => 'http://www.opensns.cn',
    //前台入口，可用U函数
    'entry' => 'Project/index/index',

    'admin_entry' => 'Admin/Project/index',
    'icon' => 'comments',
    'can_uninstall' => 1
);