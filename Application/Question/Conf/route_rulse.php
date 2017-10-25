<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/26
 * Time: 14:40
 * @author :  xjw129xjt（駿濤） xjt@ourstu.com
 */


return array(
    'route_rules' => array(
        'question/detail/[:id\d]' => is_mobile() ? 'mob/question/questiondetail' : 'question/index/detail',
    ),
    'router' => array(
        'question/index/detail' => 'question/detail/[id]',
    )

);