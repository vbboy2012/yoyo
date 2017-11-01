<?php

return array(

    'switch'=>array(//配置在表单中的键名 ,这个会是config[title]
        'title'=>'是否开启七牛云存储：',//表单的文字
        'type'=>'radio',		 //表单的类型：text、textarea、checkbox、radio、select等
        'options'=>array(
            '1'=>'启用',
            '0'=>'禁用',
        ),
        'value'=>'0',
        'tip'=>'启用时请确保其他云存储插件为禁用状态'
    ),

    'upAddress'=>array(//配置在表单中的键名 ,这个会是config[title]
        'title'=>'七牛云存储区域选择：',//表单的文字
        'type'=>'select',		 //表单的类型：text、textarea、checkbox、radio、select等
        'options'=>array(
            'up'=>'华东',
            'up-z1'=>'华北',
            'up-z2'=>'华南',
            'up-na0'=>'北美'
        ),
        'value'=>'up',
        'tip'=>'选择与自己匹配的上传存储区域'
    ),

    'downAddress'=>array(//配置在表单中的键名 ,这个会是config[title]
        'title'=>'七牛云下载区域选择：',//表单的文字
        'type'=>'select',		 //表单的类型：text、textarea、checkbox、radio、select等
        'options'=>array(
            'iovip'=>'华东',
            'iovip-z1'=>'华北',
            'iovip-z2'=>'华南',
            'iovip-na0'=>'北美'
        ),
        'value'=>'iovip',
        'tip'=>'选择与自己匹配的下载存储区域'
    ),

    'accessKey'=>array(//配置在表单中的键名 ,这个会是config[title]
        'title'=>'七牛的ak：',//表单的文字
        'type'=>'text',		 //表单的类型：text、textarea、checkbox、radio、select等
        'value'=>'',			 //表单的默认值
        'tip'=>'七牛的ak'
    ),


    'secrectKey'=>array(//配置在表单中的键名 ,这个会是config[title]
        'title'=>'七牛的sk：',//表单的文字
        'type'=>'text',		 //表单的类型：text、textarea、checkbox、radio、select等
        'value'=>'',			 //表单的默认值
        'tip'=>'七牛的sk'
    ),

    'bucket'=>array(//配置在表单中的键名 ,这个会是config[title]
        'title'=>'七牛的空间名称：',//表单的文字
        'type'=>'text',		 //表单的类型：text、textarea、checkbox、radio、select等
        'value'=>'',			 //表单的默认值
        'tip'=>'七牛的空间名称'
    ),

    'domain'=>array(//配置在表单中的键名 ,这个会是config[title]
        'title'=>'七牛的空间对应的域名：',//表单的文字
        'type'=>'text',		 //表单的类型：text、textarea、checkbox、radio、select等
        'value'=>'',			 //表单的默认值
        'tip'=>'七牛的空间对应的域名'
    ),


    'tip'=>array(
        'title'=>'OpenSNS与[<a href="http://www.qiniu.com/" target="_blank" style="color: #209361">七牛云服务</a>]达成2017年深度合作，现购买七牛云服务，输入优惠码即可享受优惠折扣。',
    ),

    'proCode'=>array(
        'title'=>'优惠码：<span style="color: #e33737">829c5ff8</span>',
    ),
);


