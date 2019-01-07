/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50711
Source Host           : localhost:3306
Source Database       : ceshi

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2019-01-06 19:58:17
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
  `url` varchar(255) DEFAULT '' COMMENT '链接地址',
  `banner` tinyint(1) unsigned DEFAULT '1' COMMENT '轮播0是1否',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='活动表';

-- ----------------------------
-- Records of th_activity
-- ----------------------------
INSERT INTO `th_activity` VALUES ('1', '123123', '/uploads/member/20190105\\99895cac3226529a2a9972bfd530a7cf.jpg', '1546617600', '1547136000', '123123123', '0', '0', '1546667394', '1546667394', '1', '', '0', '0', '0', '0', '', '1');

-- ----------------------------
-- Table structure for `th_browsing_log`
-- ----------------------------
DROP TABLE IF EXISTS `th_browsing_log`;
CREATE TABLE `th_browsing_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `pid` int(11) unsigned DEFAULT NULL COMMENT '商品id/活动id等',
  `type` tinyint(1) unsigned DEFAULT '1' COMMENT '1商品2活动',
  `createTime` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `updateTime` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常 1删除',
  `userId` int(11) unsigned DEFAULT '0' COMMENT '用户id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='浏览记录';

-- ----------------------------
-- Records of th_browsing_log
-- ----------------------------
INSERT INTO `th_browsing_log` VALUES ('8', '10', '1', '1546773346', '1546773346', '1', '1');
INSERT INTO `th_browsing_log` VALUES ('9', '11', '1', '1546773352', '1546773352', '1', '1');
INSERT INTO `th_browsing_log` VALUES ('10', '10', '1', '1546774184', '1546774184', '1', '1');
INSERT INTO `th_browsing_log` VALUES ('11', '11', '1', '1546774471', '1546774471', '0', '1');

-- ----------------------------
-- Table structure for `th_collection_and_coupons`
-- ----------------------------
DROP TABLE IF EXISTS `th_collection_and_coupons`;
CREATE TABLE `th_collection_and_coupons` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `goods_id` int(11) unsigned DEFAULT '0' COMMENT '商品id',
  `userId` int(11) unsigned DEFAULT '0' COMMENT '用户id',
  `type` tinyint(1) unsigned DEFAULT '1' COMMENT '1收藏2领取优惠卷',
  `createTime` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `updateTime` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='收藏与优惠券';

-- ----------------------------
-- Records of th_collection_and_coupons
-- ----------------------------
INSERT INTO `th_collection_and_coupons` VALUES ('16', '11', '1', '1', '1546775638', '1546775638', '1');

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
  `collection_number` int(11) unsigned DEFAULT '0' COMMENT '收藏数',
  `sell_well` tinyint(1) unsigned DEFAULT '1' COMMENT '爆款 0是 1否',
  `characteristic` tinyint(1) unsigned DEFAULT '1' COMMENT '个人中心 0 是 1否',
  `popularity` tinyint(1) unsigned DEFAULT '1' COMMENT '人气 0是 1否',
  `relation` tinyint(1) unsigned DEFAULT '1' COMMENT '关联0是 1否',
  `activity_id` int(10) unsigned DEFAULT '0' COMMENT '活动id',
  `number_of_visits` int(10) unsigned DEFAULT '0' COMMENT '访问数量',
  `start_time` int(10) unsigned DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) unsigned DEFAULT '0' COMMENT '结束时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='商品表';

-- ----------------------------
-- Records of th_goods
-- ----------------------------
INSERT INTO `th_goods` VALUES ('4', '1', '232323', '[\"\\/uploads\\/member\\/20181218\\\\9b0cfbea07a2104cd4451bd29f90be16.jpg\",\"\\/uploads\\/member\\/20181218\\\\bb79e27bdafdab56d21871a7286ae063.png\",\"\\/uploads\\/member\\/20181218\\\\5f990b51bad337ea4bafc81de2980a2a.jpg\",\"\\/uploads\\/member\\/20181218\\\\056626c9f4f4b45744486f7a8419544d.jpg\"]', '500.00', '482.00', '23232', '[\"12\",\"2\",\"34\",\"6\"]', '[\"23\"]', '50', '0', '0', '1545132853', '1545526491', '2', '[\"{\\\"name\\\":\\\"\\\\u6d88\\\\u606f_\\\\u770b\\\\u56fe\\\\u738b.jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php855E.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"36.9kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u8bed\\\\u97f3\\\\u6587\\\\u4ef6\\\\u540e\\\\u53f0\\\\u4e0a\\\\u4f20\\\\u63a8\\\\u9001\\\\u7ba1\\\\u7406\\\\u610f\\\\u89c1.png\\\",\\\"type\\\":\\\"image\\\\\\/png\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\phpCC8.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"191.8kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u6211\\\\u4e5f\\\\u6765\\\\u4e92\\\\u52a8_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php4F64.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"77.2kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u6211\\\\u4e5f\\\\u6765\\\\u8bc4\\\\u4ef7_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php4F65.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"79.3kb\\\"}\"]', '0', '0', '1', '0', '1', '0', '0', '0', '0', '0');
INSERT INTO `th_goods` VALUES ('5', '0', '123123', '[\"\\/uploads\\/member\\/20181218\\\\d86fcfc04473c07ff78d6652b24a75b5.jpg\"]', '500.00', '100.00', '123123', '[\"33\"]', '[\"66\"]', '50', '1', '0', '1545136868', '1545551514', '9', '[\"{\\\"name\\\":\\\"\\\\u65e0\\\\u7f51\\\\u7edc_\\\\u770b\\\\u56fe\\\\u738b.jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php6C98.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"28.9kb\\\"}\"]', '6', '12', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `th_goods` VALUES ('6', '0', '123213213', '[\"\\/uploads\\/member\\/20181218\\\\4f709f72ed2f9194cf95d33395d8ad24.png\"]', '5500.00', '2200.00', '333', '[\"1\"]', '[\"2\"]', '50', '1', '0', '1545136892', '1545551520', '4', '[\"{\\\"name\\\":\\\"\\\\u8bed\\\\u97f3\\\\u6587\\\\u4ef6\\\\u540e\\\\u53f0\\\\u4e0a\\\\u4f20\\\\u63a8\\\\u9001\\\\u7ba1\\\\u7406\\\\u610f\\\\u89c1.png\\\",\\\"type\\\":\\\"image\\\\\\/png\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php704E.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"191.8kb\\\"}\"]', '5', '20', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `th_goods` VALUES ('7', '0', '232323', '[\"\\/uploads\\/member\\/20181218\\\\9b0cfbea07a2104cd4451bd29f90be16.jpg\",\"\\/uploads\\/member\\/20181218\\\\bb79e27bdafdab56d21871a7286ae063.png\",\"\\/uploads\\/member\\/20181218\\\\5f990b51bad337ea4bafc81de2980a2a.jpg\",\"\\/uploads\\/member\\/20181218\\\\056626c9f4f4b45744486f7a8419544d.jpg\"]', '500.00', '482.00', '23232', '[\"12\",\"2\",\"34\",\"6\"]', '[\"23\"]', '50', '1', '1', '1545136892', '1545136892', '2', '[\"{\\\"name\\\":\\\"\\\\u6d88\\\\u606f_\\\\u770b\\\\u56fe\\\\u738b.jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php855E.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"36.9kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u8bed\\\\u97f3\\\\u6587\\\\u4ef6\\\\u540e\\\\u53f0\\\\u4e0a\\\\u4f20\\\\u63a8\\\\u9001\\\\u7ba1\\\\u7406\\\\u610f\\\\u89c1.png\\\",\\\"type\\\":\\\"image\\\\\\/png\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\phpCC8.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"191.8kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u6211\\\\u4e5f\\\\u6765\\\\u4e92\\\\u52a8_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php4F64.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"77.2kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u6211\\\\u4e5f\\\\u6765\\\\u8bc4\\\\u4ef7_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php4F65.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"79.3kb\\\"}\"]', '4', '1', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `th_goods` VALUES ('9', '0', '232323', '[\"\\/uploads\\/member\\/20181218\\\\9b0cfbea07a2104cd4451bd29f90be16.jpg\",\"\\/uploads\\/member\\/20181218\\\\bb79e27bdafdab56d21871a7286ae063.png\",\"\\/uploads\\/member\\/20181218\\\\5f990b51bad337ea4bafc81de2980a2a.jpg\",\"\\/uploads\\/member\\/20181218\\\\056626c9f4f4b45744486f7a8419544d.jpg\"]', '500.00', '482.00', '23232', '[\"12\",\"2\",\"34\",\"6\"]', '[\"23\"]', '50', '1', '1', '1545137244', '1545137244', '9', '[\"{\\\"name\\\":\\\"\\\\u6d88\\\\u606f_\\\\u770b\\\\u56fe\\\\u738b.jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php855E.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"36.9kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u8bed\\\\u97f3\\\\u6587\\\\u4ef6\\\\u540e\\\\u53f0\\\\u4e0a\\\\u4f20\\\\u63a8\\\\u9001\\\\u7ba1\\\\u7406\\\\u610f\\\\u89c1.png\\\",\\\"type\\\":\\\"image\\\\\\/png\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\phpCC8.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"191.8kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u6211\\\\u4e5f\\\\u6765\\\\u4e92\\\\u52a8_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php4F64.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"77.2kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u6211\\\\u4e5f\\\\u6765\\\\u8bc4\\\\u4ef7_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php4F65.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"79.3kb\\\"}\"]', '4', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `th_goods` VALUES ('10', '1', '5555', '[\"\\/uploads\\/member\\/20190105\\\\06561ca8295733b8e0a0c3d3e877dd5f.jpg\",\"\\/uploads\\/member\\/20190105\\\\06561ca8295733b8e0a0c3d3e877dd5f.jpg\",\"\\/uploads\\/member\\/20190106\\\\c03e3b8683d7ad1183a2f49d2bc2438a.jpg\"]', '100.00', '40.00', '<p>12</p><p>3434</p><p>35353</p><p><img src=\"http://www.shop.com/uploads/member/20190106\\90c214b4aed2c9e6a10ad9ace1344abf.jpg\" alt=\"\"><br></p>', '[\"12\"]', '[\"12\"]', '10', '0', '0', '1546689344', '1546761448', '18', '[\"{\\\"name\\\":\\\"\\\\u91cd\\\\u7f6e\\\\u5bc6\\\\u7801_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php9E81.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"44.4kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u76f4\\\\u64ad\\\\u8bfe_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php9E80.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"75.5kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u8d44\\\\u8baf_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php31A3.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"180.6kb\\\"}\"]', '0', '0', '0', '1', '1', '1', '1', '0', '1546300800', '1547683200');
INSERT INTO `th_goods` VALUES ('11', '1', '测试商品', '[\"\\/uploads\\/member\\/20190106\\\\2009302af428b00833e2a80ece67fead.jpg\",\"\\/uploads\\/member\\/20190106\\\\d487fcaa8d8c8496c22e2ba4305cd6e3.jpg\"]', '200.00', '180.00', '<p>1好使</p><p>2好玩</p><p>3好用</p><p><img src=\"http://www.shop.com/uploads/member/20190106\\0cc819b8562dc5b397f79223e5893b2e.png\" alt=\"\"><br></p>', '[\"123\"]', '[\"123\"]', '500', '0', '0', '1546761674', '1546773332', '13', '[\"{\\\"name\\\":\\\"\\\\u9009\\\\u4fee\\\\u8bfe\\\\u7a0b\\\\u8be6\\\\u60c5\\\\u9875_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\phpECAD.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"60.8kb\\\"}\",\"{\\\"name\\\":\\\"\\\\u8d44\\\\u8baf_\\\\u770b\\\\u56fe\\\\u738b(1).jpg\\\",\\\"type\\\":\\\"image\\\\\\/jpeg\\\",\\\"tmp_name\\\":\\\"D:\\\\\\\\wamp\\\\\\\\tmp\\\\\\\\php5747.tmp\\\",\\\"error\\\":0,\\\"size\\\":\\\"180.6kb\\\"}\"]', '0', '0', '0', '0', '1', '1', '1', '4', '1546704000', '1548172800');
