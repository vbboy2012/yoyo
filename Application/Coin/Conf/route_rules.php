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
        'tradead/:id/:info'    => 'coin/index/tradead',
        'order/:orderId'    => 'coin/index/order',
        'new'    => 'coin/index/index',
        'buybtc'    => 'coin/index/buybtc',
        'sellbtc'    => 'coin/index/sellbtc',
        'buyeth'    => 'coin/index/buyeth',
        'selleth'    => 'coin/index/selleth',
    ),


);