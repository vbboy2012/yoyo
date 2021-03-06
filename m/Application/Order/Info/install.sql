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

