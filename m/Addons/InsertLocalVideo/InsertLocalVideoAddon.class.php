<?php

namespace Addons\InsertLocalVideo;

use Common\Controller\Addon;

class InsertLocalVideoAddon extends Addon
{

    public $error = '';
    public $info = array(
        'name' => 'InsertLocalVideo',
        'title' => '插入本地视频',
        'description' => '微博插入本地视频，暂时支持MP4、flv',
        'status' => 1,
        'author' => 'fan',
        'version' => '0.0.3'
    );

    public function install()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }

    //实现的InsertImage钩子方法
    public function weiboType($param)
    {
        $this->display('insertLocalVideo');
    }
    public function fetchLocalVideo($weibo)
    {
        $root=__ROOT__;
        $content=$weibo['content'];
        $position=strrpos($content,'|video:|');
        $savename=substr($content,$position+8,strlen($content)-$position);
        $map['savename']=$savename;
        $savepath=M('file')->where($map)->select();
      if($savepath[0]['driver']=='local'){
          $url = $root.$savepath[0]['savepath'].$savename; 
      }else {
          $url = $savepath[0]['savepath'];
      }
        $this->assign('savename',$savename);
        $this->assign('url',$url);
        $this->assign('weibo',$weibo);
        return $this->fetch('display');
      
    }




}