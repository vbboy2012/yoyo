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
        if (!is_login()) {
            $this->redirect('ucenter/member/login');
        }
        if (isset($_GET['id'])){
            $isNew = 0;
            $id = I('get.id');
            $uid = get_uid();
            $ad = M('tradead')->where('id='.$id.' and uid='.$uid)->find();
            if ($ad){
                $openTime = json_decode($ad['open_time']);
                $this->assign('id',$id);
                $this->assign('ad',$ad);
                $this->assign('openTime',$openTime);
            }else{
                $this->redirect('/ad/advertise');
            }
        }else{
            $isNew = 1;
        }
        $country = D('Country')->field('id,name,code')->select();
        $currency = D('Currency')->select();
        $payType = D('Pay')->select();
        $time = array(
            array('time' => '00:00'), array('time' => '01:00'), array('time' => '02:00'),
            array('time' => '03:00'), array('time' => '04:00'), array('time' => '05:00'),
            array('time' => '06:00'), array('time' => '07:00'), array('time' => '08:00'),
            array('time' => '09:00'), array('time' => '10:00'), array('time' => '11:00'),
            array('time' => '12:00'), array('time' => '13:00'), array('time' => '14:00'),
            array('time' => '15:00'), array('time' => '16:00'), array('time' => '17:00'),
            array('time' => '18:00'), array('time' => '19:00'), array('time' => '20:00'),
            array('time' => '21:00'), array('time' => '22:00'), array('time' => '23:00'),
            array('time' => '24:00'),
        );
        $ratePrice = 24000;
        $this->assign('country', $country);
        $this->assign('currency', $currency);
        $this->assign('payType', $payType);
        $this->assign('time', $time);
        $this->assign('ratePrice', $ratePrice);
        $this->assign('isNew', $isNew);
        $this->display();
    }

    public function doPost($id=0,$coin_type = 0,$type = 0,$country=0,$currency='',$pre_price=0,$price=0,$pay_time=0,$pay_addr='',$low_price=0,$min_price=0,$max_price=0,$pay_type=0,$pay_text='',$auto_message='',$is_safe=0,$is_trust=0,$start_time1=0,$start_time2=0,$start_time3=0,$start_time4=0,$start_time5=0,$start_time6=0,$start_time7=0,$end_time1=0,$end_time2=0,$end_time3=0,$end_time4=0,$end_time5=0,$end_time6=0,$end_time7=0)
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
        $content['pay_text'] = $pay_text;
        $content['auto_message'] = $auto_message;
        $content['is_safe'] = $is_safe;
        $content['is_trust'] = $is_trust;
        $openTime = array('st7'=>$start_time7,'st6'=>$start_time6,
            'st1'=>$start_time1,'st2'=>$start_time2,
            'st3'=>$start_time3,'st4'=>$start_time4,
            'st5'=>$start_time5,'et7'=>$end_time7,
            'et6'=>$end_time6,'et1'=>$end_time1,
            'et2'=>$end_time2,'et3'=>$end_time3,
            'et4'=>$end_time4,'et5'=>$end_time5,
            );
        $content['open_time'] = json_encode($openTime);
        $content['status'] = 0;     //发布广告默认为下架状态
        if ($id > 0){
            D('Tradead')->where('id='.$id)->save($content);
        }else{
            $content['uid'] = is_login();
            $content['create_time'] = time();
            D('Tradead')->add($content);
        }
        $this->success(L('_SUCCESS_POST_'), U('ucenter/index/myad'));
    }


} 