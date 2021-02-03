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

 Date: 03/02/2021 10:02:45
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for c_offline_message
-- ----------------------------
DROP TABLE IF EXISTS `c_offline_message`;
CREATE TABLE `c_offline_message`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `data` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0未发送 1已发送',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3474 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '离线消息表' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
