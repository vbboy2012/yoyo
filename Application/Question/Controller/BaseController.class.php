<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-5-7
 * Time: 上午9:30
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Question\Controller;


use Think\Controller;

class BaseController extends Controller{
    protected  $questionModel;
    protected $questionAnswerModel;
    protected $questionCategoryModel;
    protected $questionSupportModel;

    public function _initialize()
    {
        if(D('Common/Module')->isInstalled('Mob')) {
            $sign = modC('JUMP_MOB', 0, 'mob');
            if(is_mobile() && ($sign == 0)&& ACTION_NAME!="detail") {
                if(ACTION_NAME!="detail") {
                    redirect('Mob/Question/index');
                }else{
                    $id = I('id','0','intval') ;
                    redirect(U('Mob/Question/questionDetail',array('id'=>$id)));
                }
            }
        }

        $this->questionModel=D('Question/Question');
        $this->questionAnswerModel=D('Question/QuestionAnswer');
        $this->questionCategoryModel=D('Question/QuestionCategory');
        $this->questionSupportModel=D('Question/QuestionSupport');
    }

    protected  function _needLogin()
    {
        if(!is_login()){
            $this->error(L('_PLEASE_LOG_IN_WITH_EXCLAMATION_'));
        }
    }
} 