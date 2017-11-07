<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-4-28
 * Time: 上午11:30
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Coin\Controller;


use Think\Controller;

class IndexController extends Controller{

    protected $newsModel;
    protected $newsDetailModel;
    protected $newsCategoryModel;
    private $defaultCountry = 45;
    private $defaultCurrency = 'CNY';

    function _initialize()
    {
        $sub_menu =
            array(
                'left' =>
                    array(
                        array('tab' => 'btc', 'title' => 'BTC'.L('_TRADE_'), 'href' => U('/btc')),
                        array('tab' => 'eth', 'title' => 'ETH'.L('_TRADE_'), 'href' => U('/eth')),
                    ),
            );
        $this->assign('sub_menu', $sub_menu);

    }


    public function btc()
    {
        $page = I('get.page','1','op_t');
        $type = I('get.type','0','op_t');
        $defaultCurrency = I('get.currency',$this->defaultCurrency,'op_t');
        $defaultCountry = I('get.country',$this->defaultCountry,'op_t');
        $pay_type = I('get.pay_type','0','op_t');
        $week = date('w');
        $hour = date('H:i');
        $hour = str_replace(':','.',$hour);
        $where = array();
        $where['t.status'] = 1;
        $where['t.coin_type'] = 1;
        $where['t.country'] = $defaultCountry;
        $where['t.currency'] = $defaultCurrency;
        $where['t.start'.$week] = array('elt',$hour);
        $where['t.end'.$week] = array('egt',$hour);
        $rows = 10;      //一页显示几条数据
        $offset = $page * $rows - $rows;
        if ($type != 0){
            $where['t.type'] = $type;
        }
        if ($pay_type != 0){
            $where['t.pay_type'] = $pay_type;
        }
        $tradead = M('tradead');
        $totalCount = $tradead->where($where)->count();
        $adList = $tradead->alias('t')->join('ocenter_member m on t.uid = m.uid')
            ->join('ocenter_avatar a on t.uid = a.uid')
            ->join('ocenter_country c on t.country = c.id')
            ->field('t.pay_type,t.type,t.id,t.uid,t.price,a.path,a.driver,t.currency,t.min_price,t.max_price,m.nickname,m.trade_count,m.trade_score,c.en_name as countryEn')
            ->where($where)->order('t.id desc')->limit($offset.','.$rows)->select();
        $country = D('Country')->field('id,name,code')->select();
        $currency = D('Currency')->select();
        $payType = D('pay')->select();
        $pages = $totalCount / $rows;
        if ($pages > 1){
            $pages = ceil($pages)+1;
        }
        $this->assign('defaultCurrency', $defaultCurrency);
        $this->assign('defaultCountry', $defaultCountry);
        $this->assign('pay_type', $pay_type);
        $this->assign('type', $type);
        $this->assign('country', $country);
        $this->assign('currency', $currency);
        $this->assign('payType', $payType);
        $this->assign('adList', $adList);
        $this->assign('rows', $rows);
        $this->assign('pages', $pages);
        $this->assign('page', $page);
        $this->assign('current', 'btc');
        $this->display();
    }

    public function eth()
    {
        $page = I('get.page','1','op_t');
        $type = I('get.type','0','op_t');
        $defaultCurrency = I('get.currency',$this->defaultCurrency,'op_t');
        $defaultCountry = I('get.country',$this->defaultCountry,'op_t');
        $pay_type = I('get.pay_type','0','op_t');
        $week = date('w');
        $hour = date('H:i');
        $hour = str_replace(':','.',$hour);
        $where = array();
        $where['t.status'] = 1;
        $where['t.coin_type'] = 2;
        $where['t.country'] = $defaultCountry;
        $where['t.currency'] = $defaultCurrency;
        $where['t.start'.$week] = array('elt',$hour);
        $where['t.end'.$week] = array('egt',$hour);
        $rows = 10;      //一页显示几条数据
        $offset = $page * $rows - $rows;
        if ($type != 0){
            $where['t.type'] = $type;
        }
        if ($pay_type != 0){
            $where['t.pay_type'] = $pay_type;
        }
        $tradead = M('tradead');
        $totalCount = $tradead->where($where)->count();
        $adList = $tradead->alias('t')->join('ocenter_member m on t.uid = m.uid')
            ->join('ocenter_avatar a on t.uid = a.uid')
            ->join('ocenter_country c on t.country = c.id')
            ->field('t.pay_type,t.type,t.id,t.uid,t.price,a.path,a.driver,t.currency,t.min_price,t.max_price,m.nickname,m.trade_count,m.trade_score,c.en_name as countryEn')
            ->where($where)->order('t.id desc')->limit($offset.','.$rows)->select();
        $country = D('Country')->field('id,name,code')->select();
        $currency = D('Currency')->select();
        $payType = D('pay')->select();
        $pages = $totalCount / $rows;
        if ($pages > 1){
            $pages = ceil($pages)+1;
        }
        $this->assign('defaultCurrency', $defaultCurrency);
        $this->assign('defaultCountry', $defaultCountry);
        $this->assign('pay_type', $pay_type);
        $this->assign('type', $type);
        $this->assign('country', $country);
        $this->assign('currency', $currency);
        $this->assign('payType', $payType);
        $this->assign('adList', $adList);
        $this->assign('rows', $rows);
        $this->assign('pages', $pages);
        $this->assign('page', $page);
        $this->assign('current', 'btc');
        $this->display();
    }

    public function ltc()
    {

    }

    public function tradead()
    {
        if (IS_POST) {
            $status = I('post.status');
            if($status == '0'){
                $this->error(L('_AD_TRADE_TIPS_'));
            }
            $adId = I('post.adId');
            $adUid = I('post.adUid');
            $uid = get_uid();
            if ($uid == $adUid){
                $this->error(L('_AD_TRADE_ERROR_'));
            }
            $coinNum = I('post.coin_num');
            $tradePrice = I('post.trade_price');
            $pay_text = I('post.pay_text');
            $content = D('trade_order')->create();
            $content['ad_id'] = $adId;
            $orderId = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
            $content['order_id'] = $orderId;      //需改
            $content['get_uid'] = $uid;
            $content['ad_uid'] = $adUid;
            $content['coin_num'] = $coinNum;
            $content['trade_price'] = $tradePrice;
            $content['pay_code'] = generate_paycode(7);
            $content['pay_text'] = $pay_text;
            $content['fee'] = $coinNum * 0.005;
            $content['status'] = 1;
            $content['create_time'] = time();
            D('trade_order')->add($content);
            //创建交易聊天
            $memebers = array($adUid);
            D('Common/Talk')->createTradeTalk($memebers,$orderId);
            $this->success(L('_SUBMIT_SUCCESS_'), U('/order/'.$orderId));
        }else{
            $id = I('get.id');
            $tradeadModel = M('tradead');
            $tradead = $tradeadModel->alias('t')->join('ocenter_member m on t.uid = m.uid')
                ->join('ocenter_avatar a on t.uid = a.uid')
                ->join('ocenter_country c on t.country = c.id')
                ->field('t.country as countryId,t.id,t.uid,t.pay_text,t.type,a.path,a.driver,t.pay_type,t.coin_type,t.pay_time,t.price,t.currency,t.min_price,t.max_price,c.name as country,m.nickname,m.trade_count,m.trade_score,m.fans')
                ->where('t.id='.$id)->find();
            $payType = query_pay($tradead['pay_type']);
            $payName = '';
            foreach ($payType as $item){
                $payName .= $item['name'].',';
            }
            $payName = substr($payName,0,strlen($payName)-1);
            $this->assign('payName', $payName);
            $this->assign('tradead', $tradead);
            $this->display();
        }
    }

    public function order()
    {
        $uid = is_login();
        if(!$uid){
            $this->error(L('_ERROR_LOGIN_'));
        }
        if (IS_POST){
            $data['status'] = 1;
            $orderId = I('post.orderId');
            $type = intval(I('post.type'));
            $order = M('trade_order')->where("order_id='".$orderId."'")->find();
            if (!$order){
                return false;
            }
            $tradead = M('tradead')->where('id='.$order['ad_id'])->find();
            if(!$tradead){
                return false;
            }
            if($tradead['type'] ==1 || $tradead['type'] == 3){
                if ($order['ad_uid'] == $uid && $order['status'] == 2){  //时广告主出售 放行货币
                    M('trade_order')->where('id='.$order['id'])->save(array('status'=>3,'update_time'=>time()));
                }else if($order['get_uid'] == $uid && ($order['status'] == 1 || $order['status'] == 2)){//用户购买，已完成付款
                    if ($type == 1){
                        M('trade_order')->where('id='.$order['id'])->save(array('status'=>2,'update_time'=>time()));
                    }else if($type == 2){
                        M('trade_order')->where('id='.$order['id'])->save(array('status'=>5,'update_time'=>time()));
                    }
                }
            }else if($tradead['type'] ==2 || $tradead['type'] == 4){
                if ($order['ad_uid'] == $uid && ($order['status'] == 1 || $order['status'] == 2)){ //广告主在线购买，已完成付款
                    if ($type == 1){
                        M('trade_order')->where('id='.$order['id'])->save(array('status'=>2,'update_time'=>time()));
                    }else if($type == 2){
                        M('trade_order')->where('id='.$order['id'])->save(array('status'=>5,'update_time'=>time()));
                    }
                }else if($order['get_uid'] == $uid && $order['status'] == 2){//用户卖出货币，放行货币
                    M('trade_order')->where('id='.$order['id'])->save(array('status'=>3,'update_time'=>time()));
                }
            }
            echo json_encode($data);
        }else{
            $orderId = I('get.orderId');
            $id = I('get.id',0,'intval');
            $where = array();
            if($id > 0){
                $where['o.id'] = $id;
            }else{
                $where['o.order_id'] = $orderId;
            }
            $where['_string'] = "(o.ad_uid=".$uid." or o.get_uid=".$uid.")";
            $orderModel = M('trade_order');
            $order = $orderModel->alias('o')->join('ocenter_member m on o.ad_uid = m.uid')
                ->join('ocenter_tradead t on o.ad_id = t.id')
                ->join('ocenter_country c on t.country = c.id')
                ->join('ocenter_pay p on t.pay_type = p.id')
                ->field('t.pay_remark,p.en_name as payType,c.en_name as countryEn,o.ad_id,t.type,o.order_id,o.pay_code,o.ad_uid,o.get_uid,t.price,o.trade_price,t.currency,t.coin_type,o.coin_num,o.status,t.pay_time,o.create_time,o.update_time,m.nickname,m.trade_count,m.trade_score')
                ->where($where)->find();
            if (!$order){
                $this->error(L('_INEXISTENT_404_'));
            }
            $getUser = M('member')->field('nickname as getName,trade_count as tradeCount,trade_score as tradeScore')->where('uid='.$order['get_uid'])->find();
            $this->assign('order', $order);
            $this->assign('getUser', $getUser);
            $this->display();
        }
    }

    public function timeOver()
    {
        if (IS_POST){
            $uid = is_login();
            if(!$uid){
                $this->error(L('_ERROR_LOGIN_'));
            }
            $data['status'] = 1;
            $orderId = I('post.orderId');
            $order = M('trade_order')->field('id,status')->where("order_id='".$orderId."' and (ad_uid=".$uid." or get_uid=".$uid.")")->find();
            if (!$order){
                return false;
            }
            if ($order['status'] == 1){// 买家没有响应才关闭交易
                M('trade_order')->where('id='.$order['id'])->save(array('status'=>5,'update_time'=>time()));
            }
            echo json_encode($data);
        }
    }


} 