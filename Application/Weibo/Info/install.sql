-- -----------------------------
-- 表结构 `ocenter_weibo`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_weibo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `comment_count` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `is_top` tinyint(4) NOT NULL,
  `type` varchar(255) NOT NULL,
  `data` text NOT NULL,
  `repost_count` int(11) NOT NULL,
  `from` varchar(40) NOT NULL,
  `crowd_id` int(11) NOT NULL COMMENT '圈子类型',
  `pos` varchar(20) NOT NULL,
  `reply_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后一次评论时间',
  `is_crowd_top` tinyint(4) NOT NULL,
  `geolocation_id` int(11) NOT NULL COMMENT '地理信息id',
  PRIMARY KEY (`id`),
  KEY `crowd_id` (`crowd_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;


-- -----------------------------
-- 表结构 `ocenter_weibo_cache`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_weibo_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weibo_id` int(11) NOT NULL,
  `groups` varchar(100) NOT NULL,
  `cache_html` text NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `weibo_id` (`weibo_id`),
  KEY `groups` (`groups`),
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='微博html缓存表' AUTO_INCREMENT=1 ;


-- -----------------------------
-- 表结构 `ocenter_weibo_comment`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_weibo_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `weibo_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `to_comment_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;


-- -----------------------------
-- 表结构 `ocenter_weibo_crowd`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_weibo_crowd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `title` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `post_count` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `allow_user_post` int(11) NOT NULL,
  `logo` int(11) NOT NULL,
  `background` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `intro` text NOT NULL,
  `notice` text NOT NULL COMMENT '圈子公告',
  `type` tinyint(4) NOT NULL COMMENT '圈子类型，0为公共的，1为私有的',
  `need_pay` float NOT NULL DEFAULT '0' COMMENT '私有圈子是否付费入圈',
  `pay_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '支付类型',
  `activity` int(11) NOT NULL,
  `member_count` int(11) NOT NULL,
  `member_alias` varchar(10) NOT NULL,
  `order_type` int(11) NOT NULL COMMENT '圈内动态排序方式 0:最新发表  1:最新回复',
  `invisible` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否私密（1:私密，0:公开）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;


-- -----------------------------
-- 表结构 `ocenter_weibo_crowd_member`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_weibo_crowd_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `crowd_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `activity` int(11) NOT NULL,
  `last_view` int(11) NOT NULL,
  `position` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1为普通成员，2为管理员，3为创建者',
  `contribution` int(11) NOT NULL COMMENT '贡献',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;


-- -----------------------------
-- 表结构 `ocenter_weibo_crowd_score`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_weibo_crowd_score` (
  `crowd_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '圈子id',
  `score1` float NOT NULL DEFAULT '0' COMMENT '代码量',
  `score2` float NOT NULL DEFAULT '0' COMMENT '威望',
  `score3` float NOT NULL DEFAULT '0' COMMENT '贡献',
  `score4` float NOT NULL DEFAULT '0' COMMENT '人民币',
  `score6` float NOT NULL DEFAULT '0' COMMENT '云市场代金券',
  `score8` float NOT NULL DEFAULT '0' COMMENT '搬砖',
  PRIMARY KEY (`crowd_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='圈子账户' AUTO_INCREMENT=1 ;


-- -----------------------------
-- 表结构 `ocenter_weibo_crowd_type`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_weibo_crowd_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  `status` tinyint(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='圈子的分类表' AUTO_INCREMENT=1 ;


-- -----------------------------
-- 表结构 `ocenter_weibo_long`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_weibo_long` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weibo_id` int(11) NOT NULL,
  `long_content` text NOT NULL COMMENT '长微博内容',
  `title` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='长微博内容' AUTO_INCREMENT=1 ;


-- -----------------------------
-- 表结构 `ocenter_weibo_top`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_weibo_top` (
  `weibo_id` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`weibo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='置顶微博表';


-- -----------------------------
-- 表结构 `ocenter_weibo_topic`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_weibo_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '话题名',
  `logo` int(255) NOT NULL DEFAULT '0' COMMENT '话题logo',
  `intro` varchar(255) NOT NULL COMMENT '导语',
  `qrcode` int(11) NOT NULL COMMENT '二维码',
  `uadmin` varchar(255) NOT NULL DEFAULT '0' COMMENT '话题管理   默认无',
  `read_count` int(11) NOT NULL DEFAULT '0' COMMENT '阅读',
  `is_top` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `weibo_num` int(11) NOT NULL DEFAULT '0' COMMENT '微博数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;


-- -----------------------------
-- 表结构 `ocenter_weibo_topic_follow`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_weibo_topic_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;


-- -----------------------------
-- 表结构 `ocenter_weibo_topic_link`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_weibo_topic_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weibo_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `create_time` int(11) NOT NULL,
  `is_top` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='话题、微博关联表' AUTO_INCREMENT=1 ;

set @pid=0;
select @pid:= id from `ocenter_menu` where title = '微博';
INSERT INTO `ocenter_menu` (`title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`, `icon`, `module`) VALUES
( '圈子配置',@pid,'0','Weibo/crowdConfig','0','','圈子管理','0','',''),
( '圈子类型',@pid,'0','Weibo/crowdType','0','','圈子管理','0','',''),
( '圈子列表',@pid,'0','Weibo/crowd','0','','圈子管理','0','',''),
( '编辑圈子',@pid,'0','Weibo/editCrowd','0','','圈子管理','0','',''),
( '设置圈子类型状态',@pid,'0','Weibo/setcrowdtypestatus','1','','圈子管理','0','',''),
( '设置圈子状态',@pid,'0','Weibo/setcrowdstatus','1','','圈子管理','0','',''),
( '圈子是否可发送微博',@pid,'0','Weibo/doCrowdAllowPost','1','','圈子管理','0','',''),
( '执行默认信任',@pid,'0','Weibo/followCrowd','0','','圈子管理','0','',''),
( '修复信任数脚本',@pid,'0','Weibo/repaircrowdfollow','1','','圈子管理','0','','');