-- -----------------------------
-- 表结构 `ocenter_coin_addr`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_coin_addr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `addr_type` tinyint(2) NOT NULL COMMENT '类型：1充值,2提款',
  `coin_type` tinyint(2) NOT NULL COMMENT '类型：1BTC,2ETH',
  `addr` varchar(50) NOT NULL COMMENT '钱包地址',
  `status` tinyint(2) NOT NULL COMMENT '状态：1正常，2冻结',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='钱包地址表';

-- -----------------------------
-- 表结构 `ocenter_tradead`
-- -----------------------------

CREATE TABLE IF NOT EXISTS `ocenter_tradead` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` tinyint(2) NOT NULL COMMENT '广告类型：1在线sell，2在线buy，3本地sell，4本地buy',
  `coin_type` tinyint(2) NOT NULL COMMENT '币种',
  `country` smallint(4) NOT NULL COMMENT '国家地区',
  `currency` varchar(5) NOT NULL COMMENT '货币类型',
  `market` CHAR(20) NULL COMMENT '市场价格源',
  `formula` CHAR(50) NULL COMMENT '计价公式',
  `price` decimal(10,2) NOT NULL COMMENT '价格',
  `pre_price` tinyint(2) NOT NULL COMMENT '溢价',
  `max_price` int(11) NULL COMMENT 'max',
  `min_price` int(11) NULL COMMENT 'min',
  `pay_type` CHAR (50) NOT NULL COMMENT '付款方式',
  `pay_remark` varchar(200) NULL COMMENT '付款方式备注',
  `pay_time` int(11) NULL,
  `pay_addr` varchar(50) NULL COMMENT '见面地点',
  `pay_text` varchar(200) NULL COMMENT '交易条款',
  `auto_message` varchar(200) NULL COMMENT '自动回复消息',
  `is_safe` tinyint(2) NOT NULL DEFAULT '0' COMMENT '安全验证',
  `is_trust` tinyint(2) NOT NULL DEFAULT '0'  COMMENT '信任验证',
  `is_price` tinyint(2) NOT NULL DEFAULT '0'  COMMENT '固定价格',
  `start0` tinyint(1) NULL,
  `end0` tinyint(1) NULL,
  `start1` tinyint(1) NULL,
  `end1` tinyint(1) NULL,
  `start2` tinyint(1) NULL,
  `end2` tinyint(1) NULL,
  `start3` tinyint(1) NULL,
  `end3` tinyint(1) NULL,
  `start4` tinyint(1) NULL,
  `end4` tinyint(1) NULL,
  `start5` tinyint(1) NULL,
  `end5` tinyint(1) NULL,
  `start6` tinyint(1) NULL,
  `end6` tinyint(1) NULL,
  `status` tinyint(2) NOT NULL COMMENT '状态：1开放，0关闭',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4003 DEFAULT CHARSET=utf8 COMMENT='交易广告表';

-- -----------------------------
-- 表结构 `ocenter_trade_order`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_trade_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ad_id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `ad_uid` int(11) NOT NULL,
  `get_uid` int(11) NOT NULL,
  `coin_num` decimal(10,6) NOT NULL COMMENT '交易数量',
  `trade_price` decimal(10,2) NOT NULL COMMENT '交易金额',
  `fee` decimal(10,6) NOT NULL COMMENT '手续费',
  `pay_code` CHAR (7) NOT NULL COMMENT '付款参考编码',
  `pay_text` varchar(200) NULL COMMENT '交易条款',
  `status` tinyint(2) NOT NULL COMMENT '状态：1等待付款，2付款完毕，3确认完成，4，申诉，5取消',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='交易表';

-- -----------------------------
-- 表结构 `ocenter_ticket`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_ticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL,
  `type` tinyint(1) NOT NULL COMMENT '类型',
  `question_id` varchar(50) NOT NULL,
  `email` varchar(20) NOT NULL,
  `content` varchar(200) NOT NULL COMMENT '内容',
  `images` varchar(50) NOT NULL COMMENT '图片',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NULL,
  `status` tinyint(2) NOT NULL DEFAULT '2' COMMENT '状态：1已处理2未处理',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支持工单';

CREATE TABLE IF NOT EXISTS `ocenter_market` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `market` CHAR(20) NOT NULL,
  `high` decimal(10,2) NOT NULL,
  `low` decimal(10,2) NOT NULL,
  `bid` decimal(10,2) NOT NULL,
  `ask` decimal(10,2) NOT NULL,
  `close` decimal(10,2) NOT NULL,
  `avg` decimal(10,2) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `market` (`market`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='BTC市场价格表';

-- ----------------------------
-- Table structure for `ocenter_country`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_country` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `en_name` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL DEFAULT '',
  `code2` varchar(5) NOT NULL DEFAULT '',
  `code3` varchar(5) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=244 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ocenter_pay`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_pay` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `en_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ocenter_country`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_currency` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `rate` decimal(10,4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=172 DEFAULT CHARSET=utf8;