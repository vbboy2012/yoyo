<?php
namespace Admin\Controller;

use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminConfigBuilder;

class WeiboController extends AdminController
{
    public function config()
    {
        $builder = new AdminConfigBuilder();
        $data = $builder->callback('configCallback')->handleConfig();

        $data['SHOW_TITLE'] = $data['SHOW_TITLE'] == null ? 1 : $data['SHOW_TITLE'];
        $data['HIGH_LIGHT_AT'] = $data['HIGH_LIGHT_AT'] == null ? 1 : $data['HIGH_LIGHT_AT'];
        $data['HIGH_LIGHT_TOPIC'] = $data['HIGH_LIGHT_TOPIC'] == null ? 1 : $data['HIGH_LIGHT_TOPIC'];
        $data['CAN_IMAGE'] = $data['CAN_IMAGE'] == null ? 1 : $data['CAN_IMAGE'];
        $data['CAN_TOPIC'] = $data['CAN_TOPIC'] == null ? 1 : $data['CAN_TOPIC'];
        $data['WEIBO_INFO'] = $data['WEIBO_INFO'] ? $data['WEIBO_INFO'] : L('_TIP_WEIBO_INFO_') . L('_QUESTION_');
        $data['WEIBO_NUM'] = $data['WEIBO_NUM'] ? $data['WEIBO_NUM'] : 140;
        $data['SHOW_COMMENT'] = $data['SHOW_COMMENT'] == null ? 1 : $data['SHOW_COMMENT'];
        $data['ACTIVE_USER'] = $data['ACTIVE_USER'] == null ? 1 : $data['ACTIVE_USER'];
        $data['ACTIVE_USER_COUNT'] = $data['ACTIVE_USER_COUNT'] ? $data['ACTIVE_USER_COUNT'] : 6;
        $data['NEWEST_USER'] = $data['NEWEST_USER'] == null ? 1 : $data['NEWEST_USER'];
        $data['NEWEST_USER_COUNT'] = $data['NEWEST_USER_COUNT'] ? $data['NEWEST_USER_COUNT'] : 6;

        $tab = array(
            array('data-id' => 'all', 'title' => L('_ALL_WEBSITE_FOLLOW_')),
            array('data-id' => 'concerned', 'title' => L('_MY_FOLLOW_')),
            array('data-id' => 'hot', 'title' => L('_HOT_WEIBO_')),
            array('data-id' => 'fav', 'title' => L('_MY_FAV_')),
            array('data-id' => 'huati', 'title' => L('_HOT_TOPIC_'))
        );
        $default = array(array('data-id' => 'enable', 'title' => L('_ENABLE_'), 'items' => $tab), array('data-id' => 'disable', 'title' => L('_DISABLE_'), 'items' => array()));

        $data['WEIBO_DEFAULT_TAB'] = $builder->parseKanbanArray($data['WEIBO_DEFAULT_TAB'], $tab, $default);

        $scoreTypes = D('Ucenter/Score')->getTypeList(array('status' => 1));
        foreach ($scoreTypes as $val) {
            $types[$val['id']] = $val['title'];
        }

        $data['WEIBO_SHOW_TITLE1'] = $data['WEIBO_SHOW_TITLE1'] ? $data['WEIBO_SHOW_TITLE1'] : L('_NEWEST_WEIBO_');
        $order = array('create_time' => L('_DELIVER_TIME_'), 'comment_count' => L('_COMMENT_COUNT_'));

        $builder->title(L('_WEIBO_BASIC_SETTINGS_'))
            ->data($data)
            ->keySingleImage('SOCIAL_QRCODE','社区微信公众号二维码')
//            ->keySwitch('SHOW_TITLE', L('_RANK_SHOW_IN_LEFT_'))
            ->keyBool('WEIBO_BR', L('_CONTENT_TYPE_OPEN_'), L('_SUPPORT_ENTER_SPACE_'))
            ->keyText('WEIBO_INFO', L('_WEIBO_POST_BOX_UP_LEFT_CONTENT_'))
//            ->keyText('WEIBO_NUM', L('_WEIBO_WORDS_LIMIT_'))
            ->keyText('HOT_LEFT', L('_HOT_WEIBO_RULE_'))->keyDefault('HOT_LEFT', 3)
            ->keyRadio('COMMENT_ORDER', L('_WEIBO_COMMENTS_LIST_ORDER_'), '', array(0 => L('_TIME_COUNTER_'), 1 => L('_TIME_DIRECT_')))
            //->keySelect('WEIBO_DEFAULT_TAB', '动态默认显示标签', '', array('all'=>'全站动态','concerned'=>'我的信任','hot'=>'热门动态'))
            ->keyKanban('WEIBO_DEFAULT_TAB', L('_WEIBO_SIGN_DEFAULT_'))
            ->keySwitch('ACTIVE_USER', L('_ACTIVE_USER_SWITCH_'))
            ->keySelect('ACTIVE_USER_ORDER', L('_ACTIVE_USER_SORT_'), '', $types)
            ->keyText('ACTIVE_USER_COUNT', L('_ACTIVE_USER_SHOW_NUMBER_'), '')
            ->keySwitch('NEWEST_USER', L('_USER_SWITCH_NEWEST_'))
            ->keyText('NEWEST_USER_COUNT', L('_USER_SHOW_NUMBER_NEWEST_'), '')
            ->keyText('HOT_WEIBO_COMMENT_NUM','热议标记阀值', '评论数超过该值时，会出现热议标记')->keyDefault('HOT_WEIBO_COMMENT_NUM', 10)
            ->keyText('LIMIT_REVIEW','神评论数量')->keyDefault('LIMIT_REVIEW', '5')
            ->keyText('SUPPORT_NUMBER','神评论所需点赞数量')->keyDefault('SUPPORT_NUMBER', '20')
            ->keyDefault('WEIBO_BR', 0)
            ->group(L('_BASIC_SETTINGS_'), 'SOCIAL_QRCODE,WEIBO_NUM,WEIBO_BR,WEIBO_DEFAULT_TAB,HIGH_LIGHT_AT,HIGH_LIGHT_TOPIC,WEIBO_INFO,HOT_LEFT,HOT_WEIBO_COMMENT_NUM')
            ->group(L('_SETTINGS_COMMENTS_'), 'COMMENT_ORDER,SHOW_COMMENT,LIMIT_REVIEW,SUPPORT_NUMBER')
            ->buttonSubmit();


        $builder->display();
    }

    public function configCallback()
    {
        S('weibo_latest_user_top', null);
        S('weibo_latest_user_new', null);
    }


    public function weibo()
    {
        $aPage = I('page', 1, 'intval');
        $r = 20;
        $model = M('Weibo');
        //微博内容找微博
        $aContent = I('content', '', 'op_t');

        $map = array('status' => array('EGT', 0));

        $aContent && $map['content'] = array('like', '%' . $aContent . '%');

        $list = $model->where($map)->order('create_time desc')->page($aPage, $r)->select();
        unset($li);
        $totalCount = $model->where($map)->count();
        //显示页面
        $builder = new AdminListBuilder();
        $attr['class'] = 'btn ajax-post';
        $attr['target-form'] = 'ids';
        $attr1 = $attr;
        $attr1['url'] = $builder->addUrlParam(U('setWeiboTop'), array('top' => 1));
        $attr0 = $attr;
        $attr0['url'] = $builder->addUrlParam(U('setWeiboTop'), array('top' => 0));

        $builder->title(L('_WEIBO_MANAGER_'))
            ->setStatusUrl(U('setWeiboStatus'))->buttonEnable()->buttonDisable()->buttonDelete()->button(L('_STICK_'), $attr1)->button(L('_STICK_CANCEL_'), $attr0)
            ->keyId()->keyLink('content', L('_CONTENT_'), 'comment?weibo_id=###')->keyUid()->keyCreateTime()->keyStatus()
            ->keyDoActionEdit('editWeibo?id=###')->keyMap('is_top', L('_STICK_'), array(0 => L('_STICK_NOT_'), 1 => L('_STICK_')))
            ->search(L('_CONTENT_'), 'content')
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }

    public function cleanWeiboHtmlCache()
    {
        D('Weibo/WeiboCache')->cleanCache();
        $this->success('操作成功！');
    }

    public function setWeiboTop($ids, $top)
    {
        foreach ($ids as $id) {
            D('Weibo')->where(array('id' => $id))->setField('is_top', $top);
            S('weibo_' . $id, null);
        }

        $this->success(L('_SUCCESS_SETTING_'), $_SERVER['HTTP_REFERER']);
    }

    public function weiboTrash()
    {
        $aPage = I('page', 1, 'intval');
        $r = 20;
        $builder = new AdminListBuilder();
        $builder->clearTrash('Weibo');
        //读取动态列表
        $map = array('status' => -1);
        $model = M('Weibo');
        $list = $model->where($map)->order('id desc')->page($aPage, $r)->select();
        $totalCount = $model->where($map)->count();

        //显示页面

        $builder->title('动态回收站')
            ->setStatusUrl(U('setWeiboStatus'))->buttonRestore()->buttonClear('Weibo')
            ->keyId()->keyLink('content', L('_CONTENT_'), 'comment?weibo_id=###')->keyUid()->keyCreateTime()
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }

    public function setWeiboStatus($ids, $status)
    {
        $builder = new AdminListBuilder();
        $builder->doSetStatus('Weibo', $ids, $status);
    }

    public function editWeibo()
    {
        $aId = I('id', 0, 'intval');
        $aContent = I('post.content', '', 'op_t');
        $aCreateTime = I('post.create_time', time(), 'intval');
        $aStatus = I('post.status', 1, 'intval');

        $model = M('Weibo');
        if (IS_POST) {
            //写入数据库
            $data = array('content' => $aContent, 'create_time' => $aCreateTime, 'status' => $aStatus);

            $result = $model->where(array('id' => $aId))->save($data);
            S('weibo_' . $aId, null);
            if (!$result) {
                $this->error(L('_FAIL_EDIT_'));
            }

            //返回成功信息
            $this->success(L('_SUCCESS_EDIT_'), U('weibo'));
        } else {
            //读取动态内容
            $weibo = $model->where(array('id' => $aId))->find();

            //显示页面
            $builder = new AdminConfigBuilder();
            $builder->title(L('_WEIBO_EDIT_'))
                ->keyId()->keyTextArea('content', L('_CONTENT_'))->keyCreateTime()->keyStatus()
                ->buttonSubmit(U('editWeibo'))->buttonBack()
                ->data($weibo)
                ->display();
        }
    }

    public function comment()
    {
        $aWeiboId = I('weibo_id', 0, 'intval');
        $aPage = I('page', 1, 'intval');
        $r = 20;
        //读取评论列表
        $map = array('status' => array('EGT', 0));
        if ($aWeiboId) $map['weibo_id'] = $aWeiboId;
        $model = M('WeiboComment');
        $list = $model->where($map)->order('id desc')->page($aPage, $r)->select();
        $weiboModel = D('weibo');
        foreach ($list as &$vo){
           $weibo=$weiboModel->where(array('id'=>$vo['weibo_id']))->field('content')->find();
            $vo['weibo_content']='[weibo_id:'.$vo['weibo_id'].'] '.$weibo['content'];
        }

        $totalCount = $model->where($map)->count();
        //显示页面
        $builder = new AdminListBuilder();
        $builder->title(L('_REPLY_MANAGER_'))
            ->setStatusUrl(U('setCommentStatus'))->buttonEnable()->buttonDisable()->buttonDelete()
            ->keyId()->keyText('content', L('_CONTENT_'))->keyUid()->keyText('weibo_content','所属微博')->keyCreateTime()->keyStatus()->keyDoActionEdit('editComment?id=###')
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }

    public function commentTrash()
    {
        $aPage = I('page', 1, 'intval');
        $r = 20;
        $builder = new AdminListBuilder();
        $builder->clearTrash('WeiboComment');
        //读取评论列表
        $map = array('status' => -1);
        $model = M('WeiboComment');
        $list = $model->where($map)->order('id desc')->page($aPage, $r)->select();
        $totalCount = $model->where($map)->count();
        //显示页面
        $builder->title(L('_REPLY_TRASH_'))
            ->setStatusUrl(U('setCommentStatus'))->buttonRestore()->buttonClear('WeiboComment')
            ->keyId()->keyText('content', L('_CONTENT_'))->keyUid()->keyCreateTime()
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }

    public function setCommentStatus($ids, $status)
    {
        foreach ($ids as $id) {
            $comemnt = D('Weibo/WeiboComment')->getComment($id);
            if ($status == 1) {
                D('Weibo/Weibo')->where(array('id' => $comemnt['weibo_id']))->setInc('comment_count');
            } else {
                D('Weibo/Weibo')->where(array('id' => $comemnt['weibo_id']))->setDec('comment_count');
            }
            S('weibo_' . $comemnt['weibo_id'], null);
        }


        $builder = new AdminListBuilder();
        $builder->doSetStatus('WeiboComment', $ids, $status);
    }

    public function editComment()
    {
        $aId = I('id', 0, 'intval');

        $aContent = I('post.content', '', 'op_t');
        $aCreateTime = I('post.create_time', time(), 'intval');
        $aStatus = I('post.status', 1, 'intval');

        $model = M('WeiboComment');
        if (IS_POST) {
            //写入数据库
            $data = array('content' => $aContent, 'create_time' => $aCreateTime, 'status' => $aStatus);
            $result = $model->where(array('id' => $aId))->save($data);
            S('weibo_comment_' . $aId);
            if (!$result) {
                $this->error(L('_ERROR_EDIT_'));
            }
            //显示成功消息
            $this->success(L('_SUCCESS_EDIT_'), U('comment'));
        } else {
            //读取评论内容
            $comment = $model->where(array('id' => $aId))->find();
            //显示页面
            $builder = new AdminConfigBuilder();
            $builder->title(L('_EDIT_COMMENTS_'))
                ->keyId()->keyTextArea('content', L('_CONTENT_'))->keyCreateTime()->keyStatus()
                ->data($comment)
                ->buttonSubmit(U('editComment'))->buttonBack()
                ->display();
        }
    }

    /**======================================start===================================
    ==========================================圈子=================================**/

    public function crowdConfig()
    {
        $builder = new AdminConfigBuilder();
        $data = $builder->handleConfig();
        $builder->title('基本配置');
        $builder->keyText('JOIN_CROWD_NUM', '用户可加入最大圈子数', '');
        $builder->keyBool('CREATE_CROWD_CHECK', '新建圈子是否需要审核');
        $builder->keyText('DEFAULT_FOLLOW_CROWD', '注册后默认加入的圈子ID','多个圈子用","分隔!~~~设置完请执行默认加入脚本');
        $builder->group('基本配置','JOIN_CROWD_NUM,CREATE_CROWD_CHECK,DEFAULT_FOLLOW_CROWD');
        $builder->buttonSubmit();
        $builder->data($data);
        $builder->keyDefault('JOIN_CROWD_NUM', 5);
        $builder->keyDefault('CREATE_CROWD_CHECK', 0);
        $builder->display();
    }

    //执行默认信任脚本
    public function followCrowd()
    {
        $step = I('get.step', '0', 'intval');
        if ($step == 1) {
            ignore_user_abort(true);
            set_time_limit(0);
            if (!is_administrator(is_login())) {
                $this->error('非管理员无法操作');
            }

            $follow = modC('DEFAULT_FOLLOW_CROWD','','Weibo');
            if (!empty($follow)) {
                $id = explode(',', $follow);
                foreach ($id as $item) {
                    $crowd = D('Weibo/WeiboCrowd')->getCrowd($item);
                    if (!empty($crowd)) {
                        $crowds[] = $crowd;
                    }
                }
            }

            if (!empty($crowds)) {
                $users = D('member')->where(array('status'=>1))->field('uid')->order('reg_time desc')->select();
                $user = array_column($users,'uid');
                foreach ($crowds as $item) {
                    foreach ($user as $v) {
                        $data['crowd_id'] = $item['id'];
                        $data['uid'] = $v;
                        $res = D('Weibo/WeiboCrowdMember')->getIsJoin($v,$item['id']);
                        if ($res == 0 ){
                            if ($item['type'] == 1) {
                                $data['status'] = 0;
                                D('Weibo/WeiboCrowd')->crowdDefaultFollow($v,$item,$data,1);
                            } elseif ($item['type'] == 0) {
                                $data['status'] = 1;
                                D('Weibo/WeiboCrowd')->crowdDefaultFollow($v,$item,$data);
                            }
                        }
                    }
                    D('Weibo/WeiboCrowd')->setMemberNum($item['id']);
                }
            }
            $this->success('执行成功');
        }
    }

    //修正圈子成员数
    public function repaireCrowdFans()
    {
        $model = D('Weibo/WeiboCrowd');
        $crowds = $model->where(array('status'=>1))->select();
        foreach ($crowds as $v) {
            $model->setMemberNum($v['id']);
        }
        $this->success(L('_SUCCESS_SETTING_'), $_SERVER['HTTP_REFERER']);
    }

    public function crowdType()
    {
        //读取数据
        $map = array('status' => array('EGT', -1));

        $model = M('WeiboCrowdType');
        $list = $model->where($map)->order('sort asc')->select();

        foreach ($list as &$v) {
            $v['crowd_count'] = M('WeiboCrowd')->where(array('type_id' => $v['id']))->count();
        }
        unset($v);
        //显示页面
        $builder = new AdminListBuilder();
        $builder
            ->title('圈子分类')
            ->buttonNew(U('Weibo/editCrowdType'))
            ->setStatusUrl(U('Weibo/setCrowdTypeStatus'))->buttonEnable()->buttonDisable()->buttonDelete()
            ->keyId()->keyLink('title', L('_TITLE_'), 'Weibo/crowd?type_id=###')
            ->keyCreateTime()->keyText('crowd_count', '圈子数')->keyStatus()->keyDoActionEdit('editCrowdType?id=###')
            ->data($list)
            ->display();
    }

    public function setCrowdTypeStatus($ids, $status)
    {
        $builder = new AdminListBuilder();

        $builder->doSetStatus('WeiboCrowdType', $ids, $status);

    }

    public function editCrowdType()
    {
        $aId = I('id', 0, 'intval');
        if (IS_POST) {
            if ($aId != 0) {
                $data = D('Weibo/WeiboCrowdType')->create();
                $res = D('Weibo/WeiboCrowdType')->save($data);
            } else {
                $data = D('Weibo/WeiboCrowdType')->create();
                $res = D('Weibo/WeiboCrowdType')->add($data);
            }
            if ($res) {
                $this->success(($aId == 0 ?  L('_ADD_'): L('_EDIT_')) . L('_SUCCESS_'));
            } else {
                $this->error(($aId == 0 ?  L('_ADD_'): L('_EDIT_')) . L('_FAIL_'));
            }

        } else {
            $builder = new AdminConfigBuilder();

            $types = D('Weibo/WeiboCrowdType')->select();
            $opt = array();
            foreach ($types as $type) {
                $opt[$type['id']] = $type['title'];
            }

            if ($aId != 0) {
                $wordCate1 = D('Weibo/WeiboCrowdType')->find($aId);
            } else {
                $wordCate1 = array('status' => 1, 'sort' => 0);
            }
            $builder->title($aId == 0 ?  '新增分类': '编辑分类')->keyId()->keyText('title', '标题')
                ->keyStatus()->keyCreateTime()
                ->data($wordCate1)
                ->buttonSubmit(U('Weibo/editCrowdType'))->buttonBack()->display();
        }
    }

    public function crowd()
    {
        $map = array();
        $type = I('get.type_id','','intval');
        $status = I('get.status','3','intval');
        if ($status == 3) {
            //筛选全部
            $map['status'] = array('egt',-1);
        } else {
            $map['status'] = $status;
        }
        if (!empty($type)) {
            $map['type_id'] = $type;
        }
        $list = D('Weibo/WeiboCrowd')->where($map)->order('create_time desc')->select();
        foreach ($list as &$v) {
            $v['allow_post'] = get_user_crowd_post($v['allow_user_post']);
        }
        unset($v);
        $attr['class'] = 'btn ajax-post';
        $attr['target-form'] = 'ids';
        $builder = new AdminListBuilder();
        $builder
            ->title('圈子')
            ->buttonNew(U('Weibo/editCrowd'))
            ->select('', 'status', 'select', '', '', '', array(array('id' => '3', 'value' => '全部'), array('id' => '2', 'value' => '待审核'),array('id' => '-1', 'value' => '已删除'),array('id' => '0', 'value' => '已禁用')))
            ->setStatusUrl(U('setCrowdStatus'))->buttonEnable()->buttonDelete()->button('允许发动态', array_merge($attr, array('url' => U('doCrowdAllowPost',array('type'=>'allow')))))->button('不允许发动态', array_merge($attr, array('url' => U('doCrowdAllowPost',array('type'=>'no')))))->ajaxButton(U('repairCrowdFollow'),null,'修正信任数',array('hide-data' => 'true'))->ajaxButton(U('repaireCrowdFans'),null,'修正圈子成员数',array('hide-data' => 'true'))->ajaxButton(U('followCrowd',array('step'=>1)),null,'执行默认信任（耗时请等待）',array('hide-data' => 'true'))
            ->keyId()->keyLink('title', L('_TITLE_'), 'Weibo/Index/index/crowd/###')
            ->keyText('allow_post','允许发动态')
            ->keyCreateTime()->keyText('member_count', '成员数')->keyStatus()->keyDoActionEdit('editCrowd?id=###')
            ->data($list)
            ->display();
    }

    public function doCrowdAllowPost($type = 'allow')
    {
        $status = 1;
        $type = $type == 'allow' ? true : false;
        if (!$type) {
            $status = -1;
        }
        $ids = I('post.ids','','intval');
        $id = array_unique((array)$ids);
        $rs = M('WeiboCrowd')->where(array('id' => array('in', $id)))->save(array('allow_user_post' => $status));
        if ($rs === false) {
            $this->error(L('_ERROR_SETTING_') . L('_PERIOD_'));
        }
        foreach ($id as $v) {
            S('crowd_by_'.$v,null);
        }
        $this->success(L('_SUCCESS_SETTING_'), $_SERVER['HTTP_REFERER']);
    }

    public function editCrowd($id = 0)
    {

        if (IS_POST) {
            $aId = I('post.id', 0, 'intval');
            $aTitle = I('post.title', '', 'text');
            $aCreateTime = I('post.create_time', 0, 'intval');
            $aStatus = I('post.status', 0, 'intval');
            $aAllowUserGroup = I('post.allow_user_group', 0, 'intval');
            $aLogo = I('post.logo', 0, 'intval');
            $aTypeId = I('post.type_id', 0, 'intval');
            $aIntro = I('post.intro', '', 'text');
            $aNotice = I('post.notice', '' , 'text');
            $aType = I('post.type', 0, 'intval');
            $aOrderType = I('post.order_type','0','intval');
            $aMemberAlias = I('post.member_alias', '', 'text');
            //微社区新增字段
            $aIsShow = I('post.is_show', 1, 'intval');
            $aSort = I('post.sort', 0, 'intval');

            $isEdit = $aId ? true : false;
            //生成数据
            $data = array('title' => $aTitle, 'create_time' => $aCreateTime, 'status' => $aStatus, 'allow_user_group' => $aAllowUserGroup, 'logo' => $aLogo, 'type_id' => $aTypeId,
                'intro' => $aIntro, 'notice' => $aNotice , 'type' => $aType, 'member_alias' => $aMemberAlias,'order_type' => $aOrderType,'is_show' =>$aIsShow,'sort' => $aSort);
            //写入数据库
            $model = M('WeiboCrowd');
            if ($isEdit) {
                $data['id'] = $aId;
                $data = $model->create($data);
                $result = $model->where(array('id' => $aId))->save($data);

            } else {
                $data = $model->create($data);
                $data['uid'] = 1;
                $result = $model->add($data);
                if (!$result) {
                    $this->error(L('_ERROR_CREATE_FAIL_'));
                }
                //添加创建者成员
                D('Weibo/WeiboCrowdMember')->addMember(array('uid' => is_login(), 'crowd_id' => $result, 'status' => 1, 'position' => 3));
                D('Weibo/WeiboCrowd')->changeCrowdNum($result,'member','inc');
                S('crowd_joined_'.is_login(),null);
            }
            S('group_list', null);
            //返回成功信息
            $this->success($isEdit ? L('_SUCCESS_EDIT_') : L('_SUCCESS_SAVE_'));
        } else {
            $aId = I('get.id', 0, 'intval');
            //判断是否为编辑模式
            $isEdit = $aId ? true : false;
            //如果是编辑模式，读取群组的属性
            if ($isEdit) {
                $group = M('WeiboCrowd')->where(array('id' => $aId))->find();
            } else {
                $group = array('create_time' => time(), 'post_count' => 0, 'status' => 1);
            }
            $groupType = D('WeiboCrowdType')->where(array('status' => 1))->limit(100)->select();
            foreach ($groupType as $k => $v) {
                $child = D('WeiboCrowdType')->where(array('status' => 1))->order('sort asc')->select();
                //获取数组中第一父级的位置
                $key_name = array_search($v, $groupType);
                foreach ($child as $key => $val) {
                    $val['title'] = '------' . $val['title'];
                    //在父级后面添加数组
                    array_splice($groupType, $key_name + 1, 0, array($val));
                }
            }
            foreach ($groupType as $type) {
                $opt[$type['id']] = $type['title'];
            }
            //显示页面
            $builder = new AdminConfigBuilder();
            $builder
                ->title($isEdit ? '编辑圈子':'新增圈子' )->data($group)
                ->keyId()->keyTitle()->keyTextArea('intro','圈子介绍' )->keyTextArea('notice','圈子公告')
                ->keyRadio('type','圈子类型' , '圈子的类型' , array(0 => '公有圈子' , 1 => '私有圈子' ))
                ->keyRadio('order_type','排序方式' , '圈内动态排序方式' , array(0 => '最新发表' , 1 => '最新回复' ))
                ->keyRadio('is_show', '是否展示在圈子首页', '前台创建的圈子默认不展示在圈子首页', array(0 => '不展示' , 1 => '展示'))->keyDefault('is_show', 1)
                ->keyText('sort', '圈子首页的排序', '值越大，圈子展示在越上面')->keyDefault('sort', 0)
                ->keySelect('type_id', L('_CATEGORY_'), '选择分类', $opt)
                ->keyStatus()
                ->keySingleImage('logo', '圈子logo', '圈子的logo,300px*300px')->keyCreateTime()
                ->buttonSubmit(U('editCrowd'))->buttonBack()
                ->display();
        }
    }

    public function setCrowdStatus($ids, $status)
    {
        $id = array_unique((array)$ids);
        //todo  可优化
        foreach ($id as $v) {
            $crowd[] =  D('Weibo/WeiboCrowd')->where(array('id'=>$v))->find();
        }
        $rs = M('WeiboCrowd')->where(array('id' => array('in', $id)))->save(array('status' => $status));
        if ($rs === false) {
            $this->error(L('_ERROR_SETTING_') . L('_PERIOD_'));
        }

        foreach ($crowd as $item) {
            //如果该圈子状态从未审核到审核，那就发送消息给圈子创建人
            if ($item['status'] == 2 && $status == 1) {
                send_message($item['uid'], '圈子审核成功', "您创建的圈子" . "【{$item['title']}】已通过审核", 'Weibo/Index/index', array('crowd' => $item['id']), is_login(), 'Weibo_crowd');
            }

            //圈子状态从正常到假删除或者禁用时，对应圈子的成员加入状态改为-1
            if ($item['status'] == 1 && ($status == -1 || $status == 0)) {
                $map = array('crowd_id'=>$item['id'],'status'=>1);
                $data = array('status'=>-1,'update_time'=>time());
                D('Weibo/WeiboCrowdMember')->setStatusByAdmin($map,$data);
            }

            if (($item['status'] == 0 || $item['status'] == -1) && $status == 1) {
                $map = array('crowd_id'=>$item['id'],'status'=>-1);
                $data = array('status'=>1,'update_time'=>time());
                D('Weibo/WeiboCrowdMember')->setStatusByAdmin($map,$data);
            }

            S('crowd_by_'.$item['id'],null);
        }

        $this->success(L('_SUCCESS_SETTING_'), $_SERVER['HTTP_REFERER']);
    }

    /**
     * 执行修复信任数脚本
     */
    public function repairCrowdFollow()
    {
        //todo 优化性能
        $model = D('Weibo/WeiboCrowdMember');
        $member = $model->where(array('status'=>1))->select();
        foreach ($member as $v) {
            $crowd = D('Weibo/WeiboCrowd')->where(array('id'=>$v['crowd_id']))->find();
            $model->where(array('crowd_id'=>$v['crowd_id']));
            if ($crowd['status'] == 0 || $crowd['status'] == -1) {
                $model->setField('status',-1);
                S('crowd_joined_'.$v['uid'],null);
            }
            if ( empty($crowd) ) {
                $model->delete();
                S('crowd_joined_'.$v['uid'],null);
            }
        }
        $this->success(L('_SUCCESS_SETTING_'), $_SERVER['HTTP_REFERER']);
    }
    /**=======================================end===================================
    ==========================================圈子=================================**/
}
