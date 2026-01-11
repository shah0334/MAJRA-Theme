/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.1.2-MariaDB, for osx10.21 (arm64)
--
-- Host: 127.0.0.1    Database: sic_portal
-- ------------------------------------------------------
-- Server version	8.0.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `sic_applicants`
--

DROP TABLE IF EXISTS `sic_applicants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sic_applicants` (
  `applicant_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `wp_user_id` bigint unsigned DEFAULT NULL,
  `email` varchar(320) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`applicant_id`),
  UNIQUE KEY `uq_applicant_email` (`email`),
  UNIQUE KEY `uq_applicant_wp_user` (`wp_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sic_applicants`
--

LOCK TABLES `sic_applicants` WRITE;
/*!40000 ALTER TABLE `sic_applicants` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sic_applicants` VALUES
(1,NULL,'dummy@sic.ae','SIC','Shah','0500000000','2026-01-08 21:07:36','2026-01-09 01:32:00');
/*!40000 ALTER TABLE `sic_applicants` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sic_beneficiary_types`
--

DROP TABLE IF EXISTS `sic_beneficiary_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sic_beneficiary_types` (
  `beneficiary_type_id` tinyint unsigned NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`beneficiary_type_id`),
  UNIQUE KEY `uq_beneficiary_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sic_beneficiary_types`
--

LOCK TABLES `sic_beneficiary_types` WRITE;
/*!40000 ALTER TABLE `sic_beneficiary_types` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sic_beneficiary_types` VALUES
(7,'Creative Professionals & Innovators'),
(3,'Elderly'),
(5,'Families'),
(9,'General Public'),
(4,'People of Determination'),
(6,'Small Businesses & Entrepreneurs'),
(8,'Third Sector Organizations'),
(2,'Women & Girls'),
(1,'Youth & Students');
/*!40000 ALTER TABLE `sic_beneficiary_types` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sic_files`
--

DROP TABLE IF EXISTS `sic_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sic_files` (
  `file_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cycle_id` bigint unsigned NOT NULL,
  `storage_provider` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `storage_url` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL,
  `storage_key` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mime_type` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size_bytes` bigint unsigned DEFAULT NULL,
  `sha256` char(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_by_applicant_id` bigint unsigned DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`file_id`),
  KEY `idx_files_cycle` (`cycle_id`),
  KEY `idx_files_uploaded_by` (`uploaded_by_applicant_id`),
  CONSTRAINT `fk_files_cycle` FOREIGN KEY (`cycle_id`) REFERENCES `sic_program_cycles` (`cycle_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_files_uploaded_by` FOREIGN KEY (`uploaded_by_applicant_id`) REFERENCES `sic_applicants` (`applicant_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sic_files`
--

LOCK TABLES `sic_files` WRITE;
/*!40000 ALTER TABLE `sic_files` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sic_files` VALUES
(1,2,'local','http://majra-local.local/wp-content/uploads/org-logos/2026/01/app-icon-1.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/org-logos/2026/01/app-icon-1.png','app-icon-1.png','image/png',NULL,NULL,1,NULL,'2026-01-08 21:33:58'),
(2,2,'local','http://majra-local.local/wp-content/uploads/org-licenses/2026/01/Savola_Professional_Catalogue_Food_Industry-3.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/org-licenses/2026/01/Savola_Professional_Catalogue_Food_Industry-3.pdf','Savola_Professional_Catalogue_Food_Industry-3.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-08 21:33:58'),
(3,2,'local','http://majra-local.local/wp-content/uploads/org-logos/2026/01/Barnaby-Hi.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/org-logos/2026/01/Barnaby-Hi.png','Barnaby-Hi.png','image/png',NULL,NULL,1,NULL,'2026-01-08 21:47:36'),
(4,2,'local','http://majra-local.local/wp-content/uploads/org-licenses/2026/01/Savola_Professional_Catalogue_Food_Service.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/org-licenses/2026/01/Savola_Professional_Catalogue_Food_Service.pdf','Savola_Professional_Catalogue_Food_Service.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-08 21:47:36'),
(5,2,'local','http://majra-local.local/wp-content/uploads/org-logos/2026/01/Gemini_Generated_Image_vez10vvez10vvez1.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/org-logos/2026/01/Gemini_Generated_Image_vez10vvez10vvez1.png','Gemini_Generated_Image_vez10vvez10vvez1.png','image/png',NULL,NULL,1,NULL,'2026-01-08 21:49:04'),
(6,2,'local','http://majra-local.local/wp-content/uploads/org-licenses/2026/01/Savola_Professional_Catalogue_Food_Service-1.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/org-licenses/2026/01/Savola_Professional_Catalogue_Food_Service-1.pdf','Savola_Professional_Catalogue_Food_Service-1.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-08 21:49:04'),
(7,2,'local','http://majra-local.local/wp-content/uploads/org-logos/2026/01/app-icon-1-1.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/org-logos/2026/01/app-icon-1-1.png','app-icon-1-1.png','image/png',NULL,NULL,1,NULL,'2026-01-08 21:52:00'),
(8,2,'local','http://majra-local.local/wp-content/uploads/org-licenses/2026/01/Savola_Professional_Catalogue_Food_Service-2.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/org-licenses/2026/01/Savola_Professional_Catalogue_Food_Service-2.pdf','Savola_Professional_Catalogue_Food_Service-2.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-08 21:52:00'),
(9,2,'local','http://majra-local.local/wp-content/uploads/project-profiles/2026/01/Savola_Professional_Catalogue_Food_Service.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-profiles/2026/01/Savola_Professional_Catalogue_Food_Service.pdf','Savola_Professional_Catalogue_Food_Service.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-08 22:10:00'),
(10,2,'local','http://majra-local.local/wp-content/uploads/project-profiles/2026/01/Savola_Professional_Catalogue_Food_Service-1.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-profiles/2026/01/Savola_Professional_Catalogue_Food_Service-1.pdf','Savola_Professional_Catalogue_Food_Service-1.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-08 22:17:31'),
(11,2,'local','http://majra-local.local/wp-content/uploads/project-profiles/2026/01/Savola_Professional_Catalogue_Food_Service-2.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-profiles/2026/01/Savola_Professional_Catalogue_Food_Service-2.pdf','Savola_Professional_Catalogue_Food_Service-2.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-08 22:17:52'),
(12,2,'local','http://majra-local.local/wp-content/uploads/project-profiles/2026/01/app-icon-1.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-profiles/2026/01/app-icon-1.png','app-icon-1.png','image/png',NULL,NULL,1,NULL,'2026-01-09 10:02:17'),
(13,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Service.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Service.pdf','Savola_Professional_Catalogue_Food_Service.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-09 10:02:52'),
(14,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Industry.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Industry.pdf','Savola_Professional_Catalogue_Food_Industry.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-09 10:02:52'),
(15,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Non_Food-1.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Non_Food-1.pdf','Savola_Professional_Catalogue_Non_Food-1.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-09 10:02:52'),
(16,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Industry-1.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Industry-1.pdf','Savola_Professional_Catalogue_Food_Industry-1.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-09 10:02:52'),
(17,2,'local','http://majra-local.local/wp-content/uploads/org-logos/2026/01/app-icon-1-2.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/org-logos/2026/01/app-icon-1-2.png','app-icon-1-2.png','image/png',NULL,NULL,1,NULL,'2026-01-09 10:26:20'),
(18,2,'local','http://majra-local.local/wp-content/uploads/org-licenses/2026/01/Savola_Professional_Catalogue_Food_Service-3.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/org-licenses/2026/01/Savola_Professional_Catalogue_Food_Service-3.pdf','Savola_Professional_Catalogue_Food_Service-3.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-09 10:26:20'),
(19,2,'local','http://majra-local.local/wp-content/uploads/project-profiles/2026/01/app-icon-1-1.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-profiles/2026/01/app-icon-1-1.png','app-icon-1-1.png','image/png',NULL,NULL,1,NULL,'2026-01-09 11:01:18'),
(20,2,'local','http://majra-local.local/wp-content/uploads/project-profiles/2026/01/app-icon-1-2.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-profiles/2026/01/app-icon-1-2.png','app-icon-1-2.png','image/png',NULL,NULL,1,NULL,'2026-01-10 11:02:27'),
(21,2,'local','http://majra-local.local/wp-content/uploads/project-profiles/2026/01/app-icon-1-3.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-profiles/2026/01/app-icon-1-3.png','app-icon-1-3.png','image/png',NULL,NULL,1,NULL,'2026-01-10 11:07:19'),
(22,2,'local','http://majra-local.local/wp-content/uploads/project-profiles/2026/01/app-icon-1-4.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-profiles/2026/01/app-icon-1-4.png','app-icon-1-4.png','image/png',NULL,NULL,1,NULL,'2026-01-10 11:15:22'),
(23,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/app-icon-1.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/app-icon-1.png','app-icon-1.png','image/png',NULL,NULL,1,NULL,'2026-01-10 11:45:53'),
(24,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Industry-2.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Industry-2.pdf','Savola_Professional_Catalogue_Food_Industry-2.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-10 11:45:53'),
(25,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Industry-3.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Industry-3.pdf','Savola_Professional_Catalogue_Food_Industry-3.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-10 11:45:53'),
(26,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Non_Food-1-1.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Non_Food-1-1.pdf','Savola_Professional_Catalogue_Non_Food-1-1.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-10 11:45:53'),
(27,2,'local','http://majra-local.local/wp-content/uploads/project-profiles/2026/01/app-icon-1-5.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-profiles/2026/01/app-icon-1-5.png','app-icon-1-5.png','image/png',NULL,NULL,1,NULL,'2026-01-10 11:47:10'),
(28,2,'local','http://majra-local.local/wp-content/uploads/project-profiles/2026/01/app-icon-1-6.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-profiles/2026/01/app-icon-1-6.png','app-icon-1-6.png','image/png',NULL,NULL,1,NULL,'2026-01-10 11:47:57'),
(29,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/app-icon-1-1.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/app-icon-1-1.png','app-icon-1-1.png','image/png',NULL,NULL,1,NULL,'2026-01-10 11:48:14'),
(30,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Service-1.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Service-1.pdf','Savola_Professional_Catalogue_Food_Service-1.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-10 11:48:14'),
(31,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Industry-4.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Industry-4.pdf','Savola_Professional_Catalogue_Food_Industry-4.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-10 11:48:14'),
(32,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Non_Food-1-2.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Non_Food-1-2.pdf','Savola_Professional_Catalogue_Non_Food-1-2.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-10 11:48:14'),
(33,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/app-icon-1-2.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/app-icon-1-2.png','app-icon-1-2.png','image/png',NULL,NULL,1,NULL,'2026-01-10 12:12:16'),
(34,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Service-2.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Service-2.pdf','Savola_Professional_Catalogue_Food_Service-2.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-10 12:12:16'),
(35,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Industry-5.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Industry-5.pdf','Savola_Professional_Catalogue_Food_Industry-5.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-10 12:12:16'),
(36,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Non_Food-1-3.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Non_Food-1-3.pdf','Savola_Professional_Catalogue_Non_Food-1-3.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-10 12:12:16'),
(37,2,'local','http://majra-local.local/wp-content/uploads/project-profiles/2026/01/app-icon-1-7.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-profiles/2026/01/app-icon-1-7.png','app-icon-1-7.png','image/png',NULL,NULL,1,NULL,'2026-01-10 12:13:47'),
(38,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/app-icon-1-3.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/app-icon-1-3.png','app-icon-1-3.png','image/png',NULL,NULL,1,NULL,'2026-01-10 12:14:11'),
(39,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Service-3.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Service-3.pdf','Savola_Professional_Catalogue_Food_Service-3.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-10 12:14:11'),
(40,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Industry-6.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Food_Industry-6.pdf','Savola_Professional_Catalogue_Food_Industry-6.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-10 12:14:11'),
(41,2,'local','http://majra-local.local/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Non_Food-1-4.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-evidence/2026/01/Savola_Professional_Catalogue_Non_Food-1-4.pdf','Savola_Professional_Catalogue_Non_Food-1-4.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-10 12:14:11'),
(42,2,'local','http://majra-local.local/wp-content/uploads/org-logos/2026/01/Screenshot-2026-01-09-at-4.02.48-PM.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/org-logos/2026/01/Screenshot-2026-01-09-at-4.02.48-PM.png','Screenshot-2026-01-09-at-4.02.48-PM.png','image/png',NULL,NULL,1,NULL,'2026-01-10 19:27:29'),
(43,2,'local','http://majra-local.local/wp-content/uploads/org-licenses/2026/01/Savola_Professional_Catalogue_Food_Service-4.pdf','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/org-licenses/2026/01/Savola_Professional_Catalogue_Food_Service-4.pdf','Savola_Professional_Catalogue_Food_Service-4.pdf','application/pdf',NULL,NULL,1,NULL,'2026-01-10 19:27:29'),
(44,2,'local','http://majra-local.local/wp-content/uploads/project-profiles/2026/01/app-icon-1-8.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-profiles/2026/01/app-icon-1-8.png','app-icon-1-8.png','image/png',NULL,NULL,1,NULL,'2026-01-10 19:40:53'),
(45,2,'local','http://majra-local.local/wp-content/uploads/project-profiles/2026/01/app-icon-1-9.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-profiles/2026/01/app-icon-1-9.png','app-icon-1-9.png','image/png',NULL,NULL,1,NULL,'2026-01-10 19:41:21'),
(46,2,'local','http://majra-local.local/wp-content/uploads/project-profiles/2026/01/app-icon-1-10.png','/Users/muhammadshah/Local Sites/majra-local/app/public/wp-content/uploads/project-profiles/2026/01/app-icon-1-10.png','app-icon-1-10.png','image/png',NULL,NULL,1,NULL,'2026-01-10 19:42:26');
/*!40000 ALTER TABLE `sic_files` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sic_impact_areas`
--

DROP TABLE IF EXISTS `sic_impact_areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sic_impact_areas` (
  `impact_area_id` tinyint unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`impact_area_id`),
  UNIQUE KEY `uq_impact_area_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sic_impact_areas`
--

LOCK TABLES `sic_impact_areas` WRITE;
/*!40000 ALTER TABLE `sic_impact_areas` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sic_impact_areas` VALUES
(1,'Art, Culture & Heritage'),
(6,'Education'),
(2,'Environment'),
(4,'Health'),
(5,'Sports'),
(3,'Technology');
/*!40000 ALTER TABLE `sic_impact_areas` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sic_org_csr_activities`
--

DROP TABLE IF EXISTS `sic_org_csr_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sic_org_csr_activities` (
  `csr_activity_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `org_profile_id` bigint unsigned NOT NULL,
  `program_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `allocated_amount_aed` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`csr_activity_id`),
  KEY `idx_csr_profile` (`org_profile_id`),
  CONSTRAINT `fk_csr_profile` FOREIGN KEY (`org_profile_id`) REFERENCES `sic_organization_profiles` (`org_profile_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sic_org_csr_activities`
--

LOCK TABLES `sic_org_csr_activities` WRITE;
/*!40000 ALTER TABLE `sic_org_csr_activities` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sic_org_csr_activities` VALUES
(3,1,'asdasd',213.00,'2026-01-08 21:49:04'),
(4,2,'asdasd',0.00,'2026-01-08 21:52:00'),
(5,3,'sadasd',123.00,'2026-01-09 10:26:20');
/*!40000 ALTER TABLE `sic_org_csr_activities` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sic_organization_members`
--

DROP TABLE IF EXISTS `sic_organization_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sic_organization_members` (
  `organization_id` bigint unsigned NOT NULL,
  `applicant_id` bigint unsigned NOT NULL,
  `member_role` enum('owner','editor','viewer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'owner',
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`organization_id`,`applicant_id`),
  KEY `fk_org_members_app` (`applicant_id`),
  CONSTRAINT `fk_org_members_app` FOREIGN KEY (`applicant_id`) REFERENCES `sic_applicants` (`applicant_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_org_members_org` FOREIGN KEY (`organization_id`) REFERENCES `sic_organizations` (`organization_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sic_organization_members`
--

LOCK TABLES `sic_organization_members` WRITE;
/*!40000 ALTER TABLE `sic_organization_members` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sic_organization_members` VALUES
(1,1,'owner','2026-01-08 21:33:58'),
(2,1,'owner','2026-01-08 21:52:00'),
(3,1,'owner','2026-01-09 10:26:20'),
(4,1,'owner','2026-01-10 19:27:29');
/*!40000 ALTER TABLE `sic_organization_members` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sic_organization_profile_files`
--

DROP TABLE IF EXISTS `sic_organization_profile_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sic_organization_profile_files` (
  `org_profile_id` bigint unsigned NOT NULL,
  `file_role` enum('logo','trade_license_certificate') COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_id` bigint unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`org_profile_id`,`file_role`),
  UNIQUE KEY `uq_org_profile_file_fileid` (`file_id`),
  CONSTRAINT `fk_org_profile_files_file` FOREIGN KEY (`file_id`) REFERENCES `sic_files` (`file_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_org_profile_files_profile` FOREIGN KEY (`org_profile_id`) REFERENCES `sic_organization_profiles` (`org_profile_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sic_organization_profile_files`
--

LOCK TABLES `sic_organization_profile_files` WRITE;
/*!40000 ALTER TABLE `sic_organization_profile_files` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sic_organization_profile_files` VALUES
(1,'logo',5,'2026-01-08 21:49:04'),
(1,'trade_license_certificate',6,'2026-01-08 21:49:04'),
(2,'logo',7,'2026-01-08 21:52:00'),
(2,'trade_license_certificate',8,'2026-01-08 21:52:00'),
(3,'logo',17,'2026-01-09 10:26:20'),
(3,'trade_license_certificate',18,'2026-01-09 10:26:20'),
(4,'logo',42,'2026-01-10 19:27:29'),
(4,'trade_license_certificate',43,'2026-01-10 19:27:29');
/*!40000 ALTER TABLE `sic_organization_profile_files` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sic_organization_profiles`
--

DROP TABLE IF EXISTS `sic_organization_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sic_organization_profiles` (
  `org_profile_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cycle_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `created_by_applicant_id` bigint unsigned NOT NULL,
  `organization_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trade_license_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website_url` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emirate_of_registration` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `legal_entity_type` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `industry` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_freezone` tinyint(1) NOT NULL DEFAULT '0',
  `business_activity_type` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_of_employees` int unsigned DEFAULT NULL,
  `annual_turnover_band` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `csr_implemented` tinyint(1) DEFAULT NULL,
  `status` enum('draft','finalized') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `finalized_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`org_profile_id`),
  UNIQUE KEY `uq_org_cycle` (`cycle_id`,`organization_id`),
  UNIQUE KEY `uq_org_profile_cycle` (`org_profile_id`,`cycle_id`),
  KEY `idx_org_profile_cycle` (`cycle_id`),
  KEY `idx_org_profile_org` (`organization_id`),
  KEY `idx_org_profile_created_by` (`created_by_applicant_id`),
  CONSTRAINT `fk_org_profile_created_by` FOREIGN KEY (`created_by_applicant_id`) REFERENCES `sic_applicants` (`applicant_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_org_profile_cycle` FOREIGN KEY (`cycle_id`) REFERENCES `sic_program_cycles` (`cycle_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_org_profile_org` FOREIGN KEY (`organization_id`) REFERENCES `sic_organizations` (`organization_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sic_organization_profiles`
--

LOCK TABLES `sic_organization_profiles` WRITE;
/*!40000 ALTER TABLE `sic_organization_profiles` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sic_organization_profiles` VALUES
(1,2,1,1,'Org 3','asdsadsa','https://www.instagram.com','Abu Dhabi','Limited Liability Company','Technology',1,'Industry',23,'AED 50m - 100m',1,'draft',NULL,'2026-01-08 21:33:58','2026-01-08 21:49:04'),
(2,2,2,1,'New organization','asdsadsad','https://www,google.com','Abu Dhabi','Limited Liability Company','Technology',1,'Service',231,'&lt; AED 50 million',1,'draft',NULL,'2026-01-08 21:52:00','2026-01-08 21:52:00'),
(3,2,3,1,'IT Max','asds','https://www,google.com','Dubai','Limited Liability Company','Technology',1,'Industry',32,'&lt; AED 50 million',1,'draft',NULL,'2026-01-09 10:26:20','2026-01-09 10:26:20'),
(4,2,4,1,'Browser Org 1','12345678','https://browser-org-1.com','Abu Dhabi','Limited Liability Company','Energy',1,'Industry',4,'&lt; AED 50 million',1,'draft',NULL,'2026-01-10 19:27:29','2026-01-10 19:27:29');
/*!40000 ALTER TABLE `sic_organization_profiles` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sic_organizations`
--

DROP TABLE IF EXISTS `sic_organizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sic_organizations` (
  `organization_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_by_applicant_id` bigint unsigned NOT NULL,
  `canonical_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`organization_id`),
  KEY `idx_org_created_by` (`created_by_applicant_id`),
  CONSTRAINT `fk_org_created_by` FOREIGN KEY (`created_by_applicant_id`) REFERENCES `sic_applicants` (`applicant_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sic_organizations`
--

LOCK TABLES `sic_organizations` WRITE;
/*!40000 ALTER TABLE `sic_organizations` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sic_organizations` VALUES
(1,1,'Org 3','2026-01-08 21:33:58','2026-01-08 23:42:48'),
(2,1,'New organization','2026-01-08 21:52:00','2026-01-08 21:52:00'),
(3,1,'IT Max','2026-01-09 10:26:20','2026-01-09 10:26:20'),
(4,1,'Browser Org 1','2026-01-10 19:27:29','2026-01-10 19:27:29');
/*!40000 ALTER TABLE `sic_organizations` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sic_program_cycles`
--

DROP TABLE IF EXISTS `sic_program_cycles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sic_program_cycles` (
  `cycle_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_id` smallint unsigned NOT NULL,
  `cycle_year` smallint unsigned NOT NULL,
  `cycle_label` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `submission_open_at` datetime DEFAULT NULL,
  `submission_close_at` datetime DEFAULT NULL,
  `terms_version` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `privacy_version` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cycle_id`),
  UNIQUE KEY `uq_program_year` (`program_id`,`cycle_year`),
  KEY `idx_cycle_active` (`program_id`,`is_active`),
  CONSTRAINT `fk_cycles_program` FOREIGN KEY (`program_id`) REFERENCES `sic_programs` (`program_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sic_program_cycles`
--

LOCK TABLES `sic_program_cycles` WRITE;
/*!40000 ALTER TABLE `sic_program_cycles` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sic_program_cycles` VALUES
(1,1,2026,'SIC 2026',0,NULL,NULL,NULL,NULL,'2026-01-07 11:52:25','2026-01-08 20:44:12'),
(2,1,2027,'SIC 2027',1,NULL,NULL,NULL,NULL,'2026-01-07 11:52:25','2026-01-08 20:44:12');
/*!40000 ALTER TABLE `sic_program_cycles` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sic_programs`
--

DROP TABLE IF EXISTS `sic_programs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sic_programs` (
  `program_id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `program_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `program_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`program_id`),
  UNIQUE KEY `uq_program_code` (`program_code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sic_programs`
--

LOCK TABLES `sic_programs` WRITE;
/*!40000 ALTER TABLE `sic_programs` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sic_programs` VALUES
(1,'SIC','SIC','2026-01-07 11:52:25');
/*!40000 ALTER TABLE `sic_programs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sic_project_beneficiaries`
--

DROP TABLE IF EXISTS `sic_project_beneficiaries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sic_project_beneficiaries` (
  `project_id` bigint unsigned NOT NULL,
  `beneficiary_type_id` tinyint unsigned NOT NULL,
  PRIMARY KEY (`project_id`,`beneficiary_type_id`),
  KEY `fk_pb_beneficiary` (`beneficiary_type_id`),
  CONSTRAINT `fk_pb_beneficiary` FOREIGN KEY (`beneficiary_type_id`) REFERENCES `sic_beneficiary_types` (`beneficiary_type_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_pb_project` FOREIGN KEY (`project_id`) REFERENCES `sic_projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sic_project_beneficiaries`
--

LOCK TABLES `sic_project_beneficiaries` WRITE;
/*!40000 ALTER TABLE `sic_project_beneficiaries` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sic_project_beneficiaries` VALUES
(2,1),
(3,1),
(5,1),
(12,1),
(6,2),
(4,3);
/*!40000 ALTER TABLE `sic_project_beneficiaries` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sic_project_files`
--

DROP TABLE IF EXISTS `sic_project_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sic_project_files` (
  `project_id` bigint unsigned NOT NULL,
  `file_role` enum('profile_image','photos','impact_report','sustainable_impact_plan','testimonials_file') COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_id` bigint unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`project_id`,`file_role`),
  UNIQUE KEY `uq_project_file_fileid` (`file_id`),
  CONSTRAINT `fk_project_files_file` FOREIGN KEY (`file_id`) REFERENCES `sic_files` (`file_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_project_files_project` FOREIGN KEY (`project_id`) REFERENCES `sic_projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sic_project_files`
--

LOCK TABLES `sic_project_files` WRITE;
/*!40000 ALTER TABLE `sic_project_files` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sic_project_files` VALUES
(1,'profile_image',10,'2026-01-08 22:17:31'),
(2,'profile_image',11,'2026-01-08 22:17:52'),
(3,'profile_image',12,'2026-01-09 10:02:17'),
(3,'photos',13,'2026-01-09 10:02:52'),
(3,'impact_report',14,'2026-01-09 10:02:52'),
(3,'sustainable_impact_plan',16,'2026-01-09 10:02:52'),
(3,'testimonials_file',15,'2026-01-09 10:02:52'),
(4,'profile_image',19,'2026-01-09 11:01:18'),
(4,'photos',33,'2026-01-10 12:12:16'),
(4,'impact_report',34,'2026-01-10 12:12:16'),
(4,'sustainable_impact_plan',36,'2026-01-10 12:12:16'),
(4,'testimonials_file',35,'2026-01-10 12:12:16'),
(5,'profile_image',28,'2026-01-10 11:47:57'),
(5,'photos',29,'2026-01-10 11:48:14'),
(5,'impact_report',30,'2026-01-10 11:48:14'),
(5,'sustainable_impact_plan',32,'2026-01-10 11:48:14'),
(5,'testimonials_file',31,'2026-01-10 11:48:14'),
(6,'profile_image',37,'2026-01-10 12:13:47'),
(6,'photos',38,'2026-01-10 12:14:11'),
(6,'impact_report',39,'2026-01-10 12:14:11'),
(6,'sustainable_impact_plan',41,'2026-01-10 12:14:11'),
(6,'testimonials_file',40,'2026-01-10 12:14:11'),
(10,'profile_image',44,'2026-01-10 19:40:53'),
(11,'profile_image',45,'2026-01-10 19:41:21'),
(12,'profile_image',46,'2026-01-10 19:42:26');
/*!40000 ALTER TABLE `sic_project_files` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sic_project_impact_areas`
--

DROP TABLE IF EXISTS `sic_project_impact_areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sic_project_impact_areas` (
  `project_id` bigint unsigned NOT NULL,
  `impact_area_id` tinyint unsigned NOT NULL,
  PRIMARY KEY (`project_id`,`impact_area_id`),
  KEY `fk_pia_area` (`impact_area_id`),
  CONSTRAINT `fk_pia_area` FOREIGN KEY (`impact_area_id`) REFERENCES `sic_impact_areas` (`impact_area_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_pia_project` FOREIGN KEY (`project_id`) REFERENCES `sic_projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sic_project_impact_areas`
--

LOCK TABLES `sic_project_impact_areas` WRITE;
/*!40000 ALTER TABLE `sic_project_impact_areas` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sic_project_impact_areas` VALUES
(2,1),
(3,1),
(4,1),
(6,1),
(7,1),
(8,1),
(12,1),
(7,2),
(8,2);
/*!40000 ALTER TABLE `sic_project_impact_areas` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sic_project_links`
--

DROP TABLE IF EXISTS `sic_project_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sic_project_links` (
  `link_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `link_role` enum('testimonials_media_coverage') COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`link_id`),
  KEY `idx_links_project` (`project_id`),
  CONSTRAINT `fk_links_project` FOREIGN KEY (`project_id`) REFERENCES `sic_projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sic_project_links`
--

LOCK TABLES `sic_project_links` WRITE;
/*!40000 ALTER TABLE `sic_project_links` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `sic_project_links` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sic_project_sdgs`
--

DROP TABLE IF EXISTS `sic_project_sdgs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sic_project_sdgs` (
  `project_id` bigint unsigned NOT NULL,
  `sdg_id` tinyint unsigned NOT NULL,
  PRIMARY KEY (`project_id`,`sdg_id`),
  KEY `fk_psdg_sdg` (`sdg_id`),
  CONSTRAINT `fk_psdg_project` FOREIGN KEY (`project_id`) REFERENCES `sic_projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_psdg_sdg` FOREIGN KEY (`sdg_id`) REFERENCES `sic_sdgs` (`sdg_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sic_project_sdgs`
--

LOCK TABLES `sic_project_sdgs` WRITE;
/*!40000 ALTER TABLE `sic_project_sdgs` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sic_project_sdgs` VALUES
(2,1),
(4,1),
(5,1),
(2,2),
(4,2),
(5,2),
(2,5),
(3,5),
(4,5),
(5,5),
(3,6),
(6,10),
(12,10);
/*!40000 ALTER TABLE `sic_project_sdgs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sic_projects`
--

DROP TABLE IF EXISTS `sic_projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sic_projects` (
  `project_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cycle_id` bigint unsigned NOT NULL,
  `org_profile_id` bigint unsigned NOT NULL,
  `created_by_applicant_id` bigint unsigned NOT NULL,
  `project_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_stage` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_description` text COLLATE utf8mb4_unicode_ci,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `total_beneficiaries_targeted` int unsigned DEFAULT NULL,
  `total_beneficiaries_reached` int unsigned DEFAULT NULL,
  `contributes_env_social` enum('yes','no','unknown') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_governance_monitoring` enum('yes','no','unknown') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_search_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_address` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_place_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_provider` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `leadership_women_pct` decimal(5,2) DEFAULT NULL,
  `team_women_pct` decimal(5,2) DEFAULT NULL,
  `leadership_pod_pct` decimal(5,2) DEFAULT NULL,
  `team_pod_pct` decimal(5,2) DEFAULT NULL,
  `team_youth_pct` decimal(5,2) DEFAULT NULL,
  `engages_youth` enum('yes','no','unknown') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `involves_influencers` enum('yes','no','unknown') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submission_status` enum('draft','submitted','locked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `profile_completed` tinyint(1) NOT NULL DEFAULT '0',
  `details_completed` tinyint(1) NOT NULL DEFAULT '0',
  `evidence_completed` tinyint(1) NOT NULL DEFAULT '0',
  `pinpoint_completed` tinyint(1) NOT NULL DEFAULT '0',
  `demographics_completed` tinyint(1) NOT NULL DEFAULT '0',
  `disclaimer_accepted` tinyint(1) NOT NULL DEFAULT '0',
  `disclaimer_accepted_at` timestamp NULL DEFAULT NULL,
  `terms_version` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `privacy_version` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `locked_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`project_id`),
  KEY `idx_projects_cycle` (`cycle_id`),
  KEY `idx_projects_profile` (`org_profile_id`),
  KEY `idx_projects_status` (`submission_status`),
  KEY `fk_projects_profile_cycle` (`org_profile_id`,`cycle_id`),
  KEY `fk_projects_created_by` (`created_by_applicant_id`),
  CONSTRAINT `fk_projects_created_by` FOREIGN KEY (`created_by_applicant_id`) REFERENCES `sic_applicants` (`applicant_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_projects_cycle` FOREIGN KEY (`cycle_id`) REFERENCES `sic_program_cycles` (`cycle_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_projects_profile_cycle` FOREIGN KEY (`org_profile_id`, `cycle_id`) REFERENCES `sic_organization_profiles` (`org_profile_id`, `cycle_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sic_projects`
--

LOCK TABLES `sic_projects` WRITE;
/*!40000 ALTER TABLE `sic_projects` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sic_projects` VALUES
(1,2,1,1,'asdsadasd','Planned','sadasdsad','2026-01-30','2026-02-05',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'draft',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,'2026-01-08 22:17:31','2026-01-08 22:17:31'),
(2,2,1,1,'sadasd','In Progress','asdasdsa','2026-01-29','2026-02-05',321,3123,'yes','yes','IT Max Global DMCC, Unit 804 - Dubai - United Arab Emirates','Unit 804, Reef Tower - Al Thanyah Fifth - Jumeirah Lakes Towers - Dubai - United Arab Emirates','ChIJV9FqPqZsXz4Rl7fRn35E3Mg',NULL,25.0740328,55.1434116,21.00,21.00,12.00,12.00,12.00,'yes','yes','submitted',1,1,1,1,1,0,NULL,NULL,NULL,NULL,NULL,'2026-01-08 22:17:52','2026-01-08 23:09:03'),
(3,2,1,1,'sadasdasd','In Progress','sadasdsad','2026-01-29','2026-02-06',32,23,'yes','yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'draft',0,1,1,0,0,0,NULL,NULL,NULL,NULL,NULL,'2026-01-09 10:02:17','2026-01-09 10:02:52'),
(4,2,2,1,'asdasd','In Progress','asdsad','2026-01-22','2026-01-29',0,0,'yes','','Beyond Logistics Solutions, 7th Ave, Glen marais, Kempton Park, South Africa','12 7th Ave, Glen marais, Kempton Park, 1619, South Africa','ChIJl5HZedYUlR4RkBRfN4LmkzY',NULL,-26.0738950,28.2504584,2.00,2.00,3.00,3.00,0.04,'yes','yes','submitted',1,1,1,1,1,0,NULL,NULL,NULL,NULL,NULL,'2026-01-09 11:01:18','2026-01-10 12:12:47'),
(5,2,1,1,'sadasd','Planned','sadasdasd','2026-01-28','2026-02-04',22,123,'yes','yes','Beyond Logics Inc, Main Road PGECHS, Block A5 Block A 5 PGECHS, Lahore, Pakistan','18-A5 Main Road PGECHS, Block A5 Block A 5 PGECHS, Lahore, 54000, Pakistan','ChIJVVrNmnRXHzkRkFY4YvyqmiE',NULL,31.4435880,74.2830933,99.94,99.98,99.98,100.00,0.96,'yes','yes','submitted',1,1,1,1,1,0,NULL,NULL,NULL,NULL,NULL,'2026-01-10 11:02:27','2026-01-10 12:05:52'),
(6,2,3,1,'New project','Planned','sadsadasd','2026-01-29','2026-02-07',0,0,'yes','','IT Max Global DMCC, Unit 804 - Dubai - United Arab Emirates','Unit 804, Reef Tower - Al Thanyah Fifth - Jumeirah Lakes Towers - Dubai - United Arab Emirates','ChIJV9FqPqZsXz4Rl7fRn35E3Mg',NULL,25.0740328,55.1434116,1.00,1.00,1.00,1.00,0.97,'yes','yes','submitted',1,1,1,1,1,0,NULL,NULL,NULL,NULL,NULL,'2026-01-10 12:13:47','2026-01-10 12:14:42'),
(7,2,1,1,'Browser Project 1','Planned','This is a description for Browser Project 1.','2026-01-10','2026-01-10',100,100,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'draft',0,1,0,0,0,0,NULL,NULL,NULL,NULL,NULL,'2026-01-10 18:49:19','2026-01-10 18:50:25'),
(8,2,1,1,'Browser Project 1','Planned','Description for Browser Project 1','2026-01-10','2026-01-10',0,0,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'draft',0,1,0,0,0,0,NULL,NULL,NULL,NULL,NULL,'2026-01-10 19:00:29','2026-01-10 19:00:53'),
(9,2,1,1,'Browser Project 1','Planned','Details','2026-01-10','2026-01-10',0,0,'','','','',NULL,NULL,NULL,NULL,25.00,25.00,25.00,25.00,25.00,'','','submitted',1,1,1,1,1,0,NULL,NULL,NULL,NULL,NULL,'2026-01-10 19:04:21','2026-01-10 19:15:05'),
(10,2,1,1,'Browser Project 1','Planned','sadasd','2026-01-08','2026-01-14',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'draft',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,'2026-01-10 19:40:53','2026-01-10 19:40:53'),
(11,2,1,1,'asdasd','In Progress','asdsad','2026-01-06','2026-01-19',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'draft',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,'2026-01-10 19:41:21','2026-01-10 19:41:21'),
(12,2,2,1,'New project','Planned','asdasd','2026-02-05','2026-02-06',0,0,'','','','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'draft',0,1,1,1,0,0,NULL,NULL,NULL,NULL,NULL,'2026-01-10 19:42:26','2026-01-10 19:53:18');
/*!40000 ALTER TABLE `sic_projects` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sic_sdgs`
--

DROP TABLE IF EXISTS `sic_sdgs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sic_sdgs` (
  `sdg_id` tinyint unsigned NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`sdg_id`),
  UNIQUE KEY `uq_sdg_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sic_sdgs`
--

LOCK TABLES `sic_sdgs` WRITE;
/*!40000 ALTER TABLE `sic_sdgs` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sic_sdgs` VALUES
(7,'Affordable and Clean Energy'),
(6,'Clean Water and Sanitation'),
(13,'Climate Action'),
(8,'Decent Work and Economic Growth'),
(5,'Gender Equality'),
(3,'Good Health and Well-being'),
(9,'Industry, Innovation and Infrastructure'),
(14,'Life Below Water'),
(15,'Life on Land'),
(1,'No Poverty'),
(17,'Partnerships for the Goals'),
(16,'Peace, Justice and Strong Institutions'),
(4,'Quality Education'),
(10,'Reduced Inequalities'),
(12,'Responsible Consumption and Production'),
(11,'Sustainable Cities and Communities'),
(2,'Zero Hunger');
/*!40000 ALTER TABLE `sic_sdgs` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-01-11 13:52:04
