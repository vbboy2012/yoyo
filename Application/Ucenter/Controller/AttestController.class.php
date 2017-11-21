<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2016/11/4
 * Time: 14:55
 * @author:zzl(郑钟良) zzl@ourstu.com
 */

namespace Ucenter\Controller;


class AttestController extends BaseController
{
    protected $attestModel,$attestTypeModel;

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->attestModel=D('Ucenter/Attest');
        $this->attestTypeModel=D('Ucenter/AttestType');
    }

    public function index()
    {
        $attestType=$this->attestTypeModel->getTypeList();
        $this->assign('attest_type',$attestType);
        $this->_checkAttestStatus();

//        $celebrity=$this->attestModel->getListLimit();
//        $this->assign('celebrity',$celebrity);
        //dump($celebrity);exit;

        $this->display();
    }
    public function individual()
    {
        $this->_checkAttestConditions();
        $this->_checkAttestStatus();

        $this->display();
    }

    private function _checkAttestConditions($attest_old=null)
    {
        if($attest_old){
            $attestType=$this->_checkTypeExist($attest_old['attest_type_id']);
        }else{
            $aId=I('get.id',0,'intval');
            $attestType=$this->_checkTypeExist($aId);
        }
        $this->assign('attest_type',$attestType);

        //检测申请条件 start
        $can_apply=1;
        if($attestType['conditions']['avatar']==1){
            $avatar_user=query_user(array('avatar128'));
            $this->assign('user_avatar',$avatar_user['avatar128']);

            $map['uid']=is_login();
            $map['status']=1;
            $avatar=M('Avatar')->where($map)->find();
            if($avatar){
                $this->assign('avatar_ok',1);
            }else{
                $can_apply=0;
            }
        }
        if($attestType['conditions']['phone']==1){
            $mobile=query_user(array('mobile'));
            if($mobile['mobile']!=''){
                $this->assign('phone_ok',1);
            }else{
                $can_apply=0;
            }
        }
        $followModel=D('Follow');
        if($attestType['conditions']['follow']>0){
            $map_follow['who_follow']=is_login();
            $map_follow['follow_who']=array('neq','');
            $follow_count=$followModel->where($map_follow)->count();
            if($follow_count>$attestType['conditions']['follow']){
                $this->assign('follow_ok',1);
            }else{
                $can_apply=0;
            }
        }
        if($attestType['conditions']['fans']>0){
            $map_fans['follow_who']=is_login();
            $map_fans['who_follow']=array('neq','');
            $fans_count=$followModel->where($map_fans)->count();
            if($fans_count>$attestType['conditions']['fans']){
                $this->assign('fans_ok',1);
            }else{
                $can_apply=0;
            }
        }
        if($attestType['conditions']['friends']>0){
            $friendUids=$followModel->getMyFriends();
            $map_friend['uid']=array('in',$friendUids);
            $map_friend['status']=1;
            $friends_count=$this->attestModel->where($map_friend)->count();
            if($friends_count>$attestType['conditions']['fans']){
                $this->assign('friends_ok',1);
            }else{
                $can_apply=0;
            }
        }
        $this->assign('can_apply',$can_apply);
        //检测申请条件 end
        return $can_apply;
    }

    public function apply()
    {
        $attest_old=$this->_checkAttestStatus(1);

     //   $this->checkAuth('Ucenter/Attest/apply',-1,'你没有申请权限');
        if(IS_POST){
            $attest=$this->attestModel->create();
            //检测认证资料 start
            $res=$this->_checkAttestConditions($attest);
            if(!$res){
                $this->error('未满足认证申请条件！');
            }
            $attest_type=$this->_checkTypeExist($attest['attest_type_id']);
            $attest_type['fields']['child_type_option']=explode(',',str_replace('，',',',$attest_type['fields']['child_type_option']));
            $attest_type['fields']['image_type_option']=explode(',',str_replace('，',',',$attest_type['fields']['image_type_option']));
            if(!in_array($attest['child_type'],$attest_type['fields']['child_type_option'])){
                $this->error('非法操作！');
            }
            if($attest_type['fields']['company_name']!=0){
                if($attest_type['fields']['company_name']==1&&strlen($attest['company_name'])==0){
                    $this->error('企业、组织名称不能为空！');
                }
                if(strlen($attest['company_name'])<2||strlen($attest['company_name'])>100){
                    $this->error('名称长度应该在2到100之间');
                }
            }
            if($attest_type['fields']['name']!=0){
                if($attest_type['fields']['name']==1&&strlen($attest['name'])==0){
                    $this->error('真实姓名不能为空！');
                }
                if(preg_match('/^[\x4e00-\x9fa5]{2,8}$/',$attest['name'])===false){
                    $this->error('请填写真实姓名');
                }
            }
            if($attest_type['fields']['name']!=0){
                if($attest_type['fields']['id_num']==1&&strlen($attest['id_num'])==0){
                    $this->error('身份证号不能为空！');
                }
                if(preg_match('/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/',$attest['id_num'])===false&&preg_match('/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/',$attest['id_num'])===false){
                    $this->error('请填写正确身份证号码');
                }
            }
            if($attest_type['fields']['phone']!=0){
                if($attest_type['fields']['phone']==1&&strlen($attest['phone'])==0){
                    $this->error('联系方式不能为空！');
                }
                if(preg_match('/^(1[3|4|5|7|8])[0-9]{9}$/',$attest['phone'])===false&&preg_match('/^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/',$attest['phone'])===false){
                    $this->error('请填写正确联系方式');
                }
            }
            if(!in_array($attest['image_type'],$attest_type['fields']['image_type_option'])){
                $this->error('非法操作！');
            }
            if($attest_type['fields']['prove_image']==1&&!count($attest['prove_image'])){
                $this->error('请上传组织或企业证件的高清照片！');
            }
            if($attest_type['fields']['image']==1&&!count($attest['image'])){
                $this->error('请上传证件正反面的高清照片！');
            }
            if($attest_type['fields']['other_image']==1&&!count($attest['other_image'])){
                $this->error('请上传其他证明材料的高清照片！');
            }
            if($attest_type['fields']['info']!=0){
                if($attest_type['fields']['info']==1&&strlen($attest['info'])==0){
                    $this->error('认证补充不能为空！');
                }
            }
            //检测认证资料 end

            $attest['prove_image']&&$attest['prove_image']=implode(',',$attest['prove_image']);
            $attest['image']&&$attest['image']=implode(',',$attest['image']);
            $attest['other_image']&&$attest['other_image']=implode(',',$attest['other_image']);
            $attest['uid']=is_login();
            $attest['status']=2;
            $res=$this->attestModel->editData($attest);
            if($res!==false){
                $uids=get_auth_user('Admin/Attest/setAuditStatus');
                $user=query_user(array('nickname'));
                send_message($uids,'用户申请认证','用户'.$user['nickname'].'申请'.$attest_type['title'].'，请速去审核',U('Admin/attest/attestlist',array('attest_type_id'=>$attest['attest_type_id'],'status'=>3),true,true),array('status'=>2),-1);
                $this->success('申请成功，请等待审核！',U('Ucenter/Attest/process'));
            }else{
                $this->success('申请失败，请重试！');
            }
        }else{
            $res=$this->_checkAttestConditions($attest_old);
            if(!$res){
                $this->error('未满足认证申请条件！');
            }
            if($attest_old){
                $attestType=$this->_checkTypeExist($attest_old['attest_type_id']);
            }else{
                $aId=I('get.id',0,'intval');
                $attestType=$this->_checkTypeExist($aId);
            }
            $attestType['fields']['child_type_option']=explode(',',str_replace('，',',',$attestType['fields']['child_type_option']));
            $attestType['fields']['image_type_option']=explode(',',str_replace('，',',',$attestType['fields']['image_type_option']));
            $this->assign('attest_type',$attestType);

            $this->display();
        }
    }

    /**
     * 取消认证
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function deleteApply()
    {
        $aId=I('get.id',0,'intval');
        $res=$this->attestModel->deleteApply($aId);
        if($res){
            clean_query_user_cache(is_login(),array('avatars_html','attest'));
            $this->success('取消认证成功！',U('Ucenter/Attest/index'));
        }else{
            $this->error('取消认证失败！');
        }
    }

    /**
     * 认证状态
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function process()
    {
        $attest=$this->_checkAttestStatus();
        if(!$attest){
            $aIndex=I('get.go_index',0,'intval');
            if($aIndex){
                redirect(U('Ucenter/Attest/index'));
            }else{
                $this->error('还没有进行申请认证，请先申请！');
            }
        }
        $this->assign('tab','process');
        $this->display();
    }

    private function _checkAttestStatus($redirect=0)
    {
        $map['uid']=is_login();
        $map['status']=array('in','1,2,0');
        $attest=$this->attestModel->getData($map);
        if(!$attest){
            return false;
        }
        $attest['prove_image']=explode(',',$attest['prove_image']);
        $attest['image']=explode(',',$attest['image']);
        $attest['other_image']=explode(',',$attest['other_image']);
        $attest['type']=$this->attestTypeModel->getData($attest['attest_type_id'],1);
        if($attest['status']==1){
            if($redirect){
                redirect(U('Ucenter/Attest/process'));
            }
        }
        if($attest['status']==2||$attest['status']==0){
            $aChange=I('change',0,'intval');
            if(!$aChange){
                if($redirect){
                    redirect(U('Ucenter/Attest/process'));
                }
            }else{
                $this->assign('change',1);
            }
        }
        $this->assign('attest',$attest);
        return $attest;
    }

    private function _checkTypeExist($id)
    {
        $data=$this->attestTypeModel->getData($id,1);
        if(!$data){
            $this->error('该认证类型不存在！');
        }
        return $data;
    }
}