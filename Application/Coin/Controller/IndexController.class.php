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
            ->field('ocenter_tradead.type,ocenter_tradead.id,ocenter_tradead.uid,ocenter_tradead.price,ocenter_tradead.currency,ocenter_tradead.min_price,ocenter_tradead.max_price,ocenter_member.nickname,ocenter_member.trade_num,ocenter_avatar.path,ocenter_country.en_name as countryEn,ocenter_pay.name as payName,ocenter_pay.en_name as payEn')
            ->where('ocenter_tradead.status=1')->select();
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

    public function ad($current,$id)
    {
        $tradead = M('tradead')->join('ocenter_country on ocenter_tradead.country = ocenter_country.id')
            ->join('ocenter_pay on ocenter_tradead.pay_type = ocenter_pay.id')
            ->field('ocenter_tradead.pay_time,ocenter_tradead.price,ocenter_tradead.currency,ocenter_tradead.min_price,ocenter_tradead.max_price,ocenter_country.name as country,ocenter_pay.name as payName,ocenter_pay.en_name as payEn')
            ->where('ocenter_tradead.id='.$id)->find();
        $this->assign('tradead', $tradead);
        $this->assign('current', $current);
        $this->display();
    }

} 