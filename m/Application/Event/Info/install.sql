-- -----------------------------
-- 表结构 `ocenter_event`
-- -----------------------------

CREATE TABLE IF NOT EXISTS `ocenter_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '发起人',
  `title` varchar(255) NOT NULL COMMENT '活动名称',
  `explain` text NOT NULL COMMENT '详细内容',
  `sTime` int(11) NOT NULL COMMENT '活动开始时间',
  `eTime` int(11) NOT NULL COMMENT '活动结束时间',
  `address` varchar(255) NOT NULL COMMENT '活动地点',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `limitCount` int(11) NOT NULL COMMENT '限制人数',
  `cover_id` int(11) NOT NULL COMMENT '封面ID',
  `deadline` int(11) NOT NULL,
  `attentionCount` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `view_count` int(11) NOT NULL,
  `reply_count` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `signCount` int(11) NOT NULL,
  `is_recommend` tinyint(4) NOT NULL,
  `phone` varchar(15) NOT NULL DEFAULT '',
  `pay_way` varchar(32) NOT NULL DEFAULT '' COMMENT '支付方式',
  `price` float(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '活动支付金额',
  `self_join` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '发起人参与活动，0参加，1不参加',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_event_attend`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_event_attend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` bigint(20) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0为报名，1为参加',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_event_type`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_event_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `allow_post` tinyint(4) NOT NULL,
  `pid` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ocenter_event_search` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `historical` varchar(50) NOT NULL COMMENT '历史记录',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='活动搜索';


ALTER TABLE  `ocenter_event` ADD `pay_way` varchar(32) NOT NULL DEFAULT '' COMMENT '支付方式';
ALTER TABLE  `ocenter_event` ADD `price` float(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '活动支付金额';
ALTER TABLE  `ocenter_event` ADD `self_join` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '发起人参与活动，0参加，1不参加';

