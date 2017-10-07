<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
<title><?php echo ($mob_seo['title']); ?> | <?php echo modC('WEBSITE_NAME','OpenSNS','Mob');?></title>
<meta name="keywords" content="<?php echo ($mob_seo['keywords']); ?>">
<!--<meta name="description" content="<?php echo ($mob_seo['description']); ?>">-->

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp"/>

<script src="/yoyo/Application/Mob/Static/js/jquery.min.js"></script>
<script src="/yoyo/Application/Mob/Static/js/amazeui.min.js"></script>
<script src="/yoyo/Public/js/com/com.functions.js"></script>
<script src="/yoyo/Application/Mob/Static/js/toast.js"></script>
<script src="/yoyo/Application/Mob/Static/js/apps/base.js"></script>


<script src="/yoyo/Public/js/ext/magnific/jquery.magnific-popup.min.js"></script>
<script src="/yoyo/Application/Mob/Static/js/toastr/toastr.min.js"></script>
<script src="/yoyo/Application/Mob/Static/js/toast.js"></script>
<script src="/yoyo/Application/Mob/Static/js/dist/lrz.mobile.min.js"></script>

<link rel="stylesheet" href="/yoyo/Application/Mob/Static/css/amazeui.min.css"/>



<script src="/yoyo/Application/Mob/Static/js/jquery.simple-color.js"></script>
<script src="__BTS__/plugins/zui.js"></script>
<script src="/yoyo/Application/Mob/Static/js/channel.js"></script>
<link href="/yoyo/Public/static/os-icon/select-os-icon.css" rel="stylesheet">
<script src="/yoyo/Public/static/os-icon/select-os-icon.js"></script>
<link rel="stylesheet" href="/yoyo/Application/Mob/Static/css/app.css"/>
<link rel="stylesheet" href="/yoyo/Application/Mob/Static/js/toastr/toastr.min.css" />
<link rel="stylesheet" href="/yoyo/Application/Mob/Static/js/diyUpload.css"/>
<link rel="stylesheet" href="/yoyo/Application/Mob/Static/css/apps/core.css"/>
<link rel="stylesheet" href="/yoyo/Public/zui/css/zui.css"/>
<link rel="stylesheet" href="/yoyo/Public/js/ext/magnific/magnific-popup.css"/>
<link rel="alternate icon" type="image/png" href="assets/i/favicon.png">
<link href="/yoyo/Public/static/os-icon/select-os-icon.css" rel="stylesheet">
<link href="/yoyo/Public/static/os-icon/simple-line-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="//at.alicdn.com/t/font_qhoj73h4c3bd42t9.css">
<!--用于加载顶部css和js-->
<?php $config = api('Config/lists'); C($config); $count_code=C('_MOB_STATISTICALCODE'); if(empty($count_code)){ $count_code=C('COUNT_CODE'); } ?>

<script type="text/javascript">
    //全局内容的定义
    var _ROOT_ = "/yoyo";
    var MID = "<?php echo is_login();?>";
    var MODULE_NAME="<?php echo MODULE_NAME; ?>";
    var ACTION_NAME="<?php echo ACTION_NAME; ?>";
    var CONTROLLER_NAME ="<?php echo CONTROLLER_NAME; ?>";
    var ThinkPHP = window.Think = {
        "ROOT": "/yoyo", //当前网站地址
        "APP": "/yoyo/index.php?s=", //当前项目地址
        "PUBLIC": "/yoyo/Public", //项目公共目录地址
        "DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
        "MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
        "VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"],
        'URL_MODEL': "<?php echo C('URL_MODEL');?>",
        'WEIBO_ID': "<?php echo C('SHARE_WEIBO_ID');?>"
    }

    var weibo_comment_order = "<?php echo modC('COMMENT_ORDER',0,'WEIBO');?>";
</script>
<script>
    //全局内容的定义
    var _LOADING_ = "/yoyo/Application/Mob/Static/images/loading.jpg";
</script>
    </head>
    <body>
        <!-- 头部 -->
        <?php D('Member')->need_login(); ?>
<style>
    .header {
        text-align: center;
    }

    .header h1 {
        font-size: 200%;
        color: #333;
        margin-top: 30px;
    }

    .header p {
        font-size: 14px;
    }
/*    .emot_content{
        width: 100%;
        margin: 0 auto;
        height: 300px;
        position: absolute;
        bottom: 45px;
    }*/
</style>
<div data-am-widget="header" class="am-header am-header-default am-header-fixed am-cf">
    <?php if(!empty($top_menu_list["left"])): ?><div class="am-header-left am-header-nav">
            <?php if(is_array($top_menu_list["left"])): $i = 0; $__LIST__ = $top_menu_list["left"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i; switch($menu["type"]): case "home": ?><a href="<?php echo ($menu["href"]); ?>" class="am-fl <?php echo ($menu["a_class"]); ?>">
                            <i class="am-header-icon am-icon-home <?php echo ($menu["i_class"]); ?>" ></i><?php echo ($menu["html"]); ?>
                        </a><?php break;?>
                    <?php case "back": ?><a id="goback" need-confirm="<?php echo ($menu["need_confirm"]); ?>" confirm-info="<?php echo ($menu["confirm_info"]); ?>"
                           class="<?php echo ($menu["a_class"]); ?>">
                            <span class="am-icon-chevron-left <?php echo ($menu["span_class"]); ?>"></span>
                        </a><?php break;?>
                    <?php case "message": if (is_login()) { ?>
                        <?php echo W('Mob/Message/index');?>
                        <?php } break; endswitch; endforeach; endif; else: echo "" ;endif; ?>
        </div><?php endif; ?>

    <h1 class="am-header-title <?php echo ($top_menu_list["center"]["h1_class"]); ?>" style="margin: 11px 30%!important;">
        <i class="<?php echo ($top_menu_list["center"]["i_class"]); ?>"></i>
        <?php echo ((isset($top_menu_list["center"]["title"]) && ($top_menu_list["center"]["title"] !== ""))?($top_menu_list["center"]["title"]):"首页"); ?>
    </h1>
    <?php if(!empty($top_menu_list["right"])): ?><div class="am-header-right am-header-nav">
            <?php if(is_array($top_menu_list["right"])): $i = 0; $__LIST__ = $top_menu_list["right"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$right): $mod = ($i % 2 );++$i; switch($right["type"]): case "edit": if(isset($right['href'])): ?><a href="<?php echo ($right['href']); ?>" class="am-fl <?php echo ($right["a_class"]); ?>">
                                <i class="am-header-icon am-icon-pencil <?php echo ($right["i_class"]); ?>"></i>
                            </a>
                            <?php else: ?>
                            <script>
                                var tip_info="<?php echo ($right['info']); ?>";
                            </script>
                            <a class="am-fl <?php echo ($right['a_class']); ?>" href="javascript:void(0);" onclick="toast.error(tip_info)">
                                <i class="am-header-icon am-icon-pencil <?php echo ($right["i_class"]); ?>"></i>
                            </a><?php endif; break; endswitch; endforeach; endif; else: echo "" ;endif; ?>
            <a href="#" class="">
            </a>
        </div><?php endif; ?>
</div>
<?php if(CONTROLLER_NAME != 'Index'): ?><link href="/yoyo/Application/Mob/Static/js/toastr/toastr.min.css" rel="stylesheet"/>

<div id="demo-view" class="" data-backend-compiled="" style="z-index: 1040">
    <nav data-am-widget="menu" class="am-menu am-menu-offcanvas1 am-no-layout" data-am-menu-offcanvas=""><a
           class="am-menu-toggle person"><i class="am-menu-toggle-icon am-icon-bars" data-open="1"></i></a>

        <div style="touch-action: pan-y; -webkit-user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
            <div class="am-offcanvas-bar am-offcanvas-bar-flip"  style="margin-top: 38px">
                <div class="am-text-center" id="user" >
                    <?php $user=query_user(array('nickname','avatar64')); ?>
                    <?php $map = array('status' => array('gt', -1), 'pid' => 0,'url'=>array('eq','mob/user/index')); $list=D('MobChannel')->where($map)->find(); if($list){ ?>
                    <a style="text-indent: 0px;" href="<?php echo U('mob/user/index');?>">
                        <img class="avatar-img" style="border-radius: 100%;width:64px;margin-top:20px "
                             src="<?php echo ($user["avatar64"]); ?>">
                    </a><?php }else{ ?>
                    <a style="text-indent: 0px;" >
                        <img class="avatar-img" style="border-radius: 100%;width:64px;margin-top:20px "
                             src="<?php echo ($user["avatar64"]); ?>">
                    </a>
                    <?php } ?>

                    <div class="am-text-center">
                        <a href="<?php echo U('Mob/User/index',array('uid'=>$vo['user']['uid']));?>" class="am-text-truncate" style="color: #fff; width: 81px;display: block;margin: 0 auto;"><?php echo ($user["nickname"]); ?></a>
                    </div>
                </div>
                <div class="am-text-center" id="signin"
                     style="display: block;font-size: 18px ; height: 44px;line-height: 44px;">
                    <a href="<?php echo U('mob/Member/index');?>">
                        <i class="am-icon-sign-in"></i>
                        &nbsp;&nbsp;&nbsp;登录
                    </a>
                </div>
                <ul class="am-menu-nav am-avg-sm-1 am-text-center">

                    <?php $map = array('status' => array('gt', -1), 'pid' => 0,'url'=>array('neq','mob/user/index')); $list=D('MobChannel')->where($map)->select(); foreach($list as &$v ){ $v['url']=U($v['url']); } ?>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                            <a style="text-indent: 0;" href="<?php echo ($vo["url"]); ?>">
                                <i class="os-icon-<?php echo ($vo["icon"]); ?>"></i>
                                &nbsp;&nbsp;&nbsp;<?php echo ($vo["title"]); ?>
                            </a>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>



                    <?php if (is_login()) { ?>

                    <li>
                        <a style="text-indent: 0;" href="<?php echo U('message/index');?>"><i class="os-icon-bell"></i>
                            消息
                        </a>
                    </li>

                    <?php } ?>
                </ul>
                <div class="am-text-center" id="signout" style="display: none;padding: 5px 0">
                    <a>
                        <i class="am-icon-sign-out"></i>
                        退出
                    </a>
                </div>
            </div>
        </div>
    </nav>
</div>
<script src="/yoyo/Application/Mob/Static/js/toastr/toastr.min.js"></script>
<script src="/yoyo/Application/Mob/Static/js/toast.js"></script>
<script>
    var uid = '<?php echo is_login();?>';
    $(document).ready(function () {
        signout();
        if (uid > 0) {
            $('#user').show();
            $('#signin').hide();
            $('#signout').show();
        } else {
            $('#user').hide();
            $('#signin').show();
            $('#signout').hide();
        }

    });

    var signout = function () {
        $('#signout').click(function () {
            $.post("<?php echo U('Mob/Member/logout');?>", {}, function (msg) {
                if (msg.status == 1) {
                    setTimeout(function () {
                        window.location.reload();
                    }, 2000);
                    toast.success('退出成功!');
                } else {
                    toast.error(msg.info);
                }
            }, 'json')
        });
    }
</script>
<script>
    $(document).ready(function () {
        var flag=true;
        var len="160";

        $("#mob-main-container").css({"position":"relative","top":"0","left":"0px"});
        $(".userinfo").css({"width":len+"px","right":"-"+len+"px"});

        $('.person').unbind('click');
        $('.person').bind('click', function () {

            if(!flag) return;
            flag=false;
            var lei_i=$(this).children()[0];
            var lei=$(lei_i).attr("data-open");

            if(lei==1){
                $("#mob-main-container").animate({"left":"-"+len+"px"},160);
                $(".am-offcanvas-bar-flip").animate({"right":"160"},160,function () {
                    flag=true;
                });
                $($(this).children()[0]).attr('data-open','2');
            }else{

                $("#mob-main-container").animate({"left":"0"},160);
                $(".am-offcanvas-bar-flip").animate({"right":"-"+160+"px"},160,function () {
                    flag=true;
                });
                $($(this).children()[0]).attr('data-open','1');
            }
        });
    });
</script><?php endif; ?>




        <!-- /头部 -->

        <!-- 主体 -->
        

<div id="mob-main-container" class="container main">
   
    <script src="/yoyo/Application/Mob/Static/js/weibo.js"></script>
    <link rel="stylesheet" href="<?php echo getRootUrl();?>Addons/InsertXiami/_static/css/xiami.css">
    <link rel="stylesheet" href="/yoyo/Application/Mob/Static/css/apps/weibo.css"/>
    <link rel="stylesheet" href="/yoyo/Application/Mob/Static/css/apps/nav.css"/>
    <div class="weibo-container" style="padding-bottom: 60px">
        <?php if(!empty($crowd_detail)): ?><ul class="crowd-box">
                <li class="crowd-list">
                    <div class="am-cf am-g info-box">
                        <div class="avatar am-u-sm-3 am-text-center">
                            <a href="<?php echo U('Mob/Weibo/index', array('crowd_id'=>$crowd_detail['id']));?>">
                                <img src="<?php echo (getthumbimagebyid($crowd_detail["logo"],160,160)); ?>" style="width: 80px;height: 80px">
                            </a>
                        </div>
                        <div class="info am-u-sm-6">
                            <a href="<?php echo U('Mob/Weibo/index', array('crowd_id'=>$crowd_detail['id']));?>">
                                <div><h4 class="info-title"><?php echo ($crowd_detail["title"]); ?></h4></div>
                                <div><span class="infos"><?php echo ($crowd_detail["intro"]); ?></span></div>
                                <div><span class="infos">成员 <?php echo ($crowd_detail["member_count"]); ?></span>&nbsp;&nbsp;&nbsp;<span class="infos">讨论 <?php echo ($crowd_detail["post_count"]); ?></span></div>
                            </a>
                        </div>
                        <div class="am-u-sm-3">
                            <?php if($crowd_detail["is_admin"] == 'true'): ?><a href="<?php echo U('Mob/Weibo/crowdManager', array('id' => $crowd_detail['id']));?>" class="btn-follow">
                                    管理
                                </a>
                                <?php else: ?>
                                <?php switch($crowd_detail["is_follow"]): case "0": ?><a href="javascript:" class="btn-follow" <?php if(($data["need_pay"]) > "0"): ?>data-toggle="modal" data-target="#myModal<?php echo ($crowd_detail["id"]); ?>"<?php else: ?>data-role="follow_crowd" data-id="<?php echo ($crowd_detail["id"]); ?>"<?php endif; ?> >
                                        加入
                                        </a><?php break;?>
                                    <?php case "1": ?><a href="javascript:" class="btn-follow" data-role="unfollow_crowd" data-id="<?php echo ($crowd_detail["id"]); ?>">
                                            已加入
                                        </a><?php break;?>
                                    <?php case "2": ?><a href="javascript:" class="btn-follow" data-id="<?php echo ($crowd_detail["id"]); ?>">
                                            待审核
                                        </a><?php break;?>
                                    <?php case "-2": ?><a href="javascript:" class="btn-follow" <?php if(($data["need_pay"]) > "0"): ?>data-toggle="modal" data-target="#inviteModal<?php echo ($crowd_detail["id"]); ?>"<?php else: ?>data-role="follow_crowd" data-id="<?php echo ($crowd_detail["id"]); ?>"<?php endif; ?> >
                                        被邀请
                                        </a><?php break;?>
                                    <?php default: ?>
                                    <a href="javascript:" class="btn-follow" data-role="follow_crowd" data-id="<?php echo ($crowd_detail["id"]); ?>">
                                        加入
                                    </a><?php endswitch; endif; ?>

                        </div>
                    </div>
                </li>
                <h4 class="notice"><?php echo ($crowd_detail["notice"]); ?></h4>
            </ul>
            <!--<div id="crowd_top_list">
                <?php if(is_array($crowd_top_list)): $i = 0; $__LIST__ = $crowd_top_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i; echo W('WeiboDetail/detail',array('weibo_id'=>$top,'can_hide'=>1)); endforeach; endif; else: echo "" ;endif; ?>
            </div>--><?php endif; ?>
        <?php if($invisible == 1): ?><p class="private-crowd-toast">该圈子已设为私密,您需加入后才能浏览</p>
            <?php else: ?>
            <ul class="weibo-list list ulclass" id="article_list_ul">

                <?php if(!empty($weibo)): if(is_array($weibo)): $i = 0; $__LIST__ = $weibo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vl): $mod = ($i % 2 );++$i;?><li class="vertical item " style="overflow: hidden">
        <div class="am-g avatar_img" style="padding: .5rem;position: relative">
            <div class="avatar am-fl">
                <img class="avatar-img" src="<?php echo ($vl["user"]["avatar64"]); ?>">
            </div>
            <div class=" nickname am-fl">
                <p style="margin-bottom: 10px"><a href="<?php echo U('Mob/User/index',array('uid'=>$vl['user']['uid']));?>"><?php echo ($vl["user"]["nickname"]); ?></a>
                    <?php echo W('Common/UserRank/render',array($vl['user']['uid']));?></p>
                <p><span class="from">  <?php echo (friendlydate($vl["create_time"])); ?></span></p>

            </div>
            <div class=" time am-fr" style="position: absolute;bottom: 6px;right: 0.5rem;color: #999">
                <?php if(empty($vl["crowd_id"])): ?>来自：<?php echo ($vl["from"]); ?>
                    <?php else: ?>
                    <span class="where"><img src="<?php echo (getthumbimagebyid($vl['crowd_logo'],16,16)); ?>" style="width: 16px;height: 16px"/><a href="<?php echo U('Mob/Weibo/index',array('crowd_id'=>$vl['crowd_id']));?>" style="color: #19bca1"><b><?php echo ($vl["crowd_title"]); ?></b></a></span><?php endif; ?>

            </div>
        </div>

        <?php if($vl['is_img'] == 0): ?><div class="am-g content" style="padding: .5rem">
                <p><?php echo ($vl["content"]); ?></p>
            </div><?php endif; ?>
        <?php if($vl['is_img'] == 1): ?><div class="am-g content">
                <p><?php echo ($vl["content"]); ?></p>

                <div class="img-content am-cf am-avg-sm-3 ">
                    <!--源微博图片显示-->
                    <?php foreach($vl['img_path'] as $key_img => $val_img){ ?>
                    <div href=" <?php echo ($vl['sourceId_img_big'][$key_img]); ?>" style="padding: 1px;float: left;">
                        <img class="img-big" style="width: 100px;height: 100px" src="<?php echo ($vl['sourceId_img_path'][$key_img]); ?>">
                    </div>
                    <?php } ?>
                    <!--源微博图片显示END-->
                </div>

            </div><?php endif; ?>

        <!--    <div>
                <?php if($vl['is_sourceId'] == 1): ?><a >

                        <div class="am-cf"
                             style="border: 1px solid #e8e8e8;padding: 10px;margin-bottom: 20px; border-radius: 6px;background: #f1f1f1">
                            <?php if(is_null($vl['sourceId_content'])){ ?>
                            <span>原微博已删除</span>
                            <?php }else{ ?>
                            <div>
                                <a href="<?php echo U('Mob/User/index',array('uid'=>$vl['sourceId_user']['uid']));?>"
                                   data-hasqtip="75">@<?php echo ($vl["sourceId_user"]["nickname"]); ?></a></div>
                            <p class="word-wrap"><?php echo ($vl["sourceId_content"]); ?></p>


                            <?php if($vl['sourceId_is_img'] == 1): ?><div class=" img-content am-cf am-avg-sm-3">
                                    &lt;!&ndash;转发后图片显示&ndash;&gt;
                                    <?php foreach($vl['sourceId_img_path'] as $key_img => $val_img){ ?>
                                    <div style="padding: 1px;float: left;
          " href=" <?php echo ($vl['sourceId_img_big'][$key_img]); ?>">

                                        <img class="img-big" style="width: 100px;height: 100px"
                                             src=" <?php echo ($vl['sourceId_img_path'][$key_img]); ?>">

                                    </div>
                                    <?php } ?>
                                    &lt;!&ndash;转发后图片显示END&ndash;&gt;
                                </div><?php endif; ?>

                                        <span class="text-primary pull-right" style="font-size: 12px;"><a
                                                href="<?php echo U('Mob/Weibo/weiboDetail',array('id'=>$vl['sourceId']));?>">
                                            原微博转发（<?php echo ($vl["sourceId_repost_count"]["repost_count"]); ?>）</a>  </span>&nbsp;
                            <span class="text-primary pull-left" style="font-size: 12px;">
                                <a href="">来自：<?php echo ($vl["sourceId_from"]); ?></a>  </span>

                            <?php } ?>
                        </div>
                    </a><?php endif; ?>
            </div>-->

        <!--转发后的内容-结束-->
        <div class="am-g" style="border-top: 1px solid #eee;border-bottom: 1px solid #eee">
            <div class="am-u-sm-4 am-text-center refresh" style="padding: .5rem 0;">
                <div style="border-right: 1px solid #eee">
                    <a class="support" weibo_id="<?php echo ($vl['id']); ?>" user_id="<?php echo ($vl['uid']); ?>" url="<?php echo U('Mob/Weibo/support');?>">
                        <?php if($vl['is_support'] == 1): ?><i class="am-icon-thumbs-up" style="color: #ccc"></i>
                            <?php else: ?>
                            <i class="am-icon-thumbs-o-up" style="color: #ccc"></i><?php endif; ?>
                    </a>
                    <span style="color: #ccc"><?php echo ($vl["support"]); ?></span>
                </div>
            </div>
            <div class="am-u-sm-4 am-text-center " style="padding: .5rem 0;">
                <div style="border-right: 1px solid #eee">
                    <?php if (!is_login()) { ?>
                    <a href="javascript:void(0);" onclick="toast.error('请登陆后再进行操作');" style="color: #999999">
                        <i class="am-icon-share" style="color: #ccc"></i> </a>
                    <span style="color: #ccc"><?php echo ($vl["repost_count"]); ?></span>

                    <?php }else{ if (!check_auth('Weibo/Index/doSendRepost')) { ?>
                    <a href="javascript:void(0);" onclick="toast.error('您无微博转发权限');">
                        <i class="am-icon-share" style="color: #ccc"></i> </a> <span style="color: #ccc"><?php echo ($vl["repost_count"]); ?></span>

                    <?php }else{ ?>

                    </php>
                    <a class="forward " href="<?php echo U('Mob/Weibo/forward',array('id'=>$vl['id'],'uid'=>$vl['uid']));?>">
                        <i class="am-icon-share" style="color: #ccc"></i> </a> <span style="color: #ccc"><?php echo ($vl["repost_count"]); ?></span>
                    <?php }} ?>
                </div>
            </div>

            <div class="am-u-sm-4 am-text-center" style="padding: .5rem 0;">
                <div style="">
                    <a data-target="section" href="<?php echo U('Mob/weibo/weibodetail',array('id'=>$vl['id']));?>" style="color: #ccc">
                        <i class="am-icon-comment-o" style="color: #ccc"></i>
                        <?php echo ($vl["comment_count"]); ?>
                    </a>
                </div>
            </div>
        </div>
    </li><?php endforeach; endif; else: echo "" ;endif; ?>


                    <?php else: ?>
                    <div class="am-text-center" style="background-color:#FFFFFF;margin-top: -15px"><img
                            src="/yoyo/Application/Mob/Static/images/tip.png" style="width: 100%;height: 100%">
                    </div><?php endif; ?>

            </ul>
            <?php if(!empty($weibo)): if($pid['count'] == 1): ?><if condition="$pid['is_allweibo'] eq 1">
                        <div class="am-list-news-ft look-more">
                            <a id="getmore" data-url="<?php echo U('Mob/Weibo/addMoreWeibo');?>"
                            >查看更多 &raquo;</a>
                        </div><?php endif; ?>
                    <?php if($pid['is_myfocus'] == 1): ?><div class="am-list-news-ft look-more">
                            <a id="getmorefocus" data-url="<?php echo U('Mob/Weibo/addMoreMyFocus');?>"
                            >查看更多 &raquo;</a>
                        </div><?php endif; ?>
                    <?php if($pid['is_hotweibo'] == 1): ?><div class="am-list-news-ft look-more">
                            <a id="getmorehotweibo" data-url="<?php echo U('Mob/Weibo/addMoreHotWeibo');?>"
                            >查看更多 &raquo;</a>
                        </div><?php endif; ?>
                    <?php if($pid['is_myweibo'] == 1): ?><div class="am-list-news-ft look-more">
                            <a id="getmoremyweibo" data-url="<?php echo U('Mob/Weibo/addMoreMyWeibo');?>"
                            >查看更多 &raquo;</a>
                        </div><?php endif; endif; endif; ?>
        </if>

    </div>

    <?php $uid=is_login(); ?>
    <div class="navBt">
        <a href="<?php echo U('Mob/Weibo/index');?>" class="btBar active">
            <i class="iconfont icon-dongtai2-copy"></i>
            <span>动态</span>
        </a>
        <a href="<?php echo U('Mob/Weibo/crowd');?>" class="btBar">
            <i class="iconfont icon-quanzi"></i>
            <span>圈子</span>
        </a>
        <a href="<?php echo U('Mob/Message/index');?>" class="btBar">
            <i class="iconfont icon-xiaoxi"></i>
            <span>消息</span>
        </a>
        <a class="btBar" <?php if($uid == 0): ?>href="javascript:toast.error('请先登录！');"
        <?php else: ?> href="<?php echo U('Mob/User/index');?>"<?php endif; ?> >
        <i class="iconfont icon-wode"></i>
            <span>我</span>
        </a>

    </div>
       <input id="navtitle" value="<?php echo ($navtitle); ?>" type="hidden">
<script>
    show_video();
   window.onload=function () {
       var src=$('[data-role="<?php echo ($navtitle); ?>"]').attr('src');
       var startsrc=src.substring(src.lastIndexOf('/')+1);
       var newpic='g'+startsrc;
       var newsrc=src.substr(0,src.lastIndexOf('/')+1)+newpic;
       $('[data-role="<?php echo ($navtitle); ?>"]').attr('src',newsrc);

   }
   
</script>

</div>



        <!-- /主体 -->

        <!-- 底部 -->
        <div class="hide-foot am-text-center" style="display: none"><!-- 用于加载统计代码等隐藏元素 -->
    <?php echo ($count_code); ?>
    
</div>
        <!-- /底部 -->
    </body>
</html>