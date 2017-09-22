<?php
// +----------------------------------------------------------------------
// | i友街 [ 新生代贵州网购社区 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.iyo9.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: i友街 <iyo9@iyo9.com> <http://www.iyo9.com>
// +----------------------------------------------------------------------
// 

/**
 * 中国省市区三级联动插件
 * @author i友街
 */

namespace Addons\InsertXiami\Controller;
use Home\Controller\AddonsController;

class XiamiController extends AddonsController{
   public function searchMusic(){
       $aPage = I('post.page',1,'intval');
       $aKey = I('post.key','','text');
       if(empty($aKey)){
           $return['status']=-1;
           $this->ajaxReturn($return);
       }
       $aKey = urlencode($aKey);
       $arr = $this->getMusic($aKey,$aPage);
       $next = $this->getMusic($aKey,$aPage+1);
       if(empty($arr)){
           $return['status']=0;
       }else{
           $return =  array('status'=>1,'data'=>$arr,'next'=>count($next));
       }
       $this->ajaxReturn($return);
   }


    private function getMusic($key,$page){
        $result = S('xiami_search_'.$key.'_'.$page);
        if(empty($result)){
            $url=   'http://www.xiami.com/web/search-songs?key='.$key.'&page='.$page;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_USERAGENT, '');
            curl_setopt($ch, CURLOPT_REFERER,'b');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $content = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($content,true);
            S('xiami_search_'.$key.'_'.$page,$result,60*60);
        }
      //  dump($result);exit;
        return $result;

    }


    public function parseXiami()
    {
        $id = I('post.id', '', 'intval');
        $data = $this->getXiaMiUrl($id);
        if($data) {
            $location = $this->ipcxiami($data['location']);
            $this->ajaxReturn(array('status' => 1, 'src' => $location));
        }
    }

    //解析虾米
    public function ipcxiami($location){
        $count = (int)substr($location, 0, 1);
        $url = substr($location, 1);
        $line = floor(strlen($url) / $count);
        $loc_5 = strlen($url) % $count;
        $loc_6 = array();
        $loc_7 = 0;
        $loc_8 = '';
        $loc_9 = '';
        $loc_10 = '';
        while ($loc_7 < $loc_5){
            $loc_6[$loc_7] = substr($url, ($line+1)*$loc_7, $line+1);
            $loc_7++;
        }
        $loc_7 = $loc_5;
        while($loc_7 < $count){
            $loc_6[$loc_7] = substr($url, $line * ($loc_7 - $loc_5) + ($line + 1) * $loc_5, $line);
            $loc_7++;
        }
        $loc_7 = 0;
        while ($loc_7 < strlen($loc_6[0])){
            $loc_10 = 0;
            while ($loc_10 < count($loc_6)){
                $loc_8 .= @$loc_6[$loc_10][$loc_7];
                $loc_10++;
            }
            $loc_7++;
        }
        $loc_9 = str_replace('^', 0, urldecode($loc_8));
        return $loc_9;
    }

    public function getXiaMiUrl($ID){
        if(!empty($ID)){
            $url= 'http://www.xiami.com/widget/json-single/sid/'.$ID;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_USERAGENT, '');
            curl_setopt($ch, CURLOPT_REFERER,'b');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $content = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($content,true);
            return $result;
        }else{
            return false;
        }
    }
}