<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2016/10/25
 * Time: 16:12
 * @author:zzl(郑钟良) zzl@ourstu.com
 */
return array(
    'route_rules' => array(
        'ucenter/myad'    => 'ucenter/index/myad',
        'ucenter/myorder'    => 'ucenter/index/myorder',
        'ucenter/following/:uid'    => 'ucenter/index/following',
        'u/[:user_short_url]' => is_mobile() ? 'mob/ucenter/index' : 'Ucenter/index/index',
    ),
    'router' => array(

    )

);