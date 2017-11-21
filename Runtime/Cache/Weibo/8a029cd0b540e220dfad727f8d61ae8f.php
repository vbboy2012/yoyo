<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<?php echo hook('syncMeta');?>

<?php $oneplus_seo_meta = get_seo_meta($vars,$seo); ?>
<?php if($oneplus_seo_meta['title']): ?><title><?php echo ($oneplus_seo_meta['title']); ?></title>
    <?php else: ?>
    <title><?php echo modC('WEB_SITE_NAME',L('_OPEN_SNS_'),'Config');?></title><?php endif; ?>
<?php if($oneplus_seo_meta['keywords']): ?><meta name="keywords" content="<?php echo ($oneplus_seo_meta['keywords']); ?>"/><?php endif; ?>
<?php if($oneplus_seo_meta['description']): ?><meta name="description" content="<?php echo ($oneplus_seo_meta['description']); ?>"/><?php endif; ?>

<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" media="screen" />

<!-- zui -->
<link href="/yoyo/Public/zui/css/zui.css" rel="stylesheet">

<link href="/yoyo/Public/zui/css/zui-theme.css" rel="stylesheet">
<link href="/yoyo/Public/static/os-icon/simple-line-icons.min.css" rel="stylesheet">
<link href="/yoyo/Public/static/os-loading/loading.css" rel="stylesheet">
<link href="/yoyo/Public/css/core.css" rel="stylesheet"/>
<link type="text/css" rel="stylesheet" href="/yoyo/Public/js/ext/magnific/magnific-popup.css"/>

<!--<script src="/yoyo/Public/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="/yoyo/Public/js/com/com.functions.js"></script>

<script type="text/javascript" src="/yoyo/Public/js/core.js"></script>-->
<script src="/yoyo/Public/js.php?f=js/jquery-2.0.3.min.js,js/com/com.functions.js,static/os-loading/loading.js,js/core.js,js/com/com.toast.class.js,js/com/com.ucard.js"></script>



<!--Style-->
<!--合并前的js-->
<?php $config = api('Config/lists'); C($config); $count_code=C('COUNT_CODE'); ?>
<script type="text/javascript">
    var ThinkPHP = window.Think = {
        "ROOT": "/yoyo", //当前网站地址
        "APP": "/yoyo", //当前项目地址
        "PUBLIC": "/yoyo/Public", //项目公共目录地址
        "DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
        "MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
        "VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"],
        'URL_MODEL': "<?php echo C('URL_MODEL');?>",
        'WEIBO_ID': "<?php echo C('SHARE_WEIBO_ID');?>"
    }
    var cookie_config={
        "prefix":"<?php echo C('COOKIE_PREFIX');?>",// cookie 名称前缀
        "path" :"<?php echo C('COOKIE_PATH');?>", // cookie 保存路径
        "domain":"<?php echo C('COOKIE_DOMAIN');?>" // cookie 有效域名
    }
    var Config={
        'GET_INFORMATION':<?php echo modC('GET_INFORMATION',1,'Config');?>,
        'GET_INFORMATION_INTERNAL':<?php echo modC('GET_INFORMATION_INTERNAL',10,'Config');?>*1000,
        'WEBSOCKET_ADDRESS':"<?php echo modC('WEBSOCKET_ADDRESS',gethostbyname($_SERVER['SERVER_NAME']),'Config');?>",
        'WEBSOCKET_PORT':<?php echo modC('WEBSOCKET_PORT',8000,'Config');?>
    }
    var weibo_comment_order = "<?php echo modC('COMMENT_ORDER',0,'WEIBO');?>";
</script>

<script src="/yoyo/Public/lang.php?module=<?php echo strtolower(MODULE_NAME);?>&lang=<?php echo LANG_SET;?>"></script>

<script src="/yoyo/Public/expression.php"></script>

<!-- Bootstrap库 -->
<!--
<?php $js[]=urlencode('/static/bootstrap/js/bootstrap.min.js'); ?>

&lt;!&ndash; 其他库 &ndash;&gt;
<script src="/yoyo/Public/static/qtip/jquery.qtip.js"></script>
<script type="text/javascript" src="/yoyo/Public/Core/js/ext/slimscroll/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="/yoyo/Public/static/jquery.iframe-transport.js"></script>
-->
<!--CNZZ广告管家，可自行更改-->
<!--<script type='text/javascript' src='http://js.adm.cnzz.net/js/abase.js'></script>-->
<!--CNZZ广告管家，可自行更改end-->
<!-- 自定义js -->
<!--<script src="/yoyo/Public/js.php?get=<?php echo implode(',',$js);?>"></script>-->

<?php D('Pushing')->doRun(); $key = C('DATA_AUTH_KEY'); $timestamp = time(); $signature = md5(is_login().$timestamp.$key); ?>
<script>
    //全局内容的定义
    var _ROOT_ = "/yoyo";
    var MID = "<?php echo is_login();?>";
    var SIGNATURE = "<?php echo ($signature); ?>";
    var TIMESTAMP = "<?php echo ($timestamp); ?>";
    var MODULE_NAME="<?php echo MODULE_NAME; ?>";
    var ACTION_NAME="<?php echo ACTION_NAME; ?>";
    var CONTROLLER_NAME ="<?php echo CONTROLLER_NAME; ?>";
    var initNum = "<?php echo modC('WEIBO_NUM',140,'WEIBO');?>";
    function adjust_navbar(){
        $('#sub_nav').css('top',$('#nav_bar').height());
        $('#main-container').css('padding-top',$('#nav_bar').height()+$('#sub_nav').height()+20)
    }
</script>

<audio id="music" src="" autoplay="autoplay"></audio>
<!-- 页面header钩子，一般用于加载插件CSS文件和代码 -->
<?php echo hook('pageHeader');?>
</head>
<body>
	<!-- 头部 -->
	<script src="/yoyo/Public/js/com/com.talker.class.js"></script>
<?php if((is_login()) ): ?><div id="talker">

    </div><?php endif; ?>

<?php D('Common/Member')->need_login(); ?>
<!--[if lt IE 8]>
<div class="alert alert-danger" style="margin-bottom: 0"><?php echo L('_TIP_BROWSER_DEPRECATED_1_');?> <strong><?php echo L('_TIP_BROWSER_DEPRECATED_2_');?></strong>
    <?php echo L('_TIP_BROWSER_DEPRECATED_3_');?> <a target="_blank"
                                          href="http://browsehappy.com/"><?php echo L('_TIP_BROWSER_DEPRECATED_4_');?></a>
    <?php echo L('_TIP_BROWSER_DEPRECATED_5_');?>
</div>
<![endif]-->
<script src="/yoyo/Public/js/canvas.js"></script>
<style>
    body {
        color: #34495e;
        font-family: "Open Sans", sans-serif;
        padding: 0px !important;
        margin: 0px !important;
        direction: "ltr";
        font-size: 14px; }
    .navbar-brand{
        font-size: 20px;
        color: #43cb83;
        margin-top: 6px;
    }
    .navbar-brand:hover{
        color: #2a985e;
    }
</style>

<script>

</script>
<div class="container-fluid topp-box">
    <div class="col-xs-2 box">
        <div class="">
            <a class="navbar-brand" href="<?php echo U('Home/Index/index');?>">YOYOCOINS.com</a>
        </div>
    </div>
    <div class="col-xs-7 box ">
        <div id="nav_bar" class="nav_bar">
            <div class=" sat-nav">
                <ul class="first-ul">
                    <?php $__NAV__ = D('Channel')->lists(true);$__NAV__ = list_to_tree($__NAV__, "id", "pid", "_"); if(is_array($__NAV__)): $i = 0; $__LIST__ = $__NAV__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i; if(($nav['_']) != ""): ?><li class="dropdown show-hide-ul">
                                <a title="<?php echo ($nav["title"]); ?>" class=" nav_item first-a"
                                   href="<?php echo U($nav['url']);?>">
                                    <i class="os-icon-<?php echo ($nav["icon"]); ?> app-icon"></i>
                                    <?php echo ($nav["title"]); ?>
                                    <i class="icon-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu nav-menu">
                                    <?php if(is_array($nav["_"])): $i = 0; $__LIST__ = $nav["_"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$subnav): $mod = ($i % 2 );++$i; if(($subnav["icon"] == 1) or ($subnav["icon"] == 2) or ($subnav["icon"] == 3) or ($subnav["icon"] == 4) or ($subnav["icon"] == 5) or ($subnav["icon"] == 6) or ($subnav["icon"] == 7) or ($subnav["icon"] == 8) or ($subnav["icon"] == 9) or ($subnav["icon"] == 10) or ($subnav["icon"] == 11) or ($subnav["icon"] == 12) or ($subnav["icon"] == 13) or ($subnav["icon"] == 14)): ?><li role="presentation">
                                                <a class="drop-a" role="menuitem" tabindex="-1" href="<?php echo (get_nav_url($subnav["url"])); ?>" target="<?php if(($subnav["target"]) == "1"): ?>_blank<?php else: ?>_self<?php endif; ?>">
                                                    <p>
                                                        <span><img  src="Application/Admin/Static/images/customedit/<?php echo ($subnav["icon"]); ?>.png"></span>
                                                        <span><?php echo ($subnav["title"]); ?></span>
                                                    </p>
                                                    <p><?php echo ($subnav["band_text"]); ?></p>
                                                </a>
                                            </li>
                                            <?php else: ?>
                                            <li role="presentation">
                                                <a class="drop-a" role="menuitem" tabindex="-1" href="<?php echo (get_nav_url($subnav["url"])); ?>" target="<?php if(($subnav["target"]) == "1"): ?>_blank<?php else: ?>_self<?php endif; ?>">
                                                    <p>
                                                        <i class="os-icon-<?php echo ($subnav["icon"]); ?>"></i>
                                                        <span><?php echo ($subnav["title"]); ?></span>
                                                    </p>
                                                    <p><?php echo ($subnav["band_text"]); ?></p>
                                                </a>
                                            </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                </ul>
                            </li>
                            <?php else: ?>
                            <li class="<?php if((get_nav_active($nav["url"])) == "1"): ?>active<?php else: endif; ?>">
                                <a class="first-a" title="<?php echo ($nav["title"]); ?>" href="<?php echo (get_nav_url($nav["url"])); ?>" target="<?php if(($nav["target"]) == "1"): ?>_blank<?php else: ?>_self<?php endif; ?>">
                                    <i class="os-icon-<?php echo ($nav["icon"]); ?> app-icon "></i>
                                    <span ><?php echo ($nav["title"]); ?></span>
                                    <span class="label label-badge rank-label" title="<?php echo ($nav["band_text"]); ?>" style="background: <?php echo ($nav["band_color"]); ?> !important;color:white !important;"><?php echo ($nav["band_text"]); ?></span>
                                </a>
                            </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xs-3 box c-b-right" style="text-align: right">
        <?php if(is_login()): ?><li class="li-hover">
                <a data-role="open-message-box" data-toggle="modal" data-target="#message-box">
                    <div class="message-num" data-role="now-message-num"  style="display: none;"></div>
                    <!--<i class="iconfont icon-lingdang"></i>-->
                    <img src="/yoyo/Public/images/information.png">
                </a>
            </li>
            <li class="dropdown li-hover self-info">
                <?php $uid = is_login(); $reg_time = D('member')->where(array('uid' => $uid))->getField('reg_time'); $reg_date = date('Y-m-d', $reg_time); $self = query_user(array('title', 'avatar128', 'nickname', 'uid', 'title', 'fans', 'following','trusting')); $map = getUserConfigMap('user_cover'); $map['role_id'] = 0; $model = D('Ucenter/UserConfig'); $cover = $model->findData($map); $self['cover_id'] = $cover['value']; $self['cover_path'] = getThumbImageById($cover['value'], 273, 80); ?>
                <a role="button" class="dropdown-toggle dropdown-toggle-avatar" data-toggle="dropdown">
                    <span><img src="<?php echo ($self["avatar32"]); ?>" class="avatar-img nav-img"></span>
                    <span class="user-name"><?php echo ($self["nickname"]); ?></span>
                </a>
                <ul class="dropdown-menu user-card" role="menu">
                    <div class="bg-wrap">
                        <?php if($self['cover_id']): ?><img class="cover uc_top_img_bg_weibo" src="<?php echo ($self['cover_path']); ?>">
                            <?php else: ?>
                            <img class="cover uc_top_img_bg_weibo" src="/yoyo/Application/Core/Static/images/bg.jpg"><?php endif; ?>
                        <?php if(is_login() && $self['uid'] == is_login()): ?><div class="change_cover"><a data-type="ajax" data-url="<?php echo U('Ucenter/Public/changeCover');?>"
                                                         data-toggle="modal" data-title="<?php echo L('_UPLOAD_COVER_');?>" style="color: white;"><img
                                    class="img-responsive" src="/yoyo/Application/Core/Static/images/fractional.png" style="width: 25px;"></a>
                            </div><?php endif; ?>
                    </div>

                    <div class="my-bg-info">
                        <div class="bg-avatar">
                            <a class="link-change-avatar" href="<?php echo U('Ucenter/Config/avatar');?>" title="更换头像">
                                <img src="<?php echo ($self["avatar128"]); ?>" class="avatar-img "/>
                            </a>
                        </div>
                        <span class="nickname">
                        <a ucard="<?php echo ($self["uid"]); ?>" href="<?php echo ($self["space_url"]); ?>" class="user_name"><?php echo (htmlspecialchars($self["nickname"])); ?></a>
                    </span>

                        <div class="bg-numb row ">
                            <a href="<?php echo U('Ucenter/index/following',array('uid'=>$self['uid']));?>" title="<?php echo L('_FOLLOW_COUNT_');?>">
                                <div class="col-xs-4 num" style="border: none">
                                    <span><?php echo L('_FOLLOW_');?></span>
                                    <span><?php echo ($self["following"]); ?></span>
                                </div>
                            </a>
                            <a href="<?php echo U('Ucenter/index/fans',array('uid'=>$self['uid']));?>" title="<?php echo L('_FANS_COUNT_');?>">
                                <div class="col-xs-4 num">
                                    <span><?php echo L('_BEI_'); echo L('_FOLLOW_');?></span>
                                    <span><?php echo ($self["fans"]); ?></span>
                                </div>
                            </a>
                            <a href="<?php echo U('Ucenter/index/applist',array('uid'=>$self['uid'],'type'=>'Weibo'));?>">
                                <div class="col-xs-4 num">
                                    <span><?php echo L('_TRUST_');?></span>
                                    <span><?php echo ($self["trusting"]); ?></span>
                                </div>
                            </a>
                        </div>

                    </div>

                    <div class="row grade-box">
                        <?php $title=D('Ucenter/Title')->getCurrentTitleInfo(is_login()); ?>
                        <script>
                            $(function () {
                                $('#upgrade').tooltip({
                                            html: true,
                                            title: '<?php echo L("_CURRENT_LEVEL_");?>：<?php echo ($self["title"]); ?> <br/><?php echo L("_NEXT_LEVEL_");?>：<?php echo ($title["next"]); ?><br/><?php echo L("_NOW_");?>：<?php echo ($self["score"]); ?><br/><?php echo L("_NEED_");?>：<?php echo ($title["upgrade_require"]); ?><br/><?php echo L("_LAST_");?>： <?php echo ($title["left"]); ?><br/><?php echo L("_PROGRESS_");?>：<?php echo ($title["percent"]); ?>% <br/> '
                                        }
                                );
                            })
                        </script>
                        <div class="col-xs-2 l-box"><span>等级</span></div>
                        <div class="col-xs-10 r-box">
                            <div id="upgrade" class="upgrade" data-toggle="tooltip" data-placement="bottom" title="">
                                <div class="grade-bottom" ></div>
                                <div class="grade-top" style="width:<?php echo ($title["percent"]); ?>%;"></div>
                            </div>
                        </div>
                        <p class="pull-right"><span><?php echo ($self["score"]); ?></span>/<span style="color: #ccc"><?php echo ($title["upgrade_require"]); ?></span></p>
                    </div>

                    <div class="link-wrap">
                        <div class="link-box row">
                            <a href="<?php echo U('Ucenter/index/information',array('uid'=>$self['uid']));?>">
                                <div class="col-xs-6 l-p0">
                                    <i class="os-icon-user"></i>
                                    个人主页
                                </div>
                            </a>
                            <a href="<?php echo U('Ucenter/index/myad');?>">
                                <div class="col-xs-6 r-p0">
                                    <i class="icon-map-marker"></i>
                                    我的广告
                                </div>
                            </a>
                        </div>
                        <div class="link-box row" style="border: none;padding-top: 0">
                            <a href="<?php echo U('ucenter/Config/index');?>" title="<?php echo L('_EDIT_INFO_');?>">
                                <div class="col-xs-6 l-p0">
                                    <i class="os-icon-settings"></i>
                                    <?php echo L('_EDIT_INFO_');?>
                                </div>
                            </a>
                            <div class="col-xs-6 r-p0"  style="cursor: pointer" event-node="logout" >
                                <i class="os-icon-logout"></i>
                                <?php echo L('_LOGOUT_');?>
                            </div>
                        </div>
                    </div>
                </ul>
            </li>
            <li class="dropdown-toggle dropdown-toggle-avatar li-hover show-hide-ul">
                <a title="<?php echo L('_EDIT_INFO_');?>" href="#" data-toggle="dropdown" >
                    <!--<i class="iconfont icon-caidan"></i>-->
                    <img src="/yoyo/Public/images/list.png">
                </a>
                <ul class="dropdown-menu  drop-self nav-menu" role="menu">
                    <?php $user_nav=S('common_user_nav'); if($user_nav===false){ $user_nav=D('UserNav')->order('sort asc')->where('status=1')->select(); S('common_user_nav',$user_nav); } ?>

                    <?php if(is_array($user_nav)): $i = 0; $__LIST__ = $user_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a style="color:<?php echo ($vo["color"]); ?>"
                               target="<?php if(($vo["target"]) == "1"): ?>_blank<?php else: ?>_self<?php endif; ?>" href="<?php echo get_nav_url($vo['url']);?>">
                            <?php echo ($vo["title"]); ?>
                            <span class="label label-badge rank-label" title="<?php echo ($vo["band_text"]); ?>"
                                  style="background: <?php echo ($vo["band_color"]); ?> !important;color:white !important;"><?php echo ($vo["band_text"]); ?></span></a>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>

                    <?php $register_type=modC('REGISTER_TYPE','normal','Invite'); $register_type=explode(',',$register_type); if(in_array('invite',$register_type)){ ?>
                    <li>
                        <a href="<?php echo U('ucenter/Invite/invite');?>"><?php echo L('_INVITE_FRIENDS_');?></a>
                    </li>
                    <?php } ?>

                    <?php echo hook('personalMenus');?>
                    <?php if(check_auth('Admin/Index/index')): ?><li>
                            <a href="<?php echo U('Admin/Index/index');?>" target="_blank"><?php echo L('_MANAGE_BACKGROUND_');?></a>
                        </li><?php endif; ?>
                </ul>
            </li>
            <?php else: ?>
            <?php $open_quick_login=modC('OPEN_QUICK_LOGIN', 0, 'USERCONFIG'); $register_type=modC('REGISTER_TYPE','normal','Invite'); $register_type=explode(',',$register_type); $only_open_register=0; if(in_array('invite',$register_type)&&!in_array('normal',$register_type)){ $only_open_register=1; } ?>
            <script>
                var OPEN_QUICK_LOGIN = "<?php echo ($open_quick_login); ?>";
                var ONLY_OPEN_REGISTER = "<?php echo ($only_open_register); ?>";
            </script>
            <div class="from">
                <div class=" a-div">
                    <a class="top-btn" data-login="do_login"><?php echo L('_LOGIN_');?></a>
                    <a class="top-btn" data-role="do_register" data-url="<?php echo U('Ucenter/Member/register');?>"><?php echo L('_REGISTER_');?></a>
                </div>
            </div><?php endif; ?>
    </div>
</div>


<!--换肤插件钩子-->
<?php echo hook('setSkin');?>
<!--换肤插件钩子 end-->
<div id="tool" class="tool ">
    <?php echo hook('tool');?>
    <?php if(check_auth('Core/Admin/View')): ?><!--  <a href="javascript:;" class="admin-view"></a>--><?php endif; ?>
    <a  id="go-top" href="javascript:;" class="go-top "></a>

</div>
<?php if(is_login()&&(get_login_role_audit()==2||get_login_role_audit()==0)){ ?>
<div id="top-role-tip" class="alert alert-danger">
    <?php echo L('_TIP_ROLE_NOT_AUDITED1_');?> <strong><?php echo L('_TIP_ROLE_NOT_AUDITED2_');?></strong><?php echo L('_TIP_ROLE_NOT_AUDITED3_');?>
    <a target="_blank" href="<?php echo U('ucenter/config/role');?>"><?php echo L('_TIP_ROLE_NOT_AUDITED4_');?></a>
</div>
<script>
    $(function () {
        $('#top-role-tip').css('margin-top', $('#nav_bar').height() + 15);
        $('#top-role-tip').css('margin-bottom', -$('#nav_bar').height()+15);
    });
</script>
<?php } ?>



<script>

    function displaySubMenu(li) {
        var subMenu = li.getElementsByTagName("ul")[0];
        subMenu.style.display = "block";
    }
    function hideSubMenu(li) {
        var subMenu = li.getElementsByTagName("ul")[0];
        subMenu.style.display = "none";
    }
</script>
	<!-- /头部 -->
	
	<!-- 主体 -->
	<div class="main-wrapper">
    
    <link href="/yoyo/Application/Weibo/Static/css/circle.css" type="text/css" rel="stylesheet"/>
    <style>
        #main-container{
            margin-top: 50px;
            max-width: 1000px;
            padding: 0;
            font-size: 14px
        }
    </style>

    <!--顶部导航之后的钩子，调用公告等-->
<!--<?php echo hook('afterTop');?>-->
<!--顶部导航之后的钩子，调用公告等 end-->
    <div id="main-container" class="container">
        <div class="row">
            
    <div class="c-left-box">
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lists): $mod = ($i % 2 );++$i;?><div class="one-type row">
            <p class="type-title"><?php echo ($lists["title"]); ?></p>
            <?php if($lists["list"] != NULL): if(is_array($lists["list"])): $i = 0; $__LIST__ = $lists["list"];if( count($__LIST__)==0 ) : echo "暂无数据" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Weibo/Index/index',array('crowd'=>$data['id']));?>">
                        <div class="one-circle">
                            <div class="circle-cover-wrap">
                                <img src="<?php echo (getthumbimagebyid($data["logo"],80,80)); ?>" alt="" title="社群封面">
                            </div>
                            <div class="circle-info-wrap">
                                <div class="info-box"><?php echo ($data["title"]); ?></div>
                                <div class="info-box" style="color: #999"><?php echo ($data["intro"]); ?></div>
                                <div class="info-box box-num"><span>成员 <?php echo ($data["member_count"]); ?></span><span>讨论 <?php echo ($data["post_count"]); ?></span></div>
                            </div>
                            <div class="circle-follow">
                                <?php switch($data["is_follow"]): case "0": ?><a href="javascript:" class="btn-follow" <?php if(($data["need_pay"]) > "0"): ?>data-toggle="modal" data-target="#myModal<?php echo ($data["id"]); ?>"<?php else: ?>data-role="follow_crowd" data-id="<?php echo ($data["id"]); ?>"<?php endif; ?> >
                                            +加入
                                        </a>
                                        <?php if(($data["need_pay"]) > "0"): ?><div class="modal fade" id="myModal<?php echo ($data["id"]); ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
                                                            <h4 class="modal-title">付费加入圈子确认</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>加入该圈子需要支付<?php echo ($data["need_pay"]); echo ($data["pay_type_title"]); ?></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                                            <button type="button" class="btn btn-primary pull-left" data-role="follow_crowd" data-id="<?php echo ($data["id"]); ?>" data-dismiss="modal">确定</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><?php endif; break;?>
                                    <?php case "1": ?><a href="javascript:" class="btn-follow" data-role="unfollow_crowd" data-id="<?php echo ($data["id"]); ?>">
                                        已加入
                                        </a><?php break;?>
                                    <?php case "2": ?><a href="javascript:" class="btn-follow" data-id="<?php echo ($data["id"]); ?>">
                                            待审核
                                        </a><?php break;?>
                                    <?php case "-2": ?><a href="javascript:" class="btn-follow" <?php if(($data["need_pay"]) > "0"): ?>data-toggle="modal" data-target="#inviteModal<?php echo ($data["id"]); ?>"<?php else: ?>data-role="follow_crowd" data-id="<?php echo ($data["id"]); ?>"<?php endif; ?> >
                                        +被邀请
                                        </a>
                                        <?php if(($data["need_pay"]) > "0"): ?><div class="modal fade" id="inviteModal<?php echo ($data["id"]); ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
                                                            <h4 class="modal-title">付费加入圈子确认</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>加入该圈子需要支付<?php echo ($data["need_pay"]); echo ($data["pay_type_title"]); ?></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                                            <button type="button" class="btn btn-primary pull-left" data-role="follow_crowd" data-id="<?php echo ($data["id"]); ?>" data-dismiss="modal">确定</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><?php endif; break;?>
                                    <?php default: ?>
                                    <a href="javascript:" class="btn-follow" data-role="follow_crowd" data-id="<?php echo ($data["id"]); ?>">
                                        +加入
                                    </a><?php endswitch;?>
                            </div>
                        </div>
                    </a><?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </div><?php endforeach; endif; else: echo "暂无数据" ;endif; ?>



    </div>
    <div class="c-right-box">
        <div class="create-circle">
            <div class="create">
                <a href="#frm-post-popup" class="btn-create open-popup-link">
                    <i class="icon-plus-sign"></i>
                    创建圈子
                </a>
                <p>我的圈子我做主</p>
            </div>
            <div class="have-circle row">
                <?php if(is_array($my_create_crowd_list)): $i = 0; $__LIST__ = $my_create_crowd_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Weibo/Index/index',array('crowd'=>$list['crowd']['id']));?>">
                        <div class="my-circle">
                            <img class="m-cover" src="<?php echo (getthumbimagebyid($list["crowd"]["logo"],80,80)); ?>" alt="" title="社群封面">
                            <p class="text-more" title="<?php echo ($list["crowd"]["title"]); ?>" style="display: block"><?php echo ($list["crowd"]["title"]); ?></p>
                        </div>
                    </a><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <?php if(empty($my_create_crowd_list)): ?><div class="no-circle">
                    <p><img src="/yoyo/Application/Weibo/Static/images/none.png" alt=""></p>
                    <p>你还未创建圈子</p>
                </div><?php endif; ?>
        </div>
        <div class="joined-circle row">
            <p class="joined-p">我已加入的圈子</p>
            <?php if(is_array($follow_crowd_list)): $i = 0; $__LIST__ = $follow_crowd_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Weibo/Index/index',array('crowd'=>$data['crowd']['id']));?>">
                    <div class="my-circle">
                        <img class="m-cover" src="<?php echo (getthumbimagebyid($data["crowd"]["logo"],80,80)); ?>" alt="" title="社群封面">
                        <p class="text-more" style="display: block" title="<?php echo ($data["crowd"]["title"]); ?>"><?php echo ($data["crowd"]["title"]); ?></p>
                    </div>
                </a><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>

    <!-- Modal -->
<div id="frm-post-popup" class="white-popup mfp-hide" style="max-width: 745px">
    <h2>创建圈子</h2>

    <div class="aline" style="margin-bottom: 10px"></div>
    <div>
        <div class="row">
            <div class="col-md-4">
                <div class="controls">
                    <input type="file" id="upload_picture_cover">

                    <div class="upload-img-box" style="margin-top: 20px;width: 250px">
                        <div style="font-size:3em;padding:2em 0;color: #ccc;text-align: center">暂无封面</div>
                    </div>
                </div>

            </div>
            <div class="col-md-8">
                <form class="form-horizontal  ajax-form" role="form" action="<?php echo U('Weibo/Crowd/create');?>" method="post">
                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">标题</label>

                        <div class="col-sm-10">
                            <input id="title" name="title" type="" class="form-control" value="" placeholder="标题"/>
                        </div>

                        <input type="hidden" name="logo" id="cover_id_cover" value="<?php echo ($data['cover']); ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="issue_top" class="col-sm-2 control-label">分类</label>

                        <div class="col-sm-10">
                            <select id="issue_top" name="type_id" class="form-control">
                                <?php if(is_array($type)): $i = 0; $__LIST__ = $type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><option value="<?php echo ($data["id"]); ?>">
                                        <?php echo ($data["title"]); ?>
                                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="intro" class="col-sm-2 control-label"><?php echo L('_INTRO_');?></label>

                        <div class="col-sm-10">
                            <label for="intro">
                                <textarea id="intro" name="intro" style="width: 382px;height: 150px;"
                                          class="form-control"></textarea>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="type" class="col-sm-2 control-label">圈子类型</label>

                        <div class="col-sm-10 ">
                            <label for="id_type_0">
                                <input id="id_type_0" name="type" value="0" type="radio" data-role="crowd_type" checked="">公有圈子
                            </label>

                            <label for="id_type_1">
                                <input id="id_type_1" name="type" value="1" type="radio" data-role="crowd_type">私有圈子
                            </label>
                        </div>
                    </div>

                    <div class="form-group private-crowd crowd-hid">
                        <label for="" class="col-sm-2 control-label">付费入圈</label>

                        <div class="col-sm-8">
                            <select data-role="select" name="pay_type" class="chosen-select form-control" tabindex="-1" style="border-radius: 5px">
                                <option value="0">不用付费,只要让我审核</option>
                                <?php if(is_array($field)): $i = 0; $__LIST__ = $field;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vl): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vl["id"]); ?>"><?php echo ($vl["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                            <input id="pay_num" name="pay_num" type="text" class="form-control"
                                   value="<?php echo ($crowd['need_pay']); ?>" placeholder="输入入圈费用" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9]/g,'')"/>
                            <div style="color: #999">
                                （付费才能加入圈子,同时需要管理员审核）
                            </div>
                        </div>
                    </div>

                    <div class="form-group private-crowd crowd-hid">
                        <label for="" class="col-sm-2 control-label">浏览模式</label>

                        <div class="col-sm-10 ">
                            <label for="">
                                <input name="invisible" value="0" type="radio" data-role="crowd_type" checked="">公开
                            </label>

                            <label for="id_type_1">
                                <input name="invisible" value="1" type="radio" data-role="crowd_type">私密
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">可发动态</label>

                        <div class="col-sm-8">
                            <label>
                                <input name="allow_user_post" value="0" type="radio" checked>是
                            </label>

                            <label>
                                <input name="allow_user_post" value="-1" type="radio">否
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notice" class="col-sm-2 control-label">圈子公告</label>

                        <div class="col-sm-10">

                            <input id="notice" name="notice" type="text" class="form-control" value=""
                                   placeholder="输入圈子展示的公告"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary " href="<?php echo U('Weibo/Crowd/create');?>">提交</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>

</div>
<!-- /.modal -->

    <script type="text/javascript" src="/yoyo/Public/static/uploadify/jquery.uploadify.min.js"></script>
    <script>
        $(function () {
            $('#top_nav >li >a ').mouseenter(function () {
                $('.children_nav').hide();
                $('#children_' + $(this).attr('data')).show();
            });
            $('.open-popup-link').magnificPopup({
                type: 'inline',
                midClick: true, // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
                closeOnBgClick: false
            });

            $('.open-popup-ajax').magnificPopup({
                type: 'iframe',
                midClick: true, // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
                closeOnBgClick: false
            });

            $('[data-role="crowd_type"]').click(function () {
                var $this = $(this);
                if ($this.val() == '1') {
                    $this.closest('.form-horizontal').children('.private-crowd').removeClass('crowd-hid');
                } else {
                    $this.closest('.form-horizontal').children('.private-crowd').addClass('crowd-hid');
                }
            });
        })
    </script>

    <script type="text/javascript">
        var SUPPORT_URL = "<?php echo addons_url('Support://Support/doSupport');?>";
        //上传图片
        /* 初始化上传插件 */
        $("#upload_picture_cover").uploadify({
            "height": 30,
            "swf": "/yoyo/Public/static/uploadify/uploadify.swf",
            "fileObjName": "download",
            "buttonText": "上传封面",
            "buttonClass": "uploadcover",
            "uploader": "<?php echo U('Core/File/uploadPicture',array('session_id'=>session_id()));?>",
            "width": 250,
            'removeTimeout': 1,
            'fileTypeExts': '*.jpg; *.png; *.gif;',
            "onUploadSuccess": uploadPicturecover,
            'overrideEvents': ['onUploadProgress', 'onUploadComplete', 'onUploadStart', 'onSelect'],
            'onFallback': function () {
                alert("<?php echo L('_FLASH_NOT_DETECTED_');?>");
            }, 'onUploadProgress': function (file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
                $("#cover_id_cover").parent().find('.upload-img-box').html(totalBytesUploaded + ' bytes uploaded of ' + totalBytesTotal + ' bytes.');
            }, 'onUploadComplete': function (file) {
                //alert('The file ' + file.name + ' finished processing.');
            }, 'onUploadStart': function (file) {
                //alert('Starting to upload ' + file.name);
            }, 'onQueueComplete': function (queueData) {
                // alert(queueData.uploadsSuccessful + ' files were successfully uploaded.');
            }
        });
        function uploadPicturecover(file, data) {
            var data = $.parseJSON(data);
            var src = '';
            if (data.status) {
                $("#cover_id_cover").val(data.id);
                src = data.url || data.path
                $('.upload-img-box').html(
                        '<div class="upload-pre-item"><img src="' + src + '"/></div>'
                );
            } else {
                toast.error("<?php echo L('_ERROR_FAIL_UPLOAD_COVER_');?>", "<?php echo L('_PROMPT_');?>");
            }
        }
    </script>



        </div>
    </div>
</div>
	<!-- /主体 -->

	<!-- 底部 -->
	<footer class="footer">
    <div class="container ftTop">
        <div class="ftBox">
            <h2>YOYOCOINS.com</h2>
            <div class="ftMain">
                <?php echo modC('ABOUT_US',L('_NOT_SET_NOW_'),'Config');?>
            </div>
        </div>
        <div class="ftBox">
            <div class="col-xs-6">
                <p class="ftTit">关于</p>
                <div class="ftLink">
                    <li><a target="_blank" title="" href="https://localbitcoins.com/about">关于我们</a></li>
                    <li><a target="_blank" title="" href="https://localbitcoins.com/about">招聘</a></li>
                    <li><a target="_blank" title="" href="https://localbitcoins.com/about">费用</a></li>
                    <li><a target="_blank" title="" href="https://localbitcoins.com/about">服务条款</a></li>
                    <li><a target="_blank" title="" href="https://localbitcoins.com/about">系统公告</a></li>
            </div>
            </div>
            <div class="col-xs-6">
                <p class="ftTit">支持</p>
                <div class="ftLink">
                    <li><a target="_blank" title="" href="https://localbitcoins.com/about">联系客服</a></li>
                    <li><a target="_blank" title="" href="https://localbitcoins.com/about">常见问题</a></li>
                    <li><a target="_blank" title="" href="https://localbitcoins.com/about">忘记密码</a></li>
                </div>
            </div>
        </div>
        <div class="ftBox">
            <div class="col-xs-6">
                <p class="ftTit">服务</p>
                <div class="ftLink">
                    <li><a target="_blank" title="" href="https://localbitcoins.com/about">问答</a></li>
                    <li><a target="_blank" title="" href="https://localbitcoins.com/about">论坛</a></li>
                    <li><a target="_blank" title="" href="https://localbitcoins.com/about">圈子</a></li>
                </div>
            </div>
            <div class="col-xs-6">
                <p class="ftTit">联系我们</p>
                <div class="ftLink">
                    <li><a target="_blank" title="" href="https://localbitcoins.com/about">Facebook</a></li>
                    <li><a target="_blank" title="" href="https://localbitcoins.com/about">Twitter</a></li>
                    <li><a target="_blank" title="" href="https://localbitcoins.com/about">Instagram</a></li>
                    <li><a target="_blank" title="" href="https://localbitcoins.com/about">博客</a></li>
                    <li><a target="_blank" title="" href="https://localbitcoins.com/about">中文博客</a></li>
                </div>
            </div>
        </div>
    </div>
    <div class="container ftBottom text-center">
        <p>
            <span><?php echo modC('COPY_RIGHT',L('_NOT_SET_NOW_'),'Config');?></span>
        </p>
        <?php echo ($count_code); ?>
    </div>
</footer>

<!-- jQuery (ZUI中的Javascript组件依赖于jQuery) -->


<!-- 为了让html5shiv生效，请将所有的CSS都添加到此处 -->
<link type="text/css" rel="stylesheet" href="/yoyo/Public/static/qtip/jquery.qtip.css"/>


<!--<script type="text/javascript" src="/yoyo/Public/js/com/com.notify.class.js"></script>-->

<!-- 其他库-->
<!--<script src="/yoyo/Public/static/qtip/jquery.qtip.js"></script>
<script type="text/javascript" src="/yoyo/Public/js/ext/slimscroll/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="/yoyo/Public/static/jquery.iframe-transport.js"></script>

<script type="text/javascript" src="/yoyo/Public/js/ext/magnific/jquery.magnific-popup.min.js"></script>-->

<!--<script type="text/javascript" src="/yoyo/Public/js/ext/placeholder/placeholder.js"></script>
<script type="text/javascript" src="/yoyo/Public/js/ext/atwho/atwho.js"></script>
<script type="text/javascript" src="/yoyo/Public/zui/js/zui.js"></script>-->
<link type="text/css" rel="stylesheet" href="/yoyo/Public/js/ext/atwho/atwho.css"/>

<script src="/yoyo/Public/js.php?t=js&f=js/com/com.notify.class.js,static/qtip/jquery.qtip.js,js/ext/slimscroll/jquery.slimscroll.min.js,js/ext/magnific/jquery.magnific-popup.min.js,js/ext/placeholder/placeholder.js,js/ext/atwho/atwho.js,zui/js/zui.js&v=<?php echo ($site["sys_version"]); ?>.js"></script>
<script type="text/javascript" src="/yoyo/Public/static/jquery.iframe-transport.js"></script>

<script src="/yoyo/Public/js/ext/lazyload/lazyload.js"></script>

<script src="/yoyo/Public/js/socket.io.js"></script>


    <script src="/yoyo/Application/Weibo/Static/js/weibo.js"></script>

<!-- 页面footer钩子，一般用于加载插件JS文件和JS代码 -->
<?php echo hook('pageFooter', 'widget');?>
<!-- 调用全站公告部件-->
<?php echo W('Common/Announce/render');?>

<!-- 调用消息部件-->
<?php echo W('Common/Message/render');?>
<div class="hidden"><!-- 用于加载统计代码等隐藏元素 -->
    <?php echo ($count_code); ?>
    
</div>

<script>
    // VERSION_NAME 替换为项目的版本，VERSION_CODE 替换为项目的子版本
  //  new Bugtags('d6023daa6c7467634636c87b3f16213e','8.12','VERSION_CODE');
</script>

	<!-- /底部 -->
</body>
</html>