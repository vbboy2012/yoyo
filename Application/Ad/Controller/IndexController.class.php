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
        $defaultCountry = 45;
        $defaultCurrency = 'CNY';
        if (isset($_GET['id'])){
            $isNew = 0;
            $id = I('get.id');
            $uid = get_uid();
            $ad = M('tradead')->where('id='.$id.' and uid='.$uid)->find();
            if ($ad){
                $this->assign('id',$id);
                $this->assign('ad',$ad);
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
            array('id'=>0,'time' => '00:00'), array('id'=>1,'time' => '01:00'), array('id'=>2,'time' => '02:00'),
            array('id'=>3,'time' => '03:00'), array('id'=>4,'time' => '04:00'), array('id'=>4,'time' => '05:00'),
            array('id'=>6,'time' => '06:00'), array('id'=>7,'time' => '07:00'), array('id'=>8,'time' => '08:00'),
            array('id'=>9,'time' => '09:00'), array('id'=>10,'time' => '10:00'), array('id'=>11,'time' => '11:00'),
            array('id'=>12,'time' => '12:00'), array('id'=>13,'time' => '13:00'), array('id'=>14,'time' => '14:00'),
            array('id'=>15,'time' => '15:00'), array('id'=>16,'time' => '16:00'), array('id'=>17,'time' => '17:00'),
            array('id'=>18,'time' => '18:00'), array('id'=>19,'time' => '19:00'), array('id'=>20,'time' => '20:00'),
            array('id'=>21,'time' => '21:00'), array('id'=>22,'time' => '22:00'), array('id'=>23,'time' => '23:00'),
            array('id'=>24,'time' => '24:00'),
        );
        $ratePrice = 24000;
        $this->assign('defaultCurrency', $defaultCurrency);
        $this->assign('defaultCountry', $defaultCountry);
        $this->assign('country', $country);
        $this->assign('currency', $currency);
        $this->assign('payType', $payType);
        $this->assign('time', $time);
        $this->assign('ratePrice', $ratePrice);
        $this->assign('isNew', $isNew);
        $this->display();
    }

    public function doPost($id=0,$coin_type = 0,$type = 0,$country=0,$currency='',$pre_price=0,$price=0,$pay_time=0,$pay_addr='',$low_price=0,$min_price=0,$max_price=0,$pay_type=0,$pay_text='',$auto_message='',$is_safe=0,$is_trust=0,$start0=0,$end0=0,$start1=0,$end1=0,$start2=0,$end2=0,$start3=0,$end3=0,$start4=0,$end4=0,$start5=0,$end5=0,$start6=0,$end6=0)
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
        if (($type == 3 || $type == 4) && $pay_addr == ''){
            $this->error(L('_ERROR_ADDR_'));
        }
        $payType = '';
        foreach ($pay_type as $type){
            $payType .= $type.',';
        }
        $payType = substr($payType,0,strlen($payType)-1);
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
        $content['pay_type'] = $payType;
        $content['pay_time'] = $pay_time;
        $content['pay_addr'] = $pay_addr;
        $content['pay_text'] = $pay_text;
        $content['auto_message'] = $auto_message;
        $content['is_safe'] = $is_safe;
        $content['is_trust'] = $is_trust;
        $content['start0'] = $start0;
        $content['end0'] = $end0;
        $content['start1'] = $start1;
        $content['end1'] = $end1;
        $content['start2'] = $start2;
        $content['end2'] = $end2;
        $content['start3'] = $start3;
        $content['end3'] = $end3;
        $content['start4'] = $start4;
        $content['end4'] = $end4;
        $content['start5'] = $start5;
        $content['end5'] = $end5;
        $content['start6'] = $start6;
        $content['end6'] = $end6;
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