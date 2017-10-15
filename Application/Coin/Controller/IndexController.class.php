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

    function _initialize()
    {
        $sub_menu =
            array(
                'left' =>
                    array(
                        array('tab' => 'new', 'title' => L('_NEW_AD_'), 'href' => U('coin/index/index')),
                        array('tab' => 'buybtc', 'title' => L('_BUY_BTC_'), 'href' => U('coin/index/buybtc')),
                        array('tab' => 'sellbtc', 'title' => L('_SELL_BTC_'), 'href' => U('coin/index/sellbtc')),
                        array('tab' => 'buyeth', 'title' => L('_BUY_ETH_'), 'href' => U('coin/index/buyeth')),
                        array('tab' => 'selleth', 'title' => L('_SELL_ETH_'), 'href' => U('coin/index/selleth')),
                    ),
            );
        $this->assign('sub_menu', $sub_menu);

    }

    public function index()
    {
        $tradead = M('tradead');
        $adList = $tradead->join('ocenter_member on ocenter_tradead.uid = ocenter_member.uid')
            ->join('ocenter_avatar on ocenter_avatar.uid = ocenter_member.uid')
            ->join('ocenter_country on ocenter_tradead.country = ocenter_country.id')
            ->join('ocenter_pay on ocenter_tradead.pay_type = ocenter_pay.id')
            ->field('ocenter_tradead.pay_type,ocenter_tradead.type,ocenter_tradead.id,ocenter_tradead.uid,ocenter_tradead.price,ocenter_tradead.currency,ocenter_tradead.min_price,ocenter_tradead.max_price,ocenter_member.nickname,ocenter_member.trade_count,ocenter_member.trade_score,ocenter_avatar.path,ocenter_country.en_name as countryEn,ocenter_pay.name as payName,ocenter_pay.en_name as payEn')
            ->where('ocenter_tradead.status=1')->order('ocenter_tradead.id desc')->select();
        $country = D('Country')->field('id,name,code')->select();
        $currency = D('Currency')->select();
        $payType = array(
            array('code' => L('_PAY_CASH_')), array('code' => L('_PAY_ALIPAY_')),
            array('code' => L('_PAY_WECHART_')), array('code' => L('_PAY_BANK_')),
            array('code' => L('_PAY_OTHER_')), array('code' => L('_PAY_SWIFT_')),
            array('code' => L('_PAY_PAYPAL_')), array('code' => L('_PAY_PAYZA_')),
            array('code' => L('_PAY_OKPAY_')), array('code' => L('_PAY_PAYTM_')),
        );
        $this->assign('country', $country);
        $this->assign('currency', $currency);
        $this->assign('payType', $payType);
        $this->assign('adList', $adList);
        $this->assign('current', 'new');
        $this->display();
    }

    public function buybtc()
    {
        $tradead = M('tradead');
        $adList = $tradead->join('ocenter_member on ocenter_tradead.uid = ocenter_member.uid')
            ->join('ocenter_avatar on ocenter_avatar.uid = ocenter_member.uid')
            ->field('ocenter_tradead.type,ocenter_tradead.id,ocenter_tradead.uid,ocenter_tradead.pay_type,ocenter_tradead.price,ocenter_tradead.currency,ocenter_tradead.min_price,ocenter_tradead.max_price,ocenter_member.nickname,ocenter_member.trade_num,ocenter_avatar.path')
            ->where('ocenter_tradead.status=1 and ocenter_tradead.coin_type=1 and (ocenter_tradead.type=1 || ocenter_tradead.type=3)')->select();
        $country = D('Country')->field('id,name,code')->select();
        $currency = D('Currency')->select();
        $payType = array(
            array('code' => L('_PAY_CASH_')), array('code' => L('_PAY_ALIPAY_')),
            array('code' => L('_PAY_WECHART_')), array('code' => L('_PAY_BANK_')),
            array('code' => L('_PAY_OTHER_')), array('code' => L('_PAY_SWIFT_')),
            array('code' => L('_PAY_PAYPAL_')), array('code' => L('_PAY_PAYZA_')),
            array('code' => L('_PAY_OKPAY_')), array('code' => L('_PAY_PAYTM_')),
        );
        $this->assign('country', $country);
        $this->assign('currency', $currency);
        $this->assign('payType', $payType);
        $this->assign('adList', $adList);
        $this->assign('current', 'buybtc');
        $this->display();
    }

    public function sellbtc()
    {
        $tradead = M('tradead');
        $adList = $tradead->join('ocenter_member on ocenter_tradead.uid = ocenter_member.uid')
            ->join('ocenter_avatar on ocenter_avatar.uid = ocenter_member.uid')
            ->field('ocenter_tradead.type,ocenter_tradead.id,ocenter_tradead.uid,ocenter_tradead.pay_type,ocenter_tradead.price,ocenter_tradead.currency,ocenter_tradead.min_price,ocenter_tradead.max_price,ocenter_member.nickname,ocenter_member.trade_num,ocenter_avatar.path')
            ->where('ocenter_tradead.status=1 and ocenter_tradead.coin_type=1 and (ocenter_tradead.type=2 || ocenter_tradead.type=4)')->select();
        $country = D('Country')->field('id,name,code')->select();
        $currency = D('Currency')->select();
        $payType = array(
            array('code' => L('_PAY_CASH_')), array('code' => L('_PAY_ALIPAY_')),
            array('code' => L('_PAY_WECHART_')), array('code' => L('_PAY_BANK_')),
            array('code' => L('_PAY_OTHER_')), array('code' => L('_PAY_SWIFT_')),
            array('code' => L('_PAY_PAYPAL_')), array('code' => L('_PAY_PAYZA_')),
            array('code' => L('_PAY_OKPAY_')), array('code' => L('_PAY_PAYTM_')),
        );
        $this->assign('country', $country);
        $this->assign('currency', $currency);
        $this->assign('payType', $payType);
        $this->assign('adList', $adList);
        $this->assign('current', 'sellbtc');
        $this->display();
    }

    public function buyeth()
    {
        $tradead = M('tradead');
        $adList = $tradead->join('ocenter_member on ocenter_tradead.uid = ocenter_member.uid')
            ->join('ocenter_member on ocenter_tradead.uid = ocenter_member.uid')
            ->join('ocenter_avatar on ocenter_avatar.uid = ocenter_member.uid')
            ->join('ocenter_country on ocenter_tradead.country = ocenter_country.id')
            ->field('ocenter_tradead.type,ocenter_tradead.id,ocenter_tradead.uid,ocenter_tradead.pay_type,ocenter_tradead.price,ocenter_tradead.currency,ocenter_tradead.min_price,ocenter_tradead.max_price,ocenter_member.nickname,ocenter_member.trade_num,ocenter_avatar.path,ocenter_country.en_name')
            ->where('ocenter_tradead.status=1 and ocenter_tradead.coin_type=2 and (ocenter_tradead.type=1 || ocenter_tradead.type=2)')->select();
        $country = D('Country')->field('id,name,code')->select();
        $currency = D('Currency')->select();
        $payType = array(
            array('code' => L('_PAY_CASH_')), array('code' => L('_PAY_ALIPAY_')),
            array('code' => L('_PAY_WECHART_')), array('code' => L('_PAY_BANK_')),
            array('code' => L('_PAY_OTHER_')), array('code' => L('_PAY_SWIFT_')),
            array('code' => L('_PAY_PAYPAL_')), array('code' => L('_PAY_PAYZA_')),
            array('code' => L('_PAY_OKPAY_')), array('code' => L('_PAY_PAYTM_')),
        );
        $this->assign('country', $country);
        $this->assign('currency', $currency);
        $this->assign('payType', $payType);
        $this->assign('adList', $adList);
        $this->assign('current', 'buyeth');
        $this->display();
    }

    public function selleth()
    {
        $tradead = M('tradead');
        $adList = $tradead->join('ocenter_member on ocenter_tradead.uid = ocenter_member.uid')
            ->join('ocenter_avatar on ocenter_avatar.uid = ocenter_member.uid')
            ->field('ocenter_tradead.type,ocenter_tradead.id,ocenter_tradead.uid,ocenter_tradead.pay_type,ocenter_tradead.price,ocenter_tradead.currency,ocenter_tradead.min_price,ocenter_tradead.max_price,ocenter_member.nickname,ocenter_member.trade_num,ocenter_avatar.path')
            ->where('ocenter_tradead.status=1 and ocenter_tradead.coin_type=2 and (ocenter_tradead.type=3 || ocenter_tradead.type=4)')->select();
        $country = D('Country')->field('id,name,code')->select();
        $currency = D('Currency')->select();
        $payType = array(
            array('code' => L('_PAY_CASH_')), array('code' => L('_PAY_ALIPAY_')),
            array('code' => L('_PAY_WECHART_')), array('code' => L('_PAY_BANK_')),
            array('code' => L('_PAY_OTHER_')), array('code' => L('_PAY_SWIFT_')),
            array('code' => L('_PAY_PAYPAL_')), array('code' => L('_PAY_PAYZA_')),
            array('code' => L('_PAY_OKPAY_')), array('code' => L('_PAY_PAYTM_')),
        );
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
                ->join('ocenter_avatar on ocenter_avatar.uid = ocenter_tradead.uid')
                ->join('ocenter_country on ocenter_tradead.country = ocenter_country.id')
                ->join('ocenter_pay on ocenter_tradead.pay_type = ocenter_pay.id')
                ->field('ocenter_tradead.id,ocenter_tradead.uid,ocenter_tradead.pay_text,ocenter_tradead.type,ocenter_tradead.coin_type,ocenter_tradead.pay_time,ocenter_tradead.price,ocenter_tradead.currency,ocenter_tradead.min_price,ocenter_tradead.max_price,ocenter_country.name as country,ocenter_pay.name as payName,ocenter_pay.en_name as payEn,ocenter_member.nickname,ocenter_member.trade_count,ocenter_member.trade_score,ocenter_member.fans,ocenter_avatar.path,ocenter_tradead.open_time')
                ->where('ocenter_tradead.id='.$id)->find();
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