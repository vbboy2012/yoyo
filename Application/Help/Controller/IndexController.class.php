<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-4-28
 * Time: 上午11:30
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Help\Controller;


use Think\Controller;

class IndexController extends Controller{

    public function index()
    {
        $ticketMenu =
            array(
                'left' =>
                    array(
                        array('tab' => 'index', 'title' => L('_ADD_TICKET_'), 'href' => U('/index')),
                        array('tab' => 'list', 'title' => L('_LIST_TICKET_'), 'href' => U('/list')),
                    ),
            );
        $this->assign('ticketMenu', $ticketMenu);
        $this->assign('current', 'index');
        $this->display();
    }

} 