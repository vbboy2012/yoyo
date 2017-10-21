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
                        array('tab' => 'quick', 'title' => L('_QUICK_AD_'), 'href' => U('/quick')),
                        array('tab' => 'buybtc', 'title' => L('_BUY_BTC_'), 'href' => U('/buybtc')),
                        array('tab' => 'sellbtc', 'title' => L('_SELL_BTC_'), 'href' => U('/sellbtc')),
                        array('tab' => 'buyeth', 'title' => L('_BUY_ETH_'), 'href' => U('/buyeth')),
                        array('tab' => 'selleth', 'title' => L('_SELL_ETH_'), 'href' => U('/selleth')),
                    ),
            );
        $this->assign('sub_menu', $sub_menu);

    }

    public function index()
    {
        $type = I('get.type','0','op_t');
        $defaultCurrency = I('get.currency',$this->defaultCurrency,'op_t');
        $defaultCountry = I('get.country',$this->defaultCountry,'op_t');
        $pay_type = I('get.pay_type','0','op_t');
        $week = date('w');
        $hour = date('H:i');
        $hour = str_replace(':','.',$hour);
        $where = array();
        $where['ocenter_tradead.status'] = 1;
        $where['ocenter_country.id'] = $defaultCountry;
        $where['ocenter_tradead.currency'] = $defaultCurrency;
        $where['ocenter_tradead.start'.$week] = array('elt',$hour);
        $where['ocenter_tradead.end'.$week] = array('egt',$hour);
        if ($type != 0){
            $where['ocenter_tradead.type'] = $type;
        }
        if ($pay_type != 0){
            $where['ocenter_tradead.pay_type'] = $pay_type;
        }
        $tradead = M('tradead');
        $adList = $tradead->join('ocenter_member on ocenter_tradead.uid = ocenter_member.uid')
            ->join('ocenter_country on ocenter_tradead.country = ocenter_country.id')
            ->field('ocenter_tradead.pay_type,ocenter_tradead.type,ocenter_tradead.id,ocenter_tradead.uid,ocenter_tradead.price,ocenter_tradead.currency,ocenter_tradead.min_price,ocenter_tradead.max_price,ocenter_member.nickname,ocenter_member.trade_count,ocenter_member.trade_score,ocenter_country.en_name as countryEn')
            ->where($where)->order('ocenter_tradead.id desc')->limit(10)->select();
        $country = D('Country')->field('id,name,code')->select();
        $currency = D('Currency')->select();
        $payType = D('pay')->select();
        $this->assign('defaultCurrency', $defaultCurrency);
        $this->assign('defaultCountry', $defaultCountry);
        $this->assign('pay_type', $pay_type);
        $this->assign('type', $type);
        $this->assign('country', $country);
        $this->assign('currency', $currency);
        $this->assign('payType', $payType);
        $this->assign('adList', $adList);
        $this->assign('current', 'quick');
        $this->display();
    }

    public function buybtc()
    {
        $type = I('get.type','0','op_t');
        $defaultCurrency = I('get.currency',$this->defaultCurrency,'op_t');
        $defaultCountry = I('get.country',$this->defaultCountry,'op_t');
        $pay_type = I('get.pay_type','0','op_t');
        $week = date('w');
        $hour = date('H:i');
        $hour = str_replace(':','.',$hour);
        $where = array();
        $where['ocenter_tradead.status'] = 1;
        $where['ocenter_tradead.coin_type'] = 1;
        $where['_string'] = '(ocenter_tradead.type=1 or ocenter_tradead.type=3)';
        $where['ocenter_country.id'] = $defaultCountry;
        $where['ocenter_tradead.currency'] = $defaultCurrency;
        $where['ocenter_tradead.start'.$week] = array('elt',$hour);
        $where['ocenter_tradead.end'.$week] = array('egt',$hour);
        if ($type != 0){
            $where['ocenter_tradead.type'] = $type;
        }
        if ($pay_type != 0){
            $where['ocenter_tradead.pay_type'] = $pay_type;
        }
        $tradead = M('tradead');
        $adList = $tradead->join('ocenter_member on ocenter_tradead.uid = ocenter_member.uid')
            ->join('ocenter_country on ocenter_tradead.country = ocenter_country.id')
            ->field('ocenter_tradead.pay_type,ocenter_tradead.type,ocenter_tradead.id,ocenter_tradead.uid,ocenter_tradead.price,ocenter_tradead.currency,ocenter_tradead.min_price,ocenter_tradead.max_price,ocenter_member.nickname,ocenter_member.trade_count,ocenter_member.trade_score,ocenter_country.en_name as countryEn')
            ->where($where)->order('ocenter_tradead.id desc')->limit(10)->select();
        $country = D('Country')->field('id,name,code')->select();
        $currency = D('Currency')->select();
        $payType = D('pay')->select();
        $this->assign('defaultCurrency', $defaultCurrency);
        $this->assign('defaultCountry', $defaultCountry);
        $this->assign('pay_type', $pay_type);
        $this->assign('type', $type);
        $this->assign('country', $country);
        $this->assign('currency', $currency);
        $this->assign('payType', $payType);
        $this->assign('adList', $adList);
        $this->assign('current', 'buybtc');
        $this->display();
    }

    public function sellbtc()
    {
        $type = I('get.type','0','op_t');
        $defaultCurrency = I('get.currency',$this->defaultCurrency,'op_t');
        $defaultCountry = I('get.country',$this->defaultCountry,'op_t');
        $pay_type = I('get.pay_type','0','op_t');
        $week = date('w');
        $hour = date('H:i');
        $hour = str_replace(':','.',$hour);
        $where = array();
        $where['ocenter_tradead.status'] = 1;
        $where['ocenter_tradead.coin_type'] = 1;
        $where['_string'] = '(ocenter_tradead.type=2 or ocenter_tradead.type=4)';
        $where['ocenter_country.id'] = $defaultCountry;
        $where['ocenter_tradead.currency'] = $defaultCurrency;
        $where['ocenter_tradead.start'.$week] = array('elt',$hour);
        $where['ocenter_tradead.end'.$week] = array('egt',$hour);
        if ($type != 0){
            $where['ocenter_tradead.type'] = $type;
        }
        if ($pay_type != 0){
            $where['ocenter_tradead.pay_type'] = $pay_type;
        }
        $tradead = M('tradead');
        $adList = $tradead->join('ocenter_member on ocenter_tradead.uid = ocenter_member.uid')
            ->join('ocenter_country on ocenter_tradead.country = ocenter_country.id')
            ->field('ocenter_tradead.pay_type,ocenter_tradead.type,ocenter_tradead.id,ocenter_tradead.uid,ocenter_tradead.price,ocenter_tradead.currency,ocenter_tradead.min_price,ocenter_tradead.max_price,ocenter_member.nickname,ocenter_member.trade_count,ocenter_member.trade_score,ocenter_country.en_name as countryEn')
            ->where($where)->order('ocenter_tradead.id desc')->limit(10)->select();
        $country = D('Country')->field('id,name,code')->select();
        $currency = D('Currency')->select();
        $payType = D('pay')->select();
        $this->assign('defaultCurrency', $defaultCurrency);
        $this->assign('defaultCountry', $defaultCountry);
        $this->assign('pay_type', $pay_type);
        $this->assign('type', $type);
        $this->assign('country', $country);
        $this->assign('currency', $currency);
        $this->assign('payType', $payType);
        $this->assign('adList', $adList);
        $this->assign('current', 'sellbtc');
        $this->display();
    }

    public function buyeth()
    {
        $type = I('get.type','0','op_t');
        $defaultCurrency = I('get.currency',$this->defaultCurrency,'op_t');
        $defaultCountry = I('get.country',$this->defaultCountry,'op_t');
        $pay_type = I('get.pay_type','0','op_t');
        $week = date('w');
        $hour = date('H:i');
        $hour = str_replace(':','.',$hour);
        $where = array();
        $where['ocenter_tradead.status'] = 1;
        $where['ocenter_tradead.coin_type'] = 2;
        $where['_string'] = '(ocenter_tradead.type=1 or ocenter_tradead.type=3)';
        $where['ocenter_country.id'] = $defaultCountry;
        $where['ocenter_tradead.currency'] = $defaultCurrency;
        $where['ocenter_tradead.start'.$week] = array('elt',$hour);
        $where['ocenter_tradead.end'.$week] = array('egt',$hour);
        if ($type != 0){
            $where['ocenter_tradead.type'] = $type;
        }
        if ($pay_type != 0){
            $where['ocenter_tradead.pay_type'] = $pay_type;
        }
        $tradead = M('tradead');
        $adList = $tradead->join('ocenter_member on ocenter_tradead.uid = ocenter_member.uid')
            ->join('ocenter_country on ocenter_tradead.country = ocenter_country.id')
            ->field('ocenter_tradead.pay_type,ocenter_tradead.type,ocenter_tradead.id,ocenter_tradead.uid,ocenter_tradead.price,ocenter_tradead.currency,ocenter_tradead.min_price,ocenter_tradead.max_price,ocenter_member.nickname,ocenter_member.trade_count,ocenter_member.trade_score,ocenter_country.en_name as countryEn')
            ->where($where)->order('ocenter_tradead.id desc')->limit(10)->select();
        $country = D('Country')->field('id,name,code')->select();
        $currency = D('Currency')->select();
        $payType = D('pay')->select();
        $this->assign('defaultCurrency', $defaultCurrency);
        $this->assign('defaultCountry', $defaultCountry);
        $this->assign('pay_type', $pay_type);
        $this->assign('type', $type);
        $this->assign('country', $country);
        $this->assign('currency', $currency);
        $this->assign('payType', $payType);
        $this->assign('adList', $adList);
        $this->assign('current', 'buyeth');
        $this->display();
    }

    public function selleth()
    {
        $type = I('get.type','0','op_t');
        $defaultCurrency = I('get.currency',$this->defaultCurrency,'op_t');
        $defaultCountry = I('get.country',$this->defaultCountry,'op_t');
        $pay_type = I('get.pay_type','0','op_t');
        $week = date('w');
        $hour = date('H:i');
        $hour = str_replace(':','.',$hour);
        $where = array();
        $where['ocenter_tradead.status'] = 1;
        $where['ocenter_tradead.coin_type'] = 2;
        $where['_string'] = '(ocenter_tradead.type=3 or ocenter_tradead.type=4)';
        $where['ocenter_country.id'] = $defaultCountry;
        $where['ocenter_tradead.currency'] = $defaultCurrency;
        $where['ocenter_tradead.start'.$week] = array('elt',$hour);
        $where['ocenter_tradead.end'.$week] = array('egt',$hour);
        if ($type != 0){
            $where['ocenter_tradead.type'] = $type;
        }
        if ($pay_type != 0){
            $where['ocenter_tradead.pay_type'] = $pay_type;
        }
        $tradead = M('tradead');
        $adList = $tradead->join('ocenter_member on ocenter_tradead.uid = ocenter_member.uid')
            ->join('ocenter_country on ocenter_tradead.country = ocenter_country.id')
            ->field('ocenter_tradead.pay_type,ocenter_tradead.type,ocenter_tradead.id,ocenter_tradead.uid,ocenter_tradead.price,ocenter_tradead.currency,ocenter_tradead.min_price,ocenter_tradead.max_price,ocenter_member.nickname,ocenter_member.trade_count,ocenter_member.trade_score,ocenter_country.en_name as countryEn')
            ->where($where)->order('ocenter_tradead.id desc')->limit(10)->select();
        $country = D('Country')->field('id,name,code')->select();
        $currency = D('Currency')->select();
        $payType = D('pay')->select();
        $this->assign('defaultCurrency', $defaultCurrency);
        $this->assign('defaultCountry', $defaultCountry);
        $this->assign('pay_type', $pay_type);
        $this->assign('type', $type);
        $this->assign('country', $country);
        $this->assign('currency', $currency);
        $this->assign('payType', $payType);
        $this->assign('adList', $adList);
        $this->assign('current', 'selleth');
        $this->display();
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
            $type = I('post.type');
            $coin_type = I('post.coin_type');
            $coinNum = I('post.coin_num');
            $price = I('post.price');
            $payText = I('post.pay_text');
            $currency = I('post.currency');
            $content = D('trade_order')->create();
            $content['ad_id'] = $adId;
            $orderId = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
            $content['order_id'] = $orderId;      //需改
            $content['get_uid'] = $uid;
            $content['ad_uid'] = $adUid;
            $content['type'] = $type;
            $content['coin_type'] = $coin_type;
            $content['coin_num'] = $coinNum;
            $content['price'] = $price;
            $content['fee'] = $coinNum * 0.005;
            $content['currency'] = $currency;
            $content['pay_text'] = $payText;
            $content['status'] = 1;
            $content['create_time'] = time();
            D('trade_order')->add($content);
            //创建交易聊天
            $memebers = array($adUid);
            D('Common/Talk')->createTradeTalk($memebers,$orderId);
            $this->success(L('_SUCCESS_POST_'), U('/order/'.$orderId));
        }else{
            $ratePrice = 25000;
            $id = I('get.id');
            $tradead = M('tradead')->join('ocenter_member on ocenter_tradead.uid = ocenter_member.uid')
                ->join('ocenter_country on ocenter_tradead.country = ocenter_country.id')
                ->field('ocenter_tradead.id,ocenter_tradead.uid,ocenter_tradead.pay_text,ocenter_tradead.type,ocenter_tradead.pay_type,ocenter_tradead.coin_type,ocenter_tradead.pay_time,ocenter_tradead.price,ocenter_tradead.currency,ocenter_tradead.min_price,ocenter_tradead.max_price,ocenter_country.name as country,ocenter_member.nickname,ocenter_member.trade_count,ocenter_member.trade_score,ocenter_member.fans,ocenter_tradead.start0,ocenter_tradead.start1,ocenter_tradead.start2,ocenter_tradead.start3,ocenter_tradead.start4,ocenter_tradead.start5,ocenter_tradead.start6,ocenter_tradead.end0,ocenter_tradead.end1,ocenter_tradead.end2,ocenter_tradead.end3,ocenter_tradead.end4,ocenter_tradead.end5,ocenter_tradead.end6')
                ->where('ocenter_tradead.id='.$id)->find();
            $payType = query_pay($tradead['pay_type']);
            $payName = '';
            foreach ($payType as $item){
                $payName .= $item['name'].',';
            }
            $payName = substr($payName,0,strlen($payName)-1);
            $avatar = query_avatar($tradead['uid']);
            $this->assign('avatar', $avatar);
            $this->assign('payName', $payName);
            $this->assign('tradead', $tradead);
            $this->assign('ratePrice', $ratePrice);
            $this->display();
        }
    }

    public function order()
    {
      //  $order = M('trade_order')->where('order_id='.$orderId)->find();
        $orderId = I('get.orderId');
        $this->assign('orderId', $orderId);
        $this->display();
    }



} 