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
    <?php $type = $order['coin_type'] == 1?'BTC':'ETH'; $coinNum = floatval($order['coin_num']); $price = number_format($order['price'],2); $updateTime = date('Y-m-d H:i:s', $order['update_time']); $params = ''; $buyer = ''; $seller = ''; $uid = is_login(); if($order['type'] == 1){ $params = 'sellonline-'; $seller = $order['nickname']; $buyer = $getUser['getName']; }else if($order['type'] == 2){ $params = 'buyonline-'; $buyer = $order['nickname']; $seller = $getUser['getName']; }else if($order['type'] == 3){ $params = 'selllocal-'; $seller = $order['nickname']; $buyer = $getUser['getName']; }else if($order['type'] == 4){ $params = 'buylocal-'; $buyer = $order['nickname']; $seller = $getUser['getName']; } $params.=$order['payType']."-".$order['countryEn']; $pay_time = $order['pay_time'] * 60; $timer = (time()-$order['create_time']); $remainTime = ceil(($pay_time-$timer)/60); if($order['status'] ==1){ $statusText = L('_TRADE_STATUS1_'); if($order['type'] == 1 || $order['type'] == 3){ $sellInfo = L('_TRADE_SELL_INFO1_',array('buyer'=>$getUser['getName'],'tradeCount'=>$getUser['tradeCount'],'tradeScore'=>$getUser['tradeScore'],'coinNum'=>$coinNum,'type'=>$type,'payText'=>$order['pay_text'],'trade_price'=>$order['trade_price'],'currency'=>$order['currency'],'pay_code'=>$order['pay_code'])); }else if($order['type'] == 2 || $order['type'] ==4){ $sellInfo = L('_TRADE_SELL_INFO1_',array('buyer'=>$order['nickname'],'tradeCount'=>$order['trade_count'],'tradeScore'=>$order['trade_score'],'coinNum'=>$coinNum,'type'=>$type,'payText'=>$order['pay_text'],'trade_price'=>$order['trade_price'],'currency'=>$order['currency'],'pay_code'=>$order['pay_code'])); } $buyInfo = L('_TRADE_BUY_INFO1_',array('time'=>$order['pay_time'])); }else if($order['status'] == 2){ $statusText = L('_TRADE_STATUS2_'); $buyInfo = L('_TRADE_BUY_INFO2_',array('updateTime'=>$updateTime,'seller'=>$seller)); if($order['type'] == 1 || $order['type'] == 3){ $sellInfo = L('_TRADE_SELL_INFO1_',array('buyer'=>$getUser['getName'],'tradeCount'=>$getUser['tradeCount'],'tradeScore'=>$getUser['tradeScore'],'coinNum'=>$coinNum,'type'=>$type,'payText'=>$order['pay_text'],'trade_price'=>$order['trade_price'],'currency'=>$order['currency'],'pay_code'=>$order['pay_code'])); }else if($order['type'] == 2 || $order['type'] ==4){ $sellInfo = L('_TRADE_SELL_INFO1_',array('buyer'=>$order['nickname'],'tradeCount'=>$order['trade_count'],'tradeScore'=>$order['trade_score'],'coinNum'=>$coinNum,'type'=>$type,'payText'=>$order['pay_text'],'trade_price'=>$order['trade_price'],'currency'=>$order['currency'],'pay_code'=>$order['pay_code'])); } }else if($order['status'] == 3){ $statusText = L('_TRADE_STATUS3_'); }else if($order['status'] == 4){ $statusText = L('_TRADE_STATUS4_'); }else if($order['status'] == 5 || $order['status'] == 6){ $statusText = L('_TRADE_STATUS0_'); if($order['status'] == 5){ $who = $buyer; }else if($order['status'] == 6){ $who = 'YOYOCOINS工作人员'; } $buyInfo = $sellInfo = L('_TRADE_BUY_INFO0_',array('who'=>$who,'updateTime'=>$updateTime)); } ?>
    <div class="col-xs-12">
        <div class="alert alert-primary" style="background: #fff;margin-top: 0px;margin-bottom: 10px">
            <h1>订单#<?php echo ($order['order_id']); ?>：以 <?php echo ($order["trade_price"]); ?> CNY 购买 <?php echo ($coinNum); ?> <?php echo ($type); ?></h1>
            <p><a href="<?php echo U('Ucenter/index/information',array('uid'=>$order['ad_uid']));?>"><?php echo ($order['nickname']); ?></a> 的交易广告# <a href="tradead/<?php echo ($order['ad_id']); ?>/<?php echo ($params); ?>"><?php echo ($order['ad_id']); ?></a>，汇率 <?php echo ($order["price"]); ?> <?php echo ($order["currency"]); ?>/<?php echo ($type); ?></p>
        </div>
        <div class="alert alert-success" style="margin-top: 0px;margin-bottom: 10px">交易状态：<label><?php echo ($statusText); ?></label></div>
    </div>
    <div class="col-xs-8">
        <div class="panel panel-info" style="min-height: 800px">
            <div class="panel-heading">
                <div id="search">
                    <div id="searchForm">
                        <input id="chat_id" type="hidden" value="0">
                        <?php $talk_self=query_user(array('avatar128')); ?>
                        <script>
                            var myhead = "<?php echo ($talk_self["avatar128"]); ?>";
                        </script>
                        <input type="text" class="form-control input-lg" id="chat_content"/>
                        <span id="web_uploader_wrapper_gallary_image"><i class="icon icon-picture icon-2x"></i></span>
                        <button id="searchHelpBtn" type="button" class="btn btn-link" onclick="talker.post_message()"><?php echo L('_SEND_');?></button>
                    </div>
                </div>
            </div>
            <?php $currentSession=D('Common/Talk')->getCurrentOrderSessions($order['order_id']); D('Common/TalkPush')->clearAll(); ?>
            <?php if(count($currentSession) != 0): ?><script>
                    $(function () {
                        talker.open("<?php echo ($currentSession["0"]["id"]); ?>");
                    })
                </script><?php endif; ?>
            <div class="panel-body" id="chat_box">
                <div class="row talk-body" style="background: none">
                    <div id="" class="row">
                        <div id="scrollContainer_chat">
                            <div class="text-muted" style="line-height: 258px;text-align: center;font-size: 32px"><?php echo L('_TALK_NONE_');?></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <h1 id="myHeader">这是标题</h1>
    <div class="col-xs-4" style="z-index: 99">
        <div class="common_block_border event_right">
    <div class="ardor">
        <p class="qtHead"><label><?php echo L('_TRADE_OPER_');?></label><label><span id="timer"></span></label></p>
        <?php if(($order['type'] == 1 or $order['type'] == 3) and $order['get_uid'] == get_uid()): ?><div class="alert alert-primary" style="margin-top: 0px;background: none">
                <?php if($order['status'] == 1): ?><h4><?php echo L('_TRADE_STEP1_');?></h4>
                    <hr>
                    <p><?php echo L('_TRADE_STEP1_INFO_',array('seller'=>$seller));?></p>
                    <?php elseif($order['status'] == 2 or $order['status'] == 4): ?>
                    <h4><?php echo L('_TRADE_STEP3_');?></h4>
                    <hr>
                    <p><?php echo L('_TRADE_STEP3_INFO_',array('updateTime'=>$updateTime,'seller'=>$seller));?></p>
                    <?php elseif($order['status'] == 3): ?>
                    <h4><?php echo L('_TRADE_INFO_');?></h4>
                    <hr>
                    <p>这笔交易在2017-11-12 12:03:17已经关闭。您仍然可以给交易方发送留言。</p>
                    <?php elseif($order['status'] == 5 or $order['status'] == 6): ?>
                    <h4><?php echo L('_TRADE_INFO_');?></h4>
                    <hr>
                    <p><?php echo ($buyInfo); ?></p><?php endif; ?>
                <p>交易状态：<span style="color: #FF0000"><?php echo ($statusText); ?></span></p>
                <div style="margin-top: 5px;margin-bottom: 10px;border:1px solid #D4D4D4;border-radius:5px;padding: 5px 5px">
                    <label>购买人：</label><a class="ardora" href="<?php echo U('/ucenter/index/information/uid/'.$order['get_uid']);?>"><?php echo ($buyer); ?></a>
                    <br>
                    <label>出售人：</label><a class="ardora" href="<?php echo U('/ucenter/index/information/uid/'.$order['ad_uid']);?>"><?php echo ($seller); ?></a>
                    <br><label>托管金额：</label><?php echo ($coinNum); ?> <?php echo ($type); ?><br>
                    <label>付款备注：</label><?php echo ($order["pay_remark"]); ?>
                    <br><label>付款金额：</label><?php echo ($order["trade_price"]); ?> <?php echo ($order["currency"]); ?>
                    <br><label>付款参考码：</label><?php echo ($order["pay_code"]); ?>
                </div>
                <?php if($order['status'] == 1): ?><h4><?php echo L('_TRADE_STEP2_');?></h4>
                    <hr>
                    <p><?php echo L('_TRADE_STEP2_INFO_',array('time'=>$order['pay_time'],''));?></p><?php endif; ?>
                <?php if($order['status'] == 1){ ?>
                <hr>
                <a href="javascript:void(0)" class="btn btn-info" name="pay-ok">我已付款</a>
                <a href="javascript:void(0)" class="btn btn-default" id="cancel-trade" style="float: right">取消交易</a>
                <?php }else if($order['status'] == 2 or $order['status'] == 4){ ?>
                <p style="margin-top: 20px">1，<?php echo L('_TRADE_BUY_INFO1_');?><br>2，<?php echo L('_TRADE_BUY_INFO3_');?></p>
                <hr>
                <?php if($order['status']!=4){ $right="float: right"; ?>
                <a href="#appeal" data-toggle="collapse" id="appeal-trade" class="btn btn-danger">申&nbsp;诉</a>
                <?php }else{ $right=""; } ?>
                <a href="javascript:void(0)" class="btn btn-default" id="cancel-trade" style="<?php echo ($right); ?>">取消交易</a>
                <div class="collapse" id="appeal">
                    <h4><label>提交申诉</label></h4>
                    <hr>
                    <form class="form-horizontal ajax-form" id="appeal-form" method="post">
                        <input type="hidden" name="question_id" value="<?php echo ($order['order_id']); ?>">
                        <div class="form-group">
                            <label class="col-sm-3"><?php echo L('_TRADE_INFO1_');?></label>
                            <div class="col-sm-9">
                                <select name="type" style="width: 100%">
                                    <option value="8">
                                    <?php echo L('_TICKET_QUESTION8_');?>
                                    </option>
                                    <option value="9">
                                    <?php echo L('_TICKET_QUESTION9_');?>
                                    </option>
                                    <option value="7">
                                    <?php echo L('_TICKET_QUESTION7_');?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3"><?php echo L('_TRADE_INFO2_');?></label>
                            <div class="col-xs-9">
                                <span id="web_uploader_wrapper_gallary_other_image"><i class="icon icon-picture icon-2x"></i></span>
                                <input id="web_uploader_input_gallary_other_image" type="hidden" value=""  event-node="uploadinput">

                                <div id="web_uploader_picture_list_gallary_other_image" class="web_uploader_picture_list">
                                    <?php if(is_array($attest["image"])): $i = 0; $__LIST__ = $attest["image"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$o): $mod = ($i % 2 );++$i;?><img class="gallary_thumb" onclick="remove_file(this,'other_image')" src="<?php echo (get_cover($o,'path')); ?>">
                                        <input type="hidden" name="image[]" value="<?php echo ($o); ?>"/><?php endforeach; endif; else: echo "" ;endif; ?>
                                </div>
                                <span class="help-block"><?php echo ($attest_type["fields"]["other_image_tip"]); ?></span>
                            </div>
                        </div>

                        <div class="">
                            <label>请上传支持您的证据，可以是以下几类</label>
                            <ul style="margin-left: 20px">
                                <li>付款的收据</li>
                                <li>银行对账单</li>
                                <li>个人身份证</li>
                                <li>或者是YOYOCOINS客服所要求的任何文档</li>
                            </ul>
                        </div>
                        文件描述
                        <div>
                            <textarea style="width: 100%;height: 100px" name="content"></textarea>
                        </div>
                        <a href="javascript:void(0);" data-role="send-appeal" class="btn btn-info" style="margin-top: 10px">发起申诉</a>
                        <a href="#appeal" id="cancel-appeal" href="#appeal" data-toggle="collapse" class="btn btn-default" style="margin-top: 10px;float: right">取消申诉</a>
                    </form>
                </div>
                <?php }else if($order['status'] == 3){ ?>
                <hr style="margin-top: 15px">
                <?php if($evaModel){ $block = 'display: block'; $collapse = 'collapse'; }else{ $collapse = ''; $block = 'display: none'; } ?>
                <h4 style="<?php echo ($block); ?>">
                    <a href="#updateEva" id="update-eva" data-toggle="collapse" class="ardora">更新您对用户<?php echo ($seller); ?>的评价</a>
                </h4>
                <div class="<?php echo ($collapse); ?>" id="updateEva">
                    <h4 style="margin-bottom: 0px"><?php echo L('_TRADE_INFO3_');?><a class="ardora" href="<?php echo U('/ucenter/index/information/uid/'.$order['ad_uid']);?>"><?php echo ($seller); ?></a><?php echo L('_TRADE_INFO4_');?></h4>
                    <hr>
                    交易者评级：
                    <br>
                    <form class="ajax-form" id="eva-form">
                        <input type="hidden" name="evaUid" value="<?php echo ($order['ad_uid']); ?>">
                        <input type="hidden" name="orderId" value="<?php echo ($order['order_id']); ?>">
                        <div style="margin-top: 5px;margin-bottom: 10px;border:1px solid #D4D4D4;border-radius:5px;padding: 5px 5px">
                            <div class="haoping">
                                <label> <input name="evaLevel" value="1" type="radio" <?php if($evaModel['eva_level'] == 1): ?>checked<?php endif; ?>/> <?php echo L('_TRADE_INFO5_');?></label>
                                <p><?php echo L('_TRADE_INF10_');?></p>
                            </div>
                            <div class="haoping">
                                <label> <input name="evaLevel" value="2" type="radio" <?php if($evaModel['eva_level'] == 2): ?>checked<?php endif; ?>/> <?php echo L('_TRADE_INFO6_');?></label>
                                <p><?php echo L('_TRADE_INF11_');?></p>
                            </div>
                            <div class="haoping">
                                <label> <input name="evaLevel" value="3" type="radio" <?php if($evaModel['eva_level'] == 3): ?>checked<?php endif; ?>/> <?php echo L('_TRADE_INFO7_');?></label>
                                <p><?php echo L('_TRADE_INF12_');?></p>
                            </div>
                            <div class="chaping">
                                <label> <input name="evaLevel" value="4" type="radio" <?php if($evaModel['eva_level'] == 4): ?>checked<?php endif; ?>/> <?php echo L('_TRADE_INFO8_');?></label>
                                <p><?php echo L('_TRADE_INF13_');?></p>
                            </div>
                            <div class="chaping">
                                <label> <input name="evaLevel" value="5" type="radio" <?php if($evaModel['eva_level'] == 5): ?>checked<?php endif; ?>/> <?php echo L('_TRADE_INFO9_');?></label>
                                <p><?php echo L('_TRADE_INF14_');?></p>
                            </div>
                        </div>
                        评价信息（可选）：
                        <div>
                            <textarea style="width: 100%" name="evaInfo"><?php echo ($evaModel['eva_info']); ?></textarea>
                        </div>
                        <a href="javascript:void(0);" data-role="submit" class="btn btn-primary" style="margin-top: 10px;outline: none">评价</a>
                    </form>
                </div>
                <?php } ?>
            </div>
            <?php if($order['status'] ==1 or $order['status'] ==2 or $order['status'] == 4): ?><div class="alert alert-info" style="margin-top: 0px">
                    <p><?php echo L('_TRADE_TIPS1_');?></p>
                    <p><?php echo L('_TRADE_TIPS2_');?></p>
                </div><?php endif; ?>
            
            <?php elseif(($order['type'] == 1 or $order['type'] == 3) and $order['ad_uid'] == get_uid()): ?>
            <div class="alert alert-primary" style="margin-top: 0px;background: none">
                <h4><?php echo L('_TRADE_INFO_');?></h4>
                <hr>
                <?php if($order['status'] == 3): ?><p>这笔交易在2017-11-12 12:03:17已经关闭。您仍然可以给交易对方发送留言。</p>
                <?php elseif($order['status'] == 5 or $order['status'] == 6): ?>
                    <p><?php echo ($buyInfo); ?></p><?php endif; ?>
                <p>交易状态：<span style="color: #FF0000"><?php echo ($statusText); ?></span></p>
                <div style="margin-top: 5px;margin-bottom: 10px;border:1px solid #D4D4D4;border-radius:5px;padding: 5px 5px">
                    <label>购买人：</label><a class="ardora" href="<?php echo U('/ucenter/index/information/uid/'.$order['get_uid']);?>"><?php echo ($buyer); ?></a>
                    <br>
                    <label>出售人：</label><a class="ardora" href="<?php echo U('/ucenter/index/information/uid/'.$order['ad_uid']);?>"><?php echo ($seller); ?></a>
                    <br>
                    <label>托管金额：</label><?php echo ($coinNum); ?> <?php echo ($type); ?><br>
                    <label>付款备注：</label><?php echo ($order["pay_remark"]); ?>
                    <br><label>付款金额：</label><?php echo ($order["trade_price"]); ?> <?php echo ($order["currency"]); ?>
                    <br><label>付款参考码：</label><?php echo ($order["pay_code"]); ?>
                </div>
                <?php if($order['status'] == 3){ ?>
                <hr style="margin-top: 15px">
                <?php if($evaModel){ $block = 'display: block'; $collapse = 'collapse'; }else{ $collapse = ''; $block = 'display: none'; } ?>
                <h4 style="<?php echo ($block); ?>">
                    <a href="#collapseExample" id="update-eva" data-toggle="collapse" class="ardora">更新您对用户<?php echo ($buyer); ?>的评价</a>
                </h4>
                <div class="<?php echo ($collapse); ?>" id="collapseExample">
                    <h4 style="margin-bottom: 0px"><?php echo L('_TRADE_INFO3_');?><a class="ardora" href="<?php echo U('/ucenter/index/information/uid/'.$order['get_uid']);?>"><?php echo ($buyer); ?></a><?php echo L('_TRADE_INFO4_');?></h4>
                    <hr>
                    交易者评级：
                    <br>
                    <form class="ajax-form" id="eva-form">
                        <input type="hidden" name="evaUid" value="<?php echo ($order['get_uid']); ?>">
                        <input type="hidden" name="orderId" value="<?php echo ($order['order_id']); ?>">
                        <div style="margin-top: 5px;margin-bottom: 10px;border:1px solid #D4D4D4;border-radius:5px;padding: 5px 5px">
                            <div class="haoping">
                                <label> <input name="evaLevel" value="1" type="radio" <?php if($evaModel['eva_level'] == 1): ?>checked<?php endif; ?>/> <?php echo L('_TRADE_INFO5_');?></label>
                                <p><?php echo L('_TRADE_INF10_');?></p>
                            </div>
                            <div class="haoping">
                                <label> <input name="evaLevel" value="2" type="radio" <?php if($evaModel['eva_level'] == 2): ?>checked<?php endif; ?>/> <?php echo L('_TRADE_INFO6_');?></label>
                                <p><?php echo L('_TRADE_INF11_');?></p>
                            </div>
                            <div class="haoping">
                                <label> <input name="evaLevel" value="3" type="radio" <?php if($evaModel['eva_level'] == 3): ?>checked<?php endif; ?>/> <?php echo L('_TRADE_INFO7_');?></label>
                                <p><?php echo L('_TRADE_INF12_');?></p>
                            </div>
                            <div class="chaping">
                                <label> <input name="evaLevel" value="4" type="radio" <?php if($evaModel['eva_level'] == 4): ?>checked<?php endif; ?>/> <?php echo L('_TRADE_INFO8_');?></label>
                                <p><?php echo L('_TRADE_INF13_');?></p>
                            </div>
                            <div class="chaping">
                                <label> <input name="evaLevel" value="5" type="radio" <?php if($evaModel['eva_level'] == 5): ?>checked<?php endif; ?>/> <?php echo L('_TRADE_INFO9_');?></label>
                                <p><?php echo L('_TRADE_INF14_');?></p>
                            </div>
                        </div>
                        评价信息（可选）：
                        <div>
                            <textarea style="width: 100%" name="evaInfo"><?php echo ($evaModel['eva_info']); ?></textarea>
                        </div>
                        <a data-role="submit" class="btn btn-primary" style="margin-top: 10px;outline: none">评价</a>
                    </form>
                <?php } ?>
                <?php if($order['status'] == 1 or $order['status'] ==2 or $order['status'] ==4): ?><p>1，<?php echo L('_TRADE_STEP4_',array('payTime'=>$order['pay_time']));?></p>
                    <p>2，<?php echo L('_TRADE_BUY_INFO3_');?></p>
                    <hr>
                    <a href="#appeal" data-toggle="collapse" id="appeal-trade" class="btn btn-danger">申&nbsp;诉</a>
                    <a href="javascript:void(0)" class="btn btn-info" id="send-coin" name="send-coin" style="float: right">放行比特币</a><?php endif; ?>
                    <div class="collapse" id="appeal">
                        <h4><label>提交申诉</label></h4>
                        <hr>
                        <form class="form-horizontal ajax-form" id="appeal-form" method="post">
                            <input type="hidden" name="question_id" value="<?php echo ($order['order_id']); ?>">
                            <div class="form-group">
                                <label class="col-sm-3"><?php echo L('_TRADE_INFO1_');?></label>
                                <div class="col-sm-9">
                                    <select name="type" style="width: 100%">
                                        <option value="8">
                                        <?php echo L('_TICKET_QUESTION8_');?>
                                        </option>
                                        <option value="10">
                                        <?php echo L('_TICKET_QUESTION10_');?>
                                        </option>
                                        <option value="7">
                                        <?php echo L('_TICKET_QUESTION7_');?>
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3"><?php echo L('_TRADE_INFO2_');?></label>
                                <div class="col-xs-9">
                                    <span id="web_uploader_wrapper_gallary_other_image"><i class="icon icon-picture icon-2x"></i></span>
                                    <input id="web_uploader_input_gallary_other_image" type="hidden" value=""  event-node="uploadinput">

                                    <div id="web_uploader_picture_list_gallary_other_image" class="web_uploader_picture_list">
                                        <?php if(is_array($attest["image"])): $i = 0; $__LIST__ = $attest["image"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$o): $mod = ($i % 2 );++$i;?><img class="gallary_thumb" onclick="remove_file(this,'other_image')" src="<?php echo (get_cover($o,'path')); ?>">
                                            <input type="hidden" name="image[]" value="<?php echo ($o); ?>"/><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </div>
                                    <span class="help-block"><?php echo ($attest_type["fields"]["other_image_tip"]); ?></span>
                                </div>
                            </div>

                            <div class="">
                                <label>请上传支持您的证据，可以是以下几类</label>
                                <ul style="margin-left: 20px">
                                    <li>付款的收据</li>
                                    <li>银行对账单</li>
                                    <li>个人身份证</li>
                                    <li>或者是YOYOCOINS客服所要求的任何文档</li>
                                </ul>
                            </div>
                            文件描述
                            <div>
                                <textarea style="width: 100%;height: 100px" name="content"></textarea>
                            </div>
                            <a href="javascript:void(0);" data-role="send-appeal" class="btn btn-info" style="margin-top: 10px">发起申诉</a>
                            <a href="#appeal" id="cancel-appeal" href="#appeal" data-toggle="collapse" class="btn btn-default" style="margin-top: 10px;float: right">取消申诉</a>
                        </form>
                    </div>
            </div>
            <?php if($order['status'] ==1 or $order['status'] ==2): ?><div class="alert alert-info" style="margin-top: 0px">
                    <p><?php echo L('_TRADE_TIPS1_');?></p>
                    <p><?php echo L('_TRADE_TIPS2_');?></p>
                </div><?php endif; ?>
            <?php elseif(($order['type'] == 2 or $order['type'] == 4) and $order['get_uid'] == get_uid()): ?>
            <div class="alert alert-info" style="margin-top: 0px">
                <h4 style="text-align: center">交易信息</h4>
                <hr>
                <p><?php echo ($sellInfo); ?></p>
            </div>
            <div class="alert alert-warning" style="margin-top: 0px">
                <h4>交易状态</h4>
                <hr>
                <h4><?php echo ($statusText); ?></h4>
                <hr>
                <?php if($order['status'] == 2): ?><p>放行比特币之前，请确认您已收到相应的交易金额！</p>
                    <div style="margin-top: 10px;text-align: center">
                        <a href="" class="btn btn-info" name="send-coin">放行比特币</a>
                    </div><?php endif; ?>
            </div>
            <?php elseif(($order['type'] == 2 or $order['type'] == 4) and $order['ad_uid'] == get_uid()): ?>
            <div class="alert alert-primary" style="margin-top: 0px">
                <h4>交易信息</h4>
                <hr>
                <p><?php echo ($buyInfo); ?></p>
                <?php if($order['status']): ?><hr>
                    <h4 style="text-align: center">付款信息</h4>
                    <hr>
                    <p>付款详细信息：<label><?php echo ($order["pay_remark"]); ?></label></p>
                    <p>金额：<label><?php echo ($order["trade_price"]); ?> <?php echo ($order["currency"]); ?></label></p>
                    <p>付款参考码：<label><?php echo ($order["pay_code"]); ?></label></p><?php endif; ?>
            </div>
            <div class="alert alert-warning" style="margin-top: 0px;">
                <h4>交易状态</h4>
                <hr>
                <h4><?php echo ($statusText); ?></h4>
                <hr>
                <?php if($order['status'] == 1){ ?>
                <a href="javascript:void(0)" class="btn btn-info" name="pay-ok">付款已完成</a>
                <a href="javascript:void(0)" class="btn btn-danger" id="cancel-trade" style="float: right">取消交易</a>
                <?php }else if($order['status'] == 1 || $order['status'] == 2){ ?>
                <div style="text-align: center">
                    <a href="javascript:void(0)" class="btn btn-default" id="cancel-trade">取消交易</a>
                    <p style="margin-top: 20px"><?php echo L('_TRADE_BUY_INFO3_');?></p>
                    <a href="<?php echo U('/support/request/3/'.$order['order_id']);?>" target="_blank" class="btn btn-danger" id="cancel-trade">申&nbsp;诉</a>
                </div>
                <?php } ?>
            </div>
            <?php if($order['status']): ?><div class="alert alert-info" style="margin-top: 0px">
                    <p>当托管启用时，只有买家和YOYOCOINS工作人员可以取消这笔交易。<a href="">了解托管策略</a></p>
                    <p>如果交易过程中遇到问题，请查找帮助中心文档，或者联系客服<a href="">提交问题</a></p>
                </div><?php endif; endif; ?>
    </div>
</div>

    </div>
    <link rel="stylesheet" href="/yoyo/Application/Coin/Static/css/style.css">
    <script type="text/javascript" charset="utf-8" src="/yoyo/Public/static/ueditor/third-party/webuploader/webuploader.js"></script>
    <script type="text/javascript" src="/yoyo/Public/static/uploadify/jquery.uploadify.min.js"></script>
    <script>
        var gallary_num_image="<?php echo count($info['image']) ?>";
        var gallary_num_other_image = "<?php echo count($info['other_image']) ?>";
        $(function () {

            //image start
            var id_image = "#web_uploader_wrapper_gallary_image";
            if($(id_image).length>0) {
                var uploader_gallary_image = WebUploader.create({
                    // swf文件路径
                    swf: 'Uploader.swf',
                    // 文件接收服务端。
                    server: "<?php echo U('Core/File/uploadPicture',array('session_id'=>session_id()));?>",
                    fileNumLimit: 9,
                    // 选择文件的按钮。可选。
                    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                    pick: {'id': id_image, 'multi': true}
                });
                uploader_gallary_image.on('beforeFileQueued', function (file) {
                    if (gallary_num_image >= 9) {
                        toast.error('图片不能超过9张');
                        return false;
                    }
                });
                uploader_gallary_image.on('fileQueued', function (file) {
                    gallary_num_image = parseInt(gallary_num_image) + 1;

                    uploader_gallary_image.upload();
                    $("#web_uploader_file_name_gallary_image").text('正在上传...');
                });

                /*上传成功*/
                uploader_gallary_image.on('uploadSuccess', function (file, ret) {
                    if (ret.status == 0) {
                        $("#web_uploader_file_name_gallary_image").text(ret.info);
                    } else {
                        talker.post_message(2,ret.data.file.path);
                    }
                });
            }
            //image end



        })

        $("a[name='pay-ok']").click(function () {
            if (confirm("确定要标记付款已完成吗？")){
                $.post("<?php echo U('/order');?>", {orderId:"<?php echo $order['order_id']; ?>",type:1}, success, "json");
                return false;
                function success(data) {
                    if (data.status) {
                        window.location.reload();
                    }
                }
            }else{
                return false;
            }
        });
        $("a[name='send-coin']").click(function () {
            if (confirm("确定要放行比特币吗？")){
                $.post("<?php echo U('/order');?>", {orderId:"<?php echo $order['order_id']; ?>",type:1}, success, "json");
                return false;
                function success(data) {
                    if (data.status) {
                        window.location.reload();
                    }
                }
            }else{
                return false;
            }
        });
        $("#cancel-trade").click(function () {
            if (confirm("确定要取消交易吗？")){
                $.post("<?php echo U('/order');?>", {orderId:"<?php echo $order['order_id']; ?>",type:2}, success, "json");
                return false;
                function success(data) {
                    if (data.status) {
                        window.location.reload();
                    }
                }
            }else{
                return false;
            }
        });

        $("#appeal-trade").click(function () {
            $(this).hide();
            $("#cancel-trade").hide();
            $("#send-coin").hide();
            loadJS();
        });

        $("#cancel-appeal").click(function () {
            $("#appeal-trade").show();
            $("#cancel-trade").show();
            $("#send-coin").show();
        });
        
        function loadJS() {
            // other_image  start
            var id_other_image = "#web_uploader_wrapper_gallary_other_image";
            if($(id_other_image).length > 0) {
                var uploader_gallary_other_image = WebUploader.create({
                    // swf文件路径
                    swf: 'Uploader.swf',
                    // 文件接收服务端。
                    server: "<?php echo U('Core/File/uploadPicture',array('session_id'=>session_id()));?>",
                    fileNumLimit: 9,
                    // 选择文件的按钮。可选。
                    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                    pick: {'id': id_other_image, 'multi': true}
                });
                uploader_gallary_other_image.on('beforeFileQueued', function (file) {
                    if (gallary_num_other_image >= 9) {
                        toast.error('图片不能超过9张');
                        return false;
                    }
                });
                uploader_gallary_other_image.on('fileQueued', function (file) {
                    gallary_num_other_image = parseInt(gallary_num_other_image) + 1;

                    uploader_gallary_other_image.upload();
                    $("#web_uploader_file_name_gallary_other_image").text('正在上传...');
                });

                /*上传成功*/
                uploader_gallary_other_image.on('uploadSuccess', function (file, ret) {
                    if (ret.status == 0) {
                        $("#web_uploader_file_name_gallary_other_image").text(ret.info);
                    } else {
                        $('#web_uploader_input_gallary_other_image').focus();
                        $('#web_uploader_input_gallary_other_image').val(ret.data.file.id);
                        $('#web_uploader_input_gallary_other_image').blur();
                        $("#web_uploader_picture_list_gallary_other_image").append('<img class="gallary_thumb" onclick="remove_file(this,' + "'other_image'" + ')" src="' + ret.data.file.path + '"/><input type="hidden" name="image[]" value="' + ret.data.file.id + '"/>');
                    }
                });
            }
            //other_image end
        }

        function remove_file(obj,str) {
            $(obj).next().remove();
            $(obj).remove();
            switch (str){
                case 'other_image':
                    gallary_num_other_image = gallary_num_other_image - 1;
                    break;
                default:;
            }
        }

        $(function () {
            $('[data-role="submit"]').click(function () {
                var $tag=$(this);
                if($tag.attr('disabled')=='disabled'){
                    return false;
                }
                $tag.attr('disabled','disabled');
                var param=$('#eva-form').serialize();
                var url=U('coin/index/evaLevel');
                $.post(url,param,function (msg) {
                    handleAjax(msg);
                    if(msg.status==0){
                        $tag.removeAttr('disabled');
                    }else if(msg.status == 1){
                        window.location.reload();
                    }
                })
            })
        })

        $(function () {
            $('[data-role="send-appeal"]').click(function () {
                var $tag=$(this);
                if($tag.attr('disabled')=='disabled'){
                    return false;
                }
                $tag.attr('disabled','disabled');
                var param=$('#appeal-form').serialize();
                var url=U('coin/index/appeal');
                $.post(url,param,function (msg) {
                    handleAjax(msg);
                    if(msg.status==0){
                        $tag.removeAttr('disabled');
                    }else if(msg.status == 1){
                        window.location.reload();
                    }
                })
            })
        })

        $("#update-eva").click(function () {
            $(this).parent().css('display','none');
        });
        $(function () {
            var status = <?php echo ($order["status"]); ?>;
            var code;
            if(status == 1){
                var remainTime = <?php echo ($remainTime); ?>;
                if(remainTime > 0){
                    $("#timer").text("<?php echo L('_TRADE_TIME_'); ?>"+remainTime+"<?php echo L('_TIME_MINUTE_'); ?>");
                    code = setInterval(GetRTime,60*1000);
                }
                else{
                    $.post("<?php echo U('/timeOver');?>", {orderId:"<?php echo $order['order_id']; ?>"}, success, "json");
                    return false;
                    function success(data) {
                        if (data.status) {
                            window.location.reload();
                            window.clearInterval(code);
                        }
                    }
                }
            }
            function GetRTime(){
                remainTime-=1;
                $("#timer").text("<?php echo L('_TRADE_TIME_'); ?>"+remainTime+"<?php echo L('_TIME_MINUTE_'); ?>");
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

<!-- 用于加载js代码 -->
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