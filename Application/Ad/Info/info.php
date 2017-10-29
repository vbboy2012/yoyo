<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-4-27
 * Time: 上午10:12
 * @author 郑钟良<zzl@ourstu.com>
 */


return array(
    //模块名
    'name' => 'Ad',
    //别名
    'alias' => '发布广告',
    //版本号
    'version' => '1.0.0',
    //是否商业模块,1是，0，否
    'is_com' => 0,
    //是否显示在导航栏内？  1是，0否
    'show_nav' => 1,
    //模块描述
    'summary' => '广告模块',
    //开发者
    'developer' => 'sobit',
    //开发者网站
    'website' => 'http://www.sobit123.com',
    //前台入口，可用U函数
    'entry' => 'Ad/index/index',

    'admin_entry' => 'Admin/Ad/index',

    'icon' => '',

    'can_uninstall' => 1

);