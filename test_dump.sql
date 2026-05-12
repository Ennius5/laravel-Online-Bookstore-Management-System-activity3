-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: pageturner_bookstore
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `api_request_logs`
--

DROP TABLE IF EXISTS `api_request_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api_request_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `endpoint` varchar(255) NOT NULL,
  `status_code` int(11) NOT NULL DEFAULT 200,
  `rate_limited` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `api_request_logs_created_at_index` (`created_at`),
  KEY `api_request_logs_endpoint_index` (`endpoint`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `api_request_logs`
--

LOCK TABLES `api_request_logs` WRITE;
/*!40000 ALTER TABLE `api_request_logs` DISABLE KEYS */;
INSERT INTO `api_request_logs` VALUES (1,NULL,'/',200,0,'2026-04-28 08:35:58','2026-04-28 08:35:58'),(2,NULL,'/',200,0,'2026-04-28 08:36:06','2026-04-28 08:36:06'),(3,NULL,'/',200,0,'2026-04-28 08:36:07','2026-04-28 08:36:07'),(4,NULL,'/',200,0,'2026-04-28 08:36:08','2026-04-28 08:36:08'),(5,NULL,'/',200,0,'2026-04-28 08:36:09','2026-04-28 08:36:09'),(6,NULL,'/',200,0,'2026-04-28 08:36:10','2026-04-28 08:36:10'),(7,NULL,'/',200,0,'2026-04-28 08:36:11','2026-04-28 08:36:11'),(8,NULL,'/',200,0,'2026-04-28 08:36:11','2026-04-28 08:36:11'),(9,NULL,'/',200,0,'2026-04-28 08:36:12','2026-04-28 08:36:12'),(10,NULL,'/',200,0,'2026-04-28 08:36:13','2026-04-28 08:36:13'),(11,NULL,'/',200,0,'2026-04-28 08:36:14','2026-04-28 08:36:14'),(12,NULL,'/',200,0,'2026-04-28 08:36:15','2026-04-28 08:36:15'),(13,NULL,'/',200,0,'2026-04-28 08:36:16','2026-04-28 08:36:16'),(14,NULL,'/',200,0,'2026-04-28 08:36:17','2026-04-28 08:36:17'),(15,NULL,'/',200,0,'2026-04-28 08:36:17','2026-04-28 08:36:17'),(16,NULL,'/',200,0,'2026-04-28 08:36:18','2026-04-28 08:36:18'),(17,NULL,'/',200,0,'2026-04-28 08:36:19','2026-04-28 08:36:19'),(18,NULL,'/',200,0,'2026-04-28 08:36:20','2026-04-28 08:36:20'),(19,NULL,'/',200,0,'2026-04-28 08:36:21','2026-04-28 08:36:21'),(20,NULL,'/',200,0,'2026-04-28 08:36:22','2026-04-28 08:36:22'),(21,NULL,'/',200,0,'2026-04-28 08:36:23','2026-04-28 08:36:23'),(22,NULL,'/',200,0,'2026-04-28 08:36:24','2026-04-28 08:36:24'),(23,NULL,'/',200,0,'2026-04-28 08:36:24','2026-04-28 08:36:24'),(24,NULL,'/',200,0,'2026-04-28 08:36:25','2026-04-28 08:36:25'),(25,NULL,'/',200,0,'2026-04-28 08:36:26','2026-04-28 08:36:26'),(26,NULL,'/',200,0,'2026-04-28 08:36:27','2026-04-28 08:36:27'),(27,NULL,'/',200,0,'2026-04-28 08:36:27','2026-04-28 08:36:27'),(28,NULL,'/',200,0,'2026-04-28 08:36:28','2026-04-28 08:36:28'),(29,NULL,'/',200,0,'2026-04-28 08:36:30','2026-04-28 08:36:30'),(30,NULL,'/',200,0,'2026-04-28 08:36:30','2026-04-28 08:36:30'),(31,NULL,'/',429,1,'2026-04-28 08:36:32','2026-04-28 08:36:32'),(32,NULL,'/',429,1,'2026-04-28 08:36:33','2026-04-28 08:36:33'),(33,NULL,'/',429,1,'2026-04-28 08:36:34','2026-04-28 08:36:34'),(34,NULL,'/',429,1,'2026-04-28 08:36:34','2026-04-28 08:36:34'),(35,NULL,'/',429,1,'2026-04-28 08:36:35','2026-04-28 08:36:35'),(36,NULL,'/',429,1,'2026-04-28 08:36:35','2026-04-28 08:36:35'),(37,NULL,'/',429,1,'2026-04-28 08:36:36','2026-04-28 08:36:36'),(38,NULL,'/',429,1,'2026-04-28 08:36:36','2026-04-28 08:36:36'),(39,NULL,'/',429,1,'2026-04-28 08:36:37','2026-04-28 08:36:37'),(40,NULL,'/',429,1,'2026-04-28 08:36:37','2026-04-28 08:36:37'),(41,NULL,'/',429,1,'2026-04-28 08:36:38','2026-04-28 08:36:38'),(42,NULL,'/',429,1,'2026-04-28 08:36:38','2026-04-28 08:36:38'),(43,NULL,'/',429,1,'2026-04-28 08:36:39','2026-04-28 08:36:39'),(44,NULL,'/',429,1,'2026-04-28 08:36:39','2026-04-28 08:36:39'),(45,NULL,'/',429,1,'2026-04-28 08:36:40','2026-04-28 08:36:40'),(46,NULL,'/',429,1,'2026-04-28 08:36:49','2026-04-28 08:36:49'),(47,NULL,'/',200,0,'2026-04-28 08:36:53','2026-04-28 08:36:53'),(48,NULL,'login',200,0,'2026-04-28 08:36:57','2026-04-28 08:36:57'),(49,2,'login',302,0,'2026-04-28 08:37:17','2026-04-28 08:37:17'),(50,2,'dashboard',200,0,'2026-04-28 08:37:21','2026-04-28 08:37:21'),(51,2,'admin/backup',200,0,'2026-04-28 08:37:48','2026-04-28 08:37:48'),(52,2,'admin/backup/trigger',302,0,'2026-04-28 08:38:13','2026-04-28 08:38:13'),(53,2,'admin/backup',200,0,'2026-04-28 08:38:14','2026-04-28 08:38:14'),(54,2,'/',200,0,'2026-04-28 08:41:55','2026-04-28 08:41:55'),(55,2,'dashboard',200,0,'2026-04-28 08:41:58','2026-04-28 08:41:58'),(56,2,'admin/backup',200,0,'2026-04-28 08:48:41','2026-04-28 08:48:41'),(57,2,'admin/backup/trigger',500,0,'2026-04-28 08:48:55','2026-04-28 08:48:55'),(58,2,'admin/backup/trigger',302,0,'2026-04-28 08:52:31','2026-04-28 08:52:31'),(59,2,'admin/backup',200,0,'2026-04-28 08:52:32','2026-04-28 08:52:32'),(60,2,'admin/backup/trigger',302,0,'2026-04-28 08:52:42','2026-04-28 08:52:42'),(61,2,'admin/backup',200,0,'2026-04-28 08:52:44','2026-04-28 08:52:44'),(62,2,'admin/backup/trigger',302,0,'2026-04-28 08:54:45','2026-04-28 08:54:45'),(63,2,'admin/backup',200,0,'2026-04-28 08:54:46','2026-04-28 08:54:46'),(64,2,'/',200,0,'2026-04-28 08:55:10','2026-04-28 08:55:10'),(65,2,'dashboard',200,0,'2026-04-28 09:04:35','2026-04-28 09:04:35'),(66,2,'admin/audit',200,0,'2026-04-28 09:04:58','2026-04-28 09:04:58'),(67,2,'admin/audit/1',200,0,'2026-04-28 09:05:02','2026-04-28 09:05:02'),(68,NULL,'/',200,0,'2026-04-28 13:09:00','2026-04-28 13:09:00'),(69,NULL,'login',200,0,'2026-04-28 13:09:09','2026-04-28 13:09:09'),(70,2,'login',302,0,'2026-04-28 13:09:16','2026-04-28 13:09:16'),(71,2,'dashboard',200,0,'2026-04-28 13:09:20','2026-04-28 13:09:20'),(72,2,'register',302,0,'2026-04-28 13:10:09','2026-04-28 13:10:09'),(73,2,'dashboard',200,0,'2026-04-28 13:10:11','2026-04-28 13:10:11'),(74,NULL,'logout',302,0,'2026-04-28 13:18:55','2026-04-28 13:18:55'),(75,NULL,'/',200,0,'2026-04-28 13:18:58','2026-04-28 13:18:58'),(76,NULL,'register',200,0,'2026-04-28 13:19:04','2026-04-28 13:19:04'),(77,13,'register',302,0,'2026-04-28 13:20:05','2026-04-28 13:20:05'),(78,13,'dashboard',302,0,'2026-04-28 13:20:07','2026-04-28 13:20:07'),(79,13,'verify-email',200,0,'2026-04-28 13:20:08','2026-04-28 13:20:08'),(80,13,'verify-email/13/a6bfb017c98ebdcb2dcfd87bc7ff92bb87c1eee5',302,0,'2026-04-28 13:20:21','2026-04-28 13:20:21'),(81,13,'dashboard',200,0,'2026-04-28 13:20:22','2026-04-28 13:20:22'),(82,13,'books/exportCatalogue',404,0,'2026-04-28 13:20:29','2026-04-28 13:20:29'),(83,13,'verify-email/13/a6bfb017c98ebdcb2dcfd87bc7ff92bb87c1eee5',302,0,'2026-04-28 13:26:52','2026-04-28 13:26:52'),(84,13,'dashboard',200,0,'2026-04-28 13:26:54','2026-04-28 13:26:54'),(85,13,'dashboard',200,0,'2026-04-28 13:27:03','2026-04-28 13:27:03'),(86,13,'books/exportCatalogue',404,0,'2026-04-28 13:29:00','2026-04-28 13:29:00'),(87,13,'books/exportCatalogue',404,0,'2026-04-28 13:32:06','2026-04-28 13:32:06'),(88,13,'books/exportCatalogue',404,0,'2026-04-28 13:33:18','2026-04-28 13:33:18'),(89,NULL,'logout',302,0,'2026-04-28 13:37:27','2026-04-28 13:37:27'),(90,NULL,'/',200,0,'2026-04-28 13:37:28','2026-04-28 13:37:28'),(91,NULL,'login',200,0,'2026-04-28 13:37:32','2026-04-28 13:37:32'),(92,2,'login',302,0,'2026-04-28 13:37:38','2026-04-28 13:37:38'),(93,2,'dashboard',200,0,'2026-04-28 13:37:40','2026-04-28 13:37:40'),(94,2,'admin/books/import-export',200,0,'2026-04-28 13:37:47','2026-04-28 13:37:47'),(95,2,'admin/books/export',200,0,'2026-04-28 13:38:00','2026-04-28 13:38:00'),(96,NULL,'dashboard',302,0,'2026-04-29 02:58:39','2026-04-29 02:58:39'),(97,NULL,'login',200,0,'2026-04-29 02:58:52','2026-04-29 02:58:52'),(98,NULL,'register',200,0,'2026-04-29 02:59:14','2026-04-29 02:59:14'),(99,NULL,'register',302,0,'2026-04-29 03:02:57','2026-04-29 03:02:57'),(100,NULL,'register',200,0,'2026-04-29 03:02:59','2026-04-29 03:02:59'),(101,NULL,'/',200,0,'2026-04-29 03:03:08','2026-04-29 03:03:08'),(102,NULL,'/',200,0,'2026-05-05 17:57:51','2026-05-05 17:57:51'),(103,NULL,'login',200,0,'2026-05-05 18:05:15','2026-05-05 18:05:15'),(104,2,'login',302,0,'2026-05-05 18:05:23','2026-05-05 18:05:23'),(105,2,'dashboard',200,0,'2026-05-05 18:05:29','2026-05-05 18:05:29'),(106,2,'admin/audit',200,0,'2026-05-05 18:06:00','2026-05-05 18:06:00'),(107,2,'admin/audit/7',200,0,'2026-05-05 18:30:39','2026-05-05 18:30:39'),(108,NULL,'/',200,0,'2026-05-12 00:04:26','2026-05-12 00:04:26'),(109,NULL,'login',200,0,'2026-05-12 00:04:31','2026-05-12 00:04:31'),(110,2,'login',302,0,'2026-05-12 00:04:37','2026-05-12 00:04:37'),(111,2,'dashboard',200,0,'2026-05-12 00:04:40','2026-05-12 00:04:40'),(112,2,'admin/backup',200,0,'2026-05-12 00:04:51','2026-05-12 00:04:51'),(113,2,'admin/backup/trigger',302,0,'2026-05-12 00:05:21','2026-05-12 00:05:21'),(114,2,'admin/backup',200,0,'2026-05-12 00:05:22','2026-05-12 00:05:22');
/*!40000 ALTER TABLE `api_request_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audits`
--

DROP TABLE IF EXISTS `audits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `event` varchar(255) NOT NULL,
  `auditable_type` varchar(255) NOT NULL,
  `auditable_id` bigint(20) unsigned NOT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(1023) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `user` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`user`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audits_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  KEY `audits_user_id_user_type_index` (`user_id`,`user_type`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audits`
--

LOCK TABLES `audits` WRITE;
/*!40000 ALTER TABLE `audits` DISABLE KEYS */;
INSERT INTO `audits` VALUES (1,'App\\Models\\User',2,'login','App\\Models\\User',2,'[]','[]','http://localhost:8000/login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-04-28 08:37:17','2026-04-28 08:37:17'),(2,'App\\Models\\User',2,'login','App\\Models\\User',2,'[]','[]','http://localhost:8000/login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-04-28 13:09:16','2026-04-28 13:09:16'),(3,'App\\Models\\User',2,'logout','App\\Models\\User',2,'[]','[]','http://localhost:8000/logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-04-28 13:18:55','2026-04-28 13:18:55'),(4,'App\\Models\\User',13,'logout','App\\Models\\User',13,'[]','[]','http://localhost:8000/logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-04-28 13:37:26','2026-04-28 13:37:26'),(5,'App\\Models\\User',2,'login','App\\Models\\User',2,'[]','[]','http://localhost:8000/login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-04-28 13:37:38','2026-04-28 13:37:38'),(6,'App\\Models\\User',2,'created','App\\Models\\ExportLog',1,'[]','{\"user_id\":2,\"format\":\"xlsx\",\"filters\":\"{\\\"category_id\\\":null,\\\"min_price\\\":null,\\\"max_price\\\":null,\\\"date_from\\\":null,\\\"date_to\\\":null}\",\"status\":\"Completed\",\"id\":1}','http://localhost:8000/admin/books/export?category_id=&columns%5B0%5D=isbn&columns%5B1%5D=title&columns%5B2%5D=author&columns%5B3%5D=price&columns%5B4%5D=stock_quantity&columns%5B5%5D=category&columns%5B6%5D=description&columns%5B7%5D=created_at&date_from=&date_to=&format=xlsx&max_price=&min_price=','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"id\":2,\"name\":\"Admin User\",\"email\":\"admin@pageturner.com\",\"email_verified_at\":\"2026-04-28T16:33:30.000000Z\",\"role\":\"admin\",\"created_at\":\"2026-04-28T16:33:30.000000Z\",\"updated_at\":\"2026-04-28T16:33:30.000000Z\",\"two_factor_enabled\":0,\"two_factor_code\":null,\"two_factor_expires_at\":null}','2026-04-28 13:37:54','2026-04-28 13:37:54'),(7,'App\\Models\\User',2,'login','App\\Models\\User',2,'[]','[]','http://localhost:8000/login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-05-05 18:05:22','2026-05-05 18:05:22'),(8,'App\\Models\\User',2,'login','App\\Models\\User',2,'[]','[]','http://localhost:8000/login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,NULL,'2026-05-12 00:04:36','2026-05-12 00:04:36');
/*!40000 ALTER TABLE `audits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `books` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `books_isbn_unique` (`isbn`),
  KEY `books_category_id_foreign` (`category_id`),
  CONSTRAINT `books_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
INSERT INTO `books` VALUES (1,1,'Odit optio eaque.','Lavonne Kling IV','9784844818922',42.82,46,'Similique nihil id provident voluptatem tenetur aut quam mollitia. Magni laboriosam sequi error culpa nisi. Itaque natus exercitationem dignissimos et. Expedita ducimus illo excepturi enim explicabo officia omnis.',NULL,'2026-04-28 08:33:33','2026-04-28 08:33:33'),(2,1,'Libero quam.','Ethyl Leffler Sr.','9796013279700',44.09,27,'Ipsa ut ut rerum et voluptatem perferendis eveniet eos. Laboriosam veniam rerum ipsam sit ut possimus aut. Laudantium aliquid ut qui tempora quod.',NULL,'2026-04-28 08:33:33','2026-04-28 08:33:33'),(3,1,'Tempore fugiat quidem.','Ms. Emmanuelle Cruickshank PhD','9783258545721',68.00,81,'Error earum dicta quaerat tempora. Voluptas rerum cumque enim labore nihil vel ut minima. Neque ea harum sequi.',NULL,'2026-04-28 08:33:33','2026-04-28 08:33:33'),(4,1,'Saepe dolor ad.','Rowan Botsford Sr.','9790044996179',34.73,64,'Aliquam aliquam aspernatur velit iure voluptatem nisi. Eos ipsa dolorem incidunt et nam sunt. Nihil mollitia ullam vero esse incidunt tenetur provident.',NULL,'2026-04-28 08:33:33','2026-04-28 08:33:33'),(5,1,'Sint cupiditate aut.','Dalton Glover','9789764972891',91.34,29,'Qui placeat reprehenderit eos repellat nam consequatur corporis. Distinctio aperiam aliquam ad.',NULL,'2026-04-28 08:33:33','2026-04-28 08:33:33'),(6,2,'Nam accusantium.','Dr. Joanie Greenholt Jr.','9787641834898',25.57,32,'Earum nihil doloremque et est eius eius aut asperiores. Eos autem vel tempora sit autem. Quia eius aut id rerum magni. Nisi numquam aliquam incidunt atque.',NULL,'2026-04-28 08:33:34','2026-04-28 08:33:34'),(7,2,'Consequuntur asperiores animi voluptatem.','Milo Brekke Jr.','9781125559833',50.25,14,'Magnam odit enim debitis. Laboriosam aut error itaque qui sapiente dolorum quibusdam. Quibusdam deleniti eligendi eius aspernatur quia libero. Omnis consequuntur asperiores dolores impedit sint magnam.',NULL,'2026-04-28 08:33:34','2026-04-28 08:33:34'),(8,2,'Asperiores quisquam.','Lillian Hintz','9788185241463',53.23,44,'Itaque est doloremque dolore praesentium sunt. Eum et corrupti ut illo. Qui accusamus nulla culpa aliquam vel cupiditate. Sapiente quis natus dolor dignissimos accusamus eum sunt.',NULL,'2026-04-28 08:33:34','2026-04-28 08:33:34'),(9,2,'Vitae voluptas voluptatum quaerat.','Asia Greenfelder','9784875414513',45.93,94,'Quasi sed et molestiae. Consequatur modi sed rem qui voluptatem molestiae. Consequatur esse dignissimos ut.',NULL,'2026-04-28 08:33:34','2026-04-28 08:33:34'),(10,2,'Qui et et.','Leopoldo Mertz','9793431609485',88.27,46,'Ut sed atque enim maiores ratione tempore. Laboriosam voluptatem sunt magni sunt. Excepturi deleniti enim tempore accusamus aut est praesentium.',NULL,'2026-04-28 08:33:34','2026-04-28 08:33:34'),(11,3,'Soluta sint.','Name Shields','9795125700744',72.33,7,'Ut perspiciatis velit et sapiente iure. Aliquam quibusdam voluptatem dolor ullam maiores minima et. Odio quis aut optio sapiente quia reprehenderit. Ut minus sapiente incidunt saepe et laboriosam necessitatibus. Dolores et quibusdam odio ad voluptatem.',NULL,'2026-04-28 08:33:34','2026-04-28 08:33:34'),(12,3,'Consequuntur qui sint quos aut.','Malachi Schowalter','9791203974762',73.60,89,'Ut dolor quis quae. Incidunt possimus quia minima maiores. Et autem accusantium qui fuga qui neque maxime.',NULL,'2026-04-28 08:33:34','2026-04-28 08:33:34'),(13,3,'A aut amet.','Verlie Franecki','9788314993690',44.53,43,'Praesentium in ut necessitatibus. Qui fugit eos porro commodi sint illo. Ducimus iste necessitatibus natus officia quos. Est explicabo nobis illum est.',NULL,'2026-04-28 08:33:34','2026-04-28 08:33:34'),(14,3,'Rerum quibusdam ut eveniet.','Lincoln Hartmann','9785807008589',48.38,81,'Laudantium velit officiis qui placeat facere quaerat occaecati. Quos et et eius eaque cumque asperiores architecto itaque. Dicta eligendi omnis qui. Aut placeat quisquam omnis non qui.',NULL,'2026-04-28 08:33:34','2026-04-28 08:33:34'),(15,3,'Ducimus voluptatem aut.','Angelita Robel IV','9793153224843',42.02,45,'Illum quo et unde. Facere illum quibusdam et eum quas dicta incidunt pariatur. Ipsum eos voluptatibus aliquam assumenda modi. Rerum a consequuntur molestias voluptatum hic omnis odio.',NULL,'2026-04-28 08:33:34','2026-04-28 08:33:34'),(16,4,'Aut et quasi.','Ally Hermann Sr.','9798422031276',15.05,87,'Nam qui rem quibusdam et iusto autem. Suscipit ad ut fugit fugiat harum. Tempora sit quis accusantium itaque repudiandae voluptatem maiores cum.',NULL,'2026-04-28 08:33:34','2026-04-28 08:33:34'),(17,4,'Minus voluptas cum rerum.','Alden Kris','9788562052026',92.11,98,'Voluptatem molestiae saepe quibusdam adipisci quibusdam sit. Ratione voluptas provident sed qui voluptas quas aut. Quo temporibus sunt qui modi doloremque. Sed minima esse amet beatae nemo velit et.',NULL,'2026-04-28 08:33:34','2026-04-28 08:33:34'),(18,4,'Explicabo omnis molestias ad.','Bernadette Zulauf','9782943352149',53.13,2,'Animi aperiam consequatur molestiae quia quisquam. Consequatur velit expedita vero rem in. Tenetur enim non voluptas iure provident ut. Porro fugit id perspiciatis et.',NULL,'2026-04-28 08:33:35','2026-04-28 08:33:35'),(19,4,'Quia aliquam labore.','Weldon Cummerata','9789597254539',16.06,3,'Earum ex repellendus qui enim iure beatae. Nisi eligendi eveniet non repudiandae excepturi magnam. Excepturi sit ut illo exercitationem et explicabo iste tempora. Quisquam doloremque repellendus ipsam.',NULL,'2026-04-28 08:33:35','2026-04-28 08:33:35'),(20,4,'Magnam et maxime et quam.','Prof. Dayton Cruickshank V','9784410415401',18.63,84,'Quod est veritatis dolores voluptas tenetur voluptatum. Qui minus id dolorem. Sed sunt voluptates omnis ex sint. Quasi ex facilis dolor tempora expedita sed corrupti in.',NULL,'2026-04-28 08:33:35','2026-04-28 08:33:35'),(21,5,'Atque aliquam necessitatibus.','Berniece Blick IV','9799523339964',46.44,8,'Ipsam enim tempore voluptas ducimus ut vel. Fuga est aperiam ab ipsam sunt. Voluptatum vitae in excepturi aspernatur placeat non at.',NULL,'2026-04-28 08:33:35','2026-04-28 08:33:35'),(22,5,'Totam quis qui iure.','Brady Mills','9790409746883',47.41,94,'Ipsam voluptatem sapiente fuga deserunt. Quae consequatur itaque velit placeat fuga. Eum ut et quia sint nam.',NULL,'2026-04-28 08:33:35','2026-04-28 08:33:35'),(23,5,'Totam animi quam.','Dillan Zulauf Jr.','9795026387006',79.41,80,'Optio aliquid officiis molestiae debitis a dolorem et eius. Recusandae et qui velit iste voluptas provident. Velit velit quia occaecati sed molestiae sit ex. Rerum in occaecati quod laboriosam. Qui expedita omnis nostrum.',NULL,'2026-04-28 08:33:35','2026-04-28 08:33:35'),(24,5,'Consequatur quia tenetur nisi.','Jeffery Wilkinson Sr.','9794816273611',51.85,6,'Quis illum ea laborum sunt. Accusamus in et molestiae aspernatur. Et repellat asperiores quisquam fugit doloribus. Corrupti reiciendis eligendi accusantium quam voluptatem ducimus.',NULL,'2026-04-28 08:33:35','2026-04-28 08:33:35'),(25,5,'Non enim eius.','Mrs. Susie Wilkinson','9792170016301',46.51,33,'Minima architecto quidem rerum minima nostrum reprehenderit non. Illo vitae officia voluptate rerum.',NULL,'2026-04-28 08:33:35','2026-04-28 08:33:35'),(26,6,'Et explicabo alias et.','Jared Bartell','9790302770282',68.72,15,'Veritatis neque est laborum ut accusantium nobis dolorem. Deleniti quis expedita quia animi.',NULL,'2026-04-28 08:33:35','2026-04-28 08:33:35'),(27,6,'Deserunt omnis laudantium.','Janice O\'Conner','9782479750372',77.71,92,'Ab nemo est est. Atque voluptate voluptatibus et porro nam vitae dolorem. Dolorem fugiat quaerat neque recusandae error et.',NULL,'2026-04-28 08:33:35','2026-04-28 08:33:35'),(28,6,'Laborum in aut.','Aurore Abbott','9790623475767',43.15,8,'Quod et necessitatibus recusandae neque laudantium occaecati rerum. Vero voluptatem unde dolorem veniam quis eveniet. Earum quis autem ut. Adipisci quam voluptatem culpa est tenetur quam quia.',NULL,'2026-04-28 08:33:35','2026-04-28 08:33:35'),(29,6,'Sit sunt veritatis.','Dr. Briana Price III','9787175706647',54.13,88,'Et placeat nihil odio id culpa quis. Earum minima quidem quis sed exercitationem soluta nemo a. Est atque saepe qui ut.',NULL,'2026-04-28 08:33:35','2026-04-28 08:33:35'),(30,6,'Et tempore harum.','Dr. Leatha Cummings III','9789383470884',34.72,85,'Eveniet a ut dolore et aspernatur dolore. Et odit qui commodi nihil totam sapiente vero. Est et labore eum dolorem at.',NULL,'2026-04-28 08:33:35','2026-04-28 08:33:35'),(31,7,'Dolorem tempore necessitatibus.','Jovany Bradtke','9794268393301',72.34,29,'Ut delectus aut ab culpa aut qui ut. Earum dolorum facere est explicabo qui. Occaecati voluptatem laudantium sint ipsum nihil ut praesentium. Velit iusto ut ipsa inventore non aliquid.',NULL,'2026-04-28 08:33:35','2026-04-28 08:33:35'),(32,7,'Beatae est nulla.','Winfield Parisian','9790812723976',40.69,35,'Id qui veritatis neque dolor ut quo aut. Iure dolores sit quia sed unde dolor et et. Quis repellendus doloremque explicabo nam. Inventore tempore molestias adipisci sequi.',NULL,'2026-04-28 08:33:35','2026-04-28 08:33:35'),(33,7,'Cum rem ea totam.','Gilberto Schinner','9792519710594',50.16,93,'Aut praesentium in et quibusdam. Deleniti maxime sit nemo ut. Odit voluptatum nisi ut voluptatibus. Veniam est autem provident asperiores omnis.',NULL,'2026-04-28 08:33:35','2026-04-28 08:33:35'),(34,7,'Accusantium voluptas ducimus.','Mollie Schumm PhD','9798741724811',20.31,95,'Corporis dolor ut porro et aspernatur. Aliquid sapiente nemo velit consequuntur et fuga. Sit in maiores adipisci nulla possimus.',NULL,'2026-04-28 08:33:36','2026-04-28 08:33:36'),(35,7,'Et ex et odio.','Mr. Jeramy Nikolaus','9791506117859',86.00,39,'Officia fuga similique nam vel sequi impedit eum. Impedit aliquam eum tempora occaecati est doloremque. Ut officia repellat magnam beatae voluptatem fuga.',NULL,'2026-04-28 08:33:36','2026-04-28 08:33:36'),(36,8,'Natus sit porro necessitatibus.','Mr. Davonte Pacocha IV','9784343140623',48.44,12,'Ut et qui quibusdam autem id. Cum aut totam deserunt totam sed. Amet sed quos quo corporis saepe.',NULL,'2026-04-28 08:33:36','2026-04-28 08:33:36'),(37,8,'Aut ut eum.','Anita Schimmel','9792129432541',23.10,26,'Rerum saepe veniam eos exercitationem. Saepe maxime fugit est totam nulla. Accusamus officia quos nisi qui dolor id.',NULL,'2026-04-28 08:33:36','2026-04-28 08:33:36'),(38,8,'Laudantium consequatur incidunt fugiat.','Fabian Eichmann','9790932932197',48.92,95,'Velit omnis nesciunt voluptate quos eos mollitia omnis alias. Sit iste omnis dolorem error consequatur. Commodi qui minima rerum commodi.',NULL,'2026-04-28 08:33:36','2026-04-28 08:33:36'),(39,8,'Ab animi explicabo.','Maurice Kohler','9792161900244',58.93,40,'Vero ut corporis eligendi sit aut. Molestiae dolor ratione ea est incidunt consequuntur voluptas. Alias eaque cupiditate laborum non ex qui perspiciatis. Autem culpa fugit tempora quidem inventore et sapiente.',NULL,'2026-04-28 08:33:36','2026-04-28 08:33:36'),(40,8,'Quis est dolore eum.','Marley Buckridge','9783918459702',60.89,53,'Delectus aperiam beatae ipsum quaerat tempora dolores. Optio qui eos est iste.',NULL,'2026-04-28 08:33:36','2026-04-28 08:33:36');
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('pageturner-bookstore-cache-0bceb8aa9bb761a521d54e1872e61f9a','i:1;',1778573122),('pageturner-bookstore-cache-0bceb8aa9bb761a521d54e1872e61f9a:timer','i:1778573122;',1778573122),('pageturner-bookstore-cache-7f3072bf378b98d6bbf2f013cff3e287','i:2;',1777411674),('pageturner-bookstore-cache-7f3072bf378b98d6bbf2f013cff3e287:timer','i:1777411674;',1777411674),('pageturner-bookstore-cache-bd307a3ec329e10a2cff8fb87480823da114f8f4','i:1;',1777411672),('pageturner-bookstore-cache-bd307a3ec329e10a2cff8fb87480823da114f8f4:timer','i:1777411672;',1777411672),('pageturner-bookstore-cache-c84258e9c39059a89ab77d846ddab909','i:3;',1778573151),('pageturner-bookstore-cache-c84258e9c39059a89ab77d846ddab909:timer','i:1778573151;',1778573151),('pageturner-bookstore-cache-e9b6cc1432541b9ceebf113eee05eeba','i:1;',1778573138),('pageturner-bookstore-cache-e9b6cc1432541b9ceebf113eee05eeba:timer','i:1778573138;',1778573138);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Fiction','Voluptatem eius qui fugiat excepturi sint et vitae. Dolores hic nostrum non minima et. Dolorem magnam amet nam omnis.','2026-04-28 08:33:31','2026-04-28 08:33:31'),(2,'Children','Reprehenderit reprehenderit qui sequi ducimus. Quibusdam saepe a perferendis rerum est maiores doloribus possimus. Corrupti autem veritatis est ut accusantium. Rerum modi omnis impedit qui.','2026-04-28 08:33:32','2026-04-28 08:33:32'),(3,'Technology','Omnis enim odit enim nobis. Aut earum voluptatem mollitia dolores. Eum accusantium nisi cupiditate eos impedit voluptatem ipsam.','2026-04-28 08:33:32','2026-04-28 08:33:32'),(4,'Romance','Ut quo aut dignissimos omnis. Ut nemo assumenda ipsa fuga consectetur. Suscipit provident illo blanditiis at voluptas officia.','2026-04-28 08:33:32','2026-04-28 08:33:32'),(5,'Mystery','Est quia ab debitis. Voluptatibus repellendus veniam eum qui corporis. Iusto et odit aut totam. Consequatur velit ex et vel esse odio.','2026-04-28 08:33:32','2026-04-28 08:33:32'),(6,'History','Aut praesentium cupiditate accusantium exercitationem. Ut eius nihil facilis et. Et nostrum est incidunt natus repellat quasi.','2026-04-28 08:33:32','2026-04-28 08:33:32'),(7,'Non-Fiction','Vitae ex quae et qui. Fuga perferendis et cupiditate non esse.','2026-04-28 08:33:32','2026-04-28 08:33:32'),(8,'Science','Alias et deleniti quas architecto eum rerum. Qui necessitatibus minus molestias et. Inventore ex consequatur architecto recusandae. Veritatis porro omnis exercitationem cum et ratione.','2026-04-28 08:33:32','2026-04-28 08:33:32');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `export_logs`
--

DROP TABLE IF EXISTS `export_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `export_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `format` enum('xlsx','csv','pdf') NOT NULL,
  `filters` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`filters`)),
  `status` enum('pending','processing','completed','failed') NOT NULL,
  `download_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `export_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `export_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `export_logs`
--

LOCK TABLES `export_logs` WRITE;
/*!40000 ALTER TABLE `export_logs` DISABLE KEYS */;
INSERT INTO `export_logs` VALUES (1,2,'xlsx','{\"category_id\":null,\"min_price\":null,\"max_price\":null,\"date_from\":null,\"date_to\":null}','completed',NULL,'2026-04-28 13:37:54','2026-04-28 13:37:54');
/*!40000 ALTER TABLE `export_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `import_logs`
--

DROP TABLE IF EXISTS `import_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `import_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `status` enum('pending','processing','completed','failed') NOT NULL,
  `total_rows` int(11) NOT NULL DEFAULT 0,
  `processed_rows` int(11) NOT NULL DEFAULT 0,
  `failed_rows` int(11) NOT NULL DEFAULT 0,
  `errors` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`errors`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `import_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `import_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `import_logs`
--

LOCK TABLES `import_logs` WRITE;
/*!40000 ALTER TABLE `import_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `import_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_02_07_135439_create_categories_table',1),(5,'2026_02_07_135449_create_books_table',1),(6,'2026_02_07_135504_create_orders_table',1),(7,'2026_02_07_135517_create_order_items_table',1),(8,'2026_02_07_135529_create_reviews_table',1),(9,'2026_02_15_063803_update_orders_enums',1),(10,'2026_02_15_112528_create_personal_access_tokens_table',1),(11,'2026_03_09_010902_add_two_factor_columns_to_users_table',1),(12,'2026_04_27_071744_create_import_logs_table',1),(13,'2026_04_27_071745_create_export_logs_table',1),(14,'2026_04_27_193416_create_audits_table',1),(15,'2026_04_27_235041_add_user_column_to_audits_table',1),(16,'2026_04_28_030703_create_api_request_logs_table',1),(17,'2026_04_28_042154_create_api_request_logs_table',1),(18,'2026_04_28_211437_create_reading_histories_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `book_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_book_id_foreign` (`book_id`),
  CONSTRAINT `order_items_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reading_histories`
--

DROP TABLE IF EXISTS `reading_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reading_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `book_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reading_histories_user_id_foreign` (`user_id`),
  KEY `reading_histories_book_id_foreign` (`book_id`),
  CONSTRAINT `reading_histories_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reading_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reading_histories`
--

LOCK TABLES `reading_histories` WRITE;
/*!40000 ALTER TABLE `reading_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `reading_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `book_id` bigint(20) unsigned NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reviews_user_id_book_id_unique` (`user_id`,`book_id`),
  KEY `reviews_book_id_foreign` (`book_id`),
  CONSTRAINT `reviews_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,3,13,5,'Aliquam mollitia sapiente quia labore. Expedita vel reiciendis eum dolor aut sunt. Reiciendis temporibus aliquam atque tenetur ea pariatur.','2026-04-28 08:33:36','2026-04-28 08:33:36'),(2,3,14,2,'Et temporibus ad nostrum soluta. Et omnis id omnis molestiae nobis doloremque. Est omnis autem doloribus aut.','2026-04-28 08:33:36','2026-04-28 08:33:36'),(3,3,17,1,'Minus voluptas omnis maxime rerum. Quisquam quo voluptatum eius provident provident quis et. Qui saepe soluta perferendis voluptatem distinctio eum consectetur rerum.','2026-04-28 08:33:36','2026-04-28 08:33:36'),(4,4,5,3,'Perspiciatis ex voluptas omnis iure ut vel. Vel fuga ipsa ullam omnis ut adipisci non. Est beatae eum eum. At enim quam modi esse aut neque.','2026-04-28 08:33:37','2026-04-28 08:33:37'),(5,4,14,1,'Et esse rerum enim. Eveniet molestiae consectetur aliquam illo ut iste. Ea iusto provident esse fuga.','2026-04-28 08:33:37','2026-04-28 08:33:37'),(6,4,17,5,'Occaecati facere rerum libero nemo dolores. Consectetur autem provident consequatur qui sint sed voluptatem facere. Sequi nisi animi hic. Fugiat dolorum numquam dicta rem quidem.','2026-04-28 08:33:37','2026-04-28 08:33:37'),(7,4,21,3,'Ratione accusamus dicta harum sit at reiciendis magni. Ullam alias et harum deleniti qui. Saepe ipsam labore dolor sunt.','2026-04-28 08:33:37','2026-04-28 08:33:37'),(8,4,25,2,'Possimus voluptas ad ducimus aut aut. Quia atque reiciendis et odio dolorem. Rerum vel inventore ut id eveniet. Occaecati suscipit odio natus.','2026-04-28 08:33:37','2026-04-28 08:33:37'),(9,5,23,2,'Voluptatem quas nisi et. Doloremque voluptas odio maiores autem. Ducimus aut molestiae omnis repellendus cupiditate accusamus.','2026-04-28 08:33:37','2026-04-28 08:33:37'),(10,5,24,4,'Ducimus voluptas hic dolorem. Quo ipsam dolorem ex nulla. Quos corrupti placeat dolor eos sunt.','2026-04-28 08:33:37','2026-04-28 08:33:37'),(11,5,29,4,'Sed et tempora debitis facilis aut rem est. Iusto quibusdam commodi magni assumenda rerum. Eos asperiores tempore excepturi sint suscipit adipisci perspiciatis. Iste ullam adipisci blanditiis odit.','2026-04-28 08:33:37','2026-04-28 08:33:37'),(12,6,5,4,'Culpa rerum nemo praesentium ea consectetur ipsum. Id excepturi rem velit voluptas quo nisi. Est aut et voluptatibus molestiae voluptate sit necessitatibus. Est qui enim qui quia nihil quo corporis.','2026-04-28 08:33:37','2026-04-28 08:33:37'),(13,6,7,5,'Quae sit ut et et autem aliquid at. Voluptas ea minima sed est. Qui deleniti aut cupiditate consequatur culpa nulla est enim.','2026-04-28 08:33:37','2026-04-28 08:33:37'),(14,6,20,3,'Voluptates maiores laudantium quia quisquam. Aut ratione ut et mollitia ipsa quod. Ullam quidem ducimus facilis voluptatem molestias recusandae eveniet.','2026-04-28 08:33:38','2026-04-28 08:33:38'),(15,6,31,3,'Aut in impedit pariatur dolore. Sed inventore qui quia ut nemo. Explicabo eius eos dolorum et. Aut laborum ullam aliquam.','2026-04-28 08:33:38','2026-04-28 08:33:38'),(16,6,36,3,'Et et sit id porro molestiae. Et ea officia sed. Quia natus veritatis deserunt quia non tempore nihil est.','2026-04-28 08:33:38','2026-04-28 08:33:38'),(17,7,14,5,'Quidem quia unde recusandae et. Ipsam sit nihil eum sint fuga atque ut. Est eos commodi natus.','2026-04-28 08:33:38','2026-04-28 08:33:38'),(18,7,18,1,'Voluptatem ratione repellat veritatis cupiditate illo id. A in eius architecto quia. Exercitationem dolores sequi unde accusantium non. Quia commodi exercitationem sunt veniam. Illo eius sint praesentium alias voluptas voluptatem.','2026-04-28 08:33:38','2026-04-28 08:33:38'),(19,7,24,4,'Id vitae facere laudantium ipsum nobis id qui. Est nulla exercitationem voluptatem ut autem rerum. Atque et a suscipit saepe et. Dolore necessitatibus voluptatem et perferendis nemo.','2026-04-28 08:33:38','2026-04-28 08:33:38'),(20,8,2,1,'Velit qui voluptas sunt atque dolore accusamus nesciunt. Deleniti nobis quod eveniet aut et. Provident vitae corrupti optio rerum.','2026-04-28 08:33:38','2026-04-28 08:33:38'),(21,8,4,5,'Facere ducimus distinctio nostrum nisi dolores. Aut dolorem dignissimos eius nisi porro. Et officia sit temporibus id impedit. Quis sed odit eveniet.','2026-04-28 08:33:38','2026-04-28 08:33:38'),(22,8,16,3,'Quaerat minima natus delectus laudantium vero ullam possimus. Qui commodi quam dolores pariatur quis. Doloremque officia maxime dicta repellat.','2026-04-28 08:33:38','2026-04-28 08:33:38'),(23,8,24,4,'Dolores id possimus rerum ad ipsum. Vero eius saepe aliquid impedit impedit. Voluptatem atque asperiores et eum aut. Aliquid accusantium consequatur facilis nesciunt veritatis aut.','2026-04-28 08:33:38','2026-04-28 08:33:38'),(24,9,4,5,'Unde maiores eligendi modi facilis. Praesentium accusantium quo voluptas labore pariatur. Ducimus vel delectus praesentium architecto ut.','2026-04-28 08:33:39','2026-04-28 08:33:39'),(25,9,16,3,'Consequatur adipisci similique illum et dolores velit. Autem molestiae qui enim et. Non pariatur repellendus totam nisi harum vel at. Illo cumque occaecati temporibus omnis.','2026-04-28 08:33:39','2026-04-28 08:33:39'),(26,9,26,1,'Tenetur quia ratione eaque vel dolorem hic. Fugit provident praesentium consequatur minus modi ut repellat. Possimus dolorem dolorem nihil numquam.','2026-04-28 08:33:39','2026-04-28 08:33:39'),(27,9,33,5,'Reprehenderit accusantium magni voluptatem eos consequuntur hic. Necessitatibus culpa consequatur consequatur commodi accusamus a. Voluptas ipsa aut consequatur dolore voluptatibus voluptas. Est illum tenetur molestias adipisci sed molestiae.','2026-04-28 08:33:39','2026-04-28 08:33:39'),(28,10,11,4,'Qui dolorem quibusdam vitae dolor ipsum. Quam deleniti et sit exercitationem maiores facere velit alias. Quod sed et doloribus asperiores. Neque est omnis nemo occaecati in. Omnis vel quos dolores corporis sunt consequatur voluptas.','2026-04-28 08:33:39','2026-04-28 08:33:39'),(29,10,20,2,'Rerum accusamus autem temporibus. Praesentium quae ea culpa consectetur tempora. Voluptas consequatur ratione numquam aut. Voluptas eos magnam quas autem quo optio quod.','2026-04-28 08:33:39','2026-04-28 08:33:39'),(30,10,27,5,'Distinctio molestias aspernatur est rem rerum. Odit aspernatur cum nostrum a. Culpa dolores illo et asperiores omnis possimus. Reprehenderit vel qui deleniti sint sint.','2026-04-28 08:33:39','2026-04-28 08:33:39'),(31,10,34,1,'Id sed accusamus illo nam sed dolor quaerat. Labore suscipit quibusdam aspernatur excepturi veritatis qui magni necessitatibus. Consequatur ullam soluta eum ut labore.','2026-04-28 08:33:39','2026-04-28 08:33:39'),(32,10,38,2,'Beatae autem sunt quia eum sit magnam voluptas. Rerum optio eius et sunt. Velit dolores ducimus ipsum.','2026-04-28 08:33:39','2026-04-28 08:33:39'),(33,11,6,4,'Minima et et quia alias velit temporibus. Deserunt voluptate est nisi porro. Mollitia sint ut sequi cumque placeat dignissimos eum. Tempora numquam assumenda commodi dolores architecto occaecati.','2026-04-28 08:33:39','2026-04-28 08:33:39'),(34,11,19,3,'Amet dolore cupiditate cum necessitatibus. Sit eligendi voluptas rerum quaerat in eos. Laudantium recusandae dolorem ut et. In dolor atque omnis repudiandae quisquam.','2026-04-28 08:33:39','2026-04-28 08:33:39'),(35,11,34,1,'Vel perspiciatis ratione numquam eligendi. Nihil accusantium quasi voluptatem dolorum et vero. Commodi aspernatur assumenda dolor nisi minus.','2026-04-28 08:33:39','2026-04-28 08:33:39'),(36,11,36,1,'Quos vel occaecati libero consectetur ut. Deleniti porro cupiditate debitis architecto. Tempora praesentium consequatur tenetur tempora occaecati eligendi similique. Natus inventore in qui quia dolores aut rerum.','2026-04-28 08:33:39','2026-04-28 08:33:39'),(37,11,39,3,'Voluptates magnam ab culpa amet non. Amet minima velit tenetur vel aut. Perspiciatis dignissimos neque vel dolor saepe fugiat. Occaecati ipsa recusandae tempore similique.','2026-04-28 08:33:39','2026-04-28 08:33:39'),(38,12,3,4,'Est eaque est molestiae nam vitae tempore iste. Illum distinctio sed ratione consequatur. Fugit officiis asperiores cupiditate. Ducimus dolor et animi consequatur dolorum expedita quis.','2026-04-28 08:33:39','2026-04-28 08:33:39'),(39,12,15,5,'Ut consequatur animi deleniti numquam quos et inventore ut. Qui repellendus voluptas ipsa expedita harum. Nisi sit ut soluta. Fuga earum voluptatibus molestiae qui.','2026-04-28 08:33:40','2026-04-28 08:33:40'),(40,12,28,5,'Natus expedita dolorem dolores dolores. Ut aut sequi enim explicabo omnis cumque ut fuga. Unde architecto voluptatem temporibus sit modi voluptatem libero. Voluptas unde magni laboriosam enim nobis distinctio voluptates.','2026-04-28 08:33:40','2026-04-28 08:33:40'),(41,12,34,5,'Omnis vel qui cumque. Cum reiciendis totam itaque natus saepe amet voluptates. Veritatis est impedit dolor. Ut qui qui atque reprehenderit.','2026-04-28 08:33:40','2026-04-28 08:33:40'),(42,12,39,2,'Et dicta sit rerum commodi magni aut. Culpa ut sunt est nostrum nam fuga. Optio iste perferendis libero et tenetur et.','2026-04-28 08:33:40','2026-04-28 08:33:40');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('AcPRhjb7YWe0FZxy89FN3XsniZiPB4f8WL2P8b6y',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoienFkU1BmVThsU0hRTnF3TllaQ0c3bzY3UnhPNmhYeEtZVEdjcmVLSyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk4OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vYm9va3MvZXhwb3J0P2NhdGVnb3J5X2lkPSZjb2x1bW5zJTVCMCU1RD1pc2JuJmNvbHVtbnMlNUIxJTVEPXRpdGxlJmNvbHVtbnMlNUIyJTVEPWF1dGhvciZjb2x1bW5zJTVCMyU1RD1wcmljZSZjb2x1bW5zJTVCNCU1RD1zdG9ja19xdWFudGl0eSZjb2x1bW5zJTVCNSU1RD1jYXRlZ29yeSZjb2x1bW5zJTVCNiU1RD1kZXNjcmlwdGlvbiZjb2x1bW5zJTVCNyU1RD1jcmVhdGVkX2F0JmRhdGVfZnJvbT0mZGF0ZV90bz0mZm9ybWF0PXhsc3gmbWF4X3ByaWNlPSZtaW5fcHJpY2U9IjtzOjU6InJvdXRlIjtzOjE4OiJhZG1pbi5ib29rcy5leHBvcnQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=',1777412280),('igDVZfPwkJMJZVWUROoPKOpFjihbdjsMcTHGEw9X',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRkJENVBMR2Nwakl1ZXc2Q3hPclB1cGd3WE9oalpDN2VjejhMY3dCMiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777460588),('wX2GD0mPveaHhMg24iqI4XTnP4gddpTJ84Ye9FE1',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNHU5NVdzcURLcWlBdHgzRWhJb1dWQ0pvS3pCYTQxOUtZZWc5UnlzciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9iYWNrdXAiO3M6NToicm91dGUiO3M6MTg6ImFkbWluLmJhY2t1cC5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==',1778573122),('XziIQgAJ9pTowGXheRwaka1DfOYkyOEMEA6vJtRE',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMUJlZE5UWHlLRTF0aENmdG5TeGZWNFN4T3c4N0pzWE1SbHZTNTVPciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9hdWRpdC83IjtzOjU6InJvdXRlIjtzOjE2OiJhZG1pbi5hdWRpdC5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9',1778034639);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_code` varchar(255) DEFAULT NULL,
  `two_factor_expires_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Test User','test@example.com','2026-04-28 08:33:29','$2y$12$nY.IXvoHWY3ab0fFlrU8lOJaK8No0bOF78gwemzCFmztdR38a2bmm','customer','iN98Qj0mJx','2026-04-28 08:33:29','2026-04-28 08:33:29',0,NULL,NULL),(2,'Admin User','admin@pageturner.com','2026-04-28 08:33:30','$2y$12$nY.IXvoHWY3ab0fFlrU8lOJaK8No0bOF78gwemzCFmztdR38a2bmm','admin','kyLzr7AtCHaZMarRc3YLAticsdd4JUBv2CFz3xepVMkGnUVVZXNKwOTRo3H0','2026-04-28 08:33:30','2026-04-28 08:33:30',0,NULL,NULL),(3,'Louvenia Leannon','damore.raegan@example.org','2026-04-28 08:33:30','$2y$12$nY.IXvoHWY3ab0fFlrU8lOJaK8No0bOF78gwemzCFmztdR38a2bmm','customer','lQQfQpxege','2026-04-28 08:33:30','2026-04-28 08:33:30',0,NULL,NULL),(4,'Miss Lydia Ankunding','runolfsdottir.neal@example.org','2026-04-28 08:33:30','$2y$12$nY.IXvoHWY3ab0fFlrU8lOJaK8No0bOF78gwemzCFmztdR38a2bmm','customer','dYmerIo98b','2026-04-28 08:33:31','2026-04-28 08:33:31',0,NULL,NULL),(5,'Lela Cronin','nbode@example.org','2026-04-28 08:33:30','$2y$12$nY.IXvoHWY3ab0fFlrU8lOJaK8No0bOF78gwemzCFmztdR38a2bmm','customer','KHfhKTKrYb','2026-04-28 08:33:31','2026-04-28 08:33:31',0,NULL,NULL),(6,'Gunner Weimann','frederic.lueilwitz@example.com','2026-04-28 08:33:30','$2y$12$nY.IXvoHWY3ab0fFlrU8lOJaK8No0bOF78gwemzCFmztdR38a2bmm','customer','UTXp2FgNgA','2026-04-28 08:33:31','2026-04-28 08:33:31',0,NULL,NULL),(7,'Elmira Jones','lbeer@example.net','2026-04-28 08:33:30','$2y$12$nY.IXvoHWY3ab0fFlrU8lOJaK8No0bOF78gwemzCFmztdR38a2bmm','customer','dOGBjjQgml','2026-04-28 08:33:31','2026-04-28 08:33:31',0,NULL,NULL),(8,'Dorris Doyle','dietrich.kaylee@example.com','2026-04-28 08:33:30','$2y$12$nY.IXvoHWY3ab0fFlrU8lOJaK8No0bOF78gwemzCFmztdR38a2bmm','customer','3qHFNXRUTA','2026-04-28 08:33:31','2026-04-28 08:33:31',0,NULL,NULL),(9,'Miss Maggie Senger II','eusebio14@example.org','2026-04-28 08:33:30','$2y$12$nY.IXvoHWY3ab0fFlrU8lOJaK8No0bOF78gwemzCFmztdR38a2bmm','customer','CAoMy9GQZX','2026-04-28 08:33:31','2026-04-28 08:33:31',0,NULL,NULL),(10,'Mr. Taurean Stoltenberg','etha87@example.net','2026-04-28 08:33:30','$2y$12$nY.IXvoHWY3ab0fFlrU8lOJaK8No0bOF78gwemzCFmztdR38a2bmm','customer','UFeRNchcxd','2026-04-28 08:33:31','2026-04-28 08:33:31',0,NULL,NULL),(11,'Dr. Orion Schinner','stacey.klein@example.net','2026-04-28 08:33:30','$2y$12$nY.IXvoHWY3ab0fFlrU8lOJaK8No0bOF78gwemzCFmztdR38a2bmm','customer','Kl9JaArud6','2026-04-28 08:33:31','2026-04-28 08:33:31',0,NULL,NULL),(12,'Dr. Coty Hills I','justyn78@example.com','2026-04-28 08:33:30','$2y$12$nY.IXvoHWY3ab0fFlrU8lOJaK8No0bOF78gwemzCFmztdR38a2bmm','customer','Ujc2ZBATcL','2026-04-28 08:33:31','2026-04-28 08:33:31',0,NULL,NULL),(13,'Nell Blomkampp','e.campomanes101848@gmail.com','2026-04-28 13:20:21','$2y$12$JKAHqXkH3U9uR2RxEqOWN.lWqVLXBWCOoBHTHqMmyxMdRTGybnbYO','customer',NULL,'2026-04-28 13:19:40','2026-04-28 13:20:21',0,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-12 16:12:30
