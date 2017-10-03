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
<link type="text/css" rel="stylesheet" href="//at.alicdn.com/t/font_m3e1llc4usypsyvi.css"/>
<!--<script src="/yoyo/Public/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="/yoyo/Public/js/com/com.functions.js"></script>

<script type="text/javascript" src="/yoyo/Public/js/core.js"></script>-->
<script src="/yoyo/Public/js.php?f=js/jquery-2.0.3.min.js,js/com/com.functions.js,static/os-loading/loading.js,js/core.js,js/com/com.toast.class.js,js/com/com.ucard.js"></script>




    <link href="/yoyo/Application/Ucenter/Static/css/center.css" type="text/css" rel="stylesheet">
    <script src="/yoyo/Application/Ucenter/Static/js/jquery.js"></script>


<!--合并前的js-->
<?php $config = api('Config/lists'); C($config); $count_code=C('COUNT_CODE'); ?>
<script type="text/javascript">
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
<link rel="stylesheet" href="http://at.alicdn.com/t/font_iwj71cmtw1dobt9.css">
<script>

</script>
<div class="container-fluid topp-box clearfloat">
    <div class="col-xs-2 box">
        <div class="img-wrap">
            <?php $logo = get_cover(modC('LOGO',0,'Config'),'path'); $logo = $logo?$logo:'/yoyo/Public/images/logo.png'; ?>
            <a class="navbar-brand logo" href="<?php echo U('Home/Index/index');?>"><img src="<?php echo ($logo); ?>"/></a>
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
                <?php $uid = is_login(); $reg_time = D('member')->where(array('uid' => $uid))->getField('reg_time'); $reg_date = date('Y-m-d', $reg_time); $self = query_user(array('title', 'avatar128', 'nickname', 'uid', 'space_url', 'score', 'title', 'fans', 'following', 'weibocount', 'rank_link')); $map = getUserConfigMap('user_cover'); $map['role_id'] = 0; $model = D('Ucenter/UserConfig'); $cover = $model->findData($map); $self['cover_id'] = $cover['value']; $self['cover_path'] = getThumbImageById($cover['value'], 273, 80); ?>
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
                            <a href="<?php echo U('Ucenter/index/applist',array('uid'=>$self['uid'],'type'=>'Weibo'));?>">
                                <div class="col-xs-4 num">
                                    <span>微博</span>
                                    <span><?php echo ($self["weibocount"]); ?></span>
                                </div>
                            </a>
                            <a href="<?php echo U('Ucenter/index/fans',array('uid'=>$self['uid']));?>" title="<?php echo L('_FANS_COUNT_');?>">
                                <div class="col-xs-4 num">
                                    <span><?php echo L('_FANS_');?></span>
                                    <span><?php echo ($self["fans"]); ?></span>
                                </div>
                            </a>
                            <a href="<?php echo U('Ucenter/index/following',array('uid'=>$self['uid']));?>" title="<?php echo L('_FOLLOW_COUNT_');?>">
                                <div class="col-xs-4 num" style="border: none">
                                    <span><?php echo L('_FOLLOW_');?></span>
                                    <span><?php echo ($self["following"]); ?></span>
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
                            <a href="<?php echo ($self["space_url"]); ?>">
                                <div class="col-xs-6 l-p0">
                                    <i class="os-icon-user"></i>
                                    个人主页
                                </div>
                            </a>
                            <a href="<?php echo U('Ucenter/index/ranking');?>">
                                <div class="col-xs-6 r-p0">
                                    <i class="os-icon-bar-chart"></i>
                                    排行榜
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
    
    <br/>

    <!--顶部导航之后的钩子，调用公告等-->
<!--<?php echo hook('afterTop');?>-->
<!--顶部导航之后的钩子，调用公告等 end-->
    <div id="main-container" class="container">
        <div class="row">
            
    <div class="login-box">
        <?php if($step == 'start'): ?><div class="col-xs-9 info-box">
                <form action="<?php echo U('register');?>" method="post">
                    <ul id="reg_nav" class="nav nav-tabs" style="margin-bottom: 20px;">
                        <?php if(check_reg_type('email')){ ?>
                        <li <?php if($regSwitch[0] == 'email'): ?>class="active"<?php endif; ?>><a href="#email_reg" data-toggle="tab"><?php echo L('_REGISTER_EMAIL_');?></a></li>
                        <?php } ?>
                        <?php if(check_reg_type('mobile')){ ?>
                        <li <?php if($regSwitch[0] == 'mobile'): ?>class="active"<?php endif; ?>><a href="#mobile_reg" data-toggle="tab"><?php echo L('_REGISTER_PHONE_');?></a></li>
                        <?php } ?>
                    </ul>

                    <div class="tab-content">
                        <?php if(isset($invite_user)){ ?>
                        <div class="alert alert-info"><?php echo L('_USER_');?> <?php echo ($invite_user['nickname']); ?> <?php echo L('_REGISTER_INVITE_'); echo C('WEB_SITE');?>，<?php echo L('_REGISTER_INFORMATION_FILL_OUT_');?>~</div>
                        <input type="hidden" name="code" value="<?php echo ($code); ?>">
                        <?php }else{ ?>
                        <?php if($open_invite_register): ?><div class="alert alert-info" style="margin-top: 0"><?php echo L('_USER_INVITE_FIRST_');?><strong><a data-type="ajax" data-url="<?php echo U('Ucenter/Member/inCode');?>" data-title="<?php echo L('_INVITE_CODE_INPUT_');?>" data-toggle="modal"><?php echo L('_INVITE_CODE_INPUT_');?></a></strong>，<?php echo L('_REGISTER_INFORMATION_FILL_OUT_');?>~</div><?php endif; ?>
                        <?php } ?>
                        <?php if(count($role_list)==1): ?><input id="name" type="hidden" name="role" value="<?php echo ($role_list[0]['id']); ?>">
                            <?php else: ?>
                            <div class="form-group" style="margin: 30px 0">
                                <input id="name" type="hidden" name="role" value="<?php echo ($role_list[0]['id']); ?>">
                                <label for="role" class=".sr-only col-xs-12" style="display: none"></label>
                                <div class="clearfix"></div>
                                <ul id="role-list" class="nav nav-justified nav-pills">
                                    <li class="text-center"><?php echo L('_REGISTER_IDENTITY_SELECT_');?></li>
                                    <?php if(is_array($role_list)): $i = 0; $__LIST__ = $role_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$role): $mod = ($i % 2 );++$i;?><li><a onclick="$('#name').val(<?php echo ($role["id"]); ?>);$('#role-list li').removeClass('active');$(this).parent().addClass('active');"><i class="icon-user"></i> <?php echo ($role["title"]); ?> </a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                                </ul>
                                <script>
                                    $(function(){
                                        $('#role-list li').eq(1).addClass('active');
                                    })
                                </script>
                                <span class="help-block"></span>
                            </div><?php endif; ?>
                        <?php if(is_array($regSwitch)): $i = 0; $__LIST__ = $regSwitch;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$regSwitch): $mod = ($i % 2 );++$i; switch($regSwitch): case "email": ?><!--邮箱注册-->
                                    <div class="tab-pane <?php if($key == 0): ?>active in<?php endif; ?>" id="email_reg">

                                        <div class="form-group new-form">
                                            <label for="email" class=".sr-only col-xs-12" style="display: none"></label>
                                            <span class="new-icon email-icon"></span>
                                            <input type="text" id="email" class="form-control form_check new-input" check-type="UserEmail" check-url="<?php echo U('ucenter/member/checkAccount');?>" <?php if($key != 0): ?>disabled="disabled"<?php endif; ?>
                                            placeholder="<?php echo L('_PLACEHOLDER_EMAIL_INPUT_');?>" value="" name="username">
                                            <input type="hidden" name="reg_type" value="email" <?php if($key != 0): ?>disabled="disabled"<?php endif; ?>>
                                        </div>
                                        <span class="tips"><?php echo L('_EMAIL_INPUT_');?></span>


                                        <?php if(modC('EMAIL_VERIFY_TYPE', 0, 'USERCONFIG') == 2){ ?>

                                        <div class="form-group new-form">
                                            <span class="new-icon code-icon"></span>
                                            <input type="text" class="form-control input-new" placeholder="输入邮箱验证码" <?php if($key != 0): ?>disabled="disabled"<?php endif; ?> name="reg_verify">
                                            <a class="get-code green-btn" data-role="getVerify"><?php echo L('_EMAIL_VERIFY_');?></a>
                                            <!--<span class="help-block"><?php echo L('_VERIFY_CODE_INPUT_');?></span>-->
                                        </div>

                                        <div class="form-group new-form verify-check" style=" display:none;">
                                            <h3>确认发送验证码</h3>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <div class="lg_lf_fm_verify">
                                                        <img class="verifyimg reloadverify img-responsive" alt="点击切换" src="<?php echo U('verify',array('id'=>3));?>">
                                                    </div>
                                                    <div class="col-xs-12 Validform_checktip text-warning lg_lf_fm_tip"></div>
                                                </div>
                                                <div class="col-xs-6 input-box">
                                                    <label for="verifyCode3" class=".sr-only col-xs-12" style="display: none"></label>
                                                    <span class="new-icon code-icon"></span>
                                                    <input type="text" id="verifyCode3" class="form-control" placeholder="图片验证码"
                                                           errormsg="请填写正确的验证码" nullmsg="请填写验证码" datatype="*5-5" name="verify">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <button class="btn y-btn" data-role="checkVerify">确定</button>
                                                </div>
                                                <div class="col-xs-6">
                                                    <button class="btn c-btn" data-role="closeVerify">取消</button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } else { ?>
                                        <?php if(check_verify_open('reg')): ?><div class="form-group new-form">
                                                <label for="verifyCode4" class=".sr-only col-xs-12"
                                                       style="display: none"></label>
                                                <span class="new-icon code-icon"></span>
                                                <input type="text" id="verifyCode4" class="form-control new-input" placeholder="验证码"
                                                       errormsg="请填写正确的验证码" nullmsg="请填写验证码" datatype="*5-5" name="verify">

                                                <div class="new-code lg_lf_fm_verify">
                                                    <img class="verifyimg reloadverify img-responsive" alt="点击切换"
                                                         src="<?php echo U('verify');?>">
                                                </div>
                                                <div class="col-xs-12 Validform_checktip text-warning lg_lf_fm_tip"></div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <span class="tips">输入验证码</span><?php endif; ?>
                                        <?php } ?>

                                    </div>
                                    <!--邮箱注册end--><?php break;?>
                                <?php case "mobile": ?><!--手机注册-->
                                    <div class="tab-pane <?php if($key == 0): ?>active in<?php endif; ?>" id="mobile_reg">

                                        <div class="form-group new-form">
                                            <label for="mobile" class=".sr-only col-xs-12" style="display: none"></label>
                                            <span class="new-icon phone-icon"></span>
                                            <input type="text" id="mobile" class="form-control form_check new-input" check-type="UserMobile" check-url="<?php echo U('ucenter/member/checkAccount');?>" <?php if($key != 0): ?>disabled="disabled"<?php endif; ?>
                                            placeholder="<?php echo L('_PLACEHOLDER_PHONE_');?>" .
                                            errormsg="<?php echo L('_ERROR_PHONE_INPUT_');?>" value="" name="username">

                                            <input type="hidden" name="reg_type" value="mobile" <?php if($key != 0): ?>disabled="disabled"<?php endif; ?>>
                                        </div>
                                        <span class="tips"><?php echo L('_PHONE_INPUT_');?></span>

                                        <?php if(modC('MOBILE_VERIFY_TYPE', 0, 'USERCONFIG') == 1){ ?>

                                        <div class="form-group new-form">
                                            <span class="new-icon code-icon"></span>
                                            <input type="text" class="form-control new-input" placeholder="<?php echo L('_VERIFY_CODE_');?>" name="reg_verify" <?php if($key != 0): ?>disabled="disabled"<?php endif; ?>>
                                            <a class="get-code green-btn" data-role="getVerify"><?php echo L('_PHONE_VERIFY_');?></a>
                                        </div>
                                        <span class="tips"><?php echo L('_VERIFY_CODE_INPUT_');?></span>

                                        <div class="form-group new-form verify-check" style=" display:none;">
                                            <h3>确认发送验证码</h3>
                                            <div class="row">
                                                <div class="col-xs-6">

                                                    <div class="lg_lf_fm_verify">
                                                        <img class="verifyimg reloadverify img-responsive" alt="点击切换" src="<?php echo U('verify',array('id'=>2));?>">
                                                    </div>
                                                    <div class="col-xs-12 Validform_checktip text-warning lg_lf_fm_tip"></div>
                                                </div>
                                                <div class="col-xs-6 input-box">
                                                    <label for="verifyCode2" class=".sr-only col-xs-12" style="display: none"></label>
                                                    <span class="new-icon code-icon"></span>
                                                    <input type="text" id="verifyCode2" class="form-control" placeholder="图片验证码"
                                                           errormsg="请填写正确的验证码" nullmsg="请填写验证码" datatype="*5-5" name="verify">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <button class="btn y-btn" data-role="checkVerify">确定</button>
                                                </div>
                                                <div class="col-xs-6">
                                                    <button class="btn c-btn" data-role="closeVerify">取消</button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } else { ?>
                                        <?php if(check_verify_open('reg')): ?><div class="form-group new-form">
                                                <label for="verifyCode5" class=".sr-only col-xs-12"
                                                       style="display: none"></label>
                                                <span class="new-icon code-icon"></span>
                                                <input type="text" id="verifyCode5" class="form-control new-input" placeholder="验证码"
                                                       errormsg="请填写正确的验证码" nullmsg="请填写验证码" datatype="*5-5" name="verify">

                                                <div class="new-code lg_lf_fm_verify">
                                                    <img class="verifyimg reloadverify img-responsive" alt="点击切换"
                                                         src="<?php echo U('verify');?>">
                                                </div>
                                                <div class="col-xs-12 Validform_checktip text-warning lg_lf_fm_tip"></div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <span class="tips">输入验证码</span><?php endif; ?>
                                        <?php } ?>

                                    </div>
                                    <!--手机注册end--><?php break; endswitch; endforeach; endif; else: echo "" ;endif; ?>



                        <div class="form-group new-form">
                            <label for="nickname" class=".sr-only col-xs-12" style="display: none"></label>
                            <span class="new-icon name-icon"></span>
                            <input type="text" id="nickname" class="form-control form_check new-input" check-type="Nickname"  check-url="<?php echo U('ucenter/member/checkNickname');?>" placeholder="请输入昵称" value="" name="nickname">
                        </div>
                        <span class="tips">输入昵称，只允许中文、字母和数字和下划线</span>
                        <div class="form-group new-form password_block">
                            <span class="new-icon password-icon"></span>
                            <input type="password" id="inputPassword" class="form-control new-input" check-length="6,30"  placeholder="请输入密码"  name="password">

                            <div class="input-group-addon show-password green-btn">
                                <a  href="javascript:void(0);" onclick="change_show(this)">show</a>
                            </div>
                        </div>
                        <span class="tips">请输入密码，6-30位字符</span>

                        <!--<div style="float: left;vertical-align: bottom;margin-top: 12px;color: #848484;">
                            已有账户， <a href="<?php echo U('Ucenter/Member/login');?>" title="" style="color: #03B38B;">登录</a>
                        </div>-->
                        <button type="submit" class="btn btn-primary new-btn green-btn">注册</button>


                    </div>
                </form>
            </div>
            <div class="col-xs-3 right-box">
                <p class="p1">已有账号？</p>
                <a href="<?php echo U('Ucenter/Member/login');?>"><p class="p2">直接登录</p></a>
            </div><?php endif; ?>
        <?php if($step != 'start' and $step != 'finish'): echo W('RegStep/view'); endif; ?>
        <?php if($step == 'finish'): ?><div class="col-xs-12" style="font-size: 16px;margin-top: 30px;">
                    <span>感谢您注册 <?php echo modC('WEB_SITE_NAME','OpenSNS开源社交系统','Config');?> ，希望你玩的愉快！
                        <a class="btn y-btn" href="<?php echo U('Ucenter/Config/index');?>" title="">完善个人资料</a> 或
                        <a class="btn y-btn" href="<?php echo U('home/Index/index');?>" title="">前往首页</a></span>
            </div><?php endif; ?>

    </div>

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


    <script>
        $(function(){
            $('.new-input').focus(function () {
                $(this).closest(".new-form").css('marginBottom','15px').next().css('display','block');
            });
            $('.new-input').blur(function () {
                $(this).closest(".new-form").css('marginBottom','30px').next().css('display','none');
            })
        })
    </script>
    <script type="text/javascript">
        var step="<?php echo ($step); ?>";
        if (MID == 0&&step=='start') {
            $(document)
                .ajaxStart(function () {
                    $("button:submit").addClass("log-in").attr("disabled", true);
                })
                .ajaxStop(function () {
                    $("button:submit").removeClass("log-in").attr("disabled", false);
                });
            $("form").submit(function () {
                toast.showLoading();
                var self = $(this);
//                console.log(self.serialize());
                $.post(self.attr("action"), self.serialize(), success, "json");
                return false;

                function success(data) {
                    if (data.status) {
                        //toast.success(data.info, '温馨提示');
                        setTimeout(function () {
                            window.location.href = data.url
                        }, 10);
                    } else {
                        toast.error(data.info, '温馨提示');
                        //self.find(".Validform_checktip").text(data.info);
                        //刷新验证码
                        $(".reloadverify").click();
                    }
                    toast.hideLoading();
                }
            });

            function change_show(obj) {
                if ($(obj).text().trim() == 'show') {
                    $(obj).html('hide');
                    $(obj).parents('.password_block').find('input').attr('type', 'text');
                } else {
                    $(obj).html('show');
                    $(obj).parents('.password_block').find('input').attr('type', 'password');
                }
            }


            function setNickname(obj) {
                var text = jQuery.trim($(obj).val());
                if (text != null && text != '') {
                    $('#nickname').val(text);
                }
            }

            $(function () {

                $(".reloadverify").click(function () {
                    var $this = $(this);
                    var verifyimg = $this.attr("src");
                    $this.attr("src", verifyimg + '&random=' + Math.random());
                });
            });



            $(function () {
                $("[data-role='getVerify']").click(function () {
                    var $this = $(this);
//                    toast.showLoading();
                    var account = $this.parents('.tab-pane').find('[name="username"]').val();
                    var type = $this.parents('.tab-pane').find('[name="reg_type"]').val();
//                    var url = "<?php echo U('ucenter/member/checkAccount');?>";

                    $.post(U('ucenter/member/checkAccount'),{account:account,type:type},function(res){
                        ajaxRerurn(res);
                        if(res.info == '验证成功') {
                            $('.verify-check').show();
                            $('[data-role="closeVerify"]').click(function() {
                                $('.verify-check').hide();
                                return false;
                            })
                        }
                    },'json')
                });

                $('[data-role="checkVerify"]').click(function(event) {
                    var $this = $(this);
//                    toast.showLoading();
                    var account = $this.parents('.tab-pane').find('[name="username"]').val();
                    var type = $this.parents('.tab-pane').find('[name="reg_type"]').val();
                    var verify = $this.parents('.tab-pane').find('[name="verify"]').val();

                    $.post("<?php echo U('ucenter/verify/sendVerify');?>", {account: account, type: type, action: 'member',verify:verify}, function (res) {
                        if (res.status) {
                            $('.verify-check').hide();
                            DecTime.obj = $this;
                            DecTime.time = "<?php echo modC('SMS_RESEND','60','USERCONFIG');?>";
                            $this.attr('disabled',true);
                            DecTime.dec_time();

                            toast.success(res.info);
                        }
                        else {
                            toast.error(res.info);
                        }
                        toast.hideLoading();
                    });
                    event.preventDefault();
                });

                $('#reg_nav li a').click(function(){
                    $('.tab-pane').find('input').attr('disabled',true);
                    $('.tab-pane').eq($("#reg_nav li a").index(this)).find('input').attr('disabled',false);
                })
                $("[type='submit']").click(function () {
                    $(this).parents('form').submit();
                })

                $('[href="#<?php echo ($type); ?>_reg"]').click()


            })
        }



        var DecTime = {
            obj:0,
            time:0,
            dec_time : function(){
                if(this.time > 0){
                    this.obj.text(this.time--+'S')
                    setTimeout("DecTime.dec_time()",1000)
                }else{
                    this.obj.text("<?php echo L('_EMAIL_VERIFY_');?>")
                    this.obj.attr('disabled',false)
                }

            }
        }

    </script>
    <link href="/yoyo/Application/Core/Static/css/form_check.css" rel="stylesheet" type="text/css">
    <script src='/yoyo/Application/Core/Static/js/form_check.js'></script>
    <script>
        // 验证密码长度
        $(function(){
            $('#inputPassword').after('<div class=" show_info" ></div>');
            $('#inputPassword').blur(function(){

                var obj =$('#inputPassword');
                var str =  obj.val().replace(/\s+/g, "");
                var html = '';
                if (str.length == 0) {
                    html = '<div class="send red"><div class="arrow"></div>'+"<?php echo L('_EMPTY_CANNOT_');?>"+'</div>';
                } else {
                    if (typeof (obj.attr('check-length')) != 'undefined') {
                        var strs = new Array(); //定义一数组
                        strs = obj.attr('check-length').split(","); //字符分割
                        if (strs[1]) {
                            if (strs[1] < str.length || str.length < strs[0]) {
                                html = '<div class="send red"><div class="arrow"></div>'+"<?php echo L('_LENGTH_ILLEGAL_');?>"+'</div>';
                            }
                        }
                        else {
                            if (strs[0] < str.length) {
                                html = '<div class="send red"><div class="arrow"></div>'+"<?php echo L('_LENGTH_ILLEGAL_');?>"+'</div>';
                            }
                        }
                    }
                    obj.parent().find('.show_info').html(html);
                }
            })
        })
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