ALTER TABLE  `ocenter_channel` ADD  `out_site` TINYINT( 2 ) NOT NULL DEFAULT  '0' COMMENT  '是否为站外链接' AFTER  `target`;

--
-- 表的结构 `ocenter_weibo_top`
--

CREATE TABLE IF NOT EXISTS `ocenter_weibo_top` (
  `weibo_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(64) NOT NULL COMMENT '置顶标题',
  `dead_time` int(11) NOT NULL COMMENT '过期日期',
  `crowd_id` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `create_time` int(11) NOT NULL,
  KEY `weibo_id` (`weibo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='置顶微博表';



ALTER TABLE  `ocenter_weibo` ADD  `goods_id` INT( 11 ) NOT NULL COMMENT  '商品id' AFTER  `crowd_id`;

ALTER TABLE  `ocenter_mall_goods` CHANGE  `price`  `price` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00' COMMENT  '商品价格';

ALTER TABLE  `ocenter_mall_goods` ADD  `is_hot` TINYINT( 4 ) NOT NULL DEFAULT  '0' COMMENT  '是否热门';


--
-- 表的结构 `ocenter_collect`
--

CREATE TABLE IF NOT EXISTS `ocenter_collect` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `module` varchar(32) NOT NULL COMMENT '模块名',
  `table` varchar(32) NOT NULL COMMENT '表名',
  `row` int(11) NOT NULL,
  `create_time` int(11) NOT NULL COMMENT '收藏时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



--
-- 表的结构 `ocenter_redbag`
--

CREATE TABLE IF NOT EXISTS `ocenter_redbag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  `all_money` double DEFAULT '0',
  `sell_money` double DEFAULT '0',
  `rank` text NOT NULL,
  `content` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `redbag_type` tinyint(4) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 表的结构 `ocenter_redbag_list`
--

CREATE TABLE IF NOT EXISTS `ocenter_redbag_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `redbagId` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `get_bag` double DEFAULT '0',
  `create_time` int(11) NOT NULL,
  `best_luck` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;