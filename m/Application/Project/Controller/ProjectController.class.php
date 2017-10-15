<?php
/**
 * Created by PhpStorm.
 * User: 王杰
 * Date: 2017/6/2
 * Time: 10:22
 */

namespace Admin\Controller;

use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminConfigBuilder;

class ProjectController extends AdminController
{
    public function index()
    {
        $map = array();
        $status = I('get.status','3','intval');
        if ($status == 3) {
            //筛选全部
            $map['status'] = array('egt',-1);
        } else {
            $map['status'] = $status;
        }
        $list = D('Project/Project')->getList(array('field'=>'id','where'=>$map,'order'=>'create_time desc'));
        foreach ($list as &$v) {
            $v = D('Project/Project')->getProject($v);
        }
        unset($v);
        $totalCount = D('Project/Project')->where($map)->count();
        $r = 20;
        $attr['class'] = 'btn ajax-post';
        $attr['target-form'] = 'ids';
        $builder = new AdminListBuilder();
        $builder
            ->title('项目')
            ->buttonNew(U('Project/editProject'))
            ->select('', 'status', 'select', '', '', '', array(array('id' => '3', 'value' => '全部'), array('id' => '2', 'value' => '待审核'),array('id' => '-1', 'value' => '已删除'),array('id' => '0', 'value' => '已禁用')))
            ->setStatusUrl(U('setProjectStatus'))->buttonEnable()->buttonDelete()
            ->keyId()->keyText('uid','客户uid')->keyLink('title', '项目名称', 'admin/Project/progress/project_id/###')
            ->keyLink('进度文档','进度文档','admin/Project/file/project_id/###')
            ->keyImage('cover','项目封面')
            ->keyCreateTime()->keyStatus()
            ->keyDoAction('editProject?id=###','编辑')
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }

    public function editProject()
    {
        if (IS_POST) {
            $aId = I('post.id', 0, 'intval');
            $aUid = I('post.uid',0,'intval');
            $aTitle = I('post.title', '', 'text');
            $aCycle = I('post.cycle',1,'intval');
            $aFile = I('post.file','','text');
            $aCover = I('post.cover','','intval');
            $aCreateTime = I('post.create_time', 0, 'intval');
            $aStatus = I('post.status', 0, 'intval');
            $aPrivate = I('post.is_private', 0, 'intval');
            if(!$aTitle){
                $this->error("请输入项目名！");
            }
            // $isEdit = $aId ? true : false;
            if (!$aUid) {
                $this->error('请选择客户uid');
            }
            if(!$aCycle){
                $this->error("请输入项目周期！");
            }
            if(!$aCover){
                $this->error("请选择项目封面！");
            }
            //生成数据
            $data = array('uid'=>$aUid,'cycle'=>$aCycle,'title' => $aTitle,'is_private'=>$aPrivate, 'create_time' => $aCreateTime, 'status' => $aStatus,'cover'=>$aCover,'file'=>$aFile);
            //写入数据库
            $model = D('Project/Project');
            $data = $model->create($data);
            if ($aId) {
                S('project_by_'.$aId,null);
                $model->editProject($aId,$data);
            } else {
                $result = $model->addProject($data);
                if (!$result) {
                    $this->error(L('_ERROR_CREATE_FAIL_'));
                }
            }
            $string=$aId ? "编辑" :"新增";
            send_message_without_check_self($aUid, '项目初始化', get_nickname(is_login()) . $string.$aTitle."项目", 'Project/Index/index', array('status' => 2), is_login(),'Project_project');
            //返回成功信息
            $this->success($aId ? L('_SUCCESS_EDIT_') : L('_SUCCESS_SAVE_'));
        } else {
            $aId = I('get.id', 0, 'intval');
            //判断是否为编辑模式
           // $isEdit = $aId ? true : false;
            //如果是编辑模式，读取群组的属性
            if ($aId) {
                $project = D('Project/Project')->getProject($aId);
            } else {
                $project = array('create_time' => time(), 'status' => 1);
            }
            //显示页面
            $builder = new AdminConfigBuilder();
            $builder
                ->title($aId ? '编辑项目':'新增项目' )
                ->keyId()->keyText('title','项目名称')
                ->keyBool('is_private','是否为私人定制项目')
                ->keyText('uid','客户uid','创建公共项目时客户uid填1')
                ->keyText('cycle','预计周期(单位:周)')
                ->keySingleImage('cover','项目封面')
                ->keyStatus()
                ->keyCreateTime()
                ->data($project)
                ->buttonSubmit(U('editProject'))->buttonBack()
                ->display();
        }
    }

    public function setProjectStatus($ids, $status)
    {
        $id = array_unique((array)$ids);
        $rs = D('Project/Project')->setStatus($id,$status);
        if ($rs === false) {
            $this->error(L('_ERROR_SETTING_') . L('_PERIOD_'));
        }
        foreach ($id as $v) {
            S('project_by_'.$v,null);
        }
        $this->success(L('_SUCCESS_SETTING_'), $_SERVER['HTTP_REFERER']);
    }

    public function progress()
    {
        $aId = I('get.project_id',0,'intval');
        $project = D('Project/Project')->getProject($aId);
        if (empty($aId) || empty($project)) {
            $this->error('该项目不存在');
        }

        $map = array();
        $status = I('get.status','3','intval');
        if ($status == 3) {
            //筛选全部
            $map['status'] = array('egt',-1);
        } else {
            $map['status'] = $status;
        }
        $list = D('Project/Progress')->getList(array('field'=>'id','where'=>array('project_id'=>$aId),'order'=>'create_time desc'));
        foreach ($list as &$v) {
            $v = D('Project/Progress')->getProgress($v);
        }
        unset($v);
        $totalCount = D('Project/Progress')->where($map)->count();
        $r = 20;
        $attr['class'] = 'btn ajax-post';
        $attr['target-form'] = 'ids';
        $builder = new AdminListBuilder();
        $builder
            ->title('项目进度')
            ->buttonNew(U('Project/editProgress',array('project_id'=>$aId)))
            ->select('', 'status', 'select', '', '', '', array(array('id' => '3', 'value' => '全部'), array('id' => '2', 'value' => '待审核'),array('id' => '-1', 'value' => '已删除'),array('id' => '0', 'value' => '已禁用')))
            ->setStatusUrl(U('setProgressStatus'))->buttonEnable()->buttonDelete()
            ->keyLink('title', L('_TITLE_'), 'admin/Project/editProgress/id/###')
            ->keyCreateTime()->keyStatus()
            ->keyDoAction('admin/project/editProgress/id/###/project_id/'.$aId,'编辑')
            ->data($list)
            ->pagination($totalCount,$r)
            ->display();
    }
    public function setProgressStatus($ids, $status)
    {
        $id = array_unique((array)$ids);
        $rs = D('Project/Progress')->setStatus($id,$status);
        if ($rs === false) {
            $this->error(L('_ERROR_SETTING_') . L('_PERIOD_'));
        }
        foreach ($id as $v) {
            S('Progress_by_'.$v,null);
        }
        $this->success(L('_SUCCESS_SETTING_'), $_SERVER['HTTP_REFERER']);
    }

    public function editProgress()
    {
        $aProjectId = I('get.project_id',0,'intval');
        if (IS_POST) {
            $aId = I('post.id', 0, 'intval');
            if (empty($aProjectId)) {
                $this->error('该项目不存在');
            }
            $aTitle = I('post.title', '', 'text');
            $aCreateTime = I('post.create_time', 0, 'intval');
            $aStatus = I('post.status', 0, 'intval');
            //$aFile = I('post.file',0,'intval');
            $aContent=I('post.content','','text');

            $isEdit = $aId ? true : false;

            //生成数据
            $data = array('title' => $aTitle, 'content'=>$aContent,'create_time' => $aCreateTime, 'status' => $aStatus,'project_id'=>$aProjectId);
            //写入数据库
            $model = D('Project/Progress');
            $project = D('Project/Project')->getProject($aProjectId);
            if ($isEdit) {
                $data = $model->create($data);
                $result = $model->editProgress($aId,$data);
                send_message_without_check_self($project['uid'], '项目进度', get_nickname(is_login()) . "修改了".$aTitle, 'Project/Index/index', array('status' => 2), is_login(),'Project_project');
                S('progress_by_'.$aId,null);

            } else {
                $data = $model->create($data);
                $result = $model->addProgress($data);
                //进度报告添加后 更新进度
                $project['progress']=round(($model->getCount($aProjectId))/$project['cycle'],1)*70;
                D('Project/Project')->where(array('id'=>$aProjectId))->setInc('progress',$project['progress']);

                if (!$result) {
                    $this->error(L('_ERROR_CREATE_FAIL_'));
                }

                send_message_without_check_self($project['uid'], '项目进度', get_nickname(is_login()) . "新增了".$aTitle, 'Project/Index/index', array('status' => 2), is_login(),'Project_project');
            }
            //返回成功信息
            S('project_by_'.$aProjectId,null);
            $this->success($isEdit ? L('_SUCCESS_EDIT_') : L('_SUCCESS_SAVE_'));
        } else {
            $aId = I('get.id', 0, 'intval');
            //判断是否为编辑模式
            $isEdit = $aId ? true : false;
            //如果是编辑模式，读取群组的属性
            if ($isEdit) {
                $project = D('Project/Progress')->getProgress($aId);
            } else {
                $aProjectId = I('get.project_id',0,'intval');
                if (empty($aProjectId)) {
                    $this->error('该项目不存在');
                }
                $project = array('create_time' => time(), 'status' => 1,'project_id'=>$aProjectId);
            }
            //显示页面
            $builder = new AdminConfigBuilder();
            $builder
                ->title($isEdit ? '编辑项目进度':'新增项目进度' )
                ->keyId()->keyText('title','项目进度描述')
                //->keyMultiFile('file','相关文档')
                ->keyEditor('content','文档内容')
                ->keyStatus()
                ->keyCreateTime()
                ->data($project)
                ->buttonSubmit(U('editProgress',array('project_id'=>$aProjectId)))->buttonBack()
                ->display();
        }
    }

    public function file(){

        $aId = I('get.project_id',0,'intval');
        $project = D('Project/Project')->getProject($aId);
        if (empty($aId) || empty($project)) {
            $this->error('该项目不存在');
        }
        $map = array();
        $status = I('get.status','3','intval');
        $map['project_id']=$aId;
        if ($status == 3) {
            //筛选全部
            $map['status'] = array('egt',-1);
        } else {
            $map['status'] = $status;
        }
        $list = D('Project/File')->getList(array('field'=>'id','where'=>$map,'order'=>'create_time desc'));

        foreach ($list as &$v) {
            $v = D('Project/File')->getFile($v);
        }
        unset($v);
       // dump($list);
        $totalCount = D('Project/File')->where($map)->count();
        $r = 20;
        $attr['class'] = 'btn ajax-post';
        $attr['target-form'] = 'ids';
        $builder = new AdminListBuilder();
        $builder
            ->title('文档列表')
            ->buttonNew(U('Project/editFile',array('project_id'=>$aId)))
            ->select('', 'status', 'select', '', '', '', array(array('id' => '3', 'value' => '全部'), array('id' => '2', 'value' => '待审核'),array('id' => '-1', 'value' => '已删除'),array('id' => '0', 'value' => '已禁用')))
            ->setStatusUrl(U('setFileStatus'))->buttonEnable()->buttonDelete()
            ->keyId()->keyText('name','发布人')->keyText('title','标题')
            ->keyCreateTime()->keyUpdateTime()->keyStatus()
            ->keyDoAction('admin/project/editFile/id/###/project_id/'.$aId,'编辑')
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }

    public function setFileStatus($ids, $status){
        $id = array_unique((array)$ids);
        $rs = D('Project/File')->setStatus($id,$status);
        if ($rs === false) {
            $this->error(L('_ERROR_SETTING_') . L('_PERIOD_'));
        }
        foreach ($id as $v) {
            S('file_by'.$v,null);
        }
        $this->success(L('_SUCCESS_SETTING_'), $_SERVER['HTTP_REFERER']);
    }


    public function editFile(){
        $aProjectId = I('get.project_id',0,'intval');
        if (IS_POST) {
            $aId = I('post.id', 0, 'intval');
            if (empty($aProjectId)) {
                $this->error('该项目不存在');
            }
            $aTitle = I('post.title', '', 'text');
            $aName = I('post.name', '', 'text');
            $aCreateTime = I('post.create_time', 0, 'intval');
            $aUpdateTime =I('post.update_time',0,'intval');
            $aStatus = I('post.status', 0, 'intval');
            $aFile = I('post.file','','text');

            $isEdit = $aId ? true : false;

            //生成数据
            $data = array('title' => $aTitle, 'file'=>$aFile,'name'=>$aName,'create_time' => $aCreateTime,'update_time'=>$aUpdateTime, 'status' => $aStatus,'project_id'=>$aProjectId);
            //写入数据库
            $model=D('Project/File');
            if ($isEdit) {
                $data = $model->create($data);
                $result = $model->editFile($aId,$data);
                S('file_by'.$aId,null);
            } else {
                $data = $model->create($data);
                $result = $model->addFile($data);
                if (!$result) {
                    $this->error(L('_ERROR_CREATE_FAIL_'));
                }
                S('file_by'.$aId,null);
            }
            //返回成功信息
            $this->success($isEdit ? L('_SUCCESS_EDIT_') : L('_SUCCESS_SAVE_'));
        }else{
            $aId = I('get.id', 0, 'intval');
            //判断是否为编辑模式
            $isEdit = $aId ? true : false;
            //如果是编辑模式，读取群组的属性
            if ($isEdit) {
                $file = D('Project/File')->getFile($aId);
                $file['update_time']=time();
            } else {
                $aProjectId = I('get.project_id',0,'intval');
                if (empty($aProjectId)) {
                    $this->error('该项目不存在');
                }
                $file = array('create_time' => time(),'update_time'=>time(),'status' => 1,'project_id'=>$aProjectId);
            }
            //显示页面
            $builder = new AdminConfigBuilder();
            $builder
                ->title($isEdit ? '编辑文档':'新增文档' )
                ->keyId()->keyText('title','文档标题')
                ->keyText('name','发布人')
                ->keyMultiFile('file','相关文档')
                ->keyStatus()
                ->keyCreateTime()
                ->keyUpdateTime()
                ->data($file)
                ->buttonSubmit(U('editFile',array('project_id'=>$aProjectId)))->buttonBack()
                ->display();
        }
    }

    /**
     * 广告信息
     * @auth qhy qhy@ourstu.com
     */
    public function advertisement($page = 1, $r = 10){
        $map['status']=array('egt',0);
        $count=D('project_advertisement')->where($map)->count();
        $advertisement = D('project_advertisement')->where($map)->order('create_time desc')->page($page, $r)->select();
        foreach ($advertisement as &$val){
            $val['end_time'] = time_format($val['end_time']);
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
            ->keyText('link', '广告链接')
            ->keyStatus('status', '状态')
            ->keyCreateTime('create_time', '创建时间')
            ->keyText('end_time', '过期时间')
            ->keyDoActionEdit('Project/addAdvertisement?id=###', $text = '编辑');
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
            $data['link']=I('post.link', '', 'string');
            $data['imgid']=I('post.imgid', '', 'string');
            $data['end_time']=I('post.end_time', '', 'intval');
            if($data['name']==null){
                $this->error('广告名不能为空');
            }
            if($data['link']==null){
                $this->error('链接不能为空');
            }
            if($data['imgid']==null){
                $this->error('图片不能为空');
            }
            if ($isEdit) {
                $data['status'] = 1;
                $rs = D('project_advertisement')->where(array('id' => $id))->save($data);
                if ($rs) {
                    $this->success('保存成功');
                } else {
                    $this->error('保存失败');
                }
            } else {
                $data['create_time'] = time();
                $data['status'] = 1;
                $rs = D('project_advertisement')->add($data);
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
            $data = D('project_advertisement')->where(array('id' => $id))->find();
            $builder->keyReadOnly('id', 'id')
                ->keyText('name', '广告名')
                ->keyText('link', '广告链接','点击图片所链接过去的地址，请完整填写，不然会出错，如：https://www.baidu.com/')
                ->keySingleImage('imgid','请选择广告图片')
                ->keyTime('end_time',L('_PERIOD_TO_'))->keyDefault('end_time',2147483640)
                ->buttonSubmit('', '保存');
            $builder->data($data);
            $builder->display();
        } else {
            $builder->keyText('name', '广告名')
                ->keyText('link', '广告链接','点击图片所链接过去的地址，请完整填写，不然会出错，如：https://www.baidu.com/')
                ->keySingleImage('imgid','请选择广告图片')
                ->keyTime('end_time',L('_PERIOD_TO_'))->keyDefault('end_time',2147483640)
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
        $builder->doSetStatus('project_advertisement', $ids, $status);
    }
}