<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Update\Controller;
use Think\Controller;
use Think\Storage;

class IndexController extends Controller{
    protected $currentVersion = '1.5.0';

    //安装首页
    public function index(){
       if(is_file( '/Conf/user.php')){
            // 已经安装过了 执行更新程序
            //session('update',true);
            $msg = '请删除install.lock文件后再运行安装程序!';
        }else{
            $msg = '已经成功安装，请不要重复安装!';
        }
        if(Storage::has('Conf/install.lock')){
//            $this->error($msg);
        }
        


//        $db_dsn = "mysql://root:root@localhost:3306/test";
//        $ouser = new \Ucenter\Model\UserModel('user','think_',$db_dsn);
//        $aUsername = 'admin';
//        $aPassword = 'admin';
//        $sql = "insert into user values('$aUsername','$aPassword')";
//        $ouser ->query($sql);


        $this->display();
    }

    //创建锁文件并升级
    public function update()
    {
        $version = D('Update')->getCurrentVersion();
        if($version == $this->currentVersion) {
            $this->error('当前版本已经是最新版本');
        }

        if($version == '1.0.0') {
            $config = array(
                'path'     => realpath('./Data') . DIRECTORY_SEPARATOR,
                'part'     => C('DATA_BACKUP_PART_SIZE'),
                'compress' => C('DATA_BACKUP_COMPRESS'),
                'level'    => C('DATA_BACKUP_COMPRESS_LEVEL'),
            );

            //检查是否有正在执行的任务
            $lock = "{$config['path']}update_wsq.lock";

            if(is_file($lock)){
                $this->error('系统发现上次升级失败，目前无法再升级，请联系官方人员');
            } else {
                //创建锁文件
                file_put_contents($lock, NOW_TIME);
            }

            //判断data.ini写入权限
            if(!is_writable("{$config['path']}version.ini")) {
                $this->error("{$config['path']}version.ini".'文件不可写！');
            }

            //升级数据库
            $this->updb();
        }
//        $this->display();
    }

    /**
     * 升级数据库
     */
    public function updb()
    {
        $sql_path = realpath('./') . DIRECTORY_SEPARATOR . 'update.sql';
//        $sql = file_get_contents($sql_path);
        if (!file_exists($sql_path)) {
            $this->error(realpath('./') . DIRECTORY_SEPARATOR . 'update.sql文件不存在！');
        } else {
            $result = D('')->executeSqlFile($sql_path);
            if ($result) {
                //修改版本号
                if(D('Update')->setCurrentVersion($this->currentVersion)) {
                    //删除锁文件
                    unlink(realpath('./Data') . DIRECTORY_SEPARATOR . 'update_wsq.lock');
                }

                $this->success('数据库升级成功！');
            } else {
                $this->error('数据库升级失败！');
            }
        }

    }

    //安装完成
    public function complete(){
        $step = session('step');

        if(!$step){
            $this->redirect('index');
        } elseif($step != 3) {
            $this->redirect("Install/step{$step}");
        }

        // 写入安装锁定文件
        Storage::put('./Conf/install.lock', 'lock');
        if(!session('update')){
            //创建配置文件
            $this->assign('info',session('config_file'));
        }
        session('step', null);
        session('error', null);
        session('update',null);
        $this->display();
    }
}