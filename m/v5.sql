# 新增打赏消息表
CREATE TABLE IF NOT EXISTS `ocenter_m_reward` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `message` text NOT NULL,
  `authorid` int(11) NOT NULL,
  `Articleid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `table_name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

#新增资讯搜索表
CREATE TABLE IF NOT EXISTS `ocenter_news_search` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `historical` varchar(50) NOT NULL COMMENT '历史记录',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='资讯';

#新增问答搜索表
CREATE TABLE IF NOT EXISTS `ocenter_question_search` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `historical` varchar(50) NOT NULL COMMENT '历史记录',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='问答';


# 新增资讯楼中楼评论表
CREATE TABLE IF NOT EXISTS `ocenter_news_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `to_f_reply_id` int(11) NOT NULL,
  `to_reply_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `uid` int(11) NOT NULL,
  `to_uid` int(11) NOT NULL,
  `ctime` int(11) NOT NULL,
  `is_del` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

#新增广告表
CREATE TABLE IF NOT EXISTS `ocenter_advertisement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` text NOT NULL COMMENT '广告的位置，表示在什么地方显示',
  `status` tinyint(3) NOT NULL COMMENT '状态：1表示启用，0表示禁用，-1表示删除',
  `imgid` text NOT NULL COMMENT '图片',
  `link` text NOT NULL COMMENT '链接地址',
  `create_time` int(11) NOT NULL,
  `name` text NOT NULL COMMENT '广告名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

#新增广告菜单
INSERT INTO `ocenter_m_menu` (`title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`, `icon`, `module`) VALUES
('广告信息', 2, 0, 'Admin/Ucenter/advertisement', 0, '', '广告', 0, '', ''),
('新增广告', 2, 0, 'Admin/Ucenter/addAdvertisement', 0, '', '广告', 0, '', '');

#新增广场菜单
INSERT INTO `ocenter_m_channel` (`pid`, `title`, `url`, `sort`, `create_time`, `update_time`, `status`, `target`, `out_site`, `color`, `band_color`, `band_text`, `icon`, `image`, `remark`) VALUES
(0, '广场', 'ucenter/index/square', 1, 0, 0, 1, 0, 0, '', '', '', 'icon-remen', 0, '');

#新增回复可见字段
ALTER TABLE  `ocenter_forum_post` ADD  `hide` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '是否回复可见';

#新增加精字段
ALTER TABLE  `ocenter_forum_post` ADD  `is_essence` INT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '是否加精';

#修改悬赏类型字段的类型
ALTER TABLE  `ocenter_question` CHANGE  `leixing`  `leixing` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '类型';

#新增付费下载表
CREATE TABLE IF NOT EXISTS `ocenter_forum_pay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `forum_post_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

#新增图片字段
ALTER TABLE  `ocenter_question` ADD  `img_id` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
# 新增文件ID字段
ALTER TABLE `ocenter_forum_post` ADD `file_id` INT(11) NULL DEFAULT '0';
# 新增付费下载相关字段
ALTER TABLE  `ocenter_forum_post` ADD  `pay_type` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '付费类型';
ALTER TABLE  `ocenter_forum_post` ADD  `pay_num` INT( 11 ) NOT NULL COMMENT  '付费数量';
ALTER TABLE  `ocenter_forum_post` ADD  `pay_on` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '是否开启付费下载';

ALTER TABLE  `ocenter_file` ADD  `download_num` INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  '下载数';
#新增图片字段
ALTER TABLE  `ocenter_weibo_comment` ADD  `img_id` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '图片id';

ALTER TABLE  `ocenter_weibo_crowd` ADD  `is_show` INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  '是否展示在圈子首页' AFTER  `invisible`;
ALTER TABLE  `ocenter_weibo_crowd` ADD  `sort` INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  '圈子排序' AFTER  `is_show`;

CREATE TABLE IF NOT EXISTS `ocenter_collect` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `module` varchar(32) NOT NULL COMMENT '模块名',
  `table` varchar(32) NOT NULL COMMENT '表名',
  `row` int(11) NOT NULL,
  `create_time` int(11) NOT NULL COMMENT '收藏时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;