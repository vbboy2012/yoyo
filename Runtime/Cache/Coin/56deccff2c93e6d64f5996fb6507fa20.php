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




    <link href="/yoyo/Application/Coin/Static/css/event.css" rel="stylesheet" type="text/css"/>

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
    
    <!--顶部导航之后的钩子，调用公告等-->
<!--<?php echo hook('afterTop');?>-->
<!--顶部导航之后的钩子，调用公告等 end-->
    <div id="main-container" class="container">
        <div class="row">
            
    <!--导航-->
    <div class="col-xs-8">
        <div class="forum_module" style="min-height: 800px">
            <?php $price = number_format($tradead['price'],2); $coinType = $tradead['coin_type'] == 1 ? 'BTC':'ETH'; if($tradead['coin_type'] == 1 && ($tradead['type'] == 1 || $tradead['type'] == 3)){ $action = L('_WISHES_').L('_SELL_').$coinType.L('_GIVE_YOU_'); $tipRegister = L('_BUY_').$coinType; }else if($tradead['coin_type'] == 1 && ($tradead['type'] == 2 || $tradead['type'] == 4)){ $action = L('_WISHES_').L('_BUY_FROM_').$coinType; $tipRegister = L('_SELL_').$coinType; }else if($tradead['coin_type'] == 2 && ($tradead['type'] == 1 || $tradead['type'] == 3)){ $action = L('_WISHES_').L('_SELL_').$coinType.L('_GIVE_YOU_'); $tipRegister = L('_BUY_').$coinType; }else if($tradead['coin_type'] == 2 && ($tradead['type'] == 2 || $tradead['type'] == 4)){ $action = L('_WISHES_').L('_BUY_FROM_').$coinType; $tipRegister = L('_SELL_').$coinType; } if(is_login()){ $buttonTxt = L('_AD_SEND_TRADE_REQUEST_'); }else{ $buttonTxt = L('_FREE_REGISTER_'); } $tipRegister = L('_FREE_REGISTER_30_',array('action'=>$tipRegister)); ?>

            <div class="ad-title">
                <div class="no-event">在 <?php echo ($tradead['country']); ?> 以 (<?php echo ($tradead['currency']); ?>) 购买 <?php echo ($coinType); ?></div>
                <div>YOYOCOINS.com <?php echo L('_USER_');?> <?php echo ($tradead["nickname"]); ?> <?php echo ($action); ?></div>
            </div>
            <div class="aline" style="margin-bottom: 35px"></div>
            <div class="ad-form">
                <form class="form-horizontal ajax-form" id="trade-form" method="post">
                    <input type="hidden" name="adId" value="<?php echo ($tradead["id"]); ?>">
                    <input type="hidden" name="adUid" value="<?php echo ($tradead["uid"]); ?>">
                    <div class="form-group tradeorder">
                        <div class="col-xs-3">
                            <label>价格：</label>
                        </div>
                        <div class="col-xs-9">
                            <label style="color: #0C7F12"> <?php echo ($price); ?> <?php echo ($tradead['currency']); ?>/<?php echo ($coinType); ?></label>
                        </div>
                    </div>
                    <div class="form-group tradeorder">
                        <div class="col-xs-3">
                            <label>付款方式：</label>
                        </div>
                        <div class="col-xs-9">
                            <?php echo ($payName); ?>
                        </div>
                    </div>
                    <div class="form-group tradeorder">
                        <div class="col-xs-3">
                            <label>交易限额：</label>
                        </div>
                        <div class="col-xs-9">
                            <?php $minPrice = number_format($tradead['min_price']); $maxPrice = number_format($tradead['max_price']); ?>
                            <?php echo ($minPrice); ?>-<?php echo ($maxPrice); ?> <?php echo ($tradead['currency']); ?>
                        </div>
                    </div>
                    <div class="form-group tradeorder">
                        <div class="col-xs-3">
                            <label>所在地：</label>
                        </div>
                        <div class="col-xs-9">
                            <?php echo ($tradead['country']); ?>
                        </div>
                    </div>
                    <div class="form-group tradeorder">
                        <div class="col-xs-3">
                            <label>付款期限：</label>
                        </div>
                        <div class="col-xs-9">
                            <?php echo ($tradead['pay_time']); ?> 分钟 <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" data-tip-class="tooltip-info" title="<?php echo L('_TIME_TIPS_');?>"><i class="icon icon-question-sign"></i></a>
                        </div>
                    </div>
                    <div class="form-group tradeorder">
                        <div class="col-xs-12">
                            <i class="icon icon-flag"></i><a href="<?php echo U('/support/request/5/'.$tradead['id']);?>">举报这条交易广告？</a>
                        </div>
                    </div>
                    <?php if($tradead['uid'] == get_uid()): ?><div class="alert alert-warning" style="margin-top: 10px">您无法向自己的交易广告发出交易请求。</div>
                        <?php else: ?>
                        <div class="form-group tradeorder" style="margin-top: 20px">
                            <div class="col-xs-5">
                                <div class="input-group input-group-lg">
                                    <input type="text" class="form-control" id="trade_price" name="trade_price">
                                    <span class="input-group-addon">CNY</span>
                                </div>
                            </div>
                            <div class="col-xs-2" style="text-align: center;padding-top: 5px"><img src="/yoyo/Application/Coin/Static/images/xchange.png" style="width: 20%"></div>
                            <div class="col-xs-5">
                                <div class="input-group input-group-lg">
                                    <input type="text" class="form-control" id="coinNum" name="coin_num">
                                    <span class="input-group-addon">BTC</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input id="status" type="hidden" name="status" value="0">
                                <label id="show-min" style="display: none;color: red">您可以从此广告中购买的最小金额为 <?php echo ($minPrice); ?> <?php echo ($tradead['currency']); ?>。</label>
                                <label id="show-max" style="display: none;color: red">您可以从此广告中购买的最大金额为 <?php echo ($maxPrice); ?> <?php echo ($tradead['currency']); ?>。</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <?php if(is_login()): ?><textarea name="pay_text" class="text form-control" placeholder="<?php echo L('_AD_PAY_TEXT2_TIPS_LABEL_');?>" style="height: 8em;height: 100px"></textarea>
                                    <?php else: ?>
                                    <div class="alert alert-success" style="margin-top: 10px;margin-bottom: 0px"><?php echo ($tipRegister); ?></div><?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-6 col-md-offset-3" style="text-align: center">
                                <?php if(is_login()): ?><a href="javascript:void(0);" data-role="submit" class="app-btn"><?php echo ($buttonTxt); ?></a>
                                    <?php else: ?>
                                    <a href="<?php echo U('/ucenter/member/register');?>" class="app-btn"><?php echo ($buttonTxt); ?></a><?php endif; ?>
                            </div>
                        </div><?php endif; ?>
                </form>

            </div>
            <div class="aline" style="margin-bottom: 35px"></div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <?php echo ($tradead["nickname"]); ?> 的广告留言
                </div>
                <div class="panel-body">
                    <?php echo ($tradead["pay_text"]); ?>
                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    交易须知
                </div>
                <div class="panel-body">
                    <p>1，<?php echo L('_TRADE_KNOW1_');?></p>
                    <p>2，<?php echo L('_TRADE_KNOW2_');?></p>
                    <p>3，<?php echo L('_TRADE_KNOW3_');?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-4">
        <div class="common_block_border event_right">
    <div class="common_block_title_right" style="text-align: center;">
        <?php if($tradead['driver'] == 'local'){ $img = "/yoyo/Uploads/Avatar/".$tradead['path']; }else{ $img = $tradead['path']."/thumbnail/64x64!"; } ?>
        <div><a href=""><img src="<?php echo ($img); ?>" class="img-circle" style="padding-right: 10px"></a></div>
        <div><label><a href="" style="color: cornflowerblue"><?php echo ($tradead["nickname"]); ?></a></label></div>
        <div style="margin-top: 20px;color: cornflowerblue">
            <span><i class="icon icon-envelope icon-2x pad10"></i></span><span><i class="icon icon-phone icon-2x pad10"></i></span><span><i class="icon icon-user icon-2x pad10"></i></span>
        </div>
        <div style="margin-top: 20px">
            <p>信任人数：<?php echo ($tradead["fans"]); ?></p>
            <p>好评：<?php echo ($tradead["trade_score"]); ?>%</p>
            <p>交易次数：<?php echo ($tradead["trade_count"]); ?></p>
            <p>平均放行：10分钟</p>
        </div>
        <?php if(is_login()): ?><div class="bt-group">
                <?php echo W('Common/Follow/follow',array('follow_who'=>$tradead['uid']));?>
                <?php echo W('Common/Follow/trust',array('follow_who'=>$tradead['uid']));?>
            </div><?php endif; ?>
    </div>
</div>


    </div>
    <script>
        $(function () {
            $('[data-role="submit"]').click(function () {
                var $tag=$(this);
                if($tag.attr('disabled')=='disabled'){
                    return false;
                }
                $tag.attr('disabled','disabled');
                var param=$('#trade-form').serialize();
                var url=U('Coin/Index/tradead');
                $.post(url,param,function (msg) {
                    if(msg.status == 0){
                        $tag.removeAttr('disabled');
                    }
                    handleAjax(msg);
                })
            })
        })
        var minPrice = <?php echo ($tradead['min_price']); ?>;
        var maxPrice = <?php echo ($tradead['max_price']); ?>;
        var ratePrice = <?php echo ($tradead['price']); ?>;
        $("#trade_price").bind('input propertychange',function(){
            $(this).val($(this).val().replace(/[^\d.]/g,""));
            $(this).val($(this).val().replace(/^\./g,""));
            $(this).val($(this).val().replace(/\.{2,}/g,"."));
            $(this).val($(this).val().replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3'));
            var price = $(this).val();
            if(price < minPrice){
                $("#show-max").css('display','none');
                $("#show-min").css('display','block');
                $("#status").val('0');
            }
            else if(price > maxPrice){
                $("#show-max").css('display','block');
                $("#show-min").css('display','none');
                $("#status").val('0');
            }else{
                $("#show-max").css('display','none');
                $("#show-min").css('display','none');
                $("#status").val('1');
            }
            price = price/ratePrice;
            $("#coinNum").val(price.toFixed(6));

        })
        $("#coinNum").bind('input propertychange',function(){
            $(this).val($(this).val().replace(/[^\d.]/g,""));
            $(this).val($(this).val().replace(/^\./g,""));
            $(this).val($(this).val().replace(/\.{2,}/g,"."));
            $(this).val($(this).val().replace(/^(\-)*(\d+)\.(\d\d\d\d).*$/,'$1$2.$3'));
            var num = $(this).val();
            var price = num*ratePrice;
            $("#trade_price").val(price.toFixed(2));
            if(price < minPrice){
                $("#show-max").css('display','none');
                $("#show-min").css('display','block');
                $("#status").val('0');
            }
            else if(price > maxPrice){
                $("#show-max").css('display','block');
                $("#show-min").css('display','none');
                $("#status").val('0');
            }else{
                $("#show-max").css('display','none');
                $("#show-min").css('display','none');
                $("#status").val('1');
            }
        })
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


    <script>
        $('[data-toggle="tooltip"]').tooltip();
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