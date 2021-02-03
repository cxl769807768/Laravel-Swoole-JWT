/*
 Navicat Premium Data Transfer

 Source Server         : 192.168.33.13
 Source Server Type    : MySQL
 Source Server Version : 80021
 Source Host           : 192.168.33.13:3306
 Source Schema         : laravel_admin

 Target Server Type    : MySQL
 Target Server Version : 80021
 File Encoding         : 65001

 Date: 03/02/2021 10:02:52
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for c_system_message
-- ----------------------------
DROP TABLE IF EXISTS `c_system_message`;
CREATE TABLE `c_system_message`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT '接收用户id',
  `from_id` int NOT NULL COMMENT '来源相关用户id',
  `group_id` int NOT NULL DEFAULT 0,
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '添加好友附言',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0好友请求 1请求结果通知',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0未处理 1同意 2拒绝',
  `read` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0未读 1已读，用来显示消息盒子数量',
  `time` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 61 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '系统消息表' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
