/*
 Navicat Premium Dump SQL

 Source Server         : Localhost
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : access_logs

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 08/11/2024 23:51:23
*/
USE access_logs;
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
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of affiliation
-- ----------------------------
INSERT INTO `affiliation` VALUES (1, 'Administrator');
INSERT INTO `affiliation` VALUES (16, 'Writer');
INSERT INTO `affiliation` VALUES (17, 'Editor');

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `date` datetime NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `writer_id` int NULL DEFAULT NULL,
  `editor_id` int NULL DEFAULT NULL,
  `company_id` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of article
-- ----------------------------

-- ----------------------------
-- Table structure for company
-- ----------------------------
DROP TABLE IF EXISTS `company`;
CREATE TABLE `company`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `company_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of company
-- ----------------------------
--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `logo`, `company_name`, `status`) VALUES
(1, 'https://th.bing.com/th/id/OIP.nqIQ9nh7e6qvrHQIfqFBYwHaCl?rs=1&pid=ImgDetMain', 'Example Company', 0);
-- ----------------------------
-- Table structure for directory
-- ----------------------------
DROP TABLE IF EXISTS `directory`;
CREATE TABLE `directory`  (
  `id` int NOT NULL AUTO_INCREMENT,
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
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of directory
-- ----------------------------
INSERT INTO `directory` VALUES (2, 'Interviewer', 'Examinee', 'Developer', 'Mariveles Bataan', 'IED', 1, 'Male', '1995-11-23 19:14:54', '2024-10-15 19:14:51');
INSERT INTO `directory` VALUES (18, 'Archintel', 'Executive Mosaic', 'Writer', 'None at the least', 'AEW', 16, 'Other', '2024-11-08 00:00:00', '2024-11-08 23:00:11');
INSERT INTO `directory` VALUES (19, 'Archintel', 'Executive Mosaic', 'Editor', 'None at the least', 'AEE', 17, 'Other', '2024-11-01 00:00:00', '2024-11-08 23:01:16');

-- ----------------------------
-- Table structure for permission
-- ----------------------------
DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `permission_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `permission_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

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
INSERT INTO `permission` VALUES (11, 'view_companies', 'View Companies');
INSERT INTO `permission` VALUES (12, 'add_companies', 'Add Companies');
INSERT INTO `permission` VALUES (13, 'edit_companies', 'Edit Companies');
INSERT INTO `permission` VALUES (14, 'view_articles', 'View Articles');
INSERT INTO `permission` VALUES (15, 'add_articles', 'Add Articles');
INSERT INTO `permission` VALUES (16, 'edit_articles', 'Edit Articles');
INSERT INTO `permission` VALUES (17, 'edit_published_articles', 'Edit Published Articles');

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
INSERT INTO `role_permissions` VALUES (3, 14);
INSERT INTO `role_permissions` VALUES (3, 15);
INSERT INTO `role_permissions` VALUES (3, 16);
INSERT INTO `role_permissions` VALUES (2, 2);
INSERT INTO `role_permissions` VALUES (2, 3);
INSERT INTO `role_permissions` VALUES (2, 4);
INSERT INTO `role_permissions` VALUES (2, 5);
INSERT INTO `role_permissions` VALUES (2, 6);
INSERT INTO `role_permissions` VALUES (2, 7);
INSERT INTO `role_permissions` VALUES (2, 8);
INSERT INTO `role_permissions` VALUES (2, 9);
INSERT INTO `role_permissions` VALUES (2, 10);
INSERT INTO `role_permissions` VALUES (2, 11);
INSERT INTO `role_permissions` VALUES (2, 12);
INSERT INTO `role_permissions` VALUES (2, 13);
INSERT INTO `role_permissions` VALUES (2, 14);
INSERT INTO `role_permissions` VALUES (2, 16);
INSERT INTO `role_permissions` VALUES (2, 17);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'SuperAdmin');
INSERT INTO `roles` VALUES (2, 'Editor');
INSERT INTO `roles` VALUES (3, 'Writer');

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
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 2, 'administrator', '$2y$10$Wh6F7NMgr2vVZkNj6QLmEeH8KZhccktgP/IYqkdT.nqmLBBW8P.DW', 1, '0', 1, '2024-10-24 16:17:02', '2024-11-08 23:45:15');
INSERT INTO `users` VALUES (9, 18, 'writer', '$2y$10$YH2mdy56lJ9/PPciuxEbjOCwSWSvWgTUiAuONucSuOZcVfWBwNs5e', 3, '0', 1, '2024-11-08 23:00:11', NULL);
INSERT INTO `users` VALUES (10, 19, 'editor', '$2y$10$s5XEcPy56Lih3E2KM0DXV.Zp3aSIGkIVqhSRBv7VPEBHHVfxmzfE6', 2, '0', 1, '2024-11-08 23:01:16', NULL);

SET FOREIGN_KEY_CHECKS = 1;
