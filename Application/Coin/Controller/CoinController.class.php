<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-4-27
 * Time: 上午10:21
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Admin\Controller;


use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminTreeListBuilder;

class CoinController extends AdminController{

    public function trade()
    {
        $status = I('status',0,'intval');
        if ($status > 0){
            $map = array('a.status'=>$status);
        }
        $tradead = M('trade_order');
        $list = $tradead->alias('a')->join('ocenter_tradead b on a.ad_id = b.id')
            ->field('a.id,a.ad_id,a.ad_uid,a.get_uid,a.order_id,a.create_time,a.update_time,a.coin_num,a.trade_price,a.fee,a.pay_code,a.status,b.currency')
            ->where($map)->select();
        $builder=new AdminListBuilder();
        $builder->title('交易列表')
            ->data($list)
            ->setSelectPostUrl(U('Admin/Coin/trade'))
            ->select('','status','select','','','',array(array('id'=>0,'value'=>'全部'),array('id'=>1,'value'=>'待付款'),array('id'=>2,'value'=>'已付款'),array('id'=>3,'value'=>'已完成'),array('id'=>4,'value'=>'申诉中'),array('id'=>5,'value'=>'已取消')))
            ->keyId()->keyText('ad_id','广告ID')->keyUid('ad_uid','广告主')->keyUid('get_uid','下单主')
            ->keyLink('order_id','编号','order/?id=###')->keyText('coin_num','数量')->keyText('trade_price','金额')->keyText('fee','手续费')->keyText('currency','货币')->keyText('pay_code','参考码')
            ->keyStatus()->keyCreateTime();

        $builder->pagination($totalCount,$r)
            ->display();
    }

    public function ad()
    {
        $status = I('status',0,'intval');
        if ($status > 0){
            $map = array('status'=>$status);
        }
        $tradead = M('tradead');
        $list = $tradead->alias('a')->join('ocenter_country b on a.country = b.id')
            ->field('a.id,a.uid,a.coin_type,a.type,a.country,a.currency,a.market,a.price,a.max_price,a.min_price,a.pay_type,a.status,a.create_time,b.name')
            ->where($map)->select();
        $builder=new AdminListBuilder();
        $builder->title('广告列表')
            ->data($list)
            ->setSelectPostUrl(U('Admin/Coin/ad'))
            ->select('','status','select','','','',array(array('id'=>0,'value'=>'全部'),array('id'=>1,'value'=>'激活'),array('id'=>2,'value'=>'未激活')))
            ->keyId()->keyUid()->keyText('coin_type','币种')->keyText('name','国家')->keyText('currency','货币')->keyText('market','市场源')
            ->keyText('price','价格')->keyText('min_price','最小额')->keyText('max_price','最大额')
            ->keyText('pay_type','支付方式')
            ->keyStatus()->keyCreateTime();

        $builder->pagination($totalCount,$r)
            ->display();
    }
 
} 