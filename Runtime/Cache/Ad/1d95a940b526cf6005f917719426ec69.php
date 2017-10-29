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




    <link href="/yoyo/Application/Ad/Static/css/event.css" rel="stylesheet" type="text/css"/>

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
            <a class="navbar-brand" href="<?php echo U('Home/Index/index');?>">YOYOCOINS</a>
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
            
    <div class="white-popup1 boxShadowBorder col-xs-12" style="">
        <h2><?php echo L('_AD_TITLE_');?></h2>
        <?php if(!is_login()): ?><div class="alert alert-danger with-icon" style="margin-top: 10px"><i class="icon-warning-sign"></i><div class="content">在创建广告前，请<a class="alert-link" href="<?php echo U('ucenter/member/login');?>">登录</a>或<a class="alert-link" href="<?php echo U('ucenter/member/register');?>">注册</a>。</div></div><?php endif; ?>
        <p><?php echo L('_AD_TIPS1_');?></p>
        <p><?php echo L('_AD_TIPS2_');?></p>
        <p><?php echo L('_AD_TIPS3_');?></p>
        <p><?php echo L('_AD_TIPS4_');?></p>
        <p><?php echo L('_AD_TIPS5_');?></p>
        <p><?php echo L('_AD_TIPS6_');?></p>

        <h2 style="margin-top: 30px;color: #1798F2">基础选项</h2>
        <div class="aline" style="margin-bottom: 35px"></div>
        <div>
            <div class="row">
                <div style="padding: 0 10px;width: 100%;float: left;">
                    <form class="form-horizontal ajax-form" action="<?php echo U('Ad/Index/doPost');?>" method="post">
                        <input type="hidden" name="id" value="<?php echo ($id); ?>">
                        <div class="form-group">
                            <div class="col-xs-2">
                                <label class="required"><?php echo L('_AD_COIN_TYPE_LABEL_');?></label>
                            </div>
                            <div class="col-xs-10">
                                <label> <input name="coin_type" value="1" type="radio" <?php if($isNew): ?>checked<?php elseif($ad['coin_type'] == 1): ?>checked<?php endif; ?> /> BTC</label>
                                <label class="ad_magrain40"> <input name="coin_type" value="2" type="radio" <?php if($ad['coin_type'] == 2): ?>checked<?php endif; ?>/> ETH</label>
                                <div style="color: #B9C5CF;margin-top: 10px"><i class="icon icon-question-sign"></i>&nbsp;此广告的币种（BTC或ETH）。</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-2">
                                <label class="required"><?php echo L('_AD_TYPE_LABEL_');?></label>
                            </div>
                            <div class="col-xs-10">
                                <label> <input name="type" value="1" type="radio" <?php if($isNew): ?>checked<?php elseif($ad['type'] == 1): ?>checked<?php endif; ?>/> <?php echo L('_AD_ONLINE_SELL_');?></label>
                                <label class="ad_magrain40"> <input name="type" value="2" type="radio" <?php if($ad['type'] == 2): ?>checked<?php endif; ?>/> <?php echo L('_AD_ONLINE_BUY_');?></label>
                                <label class="ad_magrain40"> <input name="type" value="3" type="radio" <?php if($ad['type'] == 3): ?>checked<?php endif; ?>/> <?php echo L('_AD_LOCAL_SELL_');?></label>
                                <label class="ad_magrain40"> <input name="type" value="4" type="radio" <?php if($ad['type'] == 4): ?>checked<?php endif; ?>/> <?php echo L('_AD_LOCAL_BUY_');?></label>
                                <div style="color: #B9C5CF;margin-top: 10px"><i class="icon icon-question-sign"></i>&nbsp;您想要创建什么样的交易广告？</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-2" style="margin-top: 10px">
                                <label class="required"><?php echo L('_AD_COUNTRY_LABEL_');?></label>
                            </div>
                            <div class="col-xs-6">
                                <select name="country" class="select2" style="width: 100%">
                                    <?php if(is_array($country)): $i = 0; $__LIST__ = $country;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["id"]); ?>" <?php if($isNew && $defaultCountry == $top['id']): ?>selected<?php elseif($ad['country'] == $top['id']): ?>selected<?php endif; ?>>
                                            <?php echo ($top["code"]); ?> <?php echo ($top["name"]); ?>
                                        </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                                <div style="color: #B9C5CF;margin-top: 10px"><i class="icon icon-question-sign"></i>&nbsp;请选择您发布广告所在的国家/地区。</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-2" style="margin-top: 10px">
                                <label class="required"><?php echo L('_AD_CURRENCY_LABEL_');?></label>
                            </div>
                            <div class="col-xs-6">
                                <select name="currency" class="select2" style="width: 100%">
                                    <?php if(is_array($currency)): $i = 0; $__LIST__ = $currency;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["code"]); ?>" <?php if($isNew && $defaultCurrency == $top['code']): ?>selected<?php elseif($ad['currency'] == $top['code']): ?>selected<?php endif; ?>>
                                            <?php echo ($top["code"]); ?>
                                        </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-2" style="margin-top: 10px">
                                <label class="required"><?php echo L('_AD_MARKET_LABEL_');?></label>
                            </div>
                            <div class="col-xs-6">
                                <select name="market" class="select2" style="width: 100%">
                                    <?php if(is_array($market)): $i = 0; $__LIST__ = $market;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["market"]); ?>" <?php if($ad['market'] == $top['market']): ?>selected<?php endif; ?>>
                                        <?php echo ($top["market"]); ?>
                                        </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-2" style="margin-top: 10px">
                                <label class="required"><?php echo L('_AD_PRE_PRICE_LABEL_');?></label>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group input-group-lg">
                                    <input type="text" class="form-control form_check" value="<?php echo ((isset($ad['pre_price']) && ($ad['pre_price'] !== ""))?($ad['pre_price']):'2'); ?>" name="pre_price" check-type="IntNum">
                                    <span class="input-group-addon">%</span>
                                </div>
                                <div style="color: #B9C5CF;margin-top: 10px"><i class="icon icon-question-sign"></i>&nbsp;输入溢价比例，会根据部分大型交易所实时价格计算出价格，比如当前价格为20000，溢价比例为10%，那么价格为22000。</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-2" style="margin-top: 10px">
                                <label class="required"><?php echo L('_AD_FORMULA_LABEL_');?></label>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group input-group-lg" style="width: 100%">
                                    <input type="text" class="form-control" value="<?php echo ($ad['formula']); ?>" name="formula">
                                    <input type="hidden" name="price" value="">
                                </div>
                                <div style="color: #B9C5CF;margin-top: 10px"><i class="icon icon-question-sign"></i>&nbsp;交易价格：<span class="marketPrice" style="color: red"></span>&nbsp;根据溢价比例得出的报价，10分钟更新一次。</div>
                            </div>
                        </div>

                        <div class="form-group pay_addr" <?php if($ad['type'] ==1 or $ad['type'] ==2 or $ad['type'] ==null): ?>style="display: none"<?php endif; ?>>
                            <div class="col-xs-2" style="margin-top: 10px">
                                <label class="required"><?php echo L('_AD_PAY_ADDRESS_LABEL_');?></label>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group input-group-lg" style="width: 100%">
                                    <input type="text" class="form-control" value="<?php echo ($ad['pay_addr']); ?>" name="pay_addr">
                                </div>
                                <div style="color: #B9C5CF;margin-top: 10px"><i class="icon icon-question-sign"></i>&nbsp;广告交易的见面地点，例如咖啡厅。</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-2" style="margin-top: 10px">
                                <label class="required"><?php echo L('_AD_MIN_PRICE_LABEL_');?></label>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group input-group-lg">
                                    <input type="text" class="form-control form_check" value="<?php echo ($ad['min_price']); ?>" name="min_price" check-type="IntNum">
                                    <span class="input-group-addon label-currency"><?php echo ($defaultCurrency); ?></span>
                                </div>
                                <div style="color: #B9C5CF;margin-top: 10px"><i class="icon icon-question-sign"></i>&nbsp;每笔交易的最低限额。</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-2" style="margin-top: 10px">
                                <label class="required"><?php echo L('_AD_MAX_PRICE_LABEL_');?></label>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group input-group-lg">
                                    <input type="text" class="form-control form_check" value="<?php echo ($ad['max_price']); ?>" name="max_price" check-type="IntNum">
                                    <span class="input-group-addon label-currency"><?php echo ($defaultCurrency); ?></span>
                                </div>
                                <div style="color: #B9C5CF;margin-top: 10px"><i class="icon icon-question-sign"></i>&nbsp;每笔交易的最大限额，必须大于最小限额，您的钱包余额会影响最大额度的设置。</div>
                            </div>
                        </div>

                        <div class="form-group pay_time">
                            <div class="col-xs-2" style="margin-top: 10px">
                                <label class="required"><?php echo L('_AD_PAY_TIME_LABEL_');?></label>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group input-group-lg">
                                    <input type="text" class="form-control form_check" value="<?php if($ad['pay_time'] > 0): echo ($ad['pay_time']); else: ?>180<?php endif; ?>" name="pay_time" check-type="IntNum">
                                    <span class="input-group-addon">分钟</span>
                                </div>
                                <div style="color: #B9C5CF;margin-top: 10px"><i class="icon icon-question-sign"></i>&nbsp;设置广告付款时间期限。</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-2" style="margin-top: 10px">
                                <label class="required"><?php echo L('_AD_PAY_TYPE_LABEL_');?></label>
                            </div>
                            <div class="col-xs-6">
                                <?php $typeArray = explode(',',$ad['pay_type']); ?>
                                <select name="pay_type[]" class="chosen-select select2" multiple="multiple" style="width: 100%">
                                    <?php if(is_array($payType)): $i = 0; $__LIST__ = $payType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["id"]); ?>" <?php if(in_array($top['id'],$typeArray)): ?>selected<?php endif; ?>>
                                            <?php echo ($top["name"]); ?>
                                        </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>
                        </div>

                <div class="form-group">
                    <div class="col-xs-2">
                        <label><?php echo L('_AD_PAY_REMARK_LABEL_');?></label>
                    </div>
                    <div class="col-xs-6">
                        <?php $pay_remark = str_replace("<br>","\n",$ad['pay_remark']) ?>
                        <textarea name="pay_remark" class="text form-control" placeholder="<?php echo L('_AD_PAY_REMARK_TIPS_LABEL_');?>" style="height: 8em;height: 100px"><?php echo ($pay_remark); ?></textarea>
                    </div>
                </div>

                        <div class="form-group">
                            <div class="col-xs-2">
                                <label><?php echo L('_AD_PAY_TEXT_LABEL_');?></label>
                            </div>
                            <div class="col-xs-6">
                                <?php $pay_text = str_replace("<br>","\n",$ad['pay_text']) ?>
                                <textarea name="pay_text" class="text form-control" placeholder="<?php echo L('_AD_PAY_TEXT_TIPS_LABEL_');?>" style="height: 8em;height: 160px"><?php echo ($pay_text); ?></textarea>
                            </div>
                        </div>

                        <h2 style="margin-top: 30px"><a href="#collapseExample" data-toggle="collapse" style="color: #1798F2">显示高级选项...</a></h2>
                        <div class="aline" style="margin-bottom: 35px"></div>
                        <div class="collapse" id="collapseExample">
                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label><?php echo L('_AD_MESSAGE_LABEL_');?></label>
                                </div>
                                <div class="col-xs-6">
                                    <textarea name="auto_mesage" class="text input-large form-control" style="height: 8em;height: 100px"><?php echo ($ad['auto_mesage']); ?></textarea>
                                    <div style="color: #B9C5CF;margin-top: 10px"><i class="icon icon-question-sign"></i>&nbsp;用户通过此广告向您发起交易时，系统自动将您选择的自动回复用语发送给对方。</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label><?php echo L('_AD_SAFE_LABEL_');?></label>
                                </div>
                                <div class="col-xs-10">
                                    <label> <input name="is_safe" value="1" type="radio" <?php if($ad['is_safe'] == 1): ?>checked<?php endif; ?>/> <?php echo L('_AD_OPEN_');?></label>&nbsp;&nbsp;&nbsp;
                                    <label class=""> <input name="is_safe" value="0" type="radio" <?php if($ad['is_safe'] == 0): ?>checked<?php endif; ?>/> <?php echo L('_AD_CLOSE_');?></label>
                                    <div style="color: #B9C5CF;margin-top: 10px"><i class="icon icon-question-sign"></i>&nbsp;启用后，仅限于已验证身份证和通过短信验证的用户与本广告交易。</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label><?php echo L('_AD_TRUST_LABEL_');?></label>
                                </div>
                                <div class="col-xs-10">
                                    <label> <input name="is_trust" value="1" type="radio" <?php if($ad['is_trust'] == 1): ?>checked<?php endif; ?>/> <?php echo L('_AD_OPEN_');?></label>&nbsp;&nbsp;&nbsp;
                                    <label class=""> <input name="is_trust" value="0" type="radio" <?php if($ad['is_trust'] == 0): ?>checked<?php endif; ?>/> <?php echo L('_AD_CLOSE_');?></label>
                                    <div style="color: #B9C5CF;margin-top: 10px"><i class="icon icon-question-sign"></i>&nbsp;启用后，仅限于自己信任的用户与本广告交易。</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label><?php echo L('_AD_OPEN_TIME_LABEL_');?></label>
                                </div>
                                <div class="col-xs-10">
                                    <div style="color: #B9C5CF;margin-top: 10px"><i class="icon icon-question-sign"></i>&nbsp;您希望广告自动展示和隐藏的天数和小时数。</div>
                                </div>
                            </div>

                            <div class="opentime">
                                <div class="form-group">
                                    <!--星期日-->
                                    <div class="col-xs-2">
                                        <label><?php echo L('_AD_SUNDAY_LABEL_');?></label>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="input-group input-group-lg">
                                            <select name="start0" class="form-control ">
                                                <option value="-2" <?php if($ad[start0] == -2): ?>selected<?php endif; ?>>
                                                <?php echo L('_START_TIME_');?>
                                                </option>
                                                <option value="-1" <?php if($ad[start0] == -1): ?>selected<?php endif; ?>>
                                                <?php echo L('_CLOSE_TIME_');?>
                                                </option>
                                                <?php if(is_array($time)): $i = 0; $__LIST__ = $time;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["id"]); ?>" <?php if(($ad[start0] == $top['id']) and !$isNew): ?>selected<?php endif; ?>>
                                                    <?php echo ($top["time"]); ?>
                                                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="input-group input-group-lg">
                                            <select name="end0" class="form-control ">
                                                <option value="25" <?php if($ad[end0] == 25): ?>selected<?php endif; ?>>
                                                <?php echo L('_END_TIME_');?>
                                                </option>
                                                <option value="-1" <?php if($ad[end0] == -1): ?>selected<?php endif; ?>>
                                                <?php echo L('_CLOSE_TIME_');?>
                                                </option>
                                                <?php if(is_array($time)): $i = 0; $__LIST__ = $time;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["id"]); ?>" <?php if($ad[end0] == $top['id'] and !$isNew): ?>selected<?php endif; ?>>
                                                    <?php echo ($top["time"]); ?>
                                                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <!--星期一-->
                                    <div class="col-xs-2">
                                        <label><?php echo L('_AD_MONDAY_LABEL_');?></label>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="input-group input-group-lg">
                                            <select name="start1" class="form-control ">
                                                <option value="-2" <?php if($ad[start1] == -2): ?>selected<?php endif; ?>>
                                                <?php echo L('_START_TIME_');?>
                                                </option>
                                                <option value="-1" <?php if($ad[start1] == -1): ?>selected<?php endif; ?>>
                                                <?php echo L('_CLOSE_TIME_');?>
                                                </option>
                                                <?php if(is_array($time)): $i = 0; $__LIST__ = $time;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["id"]); ?>" <?php if($ad[start1] == $top['id'] and !$isNew): ?>selected<?php endif; ?>>
                                                    <?php echo ($top["time"]); ?>
                                                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="input-group input-group-lg">
                                            <select name="end1" class="form-control ">
                                                <option value="25" <?php if($ad[end1] == 25): ?>selected<?php endif; ?>>
                                                <?php echo L('_END_TIME_');?>
                                                </option>
                                                <option value="-1" <?php if($ad[end1] == -1): ?>selected<?php endif; ?>>
                                                <?php echo L('_CLOSE_TIME_');?>
                                                </option>
                                                <?php if(is_array($time)): $i = 0; $__LIST__ = $time;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["id"]); ?>" <?php if($ad[end1] == $top['id'] and !$isNew): ?>selected<?php endif; ?>>
                                                    <?php echo ($top["time"]); ?>
                                                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <!--星期二-->
                                    <div class="col-xs-2">
                                        <label><?php echo L('_AD_TUESDAY_LABEL_');?></label>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="input-group input-group-lg">
                                            <select name="start2" class="form-control ">
                                                <option value="-2" <?php if($ad[start2] == -2): ?>selected<?php endif; ?>>
                                                <?php echo L('_START_TIME_');?>
                                                </option>
                                                <option value="-1" <?php if($ad[start2] == -1): ?>selected<?php endif; ?>>
                                                <?php echo L('_CLOSE_TIME_');?>
                                                </option>
                                                <?php if(is_array($time)): $i = 0; $__LIST__ = $time;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["id"]); ?>" <?php if($ad[start2] == $top['id'] and !$isNew): ?>selected<?php endif; ?>>
                                                    <?php echo ($top["time"]); ?>
                                                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="input-group input-group-lg">
                                            <select name="end2" class="form-control ">
                                                <option value="25" <?php if($ad[end2] == 25): ?>selected<?php endif; ?>>
                                                <?php echo L('_END_TIME_');?>
                                                </option>
                                                <option value="-1" <?php if($ad[end2] == -1): ?>selected<?php endif; ?>>
                                                <?php echo L('_CLOSE_TIME_');?>
                                                </option>
                                                <?php if(is_array($time)): $i = 0; $__LIST__ = $time;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["id"]); ?>" <?php if($ad[end2] == $top['id'] and !$isNew): ?>selected<?php endif; ?>>
                                                    <?php echo ($top["time"]); ?>
                                                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <!--星期三-->
                                    <div class="col-xs-2">
                                        <label><?php echo L('_AD_WEDNESDAY_LABEL_');?></label>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="input-group input-group-lg">
                                            <select name="start3" class="form-control ">
                                                <option value="-2" <?php if($ad[start3] == -2): ?>selected<?php endif; ?>>
                                                <?php echo L('_START_TIME_');?>
                                                </option>
                                                <option value="-1" <?php if($ad[start3] == -1): ?>selected<?php endif; ?>>
                                                <?php echo L('_CLOSE_TIME_');?>
                                                </option>
                                                <?php if(is_array($time)): $i = 0; $__LIST__ = $time;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["id"]); ?>" <?php if($ad[start3] == $top['id'] and !$isNew): ?>selected<?php endif; ?>>
                                                    <?php echo ($top["time"]); ?>
                                                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="input-group input-group-lg">
                                            <select name="end3" class="form-control ">
                                                <option value="25" <?php if($ad[end3] == 25): ?>selected<?php endif; ?>>
                                                <?php echo L('_END_TIME_');?>
                                                </option>
                                                <option value="-1" <?php if($ad[end3] == -1): ?>selected<?php endif; ?>>
                                                <?php echo L('_CLOSE_TIME_');?>
                                                </option>
                                                <?php if(is_array($time)): $i = 0; $__LIST__ = $time;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["id"]); ?>" <?php if($ad[end3] == $top['id'] and !$isNew): ?>selected<?php endif; ?>>
                                                    <?php echo ($top["time"]); ?>
                                                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <!--星期四-->
                                    <div class="col-xs-2">
                                        <label><?php echo L('_AD_THURSDAY_LABEL_');?></label>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="input-group input-group-lg">
                                            <select name="start4" class="form-control ">
                                                <option value="-2" <?php if($ad[start4] == -2): ?>selected<?php endif; ?>>
                                                <?php echo L('_START_TIME_');?>
                                                </option>
                                                <option value="-1" <?php if($ad[start4] == -1): ?>selected<?php endif; ?>>
                                                <?php echo L('_CLOSE_TIME_');?>
                                                </option>
                                                <?php if(is_array($time)): $i = 0; $__LIST__ = $time;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["id"]); ?>" <?php if($ad[start4] == $top['id'] and !$isNew): ?>selected<?php endif; ?>>
                                                    <?php echo ($top["time"]); ?>
                                                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="input-group input-group-lg">
                                            <select name="end4" class="form-control ">
                                                <option value="25" <?php if($ad[end4] == 25): ?>selected<?php endif; ?>>
                                                <?php echo L('_END_TIME_');?>
                                                </option>
                                                <option value="-1" <?php if($ad[end4] == -1): ?>selected<?php endif; ?>>
                                                <?php echo L('_CLOSE_TIME_');?>
                                                </option>
                                                <?php if(is_array($time)): $i = 0; $__LIST__ = $time;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["id"]); ?>" <?php if($ad[end4] == $top['id'] and !$isNew): ?>selected<?php endif; ?>>
                                                    <?php echo ($top["time"]); ?>
                                                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <!--星期五-->
                                    <div class="col-xs-2">
                                        <label><?php echo L('_AD_FRIDAY_LABEL_');?></label>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="input-group input-group-lg">
                                            <select name="start5" class="form-control ">
                                                <option value="-2" <?php if($ad[start5] == -2): ?>selected<?php endif; ?>>
                                                <?php echo L('_START_TIME_');?>
                                                </option>
                                                <option value="-1" <?php if($ad[start5] == -1): ?>selected<?php endif; ?>>
                                                <?php echo L('_CLOSE_TIME_');?>
                                                </option>
                                                <?php if(is_array($time)): $i = 0; $__LIST__ = $time;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["id"]); ?>" <?php if($ad[start5] == $top['id'] and !$isNew): ?>selected<?php endif; ?>>
                                                    <?php echo ($top["time"]); ?>
                                                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="input-group input-group-lg">
                                            <select name="end5" class="form-control ">
                                                <option value="25" <?php if($ad[end5] == 25): ?>selected<?php endif; ?>>
                                                <?php echo L('_END_TIME_');?>
                                                </option>
                                                <option value="-1" <?php if($ad[end5] == -1): ?>selected<?php endif; ?>>
                                                <?php echo L('_CLOSE_TIME_');?>
                                                </option>
                                                <?php if(is_array($time)): $i = 0; $__LIST__ = $time;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["id"]); ?>" <?php if($ad[end5] == $top['id'] and !$isNew): ?>selected<?php endif; ?>>
                                                    <?php echo ($top["time"]); ?>
                                                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <!--星期六-->
                                    <div class="col-xs-2">
                                        <label><?php echo L('_AD_SATURDAY_LABEL_');?></label>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="input-group input-group-lg">
                                            <select name="start6" class="form-control ">
                                                <option value="-2" <?php if($ad[start6] == -2): ?>selected<?php endif; ?>>
                                                <?php echo L('_START_TIME_');?>
                                                </option>
                                                <option value="-1" <?php if($ad[start6] == -1): ?>selected<?php endif; ?>>
                                                <?php echo L('_CLOSE_TIME_');?>
                                                </option>
                                                <?php if(is_array($time)): $i = 0; $__LIST__ = $time;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["id"]); ?>" <?php if($ad[start6] == $top['id'] and !$isNew): ?>selected<?php endif; ?>>
                                                    <?php echo ($top["time"]); ?>
                                                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="input-group input-group-lg">
                                            <select name="end6" class="form-control ">
                                                <option value="25" <?php if($ad[end6] == 25): ?>selected<?php endif; ?>>
                                                <?php echo L('_END_TIME_');?>
                                                </option>
                                                <option value="-1" <?php if($ad[end6] == -1): ?>selected<?php endif; ?>>
                                                <?php echo L('_CLOSE_TIME_');?>
                                                </option>
                                                <?php if(is_array($time)): $i = 0; $__LIST__ = $time;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["id"]); ?>" <?php if($ad[end6] == $top['id'] and !$isNew): ?>selected<?php endif; ?>>
                                                    <?php echo ($top["time"]); ?>
                                                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="aline" style="margin-bottom: 35px"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-2 col-md-offset-2">
                                <button type="submit" class="btn btn-primary btn-lg" href="<?php echo U('Ad/Index/doPost');?>" style="outline: none"><?php echo L('_AD_SUBMIT_LABEL_');?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <link href="/yoyo/Application/Ad/Static/css/form_check.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/yoyo/Application/Ad/Static/js/form_check.js"></script>
    <link rel="stylesheet" href="/yoyo/Application/Ad/Static/css/select2.css">
    <script src="/yoyo/Application/Ad/Static/js/select2.js"></script>
    <link rel="stylesheet" href="/yoyo/Application/Ad/Static/css/components.css">
    <script>
        $("input[name='type']").click(function () {
            var type = $(this).val();
            if(type == 1){
                $(".pay_addr").css('display','none');
            }else if(type == 2){
                $(".pay_addr").css('display','none');
            }else if(type == 3){
                $(".pay_addr").css('display','block');
                $(".pay_time").css('display','none');
            }else if(type == 4){
                $(".pay_addr").css('display','block');
                $(".pay_time").css('display','none');
            }
        });
        $("input[name='pre_price']").blur(function () {
            var prePrice = $(this).val();
            var currency = $("select[name='currency']").val();
            if(prePrice == null || prePrice.length == 0){
                return;
            }
            var formula = $("input[name='formula']").val();
            var i = formula.lastIndexOf('*');
            formula = formula.substr(0,i);
            prePrice /= 100;
            prePrice += 1;
            prePrice = parseFloat(prePrice.toFixed(2));
            formula += "*"+prePrice;
            getMarketPrice(currency,formula);
            $("input[name='formula']").val(formula);
        });
        $(function(){
            $(".select2").select2();
        })

        $("select[name='currency']").change(function () {
            var currency = $(this).val();
            $(".label-currency").text(currency);
            var market = $("select[name='market']").val();
            var prePrice = $("input[name='pre_price']").val();
            $.post('<?php echo U("ad/index/getFormula");?>', {market:market,currency:currency}, success);
            return false;
            function success(data) {
                prePrice /= 100;
                prePrice += 1;
                prePrice = parseFloat(prePrice.toFixed(2));
                var formula = data+"*"+prePrice;
                getMarketPrice(currency,formula);
                $("input[name='formula']").val(formula);
            }
        });

        $("select[name='market']").change(function () {
            var market = $(this).val();
            var currency = $("select[name='currency']").val();
            var prePrice = Number($("input[name='pre_price']").val());
            $.post('<?php echo U("ad/index/getFormula");?>', {market:market,currency:currency}, success);
            return false;
            function success(data) {
                prePrice /= 100;
                prePrice += 1;
                prePrice = parseFloat(prePrice.toFixed(2));
                var formula = data+"*"+prePrice;
                getMarketPrice(currency,formula);
                $("input[name='formula']").val(formula);
            }
        });

        $(".btn-lg").click(function () {
            var start0 = Number($("select[name='start0']").val());
            var end0 = Number($("select[name='end0']").val());
            if(start0 >= 0 && end0 == 25){
                timeError();
                return false;
            }else if(start0 == -2 && (end0 <25 && end0>=0)){
                timeError();
                return false;
            }else if(start0 >= end0 && start0 >=0){
                timeError();
                return false;
            }
            var start1 = Number($("select[name='start1']").val());
            var end1 = Number($("select[name='end1']").val());
            if(start1 >= 0 && end1 == 25){
                timeError();
                return false;
            }else if(start1 == -2 && (end1 <25 && end1>=0)){
                timeError();
                return false;
            }else if(start1 >= end1 && start1 >=0){
                timeError();
                return false;
            }
            var start2 = Number($("select[name='start2']").val());
            var end2 = Number($("select[name='end2']").val());
            if(start2 >= 0 && end2 == 25){
                timeError();
                return false;
            }else if(start2 == -2 && (end2 <25 && end2>=0)){
                timeError();
                return false;
            }else if(start2 >= end2 && start2 >=0){
                timeError();
                return false;
            }
            var start3 = Number($("select[name='start3']").val());
            var end3 = Number($("select[name='end3']").val());
            if(start3 >= 0 && end3 == 25){
                timeError();
                return false;
            }else if(start3 == -2 && (end3 <25 && end3>=0)){
                timeError();
                return false;
            }else if(start3 >= end3 && start3 >=0){
                timeError();
                return false;
            }
            var start4 = Number($("select[name='start4']").val());
            var end4 = Number($("select[name='end4']").val());
            if(start4 >= 0 && end4 == 25){
                timeError();
                return false;
            }else if(start4 == -2 && (end4 <25 && end4>=0)){
                timeError();
                return false;
            }else if(start4 >= end4 && start4 >=0){
                timeError();
                return false;
            }
            var start5 = Number($("select[name='start5']").val());
            var end5 = Number($("select[name='end5']").val());
            if(start5 >= 0 && end5 == 25){
                timeError();
                return false;
            }else if(start5 == -2 && (end5 <25 && end5>=0)){
                timeError();
                return false;
            }else if(start5 >= end5 && start5 >=0){
                timeError();
                return false;
            }
            var start6 = Number($("select[name='start6']").val());
            var end6 = Number($("select[name='end6']").val());
            if(start6 >= 0 && end6 == 25){
                timeError();
                return false;
            }else if(start6 == -2 && (end6 <25 && end6>=0)){
                timeError();
                return false;
            }else if(start6 >= end6 && start6 >=0){
                timeError();
                return false;
            }
        });

        function timeError() {
            toast.error("请选择正确的广告开放时间");
        }

        $(".opentime select").change(function () {
           var time = Number($(this).val());
           if(time == -1){
               var name = $(this).attr('name');
               if(name == 'start0'){
                    $("select[name='end0']").val('-1');
               }else if(name == 'end0'){
                   $("select[name='start0']").val('-1');
               }else if(name == 'start1'){
                   $("select[name='end1']").val('-1');
               }else if(name == 'end1'){
                   $("select[name='start1']").val('-1');
               }else if(name == 'start2'){
                   $("select[name='end2']").val('-1');
               }else if(name == 'end2'){
                   $("select[name='start2']").val('-1');
               }else if(name == 'start3'){
                   $("select[name='end3']").val('-1');
               }else if(name == 'end3'){
                   $("select[name='start3']").val('-1');
               }else if(name == 'start4'){
                   $("select[name='end4']").val('-1');
               }else if(name == 'end4'){
                   $("select[name='start4']").val('-1');
               }else if(name == 'start5'){
                   $("select[name='end5']").val('-1');
               }else if(name == 'end5'){
                   $("select[name='start5']").val('-1');
               }else if(name == 'start6'){
                   $("select[name='end6']").val('-1');
               }else if(name == 'end6'){
                   $("select[name='start6']").val('-1');
               }
           }
        });

        initFormula();
        function initFormula() {
            var market = $("select[name='market']").val();
            var currency = $("select[name='currency']").val();
            var prePrice = Number($("input[name='pre_price']").val());
            $.post('<?php echo U("ad/index/getFormula");?>', {market:market,currency:currency}, success);
            return false;
            function success(data) {
                prePrice /= 100;
                prePrice += 1;
                prePrice = parseFloat(prePrice.toFixed(2));
                var formula = data+"*"+prePrice;
                getMarketPrice(currency,formula);
                $("input[name='formula']").val(formula);
            }
        }
        
        function getMarketPrice(currency,formula) {
            $.post('<?php echo U("ad/index/getMarketPrice");?>', {formula:formula}, success);
            return false;
            function success(data) {
                var coinType = Number($("input[name='coin_type']:checked").val());
                $("input[name='price']").val(data);
                $(".marketPrice").text(data+" "+currency+"/"+(coinType==1?'BTC':'ETH'));
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
            <p class="ftTit">关于我们</p>
            <div class="ftMain">
                <?php echo modC('ABOUT_US',L('_NOT_SET_NOW_'),'Config');?>
            </div>
        </div>
        <div class="ftBox">
            <p class="ftTit">友情链接</p>
            <div class="ftLink">
                <?php echo W('Common/SuperLinks/superLinks');?>
            </div>
        </div>
        <div class="ftBox codeBox">
            <?php $logo = get_cover(modC('QRCODE_BOTTOM',0,'Config'),'path'); $logo = $logo?$logo:'/yoyo/Public/images/code.png'; ?>
            <div class="code">
                <div class="ftCircle" style="background-color: #6cc775"><i class="iconfont icon-weixin1"></i></div>
                <p>官方微信</p>
                <div class="imgWrap"><img src="<?php echo ($logo); ?>" alt=""></div>
            </div>
        </div>
    </div>
    <div class="container ftOther">
        <?php echo modC('COMPANY', L('_NOT_SET_NOW_'), 'Config');?>
    </div>
    <div class="container ftBottom text-center">
        <p>
            <span><?php echo modC('COPY_RIGHT',L('_NOT_SET_NOW_'),'Config');?></span>
            <span><?php echo modC('ICP','浙ICP备12042711号-5', 'Config');?><br><a href="http://www.opensns.cn" target="_blank">Powered by OpenSNS</a></span>
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