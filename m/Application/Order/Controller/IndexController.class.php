<?php
namespace Order\Controller;
use Think\Controller;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/28
 * Time: 9:44
 * @author lin <lt@ourstu.com>
 */
class IndexController extends Controller
{
    public function index(){
        $aId=I('get.id',0);
        $goods=D('OrderGoods')->getGoodsById($aId);
        $amount=$goods['price']*100;
        $name=$goods['name'];
        $this->assign('id',$aId);
        $this->assign('amount',$amount);
        $this->assign('name',$name);
        $this->display();
    }

    public function notify()
    {
//        require_once(APP_PATH."Order/Lib/notify.php");
//        $notify = new \PayNotifyCallBack();
//         $notify->Handle(false);

        function FromXml($xml)
        {
            libxml_disable_entity_loader(true);
            return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        }

        function ToXml($data)
        {
            $xml = "<xml>";
            foreach ($data as $key=>$val)
            {
                if (is_numeric($val)){
                    $xml.="<".$key.">".$val."</".$key.">";
                }else{
                    $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
                }
            }
            $xml.="</xml>";
            return $xml;
        }

        header('Content-type: text/xml');

        $returnResult = $GLOBALS['HTTP_RAW_POST_DATA'];

        $res = FromXml($returnResult);

        //file_put_contents('callback.php',json_encode($res));

//支付成功
        if ($res['result_code'] == 'SUCCESS') {
            $data['process']=1;
            $data['is_pay']=1;
            $data['pay_time']=time();
            if(M('order_goods')->where(array('wechat_order'=>$res['out_trade_no']))->count()){
                M('order_goods')->where(array('wechat_order'=>$res['out_trade_no']))->save($data);
                $order=M('order_goods')->where(array('wechat_order'=>$res['out_trade_no']))->find();
                $goods=M('mall_goods')->where(array('id'=>$order['goods_id']))->getField('name');
                $behavior='购买-'.$goods;
                $amount='-'.$order['amount'];
                $uid=$order['uid'];
                expense_alendar($behavior,$amount,$uid);
            }else if(M('order_recharge')->where(array('wechat_order'=>$res['out_trade_no']))->count()){
                M('order_recharge')->where(array('wechat_order'=>$res['out_trade_no']))->save($data);
                $recharge=M('order_recharge')->where(array('wechat_order'=>$res['out_trade_no']))->find();
                M('member')->where(array('uid'=>$recharge['uid']))->setInc('score4',$recharge['amount']);
                $behavior='充值+'.$recharge['amount'];
                $amount='+'.$recharge['amount'];
                expense_alendar($behavior,$amount,$recharge['uid']);
                action_log('recharge_order', 'Order', $recharge['id'], $recharge['uid']);
            }
            $sucess = array('return_code' => 'SUCCESS', 'return_msg' => 'OK');
            exit(ToXml($sucess));
        } else{
            // todo 返回错误信息记录表
        }
    }

    public function deposit(){
        $aId=I('get.id',0);
        $order=D('Recharge')->getRechargeOrder($aId);
        $amount=$order['amount']*100;
        $this->assign('id',$aId);
        $this->assign('amount',$amount);
        $this->display();
    }

    public function orderGoods(){
        $this->setTitle('商品订单');
        $aId=I('get.id',0);
        $idAmount=count(explode(',',D('OrderGoods')->getGoodsInfo($aId)));
        $this->assign('idAmount',$idAmount);
        $goods=D('OrderGoods')->getGoodsById($aId);
        if(empty($goods['banner'])){
            $goods['banner']= $goods['picture'];
        }
        $this->assign('goods',$goods);
        $order=D('OrderGoods')->getGoodsOrder($aId);
        if($order['uid']!=is_login()||$order['id']!=$aId){
            $this->error('订单不存在','',3);
        }
        $score=get_pay_field();
        foreach ($score as &$v){
            $v['UNIT']=$goods['price']*$v['UNIT'];
        }
        unset($v);
        $this->assign('score',$score);
        $this->assign('order',$order);
        $this->display();
    }

    public function addOrder()
    {
        if (IS_POST) {
            if (!is_login()) {
                $res['status']=-1;
                $res['info']='请登录后操作';
                $this->ajaxReturn($res,"JSON");
            }
            $gId = I('post.goods_id', '', 'intval');
            $goods = M('mall_goods')->where(array('id' => $gId))->find();
            $data['goods_id'] = $gId;
            $data['goods_type'] = $goods['cate'];
            $data['uid'] = get_uid();
            $data['amount'] = $goods['price'];
            $data['create_time'] = time();
            $data['status']=1;
            $data['method'] = 'wechat';
            $res = D('OrderGoods')->createOrder($data);
            if ($res) {
                $this->success('下单成功！',U('Order/Index/ordergoods',array('id' => $res)));
            }
        }
    }

    public function carOrder()
    {
        if (IS_POST) {
            if (!is_login()) {
                $res['status']=-1;
                $res['info']='请登录后操作';
                $this->ajaxReturn($res,"JSON");
            }
            $gId = $_POST['goods_id'];
            $map['id']=array('in',$gId);
            $goods = M('mall_goods')->where($map)->select();
            $price=0;
            foreach ($goods as $val){
                $price=$price+$val['price'];
            }
            unset($val);
            foreach ($goods as $key=>$vo){
                $data['goods_id'][$key]= $gId[$key];
                $data['goods_type'] = $goods[$key]['cate'];
                $data['uid'] = get_uid();
                $data['amount'] = $price;
                $data['create_time'] = time();
                $data['status']=1;
                $data['method'] = 'wechat';
            }
            unset($vo);
            $data['goods_id']=implode(',', $data['goods_id']);
            $res = D('OrderGoods')->createOrder($data);
            if ($res) {
                $this->success('下单成功！',U('Order/Index/ordergoods',array('id' => $res)));
            }
        }
    }

    public function getOwn(){
        $method=I('post.method','','string');
        $aId=I('post.order_id',0);
        M('order_goods')->where(array('id'=>$aId))->setField('method',$method);
        if($method=='wechat'){
            $this->ajaxReturn($method,"JSON");
        }
        $result['own']=M('member')->where(array('uid' => is_login()))->getField('score' . $method);
        $result['own'] = number_format($result['own'], 2, ".", "");
        $result['str']=M('ucenter_score_type')->where(array('id'=>$method))->getField('unit');
        $result=$result['own'] . $result['str'];
        $this->ajaxReturn($result,"JSON");
    }

    public function payOrder(){
        if (!is_login()) {
            $res['status']=-1;
            $res['info']='请登录后操作';
            $this->ajaxReturn($res,"JSON");
        }
        $oId = I('post.order_id',0);
        $method=I('post.method','','intval');
        $data['field']=$method;
        $data['process']=1;
        $data['is_pay']=1;
        $data['pay_time']=time();
        $order=D('OrderGoods')->getGoodsOrder($oId);
        $goods=D('OrderGoods')->getGoodsById($oId);
        if($order['method']==''){
            $res['status']=-1;
            $res['info']='请选择付款方式';
            $this->ajaxReturn($res,"JSON");
        }
        if($order['is_pay']==1){
            $res['status']=-1;
            $res['info']='该订单已付款';
            $this->ajaxReturn($res,"JSON");
        }
        $own=M('member')->where(array('uid' => is_login()))->getField('score' . $method);
        $type = get_pay_type($method);
        if(!$type){
            $res['status']=-1;
            $res['info']='该付款类型不存在';
            $this->ajaxReturn($res,"JSON");
        }
        $all=$order['amount']*$type['UNIT'];
        if($own<$all){
            $res['status']=-1;
            $res['info']='该充值啦';
            $this->ajaxReturn($res,"JSON");
        }
        $res=M('member')->where(array('uid' => is_login()))->setDec('score' . $method,$all);
        if($res){
            M('order_goods')->where(array('id'=>$oId))->save($data);
            action_log('pay_order', 'Order', $oId, is_login());
            $behavior='购买-'.$goods['name'];
            $amount='-'.$order['amount'];
            expense_alendar($behavior,$amount);
            D('Mall/GoodsCar')->clearCar();
            $this->success('付款成功',U('Order/index/completion'));
        }
    }

    public function completion(){
        $this->setTitle('付款成功');
        $this->display();
    }

    public function recharge(){
        if(IS_POST){
            $this->createRecharge();
        }else{
            $aId=I('get.id',0);
            $recharge_order=D('Recharge')->getRechargeOrder($aId);
            $this->assign('order',$recharge_order);
            $this->display();
        }
    }

    private function createRecharge(){
        if(!is_login()){
            $res['status']=-1;
            $res['info']='请登录后操作！';
            $this->ajaxReturn($res,"JSON");
        }
        $amount=I('post.amount',0,'floatval');
        $amount = number_format($amount, 2, ".", "");
        $minAmount=modC('RECHARGE_MIN_AMOUNT',0,'order');
        if($amount<=0){
            $res['status']=-1;
            $res['info']='请输入正确的数额！';
            $this->ajaxReturn($res,"JSON");
        }
        if($amount<$minAmount){
            $res['status']=-1;
            $res['info']='最小充值数额为' . $minAmount;
            $this->ajaxReturn($res,"JSON");
        }
        $data['amount']=$amount;
        $data['field']=4;
        $data['method']='wechat';
        $data['uid']=is_login();
        $data['create_time']=time();
        $data['status']=1;
        $res=D('Recharge')->rechargeOrder($data);
        if ($res) {
            $this->success('请查看充值订单，确认充值',U('Order/Index/recharge', array('id' => $res)));
        }

    }

    public function withdraw(){
        if(IS_POST){
            $this->createWithdraw();
        }else{
            $aId=I('get.id',0,'intval');
            $withdraw=D('Withdraw')->getWithdrawOrder($aId);
            $this->assign('draw',$withdraw);
            $this->display();
        }
    }

    private function createWithdraw(){
        if(!is_login()){
            $res['status']=-1;
            $res['info']='请登录后操作！';
            $this->ajaxReturn($res,"JSON");
        }
        $draw_amount=I('post.draw_amount',0,'floatval');
        $amount = number_format($draw_amount, 2, ".", "");
        $minAmount=modC('WITHDRAW_MIN_AMOUNT',0,'order');
        $field=I('post.field',0,'intval');
        if($field==''){
            $res['status']=-1;
            $res['info']='请选择提现类型';
            $this->ajaxReturn($res,"JSON");
        }
        if($amount<=0){
            $res['status']=-1;
            $res['info']='请输入正确的数额！';
            $this->ajaxReturn($res,"JSON");
        }
        if($amount<$minAmount){
            $res['status']=-1;
            $res['info']='最小提现数额为￥' . $minAmount;
            $this->ajaxReturn($res,"JSON");
        }
        $type=get_wi_type($field);
        if(!$type){
            $res['status']=-1;
            $res['info']='该付款类型不存在';
            $this->ajaxReturn($res,"JSON");
        }
        $score=M('member')->where(array('uid' => is_login()))->getField('score' . $field);
        $freeze_count = $type['UNIT'] * $amount;
        if($score<$freeze_count){
            $res['status']=-1;
            $res['info']='超出可提现数额！';
            $this->ajaxReturn($res,"JSON");
        }
        $data['field']=$field;
        $data['amount']=$amount;
        $data['method']='wechat';
        $data['uid']=is_login();
        $data['create_time']=time();
        $data['status']=1;
        $data['freeze_amount']=$freeze_count;
        $res=D('Withdraw')->withdrawOrder($data);
        if($res){
            M('member')->where(array('uid' => is_login()))->setDec('score' . $field,$freeze_count);
            $this->success('请查看提现详情',U('Order/Index/withdraw', array('id' => $res)));
        }
    }
}