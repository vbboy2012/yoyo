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

-- -----------------------------
-- 表结构 `ocenter_order_goods`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_order_goods` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '订单号',
  `goods_id` varchar(50) NOT NULL COMMENT '商品id',
  `goods_type` int(11) NOT NULL COMMENT '商品类型',
  `uid` int(11) NOT NULL COMMENT '下单用户',
  `amount` decimal(10,2) NOT NULL COMMENT '交易金额',
  `field` int(11) NOT NULL COMMENT '交易字段',
  `method` varchar(50) NOT NULL COMMENT '交易方式',
  `status` tinyint(4) NOT NULL COMMENT '订单状态',
  `is_pay` tinyint(4) NOT NULL COMMENT '支付状态0未支付1已支付',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `pay_time` int(11) NOT NULL COMMENT '支付时间',
  `process` int(11) NOT NULL COMMENT '订单流程',
  `wechat_order` varchar(50) NOT NULL COMMENT '微信订单号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15002536577403 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_order_recharge`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_order_recharge` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '订单号',
  `field` int(11) NOT NULL COMMENT '充值字段',
  `amount` decimal(10,2) NOT NULL COMMENT '充值数额',
  `method` varchar(50) NOT NULL COMMENT '支付方式',
  `uid` int(11) NOT NULL COMMENT '充值用户',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `is_pay` tinyint(4) NOT NULL COMMENT '支付状态',
  `pay_time` int(11) NOT NULL COMMENT '支付时间',
  `process` int(11) NOT NULL COMMENT '订单流程',
  `wechat_order` varchar(50) NOT NULL COMMENT '微信订单号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15000975081936 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_order_withdraw`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_order_withdraw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field` int(11) NOT NULL COMMENT '提现字段',
  `amount` decimal(10,2) NOT NULL COMMENT '提现金额',
  `method` varchar(50) NOT NULL COMMENT '提现方式',
  `uid` int(11) NOT NULL COMMENT '提现用户',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `is_pay` tinyint(4) NOT NULL COMMENT '支付状态',
  `pay_uid` int(11) NOT NULL COMMENT '支付者',
  `pay_time` int(11) NOT NULL COMMENT '支付时间',
  `freeze_amount` decimal(10,2) NOT NULL COMMENT '冻结积分',
  `account_info` varchar(200) NOT NULL COMMENT '账户信息',
  `process` int(11) NOT NULL COMMENT '订单流程',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ocenter_consumption_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` varchar(255) NOT NULL DEFAULT '' COMMENT '用户id',
  `behavior` text NOT NULL COMMENT '行为',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `amount` decimal(10,2) NOT NULL COMMENT '金额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户消费记录日志' AUTO_INCREMENT=75 ;

-- 新增广告菜单
INSERT INTO `ocenter_m_menu` (`title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`, `icon`, `module`) VALUES
('广告信息', 2, 0, 'Admin/Ucenter/advertisement', 0, '', '广告', 0, '', ''),
('新增广告', 2, 0, 'Admin/Ucenter/addAdvertisement', 0, '', '广告', 0, '', '');

-- 新增广场菜单
INSERT INTO `ocenter_m_channel` (`pid`, `title`, `url`, `sort`, `create_time`, `update_time`, `status`, `target`, `out_site`, `color`, `band_color`, `band_text`, `icon`, `image`, `remark`) VALUES
(0, '广场', 'ucenter/index/square', 1, 0, 0, 1, 0, 0, '', '', '', 'icon-remen', 0, '');

-- 新增加精字段
ALTER TABLE  `ocenter_forum_post` ADD  `is_essence` INT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '是否加精';


--
-- 转存表中的数据 `ocenter_m_menu`
--

INSERT INTO `ocenter_m_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`, `icon`, `module`) VALUES
(268, '支付系统', 0, 0, 'Order/index', 1, '', '', 0, '', 'Order'),
(269, '配置', 268, 0, 'Order/config', 0, '', '配置管理', 0, '', 'Order'),
(270, '商品订单列表', 268, 0, 'Order/index', 0, '', '订单列表', 0, '', 'Order'),
(271, '充值订单列表', 268, 0, 'Order/rechargeList', 0, '', '订单列表', 0, '', 'Order'),
(272, '提现记录表', 268, 0, 'Order/withdrawList', 0, '', '订单列表', 0, '', 'Order');