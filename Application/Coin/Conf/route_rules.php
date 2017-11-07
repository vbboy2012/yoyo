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
        'orderid/:id'    => 'coin/index/order',
        'order'    => 'coin/index/order',
        'btc'    => 'coin/index/btc',
        'eth'    => 'coin/index/eth',
        'ltc'    => 'coin/index/ltc',
        'timeOver'    => 'coin/index/timeOver',
    ),


);