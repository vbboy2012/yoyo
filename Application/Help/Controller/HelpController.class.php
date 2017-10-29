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

class HelpController extends AdminController{

    public function ticket()
    {
        $status = I('status',0,'intval');
        if ($status <=1){
            $map = array('status'=>$status);
        }
        $tradead = M('ticket');
        $list = $tradead->alias('a')
            ->where($map)->select();
        $builder=new AdminListBuilder();
        $builder->title('广告列表')
            ->data($list)
            ->setSelectPostUrl(U('Admin/Help/ticket'))
            ->select('','status','select','','','',array(array('id'=>3,'value'=>'全部'),array('id'=>1,'value'=>'已处理'),array('id'=>0,'value'=>'未处理')))
            ->keyId()->keyUid()->keyText('type','类型')->keyText('question_id','单号')->keyText('email','回复地址')->keyText('content','内容')
            ->keyStatus()->keyCreateTime()->keyUpdateTime();
        $builder->ajaxButton(U('Help/setStatus'),'','设为已处理')->keyDoAction('Help/setStatus?ids=###');
        $builder->pagination($totalCount,$r)
            ->display();
    }

    public function setStatus($ids)
    {
        !is_array($ids)&&$ids=explode(',',$ids);
        !is_array($ids) && $ids = explode(',', $ids);
        $map['id'] = array('in', $ids);
        $res = M('ticket')->where($map)->setField(array('status'=>1,'update_time'=>time()));
        if($res){
            $this->success(L('_SUCCESS_TIP_'),U('Help/ticket'));
        }else{
            $this->error(L('_OPERATE_FAIL_'));
        }
    }
 
} 