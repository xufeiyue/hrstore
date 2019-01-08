/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50711
Source Host           : localhost:3306
Source Database       : ceshi

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2019-01-08 22:07:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `th_activity_log`
-- ----------------------------
DROP TABLE IF EXISTS `th_activity_log`;
CREATE TABLE `th_activity_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `activity_id` int(11) unsigned DEFAULT '0' COMMENT '活动id',
  `userId` int(10) unsigned DEFAULT '0' COMMENT '用户id',
  `createTime` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `updateTime` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='参加活动记录';

-- ----------------------------
-- Records of th_activity_log
-- ----------------------------
INSERT INTO `th_activity_log` VALUES ('1', '1', '1', '1546956150', '1546956150', '0');

-- ----------------------------
-- Table structure for `th_new_discovery`
-- ----------------------------
DROP TABLE IF EXISTS `th_new_discovery`;
CREATE TABLE `th_new_discovery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `src` varchar(255) DEFAULT '' COMMENT '图片',
  `url` varchar(255) DEFAULT '' COMMENT '跳转路径',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常1删除',
  `store_id` tinyint(1) unsigned DEFAULT '0' COMMENT '店铺id',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='新发现';

-- ----------------------------
-- Records of th_new_discovery
-- ----------------------------
INSERT INTO `th_new_discovery` VALUES ('1', '/uploads/member/20190108\\05dba2a78d3ce633db1703453013436e.jpg', 'http://www.shop.com/home/activity/lq_huodong/id/1', '0', '1', '1546953161', '1546953161');
INSERT INTO `th_new_discovery` VALUES ('2', '/uploads/member/20190108\\abebebce53d57d9c5ab7427804918c3d.jpg', '123123123', '1', '1', '1546953237', '1546953237');
INSERT INTO `th_new_discovery` VALUES ('3', '/uploads/member/20190108\\0d5d8e8b9ad2bd93b59750421428ec45.jpg', '123123123', '1', '1', '1546953249', '1546953249');
