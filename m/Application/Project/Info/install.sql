-- -----------------------------
-- 表结构 `ocenter_project_advertisement`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_project_advertisement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) NOT NULL COMMENT '状态：1表示启用，0表示禁用，-1表示删除',
  `imgid` text NOT NULL COMMENT '图片',
  `link` text NOT NULL COMMENT '链接地址',
  `create_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL COMMENT '过期时间',
  `name` text NOT NULL COMMENT '广告名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_project_lists`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_project_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '客户uid',
  `file` varchar(50) NOT NULL COMMENT '文档',
  `cover` int(11) NOT NULL COMMENT '项目封面',
  `create_time` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `title` varchar(64) NOT NULL COMMENT '项目名称',
  `progress` float NOT NULL DEFAULT '30' COMMENT '项目进度',
  `cycle` int(11) NOT NULL COMMENT '预估周期',
  `is_private` tinyint(4) NOT NULL COMMENT '0=公共，1=私有',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='项目列表';


-- -----------------------------
-- 表结构 `ocenter_project_progress`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_project_progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL COMMENT '项目id',
  `file` varchar(16) NOT NULL DEFAULT '' COMMENT '文件',
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `is_check` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否查看',
  `title` varchar(64) NOT NULL COMMENT '进度描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_project_user`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_project_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '客户uid',
  `subscribe` tinyint(4) NOT NULL COMMENT '是否订阅 1为订阅 0为未订阅',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户订阅表';

