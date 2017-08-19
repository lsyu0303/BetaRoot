/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 50719
 Source Host           : localhost:3306
 Source Schema         : betaroot

 Target Server Type    : MySQL
 Target Server Version : 50719
 File Encoding         : 65001

 Date: 19/08/2017 13:59:03
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for clover_category
-- ----------------------------
DROP TABLE IF EXISTS `clover_category`;
CREATE TABLE `clover_category`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标识',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `parentid` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级',
  `modelid` smallint(5) UNSIGNED NOT NULL DEFAULT 1 COMMENT '模型',
  `type` tinyint(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '类型',
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片',
  `seotitle` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '搜索',
  `keywords` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '关键词',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '内容',
  `template_index` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '模块模板',
  `template_list` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '列表模板',
  `template_show` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '输出模板',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态',
  `sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of clover_category
-- ----------------------------
INSERT INTO `clover_category` VALUES (1, 'china', '中国中国', 0, 2, 1, '20170817\\c5e1993e0dd56bb9a6a20ee65bbfb255.png', '', '', '', '', 'image_index.html', 'image_list.html', 'image_show.html', 1, 1);
INSERT INTO `clover_category` VALUES (2, 'australia', '澳大利亚', 0, 3, 1, '20170817\\3a5e17c7766d7dc9043d726f0274b55d.png', '', '', '', '', 'image_index.html', 'image_list.html', 'image_show.htm', 1, 2);
INSERT INTO `clover_category` VALUES (3, 'guangxi', '广西广西', 1, 3, 1, '', '', '', '', '', 'down_index.html', 'down_list.html', 'down_show.htm', 1, 1);
INSERT INTO `clover_category` VALUES (4, 'zhejiang', '浙江浙江', 1, 1, 1, '', '', '', '', '', 'image_index.html', 'image_list.html', 'image_show.htm', 1, 2);
INSERT INTO `clover_category` VALUES (5, 'qinzhou', '钦州市', 3, 1, 1, '', '', '', '', '', 'video_index.html', 'video_list.html', 'video_show.htm', 1, 0);
INSERT INTO `clover_category` VALUES (6, 'beihai', '北海市', 3, 1, 1, '', '', '', '', '', 'image_index.html', 'image_list.html', 'image_show.htm', 1, 0);
INSERT INTO `clover_category` VALUES (7, 'pubei', '浦北县', 5, 2, 1, '', '', '', '', '', 'image_index.html', 'image_list.html', 'image_show.htm', 1, 0);
INSERT INTO `clover_category` VALUES (8, 'naning', '南宁市', 3, 2, 1, '', '', '', '', '', 'image_index.html', 'image_list.html', 'image_show.htm', 1, 0);
INSERT INTO `clover_category` VALUES (9, 'guangdong', '广东广东', 1, 2, 1, '', '', '', '', '', 'image_index.html', 'image_list.html', 'image_show.html', 1, 0);

-- ----------------------------
-- Table structure for clover_down
-- ----------------------------
DROP TABLE IF EXISTS `clover_down`;
CREATE TABLE `clover_down`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for clover_image
-- ----------------------------
DROP TABLE IF EXISTS `clover_image`;
CREATE TABLE `clover_image`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for clover_model
-- ----------------------------
DROP TABLE IF EXISTS `clover_model`;
CREATE TABLE `clover_model`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '排序',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标识',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `tablename` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '表名',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `template_index` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '模型模板',
  `template_list` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '列表模板',
  `template_show` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '输出模板',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态',
  `sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of clover_model
-- ----------------------------
INSERT INTO `clover_model` VALUES (1, 'video', '视频模型', 'video', '2', 'video_index.html', 'video_list.html', 'video_show.html', 1, 0);
INSERT INTO `clover_model` VALUES (2, 'image', '图片模型', 'image', '', 'image_index.html', 'image_list.html', 'image_show.html', 1, 0);
INSERT INTO `clover_model` VALUES (3, 'down', '下载模型', 'down', '', 'down_index.html', 'down_list.html', 'down_show.html', 1, 0);

-- ----------------------------
-- Table structure for clover_system
-- ----------------------------
DROP TABLE IF EXISTS `clover_system`;
CREATE TABLE `clover_system`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标识',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `typeid` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '类型',
  `groupid` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '默认值',
  `optional` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '可选值',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态',
  `sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of clover_system
-- ----------------------------
INSERT INTO `clover_system` VALUES (1, 'CON_SYSTEM_NAME', '网站名称', '', 1, 1, '轻风之语', '', 1, 1);
INSERT INTO `clover_system` VALUES (2, 'CFG_SYSTEM_URL', '网站地址', '', 1, 1, '2', '', 1, 0);
INSERT INTO `clover_system` VALUES (3, 'CFG_SYSTEM_TITLE', '网站标题', '', 1, 1, '', '', 1, 2);
INSERT INTO `clover_system` VALUES (4, 'CFG_SYSTEM_ICP', '备案信息', '', 2, 2, '桂ICP备15000934号', '', 1, 3);
INSERT INTO `clover_system` VALUES (5, 'CFG_SYSTEM_POWERBY', '版权信息', '', 1, 2, '', '', 1, 5);
INSERT INTO `clover_system` VALUES (6, 'CFG_SYSTEM_STATS', '网站统计', '', 1, 2, '', '', 1, 0);
INSERT INTO `clover_system` VALUES (7, 'CFG_SYSTEM_STATUS', '网站状态', '', 1, 3, '', '', 1, 0);
INSERT INTO `clover_system` VALUES (8, 'CFG_SYSTEM_KEYWORDS', '关 键 词', '', 1, 3, '', '', 1, 0);
INSERT INTO `clover_system` VALUES (9, 'CFG_SYSTEM_DESCRIPTION', '网站描述', '', 1, 3, '', '', 1, 2);
INSERT INTO `clover_system` VALUES (10, 'CFG_SYSTEM_THEMESTYLE', '模板风格', '', 1, 4, '', '', 1, 0);
INSERT INTO `clover_system` VALUES (11, 'Text', '字符值型', '', 1, 1, '字符值型', '', 1, 0);
INSERT INTO `clover_system` VALUES (12, 'Textarea', '文本域型', '', 2, 1, '文本域型', '', 1, 9);
INSERT INTO `clover_system` VALUES (13, 'Select', '下拉列表', '', 3, 1, '维护', '开启|关闭|维护', 1, 0);
INSERT INTO `clover_system` VALUES (14, 'Radio', '单项选择', '', 4, 1, '男', '男|女|未知', 1, 0);
INSERT INTO `clover_system` VALUES (15, 'Checkbox', '多项选择', '', 5, 1, '男|女|未知', '男|女|未知', 1, 0);

-- ----------------------------
-- Table structure for clover_tab
-- ----------------------------
DROP TABLE IF EXISTS `clover_tab`;
CREATE TABLE `clover_tab`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `group` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分组',
  `value` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '默认值',
  `parentid` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态',
  `sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`id`, `value`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of clover_tab
-- ----------------------------
INSERT INTO `clover_tab` VALUES (1, '网站设置', 'configgroup', 1, 0, 0, 0);
INSERT INTO `clover_tab` VALUES (2, '核心设置', 'configgroup', 2, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (3, '附件设置', 'configgroup', 3, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (4, '邮箱设置', 'configgroup', 4, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (5, '会员设置', 'configgroup', 5, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (6, '其他设置', 'configgroup', 6, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (7, '单行文本', 'configtype', 1, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (8, '文本域型', 'configtype', 2, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (9, '下拉列表', 'configtype', 3, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (10, '单项选择', 'configtype', 4, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (11, '多项先择', 'configtype', 5, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (12, '文件附件', 'configtype', 6, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (13, '1', 'configtype', 7, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (14, '2', 'configtype', 8, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (15, '3', 'configtype', 9, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (16, '4', 'configtype', 10, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (17, '5', 'configtype', 11, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (18, '6', 'configtype', 12, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (19, '列表模块', 'categorytype', 1, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (20, '封面模块', 'categorytype', 2, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (21, '外部链接', 'categorytype', 0, 0, 1, 0);
INSERT INTO `clover_tab` VALUES (22, '外部链良', 'categorytype', 4, 0, 1, 0);

-- ----------------------------
-- Table structure for clover_tabs
-- ----------------------------
DROP TABLE IF EXISTS `clover_tabs`;
CREATE TABLE `clover_tabs`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标识',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of clover_tabs
-- ----------------------------
INSERT INTO `clover_tabs` VALUES (1, 'configtype', '配置类型', 1);
INSERT INTO `clover_tabs` VALUES (2, 'configgroup', '配置分组', 1);
INSERT INTO `clover_tabs` VALUES (3, 'categorytype', '模块类型', 1);

-- ----------------------------
-- Table structure for clover_video
-- ----------------------------
DROP TABLE IF EXISTS `clover_video`;
CREATE TABLE `clover_video`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
