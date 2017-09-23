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
                        array('tab' => 'allad', 'title' => L('_NEW_AD_'), 'href' => U('coin/index/index')),
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
            ->field('ocenter_tradead.type,ocenter_tradead.id,ocenter_tradead.uid,ocenter_tradead.pay_type,ocenter_tradead.price,ocenter_tradead.currency,ocenter_tradead.min_price,ocenter_tradead.max_price,ocenter_member.nickname,ocenter_member.trade_num,ocenter_avatar.path')
            ->where('ocenter_tradead.status=1')->select();
        $this->assign('adList', $adList);
        $this->assign('current', 'allad');
        $this->display();
    }

    public function buybtc()
    {
        $this->assign('current', 'buybtc');
        $this->display();
    }

    public function sellbtc()
    {
        $this->assign('current', 'sellbtc');
        $this->display();
    }

    public function buyeth()
    {
        $this->assign('current', 'buyeth');
        $this->display();
    }

    public function selleth()
    {
        $this->assign('current', 'selleth');
        $this->display();
    }

    public function add()
    {
        $country = D('Country')->field('id,name,code')->select();
        $currency = D('Currency')->select();
        $payType = array(
            array('code' => L('_PAY_CASH_')), array('code' => L('_PAY_ALIPAY_')),
            array('code' => L('_PAY_WECHART_')), array('code' => L('_PAY_BANK_')),
            array('code' => L('_PAY_OTHER_')), array('code' => L('_PAY_SWIFT_')),
            array('code' => L('_PAY_PAYPAL_')), array('code' => L('_PAY_PAYZA_')),
            array('code' => L('_PAY_OKPAY_')), array('code' => L('_PAY_PAYTM_')),
        );
        $time = array(
            array('time' => '0:00'), array('time' => '1:00'), array('time' => '2:00'),
            array('time' => '3:00'), array('time' => '4:00'), array('time' => '5:00'),
            array('time' => '6:00'), array('time' => '7:00'), array('time' => '8:00'),
            array('time' => '9:00'), array('time' => '10:00'), array('time' => '11:00'),
            array('time' => '12:00'), array('time' => '13:00'), array('time' => '14:00'),
            array('time' => '15:00'), array('time' => '16:00'), array('time' => '17:00'),
            array('time' => '18:00'), array('time' => '19:00'), array('time' => '20:00'),
            array('time' => '21:00'), array('time' => '22:00'), array('time' => '23:00'),
            array('time' => '24:00'),
        );
        $marketPrice = 24000;
        $this->assign('country', $country);
        $this->assign('currency', $currency);
        $this->assign('payType', $payType);
        $this->assign('time', $time);
        $this->assign('marketPrice', $marketPrice);
        $this->display();
    }

    public function doPost($coin_type = 0,$type = 0,$country='',$currency='',$pre_price=0,$price=0,$pay_time=0,$pay_addr='',$low_price=0,$min_price=0,$max_price=0,$pay_type='',$pay_text='',$auto_message='',$is_safe=0,$is_trust=0,$start_time1=0,$start_time2=0,$start_time3=0,$start_time4=0,$start_time5=0,$start_time6=0,$start_time7=0,$end_time1=0,$end_time2=0,$end_time3=0,$end_time4=0,$end_time5=0,$end_time6=0,$end_time7=0)
    {
        if (!is_login()) {
            $this->error(L('_ERROR_LOGIN_'));
        }
        if ($coin_type == 0){
            $this->error(L('_ERROR_COIN_TYPE_'));
        }
        if ($type == 0){
            $this->error(L('_ERROR_TYPE_'));
        }
        if ($country == ''){
            $this->error(L('_ERROR_COUNTRY_'));
        }
        if ($currency == ''){
            $this->error(L('_ERROR_CURRENCY_'));
        }
        if ($pre_price == 0){
            $this->error(L('_ERROR_PRE_'));
        }
        if ($min_price == 0){
            $this->error(L('_ERROR_MIN_'));
        }
        if ($max_price == 0){
            $this->error(L('_ERROR_MAX_'));
        }
        if ($pay_type == ''){
            $this->error(L('_ERROR_PAY_'));
        }
        if ($pay_text == ''){
            $this->error(L('_ERROR_TEXT_'));
        }
        $listType = '';
        foreach ($pay_type as $type){
            $listType .= $type;
        }
        $content = D('Tradead')->create();
        $content['uid'] = is_login();
        $content['type'] = $type;
        $content['coin_type'] = $coin_type;
        $content['country'] = $country;
        $content['currency'] = $currency;
        $content['price'] = $price;
        $content['pre_price'] = $pre_price;
        $content['low_price'] = $low_price;
        $content['max_price'] = $max_price;
        $content['min_price'] = $min_price;
        $content['pay_type'] = $listType;
        $content['pay_time'] = $pay_time;
        $content['pay_addr'] = $pay_addr;
        $content['pay_text1'] = $pay_text;
        $content['auto_message'] = $auto_message;
        $content['is_safe'] = $is_safe;
        $content['is_trust'] = $is_trust;
        $content['open_time'] = 'time';
        $content['status'] = 1;
        $content['create_time'] = time();
        D('Tradead')->add($content);
        $this->success(L('_SUCCESS_POST_'), U('index'));
    }


} 