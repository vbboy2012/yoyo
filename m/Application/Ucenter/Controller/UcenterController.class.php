<?php
namespace Admin\Controller;


use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminTreeListBuilder;
use Common\Model\ContentHandlerModel;

class UcenterController extends AdminController{


    /**
     * 广告信息
     * @auth qhy qhy@ourstu.com
     */
    public function advertisement($page = 1, $r = 10){
        $map['status']=array('egt',0);
        $count=D('advertisement')->where($map)->count();
        $advertisement = D('advertisement')->where($map)->order('create_time desc')->page($page, $r)->select();
        foreach ($advertisement as &$val){
            $val['create_time'] = time_format($val['create_time'], 'Y-m-d H:i');
            switch ($val['location']) {
                case "0";
                    $val['location'] = '资讯';
                    break;
                case "1";
                    $val['location'] = '广场';
                    break;
            }
        }
        unset($val);
        $builder=new AdminListBuilder();
        $builder->title('广告');
        $builder->buttonEnable(U('setAdvertisementStatus'))->setStatusUrl(U('setAdvertisementStatus'));
        $builder->buttonDisable(U('setAdvertisementStatus'))->setStatusUrl(U('setAdvertisementStatus'));
        $builder->buttonNew(U('addAdvertisement'));
        $builder->buttonDelete(U('setAdvertisementStatus'));
        $builder->keyid('id', 'id')
            ->keyText('name', '广告名')
            ->keyText('location', '广告位置')
            ->keyText('link', '广告链接')
            ->keyStatus('status', '状态')
            ->keyText('create_time', '创建时间')
            ->keyDoActionEdit('Ucenter/addAdvertisement?id=###', $text = '编辑');
        $builder->data($advertisement);
        $builder->pagination($count, $r);
        $builder->display();
    }

    /**
     * 添加广告
     * @auth qhy qhy@ourstu.com
     */
    public function addAdvertisement($id = 0){
        $isEdit = $id ? 1 : 0;
        if (IS_POST){
            $data['name']=I('post.name', '', 'string');
            $data['location']=I('post.location', '', 'string');
            $data['link']=I('post.link', '', 'string');
            $data['imgid']=I('post.imgid', '', 'string');
            if($data['name']==null){
                $this->error('广告名不能为空');
            }
            if($data['location']==null){
                $this->error('广告位置不能为空');
            }
            if($data['link']==null){
                $this->error('链接不能为空');
            }
            if($data['imgid']==null){
                $this->error('图片不能为空');
            }
            if ($isEdit) {
                $data['status'] = 1;
                $rs = D('advertisement')->where(array('id' => $id))->save($data);
                S('ucenter_square_advertisement',false);
                S('news_index_advertisement',false);
                if ($rs) {
                    $this->success('保存成功');
                } else {
                    $this->error('保存失败');
                }
            } else {
                $data['create_time'] = time();
                $data['status'] = 1;
                $rs = D('advertisement')->add($data);
                S('ucenter_square_advertisement',false);
                S('news_index_advertisement',false);
                if ($rs) {
                    $this->success('新增成功');
                } else {
                    $this->error('新增失败');
                }
            }
        }
        $builder = new AdminConfigBuilder();
        $builder->title($isEdit ? '编辑广告' : '添加广告');
        if ($isEdit) {
            $data = D('advertisement')->where(array('id' => $id))->find();
            $builder->keyReadOnly('id', 'id')
                ->keyText('name', '广告名')
                ->keyText('location', '广告位置','现在只有0代表资讯，1代表广场，其他需要代码实现')
                ->keyText('link', '广告链接','点击图片所链接过去的地址，请完整填写，不然会出错，如：https://www.baidu.com/')
                ->keySingleImage('imgid','请选择广告图片')
                ->buttonSubmit('', '保存');
            $builder->data($data);
            $builder->display();
        } else {
            $builder->keyText('name', '广告名')
                ->keyText('location', '广告位置','现在只有0代表资讯，1代表广场，其他需要代码实现')
                ->keyText('link', '广告链接','点击图片所链接过去的地址，请完整填写，不然会出错，如：https://www.baidu.com/')
                ->keySingleImage('imgid','请选择广告图片')
                ->buttonSubmit('', '添加');
            $builder->display();
        }
    }

    /**
     * 设置广告状态 1=启用 0=禁用 -1=删除
     * @auth qhy qhy@ourstu.com
     */
    public function setAdvertisementStatus($ids, $status)
    {
        $builder = new AdminListBuilder();
        S('ucenter_square_advertisement',false);
        S('news_index_advertisement',false);
        $builder->doSetStatus('advertisement', $ids, $status);
    }

    public function setSquareShow(){
        $builder= new AdminConfigBuilder();
        if(IS_POST){
            S('newsResult',null);
            S('forumResult',null);
            S('weiboResult',null);
            S('quResult',null);
            S('userResult',null);
        }
        $data = $builder->handleConfig();
        $builder->title("广场设置")->data($data)
            ->keyAutoComplete("SET_NEWS","输入资讯id或者标题即可",'', array('url' => U('Ucenter/getNewsJson',array('model'=>'News')), 'delimiter' => ','))
            ->keyAutoComplete("SET_FORUM","输入帖子id或者标题即可",'', array('url' => U('Ucenter/getNewsJson',array('model'=>'ForumPost')), 'delimiter' => ','))
            ->keyAutoComplete("SET_QUESTION","输入问答id或者标题即可",'', array('url' => U('Ucenter/getNewsJson',array('model'=>'Question')), 'delimiter' => ','))
            ->keyAutoComplete("SET_WEIBO","输入动态id或者标题即可",'', array('url' => U('Ucenter/getNewsJson',array('model'=>'Weibo')), 'delimiter' => ','))
            ->keyAutoComplete("SET_USER_ID","输入用户id或者用户名即可",'', array('url' => U('Ucenter/getUserJson'), 'delimiter' => ','))
            ->buttonSubmit('','保存');

        
        $builder->display();
    }
    public function getNewsJson()
    {
        $model = I('get.model', '', 'string') ;
        $aQ = I('q', '', 'text');
        if(strtolower($model) != 'weibo') {
            $where['title'] = array('like', '%' . $aQ . '%');
            $where['id'] = $aQ;
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
            $field = 'id,title' ;
        }else{
            $map['id'] = $aQ ;
            $field = 'id' ;
        }

        $map['status'] = 1;

        $list = M($model)->where($map)->order('id desc')->field($field)->select();
        foreach ($list as &$v) {
            $v['name'] = $v['id'] . '[' . $v['title'] . ']';
            unset($v['title']);
        }
        unset($v);
        $this->ajaxReturn($list);
    }
    public function getUserJson()
    {

        $aQ = I('q', '', 'text');

        $where['username'] = array('like', '%' . $aQ . '%');
        $where['id'] = $aQ;
        $where['_logic'] = 'or';
        $map['_complex'] = $where;
        $map['status'] = 1;

        $list = M('ucenter_member')->where($map)->field('id,username')->select();
        foreach ($list as &$v) {
            $v['name'] = $v['id'] . '[' . $v['username'] . ']';
            unset($v['username']);
        }
        unset($v);
        $this->ajaxReturn($list);
    }
} 