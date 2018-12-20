/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50711
Source Host           : localhost:3306
Source Database       : ceshi

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2018-12-18 20:52:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `th_activity`
-- ----------------------------
DROP TABLE IF EXISTS `th_activity`;
CREATE TABLE `th_activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动自增id',
  `activity_name` varchar(255) DEFAULT '' COMMENT '活动名称',
  `activity_url` varchar(255) DEFAULT '' COMMENT '活动图片',
  `activity_start_time` int(10) unsigned DEFAULT '0' COMMENT '活动开始时间',
  `activity_end_time` int(10) unsigned DEFAULT '0' COMMENT '活动结束时间',
  `activity_detail` text COMMENT '活动详情',
  `status` tinyint(3) unsigned DEFAULT '0' COMMENT '0正常1删除',
  `state` tinyint(3) unsigned DEFAULT '0' COMMENT '0开启1关闭',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `store_id` int(11) unsigned DEFAULT '0' COMMENT '店铺id',
  `store_name` varchar(255) DEFAULT '' COMMENT '店铺名称',
  `link_state` tinyint(1) unsigned DEFAULT '0' COMMENT '0内连接1外连接',
  `number` int(10) unsigned DEFAULT '0' COMMENT '活动可参与总人数',
  `participants_number` int(10) unsigned DEFAULT '0' COMMENT '活动已参与人数',
  `pid` int(11) unsigned DEFAULT '0' COMMENT '活动库id',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动表';

-- ----------------------------
-- Records of th_activity
-- ----------------------------

-- ----------------------------
-- Table structure for `th_activity_library`
-- ----------------------------
DROP TABLE IF EXISTS `th_activity_library`;
CREATE TABLE `th_activity_library` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动自增id',
  `activity_name` varchar(255) DEFAULT '' COMMENT '活动名称',
  `activity_url` varchar(255) DEFAULT '' COMMENT '活动图片',
  `activity_start_time` int(10) unsigned DEFAULT '0' COMMENT '活动开始时间',
  `activity_end_time` int(10) unsigned DEFAULT '0' COMMENT '活动结束时间',
  `activity_detail` text COMMENT '活动详情',
  `status` tinyint(3) unsigned DEFAULT '0' COMMENT '0正常1删除',
  `state` tinyint(3) unsigned DEFAULT '0' COMMENT '0开启1关闭',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `store_id` int(11) unsigned DEFAULT '0' COMMENT '店铺id',
  `store_name` varchar(255) DEFAULT '' COMMENT '店铺名称',
  `link_state` tinyint(1) unsigned DEFAULT '0' COMMENT '0内连接1外连接',
  `number` int(10) unsigned DEFAULT '0' COMMENT '活动可参与总人数',
  `participants_number` int(10) unsigned DEFAULT '0' COMMENT '活动已参与人数',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动库表';

-- ----------------------------
-- Records of th_activity_library
-- ----------------------------

-- ----------------------------
-- Table structure for `th_admin_log`
-- ----------------------------
DROP TABLE IF EXISTS `th_admin_log`;
CREATE TABLE `th_admin_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `create_time` int(10) NOT NULL DEFAULT '0',
  `user_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `log_info` varchar(255) NOT NULL DEFAULT '',
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `user_name` varchar(50) DEFAULT '' COMMENT '账号',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='系统日志表';

-- ----------------------------
-- Records of th_admin_log
-- ----------------------------
INSERT INTO `th_admin_log` VALUES ('48', '1545048933', '1', '登录系统', '127.0.0.1', 'admin');
INSERT INTO `th_admin_log` VALUES ('49', '1545049915', '3', '登录系统', '127.0.0.1', '小川小川');
INSERT INTO `th_admin_log` VALUES ('50', '1545049928', '1', '登录系统', '127.0.0.1', 'admin');
INSERT INTO `th_admin_log` VALUES ('51', '1545049963', '3', '登录系统', '127.0.0.1', '小川小川');
INSERT INTO `th_admin_log` VALUES ('52', '1545050001', '1', '登录系统', '127.0.0.1', 'admin');
INSERT INTO `th_admin_log` VALUES ('53', '1545050108', '3', '登录系统', '127.0.0.1', '小川小川');
INSERT INTO `th_admin_log` VALUES ('54', '1545050534', '1', '登录系统', '127.0.0.1', 'admin');

-- ----------------------------
-- Table structure for `th_advertisement`
-- ----------------------------
DROP TABLE IF EXISTS `th_advertisement`;
CREATE TABLE `th_advertisement` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '广告id',
  `type_id` int(11) unsigned DEFAULT '0' COMMENT '广告类型id',
  `image` varchar(255) DEFAULT '' COMMENT '广告图片',
  `name` varchar(255) DEFAULT '' COMMENT '广告名称',
  `url` varchar(255) DEFAULT '' COMMENT '跳转路径',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常 1删除',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `store_id` int(10) unsigned DEFAULT '0' COMMENT '店铺id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广告表';

-- ----------------------------
-- Records of th_advertisement
-- ----------------------------

-- ----------------------------
-- Table structure for `th_advertisement_type`
-- ----------------------------
DROP TABLE IF EXISTS `th_advertisement_type`;
CREATE TABLE `th_advertisement_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '广告类型自增id',
  `type_name` varchar(255) DEFAULT '' COMMENT '类型名称',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常1删除',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `store_id` int(10) unsigned DEFAULT '0' COMMENT '店铺id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='广告类型表';

-- ----------------------------
-- Records of th_advertisement_type
-- ----------------------------
INSERT INTO `th_advertisement_type` VALUES ('1', '111', '0', '1545049417', '1545049417', '1');

-- ----------------------------
-- Table structure for `th_commodity_bank`
-- ----------------------------
DROP TABLE IF EXISTS `th_commodity_bank`;
CREATE TABLE `th_commodity_bank` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品自增id',
  `store_id` int(11) unsigned DEFAULT '0' COMMENT '店铺id',
  `goods_name` varchar(255) DEFAULT '' COMMENT '商品名称',
  `goods_images` varchar(2000) DEFAULT '' COMMENT '商品图片已json形式存储',
  `goods_original_price` decimal(20,2) unsigned DEFAULT '0.00' COMMENT '商品原价',
  `goods_present_price` decimal(20,2) unsigned DEFAULT '0.00' COMMENT '商品现价',
  `goods_detail` text COMMENT '商品详情',
  `goods_specifications` varchar(255) DEFAULT '' COMMENT '商品规则已json形式存储',
  `goods_attribute` varchar(255) DEFAULT '' COMMENT '商品属性已json形式存储',
  `goods_stock` int(11) unsigned DEFAULT '0' COMMENT '库存',
  `state` tinyint(1) unsigned DEFAULT '0' COMMENT '0上架1下架',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常1删除2清除',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `type_id` int(11) unsigned DEFAULT '0' COMMENT '商品类型id',
  `images_detail` text COMMENT ' 图片信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='商品库表';

-- ----------------------------
-- Records of th_commodity_bank
-- ----------------------------
INSERT INTO `th_commodity_bank` VALUES ('4', '0', '232323', '[\"\\/uploads\\/member\\/20181218\\\\9b0cfbea07a2104cd4451bd29f90be16.jpg\",\"\\/uploads\\/member\\/20181218\\\\bb79e27bdafdab56d21871a7286ae063.png\",\"\\/uploads\\/member\\/20181218\\\\5f990b51bad337ea4bafc81de2980a2a.jpg\",\"\\/uploads\\/member\\/20181218\\\\056626c9f4f4b45744486f7a8419544d.jpg\"]', '500.00', '482.00', '23232', '[\"12\",\"2\",\"34\",\"6\"]', '[\"23\"]', '50', '1', '0', '1545132853', '1545137220', '9', '[\"{\\\"name\\\":\\\"\\\\u6d88\\\\u606f_\\\\u770b\\\\u56fe\\\\u738b.jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php855E.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"36.9kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u8bed\\\\u97f3\\\\u6587\\\\u4ef6\\\\u540e\\\\u53f0\\\\u4e0a\\\\u4f20\\\\u63a8\\\\u9001\\\\u7ba1\\\\u7406\\\\u610f\\\\u89c1.png\\\",\\\"type\\\":\\\"image\\\\\\/png\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\phpCC8.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"191.8kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u6211\\\\u4e5f\\\\u6765\\\\u4e92\\\\u52a8_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php4F64.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"77.2kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u6211\\\\u4e5f\\\\u6765\\\\u8bc4\\\\u4ef7_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php4F65.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"79.3kb\\\"}\"]');
INSERT INTO `th_commodity_bank` VALUES ('5', '0', '123213213', '[\"\\/uploads\\/member\\/20181218\\\\4f709f72ed2f9194cf95d33395d8ad24.png\"]', '5500.00', '2200.00', '333', '[\"1\"]', '[\"2\"]', '50', '1', '0', '1545135274', '1545135274', '4', '[\"{\\\"name\\\":\\\"\\\\u8bed\\\\u97f3\\\\u6587\\\\u4ef6\\\\u540e\\\\u53f0\\\\u4e0a\\\\u4f20\\\\u63a8\\\\u9001\\\\u7ba1\\\\u7406\\\\u610f\\\\u89c1.png\\\",\\\"type\\\":\\\"image\\\\\\/png\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php704E.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"191.8kb\\\"}\"]');
INSERT INTO `th_commodity_bank` VALUES ('6', '0', '123123', '[\"\\/uploads\\/member\\/20181218\\\\d86fcfc04473c07ff78d6652b24a75b5.jpg\"]', '500.00', '100.00', '123123', '[\"33\"]', '[\"66\"]', '50', '1', '0', '1545135339', '1545135339', '9', '[\"{\\\"name\\\":\\\"\\\\u65e0\\\\u7f51\\\\u7edc_\\\\u770b\\\\u56fe\\\\u738b.jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php6C98.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"28.9kb\\\"}\"]');

-- ----------------------------
-- Table structure for `th_goods`
-- ----------------------------
DROP TABLE IF EXISTS `th_goods`;
CREATE TABLE `th_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品自增id',
  `store_id` int(11) unsigned DEFAULT '0' COMMENT '店铺id',
  `goods_name` varchar(255) DEFAULT '' COMMENT '商品名称',
  `goods_images` varchar(2000) DEFAULT '' COMMENT '商品图片已json形式存储',
  `goods_original_price` decimal(20,2) unsigned DEFAULT '0.00' COMMENT '商品原价',
  `goods_present_price` decimal(20,2) unsigned DEFAULT '0.00' COMMENT '商品现价',
  `goods_detail` text COMMENT '商品详情',
  `goods_specifications` varchar(255) DEFAULT '' COMMENT '商品规则已json形式存储',
  `goods_attribute` varchar(255) DEFAULT '' COMMENT '商品属性已json形式存储',
  `goods_stock` int(11) unsigned DEFAULT '0' COMMENT '库存',
  `state` tinyint(1) unsigned DEFAULT '0' COMMENT '0上架1下架',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常1删除2清除',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `type_id` int(11) unsigned DEFAULT '0' COMMENT '商品类型id',
  `images_detail` text COMMENT ' 图片信息',
  `pid` int(11) unsigned DEFAULT '0' COMMENT '商品库id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='商品表';

-- ----------------------------
-- Records of th_goods
-- ----------------------------
INSERT INTO `th_goods` VALUES ('4', '1', '232323', '[\"\\/uploads\\/member\\/20181218\\\\9b0cfbea07a2104cd4451bd29f90be16.jpg\",\"\\/uploads\\/member\\/20181218\\\\bb79e27bdafdab56d21871a7286ae063.png\",\"\\/uploads\\/member\\/20181218\\\\5f990b51bad337ea4bafc81de2980a2a.jpg\",\"\\/uploads\\/member\\/20181218\\\\056626c9f4f4b45744486f7a8419544d.jpg\"]', '500.00', '482.00', '23232', '[\"12\",\"2\",\"34\",\"6\"]', '[\"23\"]', '50', '1', '0', '1545132853', '1545133617', '2', '[\"{\\\"name\\\":\\\"\\\\u6d88\\\\u606f_\\\\u770b\\\\u56fe\\\\u738b.jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php855E.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"36.9kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u8bed\\\\u97f3\\\\u6587\\\\u4ef6\\\\u540e\\\\u53f0\\\\u4e0a\\\\u4f20\\\\u63a8\\\\u9001\\\\u7ba1\\\\u7406\\\\u610f\\\\u89c1.png\\\",\\\"type\\\":\\\"image\\\\\\/png\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\phpCC8.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"191.8kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u6211\\\\u4e5f\\\\u6765\\\\u4e92\\\\u52a8_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php4F64.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"77.2kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u6211\\\\u4e5f\\\\u6765\\\\u8bc4\\\\u4ef7_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php4F65.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"79.3kb\\\"}\"]', '0');
INSERT INTO `th_goods` VALUES ('5', '0', '123123', '[\"\\/uploads\\/member\\/20181218\\\\d86fcfc04473c07ff78d6652b24a75b5.jpg\"]', '500.00', '100.00', '123123', '[\"33\"]', '[\"66\"]', '50', '1', '0', '1545136868', '1545136868', '9', '[\"{\\\"name\\\":\\\"\\\\u65e0\\\\u7f51\\\\u7edc_\\\\u770b\\\\u56fe\\\\u738b.jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php6C98.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"28.9kb\\\"}\"]', '6');
INSERT INTO `th_goods` VALUES ('6', '0', '123213213', '[\"\\/uploads\\/member\\/20181218\\\\4f709f72ed2f9194cf95d33395d8ad24.png\"]', '5500.00', '2200.00', '333', '[\"1\"]', '[\"2\"]', '50', '1', '0', '1545136892', '1545136892', '4', '[\"{\\\"name\\\":\\\"\\\\u8bed\\\\u97f3\\\\u6587\\\\u4ef6\\\\u540e\\\\u53f0\\\\u4e0a\\\\u4f20\\\\u63a8\\\\u9001\\\\u7ba1\\\\u7406\\\\u610f\\\\u89c1.png\\\",\\\"type\\\":\\\"image\\\\\\/png\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php704E.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"191.8kb\\\"}\"]', '5');
INSERT INTO `th_goods` VALUES ('7', '0', '232323', '[\"\\/uploads\\/member\\/20181218\\\\9b0cfbea07a2104cd4451bd29f90be16.jpg\",\"\\/uploads\\/member\\/20181218\\\\bb79e27bdafdab56d21871a7286ae063.png\",\"\\/uploads\\/member\\/20181218\\\\5f990b51bad337ea4bafc81de2980a2a.jpg\",\"\\/uploads\\/member\\/20181218\\\\056626c9f4f4b45744486f7a8419544d.jpg\"]', '500.00', '482.00', '23232', '[\"12\",\"2\",\"34\",\"6\"]', '[\"23\"]', '50', '1', '1', '1545136892', '1545136892', '2', '[\"{\\\"name\\\":\\\"\\\\u6d88\\\\u606f_\\\\u770b\\\\u56fe\\\\u738b.jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php855E.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"36.9kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u8bed\\\\u97f3\\\\u6587\\\\u4ef6\\\\u540e\\\\u53f0\\\\u4e0a\\\\u4f20\\\\u63a8\\\\u9001\\\\u7ba1\\\\u7406\\\\u610f\\\\u89c1.png\\\",\\\"type\\\":\\\"image\\\\\\/png\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\phpCC8.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"191.8kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u6211\\\\u4e5f\\\\u6765\\\\u4e92\\\\u52a8_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php4F64.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"77.2kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u6211\\\\u4e5f\\\\u6765\\\\u8bc4\\\\u4ef7_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php4F65.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"79.3kb\\\"}\"]', '4');
INSERT INTO `th_goods` VALUES ('9', '0', '232323', '[\"\\/uploads\\/member\\/20181218\\\\9b0cfbea07a2104cd4451bd29f90be16.jpg\",\"\\/uploads\\/member\\/20181218\\\\bb79e27bdafdab56d21871a7286ae063.png\",\"\\/uploads\\/member\\/20181218\\\\5f990b51bad337ea4bafc81de2980a2a.jpg\",\"\\/uploads\\/member\\/20181218\\\\056626c9f4f4b45744486f7a8419544d.jpg\"]', '500.00', '482.00', '23232', '[\"12\",\"2\",\"34\",\"6\"]', '[\"23\"]', '50', '1', '1', '1545137244', '1545137244', '9', '[\"{\\\"name\\\":\\\"\\\\u6d88\\\\u606f_\\\\u770b\\\\u56fe\\\\u738b.jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php855E.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"36.9kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u8bed\\\\u97f3\\\\u6587\\\\u4ef6\\\\u540e\\\\u53f0\\\\u4e0a\\\\u4f20\\\\u63a8\\\\u9001\\\\u7ba1\\\\u7406\\\\u610f\\\\u89c1.png\\\",\\\"type\\\":\\\"image\\\\\\/png\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\phpCC8.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"191.8kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u6211\\\\u4e5f\\\\u6765\\\\u4e92\\\\u52a8_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php4F64.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"77.2kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u6211\\\\u4e5f\\\\u6765\\\\u8bc4\\\\u4ef7_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php4F65.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"79.3kb\\\"}\"]', '4');

-- ----------------------------
-- Table structure for `th_goods_type`
-- ----------------------------
DROP TABLE IF EXISTS `th_goods_type`;
CREATE TABLE `th_goods_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品类型表',
  `goods_type_name` varchar(255) DEFAULT '' COMMENT '商品类型表',
  `store_id` int(11) unsigned DEFAULT '0' COMMENT '店铺id',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='商品类型表';

-- ----------------------------
-- Records of th_goods_type
-- ----------------------------
INSERT INTO `th_goods_type` VALUES ('1', '10', '1', '1545049492', '1545049492', '0');
INSERT INTO `th_goods_type` VALUES ('2', '11', '1', '1545049544', '1545049544', '0');
INSERT INTO `th_goods_type` VALUES ('3', '2022', '2', '1545049738', '1545049738', '0');
INSERT INTO `th_goods_type` VALUES ('4', '222222', '0', '1545050272', '1545050272', '0');
INSERT INTO `th_goods_type` VALUES ('5', '555', '1', '1545050431', '1545050431', '1');
INSERT INTO `th_goods_type` VALUES ('6', '5454', '1', '1545050520', '1545050520', '1');
INSERT INTO `th_goods_type` VALUES ('7', '666677788', '0', '1545050705', '1545050705', '1');
INSERT INTO `th_goods_type` VALUES ('8', '666', '1', '1545050638', '1545050638', '1');
INSERT INTO `th_goods_type` VALUES ('9', '家电', '0', '1545135306', '1545135306', '0');

-- ----------------------------
-- Table structure for `th_information`
-- ----------------------------
DROP TABLE IF EXISTS `th_information`;
CREATE TABLE `th_information` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `search_box_content` varchar(255) DEFAULT '' COMMENT '搜索框默认内容',
  `site_name` varchar(255) DEFAULT '' COMMENT '网站名称',
  `site_title` varchar(255) DEFAULT '' COMMENT '网站标题',
  `phone` varchar(20) DEFAULT '' COMMENT '联系电话',
  `email` varchar(30) DEFAULT '' COMMENT '电子邮箱',
  `address` varchar(255) DEFAULT '' COMMENT '联系地址',
  `copyright_information` varchar(255) DEFAULT '' COMMENT '版权信息',
  `record_information` varchar(255) DEFAULT '' COMMENT '备案信息',
  `key_word` varchar(255) DEFAULT '' COMMENT '关键词',
  `site_description` varchar(2000) DEFAULT '' COMMENT '网站描述',
  `friendship_link` varchar(255) DEFAULT '' COMMENT '友情链接',
  `site_switch_state` tinyint(1) unsigned DEFAULT '0' COMMENT '0关1开',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统基本信息';

-- ----------------------------
-- Records of th_information
-- ----------------------------

-- ----------------------------
-- Table structure for `th_item_bank`
-- ----------------------------
DROP TABLE IF EXISTS `th_item_bank`;
CREATE TABLE `th_item_bank` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '0单选题1多选题',
  `problem` varchar(255) DEFAULT '' COMMENT '问题',
  `answer` varchar(50) DEFAULT '' COMMENT '正确答案',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常1删除',
  `content` varchar(2000) DEFAULT '' COMMENT '所以选项已json形式存储',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `store_id` int(11) unsigned DEFAULT '0' COMMENT '店铺id',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='总题库';

-- ----------------------------
-- Records of th_item_bank
-- ----------------------------

-- ----------------------------
-- Table structure for `th_problem`
-- ----------------------------
DROP TABLE IF EXISTS `th_problem`;
CREATE TABLE `th_problem` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '0单选题1多选题',
  `problem` varchar(255) DEFAULT '' COMMENT '问题',
  `answer` varchar(50) DEFAULT '' COMMENT '正确答案',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常1删除',
  `content` varchar(2000) DEFAULT '' COMMENT '所以选项已json形式存储',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `store_id` int(11) unsigned DEFAULT '0' COMMENT '店铺id',
  `pid` int(11) unsigned DEFAULT '0' COMMENT '题库自增id',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺下题库';

-- ----------------------------
-- Records of th_problem
-- ----------------------------

-- ----------------------------
-- Table structure for `th_questionnaire`
-- ----------------------------
DROP TABLE IF EXISTS `th_questionnaire`;
CREATE TABLE `th_questionnaire` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '问卷自增id',
  `title` varchar(255) DEFAULT '' COMMENT '标题',
  `start_time` int(10) unsigned DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) unsigned DEFAULT '0' COMMENT '结束时间',
  `questionnaire_text` varchar(2000) DEFAULT '' COMMENT '问卷正文',
  `opinion_completion` varchar(2000) DEFAULT '' COMMENT '意见填写',
  `problem_id` varchar(255) DEFAULT '' COMMENT '问题',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常1删除',
  `store_id` int(11) unsigned DEFAULT '0' COMMENT '店铺id',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='调研问卷表';

-- ----------------------------
-- Records of th_questionnaire
-- ----------------------------
