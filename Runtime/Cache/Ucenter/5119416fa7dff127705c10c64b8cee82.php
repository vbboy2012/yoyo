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
    <link href="/yoyo/Application/Ucenter/Static/css/center.css" type="text/css" rel="stylesheet">
</head>
<body>

<!-- 头部 -->

<!-- /头部 -->

<!-- 主体 -->

    <div class="main-wrapper">
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
        <br/>
        <!--顶部导航之后的钩子，调用公告等-->
<!--<?php echo hook('afterTop');?>-->
<!--顶部导航之后的钩子，调用公告等 end-->
        <div id="main-container" class="container user-config" style="margin-top: 60px">
            <div class="row">
                <div class=" col-xs-3" style="width: 220px">
                    <div>
                        <div class="user-info-panel  text-center margin_bottom_10">
                            <img class="avatar-img" src="<?php echo ($self["avatar128"]); ?>">
                            <a class="nickname" href="<?php echo ($self["space_url"]); ?>"><?php echo ($self["nickname"]); ?></a>
                        </div>
                    </div>
                    <div>
                        <nav class="menu" data-toggle="menu">
                            <ul class="nav nav-primary side-menu">
                                <li id="info"><a href="<?php echo U('Config/index');?>"><i class="icon-th"></i>
                                    <?php echo L('_SETTINGS_DATA_');?></a></li>
                                <li id="avatar"><a href="<?php echo U('Config/avatar');?>"><i class="icon-user"></i>
                                    <?php echo L('_AVATAR_MODIFY_');?></a></li>
                                <li id="password"><a href="<?php echo U('Config/safe');?>"><i class="icon-lock"></i>
                                    <?php echo L('_SAFE_SET_');?></a></li>
                                <?php if(($can_show_role) == "1"): ?><li id="role"><a href="<?php echo U('Config/role');?>"><i class="icon-group"></i>
                                        <?php echo L('_SETTINGS_IDENTITY_');?></a></li><?php endif; ?>
                                <li id="score"><a href="<?php echo U('Config/score');?>"><i class="icon-bar-chart"></i> <?php echo L('_SCORE_MY_');?></a>
                                </li>
                                <li id="process"><a href="<?php echo U('Attest/process',array('go_index'=>1));?>"><i class="icon-certificate"></i> 申请认证</a>
                                </li>
                            </ul>
                        </nav>
                        <script>
                            $("#<?php echo ($tab); ?>").addClass('active');
                        </script>
                    </div>
                </div>
                <div class="col-xs-9 ">
                    <div id="usercenter-content-td ">
                        <div class="container-fluid common_block_border" style="min-height: 600px">
                            
<div id="center">
<div id="center_base" class="with-padding" style="padding: 20px">

    <h2>
        <?php echo L('_SAFE_SET_');?>
    </h2>
    <div class="row">
        <div class="panel-group" id="accordionPanels" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" id="headingOne">
                    <h4 class="panel-title">
                        <?php if($accountInfo['google_ver']){ $check = 'check'; $btn = L('_GOOGLE_VER_CLOSE_'); $type = 'close'; }else{ $check = 'times'; $status = L('_GOOGLE_VER_NO_'); $btn = L('_GOOGLE_VER_OPEN_'); $type = 'open'; } ?>
                        <i class="icon icon-<?php echo ($check); ?>"></i>
                        <a data-toggle="collapse" data-parent="#accordionPanels" href="#collapseOne">
                            双重身份验证
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php if($type == 'open'){ ?>
                        <div>
                            <label style="color: #0082df"><i class="icon icon-info-sign"></i><?php echo ($status); ?></label>
                        </div>
                        <div><p><?php echo L('_GOOGLE_VER_TIPS1_');?></p><p><?php echo L('_GOOGLE_VER_TIPS2_');?></p></div>
                        <?php } ?>
                        <div class="alert alert-success" style="margin-top: 5px">
                            <?php if($type == 'open'){ ?>
                            <h4>step1 在您的手机上安装双重验证程序：Google Authenticator</h4>
                            <hr>
                            <p>iPhone手机：在App Store中搜索Google Authenticator<a href="https://itunes.apple.com/cn/app/google-authenticator/id388497605">下载应用</a></p>
                            <p>Android手机：在应用市场中搜索“谷歌身份验证器”，或搜索Google Authenticator<a href="http://shouji.baidu.com/software/22417419.html">下载应用</a></p>
                            <hr>
                            <h4>step2 安装完成后，您需要对该应用程序进行如下配置</h4>
                            <hr>
                            <p>在“Google Authenticator (身份验证器)”应用程序中，点击“添加新账户 (iOS 下是 + 号)”，然后选择“扫描条形码”。将手机上的相机镜头对准下图扫描该条形码。</p>
                            <img src="data:image/png;base64,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">
                            <p>如果您无法扫描成功上图的条形码，您还可以手动添加账户，并输入如下密匙：<label style="color: #0082df"><?php echo ($secret); ?></label></p>
                            <hr>
                            <h4>step3 配置完成</h4>
                            <hr>
                            <p>配置完成后，手机上会显示一个 6 位数字，每隔 30 秒变化一次。这个数字即为您的双重验证密码。</p>
                            <p>请勿删除此双重验证密码账户，否则会导致您无法进行账户操作。</p>
                            <p>每次登录时，输入一个一次性代码，这样可以保护您的用户帐户。</p>
                            <?php } ?>
                            <form class="orm-horizontal center_info ajax-form" action="<?php echo U('ucenter/config/googleVerify');?>" method="post">
                                <label>双重验证密码：</label>
                                <input type="hidden" name="secret" value="<?php echo ($secret); ?>">
                                <input type="hidden" name="type" value="<?php echo ($type); ?>">
                                <input type="text" name="oneCode">
                                <?php if($type == 'open'){ ?>
                                <div class="checkbox">
                                    <label style="color: #0082df">
                                        <input type="checkbox" name="remember"> 请在纸上写下安全密钥 <?php echo ($secret); ?>
                                    </label>
                                </div>
                                <?php } ?>
                                <button type="submit" class="btn btn-primary" style="margin-left: 5px"><?php echo ($btn); ?></button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" id="headingTwo">
                    <h4 class="panel-title">
                        <?php if($accountInfo['safe_pw']){ $check = 'check'; $status = L('_YES_'); }else{ $check = 'times'; $status = L('_NO_'); } ?>
                        <i class="icon icon-<?php echo ($check); ?>"></i>
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordionPanels" href="#collapseTwo">
                            资金密码
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse col-xs-8">
                    <div class="panel-body">
                        <form class="form-horizontal center_info ajax-form" role="form"
                              action="<?php echo U('ucenter/config/doChangeSafepw');?>" method="post">
                            <div class="form-group">
                                <label for="account" class="col-xs-3 control-label"><?php echo L('_SAFE_PASS_');?></label>

                                <label for="account" class="col-xs-3 control-label" style="text-align: left"><?php echo ($status); ?></label>
                            </div>
                            <div class="form-group">
                                <label for="account" class="col-xs-3 control-label"><?php echo L('_SET_NEW_'); echo L('_PASSWORD_');?></label>

                                <div class="col-xs-9">
                                    <input type="text" class="form-control pull-left" id="safe_pw" name="safe_pw">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="verify" class="col-xs-3 control-label"><?php echo L('_EMAIL_'); echo L('_VERIFY_CODE_');?></label>

                                <div class="col-xs-9">
                                    <input type="text" class="form-control pull-left" id="verify" name="verify">
                                    <a class="pull-left btn btn-default " data-role="getEmailVerify"><?php echo L('_SEND_');?></a>
                                </div>
                            </div>
                            <input type="hidden" class="form-control" name="uid" value="<?php echo is_login();?>">

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-xs-10">
                                    <button type="submit" data-role="submit" class="btn btn-primary"><?php echo L('_SUBMIT_');?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" id="headingFour">
                    <h4 class="panel-title">
                        <?php if($accountInfo['mobile']){ $check = 'check'; $status = L('_YES_'); }else{ $check = 'times'; $status = L('_NO_'); } ?>
                        <i class="icon icon-<?php echo ($check); ?>"></i>
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordionPanels" href="#collapseFour">
                            手机验证
                        </a>
                    </h4>
                </div>
                <div id="collapseFour" class="panel-collapse collapse col-xs-8">
                    <div class="panel-body">
                        <form class="form-horizontal center_info ajax-form" role="form"
                              action="<?php echo U('ucenter/config/checkVerify');?>" method="post">
                            <div class="form-group">
                                <label for="account" class="col-xs-3 control-label"><?php echo L('_MOBILE_VER_');?></label>
                                <label for="account" class="col-xs-3 control-label" style="text-align: left"><?php echo ($status); ?></label>
                            </div>
                            <div class="form-group">
                                <label for="account" class="col-xs-3 control-label"><?php echo L('_MOBILE_');?></label>

                                <div class="col-xs-9">
                                    <input type="text" class="form-control pull-left" id="account" name="account">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="verifyCode2" class="col-xs-3 control-label" >输入验证码</label>

                                <div class="col-xs-4">
                                    <input type="text" id="verifyCode2" class="form-control" placeholder="验证码"
                                           errormsg="请填写正确的验证码" nullmsg="请填写验证码" datatype="*5-5" name="verify" >
                                </div>
                                <div class="col-xs-5 lg_lf_fm_verify">
                                    <img class="verifyimg reloadverify img-responsive" alt="点击切换"
                                         src="<?php echo U('verify',array('id'=>$type=='email'?3:2));?>"
                                         style="cursor:pointer;width: 150px;">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-xs-9">
                                    <a class="btn btn-default" data-role="getVerify"><?php echo L('_GET_MOBILE_');?></a>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="verify" class="col-xs-3 control-label"><?php echo L('_VERIFY_CODE_');?></label>

                                <div class="col-xs-9">
                                    <input type="text" class="form-control" id="verify" name="verify">
                                </div>
                            </div>

                            <input type="hidden" class="form-control" name="type" value="mobile">
                            <input type="hidden" class="form-control" name="uid" value="<?php echo is_login();?>">

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-xs-10">
                                    <button type="submit" data-role="submit" class="btn btn-primary"><?php echo L('_SUBMIT_');?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" id="headingFive">
                    <h4 class="panel-title">
                        <?php if($accountInfo['email']){ $check = 'check'; $status = L('_YES_'); }else{ $check = 'times'; $status = L('_NO_'); } ?>
                        <i class="icon icon-<?php echo ($check); ?>"></i>
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordionPanels" href="#collapseFive">
                            邮箱验证
                        </a>
                    </h4>
                </div>
                <div id="collapseFive" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="form-group col-xs-12">
                            <label class="col-xs-2 control-label"><?php echo L('_EMAIL_ADDR_VER_');?></label>
                            <div class="col-xs-10">
                                <?php echo ($status); ?>
                            </div>
                        </div>
                        <div class="form-group col-xs-12">
                            <label class="col-xs-2 control-label"><?php echo L('_EMAIL_ADDR_');?></label>
                            <div class="col-xs-10">
                                <?php echo ((isset($accountInfo["email"]) && ($accountInfo["email"] !== ""))?($accountInfo["email"]):L('_SET_NOT_')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" id="headingSix">
                    <h4 class="panel-title"><i class="icon icon-check"></i>
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordionPanels" href="#collapseSix">
                            登录密码
                        </a>
                    </h4>
                </div>
                <div id="collapseSix" class="panel-collapse collapse col-xs-8">
                    <div class="panel-body">
                        <form id="changePasswordForm"  action="<?php echo U('Ucenter/Config/doChangePassword');?>" method="post" class="ajax-form form-horizontal">
                        <div class="form-group">
                            <label for="inputOldPassword" class="col-xs-2 control-label"><?php echo L('_PW_OLD_');?></label>

                            <div class="col-xs-10">
                                <input type="password" class="form-control" id="inputOldPassword" value="" name="old_password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputNewPassword" class="col-xs-2 control-label"><?php echo L('_PW_NEW_');?></label>

                            <div class="col-xs-10">
                                <input type="password" class="form-control" id="inputNewPassword" value="" name="new_password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputConfirmPassword" class="col-xs-2 control-label"><?php echo L('_PW_CONFIRM_');?></label>

                            <div class="col-xs-10">
                                <input type="password" class="form-control" id="inputConfirmPassword" value="" name="confirm_password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-xs-10">
                                <p class="text-danger" id="submitTip"></p>
                                <button type="submit" class="btn btn-primary"><?php echo L('_PW_SAVE_');?></button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
</div>
    <script type="text/javascript" src="/yoyo/Application/Ucenter/Static/js/expandinfo-form.js"></script>

                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    </div>
    <script type="text/javascript">
        $(function () {
            $(window).resize(function () {
                $("#main-container").css("min-height", $(window).height() - 343);
            }).resize();
        })
    </script>

<!-- /主体 -->

<!-- 底部 -->
</div>
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


    <script>
        //检查两次输入的密码是否一致
        $(function(){
            $('#changePasswordForm').submit(function(e){
                var newPassword = $('#inputNewPassword').val();
                var confirmPassword = $('#inputConfirmPassword').val();
                if(newPassword != confirmPassword) {
                    toast.error("<?php echo L('_PW_NOT_SAME_');?>");
                    e.stopPropagation();
                    return false;
                }
            })

            $(".panel-title").click(function () {
                var a = $(this).find('a');
                a[0].click();
            })
        })
    </script>
    <script>
        $(function () {

            $(".reloadverify").click(function () {
                var $this = $(this);
                var verifyimg = $this.attr("src");
                if (verifyimg.indexOf('?') > 0) {
                    $this.attr("src", verifyimg + '&random=' + Math.random());
                } else {
                    $this.attr("src", verifyimg.replace(/\?.*$/, '') + '?' + Math.random());
                }
            });
        });


        $(function () {

            $("[data-role='getVerify']").click(function () {
                var $this = $(this);
                toast.showLoading();
                var account =$this.parents('.center_info').find('#account').val();
                var type = $this.parents('.center_info').find('[name="type"]').val();

                var verify = $this.parents('.center_info').find('[name="verify"]').val();


                if(account == ''){
                    toast.error('请输入帐号');
                    toast.hideLoading();
                    return false;
                }
                if(verify == ''){
                    toast.error('请输入验证码');
                    toast.hideLoading();
                    return false;
                }



                $.post("<?php echo U('ucenter/verify/sendVerify');?>", {account: account, type: type,action:'config',verify:verify}, function (res) {
                    if(res.status){

                        DecTime.obj = $this
                        DecTime.time = "<?php echo modC('SMS_RESEND','60','USERCONFIG');?>";
                        $this.attr('disabled',true)
                        DecTime.dec_time();

                        toast.success(res.info);
                    }
                    else{
                        toast.error(res.info);
                    }
                    toast.hideLoading();
                })
            })
        })

        $(function(){
            $("[data-role='getEmailVerify']").click(function () {
                var $this = $(this);
                toast.showLoading();
                $.post("<?php echo U('ucenter/verify/sendEmailCode');?>", {action:'config'}, function (res) {
                    if(res.status){
                        DecTime.obj = $this
                        DecTime.time = "<?php echo modC('SMS_RESEND','60','USERCONFIG');?>";
                        $this.attr('disabled',true)
                        DecTime.dec_time();

                        toast.success(res.info);
                    }
                    else{
                        toast.error(res.info);
                    }
                    toast.hideLoading();
                })
            })
        })

        var DecTime = {
            obj:0,
            time:0,
            dec_time : function(){
                if(this.time > 0){
                    this.obj.text(this.time--+'S')
                    setTimeout("DecTime.dec_time()",1000)
                }else{
                    this.obj.text('获取邮箱验证码')
                    this.obj.attr('disabled',false)
                }

            }
        }
    </script>

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

<script>
    $(function () {
        var $sidebar = $('#usercenter-sidebar-td');
        var $sidebar_xs = $('#usercenter-sidebar-xs');
        var $sidebar_sm = $('#usercenter-sidebar-sm');
        var $content = $('#usercenter-content-td');

        function trigger_resp() {
            var width = $(window).width();
            if (width < 768) {
                on_xs();
            } else {
                on_sm();
            }
        }

        function on_xs() {
            $sidebar_xs.append($sidebar);
            $content.css({'padding-left': 0, 'padding-right': 0});
        }

        function on_sm() {
            $sidebar_sm.prepend($sidebar);
        }

        trigger_resp();

        $(window).resize(function () {
            trigger_resp();
        })
    })
</script>

</body>
</html>