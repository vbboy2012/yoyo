<?php

namespace Addons\QiNiu;

use Common\Controller\Addon;
// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;


class QiNiuAddon extends Addon
{
    public $info = array(
        'name' => 'QiNiu',
        'title' => '七牛云存储',
        'description' => '七牛云存储',
        'status' => 1,
        'author' => '駿濤',
        'version' => '1.4.0'
    );

    public function install()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }

    /**
     * uploadDriver  上传驱动，必需，用于确定插件是否是上传驱动
     * @return bool
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function uploadDriver()
    {
        return true;
    }

    /**
     * uploadConfig   获取上传驱动的配置
     * @return array
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function uploadConfig()
    {
        $config = $this->getConfig();
        return $uploadConfig = array(
            'accessKey' => $config['accessKey'],
            'secrectKey' => $config['secrectKey'],
            'bucket' => $config['bucket'],
            'domain' => $config['domain'],
            'upAddress' => $config['upAddress'] ? $config['upAddress'] : 'up',
            'downAddress' => $config['downAddress'] ? $config['downAddress'] : 'iovip',
            'timeout' => 3600,
        );
    }


    /**
     * uploadDealFile   处理上传参数
     * @param $file
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function uploadDealFile(&$file)
    {
        $file['qiniu_key'] = str_replace('./', '', $file['rootPath']) . $file['savepath'] . $file['savename'];
    }

    /**
     * crop  裁剪图片
     * @param $path
     * @param $crop
     * @return string
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function crop($path,$crop){
        //解析crop参数
        $crop = explode(',', $crop);
        $x = $crop[0];
        $y = $crop[1];
        $width = $crop[2];
        $height = $crop[3];
        $imageInfo = file_get_contents($path . '?imageInfo');
        $imageInfo = json_decode($imageInfo);
        //生成将单位换算成为像素
        $x = floor($x * $imageInfo->width);
        $y = floor($y * $imageInfo->height);
        $width =floor($width * $imageInfo->width);
        $height = floor($height * $imageInfo->height);

        if(strpos($path,'?') ===false){
            $new_img = $path . '?imageMogr2/crop/!' . $width . 'x' . $height . 'a' . $x . 'a' . $y;
        }else{
            $new_img = $path . '/imageMogr2/crop/!' . $width . 'x' . $height . 'a' . $x . 'a' . $y;
        }

        //返回新文件的路径
        return $new_img;
    }

    /**
     * thumb  取缩略图
     * @param $path
     * @param string $width
     * @param string $height
     * @return string
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function thumb($path,$width='',$height=''){


        if(strpos($path,'?') ===false){
            $width=$width=='auto'?'':'/w/'.$width;
            $height=$height=='auto'?'':'/h/'.$height;
            if($width && $height){
                $path = $path . '?imageView2/1'.$width.$height;
            }else{
                $path = $path . '?imageView2/2'.$width.$height;
            }

        }else{
            $path = $path . '/thumbnail/' . $width . 'x' . $height . '!';
        }



        return $path;
    }



    public function uploadRemote($url,$savePath){

        $savePath = str_replace('/', '_', $savePath);
        $config = $this->uploadConfig();
        $access_key = $config['accessKey'];
        $secret_key =  $config['secrectKey'];

        $fetch = $this->urlsafe_base64_encode($url);
        $to = $this->urlsafe_base64_encode($config['bucket'].':'.$savePath);


        $url  = 'http://'.$config['downAddress'].'.qbox.me/fetch/'. $fetch .'/to/' . $to;
        $access_token = $this->generate_access_token($access_key, $secret_key, $url);

        $header[] = 'Content-Type: application/json';
        $header[] = 'Authorization: QBox '. $access_token;
        $curl = curl_init('http://'.$config['downAddress'].'.qbox.me/fetch/'.$fetch.'/to/'.$to);
        curl_setopt($curl, CURLOPT_POST, 1);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_TIMEOUT, $config['timeout']);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curl, CURLOPT_NOBODY,1);
        $con = curl_exec($curl);
        if ($con === false) {
            return false;
            //echo 'CURL ERROR: ' . curl_error($curl);
        } else {
            return  "http://{$config['domain']}/{$savePath}";
        }


    }


    private function urlsafe_base64_encode($str){
        $find = array("+","/");
        $replace = array("-", "_");
        return str_replace($find, $replace, base64_encode($str));
    }


    private function generate_access_token($access_key, $secret_key, $url, $params = ''){
        $parsed_url = parse_url($url);
        $path = $parsed_url['path'];
        $access = $path;
        if (isset($parsed_url['query'])) {
            $access .= "?" . $parsed_url['query'];
        }
        $access .= "\n";
        if($params){
            if (is_array($params)){
                $params = http_build_query($params);
            }
            $access .= $params;
        }
        $digest = hash_hmac('sha1', $access, $secret_key, true);
        return $access_key.':'.$this->urlsafe_base64_encode($digest);
    }


    public function uploadBase64($base64,$savePath){

        //return $this->upload($base64);

        $savePath = ltrim($savePath,'/');
        $savePath = str_replace('/', '_', $savePath);
        $config = $this->uploadConfig();
        $access_key = $config['accessKey'];
        $secret_key =  $config['secrectKey'];

        $access['scope'] = $config['bucket'];
        $access['saveKey'] = $savePath;
        $access['deadline'] = time() +3600;
        $json = json_encode($access);
        $b = $this->urlsafe_base64_encode($json);
        $sign = hash_hmac('sha1',$b, $secret_key, true);
        $encodedSign = $this->urlsafe_base64_encode($sign);
        $uploadToken = $access_key . ':' . $encodedSign . ':'. $b;

        $url = 'http://'.$config['upAddress'].'.qiniu.com/putb64/-1';
        $header[] = 'Content-Type: application/octet-stream';
        $header[] = 'Authorization: UpToken '. $uploadToken;



        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_TIMEOUT, $config['timeout']);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');

        curl_setopt($curl, CURLOPT_POSTFIELDS, $base64);
        $con = curl_exec($curl);
        if ($con === false) {
            return false;
            //echo 'CURL ERROR: ' . curl_error($curl);
        } else {
            return  "http://{$config['domain']}/{$savePath}";
        }

    }


    public function water($path){
        $water_img =get_cover( modC('PICTURE_WATER_IMG', '', 'config'),'path');
        $water_img =  is_bool(strpos($water_img, 'http://')) ?  'http://'.str_replace('//','/',$_SERVER['HTTP_HOST'] .'/'. $water_img) : $water_img;
        $water_img = $this->urlsafe_base64_encode($water_img);
        if(strpos($path,'?') ===false){
            $path = $path . '?watermark/1/image/' . $water_img;
        }else{
            $path = $path . '/watermark/1/image/' . $water_img;
        }
        return $path;
    }

    /**
     * 七牛删除文件
     * @param $file_name 删除的文件名称，如:jfdjkfhdskhf.jpg
     * @return bool
     */
    public function deleteFile($file_name)
    {
        $config = $this->uploadConfig();
        $access_key = $config['accessKey'];
        $secret_key =  $config['secrectKey'];

        $entry =$config['bucket']. ':'.$file_name;
        $encodedEntryURI= $this->urlsafe_base64_encode($entry);

        $url = 'http://rs.qiniu.com/delete/'.$encodedEntryURI;
        $deleteToken = $this->generate_manage_access_token($access_key, $secret_key, $url);
        $header[] = 'Content-Type: application/x-www-form-urlencoded';
        $header[] = 'Authorization:  QBox '. $deleteToken;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_TIMEOUT, $config['timeout']);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        $con = curl_exec($curl);

        if ($con['error'] !== null) {
            return false;
        } else {
            return  true;
        }
    }

    /**
     * 七牛管理凭证生成
     * @param $access_key
     * @param $secret_key
     * @param $urlString
     * @return string
     */
    private function generate_manage_access_token($access_key,$secret_key,$urlString)
    {
        $url = parse_url($urlString);
        $data = '';
        if (array_key_exists('path', $url)) {
            $data = $url['path'];
        }
        if (array_key_exists('query', $url)) {
            $data .= '?' . $url['query'];
        }
        $data .= "\n";
        $hmac = hash_hmac('sha1', $data, $secret_key, true);
        $token=$access_key . ':' . $this->urlsafe_base64_encode($hmac);
        return $token;
    }
}