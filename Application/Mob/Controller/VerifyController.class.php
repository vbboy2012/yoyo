<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-11
 * Time: PM3:40
 */

namespace Mob\Controller;

use Think\Controller;

class VerifyController extends Controller
{


    /**
     * sendVerify 发送验证码
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function sendVerify()
    {
        $aAccount = $cUsername = I('post.account', '', 'op_t');
        $aType = I('post.type', '', 'op_t');
        $aType = $aType == 'mobile' ? 'mobile' : 'email';
        $aAction = I('post.action', 'config', 'op_t');

        if (!check_reg_type($aType)) {
            $str = $aType == 'mobile' ? L('PHONE') : L('EMAIL');
            $this->error($str . L('_ERROR_OPTIONS_CLOSED_').L('_EXCLAMATION_'));
        }


        if (empty($aAccount)) {
            $this->error(L('_ERROR_ACCOUNT_CANNOT_EMPTY_'));
        }

        check_username($cUsername, $cEmail, $cMobile);
        $time = time();
        if($aType == 'mobile'){
            $resend_time =  modC('SMS_RESEND','60','USERCONFIG');
            if($time <= session('verify_time')+$resend_time ){
                $this->error(L('_ERROR_WAIT_1_').($resend_time-($time-session('verify_time'))).L('_ERROR_WAIT_2_'));
            }
        }


        if ($aType == 'email' && empty($cEmail)) {
            $this->error(L('_ERROR__EMAIL_'));
        }
        if ($aType == 'mobile' && empty($cMobile)) {
            $this->error(L('_ERROR_PHONE_'));
        }

/*        $checkIsExist = UCenterMember()->where(array($aType => $aAccount))->find();
        if ($checkIsExist) {
            $str = $aType == 'mobile' ? L('PHONE') : L('EMAIL');
            $this->error(L('_ERROR_USED_1_') . $str . L('_ERROR_USED_2_').L('EXCLAMATION'));
        }*/
        $mobVerify=M('Mob/Verify')->where(array('type'=>$aType,'account'=>$aAccount))->find();
        if((time()-$mobVerify['create_time'])<60){
            $this->error('操作频繁，60秒可再次发送');
        }
        $verify = D('Verify')->addVerify($aAccount, $aType);
        if (!$verify) {
            $this->error(L('_ERROR_FAIL_SEND_').L('_EXCLAMATION_'));
        }

        $res =  A(ucfirst($aAction))->doSendVerify($aAccount, $verify, $aType);
        if ($res === true) {
            if($aType == 'mobile'){
                session('verify_time',$time);
            }
            $this->success(L('_ERROR_SUCCESS_SEND_'));
        } else {
            $this->error($res);
        }

    }
    /**
     * checkAccount  ajax验证用户帐号是否符合要求
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function checkAccount()
    {
        $aAccount = I('post.account', '', 'op_t');
        $aType = I('post.type', '', 'op_t');
        if (empty($aAccount)) {
            $this->ajaxReturn(-3);//还未配置邮箱验证 -3
        }
        check_username($aAccount, $email, $mobile, $aUnType);
        $mUcenter = UCenterMember();
        switch ($aType) {
            case 'username':
                empty($aAccount) && $this->error(L('_ERROR_USERNAME_FORMAT_').L('_EXCLAMATION_'));
                $length = mb_strlen($aAccount, 'utf-8'); // 当前数据长度
                if ($length < modC('USERNAME_MIN_LENGTH',2,'USERCONFIG') || $length > modC('USERNAME_MAX_LENGTH',32,'USERCONFIG')) {
                    $this->error(L('_ERROR_USERNAME_LENGTH_1_').modC('USERNAME_MIN_LENGTH',2,'USERCONFIG').'-'.modC('USERNAME_MAX_LENGTH',32,'USERCONFIG').L('_ERROR_USERNAME_LENGTH_2_'));
                }


                $id = $mUcenter->where(array('username' => $aAccount))->getField('id');
                if ($id) {
                    $this->error(L('_ERROR_USERNAME_EXIST_2_'));
                }
                preg_match("/^[a-zA-Z0-9_]{".modC('USERNAME_MIN_LENGTH',2,'USERCONFIG').",".modC('USERNAME_MAX_LENGTH',32,'USERCONFIG')."}$/", $aAccount, $result);
                if (!$result) {
                    $this->error(L('_ERROR_USERNAME_ONLY_PERMISSION_'));
                }
                break;
            case 'email':
                empty($email) && $this->ajaxReturn(-2);//邮箱格式错误
                $length = mb_strlen($email, 'utf-8'); // 当前数据长度
                if ($length < 4 || $length > 32) {
                    $this->ajaxReturn(-1);//邮箱已注册
                }

                $id = $mUcenter->where(array('email' => $email))->getField('id');
                if ($id) {
//                    $this->error(L('_ERROR_EMAIL_LENGTH_LIMIT_'));
                    $this->ajaxReturn(-1);//邮箱已注册
                }
                break;
            case 'mobile':
                empty($mobile) && $this->ajaxReturn(-4);//手机格式错误
                $id = $mUcenter->where(array('mobile' => $mobile))->getField('id');
                if ($id) {
                    $this->ajaxReturn(-5);//手机已注册
                }
                break;
        }
        $this->ajaxReturn(1);//验证成功
    }

}