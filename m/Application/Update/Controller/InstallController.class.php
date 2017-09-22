<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Install\Controller;
use Think\Controller;
use Think\Db;
use Think\Storage;

class InstallController extends Controller{

    protected function _initialize(){
        if(Storage::has( 'Conf/install.lock')){
            $this->error('已经成功安装，请不要重复安装!');
        }
    }

    //安装第一步，检测运行所需的环境设置
    public function step1(){
        session('error', false);

        //环境检测
        $env = check_env();

        //目录文件读写检测
        if(IS_WRITE){
            $dirfile = check_dirfile();
            $this->assign('dirfile', $dirfile);
        }

        //函数检测
        $func = check_func();

        session('step', 1);

        $this->assign('env', $env);
        $this->assign('func', $func);
        $this->display();
    }

    //安装第二步，创建数据库
    public function step2(){
                if(session('error')){
                    $this->error('环境检测没有通过，请调整环境后重试！');

                    $step = session('step');
                    if($step != 1 && $step != 2){
                        // $this->redirect('step1');
                    }

                    session('step', 2);
                    $this->display();
                } else{
                    $this->redirect('step3');
                }
    }

    //安装第三步，安装数据表，创建配置文件
    public function step3(){
       /* if(session('step') != 2){
            $this->redirect('step2');
        }*/

        $this->display();


            //连接数据库
            $dbconfig = cookie('db_config');
            $db = Db::getInstance($dbconfig);
            //创建数据表

            create_tables($db, $dbconfig['DB_PREFIX']);
            //注册创始人帐号
            //$auth  = build_auth_key();
           // $admin = session('admin_info');
           // register_administrator($db, $dbconfig['DB_PREFIX'], $admin, $auth);

            //创建配置文件
         /*   $conf   =   write_config($dbconfig, $auth);
            session('config_file',$conf);*/


        if(session('error')){
            //show_msg();
        } else {
            session('step', 3);

            echo "<script type=\"text/javascript\">setTimeout(function(){location.href='".U('Index/complete')."'},5000)</script>";
            ob_flush();
            flush();
           //$this->redirect('Index/complete');
        }
    }

    public function error($info,$title='很遗憾，安装失败，失败原因'){
        $this->assign('info',$info);// 提示信息
        $this->assign('title',$title);
        $this->display('error');exit;
    }
}