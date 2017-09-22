<?php
/**
 * Created by PhpStorm.
 * User: 路飞
 * Date: 2016/12/7
 * Time: 15:58
 */
namespace Update\Model;

use Think\Model;
use Think\Hook;

class UpdateModel extends Model
{
    protected $versionPath = './Data/version.ini';

    /**获取当前的版本号
     * @return string
     */
    public function getCurrentVersion()
    {
        $version = file_get_contents($this->versionPath);
        return $version;
    }

    /**设置当前版本号
     * @param $name 版本号
     * @return int|void
     */
    public function setCurrentVersion($name)
    {
        return file_put_contents($this->versionPath, $name);
    }
}