<?php
namespace Ucenter\Controller;

use Think\Controller;
require_once APP_PATH.'User/Common/common.php';
require_once(APP_PATH . '/User/Conf/config.php');
class IndexController extends Controller
{
    protected $attestModel,$attestTypeModel;
    public function _initialize()
    {
        $this->attestModel=D('Ucenter/Attest');
        $this->attestTypeModel=D('Ucenter/AttestType');
        $this->assign('bottom_flag','mine');
        $this->setTitle('广场');
    }

    public function index($uid=null)
    {

        $this->setTitle('我');
        $this->setKeywords("我");
        $this->setDescription("我");
        $aIsShare = I('get.is_share',0,'intval');
        if(empty($uid)){
            $uid=is_login();
        }
        if($uid!=is_login()){
            redirect(U('ucenter/index/mine',array('uid'=>$uid)));
        }
        $model=new \Core\Model\CheckInModel();
        $check = $model->getCheck($uid);
        if($check){
            $this->assign("check", true);
        }else{
            $this->assign("check", false);
        }
        $user_info = query_user(array('avatar64', 'nickname', 'uid', 'space_url','space_mob_url', 'icons_html', 'score', 'title', 'fans', 'following', 'weibocount', 'rank_link', 'signature','con_check', 'total_check'), $uid);
        $this->assign("uid", $uid);
        $this->assign('user_info', $user_info);
        $friend_list=$this->_myfriend($uid);
        $friends=count($friend_list);
        $this->assign('friends',$friends);
        $this->assign('is_share',$aIsShare);
        $this->display();

    }
    public function edit(){
        $this->setTitle('基本资料');
        $this->setKeywords("基本资料");
        $this->setDescription("基本资料");
        $aUid = I('get.uid', 0, 'intval');
        if (!$aUid) {
            redirect(U('Weibo/Index/index'));
        }
        $userData=$this->_userData($aUid);
        $userData['pos_province']=str_replace('省','',$userData['pos_province']);
        $userData['pos_province']=str_replace('区','',$userData['pos_province']);
        $userData['pos_city']=str_replace('市','',$userData['pos_city']);
        $this->assign('user',$userData);
        $this->assign('uid',$aUid);
        $this->display();
    }

    public function userdata()
    {
        $this->setTitle('基本资料');
        $this->setKeywords("基本资料");
        $this->setDescription("基本资料");
        $aUid = I('get.uid', 0, 'intval');
        if (!$aUid) {
            redirect(U('Weibo/Index/index'));
        }
        $userData=$this->_userData($aUid);
        $userData['pos_province']=str_replace('省','',$userData['pos_province']);
        $userData['pos_province']=str_replace('区','',$userData['pos_province']);
        $userData['pos_city']=str_replace('市','',$userData['pos_city']);

        $profile_group_list = $this->_profile_group_list($aUid);

        foreach ($profile_group_list as &$val) {
            $val['info_list'] = $this->_info_list111($val['id'], $aUid);
            $val['fields'] = $this->_getExpandInfo($val['id']);
        }
        unset($val);
//dump($profile_group_list);exit;
        $this->assign('profile_group_list', $profile_group_list);

        $typeCount = M('field_group')->where(array('status' => 1))->count();
        $this->assign('type_count', $typeCount);

        $this->assign('user',$userData);
        $this->assign('uid',$aUid);
        $this->display();
    }

    public function _profile_group_list($uid = null)
    {
        $profile_group_list=array();
        $fields_list=$this->getRoleFieldIds($uid);

        if($fields_list){
            $fields_group_ids=D('FieldSetting')->where(array('id'=>array('in',$fields_list),'status' => '1'))->select();
            if($fields_group_ids){
                $fields_group_ids=array_unique(array_column($fields_group_ids,'profile_group_id'));
                $map['id']=array('in',$fields_group_ids);

                if (isset($uid) && $uid != is_login()) {
                    $map['visiable'] = 1;
                }
                $map['status'] = 1;
                $profile_group_list = D('field_group')->where($map)->order('sort asc')->select();
            }
        }
        return $profile_group_list;
    }

    public function _info_list111($id = null, $uid = null)
    {

        $fields_list = $this->getRoleFieldIds($uid);
        $info_list = null;

        if (isset($uid) && $uid != is_login()) {
            //查看别人的扩展信息
            $field_setting_list = D('field_setting')->where(array('profile_group_id' => $id, 'status' => '1', 'visiable' => '1', 'id' => array('in', $fields_list)))->order('sort asc')->select();

            if (!$field_setting_list) {
                return null;
            }
            $map['uid'] = $uid;
        } else if (is_login()) {
            $field_setting_list = D('field_setting')->where(array('profile_group_id' => $id, 'status' => '1', 'id' => array('in', $fields_list)))->order('sort asc')->select();

            if (!$field_setting_list) {
                return null;
            }
            $map['uid'] = is_login();

        } else {
            $this->error(L('_ERROR_PLEASE_LOGIN_').L('_EXCLAMATION_'));
        }
        foreach ($field_setting_list as $val) {
            $map['field_id'] = $val['id'];
            $field = D('field')->where($map)->find();
            $val['field_content'] = $field;
            $info_list[$val['id']] = $val;
            unset($map['field_id']);
        }

        return $info_list;
    }

    private function getRoleFieldIds($uid=null){
        $role_id=get_role_id($uid);
        $fields_list=S('Role_Expend_Info_'.$role_id);
        if(!$fields_list){
            $map_role_config=getRoleConfigMap('expend_field',$role_id);
            $fields_list=D('RoleConfig')->where($map_role_config)->getField('value');
            if($fields_list){
                $fields_list=explode(',',$fields_list);
                S('Role_Expend_Info_'.$role_id,$fields_list,600);
            }
        }
        return $fields_list;
    }

    public function _getExpandInfo($profile_group_id = null)
    {
        $res = D('field_group')->where(array('id' => $profile_group_id, 'status' => '1'))->find();
        if (!$res) {
            return array();
        }
        $info_list = $this->_info_list($profile_group_id);

        return $info_list;
    }

    public function _info_list($id = null, $uid = null)
    {
        $fields_list=$this->getRoleFieldIds($uid);
        $info_list = null;

        if (isset($uid) && $uid != is_login()) {
            //查看别人的扩展信息
            $field_setting_list = D('field_setting')->where(array('profile_group_id' => $id, 'status' => '1', 'visiable' => '1','id'=>array('in',$fields_list)))->order('sort asc')->select();

            if (!$field_setting_list) {
                return null;
            }
            $map['uid'] = $uid;
        } else if (is_login()) {
            $field_setting_list = D('field_setting')->where(array('profile_group_id' => $id, 'status' => '1','id'=>array('in',$fields_list)))->order('sort asc')->select();

            if (!$field_setting_list) {
                return null;
            }
            $map['uid'] = is_login();

        } else {
            $this->error('请先登录！');
        }

        foreach ($field_setting_list as &$val) {
            $map_l['field_id'] = $val['id'];
            $field = D('field')->where($map_l)->find();

            $val['field_content'] = $field;
            unset($map['field_id']);
            $info_list[$val['id']] = $this->_get_field_data($val);
            //当用户扩展资料为数组方式的处理@MingYangliu
            $vlaa = explode('|', $val['form_default_value']);
            $needle =':';//判断是否包含a这个字符
            $tmparray = explode($needle,$vlaa[0]);
            if(count($tmparray)>1){
                foreach ($vlaa as $kye=>$vlaas){
                    if(count($tmparray)>1){
                        $vlab[] = explode(':', $vlaas);
                        foreach ($vlab as $key=>$vlass){
                            $items[$vlass[0]] = $vlass[1];
                        }
                    }
                    continue;
                }
                $info_list[$val['id']]['field_data'] = $items[$info_list[$val['id']]['field_data']];
            }
            //当扩展资料为join时，读取数据并进行处理再显示到前端@MingYang
            if($val['child_form_type'] == "join"){
                $j = explode('|',$val['form_default_value']);
                $a = explode(' ',$info_list[$val['id']]['field_data']);
                $info_list[$val['id']]['field_data'] = get_userdata_join($a,$j[0],$j[1]);
            }
        }
        return $info_list;
    }

    public function _get_field_data($data = null)
    {
        $result = null;
        $result['field_name'] = $data['field_name'];
        $result['field_data'] = "还未设置";
        switch ($data['form_type']) {
            case 'input':
                $result['field_data'] = isset($data['field_content']['field_data']) ? $data['field_content']['field_data'] : "还未设置";
                break;
            case 'radio':
                $result['field_data'] = isset($data['field_content']['field_data']) ? $data['field_content']['field_data'] : "还未设置";
                break;
            case 'textarea':
                $result['field_data'] = isset($data['field_content']['field_data']) ? $data['field_content']['field_data'] : "还未设置";
                break;
            case 'select':
                $result['field_data'] = isset($data['field_content']['field_data']) ? $data['field_content']['field_data'] : "还未设置";
                break;
            case 'checkbox':
                $result['field_data'] = isset($data['field_content']['field_data']) ? implode(' ', explode('|', $data['field_content']['field_data'])) : "还未设置";
                break;
            case 'time':
                $result['field_data'] = isset($data['field_content']['field_data']) ? date("Y-m-d", $data['field_content']['field_data']) : "还未设置";
                break;
        }
        $result['field_data'] = op_t($result['field_data']);

        return $result;
    }

    public function edit_expandinfo($profile_group_id)
    {
        $field_list = $this->getRoleFieldIds();
        if ($field_list) {
            $map_field['id'] = array('in', $field_list);
        } else {
            $this->ajaxReturn(array('status'=>0,'info'=>L('_ERROR_INFO_SAVE_NONE_').L('_EXCLAMATION_')));
        }
        $map_field['profile_group_id'] = $profile_group_id;
        $map_field['status'] = 1;
        $field_setting_list = D('field_setting')->where($map_field)->order('sort asc')->select();

        if (!$field_setting_list) {
            $this->ajaxReturn(array('status'=>0,'info'=>L('_ERROR_INFO_CHANGE_NONE_').L('_EXCLAMATION_')));
        }

        $data = null;
        foreach ($field_setting_list as $key => $val) {
            $data[$key]['uid'] = is_login();
            $data[$key]['field_id'] = $val['id'];
            switch ($val['form_type']) {
                case 'input':
                    $val['value'] = op_t($_POST['expand_' . $val['id']]);
                    if (!$val['value'] || $val['value'] == '') {
                        if ($val['required'] == 1) {
                            $this->ajaxReturn(array('status'=>0,'info'=>$val['field_name'] . L('_ERROR_CONTENT_NONE_').L('_EXCLAMATION_')));
                        }
                    } else {
                        $val['submit'] = $this->_checkInput($val);
                        if ($val['submit'] != null && $val['submit']['succ'] == 0) {
                            $this->ajaxReturn(array('status'=>0,'info'=>$val['submit']['msg']));
                        }
                    }
                    $data[$key]['field_data'] = $val['value'];
                    break;
                case 'radio':
                    $val['value'] = op_t($_POST['expand_' . $val['id']]);
                    $data[$key]['field_data'] = $val['value'];
                    break;
                case 'checkbox':
                    $val['value'] = $_POST['expand_' . $val['id']];
                    if (!is_array($val['value']) && $val['required'] == 1) {
                        $this->ajaxReturn(array('status'=>0,'info'=>L('_ERROR_AT_LIST_ONE_').L('_COLON_') . $val['field_name']));
                    }
                    $data[$key]['field_data'] = is_array($val['value']) ? implode('|', $val['value']) : '';
                    break;
                case 'select':
                    $val['value'] = op_t($_POST['expand_' . $val['id']]);
                    $data[$key]['field_data'] = $val['value'];
                    break;
                case 'time':
                    $val['value'] = op_t($_POST['expand_' . $val['id']]);
                    $val['value'] = strtotime($val['value']);
                    $data[$key]['field_data'] = $val['value'];
                    break;
                case 'textarea':
                    $val['value'] = op_t($_POST['expand_' . $val['id']]);
                    if (!$val['value'] || $val['value'] == '') {
                        if ($val['required'] == 1) {
                            $this->ajaxReturn(array('status'=>0,'info'=>$val['field_name'] . L('_ERROR_CONTENT_NONE_').L('_EXCLAMATION_')));
                        }
                    } else {
                        $val['submit'] = $this->_checkInput($val);
                        if ($val['submit'] != null && $val['submit']['succ'] == 0) {
                            $this->ajaxReturn(array('status'=>0,'info'=>$val['submit']['msg']));
                        }
                    }
                    $val['submit'] = $this->_checkInput($val);
                    if ($val['submit'] != null && $val['submit']['succ'] == 0) {
                        $this->ajaxReturn(array('status'=>0,'info'=>$val['submit']['msg']));
                    }
                    $data[$key]['field_data'] = $val['value'];
                    break;
            }
        }
        $map['uid'] = is_login();
        $map['role_id'] = get_login_role();
        $is_success = false;
        foreach ($data as $dl) {
            $dl['role_id'] = $map['role_id'];

            $map['field_id'] = $dl['field_id'];
            $res = D('field')->where($map)->find();
            if (!$res) {
                if ($dl['field_data'] != '' && $dl['field_data'] != null) {
                    $dl['createTime'] = $dl['changeTime'] = time();
                    if (!D('field')->add($dl)) {
                        $this->ajaxReturn(array('status'=>0,'info'=>L('_ERROR_INFO_ADD_').L('_EXCLAMATION_')));
                    }
                    $is_success = true;
                }
            } else {
                $dl['changeTime'] = time();
                if (!D('field')->where(array('id' => $res['id']))->save($dl)) {
                    $this->ajaxReturn(array('status'=>0,'info'=>L('_ERROR_INFO_CHANGE_').L('_EXCLAMATION_')));
                }
                $is_success = true;
            }
            unset($map['field_id']);
        }
        clean_query_user_cache(is_login(), 'expand_info');
        if ($is_success) {
            $this->ajaxReturn(array('status'=>1,'info'=>L('_SUCCESS_SAVE_').L('_EXCLAMATION_'),'url'=>'refresh'));
        } else {
            $this->ajaxReturn(array('status'=>0,'info'=>L('_ERROR_SAVE_').L('_EXCLAMATION_')));
        }
    }

    function _checkInput($data)
    {
        if ($data['form_type'] == "textarea") {
            $validation = $this->_getValidation($data['validation']);
            if (($validation['min'] != 0 && mb_strlen($data['value'], "utf-8") < $validation['min']) || ($validation['max'] != 0 && mb_strlen($data['value'], "utf-8") > $validation['max'])) {
                if ($validation['max'] == 0) {
                    $validation['max'] = '';
                }
                $info['succ'] = 0;
                $info['msg'] = $data['field_name'] . L('_INFO_LENGTH_1_') . $validation['min'] . "-" . $validation['max'] . L('_INFO_LENGTH_2_');
            }
        } else {
            switch ($data['child_form_type']) {
                case 'string':
                    $validation = $this->_getValidation($data['validation']);
                    if (($validation['min'] != 0 && mb_strlen($data['value'], "utf-8") < $validation['min']) || ($validation['max'] != 0 && mb_strlen($data['value'], "utf-8") > $validation['max'])) {
                        if ($validation['max'] == 0) {
                            $validation['max'] = '';
                        }
                        $info['succ'] = 0;
                        $info['msg'] = $data['field_name'] .  L('_INFO_LENGTH_1_') . $validation['min'] . "-" . $validation['max'] .  L('_INFO_LENGTH_2_');
                    }
                    break;
                case 'number':
                    if (preg_match("/^\d*$/", $data['value'])) {
                        $validation = $this->_getValidation($data['validation']);
                        if (($validation['min'] != 0 && mb_strlen($data['value'], "utf-8") < $validation['min']) || ($validation['max'] != 0 && mb_strlen($data['value'], "utf-8") > $validation['max'])) {
                            if ($validation['max'] == 0) {
                                $validation['max'] = '';
                            }
                            $info['succ'] = 0;
                            $info['msg'] = $data['field_name'] .  L('_INFO_LENGTH_1_') . $validation['min'] . "-" . $validation['max'] .  L('_INFO_LENGTH_2_').L('_COMMA_').L('_INFO_AND_DIGITAL_');
                        }
                    } else {
                        $info['succ'] = 0;
                        $info['msg'] = $data['field_name'] . L('_INFO_DIGITAL_');
                    }
                    break;
                case 'email':
                    if (!preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i", $data['value'])) {
                        $info['succ'] = 0;
                        $info['msg'] = $data['field_name'] . L('_INFO_FORMAT_EMAIL_');
                    }
                    break;
                case 'phone':
                    if (!preg_match("/^\d{11}$/", $data['value'])) {
                        $info['succ'] = 0;
                        $info['msg'] = $data['field_name'] . L('_INFO_FORMAT_PHONE_');
                    }
                    break;
            }
        }
        return $info;
    }

    function _getValidation($validation)
    {
        $data['min'] = $data['max'] = 0;
        if ($validation != '') {
            $items = explode('&', $validation);
            foreach ($items as $val) {
                $item = explode('=', $val);
                if ($item[0] == 'min' && is_numeric($item[1]) && $item[1] > 0) {
                    $data['min'] = $item[1];
                }
                if ($item[0] == 'max' && is_numeric($item[1]) && $item[1] > 0) {
                    $data['max'] = $item[1];
                }
            }
        }
        return $data;
    }

    public function mine(){
        $this->setTitle('个人中心');
        $this->setKeywords("个人中心");
        $this->setDescription("个人中心");
        $aUid = I('get.uid', 0, 'intval');
        if (!$aUid) {
            redirect(U('Weibo/Index/index'));
        }
        $user_info =$this->_userData($aUid);
        $friend_list=$this->_myfriend($aUid);
        $weibo_list=$this->_myWeibo($aUid);
        $mycrowd_list=$this->_myCrowd($aUid);
        $crowd_list=$this->_crowd($aUid);
        $friend_btn=$this->_friends($aUid);
        $this->assign('weibo_list', $weibo_list);
        $this->assign('friend_list', $friend_list);
        $this->assign('mycrowd_list', $mycrowd_list);
        $this->assign('crowd_list', $crowd_list);
        $this->assign('friend_btn', $friend_btn);
        $this->assign('uid', $aUid);
        $this->assign('page', 1);
        $this->assign('user_info', $user_info);
        $this->display();
    }
   public function follow(){
       if(!is_login()){
           $data['status']=0;
           $data['info']='先去登录吧';
           $this->ajaxReturn($data);
       }
       $aUid=I('post.uid',0,'intval');
       $aType=I('post.type','mufriend','text');
       $res=D('Follow')->$aType($aUid);
       S('userResult',null);
       $this->ajaxReturn($res);
   }
    /**
     * @param $uid
     * 获得我的好友
     */
    private function _myfriend($uid){
        $aPage = I('get.page', 1, 'intval');
        $aCount = I('get.count', 10, 'intval');
        $uids=D('Follow')->page($aPage,$aCount)->getMyFriends($uid);
        $k=0;
        foreach ($uids as $val){
            $user_info[$k] = query_user(array('avatar64', 'nickname', 'uid', 'space_url', 'space_mob_url', 'title', 'fans', 'mufriending', 'signature'), $val);
            $k++;
        }
        unset($val,$k);

        foreach ($user_info as &$v){
            $v['is_follow']=D('Common/Follow')->isFollow(is_login(),$v['uid']);
        }
        return $user_info;
    }
    public function addMoreFriend(){
        $aPage = I('post.page', 1, 'intval');
        $aCount = I('post.count', 10, 'intval');
        $aUid=I('post.uid',0,'intval');
        $uids=D('Follow')->page($aPage,$aCount)->getMyFriends($aUid);
        if(empty($uids)){
            $data['status']=0;
            $data['info']='没有更多了';
            $this->ajaxReturn($data);
        }
        $k=0;
        foreach ($uids as $val){
            $user_info[$k] = query_user(array('avatar64', 'nickname', 'uid', 'space_url', 'space_mob_url', 'title', 'fans', 'following', 'signature'), $val);
            $k++;
        }
        unset($val,$k);
        foreach ($user_info as &$v){
            $v['is_follow']=D('Common/Follow')->isFollow(is_login(),$v['uid']);
        }
        $this->assign('friend_list',$user_info);
        $html=$this->fetch('_friend');
        $this->ajaxReturn($html);
    }
    /**
     * @param $uid
     * @return mixed
     * 获取所有微博信息
     */
    private  function _myWeibo($uid){
        require_once('./Application/Weibo/Common/function.php');
        $aPage = I('get.page', 1, 'intval');
        $param = array();
        //查询条件
        $weiboModel = D('Weibo/Weibo');
        $param['field'] = 'id';
        $param['page'] = $aPage;
        $param['count'] = 10;
        $param['where']['status'] = 1;
        $param['where']['uid'] = $uid;
        $invisibleList = D('Weibo/WeiboCrowd')->getInvisible($uid, 1) ;
        if (!empty($invisibleList)) {
            $iArray = array_column($invisibleList, 'id') ;
            $param['where']['crowd_id'] = array('not in',$iArray) ;
        }
        $list = $weiboModel->getWeiboList($param);
        foreach ($list as $val) {
            $weibo[] = D('Weibo/Weibo')->getWeiboDetail($val);
        }
        unset($val);
        foreach ($weibo as &$v){
            $v['is_follow']=D('Common/Follow')->isFollow(is_login(),$v['uid']);
        }
        $this->assign('weibo', $weibo);
        $this->assign('weibo_page', $aPage);
        return $weibo;
    }


    public  function addMoreWeibo(){
        require_once('./Application/Weibo/Common/function.php');
        $aPage = I('post.page', 1, 'intval');
        $aUid=I('post.uid',0,'intval');
        $param = array();
        //查询条件
        $weiboModel = D('Weibo/Weibo');
        $param['field'] = 'id';
        $param['page'] = $aPage;
        $param['count'] = 10;
        $param['where']['status'] = 1;
        $param['where']['uid'] = $aUid;

        $list = $weiboModel->getWeiboList($param);
        if(empty($list)){
            $data['status']=0;
            $data['info']='没有更多了';
            $this->ajaxReturn($data);
        }
        foreach ($list as $val) {
            $weibo[] = D('Weibo/Weibo')->getWeiboDetail($val);
        }
        $this->assign('weibo_list',$weibo);
        $html=$this->fetch('_weibo');
        $this->ajaxReturn($html);

    }

    /**
     * @param $uid
     * @return mixed
     * 用户资料
     */
    private function _userData($uid){
        $userdata=D('Member')->where(array('uid'=>$uid))->find();
        $userdata['pos_province'] = M('district')->where(array('id' => $userdata['pos_province']))->getField('name');
        $userdata['pos_city'] = M('district')->where(array('id' => $userdata['pos_city']))->getField('name');
        $userdata['pos_district'] = M('district')->where(array('id' => $userdata['pos_district']))->getField('name');
        $userdata['user']=query_user(array('nickname','email','mobile','birthday','following','avatar64','signature','space_mob_url'), $uid);
        return $userdata;
    }

    public function avatar(){
        $this->setTitle('头像');
        $this->setKeywords("头像");
        $this->setDescription("头像");
        if(IS_POST){
            $aUid=I('post.uid',0,'intval');
            if($aUid!=is_login()){
                $data['status']=0;
                $data['info']='无法修改';
            }
            else{
                $data['status']=1;
            }
            $this->ajaxReturn($data);
        }

        $data=query_user('avatar512',is_login());
        $this->assign('avatar',$data['avatar512']);
        $this->assign('uid',is_login());
        $this->display();
    }
    private function _myCrowd($uid){
        $aPage=I('get.page',1,'intval');
        $list=D('Weibo/WeiboCrowd')->getMyCrowd($aPage,$uid);
        return $list;
    }
    public function addMoreMyCrowd(){
        $aPage=I('post.page',1,'intval');
        $aUid=I('post.uid',0,'intval');
        $list=D('Weibo/WeiboCrowd')->getMyCrowd($aPage,$aUid);
        if(empty($list)){
            $data['status']=0;
            $data['info']='没有更多了';
            $this->ajaxReturn($data);
        }
        $this->assign('mycrowd_list',$list);
        $html=$this->fetch('_mycrowd');
        $this->ajaxReturn($html);
    }
    private function _crowd($uid){
        $aPage=I('get.page',1,'intval');
        $list=D('Weibo/WeiboCrowdMember')->getMyJoinCrowd($aPage,$uid);
        foreach ($list as &$v){
            $v['detail']=D('Weibo/WeiboCrowd')->getCrowd($v['crowd_id']);
        }
       return $list;
    }
    public function addMoreCrowd(){
        $aPage=I('post.page',1,'intval');
        $aUid=I('post.uid',0,'intval');
        $list=D('Weibo/WeiboCrowdMember')->getMyJoinCrowd($aPage,$aUid);
        if(empty($list)){
            $data['status']=0;
            $data['info']='没有更多了';
            $this->ajaxReturn($data);
        }
        foreach ($list as &$v){
            $v['detail']=D('Weibo/WeiboCrowd')->getCrowd($v['crowd_id']);
        }
        $this->assign('crowd_list',$list);
        $html=$this->fetch('_crowd');
        $this->ajaxReturn($html);
    }


    public  function rank(){
        $this->setTitle('排行榜');
        $this->setKeywords("排行榜");
        $this->setDescription("排行榜");
        if(IS_POST){
            $aUid=I('post.uid',0,'intval');
            if(!is_login()||is_login()!=$aUid){
                $data['status']=0;
                $data['info']='非正常登录';
                $this->ajaxReturn($data);
            }
            $addon   = new \Core\Controller\CheckInController();
            $res=$addon->doCheckIn();
            if ($res) {
                $check = query_user(array('con_check', 'total_check'), $aUid);
                $this->ajaxReturn(array('status' => 1, 'info' => '签到成功!', 'con_check' => $check['con_check'], 'total_check' => $check['total_check']));
            } else {
                $this->ajaxReturn(array('status' => 0,'info' =>'已经签到了！'));
            }
        }else{
            $memberModel = D('Member');
            $uid=is_login();
            $user=query_user(array('avatar512','nickname','con_check', 'total_check'), $uid);
            $model=new \Core\Model\CheckInModel();
            $con=$model->getRank('con');
            $total=$model->getRank('total');
            $rankList=array();
            $p=1;
            foreach ($con as $co){
                if($co['uid']==is_login()){
                    $rankList['con_check_rank'] = $p;
                }else{
                    $p++;
                }

            }

            $q=1;
            foreach ($total as $to){
                if($to['uid']==is_login()){
                    $rankList['total_check_rank'] =$q;
                }else{
                    $q++;
                }

            }
             unset($p,$q,$to,$co);
            //排行榜个人排名
            $userScore = $memberModel->where(array('uid' => $uid))->field('fans')->find();
            $rankList['my_fans']=count2str($userScore['fans']);

            $tag='fans_rank';
            $user_fans_list=S($tag);
            if(empty($user_fans_list)){
                $user_fans_list = $memberModel->where(array('status' => 1,'fans'=>array('gt',0)))->field('uid,fans,nickname')->order('fans desc,uid asc')->limit(10)->select();
                foreach ($user_fans_list as &$u) {
                    $temp_user = query_user(array('avatar512'), $u['uid']);
                    $u['avatar512'] = $temp_user['avatar512'];
                }
                S($tag,$user_fans_list,60*60);
            }

            $k=1;
            foreach ($user_fans_list as $vo){
                if($vo['uid']==is_login()){
                    $rankList['fans_rank'] = count2str($k);
                }else{
                    $k++;
                }

            }
            unset($k,$vo);
            $this->assign('user',$user);
            $this->assign('con',$con);
            $this->assign('total',$total);
            $this->assign('fans_list',$user_fans_list);
            $this->assign('rank',$rankList);
            $this->display();
        }

    }
    public function handleData($data){
        foreach ($data as &$v){
            $v['user']['is_follow']=D('Common/Follow')->isFollow(is_login(),$v['user']['uid']);
        }
        unset($v);
        return $data;
    }
    public function fansList($aType,$aUid,$page=1){
        switch ($aType) {
            case 'friends':
                $list = $this->_myfriend($aUid);
                $k=0;
                foreach ($list as $v){
                    $data[$k]['user']=$v;
                    $k++;
                }
                unset($v,$k);
                if($aUid==is_login()){
                    $title='我的好友';
                }else{
                    $title='他的好友';
                }
                $this->assign('type','friends');
                break;
            case 'fans':
                if($aUid==is_login()){
                    $title='我的粉丝';
                }else{
                    $title='他的粉丝';
                }
                $data = D('Follow')->getFans($aUid, $page, array('avatar128', 'uid', 'nickname', 'fans', 'following', 'weibocount', 'space_url', 'title','space_mob_url'));
                $data=$this->handleData($data);
                $this->assign('type','fans');
                break;
            case 'follow':
                if($aUid==is_login()){
                    $title='我的信任';
                }else{
                    $title='他的信任';
                }
                $this->assign('type','follow');
                $data = D('Follow')->getFollowing($aUid, $page, array('avatar128', 'uid', 'nickname', 'fans', 'following', 'weibocount', 'space_url', 'title','space_mob_url'));
                $data=$this->handleData($data);
                break;
            default:
                if($aUid==is_login()){
                    $title='我的信任';
                }else{
                    $title='他的信任';
                }
                $this->assign('type','follow');
                $data = D('Follow')->getFollowing($aUid, $page, array('avatar128', 'uid', 'nickname', 'fans', 'following', 'weibocount', 'space_url', 'title','space_mob_url'));
                $data=$this->handleData($data);
        }
       return array($title,$data);
    }
    //我的信任、粉丝、好友
    public function fans(){
            $aUid = I('get.uid', 0, 'intval');
            $aType = I('get.type', 'follow', 'text');
            $aPage=I('get.page',1,'intval');
            $aPull=I('get.is_pull',0,'intval');
            $result=$this->fansList($aType,$aUid,$aPage);

            $this->assign('first_num',count($result[1]));
            $this->setTitle($result[0]);
            $this->setKeywords($result[0]);
            $this->setDescription($result[0]);
            $this->assign('title',$result[0]);
            $this->assign('data',$result[1]);
            $this->assign('page',$aPage);
            $this->assign('uid',$aUid);
            if($aPull==0){
                $this->display();
            }else{
                $data['html'] = '';
                $data['status'] = 1;
                $data['html'] .= $this->fetch('_fans');
                $this->ajaxReturn($data);
            }

        }

    public function getInfo()
    {
        $aUid = I('get.uid',0,'intval');
        $user = query_user(array('uid', 'nickname', 'avatar64','avatar_html64', 'space_url', 'following', 'fans', 'weibocount', 'signature', 'rank_link','pos_province', 'pos_city', 'pos_district', 'pos_community'), $aUid);
        $cover = modC('WEB_SITE_LOGO','','Config');
        $user['logo'] = getThumbImageById($cover,80,80);
        if ($aUid == is_login()) {
            $user['is_self'] = 1;
        } else{
            $user['is_self'] = 0;
        }
        $res = D('Common/Follow')->isFollow(is_login(), $aUid);
        if ($res == 1) {
            $user['follow_status'] = '已信任';
            $user['is_follow'] = 'unfollow';
        } else {
            $user['follow_status'] = '信任';
            $user['is_follow'] = 'follow';
        }
        $user['is_login'] = is_login();
        $user['is_wechat'] = is_weixin();
        if ($user) {
            $this->ajaxReturn(array('status'=>1,'data'=>$user));
        } else {
            $this->ajaxReturn(array('status'=>0));
        }
    }
    public function favorite(){

        $collection = D('Collect')->where(array('module'=>'Mall'))->count();
        $count= D('Collect')->where(array('module'=>'News'))->count();
        $this->assign('collection',$collection);
        $this->assign('count',$count);
        $this->display();
    }

    public function addList(){

        $aPage = I('post.page',1,'intval');
        $aCount = I('post.count',10,'intval');
        $aModule=I('post.module','Mall','text');

        $collection = D('Collect')->getCollection($aModule,'',$aPage,$aCount);
        foreach ($collection as &$v) {
            switch ($v['table']) {
                case 'collect':
                    $v['detail'] = D('Mall/Goods')->getGoods($v['row']);
                    if ($v['detail']['uid']) {
                        $v['user'] = query_user(array('nickname', 'uid', 'avatar128'), $v['detail']['uid']);
                    } else {
                        $v['user'] = query_user(array('nickname', 'uid', 'avatar128'), $collection['uid']);
                    }
                    break;
                case 'news':
                    $v=D('News/news')->where(array('id'=>$v['row']))->find();
                    $v['category']=D('New/news_category')->where(array('id'=>$v['category']))->getField('title');
                    $v['uid']=query_user(array('nickname','uid','avatar512'),$v['uid']);
                    break;
            }
        }
        unset($v);
        if($collection){
            if($aModule == 'Mall'){
                $this->assign('collection',$collection);
                $collection=$this->fetch('_favorite');
                $this->ajaxReturn(array(
                    'info'=>'请求成功',
                    'status'=>1,
                    'data'=>$collection
                ));
            }else{
                $this->assign('data',$collection);
                $collection=$this->fetch('_list');
                $this->ajaxReturn(array(
                    'info'=>'请求成功',
                    'status'=>1,
                    'data'=>$collection
                ));
            }
        }else{
            $this->ajaxReturn(array(
                'info'=>'请求失败',
                'status'=>1,
                'data'=>''
            ));
        }
    }
    public function wallet(){
        $this->setTitle('我的钱包');
        $this->setKeywords("我的钱包");
        $this->setDescription("我的钱包");
        //查询总金额
        $data=D('member')->field('score4')->where(array('uid'=>is_login()))->find();
        //查询领红包情况
        $data['score4']=number_format($data['score4'],2);
        if(IS_POST){
            $page=I('post.page',1,'intval');
            $dataList=D('consumption_log')->where(array('uid'=>is_login()))->page($page,10)->order('create_time desc')->select();
            foreach ($dataList as &$vo){
                $username=D('member')->where(array('id'=>$vo['id']))->field('nickname')->find();
                $vo['uid']=$username['nickname'];
                $vo['create_time']=friendlyDate($vo['create_time']);
            }
            unset($vo);
            if($dataList){
                $this->assign('dataList',$dataList);
                $html=$this->fetch('_walletlist');
                $this->ajaxReturn(array('status'=>1,'info'=>'请求成功','data'=>$html));
            }else{
                $this->ajaxReturn(array('data' => '', 'status' => 1, 'info' => '获取失败'));
            }
        }else{
            $count=D('consumption_log')->where(array('uid'=>is_login()))->count();
            //获取后台配置提现字段
            $fields=D('Order/Withdraw')->get_wi_field();
            $this->assign(array(
                'totalMoney'=>$data['score4'],
                'count'=>$count,
                'fields'=>$fields
            ));
            $this->display();
        }
    }

    public function pay()
    {
        include_once(APP_PATH."Ucenter/Lib/WxPayPubHelper.php");

        $jsApi = new \JsApi_pub();
        redirect($jsApi->createOauthUrlForCode(U('chooseMoney','',true,true)));

    }

    public function chooseMoney()
    {
        include_once(APP_PATH."Ucenter/Lib/WxPayPubHelper.php");
        $code = I('get.code','','html');
        $jsApi = new \JsApi_pub();
        $jsApi->code = $code;
        $openId = $jsApi->getOpenid();
        $this->assign('open_id',$openId);
        $this->display();
    }

    public function payapi()
    {
        include_once(APP_PATH."Ucenter/Lib/WxPayPubHelper.php");
        $openId = I('post.open_id',0,'html');
        $money = I('post.money',1,'intval');

        if (!is_login()) {
            $this->ajaxReturn(array('status'=>0,'info'=>'未登录'));
        }
//        $user = M('sync_login')->where(array('type'=>'weixin','oauth_token_secret'=>$openId))->find();
//        $rs = D('Ucenter/Score')->setUserScore(is_login(), $money/100, 4, 'dec');

        $jsApi = new \JsApi_pub();
        $unifiedOrder = new \UnifiedOrder_pub();

        $unifiedOrder->setParameter("openid",$openId);
        $unifiedOrder->setParameter("body","this is a test");
        $timeStamp = time();
        $out_trade_no = \WxPayConf_pub::APPID.$timeStamp;
        $unifiedOrder->setParameter("out_trade_no",$out_trade_no);
        $unifiedOrder->setParameter("total_fee",$money);//总金额
        $unifiedOrder->setParameter("notify_url",\WxPayConf_pub::NOTIFY_URL);
        $unifiedOrder->setParameter("trade_type","JSAPI");

        $prepay_id = $unifiedOrder->getPrepayId();
        $jsApi->setPrepayId($prepay_id);

        $jsApiParameters = $jsApi->getParameters();

        wx_pay(0,$out_trade_no,$openId,is_login());

        exit($jsApiParameters);
    }
    //修改密码
    public function resetPassword(){
        if (!is_login()){
            $this->error('请先登录');
        }
        if (IS_POST){
            $oldPassword=I('post.oldPassword','string');
            $newPassword=trim(I('post.newPassword','','string'));
            $RenewPassword=trim(I('post.RenewPassword','','string'));
            if ($oldPassword==null){
                $this->error('新密码不能为空');
            }
            if ($newPassword!=$RenewPassword||$newPassword==null||$RenewPassword==null){
                $this->error('密码不一致');
            }
            $oldPassword=think_ucenter_md5($oldPassword, UC_AUTH_KEY);
            $id=D('ucenter_member')->field('id')->where(array('password'=>$oldPassword,'id'=>is_login()))->find();
            if ($id==null){
                $this->error('旧密码不正确');
            }else{
                $newPassword=think_ucenter_md5($newPassword,UC_AUTH_KEY);
                if ($newPassword==$oldPassword){
                    $this->error('新旧密码一致，不能更改');
                }else{
                    $data['password']=$newPassword;
                    $res=D('ucenter_member')->where(array('id'=>is_login()))->save($data);
                    if ($res){
                        $this->success('修改成功！');
                    }else{
                        $this->error('修改失败，未知错误！');
                    }
                }
            }


        }
        $this->display();
    }

    public function square(){
        $map['status']=1;
        $map['location']=1;
        $advertisement=S('ucenter_square_advertisement');
        if ($advertisement===false){
            $advertisement=D('advertisement')->where($map)->select();
            foreach ($advertisement as &$val){
                $val['img']=getThumbImageById($val['imgid'],5000,5000);
            }
            unset($val);
            S('ucenter_square_advertisement',$advertisement,86400);
        }
        $count=D('advertisement')->where($map)->count();
        $this->assign('count', $count);
        $this->assign('advertisement', $advertisement);
        $uid = is_login() ;
        $this->assign('uid', $uid) ;
        //渲染配置微博
        $weiboList = get_square_weibo() ;
        $this->assign('weibo', $weiboList) ;
        $weiboHtml = $this->fetch(T('Weibo@Index/_list')) ;
        $this->assign('weiboHtml', $weiboHtml) ;
        //渲染推荐好友
        $userResult = $this->getFriend() ;
        $this->assign('friend_list', $userResult);
        //渲染配置资讯
        S('SQUARE_NEWS_MODULE_LIVE') ;
        if (D('Module')->checkInstalled('News')) {
            $this->assign('news_module', 1) ;
            $newsResult = get_square_list('News', 'newsResult', 'SET_NEWS', 'id', array('status'=>1), 'create_time desc', 'comment desc') ;
            foreach ($newsResult as &$datum){
                $datum['category']=D('news_category')->where(array('id'=>$datum['category']))->field('id,title')->find();
                $datum['uid']=query_user(array('uid','nickname','avatar512'),$datum['uid']);
                $datum['create_time']=friendlyDate($datum['create_time']);
            }
            unset($datum);
            $this->assign('data', $newsResult);
            $newsHtml = $this->fetch(T('News@Index/_list')) ;
            $this->assign('newsHtml', $newsHtml) ;
        }

        //渲染配置论坛
        if (D('Module')->checkInstalled('Forum')) {
            $this->assign('forum_module', 1) ;
            $forumResult = get_square_list('ForumPost', 'forumResult', 'SET_FORUM', 'id', array('status'=>1), 'create_time desc', 'view_count desc') ;
            foreach ($forumResult as &$datum){
                $datum['uid']=query_user(array('nickname','uid', 'avatar128', 'space_url',),$datum['uid']);
                $datum['create_time']=friendlyDate($datum['create_time'], 'normal');
                $datum['forum'] = D('Forum')->where(array('id'=>$datum['forum_id']))->field('title')->find() ;
            }
            unset($datum);
            $this->assign('data', $forumResult);
            $forumHtml = $this->fetch(T('Forum@Index/_list')) ;
            $this->assign('forumHtml', $forumHtml) ;
        }
        //渲染配置问答
        if (D('Module')->checkInstalled('Question')) {
            $this->assign('question_module', 1) ;
            $quResult = get_square_list('Question', 'questionResult', 'SET_QUESTION', 'id', array('status'=>1), 'create_time desc', 'answer_num desc') ;
            $quResult = A('Question/Index')->fetchNewsData($quResult);
            $this->assign('data', $quResult);
            $quHtml = $this->fetch(T('Question@Index/_list')) ;
            $this->assign('quHtml', $quHtml) ;
        }
        if(D('Module')->checkInstalled('Event')) {
            $this->assign('event_module', 1) ;
        }
        $this->setTitle('广场') ;
        $this->display();
    }

    public function getFriend() {
        $uid = is_login() ;
        $userId = modC('SET_USER_ID','');
        if($userId!=""){
            $userId=explode(',', $userId);
            $follow=D('Follow')->where(array('who_follow'=>$uid))->getfield('follow_who',true);
            shuffle($userId) ;
            $userId = array_slice($userId, 0, 3) ;
            foreach ($userId as $item) {
                $list=query_user(array('nickname','avatar512','signature','uid'),$item);
                if(in_array($item,$follow)) {
                    $list=array_merge(array('is_follow' => 1), $list);
                }
                $userResult[]=$list;
            }
            unset($item);
        }
        if(IS_AJAX){
            $html = '' ;
            $this->assign('friend_list', $userResult) ;
            $html = $this->fetch('_friendbox') ;
            if($html == false){
                $this->ajaxReturn(array('status'=>0, 'data'=>$html)) ;
            }
            $this->ajaxReturn(array('status'=>1, 'data'=>$html)) ;
        }
        return $userResult ;
    }

    //申请认证
    public function condition(){
        //D('Ucenter/AttestType')->getData;
        $this->_checkAttestConditions();
        $this->_checkAttestStatus();

        $this->display();
    }

    //认证类型
    public function identify(){

        $attestType=$this->attestTypeModel->getTypeList();
        $this->assign('attest_type',$attestType);
        //dump($attestType);exit;
        $this->_checkAttestStatus();

        $this->display();
    }

    private function _checkAttestStatus($redirect=0)
    {
        $map['uid']=is_login();
        $map['status']=array('in','1,2,0');
        $attest=$this->attestModel->getData($map);
        //dump($attest);exit;
        if(!$attest){
            return false;
        }
        
        $attest['prove_image']=explode(',',$attest['prove_image']);
        $attest['image']=explode(',',$attest['image']);
        $attest['other_image']=explode(',',$attest['other_image']);
        $attest['type']=$this->attestTypeModel->getData($attest['attest_type_id'],1);
        if($attest['status']==1){
            if($redirect){
                redirect(U('Ucenter/Index/process'));
            }
        }
        if($attest['status']==2||$attest['status']==0){
            $aChange=I('change',0,'intval');
            if(!$aChange){
                if($redirect){
                    redirect(U('Ucenter/Index/process'));
                }
            }else{
                $this->assign('change',1);
            }
        }
        $this->assign('attest',$attest);
        return $attest;
    }
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
    private function _checkAttestConditions($attest_old=null)
    {
        if($attest_old){
            $attestType=$this->_checkTypeExist($attest_old['attest_type_id']);
        }else{

            $aId=I('get.id',0,'intval');
            //dump($aId);exit;
            $attestType=$this->_checkTypeExist($aId);
            $this->assign('myId',$aId);

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
    private function _checkTypeExist($id)
    {
        //dump($id);exit;
        $data=$this->attestTypeModel->getData($id,1);

        if(!$data){
            $this->error('该认证类型不存在！');
        }
        return $data;
    }

    public function apply()
    {
        $attest_old=$this->_checkAttestStatus(1);

        $this->checkAuth('Ucenter/Index/apply',-1,'你没有申请权限');
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
                $this->success('申请成功，请等待审核！',U('Ucenter/index/index'));
            }else{
                $this->success('申请失败，请重试！');
            }
        }else{
            //dump($attest_old);exit;
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
    
    public function setting()
    {
        $this->display();
    }

    public function safe()
    {
        $uid = is_login();
        if(IS_POST) {
            $sync = I('post.sync', '', 'intval');
            $mobile = I('post.mobile', '', 'intval');
            
            if($sync == 1) {
                $tel = query_user('mobile', $uid);
                if(!$tel['mobile']) {
                    $this->error('请先绑定手机号再解绑微信！');
                }
                $res = M('sync_login')->where(array('uid' => $uid))->delete();
//                $res = M('sync_login')->where(array('uid' => $uid))->setField('status', -1);
                if($res) {
                    $this->success('解绑成功~，即将刷新页面');
                } else {
                    $this->error('解绑失败！');
                }
            }
            
            if($mobile == 1) {
                $res = UCenterMember()->where(array('id' => $uid))->setField('mobile', null);
                clean_query_user_cache($uid, 'mobile');
                if($res) {
                    $this->success('解绑成功~，即将刷新页面');
                } else {
                    $this->error('解绑失败！');
                }
            }

        } else {
            $is_sync = M('sync_login')->where(array('uid' => $uid, 'status' => 1))->find();
            if($is_sync) {
                $user = query_user(array('nickname', 'avatar512'), $uid);
                $this->assign('user_info', $user);
            }

            $this->assign('is_sync', $is_sync);

            //手机号
            $is_mobile = query_user('mobile', $uid);
            $this->assign('is_mobile', $is_mobile['mobile']);
        }
        $this->display();
    }
    
    public function checkVerify()
    {
        $aAccount = I('post.account', '', 'text');
        $aVerify = I('post.verify', '', 'text');
        $ifIs = I('post.ifis', '', 'intval');
        $aUid = is_login();
        $aType = 'mobile';
        if (!is_login()) {
            $this->error('请先登录');
        }
        $res = D('Verify')->checkVerify($aAccount, $aType, $aVerify, $aUid);
        if (!$res) {
            $this->error(L('_FAIL_VERIFY_'));
        }
        UCenterMember()->where(array('id' => $aUid))->save(array($aType => $aAccount));
        $this->success(L('_SUCCESS_VERIFY_'), U($ifIs?'project/index/user':'Ucenter/Index/safe'));
    }

    private function _friends($uid) {
        $mine = is_login() ;
        if($mine == $uid) return 0 ;
        $friend = D('Follow')->eachFriend($uid, $mine) ;
        if($friend) return 1 ;
            return 0 ;
    }
}