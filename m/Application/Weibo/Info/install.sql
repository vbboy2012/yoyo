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
  `pos` varchar(20) NOT NULL,
  `crowd_id` int(11) NOT NULL COMMENT '圈子类型',
  `reply_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后一次评论时间',
  `is_crowd_top` tinyint(4) NOT NULL,
  `geolocation_id` int(11) NOT NULL COMMENT '地理信息id',
  PRIMARY KEY (`id`),
  KEY `crowd_id` (`crowd_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


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
  KEY `groups` (`groups`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微博html缓存表';


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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


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
  `allow_user_post` int(11) NOT NULL COMMENT '允许用户发微博',
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
  `is_show` int(11) NOT NULL DEFAULT '0' COMMENT '是否展示在圈子首页',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '圈子排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


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
  `contribution` float NOT NULL DEFAULT '0' COMMENT '贡献',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;


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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='圈子账户';

-- -----------------------------
-- 表结构 `ocenter_weibo_crowd_search`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_weibo_crowd_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `historical` varchar(50) NOT NULL COMMENT '历史记录',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='圈子搜索';


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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='圈子的分类表';


-- -----------------------------
-- 表结构 `ocenter_weibo_long`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_weibo_long` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weibo_id` int(11) NOT NULL,
  `long_content` text NOT NULL COMMENT '长微博内容',
  `title` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='长微博内容';


-- -----------------------------
-- 表结构 `ocenter_weibo_top`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_weibo_top` (
  `weibo_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(64) NOT NULL COMMENT '置顶标题',
  `dead_time` int(11) NOT NULL COMMENT '过期日期',
  `crowd_id` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `create_time` int(11) NOT NULL,
  UNIQUE KEY `weibo_id` (`weibo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='置顶微博表';


-- -----------------------------
-- 表结构 `ocenter_weibo_topic`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_weibo_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '话题名',
  `logo` varchar(255) NOT NULL DEFAULT '/topicavatar.jpg' COMMENT '话题logo',
  `intro` varchar(255) NOT NULL COMMENT '导语',
  `qrcode` varchar(255) NOT NULL COMMENT '二维码',
  `uadmin` int(11) NOT NULL DEFAULT '0' COMMENT '话题管理   默认无',
  `read_count` int(11) NOT NULL DEFAULT '0' COMMENT '阅读',
  `is_top` tinyint(4) NOT NULL,
  `weibo_num` int(11) NOT NULL DEFAULT '0' COMMENT '微博数',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='话题、微博关联表';

-- -----------------------------
-- 表内记录 `ocenter_weibo_crowd_type`
-- -----------------------------
INSERT INTO `ocenter_weibo_crowd_type` VALUES ('1', ' 分类一', '1', '0', '0');


ALTER TABLE  `ocenter_weibo_crowd` ADD  `is_show` INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  '是否展示在圈子首页' AFTER  `invisible`;
ALTER TABLE  `ocenter_weibo_crowd` ADD  `sort` INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  '圈子排序' AFTER  `is_show`;
