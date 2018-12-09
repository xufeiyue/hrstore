/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50711
Source Host           : localhost:3306
Source Database       : ceshi

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2018-12-09 21:47:48
*/

SET FOREIGN_KEY_CHECKS=0;

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='广告表';

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='广告类型表';

-- ----------------------------
-- Records of th_advertisement_type
-- ----------------------------

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='系统基本信息';

-- ----------------------------
-- Records of th_information
-- ----------------------------
