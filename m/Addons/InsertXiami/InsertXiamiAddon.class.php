<?php

namespace Addons\InsertXiami;

use Common\Controller\Addon;

class InsertXiamiAddon extends Addon
{

    public $info = array(
        'name' => 'InsertXiami',
        'title' => '插入虾米音乐',
        'description' => '微博插入虾米音乐',
        'status' => 1,
        'author' => '駿濤',
        'version' => '1.1.0'
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
        $this->display('insertXiami');
    }

    public function fetchXiami($weibo)
    {
        $weibo_data = unserialize($weibo['data']);
        $this->assign('weibo',$weibo);
        $this->assign('weibo_data',$weibo_data);
        return $this->fetch('display');
    }

    public function fetchMobileXiami($weibo)
    {
        $weibo_data = unserialize($weibo['data']);
        dump(2);
        $this->assign('weibo',$weibo);
        $this->assign('weibo_data',$weibo_data);
        return $this->fetch('mobiledisplay');
    }


    public function parseExtra(&$extra = array()){


        foreach ($extra as $key => $v) {
            if (empty($v)) {
                $this->error = '参数不能为空！';
                return false;
            }
        }


        $extra['title'] = text($extra['title']);
        $extra['id'] = text($extra['id']);
        $extra['author'] = text($extra['author']);
        $extra['cover'] = text($extra['cover']);
        return $extra;
    }

}