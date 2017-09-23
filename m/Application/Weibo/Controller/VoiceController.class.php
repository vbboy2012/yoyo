<?php
/**
 * Created by PhpStorm.
 * User: ning
 * Date: 17/2/10
 * Time: 10:30
 */
namespace Weibo\Controller;

use  Weibo\Sdk\JSSDK;
use  Core\Controller\FileController;

class VoiceController extends BaseController
{
    public function _initialize() {
        $this->setTitle('发语音') ;
    }
    protected $res;

    /**
     *获取微信授权登录 access_token
     * @auth nkx@ourstu.com
     * @auth andy@ourstu.com
     */
    public function index()
    {
        $wxInfo = get_wx_token();
        $wx = new JSSDK($wxInfo['app_id'],$wxInfo['token']['authorizer_access_token']);
        $this->res = $wx->getSignPackage();
        $this->assign('res', $this->res);
        $this->display('voice');
    }

    /**
     * 获取录音
     * 保存格式为amr格式
     */
    public function uploadVoliceToCloud()
    {

        $wxInfo = get_wx_token();
        $mediaId = I('post.serverId');
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.{$wxInfo['token']['authorizer_access_token']}.'&media_id={$mediaId}";
        $path=$url;
        $arr = array(
            'path' => $path
        );
        json_encode($arr);
        $this->ajaxReturn($arr);
    }

    /**
     *发布语音微博
     */
    public function send_voice()
    {
        $result=0;
        $content=I('post.voice','','string');
        $translate=I('post.voiceTranslate','','string');
        $file=new FileController();
        $cont=$file->uploadVoiceByVoice($content);
        if ($cont['data']==null){
            $this->ajaxReturn(array('result'=>'服务器错误或者空间不足,请联系服务提供商！'));
        }else{
            $res=send_weibo($cont['data'].'[]'.$translate,'voice');
            if ($res>0){
                $this->ajaxReturn(array(
                    'result'=>1,
                ));
            }else{
                $this->ajaxReturn(array(
                    'result'=>0,
                ));
            }
        }
    }
}