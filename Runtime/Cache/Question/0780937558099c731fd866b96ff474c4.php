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
    
    <?php echo W('Common/SubMenu/render',array($sub_menu,$current,$MODULE_ALIAS,''));?>
    <link type="text/css" rel="stylesheet" href="/yoyo/Application/Question/Static/css/index.css"/>
    <link type="text/css" rel="stylesheet" href="//at.alicdn.com/t/font_b9rj4a9k6zx2mx6r.css"/>

    <!--顶部导航之后的钩子，调用公告等-->
<!--<?php echo hook('afterTop');?>-->
<!--顶部导航之后的钩子，调用公告等 end-->
    <div id="main-container" class="container">
        <div class="row">
            

    <div class="container" style="min-height: 400px">
        <!--打赏弹窗-->
        <div class="modal fade" id="rewardBox">
            <div class="modal-dialog" style="width: 450px">
                <div class="modal-content rewardBox">
                    <p>觉得还不错？打赏一下吧？</p>
                    <ul class="score">
                        <li class="active" data-rmb="1">1元</li>
                        <li class="" data-rmb="2">2元</li>
                        <li class="" data-rmb="3">3元</li>
                        <li class="" data-rmb="4">4元</li>
                    </ul>
                    <input type="hidden" id="rewardnum" value="1">
                    <input type="hidden" id="rewardid">
                    <input type="hidden" id="rewardtouid">
                    <div class="yesNo">
                        <a data-role="reward-money" class="ynBtn yes">打赏</a>
                        <a data-dismiss="modal" class="ynBtn no">取消</a>
                    </div>
                </div>
            </div>
        </div>

        <!--邀请回答弹窗-->
        <div class="modal fade" id="inviteBox">
            <div class="modal-dialog modal-lg">
                <div class="modal-content inviteBox">
                    <div class="intHead">
                        <p>你可以通过邀请其他用户来更快获得回答</p>
                        <div class="intSearch">
                            <input id="s_people" value="" type="text" placeholder="搜索你想邀请的人">
                            <i class="iconfont icon-search" data-role="do-search"></i>
                        </div>
                    </div>

                    <!--搜索结果列表-->
                    <ul class="qtUser" id="qtResult">

                        <li class="hah" style="display: none">
                            <div class="userLeft">
                                <div class="avatar"><img src="/yoyo/Application/Question/Static/images/aaaa.jpg" alt="用户头像"></div>
                                <div class="info">
                                    <a ucard="" href="" class="name">持枪的绅士</a>
                                    <p class="intro text-more">这是我的个性签名</p>
                                    <p class="skilled">在话题 <a href="">话题名称</a> 下有 <span>8</span> 个回答</p>
                                </div>
                            </div>
                            <div class="userRight">
                                <span class="btnInvite">邀请回答</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-xs-8">
                <div class="dtWrap">
                    <div class="dtTop">
                        <div class="dtName">
                            <i class="iconfont icon-wenti1"></i>
                            <p><?php echo ($question["title"]); ?></p>
                            <span class="state">
                                <?php if(($question["best_answer"]) != "0"): ?>已结题<?php else: ?>待回答<?php endif; ?>
                            </span>
                        </div>
                        <?php if($question['score_num'] != 0): ?><div class="dtType">
                            悬赏：<?php echo ($question["score_num"]); echo ($question["leixing_title"]["title"]); ?>
                        </div>
                            </else><?php endif; ?>
                    </div>
                    <div class="dtContent">
                        <div class="dtInfo">
                            <a target="_blank" ucard="<?php echo ($question["uid"]); ?>" href="<?php echo ($question["user"]["space_url"]); ?>"><?php echo ($question["user"]["nickname"]); ?></a>
                            <span class="qtLine"></span>
                            <span><?php echo (friendlydate($question["create_time"])); ?></span>
                            <span class="qtLine"></span>
                            <span><?php echo ($question["answer_num"]); ?> 个回答</span>
                            <span class="qtLine"></span>
                            <span class="tags">
                                <i class="iconfont icon-tag"></i>
                            <a target="_blank" href="<?php echo U('Question/index/questions',array('category'=>$question['category']));?>"><?php echo ($question["category_info"]["title"]); ?></a>
                            <?php if($question['audit_info'] != ''): echo ($question["audit_info"]); endif; ?>
                            </span>
                        </div>
                        <div class="dtMain"><?php echo (render($question["description"])); ?></div>
                        <div class="dtBottom">
                            <div class="invite">
                                <a href="" data-toggle="modal" data-position="100px" data-target="#inviteBox"><i class="iconfont icon-xingxing"></i>邀请回答</a>
                                <!--<a href="" class="police"><i class="iconfont icon-icon_tip_off"></i>举报</a>-->
                                <?php if(check_auth('Question/Index/edit',$question['uid'])): ?>&nbsp;&nbsp;<a href="<?php echo U('Question/Index/edit',array('id'=>$question['id']));?>"><?php echo L("_EDIT_");?></a><?php endif; ?>
                                <?php if($question['uid']==is_login()||check_auth('Question/Edit/delQuestion')){ ?>
                                &nbsp;&nbsp;<a  href="javascript:void(0);" onclick="delquestion($(this))" >删除</a>
                                <?php } ?>
                            </div>
                            <div class="share"><?php echo W('Common/Share/detailShare');?></div>
                        </div>
                    </div>

                </div>
                <div class="dtAnswer">
                    <div class="answer" data-role="answer-block">
                        <?php if(!empty($best_answer)): ?><div class="one_answer clearfix">
                                <div class="" style="display: flex;width: 550px">
                                    <div class="support_block">
                                        <?php if($best_answer['is_support']||$best_answer['is_oppose']||(is_login() == $best_answer['uid'])): ?><a data-role="already_support" title=<?php echo L("_SUPPORT_WITH_DOUBLE_");?> <?php if(($best_answer['is_support']) == "1"): ?>class="butt already_do"<?php else: ?>class="butt"<?php endif; ?>>
                                            <i class="icon icon-thumbs-up"></i>
                                            <br/>
                                            <span class="num"><?php echo ($best_answer["support"]); ?></span>
                                            </a>
                                            <a data-role="already_support" title=<?php echo L("_OBJECT_NOT_SHOWING_YOUR_NAME_WITH_DOUBLE_");?> <?php if(($best_answer['is_oppose']) == "1"): ?>class="butt already_do"<?php else: ?>class="butt"<?php endif; ?>>
                                            <span class="num"><?php echo ($best_answer["oppose"]); ?></span>
                                            <br/>
                                            <i class="icon icon-thumbs-down"></i>
                                            </a>
                                            <?php else: ?>
                                            <a title=<?php echo L("_SUPPORT_WITH_DOUBLE_");?> class="butt can_do" data-role="answer-support" data-id="<?php echo ($best_answer["id"]); ?>">
                                                <i class="icon icon-thumbs-up"></i>
                                                <br/>
                                                <span class="num"><?php echo ($best_answer["support"]); ?></span>
                                            </a>
                                            <a title=<?php echo L("_OBJECT_NOT_SHOWING_YOUR_NAME_WITH_DOUBLE_");?> class="butt can_do" data-role="answer-oppose" data-id="<?php echo ($best_answer["id"]); ?>">
                                                <span class="num"><?php echo ($best_answer["oppose"]); ?></span>
                                                <br/>
                                                <i class="icon icon-thumbs-down"></i>
                                            </a><?php endif; ?>
                                    </div>
                                    <div class="a_info">
                                        <i class="iconfont icon-zuijia dtIcon"></i>
                                        <div class="answer_user">
                                            <div class="cover"><img src="<?php echo ($best_answer["user"]["avatar128"]); ?>" alt=""></div>
                                            <a ucard="<?php echo ($best_answer["uid"]); ?>" href="<?php echo ($best_answer["user"]["space_url"]); ?>"><?php echo ($best_answer["user"]["nickname"]); ?></a>
                                        </div>

                                        <div class="a_content"><?php echo (render($best_answer["content"])); ?></div>

                                        <div class="q_black_info">
                                            <span class="time"> 发布于：<?php echo (friendlydate($best_answer["create_time"])); ?></span>
                                            <?php if(($best_answer["reply_count"]) != "0"): ?><a href="<?php echo U('index/detailReply',array('id'=>$best_answer['question_id'],'answer_id'=>$best_answer['id']));?>">追问 <?php echo ($best_answer["reply_count"]); ?></a><?php endif; ?>
                                            <span class="reward" data-toggle="modal" data-target="#rewardBox" style="color:#ff6600" data-question-id="<?php echo ($best_answer["question_id"]); ?>" data-to-uid="<?php echo ($best_answer["uid"]); ?>">打赏</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="btnGroup">
                                    <?php if((check_auth('Question/Answer/setBest',-1)|| ($question['uid'] == is_login())) && !$question['best_answer']): ?><p data-role="set-best" data-id="<?php echo ($answer['id']); ?>" data-question-id="<?php echo ($question["id"]); ?>">采纳</p><?php endif; ?>
                                    <?php if($question['uid'] == is_login()): ?><a href="<?php echo U('index/detailreply',array('id'=>$best_answer['question_id'],'answer_id'=>$best_answer['id']));?>">追问</a><?php endif; ?>
                                </div>
                            </div><?php endif; ?>

                        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$answer): $mod = ($i % 2 );++$i;?><div class="one_answer clearfix">
    <div class="" style="display: flex;width: 550px">
        <div class="support_block">
            <?php if($answer['is_support']||$answer['is_oppose']): ?><a data-role="already_support" title=<?php echo L("_SUPPORT_WITH_DOUBLE_");?> <?php if(($answer['is_support']) == "1"): ?>class="butt already_do"<?php else: ?>class="butt"<?php endif; ?>>
                <i class="icon icon-thumbs-up"></i>
                <br/>
                <span class="num"><?php echo ($answer["support"]); ?></span>
                </a>
                <a data-role="already_support" title=<?php echo L("_OBJECT_NOT_SHOWING_YOUR_NAME_WITH_DOUBLE_");?> <?php if(($answer['is_oppose']) == "1"): ?>class="butt already_do"<?php else: ?>class="butt"<?php endif; ?>>
                <span class="num"><?php echo ($answer["oppose"]); ?></span>
                <br/>
                <i class="icon icon-thumbs-down"></i>
                </a>
                <?php else: ?>
                <a title=<?php echo L("_SUPPORT_WITH_DOUBLE_");?> class="butt can_do" data-role="answer-support" data-id="<?php echo ($answer["id"]); ?>">
                    <i class="icon icon-thumbs-up"></i>
                    <br/>
                    <span class="num"><?php echo ($answer["support"]); ?></span>
                </a>
                <a title=<?php echo L("_OBJECT_NOT_SHOWING_YOUR_NAME_WITH_DOUBLE_");?> class="butt can_do" data-role="answer-oppose" data-id="<?php echo ($answer["id"]); ?>">
                    <span class="num"><?php echo ($answer["oppose"]); ?></span>
                    <br/>
                    <i class="icon icon-thumbs-down"></i>
                </a><?php endif; ?>
        </div>

        <div class="a_info">
            <div class="answer_user">
                <div class="cover"><img src="<?php echo ($answer["user"]["avatar128"]); ?>" alt=""></div>
                <a ucard="<?php echo ($answer["uid"]); ?>" href="<?php echo ($answer["user"]["space_url"]); ?>"><?php echo ($answer["user"]["nickname"]); ?></a>
            </div>

            <div class="a_content"><?php echo (render($answer["content"])); ?></div>

            <div class="q_black_info">
                <span class="time">发布于：<?php echo (friendlydate($answer["create_time"])); ?></span>
                <?php if(($answer["reply_count"]) != "0"): ?><a href="<?php echo U('index/detailReply',array('id'=>$answer['question_id'],'answer_id'=>$answer['id']));?>">追问 <?php echo ($answer["reply_count"]); ?></a><?php endif; ?>
                <span class="reward" style="color:#ff6600" data-toggle="modal" data-target="#rewardBox" data-question-id="<?php echo ($answer["question_id"]); ?>" data-to-uid="<?php echo ($answer["uid"]); ?>">打赏</span>
                <?php if(check_auth('Question/Answer/edit',$answer['uid'])): ?>&nbsp;&nbsp;<a href="<?php echo U('Question/Answer/edit',array('answer_id'=>$answer['id']));?>"><?php echo L("_EDIT_");?></a>
                    &nbsp;&nbsp;<a href="<?php echo U('Question/Answer/delAnswer',array('answer_id'=>$answer['id']));?>"><?php echo L("_DELETE_");?></a><?php endif; ?>
            </div>
        </div>
    </div>

    <div class="btnGroup">
        <?php if((check_auth('Question/Answer/setBest',-1)|| ($question['uid'] == is_login())) && !$question['best_answer']): ?><p data-role="set-best" data-id="<?php echo ($answer['id']); ?>" data-question-id="<?php echo ($question["id"]); ?>">采纳</p><?php endif; ?>
        <?php if($question['uid'] == is_login()): ?><a href="<?php echo U('index/detailReply',array('id'=>$answer['question_id'],'answer_id'=>$answer['id']));?>">追问</a><?php endif; ?>
    </div>

</div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                    <div class="text-right">
                        <?php echo getPagination($totalCount,10);?>
                    </div>
                </div>

                <div class="dtSend">
                    <?php if(is_login()): if($question["status"] != 1): ?><p class="text-center text-muted" style="font-size: 3em; padding-top: 2em; padding-bottom: 2em;">
                                <?php echo L("_THE_PROBLEM_IS_NOT_AUDITED_OR_AUDIT_FAILURE_CAN_NOT_BE_ANSWERED_WITH_WAVE_WITH_SPACE_");?>
                            </p>
                            <?php else: ?>
                            <?php $user = query_user(array('avatar128','uid','space_url')); ?>
                            <div class="row">
                                <div class="col-xs-2">
                                    <p class="text-center">
                                        <a href="<?php echo ($user["space_url"]); ?>" ucard="<?php echo ($user["uid"]); ?>">
                                            <img src="<?php echo ($user["avatar128"]); ?>" width="48px" class="avatar-img"/>
                                        </a>
                                    </p>
                                </div>
                                <div class="col-xs-10">
                                    <div id="answer_block">
                                        <form id="answer_form" action="<?php echo U('Question/answer/edit');?>" method="post" class="ajax-form">
                                            <input type="hidden" name="question_id" value="<?php echo ($question['id']); ?>"/>
                                            <h4><?php echo L("_ANSWER_");?></h4>

                                            <p>
                                                <?php $config="toolbars:[['source','|','bold','italic','underline','fontsize','forecolor','justifyleft','fontfamily','|','map','emotion','insertimage','insertcode']]"; ?>

                                                <?php echo W('Common/Ueditor/editor',array('myeditor_edit','content','','100%','250px',$config));?>
                                            </p>

                                            <p class="pull-right">
                                                <input type="button" data-role="reply_button" class="btn btn-primary" value="<?php echo L('_PUBLISH_');?>Ctrl+Enter"/>
                                            </p>
                                        </form>
                                    </div>
                                </div>
                            </div><?php endif; ?>
                        <script>
                            $(function(){
                                ue_myeditor_edit.addListener("beforeSubmit",function(){
                                    ue_myeditor_edit.sync();
                                    $("[data-role=reply_button]").click();
                                    return false;
                                })
                            })
                        </script>

                        <?php else: ?>
                        <p class="text-center text-muted" style="font-size: 3em; padding-top: 2em; padding-bottom: 2em;">
                            请<a data-login="quick_login"><?php echo L("_SIGN_IN_");?></a><?php echo L("_AFTER_ANSWER_");?>
                        </p><?php endif; ?>
                </div>
            </div>
            <div class="col-xs-4">
                <?php if(is_login() != 0): ?><div class="dtSelf">
                        <div class="dtAvatar"><img src="<?php echo ($my["user"]["avatar128"]); ?>" alt=""></div>
                        <div class="dtInfo">
                            <a ucard="<?php echo ($my["user"]["uid"]); ?>" href="<?php echo ($my["user"]["space_url"]); ?>"><?php echo ($my["user"]["nickname"]); ?></a>
                            <p><span><?php echo ($my["ask"]); ?>提问</span><span><?php echo ($my["answer"]); ?>回答</span></p>
                        </div>
                    </div><?php endif; ?>

                <div class="dtLink">
                    <p class="qtHead">相关问题</p>
                    <ul>
                        <?php if(is_array($relevant_question)): $i = 0; $__LIST__ = $relevant_question;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li>
                                <a href="<?php echo U('Index/detail',array('id'=>$list['id']));?>"><?php echo ($list["title"]); ?></a>
                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    </div>
    <input data-role="question" type="hidden" data-id="<?php echo ($question["id"]); ?>" data-topic-id="<?php echo ($question["topic_id"]); ?>"/>

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


    <script type="text/javascript" src="/yoyo/Application/Question/Static/js/question.js"></script>
    <script type="text/javascript" charset="utf-8" src="/yoyo/Public/static/ueditor/third-party/SyntaxHighlighter/shCore.js"></script>
    <link rel="stylesheet" type="text/css" href="/yoyo/Public/static/ueditor/third-party/SyntaxHighlighter/shCoreDefault.css"/>
    <script type="text/javascript">
        SyntaxHighlighter.all();
    </script>
    <script>
        $(document).ready(function () {
            $.post(U('Core/Public/atWhoJson'),{},function(res){
                atwho_config = {
                    at: "@",
                    data: res,
                    tpl: "<li data-value='[at:${id}]'><img class='avatar-img' style='width:2em;margin-right: 0.6em' src='${avatar32}'/>${nickname}</li>",
                    show_the_at: true,
                    search_key: 'search_key',
                    start_with_space: false
                };
                ue_myeditor_edit.addListener( 'ready', function( editor ) {
                    $(this.document.body).atwho(atwho_config);

                } );

            },'json')



            ue_myeditor_edit.addListener("beforeSubmit",function(){
                ue_myeditor_edit.sync();
                $("#reply_form").submit();
                return false;
            })



            $('.popup-gallery').each(function () { // the containers for all your galleries
                $(this).magnificPopup({
                    delegate: '.popup',
                    type: 'image',
                    tLoading: 'Loading image #%curr%...',
                    mainClass: 'mfp-img-mobile',
                    gallery: {
                        enabled: true,
                        navigateByImgClick: true,
                        preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
                    },
                    image: {
                        tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                        titleSrc: function (item) {
                            /*           return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';*/
                            return '';
                        }
                    }
                });
            });

            $('.rewardBox .score li').click(function(){
                $('.rewardBox .score li').each(function(){
                    $(this).removeClass('active');
                });
                var $this = $(this);
                $this.each(function(index,ele){
                    $this.addClass('active');
                    $('#rewardnum').val($this.attr('data-rmb'));
                })
            })
        });

        function delquestion() {
            if (confirm("你确定要删除此问题吗？")) {
                var id = "<?php echo ($question['id']); ?>";
                var url = "<?php echo U('Question/Index/delQuestion');?>";
                $.post(url, {id: id}, function (msg) {
                    if (msg.status) {
                        toast.success(msg.info);
                        setTimeout(function () {
                            window.location.href="<?php echo U('question/index/index');?>";
                        }, 500);
                    } else {
                        toast.error(msg.info);
                    }
                }, 'json')
            }
        }

        function recommend(){

            var id = "<?php echo ($question['id']); ?>";
            var url = "<?php echo U('Question/Index/recommendQuestion');?>";
            $.post(url, {id: id}, function (msg) {
                if (msg.status) {
                    toast.success(msg.info);
                    setTimeout(function () {
                        window.location.reload();
                    }, 500);
                } else {
                    toast.error(msg.info);
                }
            }, 'json')
        }

        $('#inviteBox').on('shown.zui.modal', function() {
            var $tag = $('#qtResult');
            var url = U('question/index/getQuestionRank');
            var li = '';
            var $container = $('#qtResult');
            if ($container.find('li').length == '1') {
                OS_Loading.loading($tag,'loading1');
                $.post(url,{topic_id:$('[data-role="question"]').attr('data-topic-id')},function(res){
                    if (res.status == 1) {
                        for ( var i in res.info){
                            var t = res.info[i];
                            li += '<li>'+
                                    '<div class="userLeft">'+
                                    '<div class="avatar"><img src="'+ t.user.avatar128 +'" alt="用户头像"></div>'+
                                    '<div class="info">'+
                                    '<a ucard="" href="" class="name">'+ t.user.nickname +'</a>'+
                                    '<p class="intro text-more">这是我的个性签名</p>'+
                                    '<p class="skilled">在问答达人中 <span>'+ t.answer_count +'</span>次回答 <span>'+ t.support_count+'</span>个赞同</p>'+
                                    '</div>'+
                                    '</div>'+
                                    '<div class="userRight">'+
                                    '<span class="btnInvite" data-role="invite" data-uid="'+ t.user.uid +'">邀请回答</span>'+
                                    '</div>'+
                                    '</li>';
                        }
                    } else if(res.status == 2){
                        for ( var i in res.info){
                            var result = res.info[i].res;
                            var topic = res.info[i].topic;
                            for (var j in result) {
                                var t = result[j];
                                var url = U('question/index/questions',['topic',topic.id]);
                                li += '<li>'+
                                        '<div class="userLeft">'+
                                        '<div class="avatar"><img src="'+ t.user.avatar128 +'" alt="用户头像"></div>'+
                                        '<div class="info">'+
                                        '<a ucard="" href="" class="name">'+ t.user.nickname +'</a>'+
                                        '<p class="intro text-more">这是我的个性签名</p>'+
                                        '<p class="skilled">在话题 <a href="'+ url +'">'+topic.title+'</a> 下有 <span>'+ topic.num +'</span> 个回答</p>'+
                                        '</div>'+
                                        '</div>'+
                                        '<div class="userRight">'+
                                        '<span class="btnInvite" data-role="invite" data-uid="'+ t.user.uid +'">邀请回答</span>'+
                                        '</div>'+
                                        '</li>';
                            }
                        }
                    }
                    OS_Loading.remove($tag);
                    $container.append(li);
                    $('[data-role="invite"]').click(function(){
                        var $this = $(this);
                        var uid = $this.attr('data-uid');
                        var question = $('[data-role="question"]').attr('data-id');
                        var url = U('question/index/inviteAnswer');
                        $.post(url,{uid:uid,question:question},function(res){
                            if (res.status == 1) {
                                toast.success(res.info);
                                $this.html('已邀请');
                                $this.unbind('click');
                            }
                        });
                    })
                });
            }
        });

        $('[data-role="reward-money"]').click(function(){
            var money = $('#rewardnum').val();
            var question_id = $('#rewardid').val();
            var to_uid = $('#rewardtouid').val();
            var url = U('question/index/reward');
            $.post(url,{money:money,to_uid:to_uid,question_id:question_id},function(res){
                handleAjax(res);
                $('#rewardBox').modal('hide');
            })
        });

        $('[data-role="reply-answer"]').click(function(){
            var $this = $(this);
            var url = U('Question/answer/replyAnswer');
            var question_id = $this.attr('data-question-id');
            var reply_id = $this.attr('data-reply-id');
            var content = $this.parent().prev().children(".textarea").val();
            $.post(url,{question_id:question_id,reply_id:reply_id,content:content},function(res){
                handleAjax(res)
            });
        });

        $('[data-target="#rewardBox"]').click(function(){
            var $this = $(this);
            var to_uid = $this.attr('data-to-uid');
            var question_id = $this.attr('data-question-id');
            $('#rewardid').val(question_id);
            $('#rewardtouid').val(to_uid);
        });
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
</body>
</html>