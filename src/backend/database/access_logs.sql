/*
 Navicat Premium Dump SQL

 Source Server         : Local Server
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : access_logs

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 24/10/2024 16:40:44
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for access_logs
-- ----------------------------
DROP TABLE IF EXISTS `access_logs`;
CREATE TABLE `access_logs`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `directory_id` int NULL DEFAULT NULL,
  `access_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `access_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `directory_access`(`directory_id` ASC) USING BTREE,
  CONSTRAINT `directory_access` FOREIGN KEY (`directory_id`) REFERENCES `directory` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of access_logs
-- ----------------------------

-- ----------------------------
-- Table structure for affiliation
-- ----------------------------
DROP TABLE IF EXISTS `affiliation`;
CREATE TABLE `affiliation`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `affiliation_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of affiliation
-- ----------------------------
INSERT INTO `affiliation` VALUES (1, 'Developer');
INSERT INTO `affiliation` VALUES (16, 'System Developer');

-- ----------------------------
-- Table structure for directory
-- ----------------------------
DROP TABLE IF EXISTS `directory`;
CREATE TABLE `directory`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `rfid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `fname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `mname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `lname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `nameinit` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `affiliation` int NULL DEFAULT NULL,
  `gender` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `birthdate` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `directory_affiliation`(`affiliation` ASC) USING BTREE,
  CONSTRAINT `directory_affiliation` FOREIGN KEY (`affiliation`) REFERENCES `affiliation` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of directory
-- ----------------------------
INSERT INTO `directory` VALUES (2, 'asdasdada22', 'Clarence', 'Advincula', 'Baluyot', 'Mariveles Bataan', 'CAB', 1, 'Male', '1995-11-23 19:14:54', '2024-10-15 19:14:51');

-- ----------------------------
-- Table structure for permission
-- ----------------------------
DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `permission_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `permission_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of permission
-- ----------------------------
INSERT INTO `permission` VALUES (1, 'administrator', 'Administrator');
INSERT INTO `permission` VALUES (2, 'add_affiliation', 'Add Affiliations');
INSERT INTO `permission` VALUES (3, 'edit_affiliation', 'Edit Affiliations');
INSERT INTO `permission` VALUES (4, 'view_affiliation', 'View Affiliations');
INSERT INTO `permission` VALUES (5, 'add_role', 'Add Roles');
INSERT INTO `permission` VALUES (6, 'edit_role', 'Edit Roles');
INSERT INTO `permission` VALUES (7, 'view_role', 'View Roles');
INSERT INTO `permission` VALUES (8, 'add_users', 'Add Users');
INSERT INTO `permission` VALUES (9, 'edit_users', 'Edit Users');
INSERT INTO `permission` VALUES (10, 'view_users', 'View Users');

-- ----------------------------
-- Table structure for role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions`  (
  `role_id` int NULL DEFAULT NULL,
  `permission_id` int NULL DEFAULT NULL,
  INDEX `role_permission`(`role_id` ASC) USING BTREE,
  INDEX `permission_role`(`permission_id` ASC) USING BTREE,
  CONSTRAINT `permission_role` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `role_permission` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_permissions
-- ----------------------------
INSERT INTO `role_permissions` VALUES (1, 1);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'SuperAdmin');
INSERT INTO `roles` VALUES (2, 'Staff');

-- ----------------------------
-- Table structure for user_permissions
-- ----------------------------
DROP TABLE IF EXISTS `user_permissions`;
CREATE TABLE `user_permissions`  (
  `user_id` int NOT NULL,
  `permission_id` int NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_permissions
-- ----------------------------
INSERT INTO `user_permissions` VALUES (1, 2);
INSERT INTO `user_permissions` VALUES (1, 3);
INSERT INTO `user_permissions` VALUES (1, 4);
INSERT INTO `user_permissions` VALUES (1, 5);
INSERT INTO `user_permissions` VALUES (1, 6);
INSERT INTO `user_permissions` VALUES (1, 7);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `directory_id` int NULL DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `role` int NULL DEFAULT NULL,
  `status` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_directory`(`directory_id` ASC) USING BTREE,
  INDEX `user_role`(`role` ASC) USING BTREE,
  CONSTRAINT `user_directory` FOREIGN KEY (`directory_id`) REFERENCES `directory` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `user_role` FOREIGN KEY (`role`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 2, 'clarence', '$2y$10$lqwwcSRuNfl2AjJABlFqm.p1tLsQAH.HqsoQzzeiG9lcbqq/z0Jg6', 1, '0', 1, '2024-10-24 16:17:02', '2024-10-24 16:19:26');

SET FOREIGN_KEY_CHECKS = 1;
