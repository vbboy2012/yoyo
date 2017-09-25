<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-4-28
 * Time: 上午11:30
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Ad\Controller;


use Think\Controller;

class IndexController extends Controller{


    public function add()
    {
        $country = D('Country')->field('id,name,code')->select();
        $currency = D('Currency')->select();
        $payType = D('Pay')->select();
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

    public function doPost($coin_type = 0,$type = 0,$country=0,$currency='',$pre_price=0,$price=0,$pay_time=0,$pay_addr='',$low_price=0,$min_price=0,$max_price=0,$pay_type=0,$pay_text='',$auto_message='',$is_safe=0,$is_trust=0,$start_time1=0,$start_time2=0,$start_time3=0,$start_time4=0,$start_time5=0,$start_time6=0,$start_time7=0,$end_time1=0,$end_time2=0,$end_time3=0,$end_time4=0,$end_time5=0,$end_time6=0,$end_time7=0)
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
        if ($country == 0){
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
        if ($pay_type == 0){
            $this->error(L('_ERROR_PAY_'));
        }
        if ($pay_text == ''){
            $this->error(L('_ERROR_TEXT_'));
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
        $content['pay_type'] = $pay_type;
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
        $this->success(L('_SUCCESS_POST_'), U('coin/index/index'));
    }


} 