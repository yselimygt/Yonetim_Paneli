-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: innomis-nexpanel
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
-- Table structure for table `alumni_jobs`
--

DROP TABLE IF EXISTS `alumni_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alumni_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `posted_by_alumni_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `job_type` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `apply_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumni_jobs`
--

LOCK TABLES `alumni_jobs` WRITE;
/*!40000 ALTER TABLE `alumni_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `alumni_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alumni_messages`
--

DROP TABLE IF EXISTS `alumni_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alumni_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `sender_user_id` int(11) DEFAULT NULL,
  `receiver_alumni_id` int(11) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumni_messages`
--

LOCK TABLES `alumni_messages` WRITE;
/*!40000 ALTER TABLE `alumni_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `alumni_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alumni_profiles`
--

DROP TABLE IF EXISTS `alumni_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alumni_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `university` varchar(255) DEFAULT NULL,
  `major` varchar(255) DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `graduation_year` int(11) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `is_mentor` tinyint(1) DEFAULT 0,
  `is_spotlight` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumni_profiles`
--

LOCK TABLES `alumni_profiles` WRITE;
/*!40000 ALTER TABLE `alumni_profiles` DISABLE KEYS */;
INSERT INTO `alumni_profiles` VALUES (1,33,6,3,'Arda â”œÃ»zer','arda@innomis.tr',NULL,NULL,NULL,NULL,'2026-07-01 17:29:08',NULL,NULL,NULL,NULL,NULL,NULL,0,0);
/*!40000 ALTER TABLE `alumni_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `is_pinned` tinyint(1) DEFAULT 0,
  `is_global` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `apps` (`id`) ON DELETE CASCADE,
  CONSTRAINT `announcements_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
INSERT INTO `announcements` VALUES (3,9,6,2,'fdsasfasd','fasdfasdfasfasdffasd',0,0,'2026-07-01 18:52:42'),(4,9,6,2,'cz<zxc','cz<c<zc zv<z bcxvzc< zcx',0,0,'2026-07-01 18:52:54'),(5,9,6,2,'bb nxcb vc xbvc','xbvc xbvcxbv cxbvc bxvc',0,0,'2026-07-01 18:53:11'),(6,9,6,2,'xc xcvcvbc','cbv xbvcbzvbb bxv',0,0,'2026-07-01 18:53:14');
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_agile_sprints`
--

DROP TABLE IF EXISTS `app_agile_sprints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_agile_sprints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_agile_sprints`
--

LOCK TABLES `app_agile_sprints` WRITE;
/*!40000 ALTER TABLE `app_agile_sprints` DISABLE KEYS */;
INSERT INTO `app_agile_sprints` VALUES (1,22,6,'fsdgdsf','2026-08-09','2026-08-13',1,'2026-07-01 17:16:46'),(2,22,6,'ghdfsgdsf','2026-07-17','2026-08-05',0,'2026-07-01 17:17:05');
/*!40000 ALTER TABLE `app_agile_sprints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_agile_task_comments`
--

DROP TABLE IF EXISTS `app_agile_task_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_agile_task_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_agile_task_comments`
--

LOCK TABLES `app_agile_task_comments` WRITE;
/*!40000 ALTER TABLE `app_agile_task_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `app_agile_task_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_agile_tasks`
--

DROP TABLE IF EXISTS `app_agile_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_agile_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `sprint_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'backlog',
  `sort_order` int(11) DEFAULT 0,
  `assignee_id` int(11) DEFAULT NULL,
  `priority` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_agile_tasks`
--

LOCK TABLES `app_agile_tasks` WRITE;
/*!40000 ALTER TABLE `app_agile_tasks` DISABLE KEYS */;
INSERT INTO `app_agile_tasks` VALUES (1,22,6,1,'fgdsgsdf','','backlog',0,2,'normal','2',1,'2026-07-01 17:16:54'),(2,22,6,2,'gdsfgsd','','todo',0,NULL,'normal','2',1,'2026-07-01 17:17:14');
/*!40000 ALTER TABLE `app_agile_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_applications`
--

DROP TABLE IF EXISTS `app_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `university` varchar(255) DEFAULT NULL,
  `faculty` varchar(255) DEFAULT NULL,
  `form_data` text DEFAULT NULL,
  `status` enum('pending','reviewing','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_applications`
--

LOCK TABLES `app_applications` WRITE;
/*!40000 ALTER TABLE `app_applications` DISABLE KEYS */;
INSERT INTO `app_applications` VALUES (1,'management','Yavuz Selim Yiâ”€ÅŸit','mail@yavuzselimyigit.com','05355961046','â”€â–‘ktisadi ve â”€â–‘dari Bilimler','Bilgisayar Mâ”œâ•h','{\n  \"name\": \"Yavuz Selim Yiâ”€ÅŸit\",\n  \"phone\": \"05355961046\",\n  \"email\": \"mail@yavuzselimyigit.com\",\n  \"city\": \"Konya/Selâ”œÄŸuklu\",\n  \"faculty\": \"â”€â–‘ktisadi ve â”€â–‘dari Bilimler\",\n  \"department\": \"Bilgisayar Mâ”œâ•h\",\n  \"grade\": \"Hazâ”€â–’rlâ”€â–’k\",\n  \"studentId\": \"2435234345\",\n  \"targetTeam\": \"Ar-Ge ve Teknoloji\",\n  \"motivation\": \"\",\n  \"securityCode\": \"15\",\n  \"agreed\": true,\n  \"hasSocialMediaExp\": \"\",\n  \"designHistory\": \"\",\n  \"postSeriesIdea\": \"\",\n  \"socialPlatforms\": [],\n  \"designTools\": [],\n  \"portfolio\": null,\n  \"reelsIdea\": \"\",\n  \"critiqueInnomis\": \"\",\n  \"weeklyHours\": \"\",\n  \"smMotivation\": \"\",\n  \"hrCommunication\": \"\",\n  \"hrReporting\": \"\",\n  \"hrOfficeSkills\": \"\",\n  \"dailyHours\": \"\",\n  \"hasClubExp\": \"\",\n  \"clubExpDetails\": \"\",\n  \"hrMotivation\": \"\",\n  \"additionalNotesHR\": \"\",\n  \"crMailCommunication\": \"\",\n  \"crProcessManagement\": \"\",\n  \"crRepresentationScore\": \"\",\n  \"additionalNotesCR\": \"\",\n  \"orgHasExp\": \"\",\n  \"orgExpDetails\": \"\",\n  \"orgCrisisManagement\": \"\",\n  \"orgMultitasking\": \"\",\n  \"orgEventType\": \"\",\n  \"orgEventIdea\": \"\",\n  \"orgTimeCommitment\": \"\",\n  \"orgEventDesign\": \"\",\n  \"orgCommunication\": \"\",\n  \"additionalNotesOrg\": \"\",\n  \"argeTools\": \"fdas\",\n  \"argePastProjectsDetails\": \"fasd\",\n  \"argeContribution\": \"asfd\",\n  \"argeTubitak\": \"Evet, dâ”œâ•â”¼ÅŸâ”œâ•nâ”œâ•râ”œâ•m\",\n  \"argeWebsiteCritique\": \"dfsa\",\n  \"argeFileUpload\": null\n}','pending','2026-07-01 20:59:31'),(2,'representative','Yavuz Selim Yiâ”€ÅŸit','mail@yavuzselimyigit.com','05355961046','Selâ”œÄŸuk â”œÂ£niversitesi','Mâ”œâ•hendislik Fakâ”œâ•ltesi','{\n  \"name\": \"Yavuz Selim Yiâ”€ÅŸit\",\n  \"phone\": \"05355961046\",\n  \"email\": \"mail@yavuzselimyigit.com\",\n  \"city\": \"Konya/Selâ”œÄŸuklu\",\n  \"faculty\": \"Mâ”œâ•hendislik Fakâ”œâ•ltesi\",\n  \"department\": \"Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi\",\n  \"grade\": \"3\",\n  \"studentId\": \"2435234345\",\n  \"university\": \"Selâ”œÄŸuk â”œÂ£niversitesi\",\n  \"pastCommunityExp\": \"bvxvcbxbvc\",\n  \"howDidYouHear\": \"Sosyal Medya (Instagram vb.)\",\n  \"motivation\": \"cbvxbc\",\n  \"securityCode\": \"7\",\n  \"agreed\": true\n}','pending','2026-07-01 21:17:05'),(3,'management','Yavuz Selim Yiâ”€ÅŸit','mail@yavuzselimyigit.com','05355961046','Selâ”œÄŸuk â”œÂ£niversitesi','Mâ”œâ•hendislik','{\n  \"name\": \"Yavuz Selim Yiâ”€ÅŸit\",\n  \"phone\": \"05355961046\",\n  \"email\": \"mail@yavuzselimyigit.com\",\n  \"city\": \"Konya/Selâ”œÄŸuklu\",\n  \"faculty\": \"Mâ”œâ•hendislik\",\n  \"department\": \"YBS\",\n  \"grade\": \"1\",\n  \"studentId\": \"2435234345\",\n  \"targetTeam\": \"Organizasyon\",\n  \"motivation\": \"\",\n  \"securityCode\": \"10\",\n  \"agreed\": true,\n  \"hasSocialMediaExp\": \"\",\n  \"designHistory\": \"\",\n  \"postSeriesIdea\": \"\",\n  \"socialPlatforms\": [],\n  \"designTools\": [],\n  \"portfolio\": null,\n  \"reelsIdea\": \"\",\n  \"critiqueInnomis\": \"\",\n  \"weeklyHours\": \"\",\n  \"smMotivation\": \"\",\n  \"hrCommunication\": \"\",\n  \"hrReporting\": \"\",\n  \"hrOfficeSkills\": \"\",\n  \"dailyHours\": \"\",\n  \"hasClubExp\": \"\",\n  \"clubExpDetails\": \"\",\n  \"hrMotivation\": \"\",\n  \"additionalNotesHR\": \"\",\n  \"crMailCommunication\": \"\",\n  \"crProcessManagement\": \"\",\n  \"crRepresentationScore\": \"\",\n  \"additionalNotesCR\": \"\",\n  \"orgHasExp\": \"Evet\",\n  \"orgExpDetails\": \"jkhktkjggjhkgk\",\n  \"orgCrisisManagement\": \"gjhkghkkjghgjhk\",\n  \"orgMultitasking\": \"gkjhghkghkj\",\n  \"orgEventType\": \"gjhkghjkgjhk\",\n  \"orgEventIdea\": \"gkjhgkjhghkj\",\n  \"orgTimeCommitment\": \"ghkj\",\n  \"orgEventDesign\": \"gkhj\",\n  \"orgCommunication\": \"ghjk\",\n  \"additionalNotesOrg\": \"hkj\",\n  \"argeTools\": \"\",\n  \"argePastProjectsDetails\": \"\",\n  \"argeContribution\": \"\",\n  \"argeTubitak\": \"\",\n  \"argeWebsiteCritique\": \"\",\n  \"argeFileUpload\": null\n}','pending','2026-07-01 21:23:25');
/*!40000 ALTER TABLE `app_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_audit_notes`
--

DROP TABLE IF EXISTS `app_audit_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_audit_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_audit_notes`
--

LOCK TABLES `app_audit_notes` WRITE;
/*!40000 ALTER TABLE `app_audit_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `app_audit_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_audit_reports`
--

DROP TABLE IF EXISTS `app_audit_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_audit_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `reporter_id` int(11) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_audit_reports`
--

LOCK TABLES `app_audit_reports` WRITE;
/*!40000 ALTER TABLE `app_audit_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `app_audit_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_bootcamps`
--

DROP TABLE IF EXISTS `app_bootcamps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_bootcamps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `instructor` varchar(255) DEFAULT NULL,
  `quota` int(11) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_completed` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_bootcamps`
--

LOCK TABLES `app_bootcamps` WRITE;
/*!40000 ALTER TABLE `app_bootcamps` DISABLE KEYS */;
INSERT INTO `app_bootcamps` VALUES (1,2,6,'gfddsgdsf dsgf dsgsdgf dgf','physical','fdsafasdfasd','rafgsgadsgffaadsdfsg<gfdgdsasaOUGasgdougaduoaSDOUHAOHDohaoâ”€â–’hsâ”€â–’oadhfâ”€â–’hasfasdf\r\nasdhf\r\nasfdo\r\nasfd\r\nâ”€ÅŸsodjfâ”€ÅŸdjfaoâ”€ÅŸdajfsoâ”€ÅŸsadjfojâ”€ÅŸdfa\r\na-fsj\r\nasf\r\nasfjâ”€ÅŸojfasfjasjfâ”€ÅŸ\r\nsafsfgadagsgdf','2026-07-01','2026-07-16','sfzdfdsa',345,'https://viperadev.com/','UI/UX Tasarâ”€â–’m','uploads/bootcamps/bc_6a4505ca15c44.jpeg',1,'2','2026-07-01 12:19:22',0),(2,2,6,'Web Geliâ”¼ÅŸtirme Kampâ”€â–’ 2026','Eâ”€ÅŸitim','Online','Modern web eâ”€ÅŸitim programâ”€â–’.','2026-07-01','2026-07-15',NULL,NULL,NULL,NULL,NULL,1,'2','2026-07-01 13:48:59',0);
/*!40000 ALTER TABLE `app_bootcamps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_budget_requests`
--

DROP TABLE IF EXISTS `app_budget_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_budget_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `requester_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_budget_requests`
--

LOCK TABLES `app_budget_requests` WRITE;
/*!40000 ALTER TABLE `app_budget_requests` DISABLE KEYS */;
INSERT INTO `app_budget_requests` VALUES (1,30,6,2,'dgdfghnhdf',645645645,'TRY','ndhgfnhssdfnb s sfdfsg rgws ssbc bxcxvcvxc xvcxvc vxb','rejected','2026-07-01 17:13:51'),(2,30,6,2,'hgdffdgh',44646,'USD','ds shd sds hfdsghf ffd hfdh','director_approved','2026-07-01 17:14:08');
/*!40000 ALTER TABLE `app_budget_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_bylaw_documents`
--

DROP TABLE IF EXISTS `app_bylaw_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_bylaw_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_bylaw_documents`
--

LOCK TABLES `app_bylaw_documents` WRITE;
/*!40000 ALTER TABLE `app_bylaw_documents` DISABLE KEYS */;
INSERT INTO `app_bylaw_documents` VALUES (1,35,1,'gfdsdgsdfg','dsfgdsfgdsfg','2026-07-01 17:54:32',1),(2,35,1,'fgdsg','dsfgsdfgsd','2026-07-01 17:54:49',1);
/*!40000 ALTER TABLE `app_bylaw_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_bylaw_versions`
--

DROP TABLE IF EXISTS `app_bylaw_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_bylaw_versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) DEFAULT NULL,
  `version_number` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `changes_summary` text DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 1,
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_bylaw_versions`
--

LOCK TABLES `app_bylaw_versions` WRITE;
/*!40000 ALTER TABLE `app_bylaw_versions` DISABLE KEYS */;
INSERT INTO `app_bylaw_versions` VALUES (1,1,'v1.0','sdfggsdgdf\r\ndfgdsgdsfg\r\ngdfsdfgdsg\r\ndsfgdsfg\r\ndfgdsfg\r\ndsfg\r\ndfgs','â”€â–‘lk versiyon',0,'2','2026-07-01 17:54:32'),(2,2,'v1.0','gfsdfgsdfgsddgf\r\ndfsg\r\ndsg\r\ndsgf\r\nfgds\r\ndsfg\r\ndsfg\r\ndsfgdsgfdsfg\r\ndsfg','â”€â–‘lk versiyon',1,'2','2026-07-01 17:54:49'),(3,1,'hgdfhdhd','sdfggsdgdf\r\ndfgdsgdsfg\r\ngdfsdfgdsg\r\ndsfgdsfg\r\ndfgdsfg\r\ndsfg\r\ndfgs\r\ngf\r\ndh\r\nfh\r\ndghf\r\ndfhg\r\n\r\ndfh\r\ndhfg','gfhdfhfg',1,'2','2026-07-01 17:55:13');
/*!40000 ALTER TABLE `app_bylaw_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_certificate_templates`
--

DROP TABLE IF EXISTS `app_certificate_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_certificate_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `template_type` varchar(255) DEFAULT NULL,
  `background_path` varchar(255) DEFAULT NULL,
  `canvas_width` varchar(255) DEFAULT NULL,
  `canvas_height` varchar(255) DEFAULT NULL,
  `fields_json` varchar(255) DEFAULT NULL,
  `linked_form_app_id` int(11) DEFAULT NULL,
  `form_field_map` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_certificate_templates`
--

LOCK TABLES `app_certificate_templates` WRITE;
/*!40000 ALTER TABLE `app_certificate_templates` DISABLE KEYS */;
INSERT INTO `app_certificate_templates` VALUES (1,17,6,'dsafsd fa','','participation','','1200','850','[{\"key\":\"recipient_name\",\"text\":\"{{recipient_name}}\",\"x\":0,\"y\":5.2941176470588225,\"fontSize\":56,\"color\":\"#1a1a2e\",\"align\":\"center\",\"bold\":true,\"italic\":false,\"width\":70,\"_color\":\"#f59e0b\"},{\"key\":\"recipient_name\",\"text\":\"{{recipient_name}}\",\"x\":0.91666666',NULL,'{}',0,'2','2026-07-01 17:50:09');
/*!40000 ALTER TABLE `app_certificate_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_certificates`
--

DROP TABLE IF EXISTS `app_certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_certificates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) DEFAULT NULL,
  `app_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `recipient_name` varchar(255) DEFAULT NULL,
  `recipient_email` varchar(255) DEFAULT NULL,
  `field_values` varchar(255) DEFAULT NULL,
  `certificate_no` varchar(255) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_certificates`
--

LOCK TABLES `app_certificates` WRITE;
/*!40000 ALTER TABLE `app_certificates` DISABLE KEYS */;
INSERT INTO `app_certificates` VALUES (1,1,17,6,'fdassa asdf','','{}','DWGGWAFOGEIV9CIB','2026-07-01',NULL,'',0,'2','2026-07-01 17:50:28');
/*!40000 ALTER TABLE `app_certificates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_directory`
--

DROP TABLE IF EXISTS `app_directory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_directory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `full_name` varchar(200) NOT NULL,
  `company` varchar(200) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `emails` text DEFAULT NULL,
  `phones` text DEFAULT NULL,
  `links` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `app_directory_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `apps` (`id`) ON DELETE CASCADE,
  CONSTRAINT `app_directory_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_directory`
--

LOCK TABLES `app_directory` WRITE;
/*!40000 ALTER TABLE `app_directory` DISABLE KEYS */;
INSERT INTO `app_directory` VALUES (1,11,6,2,'asdfasdf sad f','das fas dfas','sd fasf','[\"yuf@sad.hk\",\"dfsfasvasdfvs@sdf\"]','[\"242131243124\",\"24131241243\"]','[\"https:\\/\\/sadfasfafs.sdfav\",\"https:\\/\\/sadfasfafvcdssafds.sdfav\",\"https:\\/\\/afsdasdfsdfvfvasdfsvdf.sdfav\"]','dsafdasvsafds as agafga fgsaf gasg fags dasgd asd','2026-07-01 17:36:20','2026-07-01 17:36:20');
/*!40000 ALTER TABLE `app_directory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_events`
--

DROP TABLE IF EXISTS `app_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `type` enum('physical','online') NOT NULL,
  `title` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time DEFAULT NULL,
  `location_or_platform` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_global` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  KEY `department_id` (`department_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `app_events_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `apps` (`id`) ON DELETE CASCADE,
  CONSTRAINT `app_events_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `app_events_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_events`
--

LOCK TABLES `app_events` WRITE;
/*!40000 ALTER TABLE `app_events` DISABLE KEYS */;
INSERT INTO `app_events` VALUES (1,1,6,'physical','sa asfd asdf','2026-07-01','15:29:00','dsaf fasd adfasdf','asf sadfasdf asfasfdsafdasf\r\nasdf asdfsafasfdas fasdf \r\nasfasfsa\r\nfsaf safsaf safsfdas cas asfsf asf asfsasf\r\n asfasfasf\r\nsafdsafasfsaf\r\nsafdasfasfsafsaf fsaf saf safasf','http://localhost/innomis.tr/backend','uploads/events/evt_6a4508d232b90.jpeg','Toplantâ”€â–’',1,2,'2026-07-01 12:32:18',1),(2,1,6,'physical','Geliâ”¼ÅŸtirici Buluâ”¼ÅŸmasâ”€â–’','2026-07-02',NULL,'Ofis','Teknik toplantâ”€â–’.',NULL,NULL,NULL,1,2,'2026-07-01 13:48:59',1);
/*!40000 ALTER TABLE `app_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_idea_comments`
--

DROP TABLE IF EXISTS `app_idea_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_idea_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idea_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_idea_comments`
--

LOCK TABLES `app_idea_comments` WRITE;
/*!40000 ALTER TABLE `app_idea_comments` DISABLE KEYS */;
INSERT INTO `app_idea_comments` VALUES (1,1,2,'fdsas afsa fasf as','2026-07-01 17:24:37'),(2,1,2,'f dsa fas','2026-07-01 17:24:39'),(3,1,2,'sdaff asfasf asfd','2026-07-01 17:24:43');
/*!40000 ALTER TABLE `app_idea_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_idea_votes`
--

DROP TABLE IF EXISTS `app_idea_votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_idea_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idea_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_idea_votes`
--

LOCK TABLES `app_idea_votes` WRITE;
/*!40000 ALTER TABLE `app_idea_votes` DISABLE KEYS */;
INSERT INTO `app_idea_votes` VALUES (1,1,2,'2026-07-01 17:24:34');
/*!40000 ALTER TABLE `app_idea_votes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_ideas`
--

DROP TABLE IF EXISTS `app_ideas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_ideas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_ideas`
--

LOCK TABLES `app_ideas` WRITE;
/*!40000 ALTER TABLE `app_ideas` DISABLE KEYS */;
INSERT INTO `app_ideas` VALUES (1,24,6,2,'dsg fdsdfg','gas s  sds dsdgfgsdf  sgdfsd sdfg sgd','Yazâ”€â–’lâ”€â–’m','pending','2026-07-01 17:24:32');
/*!40000 ALTER TABLE `app_ideas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_inbox_messages`
--

DROP TABLE IF EXISTS `app_inbox_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_inbox_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `sender_name` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('unread','read','archived') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `app_inbox_messages_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `apps` (`id`) ON DELETE CASCADE,
  CONSTRAINT `app_inbox_messages_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_inbox_messages`
--

LOCK TABLES `app_inbox_messages` WRITE;
/*!40000 ALTER TABLE `app_inbox_messages` DISABLE KEYS */;
INSERT INTO `app_inbox_messages` VALUES (1,40,6,'contact','Test','','test@test.com','','','Test','unread','2026-07-01 20:47:49'),(2,40,6,'contact','Yavuz Selim Yiâ”€ÅŸit','','mail@yavuzselimyigit.com','','Anasayfa Bize Ulaâ”¼ÅŸâ”€â–’n','Anasayfa Bize Ulaâ”¼ÅŸâ”€â–’n formu','unread','2026-07-01 20:48:29'),(3,40,6,'contact','Yavuz Selim Yiâ”€ÅŸit','','mail@yavuzselimyigit.com','','Anasayfa Bize Ulaâ”¼ÅŸâ”€â–’n','Anasayfa Bize Ulaâ”¼ÅŸâ”€â–’n','unread','2026-07-01 20:49:19'),(4,40,6,'sponsorship','Yavuz Selim Yiâ”€ÅŸit','ViperaDev','yavuzselimyigit@viperadev.com','05355961046','Ana Sponsorluk','test','unread','2026-07-01 20:49:59'),(5,40,6,'newsletter','Bâ”œâ•lten Abonesi','','sdfsdgf@sfgd.dghf','','','Bâ”œâ•lten aboneliâ”€ÅŸi','unread','2026-07-03 18:58:15');
/*!40000 ALTER TABLE `app_inbox_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_inventory`
--

DROP TABLE IF EXISTS `app_inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `assigned_to` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `purchase_date` date DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_inventory`
--

LOCK TABLES `app_inventory` WRITE;
/*!40000 ALTER TABLE `app_inventory` DISABLE KEYS */;
INSERT INTO `app_inventory` VALUES (1,18,6,'vzxsfgsfdg','Elektronik','','5423523','','',0,'2026-07-01','','uploads/inventory/inv_6a4548f0cdcb2.jpeg','2','2026-07-01 17:05:52');
/*!40000 ALTER TABLE `app_inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_meeting_approvals`
--

DROP TABLE IF EXISTS `app_meeting_approvals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_meeting_approvals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meeting_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_meeting_approvals`
--

LOCK TABLES `app_meeting_approvals` WRITE;
/*!40000 ALTER TABLE `app_meeting_approvals` DISABLE KEYS */;
INSERT INTO `app_meeting_approvals` VALUES (1,1,2,'2026-07-01 18:03:10','2026-07-01 18:03:10'),(3,2,2,'2026-07-01 18:06:34','2026-07-01 18:06:34');
/*!40000 ALTER TABLE `app_meeting_approvals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_meetings`
--

DROP TABLE IF EXISTS `app_meetings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_meetings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `meeting_date` datetime DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `agenda` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) DEFAULT 'upcoming',
  `decisions` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_meetings`
--

LOCK TABLES `app_meetings` WRITE;
/*!40000 ALTER TABLE `app_meetings` DISABLE KEYS */;
INSERT INTO `app_meetings` VALUES (1,38,6,'2','sffsgsdfg','2026-07-01 14:00:00','fdsafdsasdf','sadfsadf asfasd sfad fadsasfd','2026-07-01 18:02:57','completed','fsfdsgdgdsfgsd gdsfg dsfgfdsfgd sgd sfgdf sgfdsgfds gdsfgfdsgdsf\r\nSDGDFSGDFG\r\nGDDSFGD\r\nGDFSGD\r\nFGDF\r\nGDF\r\nGDF\r\nG\r\nDF\r\nDSG'),(2,38,0,'2','vfadsfa dssadfdfs','2026-07-01 14:00:00','','dfsa sasfas fsf a fds','2026-07-01 18:05:29','completed','ll\r\nâ”¼ÅŸl\r\nâ”¼ÅŸ\r\nâ”¼ÅŸlâ”¼ÅŸl\r\nlâ”¼ÅŸ\r\nlâ”¼ÅŸ\r\n\r\ni\r\n\r\nâ”¼ÅŸllâ”¼ÅŸ\r\nâ”¼ÅŸl\r\nil\r\ni\r\nâ”¼ÅŸlâ”¼ÅŸl'),(3,29,6,'2','gfds','2026-07-02 14:00:00','dsfg','dfgs','2026-07-02 19:34:41','completed','fdsdfsadf'),(4,29,6,'2','gfds','2026-07-02 14:00:00','gdf','dsfg','2026-07-02 19:34:52','upcoming',NULL);
/*!40000 ALTER TABLE `app_meetings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_members`
--

DROP TABLE IF EXISTS `app_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `university` varchar(150) DEFAULT NULL,
  `faculty` varchar(150) DEFAULT NULL,
  `department` varchar(150) DEFAULT NULL,
  `grade` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'active',
  `suspend_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_app_email` (`app_id`,`email`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `app_members_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `apps` (`id`) ON DELETE CASCADE,
  CONSTRAINT `app_members_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=652 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_members`
--

LOCK TABLES `app_members` WRITE;
/*!40000 ALTER TABLE `app_members` DISABLE KEYS */;
INSERT INTO `app_members` VALUES (1,12,6,2,'Yavuz Selim','Yiâ”€ÅŸit','mail@yavuzselimyigit.com','24100031005','','','Elektrik Elektronik Mâ”œâ•hendisliâ”€ÅŸi','','0535 596 10 46','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(2,12,6,2,'Arda','â”œÃ»zer','ardaaozerr@gmail.com','23410051013','','','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','','0555 897 83 54','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(3,12,6,2,'Latif Can','Aykurt','latifcanaykurt@gmail.com','23410051032','','','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','','0551 195 08 78','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(4,12,6,2,'â”€â–‘smail','Tetik','tetikismail22@gmail.com','23410051009','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3','555 879 36 02','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(5,12,6,2,'Rukiye','Erdem','rukiyeerdemz0@gmail.com','24410051050','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2','0541 827 39 12','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(6,12,6,2,'Gâ”œâ•lvera Beril','Keser','gulveraberilk@gmail.com','24410051046','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2','0534 835 42 03','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(7,12,6,2,'Hasan Bedirhan','â”œÃ§olak','bedirhancolak19@gmail.com','25100101011','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','1','0545 331 5203','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(8,12,6,2,'Elif','Akyol','elifakyol2007@gmail.com','25410051009','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','0531 742 90 25','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(9,12,6,2,'Sena','Gâ”œâ•ndoâ”€ÅŸan','sena.gundogan112@gmail.com','25410051406','','','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','','0543 664 10 09','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(10,12,6,2,'Kâ”œâ•bra','Demircan','demircankubra06@gmail.com','25410051043','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','0543 100 11 53','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(11,12,6,2,'Zeynep Feyza','Kendirli','feyzakendirli00@gmail.com','25410051408','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2','0552 468 50 51','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(12,12,6,2,'Sude Naz','Koâ”œÄŸak','sudenazkocak02@gmail.com','25410051004','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','0531 282 51 32','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(13,12,6,2,'Kaan','Sevinâ”œÄŸ','kaansvnc307@gmail.com','25410051067','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','0551 196 95 08','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(14,12,6,2,'Hasan Uâ”€ÅŸur','Bostan','hasanbstn26@gmail.com','25410051422','','','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','','0501 630 26 03','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(15,12,6,2,'Sefa Nur','Yâ”€â–’ldâ”€â–’z','sefanuuryildiz@gmail.com','24410051013','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2','0551 943 09 12','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(16,12,6,2,'Hasan','Keleâ”¼ÅŸ','hasokls09@gmail.com','24410051065','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2','0539 373 39 70','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(17,12,6,2,'â”¼Åerife Yâ”œâ•sra','Gâ”œâ•l','serifeygul08@gmail.com','25410051006','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','0533 734 45 04','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(18,12,6,2,'Sevim','Asâ”€â–’l','sevimasill8076@gmail.com','25410031034','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Muhasebe ve Finans Yâ”œÃ‚netimi','1','0552 803 82 15','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(19,12,6,2,'Esra','Aydoâ”€ÅŸdu','esraaydogduuu1@gmail.com','24410051003','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2','0542 502 42 15','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(20,12,6,2,'Deniz','Arslan','denizarslan683@gmail.com','23410051067','','','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','','0530 707 30 94','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(21,12,6,2,'Kadir','Kurtdere','kurtderekadir2004@gmail.com','24410051807','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3','0531 273 28 08','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(22,12,6,2,'Nurhayat','Kaya','kayanurhayat06@gmail.com','24410051039','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2','0552 302 23 71','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(23,12,6,2,'Ertuâ”€ÅŸrul','Akdemir','ertakd2004@gmail.com','24410051804','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2','0546 695 35 86','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(24,12,6,2,'Demet Nur','Sâ”œâ•rel','sdemetnur@gmail.com','25410051432','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2','05456905343','active',NULL,'2026-07-01 18:47:13','2026-07-01 18:47:13'),(25,12,6,2,'Kaan','Soylu','kaanka8176@gmail.com','22410082024','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3','5366915226','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(26,12,6,2,'Ebru','â”œÃ»zkan','ebru51ozkan6@gmail.com','22040081029','','Fen Fakâ”œâ•ltesi','Matematik ve Bilgisayar Bilimleri','3','5538827410','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(27,12,6,2,'â”¼Åerife Nazlâ”€â–’','â”œÂ£nay','unay.nazli42@gmail.com','23100011009','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','2','5437242559','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(28,12,6,2,'Fatma Zehra','Erkul','erkul07kl@gmail.com','24410031067','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Muhasebe ve Finans Yâ”œÃ‚netimi','1','5424298066','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(29,12,6,2,'Enes Talha','Gerâ”œÄŸeker','enesgerceker20042@gmail.com','25410051051','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5511221186','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(30,12,6,2,'Ceylin','Avcâ”€â–’lar','ceylinavcilar42@gmail.com','25100011014','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1','5362338567','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(31,12,6,2,'Emre','Acar','acaremre501@gmail.com','24100101036','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','2','5301318397','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(32,12,6,2,'Nuri Yasin','Yavaâ”¼ÅŸ','42nuriyavas@gmail.com','25100011013','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1','5528804905','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(33,12,6,2,'Beyzanur','Sepetci','beyzanursepetci01@gmail.com','25100121055','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5543187586','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(34,12,6,2,'Nisagâ”œâ•l','Demirtaâ”¼ÅŸ','drnisagul0906@gmail.com','25100011038','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1','5064929634','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(35,12,6,2,'Elif','â”œÃ»ztop','oztopelf05@gmail.com','25100011056','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1','5510852859','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(36,12,6,2,'Fatma Melek','Yalâ”œÄŸâ”€â–’n','fatmamelekyalcin38@gmail.com','21030031020','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Havacâ”€â–’lâ”€â–’k ve Uzay Mâ”œâ•hendisliâ”€ÅŸi','3','5426129773','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(37,12,6,2,'Senanur','Ercan','esenanur2006@gmail.com','25100031030','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','1','5467379070','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(38,12,6,2,'Berrasu','â”œÃ»zkul','Berra.oofical@gmail.com','25410051064','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5454190262','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(39,12,6,2,'â”¼Åevval','Polat','aremziye817@gmail.com','25410051061','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5059142369','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(40,12,6,2,'Hatice','Berk','berkh8359@gmail.com','25410051028','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5523604968','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(41,12,6,2,'Hâ”œâ•seyin Kerim Eren','Tokkacâ”€â–’','eren45tokkac@gmail.com','24100031046','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','2','5459277259','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(42,12,6,2,'Yaâ”€ÅŸmur','Yaka','yagmuryaka44@gmail.com','25410051047','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5525247442','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(43,12,6,2,'Mevlâ”œâ•t','Karaca','mevlutkarac4@gmail.com','24100031028','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','2','5324022635','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(44,12,6,2,'Damla Nur','Gedik','gedikdamlanurr@gmail.com','25100011065','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1','5519396457','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(45,12,6,2,'Yunus Efe','â”€â–‘â”œÄŸli','efegece83@gmail.com','24100031052','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','2','5423417530','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(46,12,6,2,'â”¼Åevval','Aktaâ”¼ÅŸ','sevvalaktas4225@gmail.com','25410051016','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5427725047','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(47,12,6,2,'Erdem','â”œÃ§anakâ”œÄŸâ”€â–’','erdem200513@outlook.com','25040081011','','Fen Fakâ”œâ•ltesi','Matematik ve Bilgisayar Bilimleri','1','5522289384','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(48,12,6,2,'Zeynep Rana','Balâ”œÄŸâ”€â–’k','balcikrana52@gmail.com','25410051430','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3','5359231817','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(49,12,6,2,'â”€â–‘layda','Saâ”€ÅŸar','ilaydasagar@gmail.com','25040081020','','Fen Fakâ”œâ•ltesi','Matematik ve Bilgisayar Bilimleri','1','5459759602','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(50,12,6,2,'Zeynep Elif','â”œÃ»zcan','zo3954393@gmail.com','25410051046','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5061844097','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(51,12,6,2,'Pusat','Yâ”œâ•ksel','pusatyuksel41@gmail.com','25410051037','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5526116358','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(52,12,6,2,'Beraat','Umaâ”œÄŸ','beraatumac@hotmail.com','25410051035','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5449275739','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(53,12,6,2,'Avni Emre','Aâ”œÄŸâ”€â–’kgâ”œÃ‚z','avniemreacikgoz@gmail.com','24030021069','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','1','5510367945','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(54,12,6,2,'Ahmet Yasin','Yayâ”€â–’k','ysnyyk@gmail.com','25020021007','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Felsefe','1','5392978935','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(55,12,6,2,'Beyza Nur','Mert','beyzanurrmert@gmail.com','25720031017','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Beslenme ve Diyetetik','1','5322813278','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(56,12,6,2,'Emrah','Abakan','emrahabakan5@gmail.com','25100091422','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','1','5346004122','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(57,12,6,2,'Mahmut Esat','Yavuz','mahmutesatyavuz@gmail.com','25100091037','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','1','5337048327','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(58,12,6,2,'Emine','Sâ”œÃ‚â”€ÅŸâ”œâ•t','eminesogut70@gmail.com','24020031006','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Halkla â”€â–‘liâ”¼ÅŸkiler ve Reklamcâ”€â–’lâ”€â–’k','2','5370233810','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(59,12,6,2,'Sevim','Emek','emeksevim38@gmail.com','24720031035','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Beslenme ve Diyetetik','2','5519665358','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(60,12,6,2,'Eda Nur','â”œÃ§anga','edanurcanga01@gmail.com','24090111050','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Mimarlâ”€â–’k','2','5442665460','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(61,12,6,2,'Ada','Uâ”œÄŸar','adaucar39@gmail.com','24090111033','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Mimarlâ”€â–’k','2','5468572839','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(62,12,6,2,'Leyla','Dâ”œÃ‚lkeleâ”¼ÅŸ','leyladolkeles19@gmail.com','24100101028','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','2','5443304281','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(63,12,6,2,'Ayâ”¼ÅŸe Beyza','Tombul','aysebeyzat7@gmail.com','24100101032','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','2','5453369195','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(64,12,6,2,'Mâ”œâ•berra','Serin','muberraserin@gmail.com','23030031040','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','2','5512408851','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(65,12,6,2,'Ayâ”¼ÅŸe Nur','Sav','aysenursav@gmail.com','22720031035','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Beslenme ve Diyetetik','4','5434856093','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(66,12,6,2,'Yâ”€â–’ldâ”€â–’râ”€â–’m','Akâ”œÄŸam','yildirimakcam0@gmail.com','24100031048','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','2','5528990709','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(67,12,6,2,'Taha','â”œÃ§oban','tahacoban0635@gmail.com','25100101043','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','1','5372758766','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(68,12,6,2,'Umut','Yâ”€â–’lmaz','umutyilmaztimsah16@icloud.com','25100101024','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','1','5373271190','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(69,12,6,2,'Kerem','Ercan','kercan586@gmail.com','25100101062','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','1','5323264411','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(70,12,6,2,'Bâ”œâ•â”¼ÅŸra','Akyol','busrkyol038@gmail.com','25090111031','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Mimarlâ”€â–’k','1','5468673334','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(71,12,6,2,'Zâ”œâ•lal','â”œÃ»zâ”œÃ‚â”€ÅŸâ”œâ•r','zulalozogur@gmail.com','25090111004','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Mimarlâ”€â–’k','1','5332164110','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(72,12,6,2,'Mislina','Hoâ”¼ÅŸgeâ”œÄŸin','misgecin03@gmail.com','25090111009','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Mimarlâ”€â–’k','1','5457240452','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(73,12,6,2,'Gâ”œÃ‚zde Nur','â”œÃ§imendaâ”€ÅŸ','gozdec36@outlook.com','25090111012','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Mimarlâ”€â–’k','1','5539876906','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(74,12,6,2,'Ahmet','Yeâ”€ÅŸin','ahmetyg1942@gmail.com','24020101015','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Tarih','2','5317462075','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(75,12,6,2,'Gâ”œâ•lsâ”œâ•m','â”€â–‘limler','ilimlergulsum@gmail.com','24090031016','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Grafik Tasarâ”€â–’m','2','5510654767','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(76,12,6,2,'Erman','Erman','ermannn231@gmail.com','24900101032','','Turizm Fakâ”œâ•ltesi','Aâ”¼ÅŸâ”œÄŸâ”€â–’lâ”€â–’k','2','5336171817','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(77,12,6,2,'Samet','Gâ”œâ•ndoâ”€ÅŸar','abdussametgundogar@gmail.com','25410051412','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5320653562','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(78,12,6,2,'Yasemin','Acar','ysmn26062006@gmail.com','25050021014','','Turizm Fakâ”œâ•ltesi','Turizm â”€â–‘â”¼ÅŸletmeciliâ”€ÅŸi','1','5511951492','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(79,12,6,2,'Aysima','Aksoy','aksoyaysima457@gmail.com','25050021022','','Turizm Fakâ”œâ•ltesi','Turizm â”€â–‘â”¼ÅŸletmeciliâ”€ÅŸi','1','5516317389','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(80,12,6,2,'Kâ”œâ•bra','Gâ”œÃ‚kce','kubra0407gokce@icloud.com','25050021020','','Turizm Fakâ”œâ•ltesi','Turizm â”€â–‘â”¼ÅŸletmeciliâ”€ÅŸi','1','5520834897','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(81,12,6,2,'Nazife','â”œÂ£ner','nazifeuner@icloud.com','23090111076','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Mimarlâ”€â–’k','3','5314919972','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(82,12,6,2,'â”¼Åerife','Er','seriifeer@gmail.com','23030062047','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Havacâ”€â–’lâ”€â–’k Yâ”œÃ‚netimi','2','5367422178','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(83,12,6,2,'Berdirhan','Bâ”œâ•yâ”œâ•kdoâ”€ÅŸrucan','bedir543bdc@gmail.com','24100031008','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','2','5435812844','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(84,12,6,2,'Tuanna','Kâ”œÃ‚se','seyrani327@hotmail.com','23050031029','','Turizm Fakâ”œâ•ltesi','Turizm Rehberliâ”€ÅŸi','3','5526161306','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(85,12,6,2,'â”œÃ»mer','Yaâ”¼ÅŸar','oyasar289@gmail.com','25050031008','','Turizm Fakâ”œâ•ltesi','Turizm Rehberliâ”€ÅŸi','1','5510636135','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(86,12,6,2,'Begâ”œâ•m â”¼Åevval','Yeâ”¼ÅŸilova','begumsevval.yesilova@gmail.com','23410122033','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Finans ve Bankacâ”€â–’lâ”€â–’k','3','5318245358','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(87,12,6,2,'â”€â–‘rem','Babaoâ”€ÅŸlan','irembbaoglan22@gmail.com','23720041049','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Saâ”€ÅŸlâ”€â–’k Yâ”œÃ‚netimi','3','5462552846','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(88,12,6,2,'Hacer','â”œÃ»lmez','hacerolmezin@gmail.com','24720031009','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Beslenme ve Diyetetik','2','5532453442','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(89,12,6,2,'Rahim Ceyhun','Kolata','kolataceyhun@gmail.com','24020011019','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Dilbilimi','2','5436764241','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(90,12,6,2,'Abdullah','Mercan','abdullahmercan2004@gmail.com','24030021005','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','1','5060966827','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(91,12,6,2,'Yahya Furkan','â”œÂ£nal','yahyafurkan4205@gmail.com','23020101071','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Tarih','2','552358465','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(92,12,6,2,'Ayâ”¼ÅŸenaz','Yaâ”¼ÅŸar','aysenazyasar894@gmail.com','25410051002','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5537974506','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(93,12,6,2,'Fatma â”œÃ§aâ”€ÅŸanur','Daâ”€ÅŸlâ”€â–’','caganurdagli27@gmail.com','25410051069','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5437782004','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(94,12,6,2,'Zâ”œâ•mrâ”œâ•t','Demirci','demircizumrut63@gmail.com','25410051011','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5411567481','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(95,12,6,2,'Yaâ”€ÅŸmur','Sâ”€â–’cakyâ”œâ•z','yagmursicakyuz38@gmail.com','25410051042','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5541754019','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(96,12,6,2,'Cennet','Tilki','cennettilki40@gmail.com','25410051024','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5528953868','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(97,12,6,2,'Erva','Avcu','aerva9636@gmail.com','25410051036','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5411029737','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(98,12,6,2,'â”œÃ§iâ”€ÅŸdem','Kel','cigdemkel07@gmail.com','25410051053','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5333646027','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(99,12,6,2,'â”¼Åevvalsu','â”œÃ»mâ”œâ•roâ”€ÅŸlu','sevvalsuomroglu@gmail.com','25410051066','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5331472054','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(100,12,6,2,'Kadriye','Keven','kevenkadriye57@gmail.com','25020071060','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Psikoloji','Hazâ”€â–’rlâ”€â–’k','5451754611','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(101,12,6,2,'Meryem','Tanrâ”€â–’kulu','meryemcantanrikulu@gmail.com','25090021007','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Geleneksel Tâ”œâ•rk Sanatlarâ”€â–’','1','5301902650','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(102,12,6,2,'Safiye','Yurdakul','Safiyeyurdakul22@gmail.com','25090021009','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Geleneksel Tâ”œâ•rk Sanatlarâ”€â–’','1','5464423929','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(103,12,6,2,'Aiâ”¼ÅŸe Azra','Dâ”œÃ‚nmez','donmezaiseazra@gmail.com','25410051045','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5524223644','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(104,12,6,2,'â”€â–‘rem Sultan','â”œÃ»zkara','irem.ozk474@gmail.com','24020011026','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Dilbilimi','2','5444832326','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(105,12,6,2,'Gâ”œâ•ray','Gâ”œâ•lgâ”œâ•n','guraygulgun81@gmail.com','25410051428','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3','5519477939','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(106,12,6,2,'Pervin','Oral','pervinoral52@gmail.com','24100131010','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Metalurji ve Malzeme Mâ”œâ•hendisliâ”€ÅŸi','1','5422803180','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(107,12,6,2,'Beyza','Kâ”œâ•â”œÄŸâ”œâ•kuslu','beyzakucukuslu6@gmail.com','24090031018','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Grafik','2','5516338218','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(108,12,6,2,'Hâ”œâ•danur','Yâ”€â–’lmaz','hudanuryilmaz51@gmail.com','23090031032','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Grafik','3','5468972189','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(109,12,6,2,'Arda Kaan','Kar','ardakaankar@gmail.com','25100101048','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','1','5538485833','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(110,12,6,2,'Zehra','Boyraz','zehraboyraz0113@gmail.com','25100101041','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','1','5525402005','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(111,12,6,2,'Hatice Kâ”œâ•bra','Bozok','bozokkubra298@gmail.com','25100101032','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','1','5449611660','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(112,12,6,2,'Hamza','Acar','hamzaacar212715@gmail.com','25100121048','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5447498186','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(113,12,6,2,'â”€â–‘smet','Ay','ismetay0656@gmail.com','25410051417','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5349245430','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(114,12,6,2,'Ekin','Karakaâ”¼ÅŸ','ekinkarakas5858@gmail.com','52090031134','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Grafik','1','5528772248','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(115,12,6,2,'Sâ”€â–’la','Kâ”œÃ‚sem','silakosem@26gmail.com','24100131009','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Metalurji ve Malzeme Mâ”œâ•hendisliâ”€ÅŸi','1','5510049333','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(116,12,6,2,'Muhammed Mustafa','â”œÃ§elik','muhammedmustafac1@gmail.com','25410141002','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Uluslararasâ”€â–’ Ticaret ve Finansman','1','5424134117','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(117,12,6,2,'Seâ”œÄŸil','Nas','nassecil648@gmail.com','25100011079','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1','5511444503','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(118,12,6,2,'Aybâ”œâ•ke','Tenekeci','aybuketenekeci985@gmail.com','25410141054','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Uluslararasâ”€â–’ Ticaret ve Finansman','1','5419033019','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(119,12,6,2,'Meryem','Batu','meryembtu4747@gmail.com','25090031039','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Grafik Tasarâ”€â–’m','1','5435865010','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(120,12,6,2,'Enes Hamza','â”œÃ§il','eneshamzacil2005@gmail.com','24030101004','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Havacâ”€â–’lâ”€â–’k ve Uzay Mâ”œâ•hendisliâ”€ÅŸi','1','5061136603','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(121,12,6,2,'Vakâ”€â–’l','Mamedov','vekilv33@gmail.com','25030101805','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Havacâ”€â–’lâ”€â–’k ve Uzay Mâ”œâ•hendisliâ”€ÅŸi','1','5558372146','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(122,12,6,2,'Nazlâ”€â–’','Karademir','nazli.krdmr.07@gmail.com','25030021411','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5355702316','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(123,12,6,2,'Hilal Daâ”€ÅŸ','Lisans','hilaldag13@gmail.com','24020031025','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Halkla â”€â–‘liâ”¼ÅŸkiler ve Reklamcâ”€â–’lâ”€â–’k','2','5530986072','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(124,12,6,2,'Esma','Gâ”œÃ‚nen','gonenesma220323@icloud.com','24020031019','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Halkla â”€â–‘liâ”¼ÅŸkiler ve Reklamcâ”€â–’lâ”€â–’k','2','5448158824','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(125,12,6,2,'Beyza Nur','Keskin','beyzanurkeeskin@gmail.com','24020031033','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Halkla â”€â–‘liâ”¼ÅŸkiler ve Reklamcâ”€â–’lâ”€â–’k','2','5467789975','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(126,12,6,2,'Halit','Aâ”€ÅŸralâ”€â–’','agrali54@gmail.com','22420031004','','Siyasal Bilgiler Fakâ”œâ•ltesi','â”€â–‘â”¼ÅŸletme','4','5347870642','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(127,12,6,2,'Efran','Kaptan','eniseefranmm@gmail.com','25100121061','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi','','5054591586','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(128,12,6,2,'Gamze Nur','â”œÃ»zkaya','gamzenurozky@gmail.com','22090031024','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Grafik Tasarâ”€â–’m','4','5377026203','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(129,12,6,2,'Beyza','Akmercan','beyzaakmercan302@gmail.com','22090031035','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Grafik Tasarâ”€â–’m','4','5362996042','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(130,12,6,2,'Sude Naz','Denizer','sudenaz.dnzr07@gmail.com','25100121059','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5330524075','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(131,12,6,2,'Eylâ”œâ•l','â”¼Åan','eylulsan672@icloud.com','25020011026','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Dilbilimi','1','5538765224','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(132,12,6,2,'Muhammed Arda','Altâ”€â–’ndaâ”€ÅŸ','muhammedardaaltindag@gmail.com','25100011068','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5421269097','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(133,12,6,2,'Elif','Akman','elfakmn44@gmail.com','23020071071','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Psikoloji','2','5308903893','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(134,12,6,2,'Erva Nur','â”œÂ£zâ”œâ•cek','ervauzucek137@gmail.com','23020071051','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Psikoloji','2','5421488044','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(135,12,6,2,'Zâ”œâ•beyde Sude','â”œÃ»z','ozzubeydesude286@gmail.com','23030062042','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Havacâ”€â–’lâ”€â–’k Yâ”œÃ‚netimi','2','5345637955','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(136,12,6,2,'Rumeysa','Yumul','rumeysayumul@gmail.com','25050011035','','Turizm Fakâ”œâ•ltesi','Gastronomi ve Mutfak Sanatlarâ”€â–’','1','5446064098','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(137,12,6,2,'â”œÂ£mran','Iâ”¼ÅŸâ”€â–’khan','umranisikhan@hotmail.com','23030062040','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Havacâ”€â–’lâ”€â–’k Yâ”œÃ‚netimi','2','5013304705','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(138,12,6,2,'Beyza','â”œÃ§â”€â–’rak','beyzacirak39@gmail.com','23390011050','','Hukuk Fakâ”œâ•ltesi','Hukuk','3','5454657085','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(139,12,6,2,'Hafize','Acâ”€â–’badem','hafierhan@gmail.com','23050021020','','Turizm Fakâ”œâ•ltesi','Turizm â”€â–‘â”¼ÅŸletmeciliâ”€ÅŸi','3','5010860304','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(140,12,6,2,'Dâ”€â–’lâ”€â–’afruz','Nâ”€â–’zamova','nizamovadilafruz55@gmail.com','22050021812','','Turizm Fakâ”œâ•ltesi','Turizm â”€â–‘â”¼ÅŸletmeciliâ”€ÅŸi','3','5054589744','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(141,12,6,2,'Fadim','Acar','acarfadim01@gmail.com','25420011024','','Siyasal Bilgiler Fakâ”œâ•ltesi','â”€â–‘ktisat','1','5314748350','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(142,12,6,2,'Maâ”¼ÅŸallah','Akbaba','masallahakbaba2@gmail.com','24720051056','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Sosyal Hizmet','2','5073794604','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(143,12,6,2,'Elif Sude','Vargeloâ”€ÅŸlu','elifsudevargeloglu@gmail.con','23040051020','','Fen Fakâ”œâ•ltesi','Molekâ”œâ•ler Biyoloji ve Genetik','3','5416970684','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(144,12,6,2,'Hazal','â”œÃ»lmez','olmezhazal5@gmail.com','24100121806','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5520870030','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(145,12,6,2,'â”€â–‘rem Nur â”œÃ»z','Mâ”€ÅŸhend','iremnuroz4601@gmail.com','25100121428','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5511480075','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(146,12,6,2,'Beyza','â”œÃ§almaz','calmazbeyza@gmail.com','23310571013','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Coâ”€ÅŸrafya â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','3','5519544836','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(147,12,6,2,'Gâ”œâ•listan','Karadaâ”¼ÅŸ','gulistankaradas151@gmail.com','23310571016','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Coâ”€ÅŸrafya â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','3','5434826970','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(148,12,6,2,'Songâ”œâ•l','Durmaz','durmazsongul78@gmail.com','23310571017','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Coâ”€ÅŸrafya â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','3','5347148623','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(149,12,6,2,'Dâ”œÃ‚ne','Bâ”€â–’yâ”€â–’klâ”€â–’','donebiyikli866@gmail.com','23310571006','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Coâ”€ÅŸrafya â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','3','5428166302','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(150,12,6,2,'Elif Sena','Eser','eserelifsena182@gmail.com','23440011001','','Hemâ”¼ÅŸirelik Fakâ”œâ•ltesi','Hemâ”¼ÅŸirelik','3','5444502873','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(151,12,6,2,'Selin','Yâ”€â–’lmaz','sy260605@gmail.com','22410141015','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Uluslararasâ”€â–’ Ticaret ve Finansman','4','5455242050','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(152,12,6,2,'Nazlâ”€â–’','Cihat','ncihat99@gmail.com','23100121053','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi','2','5468591504','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(153,12,6,2,'â”¼Åâ”œâ•kran','Emlâ”œâ•k','sukranemluk@gmail.com','22100131007','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Metalurji ve Malzeme Mâ”œâ•hendisliâ”€ÅŸi','2','5414155379','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(154,12,6,2,'Kâ”œâ•bra','Dinâ”œÄŸer','kubranazlid@gmail.com','25410131057','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Lojistik Yâ”œÃ‚netimi','1','5070098583','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(155,12,6,2,'Kader','Erikoâ”€ÅŸlu','5625233568@haydigiy.com','23040081020','','Fen Fakâ”œâ•ltesi','Matematik ve Bilgisayar Bilimleri','3','5309774606','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(156,12,6,2,'Songâ”œâ•l','Acar','acarsongul0609@gmail.com','23040081016','','Fen Fakâ”œâ•ltesi','Matematik ve Bilgisayar Bilimleri','3','5059949588','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(157,12,6,2,'Arif Samet','Aydjn','aydinarifsamet12@gmail.com','21410051091','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3','5340102604','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(158,12,6,2,'Rana Elkâ”€â–’ran','Hemsirelik','rana_elkiran@icloud.com','23440011126','','Hemâ”¼ÅŸirelik Fakâ”œâ•ltesi','Hemâ”¼ÅŸirelik','3','5511448309','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(159,12,6,2,'Melike','Akdaâ”€ÅŸ','akdagmelike6464@gmail.com','23090111015','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Mimarlâ”€â–’k','3','5459550696','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(160,12,6,2,'Sinem','Kâ”œâ•â”œÄŸâ”œâ•ksakal','sineemmkucuksakal@gmail.com','23090111012','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Mimarlâ”€â–’k','3','5011178464','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(161,12,6,2,'Abdâ”œâ•lkadir','Ekinci','akekadir53@gmail.com','25410051038','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5414683966','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(162,12,6,2,'Zeynep','â”œÃ»zcan','zeynepozcan3668@gmail.com','23090111036','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Mimarlâ”€â–’k','3','5314641298','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(163,12,6,2,'Eda','Yapar','yapareda0@gmail.com','23090111074','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Mimarlâ”€â–’k','3','5313831385','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(164,12,6,2,'Havva Sudenaz','â”œÃ»zcan','sude.ozcan.2508@gmail.com','23030062020','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Havacâ”€â–’lâ”€â–’k Yâ”œÃ‚netimi','1','5415895042','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(165,12,6,2,'Cemre','Daâ”€ÅŸ','nuriyedag.0142@gmail.com','25310331025','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Mâ”œâ•zik â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','1','5059706462','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(166,12,6,2,'Bahar Sahra','Sunay','Baharsahram3342@gmail.com','25420091007','','Siyasal Bilgiler Fakâ”œâ•ltesi','Siyaset Bilimi ve Uluslararasâ”€â–’ â”€â–‘liâ”¼ÅŸkiler','Hazâ”€â–’rlâ”€â–’k','5385771206','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(167,12,6,2,'Sena','Mirkan','senamirkan27@gmail.com','25310331023','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Mâ”œâ•zik â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','1','5457394078','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(168,12,6,2,'Sacide Reyyan','Baysal','sacidereyyan@gmail.com','25100131013','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Metalurji ve Malzeme Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5539588750','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(169,12,6,2,'Sema Nur','Karagâ”œÃ‚z','semanurkrgz4161@gmail.com','23310611007','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Tarih â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','3','5394884361','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(170,12,6,2,'Bediha','â”œÃ§evik','bedihacevik8@gmail.com','23310611001','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Tarih â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','3','5367234863','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(171,12,6,2,'Sultan','â”œÃ§obanyâ”€â–’ldâ”€â–’z','cobanyildizsultan80@gmail.com','25310331030','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Mâ”œâ•zik â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','1','5427417726','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(172,12,6,2,'â”¼Åennur','Yumrutaâ”¼ÅŸ','sumeyyeyumrutas07@gmail.com','25410051040','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5063076099','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(173,12,6,2,'â”¼Åevval','Kaya','kayaaaasevval@gmail.com','23020011029','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Dilbilimi','3','5347105924','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(174,12,6,2,'â”œÃ»mer Faruk','Kaya','omerfarukkaya5542@gmail.com','21050011078','','Turizm Fakâ”œâ•ltesi','Gastronomi ve Mutfak Sanatlarâ”€â–’','4','5461226701','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(175,12,6,2,'Aâ”¼ÅŸâ”€â–’r','â”œÃ»ztop','asiroztop05@gmail.com','25410031066','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Muhasebe ve Finans Yâ”œÃ‚netimi','1','5434928003','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(176,12,6,2,'Fatma Nur','Kahveci','f.nurkahveci07@gmail.com','25040071408','','Fen Fakâ”œâ•ltesi','â”€â–‘statistik','2','5516323117','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(177,12,6,2,'Sinem','â”œÃ§ay','snmmcy.556@gmail.com','25410051014','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5550858277','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(178,12,6,2,'Bilal Emir','â”¼Åahin','emirduru4207@gmail.com','25720041041','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Saâ”€ÅŸlâ”€â–’k Yâ”œÃ‚netimi','1','5518554212','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(179,12,6,2,'Derda','Aktaâ”¼ÅŸ','derdaktas@gmail.com','25420011034','','Siyasal Bilgiler Fakâ”œâ•ltesi','â”€â–‘ktisat','1','5526253550','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(180,12,6,2,'Melisa','Aktepe','melisaezgisuaktepe@gmail.com','25020071032','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Psikoloji','Hazâ”€â–’rlâ”€â–’k','5346906204','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(181,12,6,2,'Hâ”œâ•sna','Kâ”œÃ‚mâ”œâ•rcâ”œâ•','husnakmrcu@gmail.com','25420091021','','Siyasal Bilgiler Fakâ”œâ•ltesi','Siyaset Bilimi ve Uluslararasâ”€â–’ â”€â–‘liâ”¼ÅŸkiler','Hazâ”€â–’rlâ”€â–’k','5013431519','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(182,12,6,2,'Ecrin Yâ”œâ•sra','Erdini','eyusraerdini@gmail.com','25420031025','','Siyasal Bilgiler Fakâ”œâ•ltesi','â”€â–‘â”¼ÅŸletme','1','5392271811','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(183,12,6,2,'â”€â–‘brahim','â”œÃ§alâ”€â–’k','ibrahmcalikk@gmail.com','25100031016','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','1','5312968417','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(184,12,6,2,'â”€â–‘smail Faruk','Nair','faruknair437@gmail.com','20010101045','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','4','5384610317','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(185,12,6,2,'Neslihan','Demir','neslihhandemirr@gmail.com','25440011046','','Hemâ”¼ÅŸirelik Fakâ”œâ•ltesi','Hemâ”¼ÅŸirelik','1','5056816680','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(186,12,6,2,'Zeynep Nur','Aslâ”€â–’var','aslivarayse72@gmail.com','25020081014','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Sanat Tarihi','1','5539784419','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(187,12,6,2,'Vesile','Demir','demir07vesile@icloud.com','25020011008','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Dilbilimi','1','5461851150','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(188,12,6,2,'Mâ”œâ•zeyyen','Peltek','peltekmuzeyyen99@gmail.com','23100121051','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi','1','905075059088','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(189,12,6,2,'Nisa','â”œÃ§imen','aselsina2006@gmail.com','25410031054','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Muhasebe ve Finans Yâ”œÃ‚netimi','1','5431932931','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(190,12,6,2,'Dilan','Gâ”œâ•ndoâ”€ÅŸdu','dilandilangundogdu@gmail.com','23100121054','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi','1','5443247503','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(191,12,6,2,'Yaâ”€ÅŸmur','Eriâ”¼ÅŸ','ygmrummhners003@gmail.com','24100121058','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi','1','5423981135','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(192,12,6,2,'Fatâ”€â–’ma Nisan','Gâ”œâ•leâ”œÄŸ','gulecnisan79@gmail.com','25420011015','','Siyasal Bilgiler Fakâ”œâ•ltesi','â”€â–‘ktisat','1','5526093534','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(193,12,6,2,'Aybâ”œâ•ke','Yâ”€â–’ldâ”€â–’z','aybukeyildiz6666@gmail.com','25420011041','','Siyasal Bilgiler Fakâ”œâ•ltesi','â”€â–‘ktisat','1','5550230566','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(194,12,6,2,'Sâ”œâ•meyye Su','Can','sucansumeyye@gmail.com','25410141014','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Uluslararasâ”€â–’ Ticaret ve Finansman','1','5448726643','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(195,12,6,2,'Amine Nur','Aydâ”€â–’n','aminenuraydin2007@gmail.com','25420011053','','Siyasal Bilgiler Fakâ”œâ•ltesi','â”€â–‘ktisat','1','5511925740','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(196,12,6,2,'Seden','Karslâ”€â–’','karsliseden@gmail.com','25410141027','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Uluslararasâ”€â–’ Ticaret ve Finansman','1','5321791712','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(197,12,6,2,'â”¼Åerife Naz','Tâ”œâ•rkmen','serife.naz.turkmen@gmail.com','25410141018','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Uluslararasâ”€â–’ Ticaret ve Finansman','1','5352327541','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(198,12,6,2,'â”€â–‘rem','â”œÃ§ifâ”œÄŸioâ”€ÅŸlu','cifciogluirem@gmail.com','25410141017','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Uluslararasâ”€â–’ Ticaret ve Finansman','1','5510462258','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(199,12,6,2,'Mervenur','Doruk','dorukmervenur0@gmail.com','22720021082','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Fizyoterapi ve Rehabilitasyon','4','5011172207','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(200,12,6,2,'Ali','Aydâ”€â–’n','aliaydin196@gmail.com','23410141014','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Uluslararasâ”€â–’ Ticaret ve Finansman','2','5061168151','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(201,12,6,2,'Sedef','Serter','sertersedef4@gmail.com','25390011090','','Hukuk Fakâ”œâ•ltesi','Hukuk','1','5386992694','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(202,12,6,2,'â”€â–‘lknur','Ceran','ilknurceran06@gmail.com','25390011008','','Hukuk Fakâ”œâ•ltesi','Hukuk','1','5362006071','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(203,12,6,2,'Furkan','Betten','furkanbetten531@gmail.com','23410141057','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Uluslararasâ”€â–’ Ticaret ve Finansman','2','5459126487','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(204,12,6,2,'Mustafa','Baâ”€ÅŸâ”€â–’â”¼ÅŸ','bagismustafa87@gmail.com','24410141804','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Uluslararasâ”€â–’ Ticaret ve Finansman','2','5374712641','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(205,12,6,2,'Bennu','â”¼Åeker','sekerbennu654@gmail.com','20024071013','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Psikoloji','1','55395457571','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(206,12,6,2,'Bilal Can','â”œÃ»ztâ”œâ•rk','bilalcanozt42@gmail.com','25410051008','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5434384048','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(207,12,6,2,'Ravza','Kartal','ravzanurkartal68@gmail.com','25040061032','','Fen Fakâ”œâ•ltesi','Biyoteknoloji','1','5510646521','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(208,12,6,2,'Efe','Bayramoâ”€ÅŸlu','efebayramoglu30@gmail.com','24410051021','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2','5415857265','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(209,12,6,2,'Feyza Nur','Yâ”€â–’ldâ”€â–’z','feyzanur.yildiz01@gmail.com','25040061009','','Fen Fakâ”œâ•ltesi','Biyoteknoloji','1','5050045941','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(210,12,6,2,'Cevahir','â”¼Åenel','senelcevahir5@gmail.com','24310341026','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Okul â”œÃ»ncesi â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','2','5536407964','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(211,12,6,2,'Beyzanur','â”€â–‘lhan','beyzanurilhan20@gmail.com','24100101049','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','2','5454388799','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(212,12,6,2,'Ayâ”¼ÅŸegâ”œâ•l','â”¼Åahin','eselayaysegul@gmail.com','25100101401','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','2','5395291399','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(213,12,6,2,'Berre','Yalâ”œÄŸâ”€â–’n','mutusluberre@gmail.com','22050062061','','Turizm Fakâ”œâ•ltesi','Gastronomi ve Mutfak Sanatlarâ”€â–’','4','5079004755','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(214,12,6,2,'Emine Ceren','Yaâ”¼ÅŸar','cerennysar@gmail.com','22050062030','','Turizm Fakâ”œâ•ltesi','Gastronomi ve Mutfak Sanatlarâ”€â–’','4','5419674282','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(215,12,6,2,'Fatma','Aksoy','ftzhr.aks@gmail.com','25410111013','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Finans ve Bankacâ”€â–’lâ”€â–’k','1','5461124885','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(216,12,6,2,'Memiâ”¼ÅŸ','Saâ”€ÅŸlam','saglam.memis00@gmail.com','20010091001','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','5','5453180979','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(217,12,6,2,'Nefise','â”œÃ»zmen','seyyidetnefise@icloud.com','24100121015','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi','1','5519830347','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(218,12,6,2,'Haydar Arkâ”€â–’n','Culha','arknclh@gmail.com','24100131021','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Metalurji ve Malzeme Mâ”œâ•hendisliâ”€ÅŸi','1','5059719925','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(219,12,6,2,'Hafsa','â”œÃ§atâ”€â–’lâ”€â–’','hafsacatili@gmail.com','25410051023','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5525682775','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(220,12,6,2,'Rabia','â”œÃ»zbek','rabiaozbek128@gmail.com','24030021004','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','1','5443696396','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(221,12,6,2,'Begâ”œâ•m','Altun','begmaltun.06@gmail.com','25440011076','','Hemâ”¼ÅŸirelik Fakâ”œâ•ltesi','Hemâ”¼ÅŸirelik','1','5526899053','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(222,12,6,2,'Hamza','â”œÃ§elik','1hamzacelik@gmail.com','21100091015','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','5','5519824218','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(223,12,6,2,'Buse','Aâ”œÄŸâ”€â–’kgâ”œÃ‚z','buseacikgoz38@gmail.com','25720031025','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Beslenme ve Diyetetik','1','5464453888','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(224,12,6,2,'Zeynep Naz','Kesici','zeynepnazkesici349@gmail.com','25020021018','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Felsefe','','5426856382','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(225,12,6,2,'Beyza','Ay','42beyzaay42@gmail.com','25020021029','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Felsefe','','5511956043','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(226,12,6,2,'Azime','Nur','kalendernur030@gmail.com','25020021027','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Felsefe','1','5411054083','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(227,12,6,2,'Esra Nur','Kaplan','esrakpln0606@gmail.com','24100101021','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','2','5466223919','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(228,12,6,2,'Sebahat','â”œÃ»zdemir','sacidesebos4820@gmail.com','24100101039','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','2','5337154820','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(229,12,6,2,'Hamza','Ergâ”œâ•ven','hamzaerguven2005@gmail.com','23100011071','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1','5510281014','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(230,12,6,2,'Nisanur','Erkaya','nisanurerkaya@gmail.com','25100031034','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','1','5447114207','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(231,12,6,2,'Raziye Nur','Toklu','raziyenurtoklu@gmail.com','25100031007','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','1','5519543448','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(232,12,6,2,'Ahmet','Kâ”œâ•â”œÄŸâ”œâ•k','kucukahmet265@gmail.com','23100091014','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','3','5538383388','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(233,12,6,2,'Kadir','â”œÃ§â”œÃ‚rdâ”œâ•koâ”€ÅŸlu','zerta42064242@gmail.com','23100091046','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','','5316415491','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(234,12,6,2,'Muhammet â”€â–‘kbal','Apaydâ”€â–’n','muhammetikbalapaydin@gmail.com','23100091030','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','3','5053322450','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(235,12,6,2,'Mehmetcan','Karakaya','mehmetcankarakaya42@gmail.com','23410111014','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Finans ve Bankacâ”€â–’lâ”€â–’k','3','5426842282','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(236,12,6,2,'Ayâ”¼ÅŸe Suna','â”¼Åahin','sahinaysesuna2@gmail.com','24030021046','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','1','5352809282','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(237,12,6,2,'Sude','Alâ”€â–’câ”€â–’','hsudealici6@gmail.com','25030021077','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','1','5427824638','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(238,12,6,2,'Musa Mert','â”œÃ§etin','cetinmusamert@gmail.com','25100091045','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','1','5527142490','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(239,12,6,2,'Busra','Gokce','busragkc2010@gmail.com','23720031051','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Beslenme ve Diyetetik','3','5537827497','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(240,12,6,2,'Afiye Nur','Yeter','nuryeter032@gmail.com','23720031072','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Beslenme ve Diyetetik','3','5352527448','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(241,12,6,2,'Vildan','Eryâ”€â–’lmaz','vildaneryilmaz83@gmail.com','23720031077','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Beslenme ve Diyetetik','3','5523233041','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(242,12,6,2,'Ayâ”¼ÅŸe','â”¼Åahin','sahinayse2007@gmail.com','25440011025','','Hemâ”¼ÅŸirelik Fakâ”œâ•ltesi','Hemâ”¼ÅŸirelik','1','5378450092','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(243,12,6,2,'Sude Nur','Bakâ”€â–’rcâ”€â–’','sudenrbkrc2@gmail.com','24090121015','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','â”¼Åehir ve Bâ”œÃ‚lge Planlama','2','5458535382','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(244,12,6,2,'â”€â–‘lknur','Topal','tilknur680@gmail.com','24090121035','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','â”¼Åehir ve Bâ”œÃ‚lge Planlama','2','5516544038','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(245,12,6,2,'Elif Su','Eliâ”œâ•â”¼ÅŸâ”œâ•k','eliusukelif@gmail.com','24420031095','','Siyasal Bilgiler Fakâ”œâ•ltesi','â”€â–‘â”¼ÅŸletme','2','5438804452','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(246,12,6,2,'Elif','Yâ”€â–’ldâ”€â–’râ”€â–’m','elifyldrm0907@gmail.com','24420031053','','Siyasal Bilgiler Fakâ”œâ•ltesi','â”€â–‘â”¼ÅŸletme','2','5389398087','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(247,12,6,2,'Halime Betâ”œâ•l','Dursun','halimebetuldursun@gmail.com','22100121056','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi','3','5051643939','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(248,12,6,2,'Berfin','Diken','berfindiken97@gmail.com','24090121026','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','â”¼Åehir ve Bâ”œÃ‚lge Planlama','2','5415950907','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(249,12,6,2,'Fatma Vildan','Kâ”œÃ‚mâ”œâ•rcâ”œâ•','fatma.vildann@gmail.com','25100011053','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1','5050177865','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(250,12,6,2,'Nursena','Gâ”œâ•mâ”œâ•â”¼ÅŸsoy','nursenagumussoy@gmail.com','25100011070','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1','5525680835','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(251,12,6,2,'Damla','â”œÃ§akâ”€â–’câ”€â–’','cakicidamla6@gmail.com','22130041804','','Tâ”€â–’p Fakâ”œâ•ltesi','Tâ”€â–’p','1','5416851635','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(252,12,6,2,'Mustafa Baran','Dinâ”œÄŸ','m.baran.dinc@outlook.com','25100101027','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','1','5558884144','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(253,12,6,2,'Hamza Yekta','Aâ”œÄŸâ”€â–’k','hamzayektaacik@gmail.com','25100101005','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','1','5530239342','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(254,12,6,2,'Ahmet Kaan','Arâ”€â–’','ari.pykaan@gmail.com','25100101060','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','1','5078977970','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(255,12,6,2,'Caner','Kocu','canerkocu123@gmail.com','25090031025','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Grafik Tasarâ”€â–’m','1','5306643770','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(256,12,6,2,'Hâ”œâ•seyin','Dinâ”œÄŸ','hsyndnc.2018@gmail.com','24100031049','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','2','5434032045','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(257,12,6,2,'Muhammed Esad','â”œÃ»zkaya','mhmdesd45@gmail.com','24100031004','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','2','5511925735','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(258,12,6,2,'Emre Kerem','â”œÃ»zgan','ozganemrekerem@gmail.com','24100031070','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','2','5331641216','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(259,12,6,2,'Taha Furkan','Uyar','tahafurkanuyar@gmail.com','23020031008','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Halkla â”€â–‘liâ”¼ÅŸkiler ve Reklamcâ”€â–’lâ”€â–’k','3','5078920557','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(260,12,6,2,'Enes Semih','Acar','enessmh05@gmail.com','23020031004','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Halkla â”€â–‘liâ”¼ÅŸkiler ve Reklamcâ”€â–’lâ”€â–’k','3','5449463450','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(261,12,6,2,'Nurettin Talha','Uzun','nurettintalha12@gmail.com','23020031018','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Halkla â”€â–‘liâ”¼ÅŸkiler ve Reklamcâ”€â–’lâ”€â–’k','3','5446359824','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(262,12,6,2,'â”€â–‘lyas','â”œÂ£nal','uilyas299@gmail.com','25030101005','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Havacâ”€â–’lâ”€â–’k ve Uzay Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5522432150','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(263,12,6,2,'Hayrunnisa','Bâ”œÃ‚rklâ”œâ•','nisaborklu79@gmail.com','25050011033','','Turizm Fakâ”œâ•ltesi','Gastronomi ve Mutfak Sanatlarâ”€â–’','1','5013040136','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(264,12,6,2,'â”¼Åevval','Gâ”œâ•rbâ”œâ•z','sevvallgurbuzz@gmail.com','25050011060','','Turizm Fakâ”œâ•ltesi','Gastronomi ve Mutfak Sanatlarâ”€â–’','1','5362272045','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(265,12,6,2,'Elif Sultan','Gâ”œâ•ldalu','elifsultanguldali1@gmail.com','25050011028','','Turizm Fakâ”œâ•ltesi','Gastronomi ve Mutfak Sanatlarâ”€â–’','1','5389331554','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(266,12,6,2,'Elif Nur','Korkusuz','elifnurkorkusuz65@gmail.com','25410111057','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Finans ve Bankacâ”€â–’lâ”€â–’k','1','5366772352','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(267,12,6,2,'Hamza Mert','Armaâ”€ÅŸan','merth.06@icloud.com','23310571024','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Coâ”€ÅŸrafya â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','3','5525720835','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(268,12,6,2,'Pâ”€â–’nar','Candan','pnrcandan07@gmail.com','25050011418','','Turizm Fakâ”œâ•ltesi','Gastronomi ve Mutfak Sanatlarâ”€â–’','1','5434619307','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(269,12,6,2,'Faruk','Karataâ”¼ÅŸ','faruk02400@gmail.com','25410051419','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5512530063','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(270,12,6,2,'Mukaddes','Atâ”€â–’câ”€â–’','mukaddesatici5@icloud.com','25420011060','','Siyasal Bilgiler Fakâ”œâ•ltesi','â”€â–‘ktisat','1','5050234519','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(271,12,6,2,'Mukadder','â”œÂ£nsalan','munsalan07@gmail.com','25100121006','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi','1','55165611828','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(272,12,6,2,'Buse','Gâ”œâ•rdal','gurdalbuse3@gmail.com','23720021071','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Fizyoterapi ve Rehabilitasyon','3','5434631972','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(273,12,6,2,'Kâ”œâ•bra','Bahtiyar','Kbrabht00@gmail.com','23720021074','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Fizyoterapi ve Rehabilitasyon','3','5079801237','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(274,12,6,2,'Dilara','Aâ”œÄŸâ”€â–’kgâ”œÃ‚z','dilaraacikgoz262@gmail.com','25420011036','','Siyasal Bilgiler Fakâ”œâ•ltesi','â”€â–‘ktisat','','5510204874','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(275,12,6,2,'Didem','Tâ”œâ•mer','didemtumer42@gmail.com','25090031008','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Grafik Tasarâ”€â–’m','1','5526453117','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(276,12,6,2,'Ayâ”¼ÅŸenur','Hâ”€â–’zlâ”€â–’','aysenurhizli0@gmail.com','25420011023','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','â”€â–‘ktisat','1','5419464707','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(277,12,6,2,'Bâ”œâ•â”¼ÅŸra','Duran','busraduran796@gmail.com','25090031010','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Grafik Tasarâ”€â–’m','1','5510929171','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(278,12,6,2,'Gâ”œâ•lizar','Yeâ”€ÅŸen','yegengulizar@gmail.com','22310421001','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Sosyal Bilgiler â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','4','5541485660','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(279,12,6,2,'Nida','Burcak','zeynepnidaburcak5@gmail.com','22310421005','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Sosyal Bilgiler â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','4','5441011005','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(280,12,6,2,'Nurgâ”œâ•l','Yâ”œâ•kseldi','a@a.com','22020071019','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Psikoloji','3','5435206168','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(281,12,6,2,'Nazan','Yuka','nazanyuka667@gmail.com','24720051051','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Sosyal Hizmet','2','5442938058','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(282,12,6,2,'Sibel','Akmeâ”¼ÅŸe','sibelakmese63@gmail.com','24720051019','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Sosyal Hizmet','2','5541747773','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(283,12,6,2,'Fatma Dilara','â”¼Åaâ”¼ÅŸtâ”€â–’m','fatmadilarasastim@gmail.com','24390011094','','Hukuk Fakâ”œâ•ltesi','Hukuk','2','5467808064','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(284,12,6,2,'Hatice Gamze','â”œÃ§iâ”œÄŸekli','hcicekli081@gmail.com','24390011095','','Hukuk Fakâ”œâ•ltesi','Hukuk','2','5516302564','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(285,12,6,2,'Beyzanur','Kuâ”¼ÅŸ','beyzanurkus03@gmail.com','24390011073','','Hukuk Fakâ”œâ•ltesi','Hukuk','2','5353872508','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(286,12,6,2,'Feyza Ikra','â”œÃ§elik','feyzaikra45@gmail.com','22318271059','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','â”€â–‘ngilizce â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','4','5353762440','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(287,12,6,2,'Gâ”œâ•lbeyaz','Turgut','gulbeyazturgut1@gmail.com','24390011084','','Hukuk Fakâ”œâ•ltesi','Hukuk','2','5358761639','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(288,12,6,2,'â”¼Åevva','Avcu','avcusevval@gmail.com','24020071019','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Psikoloji','1','5511449100','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(289,12,6,2,'Dilara','Turan','dturan110@gmail.com','22090111073','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Mimarlâ”€â–’k','4','5330494511','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(290,12,6,2,'Merve','Yeniâ”œÄŸeri','merveeyeniceri06@gmail.com','22090111017','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Mimarlâ”€â–’k','4','5538790029','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(291,12,6,2,'Canan Nur','Bulca','canannur1407@gmail.com','24090101017','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Radyo','2','','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(292,12,6,2,'Eylâ”œâ•l','â”œÃ»zsoy','ellyhisponsor@gmail.com','25020011024','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Dilbilimi','1','5347488051','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(293,12,6,2,'Saliha Nur','Cengiz','salceng3272@gmail.com','25100011032','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5516475381','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(294,12,6,2,'Zeynep','Karademir','zeynepkrdmr1@gmail.com','25020011043','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Dilbilimi','1','5318947996','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(295,12,6,2,'Ali Arda','Yâ”œâ•ksel','mmaliardayuksel@gmail.com','25100091054','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','1','5511956170','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(296,12,6,2,'Aybâ”œâ•ke','Altun','aybuke1324altnn@gmail.com','24720021059','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Fizyoterapi ve Rehabilitasyon','2','5511304845','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(297,12,6,2,'Râ”œâ•veyda','Doâ”€ÅŸan','doganruveyda350@gmail.com','24720021029','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Fizyoterapi ve Rehabilitasyon','2','5394615258','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(298,12,6,2,'Eren','Gâ”œâ•ltekin','eren@21.org.tr','25020011007','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Dilbilimi','1','5525883271','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(299,12,6,2,'Miyase Eslem','Turan','turanmiyaseeslem@gmail.com','24310381003','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Resim â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','2','5532095390','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(300,12,6,2,'Rahime Nur','â”œÃ»ncel','rahimenuroncel@gmail.com','25020011025','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Dilbilimi','1','5319105697','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(301,12,6,2,'Boran Hamza','Tâ”œâ•rker','boranturker46@gmail.com','25030021066','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','','5344860281','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(302,12,6,2,'Recep','â”œÃ§oban','receocbn@gmail.com','25030021033','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5551105443','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(303,12,6,2,'Yaâ”€ÅŸmur Gâ”œâ•l','Teke','yagmurteke33@gmail.com','23320101031','','Siyasal Bilgiler Fakâ”œâ•ltesi','Maliye','3','5382230007','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(304,12,6,2,'Emiâ”¼ÅŸ Kâ”œâ•bra','Tali','kubratali@hotmail.com','23420101023','','Siyasal Bilgiler Fakâ”œâ•ltesi','Maliye','3','5345723483','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(305,12,6,2,'Remzi','â”œÃ§etinkaya','osman.remzi2004@gmail.com','22100031012','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','4','5348572583','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(306,12,6,2,'â”€â–‘clal','Mutlu','iclalmutlu345@gmail.com','25440011114','','Hemâ”¼ÅŸirelik Fakâ”œâ•ltesi','Hemâ”¼ÅŸirelik','1','5443573568','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(307,12,6,2,'Dilek','Atabek','dilekatabek00@gmail.com','25440011119','','Hemâ”¼ÅŸirelik Fakâ”œâ•ltesi','Hemâ”¼ÅŸirelik','1','5541856765','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(308,12,6,2,'Amira','Ajaj','amiraajaj322@gmail.com','24030021804','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','1','5516734500','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(309,12,6,2,'Kâ”œâ•bra','Aladaâ”€ÅŸ','kubrakip0@gmail.com','25020031005','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Halkla â”€â–‘liâ”¼ÅŸkiler ve Reklamcâ”€â–’lâ”€â–’k','1','5386187944','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(310,12,6,2,'Cenk','Karaâ”œÄŸolak','cekokaracolak@gmail.com','25030021049','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5010082620','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(311,12,6,2,'Yaren','Uâ”œÄŸar','yarenucar0410@gmail.com','25100031014','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','1','5519726269','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(312,12,6,2,'Muhammed Emir','Velibeyoâ”€ÅŸlu','emirvelibey13@gmail.com','24410051018','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2','5526360613','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(313,12,6,2,'Yasin','Baki','yasinbaki633@gmail.com','2310003154','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','3','5521324151','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(314,12,6,2,'Kadir','Samet Gâ”œÃ‚rmez','kadirsametgormez@gmail.com','23100031043','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','3','5337136971','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(315,12,6,2,'Muhammed','Talha Eroâ”€ÅŸlu','eroglutalha90@gmail.com','24030021028','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','1','551 175 2378','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(316,12,6,2,'Menekâ”¼ÅŸe','â”¼Åura Avcâ”€â–’','suraavci6@gmail.com','24100091072','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','1','5468836732','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(317,12,6,2,'Beyzanur','Bâ”œâ•yâ”œâ•kateâ”¼ÅŸ','Buyukatesbeyza4@gmail.com','23410082013','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3','5524206303','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(318,12,6,2,'Râ”œâ•meysa','Dindar','rumeysadindar76@gmail.com','23410082034','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3','5417694611','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(319,12,6,2,'Emirhan','Sarp Karabâ”€â–’yâ”€â–’k','emirhansarpk@gmail.com','24030101010','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Havacâ”€â–’lâ”€â–’k ve Uzay Mâ”œâ•hendisliâ”€ÅŸi','1','5468534328','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(320,12,6,2,'Beste','Rabia â”œÃ»zince','besteozince@gmail.com','23410082012','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3','5447138726','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(321,12,6,2,'â”€â–‘smayâ”€â–’l','Bulut','iso.safarov123@gmail.com','24100091814','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','3','5370371075','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(322,12,6,2,'Aysel','Karahasan','ayselkarahasan970@gmail.com','25100091465','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','1','5393221123','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(323,12,6,2,'Zelal','Deniz Ercan','denizercan818@gmail.com','25100101006','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5511682709','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(324,12,6,2,'â”¼Åâ”œâ•krâ”œâ•','Arda Anâ”€â–’k','ardaanik98@gmail.com','22040081046','','Fen Fakâ”œâ•ltesi','Matematik ve Bilgisayar Bilimleri','4','5537816080','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(325,12,6,2,'â”œÃ»znaz','Tâ”œâ•rk','oznaztrk@gmail.com','25030021046','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5469529593','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(326,12,6,2,'Ramazan','Nerkiz','ramazannerkiz0156@gmail.com','25030021018','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5055982456','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(327,12,6,2,'Ayâ”¼ÅŸe','Nisa Ak','aysni16sa@gmail.com','25030021029','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','0530 473 70 57','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(328,12,6,2,'Tarâ”€â–’k','Akgâ”œâ•n','takgun.is0@gmail.com','24410051025','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2','5510547155','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(329,12,6,2,'Melisa','Yuvarlak','Melisayuvar@gmail.com','2410158897','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','2','5310158897','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(330,12,6,2,'Azra','Ebrar â”œÃ§elik','celikazraebrar@gmail.com','23410082017','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3','5537764809','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(331,12,6,2,'Kâ”œâ•bra','Gâ”œâ•ler','gulerkubra095@gmail.com','25090091031','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','â”€â–‘â”œÄŸ Mimarlâ”€â–’k ve â”œÃ§evre Tasarâ”€â–’mâ”€â–’','1','5380901565','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(332,12,6,2,'â”¼Åevval','Sâ”œâ•rmen','sevvalsurmen5461@gmail.com','25090091009','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','â”€â–‘â”œÄŸ Mimarlâ”€â–’k ve â”œÃ§evre Tasarâ”€â–’mâ”€â–’','1','5461245461','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(333,12,6,2,'Hakan','â”œÃ»zyay','hakanozyayy@gmail.com','21030021012','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','3','501 012 41 75','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(334,12,6,2,'Halim','Yavuz','halim42yavuz@gmail.com','24100031014','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','2','0505 090 9562','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(335,12,6,2,'Seyhan','Yakut','yakutseyhan0@gmail.com','24100101050','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','2','5306403957','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(336,12,6,2,'Ahmet','Burak â”¼Åahin','ahmetburak.1795@gmail.com','24100031073','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','2','5431252779','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(337,12,6,2,'Talha','â”¼Åahin','talhasahin05@hotmail.com','23410051050','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3','5455739362','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(338,12,6,2,'â”œÂ£veys','Hasan â”œÃ§oban','uveyshasancoban1334@gmail.com','24100101006','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','2','0501 041 9713','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(339,12,6,2,'Fethiye','Nur Savaâ”¼ÅŸ','fethiyenursavas06@gmail.com','24100101046','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','2','5368307605','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(340,12,6,2,'â”¼Åevket','Can Coâ”¼ÅŸkun','sevketcan125@gmail.com','24100101023','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','2','5374795376','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(341,12,6,2,'Ramazan','Erdoâ”€ÅŸan','ramazanerdogan209@gmail.com','25100011009','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1','554 140 5785','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(342,12,6,2,'Semra','Nur Denizdurduran','semraa_nd@hotmail.com','23030021059','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','2','554 162 43 15','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(343,12,6,2,'Hamzacan','Erdoâ”€ÅŸan','erdogan1hamza@gmail.com','24100101011','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','2','541 712 2597','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(344,12,6,2,'Gâ”œâ•lay','Nisa Gâ”œâ•ltaâ”œÄŸ','gultacgulaynisa1321@gmail.com','23030021049','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','2','5419508262','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(345,12,6,2,'Yaren','Doâ”€ÅŸan','yrndgn1015@gmail.com','23030021037','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','2','5373881307','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(346,12,6,2,'Cansâ”€â–’n','Gâ”œâ•ndâ”œâ•z','cansingunduz942@gmail.com','23030021054','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','2','5519542301','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(347,12,6,2,'â”œÃ»mer','Aydâ”€â–’n','omeray165@gmail.com','24100101038','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Mekatronik Mâ”œâ•hendisliâ”€ÅŸi','2','0532 727 1654','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(348,12,6,2,'Ramazan','â”œÃ»zkaya','ramazanozkya088@gmail.com','24410051017','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2','5443668028','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(349,12,6,2,'Beyza','Nur Yâ”€â–’lmaz','beyzanuryilmaz286@gmail.com','25030021075','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5300403160','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(350,12,6,2,'Feride','Saâ”œÄŸâ”€â–’kara','feridesacikara00@gmail.com','25030021056','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5301854234','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(351,12,6,2,'Ayâ”¼ÅŸe','Uzun','uzunayse579@gmail.com','25030021071','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5050746657','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(352,12,6,2,'Ceyda','Doâ”€ÅŸan','doganceyda2006@gmail.com','25030101009','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Havacâ”€â–’lâ”€â–’k ve Uzay Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5551062322','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(353,12,6,2,'Nazlâ”€â–’nur','Yamaâ”œÄŸ','yamacnazli@icloud.com','25310111021','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Almanca â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5511803638','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(354,12,6,2,'Beyza','Kozak','kozakbeyza@gmail.com','25440011087','','Hemâ”¼ÅŸirelik Fakâ”œâ•ltesi','Hemâ”¼ÅŸirelik','1','5383255841','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(355,12,6,2,'Elif','â”œÃ»zkan','ozkanelif942@gmail.com','25310111039','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Almanca â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5057613544','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(356,12,6,2,'â”œÂ£mmâ”œâ•han','Gamze Akâ”œÄŸay','gamzeakcay3242@gmail.com','25310111050','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Almanca â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5059957032','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(357,12,6,2,'Zeynep','Aydâ”€â–’n','zeynepaydnn098@gmail.com','25310111016','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Almanca â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5318982191','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(358,12,6,2,'Samet','Taâ”¼ÅŸkâ”€â–’n','samettskn38@gmail.com','23100091065','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','2','5375247697','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(359,12,6,2,'Huri','Ceren Akâ”œÄŸasâ”€â–’z','cerenakcsz@gmail.com','24100031027','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','2','5304297047','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(360,12,6,2,'Yiâ”€ÅŸit','Abrek Baybaâ”¼ÅŸ','yigitabrek0@gmail.com','24100031035','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','2','5321201864','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(361,12,6,2,'Selen','â”¼Åahin','sselensahin0@gmail.com','25100031040','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','1','5379125780','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(362,12,6,2,'Nisa','Aslan','nisaaslann.13@gmail.com','25100031042','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','1','5339495450','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(363,12,6,2,'Fadime','Beyza Nur Taâ”œÄŸyâ”€â–’ldâ”€â–’z','beyzanurtacyildiz3@gmail.com','25030021027','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5415717759','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(364,12,6,2,'Zehranaz','Dâ”œâ•ndar','zehranazdundar16@gmail.com','25030021006','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5511541654','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(365,12,6,2,'Emir','Koâ”œÄŸak','emirkocak77@gmail.com','25100031434','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','2','0505 221 2502','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(366,12,6,2,'Abdullah','Bayir','abdullah23byr@gmail.com','25490411005','','Necmettin Erbakan Kos MYO','CNC Programlama ve Operatâ”œÃ‚rlâ”œâ•â”€ÅŸâ”œâ•','1','5527173616','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(367,12,6,2,'Bora','Kundakâ”œÄŸâ”€â–’oâ”€ÅŸlu','Boracan357@hotmail.com','23410082048','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','4','5320608496','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(368,12,6,2,'Sudenur','Doâ”€ÅŸan','dogansudenur@gmail.com','23030021045','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','2','5435658886','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(369,12,6,2,'Burak','Yâ”€â–’ldâ”€â–’z','mburak140@gmail.com','23030031045','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uzay ve Uydu Mâ”œâ•hendisliâ”€ÅŸi','1','5436439464','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(370,12,6,2,'Bâ”œâ•â”¼ÅŸra','Cabadak','Busracabadak.13@gmail.com','23410141004','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Uluslararasâ”€â–’ Ticaret ve Finansman','2','5346323493','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(371,12,6,2,'Aleyna','Kâ”œâ•tâ”œâ•k','aleynakutuk1290@gmail.com','24410141034','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Uluslararasâ”€â–’ Ticaret ve Finansman','2','5422417731','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(372,12,6,2,'Fatma Ceren','Akpâ”€â–’nar','Akpinarfatmaceren@gmail.com','24410141048','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Uluslararasâ”€â–’ Ticaret ve Finansman','2','5392040269','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(373,12,6,2,'Ezgi','Varlâ”€â–’','varliezgi08@gmail.com','24410141019','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Uluslararasâ”€â–’ Ticaret ve Finansman','2','5437348178','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(374,12,6,2,'Fatma Nida','Kâ”€â–’lâ”€â–’â”œÄŸ','kilicfatmanida@gmail.com','25420031041','','Siyasal Bilgiler Fakâ”œâ•ltesi','â”€â–‘â”¼ÅŸletme','1','542 208 09 23','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(375,12,6,2,'Emircan','Bingâ”œÃ‚l','emircan.bingol1132@gmail.com','24100031041','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','2','5339691489','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(376,12,6,2,'Naz Elmas','Gâ”œâ•leâ”œÄŸ','nazimelmas1@gmail.com','25100031045','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','1','5393352604','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(377,12,6,2,'Atilla','Saygâ”€â–’n','atillasaygin7@gmail.com','24100011036','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1','5550252933','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(378,12,6,2,'â”œÃ»mer','Ali Ceviz','omeraliceviz25@gmail.com','25100011011','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1','5519871341','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(379,12,6,2,'Cuma Efe','Yâ”€â–’lboâ”€ÅŸa','yilboga6363@icloud.com','25030021017','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','5457309123','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(380,12,6,2,'Furkan','â”€â–‘â”œÄŸen','icenfurkan476@gmail.com','23030031004','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Havacâ”€â–’lâ”€â–’k ve Uzay Mâ”œâ•hendisliâ”€ÅŸi','1','5437830237','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(381,12,6,2,'Bâ”œâ•â”¼ÅŸranur','Ayaz','busranrayaz@gmail.com','25490411008','','Necmettin Erbakan KOS MYO','CNC Programlama ve Operatâ”œÃ‚rlâ”œâ•â”€ÅŸâ”œâ•','1','0536 343 32 43','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(382,12,6,2,'Arda','Baha Gâ”œÃ‚ktaâ”¼ÅŸ','ardabahagoktas2006@gmail.con','25490411020','','Necmettin Erbakan KOS MYO','CNC Programlama ve Operatâ”œÃ‚rlâ”œâ•â”€ÅŸâ”œâ•','1','552 606 4270','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(383,12,6,2,'Adem','Abdullah Turan','ademabdullahturan2007@gmail.com','25490411023','','Necmettin Erbakan KOS MYO','CNC Programlama ve Operatâ”œÃ‚rlâ”œâ•â”€ÅŸâ”œâ•','1','0551 125 08 97','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(384,12,6,2,'Emre','â”œÃ§akâ”€â–’n','emrecakinusak@gmail.com','25490411002','','Necmettin Erbakan KOS MYO','Makine ve Metal Teknolojileri','1','5444746277','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(385,12,6,2,'Abdâ”œâ•ssamed','Uzun','asameduzun02@gmail.com','21100091039','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','4','5330471169','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(386,12,6,2,'â”œÃ»mer Ali','Kâ”œâ•â”œÄŸâ”œâ•k','Omeralikucuk236@gmail.com','25100091008','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','1','551 683 66 36','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(387,12,6,2,'â”œÃ»mer','Faruk â”œÃ»zat','omerfarukozat0@gmail.com','25490411015','','Necmettin Erbakan KOS MYO','Makine ve Metal Teknolojileri','1','5327297412','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(388,12,6,2,'Aysel','Tanrâ”€â–’verdi','tanriverdiaysel19@gmail.com','21030021016','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','4','0551 636 90 47','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(389,12,6,2,'Sevde','Nur Karakuâ”¼ÅŸ','karakussevdenur2869@gmail.com','24030021009','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','1','5519423801','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(390,12,6,2,'Rumeysa','Tâ”œâ•zâ”œâ•n','rumeysatuzun311@gmail.com','24410051063','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2','5519545677','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(391,12,6,2,'Zehra','â”€â–‘rem Yâ”€â–’ldâ”€â–’z','zehrayildiz915@gmail.com','24410051041','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2','5059304202','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(392,12,6,2,'Sude','Eroâ”€ÅŸlu','zeehraerogluu@gmail.com','24410051045','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2','5061801013','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(393,12,6,2,'Mediha Sultan','Yener','medihasyener25@gmail.com','25490221008','','Necmettin Erbakan KOS MYO','Yapay Zeka Operatâ”œÃ‚rlâ”œâ•â”€ÅŸâ”œâ•','1','5454906647','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(394,12,6,2,'Hafsanur','Kurt','kurt.4284@icloud.com','25100031018','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','1','0531 438 17 14','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(395,12,6,2,'Duygu','Gâ”œâ•ndoâ”€ÅŸdu','thatsduygu1864@gmail.com','23410051024','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3','5511789434','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(396,12,6,2,'Ayâ”¼ÅŸenur','Yâ”€â–’lmaz','aysenuryilmaz542@gmail.com','24410051056','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2','5437323658','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(397,12,6,2,'Sedat','Keskin','sedatkskn25@gmail.com','24030101031','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','1','5323450128','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(398,12,6,2,'Furkan','Daâ”€ÅŸdeviren','furkandagdeviren08@gmail.com','24030101034','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Havacâ”€â–’lâ”€â–’k ve Uzay Mâ”œâ•hendisliâ”€ÅŸi','1','5056656100','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(399,12,6,2,'Kadir','Koyuncu','kadirkoyuncu04@gmail.com','24100031058','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','2','5358993969','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(400,12,6,2,'Mâ”œâ•cahit','Kâ”œâ•â”œÄŸâ”œâ•kkuâ”¼ÅŸcu','mucokuscu2003@gmail.com','22100121025','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi','3','5458477978','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(401,12,6,2,'Râ”œâ•meysa','Ateâ”¼ÅŸ','atesrumeysa1206@gmail.com','25100011022','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1','5338159051','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(402,12,6,2,'â”¼Åahin Aras','Kâ”€â–’zâ”€â–’lkaya','aras.kizilkaya25@gmail.com','23030021040','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','2','5514591273','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(403,12,6,2,'Nisa','Yalâ”œÄŸâ”€â–’n','niisayalcinn@gmail.com','23030021033','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','2','+90 (541) 202 12 97','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(404,12,6,2,'â”¼Åâ”œâ•heda','Kurt','kurtsuheda622@gmail.com','23030021042','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','1','0535 650 65 03','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(405,12,6,2,'Beyzanur','Ersâ”œÃ‚z','ersozbeyzaa1@gmail.com','23030021064','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','1','5318417816','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(406,12,6,2,'â”œÂ£lkâ”œâ•','Kayra Koca','ulkukayra91@gmail.com','23030021066','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','2','5324791548','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(407,12,6,2,'Mustaf Bayram','Kahruman','mbkahruman@gmail.com','22100091036','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','4','5534915257','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(408,12,6,2,'Deniz','Akkul','akkuldeniz826@gmail.com','22100091064','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','4','5423105051','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(409,12,6,2,'Hilal','Erkan','hilalerkan62@gmail.com','24100091041','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','2','5425449928','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(410,12,6,2,'Yusuf','â”¼Åekerci','Yusufsekerci24@gmail.com','24100031023','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','1','540 735 37 24','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(411,12,6,2,'Yaâ”¼ÅŸar Emre','Koâ”œÄŸak','kocakyasaremre@gmail.com','25410051003','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','1/3/2006 0:00','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(412,12,6,2,'Eyâ”œâ•p Emir','Namlâ”€â–’','namli.emir06@gmail.com','24100091815','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','2','5316217800','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(413,12,6,2,'Mustafa','Uykun','mustafauykun564@gmail.com','22030031034','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Havacâ”€â–’lâ”€â–’k ve Uzay Mâ”œâ•hendisliâ”€ÅŸi','3','5331479317','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(414,12,6,2,'Umut Yasin','Akâ”œÄŸay','umutyasinakcay@mail.mail','','','','','','','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(415,12,6,2,'Zeynep','Tanju','zeyneptanju@mail.mail','','','','','','','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(416,12,6,2,'Aydâ”€â–’n Efe','Kaya','aydinefekaya@mail.mail','','','','','','','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(417,12,6,2,'Berk','Kavadar','berkkavadar@mail.mail','','','','','','','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(418,12,6,2,'Endâ”œâ•strici','Muhammed','endustricimuhammed@mail.mail1','','','','','','','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(419,12,6,2,'Atakan','Nergiz','anturkey20@gmail.com','25410051027','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1','5060632410','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(420,12,6,2,'Elifnur','Uysal','elifnuruysal14@gmail.com','24410051005','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2','05055329340','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(421,12,6,2,'Sinan','AKIN','47sinanakinnn@gmail.com','23410082019','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3','05436995801','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(422,12,6,2,'Mustafa','ACAR','mustafaacar@gmail.com','25100031409','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','2','5444314739','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(423,12,6,2,'ABDULLAH YASâ”€â–‘N','MERMER','mermer1731@gmail.com','20410051058','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','4','5326509630','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(424,12,6,2,'Gâ”œâ•lay','Noâ”€ÅŸay','gulaynogay4@gmail.com','22100011051','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','4','05462491802','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(425,12,6,2,'Ecrin Nur','â”¼Åoleum','soleumecrin@gmail.com','25040071019','','Fen Fakâ”œâ•ltesi','â”€â–‘statistik','1','05510738960','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(426,12,6,2,'Meleksima','Elmas','menekmeleksima@gmail.com','25040071029','','Fen Fakâ”œâ•ltesi','â”€â–‘statistik','1','5536716042','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(427,12,6,2,'Seval','Azman','azmanseval@gmail.com','23410051054','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3','05301793687','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(428,12,6,2,'Gâ”œâ•lâ”œÄŸin','Er','gulciner3@icloud.com','23410051055','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3','05364889047','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(429,12,6,2,'Beyza Nur','Bakkal','beyzaelif2024@gmail.com','233301028','','Teknoloji Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','3','05308233524','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(430,12,6,2,'â”œÃ»mer Kaan','Aydâ”€â–’n','kuskuksk@gmail.com','22090101047','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Radyo Televizyon ve Sinema','4','05512123876','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(431,12,6,2,'Hamide Nur','Coâ”¼ÅŸkun','hamidenrcskn@gmail.com','258231032001','','Mimarlâ”€â–’k ve Tasarâ”€â–’m Fakâ”œâ•ltesi','â”¼Åehir ve Bâ”œÃ‚lge Planlama','Mezun','05524182000','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(432,12,6,2,'â”œÃ»mer Faruk','â”œÂ£nlâ”œâ•','omerfarukunlu46@gmail.com','23100091077','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','3','05365655290','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(433,12,6,2,'Safa','Yâ”€â–’ldâ”€â–’râ”€â–’m','s9780840@gmail.com','24410051042','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2. Sâ”€â–’nâ”€â–’f','5546931142','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(434,12,6,2,'Emine','â”œÂ£zlâ”œâ•k','emineuzluk4242@gmail.com','24420101019','','Siyasal Bilgiler Fakâ”œâ•ltesi','Maliye','2. Sâ”€â–’nâ”€â–’f','05528320996','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(435,12,6,2,'Medine','Gâ”œÃ‚rgeâ”œÄŸ','gorgecmedine@gmail.com','25100011012','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','05466677135','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(436,12,6,2,'EYLâ”œÂ£L','ERDOâ”€ÅAN','eylulerdogan01@gmail.com','25100011058','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','905526380651','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(437,12,6,2,'Ali Erdem','Yolcuoâ”€ÅŸlu','yolcuoglualierdem07@gmail.com','25310421021','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Sosyal Bilgiler â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','05426347352','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(438,12,6,2,'Arda','â”œÃ»zer','Ardaaozer@leâ”œÃ‚wmsk','7482738282','','Tlemem','Jdlaâ”¼ÅŸqmdm','','8391837472','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(439,12,6,2,'Muhammet Emre','Yaman','eyaman651@gmail.com','25410031414','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Muhasebe ve Finans Yâ”œÃ‚netimi','3. Sâ”€â–’nâ”€â–’f','5455686403','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(440,12,6,2,'Engin','Altuntaâ”¼ÅŸ','enginaltuntas131@gmail.com','24410051023','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2. Sâ”€â–’nâ”€â–’f','05510946578','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(441,12,6,2,'Mevlâ”œâ•t','Koru','mevlutkoru5@gmail.com','24410051803','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2. Sâ”€â–’nâ”€â–’f','05326291303','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(442,12,6,2,'yusuf','kaya','yusufkaya.0@hotmail.com','24410051805','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2. Sâ”€â–’nâ”€â–’f','905386270301','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(443,12,6,2,'melike','yâ”œâ•ce','melikeycx@gmail.com','24100121034','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi (â”€â–‘ngilizce)','1. Sâ”€â–’nâ”€â–’f','5525383616','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(444,12,6,2,'Haluk','Bayatlâ”€â–’','halukbayatli8@gmail.com','23100011030','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','3. Sâ”€â–’nâ”€â–’f','05419619171','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(445,12,6,2,'Deniz','â”œÃ»zden','denizozden007@gmail.com','24410051002','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2. Sâ”€â–’nâ”€â–’f','905061900195','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(446,12,6,2,'Furkan','heray','f.furkan.heray@gmail.com','25030021058','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Uâ”œÄŸak Mâ”œâ•hendisliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','05334303664','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(447,12,6,2,'â”œÃ»mer Faruk','â”¼Åengelen','omersengelen54@gmail.com','24040071028','','Fen Fakâ”œâ•ltesi','â”€â–‘statistik','2. Sâ”€â–’nâ”€â–’f','5307961499','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(448,12,6,2,'Elifnur','Sevim','elifnursevim6@gmail.com','251482016','','Mâ”œâ•hendislik ve Doâ”€ÅŸa Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','05516479029','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(449,12,6,2,'Mustafa Eren','â”œÃ§elik','mstfclk2015@gmail.com','251482067','','Mâ”œâ•hendislik ve Doâ”€ÅŸa Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','05373017025','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(450,12,6,2,'â”€â–’lgâ”€â–’n su','yaâ”¼ÅŸar','suurellaaa@icloud.com','25490311003','','kos necmettin erbakan myo','bâ”œâ•yâ”œâ•k veri analistliâ”€ÅŸi','','05524303969','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(451,12,6,2,'Betâ”œâ•l','Karabacak','betulkarabacak007@gmail.com','25490221013','','Konya Organize Sanayi Bâ”œÃ‚lgesi Meslek Yâ”œâ•ksekokulu','Yapay Zeka Operatâ”œÃ‚rlâ”œâ•â”€ÅŸâ”œâ•','','05442504218','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(452,12,6,2,'Deniz','Parlak','denizparlak0910@gmail.com','25490221007','','NEU Kos Teknik Bilimler MYO','Yapay Zeka Operatâ”œÃ‚rlâ”œâ•â”€ÅŸâ”œâ•','','05374398943','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(453,12,6,2,'Zeynep Sema','â”¼Åahin','semazeynepsahin543@gmail.com','25490221033','','KOS Meslek Yâ”œâ•ksekokulu','Yapay zeka operatâ”œÃ‚rlâ”œâ•â”€ÅŸâ”œâ•','','05431078443','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(454,12,6,2,'Ayâ”¼ÅŸe kâ”œâ•bra','yaâ”€ÅŸcâ”€â–’lar','aysekubrayagcilar0777@gmail.com','25310401017','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu eâ”€ÅŸitim fakâ”œâ•ltesi','Sâ”€â–’nâ”€â–’f â”œÃ‚â”€ÅŸretmenliâ”€ÅŸi','','05362945777','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(455,12,6,2,'Elif','Kul','elifkul38@gmail.com','25310401043','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Sâ”€â–’nâ”€â–’f â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','','553 752 4942','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(456,12,6,2,'Muhammet ali','kozan','kozanmali@icloud.com','25310401037','','Ahmet keleâ”¼ÅŸoâ”€ÅŸlu','Sâ”€â–’nâ”€â–’f â”œÃ‚â”€ÅŸretmenliâ”€ÅŸi','','05514903839','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(457,12,6,2,'Mehmet Ali','Daâ”€ÅŸlâ”€â–’','Mhmtalidgl03@gmail.com','25020011004','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Dilbilimi','','05425124003','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(458,12,6,2,'Ayâ”¼ÅŸegâ”œâ•l','Cantâ”œâ•rkoâ”€ÅŸlu','aysglcntrkgl42@gmail.com','24020091018','','Sosyal Ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Sosyoloji','','0536 785 3485','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(459,12,6,2,'deneme','','fljsghsfdghâ”€â–’@dsfgopah.cpm','324523523452345','','fdkpsgpâ”€â–’gd','vdfpsodhsobâ”€â–’hn','','2133424154332','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(460,12,6,2,'â”¼Åerife','Bozdemir','sserifebozdemir@gmail.com','231239018','','Ticaret ve sanayi fakâ”œâ•ltesi','Sivil havacâ”€â–’lâ”€â–’k','','0545 483 2660','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(461,12,6,2,'Ayâ”¼ÅŸe Naz','Koca','aysenazkoca02@gmail.com','25410051049','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1. Sâ”€â–’nâ”€â–’f','5352822015','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(462,12,6,2,'Mahmut Asel','Akyâ”œâ•rek','m.asel1907@gmail.com','251482029','','Mâ”œâ•hendislik ve Doâ”€ÅŸa Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','05334977107','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(463,12,6,2,'Kaan','Turhan','kaan-turhan2004@hotmail.com','20244029061','','â”€â–‘ktisadi â”€â–‘dari Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1. Sâ”€â–’nâ”€â–’f','05428339418','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(464,12,6,2,'Tunahan','Karakâ”œÃ‚k','tuna9842@gmail.com','17501393722','','Bilgisayar ve Biliâ”¼ÅŸim Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','Mezun','05534145281','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(465,12,6,2,'Esra','Kara','kara42esra@gmail.com','242001090','','Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Ebelik','2. Sâ”€â–’nâ”€â–’f','5301806290','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(466,12,6,2,'Ozan','Yildiz','yildizozan2535@gmail.com','123456','','Alparslan Tâ”œâ•rkes Bilim ve Teknoloji â”œÂ£niversitesi','Yâ”œÃ‚netim Bilisim Sistemleri','Mezun','49015213150374','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(467,12,6,2,'Mustafa','Soy','soymustafa38@gmail.com','251482068','','Mâ”œâ•hendislik ve Doâ”€ÅŸa Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','05527471408','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(468,12,6,2,'mizgin','demir','mizgin.dmr377@gmail.com','236703044','','SHMYO','tâ”€â–’bbi laboratuvar teknikleri','','05364838523','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(469,12,6,2,'Sinem','Yalvaâ”œÄŸ','sinemyalvac95@gmail.com','22050021026','','Turizm Fakâ”œâ•ltesi','Turizm â”€â–‘â”¼ÅŸletmeciliâ”€ÅŸi','4. Sâ”€â–’nâ”€â–’f','05423645106','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(470,12,6,2,'Shakhrizoda','Mamadjanova','mamadjanovashahrizoda4@gmail.com','22050021803','','Turizm Fakâ”œâ•ltesi','Turizm â”€â–‘â”¼ÅŸletmeciliâ”€ÅŸi','4. Sâ”€â–’nâ”€â–’f','05418761652','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(471,12,6,2,'Yavuz','BOLAY','dr.yavuzbolay@gmail.com','18130041099','','Tâ”€â–’p Fakâ”œâ•ltesi','Tâ”€â–’p','4. Sâ”€â–’nâ”€â–’f','05526115801','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(472,12,6,2,'Berivan','Yalvaâ”œÄŸ','berivanyalvac05@icloud.com','22050021015','','Turizm Fakâ”œâ•ltesi','Turizm â”€â–‘â”¼ÅŸletmeciliâ”€ÅŸi','4. Sâ”€â–’nâ”€â–’f','5356530499','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(473,12,6,2,'â”œÃ»zge Sâ”€â–’la','Aktaâ”¼ÅŸ','ozge.sila31@gmail.com','22050021036','','Turizm Fakâ”œâ•ltesi','Turizm â”€â–‘â”¼ÅŸletmeciliâ”€ÅŸi','4. Sâ”€â–’nâ”€â–’f','05050023757','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(474,12,6,2,'Hasan','Kolsuz','hasan571kolsuz1453@yaani.com','241213064','','Bilgisayar ve Biliâ”¼ÅŸim Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','2. Sâ”€â–’nâ”€â–’f','05318662979','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(475,12,6,2,'â”œÂ£mmâ”œâ• Saadet','Duman','saadetduman42@gmail.com','20400011007','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu â”€â–‘lahiyat Fakâ”œâ•ltesi','â”€â–‘lahiyat','4. Sâ”€â–’nâ”€â–’f','05306856290','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(476,12,6,2,'Hatice','Kabakcâ”€â–’','haticekabakci49@gmail.com','241213095','','Bilgisayar ve Biliâ”¼ÅŸim Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','2. Sâ”€â–’nâ”€â–’f','5308556942','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(477,12,6,2,'Kamile','Parlak','kamileparlak29@gmail.com','24100091076','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','2. Sâ”€â–’nâ”€â–’f','05510471325','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(478,12,6,2,'Rabia','Emir','emirrabia15@gmail.com','23720031079','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Beslenme ve Diyetetik','3. Sâ”€â–’nâ”€â–’f','5525175419','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(479,12,6,2,'Aslâ”€â–’nur','Gâ”œâ•lnar','aslinurgulnar005@gmail.com','230111240','','Anadolu â”œÂ£niversitesi Aâ”œÄŸâ”€â–’kâ”œÃ‚â”€ÅŸretim Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3. Sâ”€â–’nâ”€â–’f','05465718607','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(480,12,6,2,'sena','kocakulak','senakocakulak1@gmail.com','251482010','','Mâ”œâ•hendislik ve Doâ”€ÅŸa Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','5388982899','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(481,12,6,2,'emre','akyavaâ”¼ÅŸ','eakyavas26@gmail.com','23030031034','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Havacâ”€â–’lâ”€â–’k ve Uzay Mâ”œâ•hendisliâ”€ÅŸi','2. Sâ”€â–’nâ”€â–’f','05414565340','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(482,12,6,2,'Ali','Uâ”€ÅŸur','f231220072@ktun.edu.tr','231220072','','Bilgisayar ve Biliâ”¼ÅŸim Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','3. Sâ”€â–’nâ”€â–’f','05523478015','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(483,12,6,2,'Betâ”œâ•l','Vurur','betulvurur648@gmail.com','23020031058','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Halkla â”€â–‘liâ”¼ÅŸkiler ve Reklamcâ”€â–’lâ”€â–’k','3. Sâ”€â–’nâ”€â–’f','05330403002','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(484,12,6,2,'Ceylin Nur','Akpâ”€â–’nar','ceylinnurakpinar0@gmail.com','25090111019','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Mimarlâ”€â–’k','1. Sâ”€â–’nâ”€â–’f','5510250925','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(485,12,6,2,'Fatih Mert','Mâ”œÂ£LAZIMOâ”€ÅLU','fatihmert24.04.2004@gmail.com','23040051031','','Fen Fakâ”œâ•ltesi','Molekâ”œâ•ler Biyoloji ve Genetik','3. Sâ”€â–’nâ”€â–’f','05466701391','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(486,12,6,2,'Fatâ”€â–’ma zehra','koâ”œÄŸak','fatimazehrakocak8@gmail.com','25400031037','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu â”€â–‘lahiyat Fakâ”œâ•ltesi','â”€â–‘lahiyat (Arapâ”œÄŸa)','Hazâ”€â–’rlâ”€â–’k','05370146242','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(487,12,6,2,'Derya','aâ”œÄŸâ”€â–’kâ”œÄŸa','acikcaderya23@icloud.com','25690261022','','Seydiâ”¼ÅŸehir Ahmet Cengiz Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','5528575026','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(488,12,6,2,'Simay  Hâ”œâ•snegâ”œâ•l','Ceylan','simayceylann66@gmail.com','232001018','','Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Ebelik','3. Sâ”€â–’nâ”€â–’f','05519353397','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(489,12,6,2,'Fatma Ebrar','Ulusoy','ebrarulusoy3850@gmail.com','25040071030','','Fen Fakâ”œâ•ltesi','â”€â–‘statistik','1. Sâ”€â–’nâ”€â–’f','05511228925','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(490,12,6,2,'Emir Buâ”€ÅŸra','Aktaâ”¼ÅŸ','emirbugraktas@gmail.com','25100011452','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','5331300642','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(491,12,6,2,'Belinay','Demirci','demircibelinay140@gmail.com','25410051054','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1. Sâ”€â–’nâ”€â–’f','(553) 961-08-03','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(492,12,6,2,'Muhammet Ali','Eroâ”€ÅŸlu','merogllu@gmail.com','25410051025','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1. Sâ”€â–’nâ”€â–’f','05443806042','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(493,12,6,2,'Ahmet Yaâ”¼ÅŸar','Sarâ”€â–’kaya','sarikayaahmetyasar9@gmail.com','25410051032','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1. Sâ”€â–’nâ”€â–’f','0551047573','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(494,12,6,2,'Edagâ”œâ•l','Uâ”€ÅŸur','edagul07ugur@gmail.com','241213063','','Bilgisayar ve Biliâ”¼ÅŸim Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','2. Sâ”€â–’nâ”€â–’f','5442197839','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(495,12,6,2,'Hatice','Tâ”œâ•rkmen','turkmenh713@gmail.com','24040081015','','Fen Fakâ”œâ•ltesi','Matematik ve Bilgisayar Bilimleri','2. Sâ”€â–’nâ”€â–’f','05525416221','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(496,12,6,2,'Melike','Karaoâ”€ÅŸlan','melikekaraoglan@outlook.com','120757041','','Atatâ”œâ•rk â”œÂ£niversitesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','Mezun','05305448946','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(497,12,6,2,'â”€â–‘brahim','â”œÄŸataloâ”€ÅŸlu','catalogluibrahim117@gmail.com','24040071034','','Fen Fakâ”œâ•ltesi','â”€â–‘statistik','2. Sâ”€â–’nâ”€â–’f','05435628174','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(498,12,6,2,'Mehmet','komaâ”œÄŸ','mehmetkomac@gmail.com','111111111111111','','â”€â–‘stanbul medeniyet â”œâ•niversitesi yâ”œâ•ksek lisans','Kamu yâ”œÃ‚netimi','Mezun','5387999587','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(499,12,6,2,'Eren','topcuoâ”€ÅŸlu','erentopcuoglu4881@gmail.com','24040071038','','Fen Fakâ”œâ•ltesi','â”€â–‘statistik','2. Sâ”€â–’nâ”€â–’f','05550040077','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(500,12,6,2,'muhammed','â”œÄŸiâ”œÄŸek','cicekmuhammed964@gmail.com','2656435','','Bilgisayar ve Biliâ”¼ÅŸim Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','Mezun','05465377587','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(501,12,6,2,'Ravza','Gâ”œÃ‚kal','ravzagokal125@gmail.com','25910021016','','SHMYO','â”€â–‘lk ve Acil Yardâ”€â–’m','','5363688446','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(502,12,6,2,'Duriye','Karayâ”€â–’lan','duriye.karayilan@gmail.com','25720051012','','nezahat keleâ”¼ÅŸoâ”€ÅŸlu saâ”€ÅŸlâ”€â–’k bilimleri fakâ”œâ•ltesi','sosyal hizmet','','0538 938 2474','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(503,12,6,2,'Berra','â”œÃ§akâ”€â–’câ”€â–’','berracakici@hotmail.com','25720051031','','Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Sosyal Hizmet','','05510869638','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(504,12,6,2,'Ahmet Tahir','Eldemir','ahmettahireldemir16@gmail.com','22100121050','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi (â”€â–‘ngilizce)','2. Sâ”€â–’nâ”€â–’f','05342475747','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(505,12,6,2,'Fadime Zehra','Kurtoâ”€ÅŸlu','fadimezehrakurtoglu@gmail.com','241229067','','Bilgisayar ve Biliâ”¼ÅŸim Bilimleri Fakâ”œâ•ltesi','Yazâ”€â–’lâ”€â–’m Mâ”œâ•hendisliâ”€ÅŸi','2. Sâ”€â–’nâ”€â–’f','05427904120','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(506,12,6,2,'Ceyda','Ardâ”€â–’â”œÄŸ','ceydaardic202072006@gmail.com','241229071','','Bilgisayar ve Biliâ”¼ÅŸim Bilimleri Fakâ”œâ•ltesi','Yazâ”€â–’lâ”€â–’m Mâ”œâ•hendisliâ”€ÅŸi','2. Sâ”€â–’nâ”€â–’f','05468681119','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(507,12,6,2,'Nursima','Karut','nursimakarut@gmail.com','24660110073','','Myo','Bilgisayar programcâ”€â–’lâ”€â–’â”€ÅŸâ”€â–’','1. Sâ”€â–’nâ”€â–’f','05462502608','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(508,12,6,2,'Sefa Davut','YILDIRIM','sefa.davut.yildirim@gmail.com','2520220001','','Beykoz â”œÂ£niversitesi','Bâ”œâ•yâ”œâ•k Veri Analistliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','05403545334','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(509,12,6,2,'Elif','â”œÃ§evikbaâ”¼ÅŸ','elifhanim12@gmail.com','251280008','','Ticaret ve sanayi fakâ”œâ•ltesi','E-ticaret ve pazarlama','1. Sâ”€â–’nâ”€â–’f','05519862096','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(510,12,6,2,'Mehmet Akif','â”œÃ§etin','makifcetin82@gmail.com','Tezli yâ”œâ•ksek lisans','','â”€â–‘ktisadi, â”€â–‘dari ve Sosyal Bilimler Fakâ”œâ•ltesi','â”€â–‘â”¼ÅŸletme','Mezun','05362826619','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(511,12,6,2,'Edanur','Erten','zumruduankra6306@gmail.com','20040081047','','Fen Fakâ”œâ•ltesi','Matematik ve Bilgisayar Bilimleri','4. Sâ”€â–’nâ”€â–’f','05538305848','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(512,12,6,2,'Salih Muhammed','Ulu','msalihulu60@gmail.com','231213087','','Bilgisayar ve Biliâ”¼ÅŸim Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','3. Sâ”€â–’nâ”€â–’f','+90 553 013 85 30','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(513,12,6,2,'Zeynep Eda','SAMANCI','zedasamanci@gmail.com','25370021023','','Seydiâ”¼ÅŸehir Ahmet Cengiz Mâ”œâ•hendislik Fakâ”œâ•ltesi','Makine Mâ”œâ•hendisliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','5545538866','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(514,12,6,2,'Yusuf','isaoâ”€ÅŸlu','yusuffisaoglu80@gmail.com','23410082021','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3. Sâ”€â–’nâ”€â–’f','5304264630','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(515,12,6,2,'â”€â–‘smail','â”œÃ§imen','ismlcmn@gmail.com','20120612','','Mehmet Akif Ersoy â”œÂ£niversitesi','Bâ”œÃ»TE','Mezun','05069209011','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(516,12,6,2,'Meryem','â”¼Åahin','mymshnn1210@gmail.com','25900081010','','Turizm Fakâ”œâ•ltesi','Turizm â”€â–‘â”¼ÅŸletmeciliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','5510678462','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(517,12,6,2,'Mert','Atcâ”€â–’','atcimert1@gmail.com','241238042','','Mâ”œâ•hendislik ve Doâ”€ÅŸa Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','2. Sâ”€â–’nâ”€â–’f','05326273894','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(518,12,6,2,'â”€â–‘nanâ”œÄŸ','Fâ”€â–’ndâ”€â–’k','inanc6238@gmail.com','24040071046','','Fen Fakâ”œâ•ltesi','â”€â–‘statistik','2. Sâ”€â–’nâ”€â–’f','05335925362','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(519,12,6,2,'Zeynep','Gâ”œâ•ndâ”œâ•z','zeynepgunduz2512@gmail.com','22040051046','','Fen Fakâ”œâ•ltesi','Molekâ”œâ•ler Biyoloji ve Genetik','3. Sâ”€â–’nâ”€â–’f','05529349882','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(520,12,6,2,'Elif SENA','KOCAKAYA','kocakayaelifsena@gmail.com','25050021013','','Turizm Fakâ”œâ•ltesi','Turizm â”€â–‘â”¼ÅŸletmeciliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','05314560048','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(521,12,6,2,'Hulki Kerem','Dereli','keremdereli2006@gmail.com','243032014','','Teknoloji Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','2. Sâ”€â–’nâ”€â–’f','5530218352','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(522,12,6,2,'Muhammet Mustafa','Karakuâ”¼ÅŸ','muhammet42035@gmail.com','251482022','','Mâ”œâ•hendislik ve Doâ”€ÅŸa Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','2. Sâ”€â–’nâ”€â–’f','(537) 977 4550','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(523,12,6,2,'Meltem','Kâ”€â–’lâ”€â–’nâ”œÄŸ','kilincmeltem68@gmail.com','21030031004','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi','Havacâ”€â–’lâ”€â–’k ve Uzay Mâ”œâ•hendisliâ”€ÅŸi','4. Sâ”€â–’nâ”€â–’f','05373256628','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(524,12,6,2,'Elif Gâ”œâ•l','Kayacâ”€â–’','elifgulkyc@gmail.com','251450003','','Mâ”œâ•hendislik ve Doâ”€ÅŸa Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','5307437848','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(525,12,6,2,'Safa','Kadâ”€â–’oâ”€ÅŸlu','safakadioglu7@gmail.com','25040081042','','Fen Fakâ”œâ•ltesi','Matematik ve Bilgisayar Bilimleri','1. Sâ”€â–’nâ”€â–’f','05010731217','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(526,12,6,2,'sâ”œâ•mer','â”œÃ‚zâ”¼ÅŸahin','ozsahinsumer@gmail.com','251482012','','Mâ”œâ•hendislik ve Doâ”€ÅŸa Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','05524212533','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(527,12,6,2,'Oâ”€ÅUZHAN KAAN','â”¼Åâ”€â–‘Mâ”¼ÅEK','osimsek001@gmail.com','25310361029','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Rehberlik ve Psikolojik Danâ”€â–’â”¼ÅŸmanlâ”€â–’k','1. Sâ”€â–’nâ”€â–’f','05050095231','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(528,12,6,2,'Melahat','Demir','demirmelahat84@gmail.com','24040081005','','Fen Fakâ”œâ•ltesi','Matematik ve Bilgisayar Bilimleri','2. Sâ”€â–’nâ”€â–’f','05076235474','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(529,12,6,2,'Fatâ”€â–’ma zehra','yâ”€â–’ldâ”€â–’râ”€â–’m','yildirimzehra426@gmail.com','24040081051','','Fen Fakâ”œâ•ltesi','Matematik ve Bilgisayar Bilimleri','2. Sâ”€â–’nâ”€â–’f','05453553687','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(530,12,6,2,'Mehmet Akif','AKKOâ”œÃ§','info@mefamex.com','240211003','','Aksaray â”œÂ£niversitesi Mâ”œâ•hendislik Fakâ”œâ•ltesi','Yazâ”€â–’lâ”€â–’m Mâ”œâ•hendisliâ”€ÅŸi','2. Sâ”€â–’nâ”€â–’f','05451483283','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(531,12,6,2,'Ebrar','â”œÃ»ztâ”œâ•rk','ebrarozturk810@gmail.com','25100071001','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Harita Mâ”œâ•hendisliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','5466740874','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(532,12,6,2,'Birnur Ziâ”¼ÅŸan','Ersan','zisanersan34@gmail.com','25900081009','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Mimarlâ”€â–’k','1. Sâ”€â–’nâ”€â–’f','05446720986','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(533,12,6,2,'Betâ”œâ•l','Gâ”œâ•ler','betulgulerr42@gmail.com','222010020026','','Mâ”œâ•hendislik ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi (â”€â–‘ngilizce)','3. Sâ”€â–’nâ”€â–’f','0553 362 09 74','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(534,12,6,2,'Yusuf','Dokuzoâ”€ÅŸlu','yusufdokuzoglu80@gmail.com','221222034','','Mâ”œâ•hendislik ve Doâ”€ÅŸa Bilimleri Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','4. Sâ”€â–’nâ”€â–’f','05411422446','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(535,12,6,2,'â”¼Åimal','Biâ”œÄŸer','bicersimal@gmail.com','22318271108','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','â”€â–‘ngilizce â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','4. Sâ”€â–’nâ”€â–’f','05061451771','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(536,12,6,2,'Sadiye','Agirman','sadiyeagirman321@gmail.com','232718030','','Fen Fakâ”œâ•ltesi','Aktâ”œâ•erya Bilimleri','3. Sâ”€â–’nâ”€â–’f','05441485247','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(537,12,6,2,'Sadiye','Acar','sadiyeacarr@gmail.com','24100121030','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi (â”€â–‘ngilizce)','1. Sâ”€â–’nâ”€â–’f','5456177547','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(538,12,6,2,'eda','â”œÃ‚zâ”¼ÅŸeker','ozsekereda1@gmail.com','23090121054','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','â”¼Åehir ve Bâ”œÃ‚lge Planlama','3. Sâ”€â–’nâ”€â–’f','5388910836','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(539,12,6,2,'Saliha','SALTALI','saltalisaliha@gmail.com','242705046','','Fen Fakâ”œâ•ltesi','â”€â–‘statistik','2. Sâ”€â–’nâ”€â–’f','5527197278','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(540,12,6,2,'Makbule','Genâ”œÄŸ','makbulegenc91@gmail.com','24100121031','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi (â”€â–‘ngilizce)','1. Sâ”€â–’nâ”€â–’f','5340216393','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(541,12,6,2,'Muhammed','Kibar','fikomriko243@gmail.com','250101108','','Bilgisayar ve Biliâ”¼ÅŸim Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','+90 5467677657','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(542,12,6,2,'fdgsdbg','','sdfgbdgfbs@fdsav.kjgy','','','gsbdfgsdf','dsbfgdsfgb','','435252352352','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(543,12,6,2,'Ali','AFâ”¼ÅAR','afsar@outlook.com.tr','','','Lisansâ”œâ•stâ”œâ• Enstitâ”œâ•sâ”œâ•','Siber Gâ”œâ•venlik','','05423729841','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(544,12,6,2,'Gâ”œâ•lnaz Gâ”œÃ‚kâ”œÄŸe','TOKLU','gulnazgokcetoklu@icloud.com','','','Mâ”œâ•hendislik','Elektrik elektronik mâ”œâ•hendisi','','05454424410','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(545,12,6,2,'Talha','â”¼Åahin','talhasahin565@hotmail.com','','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','','5455739362','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(546,12,6,2,'Nazlâ”€â–’','Aytekin','nazliaytekin378@gmail.com','23040051042','','Fen Fakâ”œâ•ltesi','Molekâ”œâ•ler Biyoloji ve Genetik','3. Sâ”€â–’nâ”€â–’f','05071094645','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(547,12,6,2,'Harun','â”œÃ»zkan','ozkanha7@gmail.com','636363773','','Atatâ”œâ•rk Eâ”€ÅŸitim fakâ”œâ•ltesi','Fen ve teknoloji','Mezun','0501 058 30 04','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(548,12,6,2,'Bahri','Karakâ”œÃ‚k','bahrikarakok@hotmail.com','22370031021','','Seydiâ”¼ÅŸehir Ahmet Cengiz Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','4. Sâ”€â–’nâ”€â–’f','5343379479','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(549,12,6,2,'Osman','Toraman','tahatoramann@gmail.com','24812602012','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik-Elektronik Mâ”œâ•hendisliâ”€ÅŸi','Mezun','05069238446','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(550,12,6,2,'fatma sude','vural','fatmasudevural@gmail.com','25020111031','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Tâ”œâ•rk Dili ve Edebiyatâ”€â–’','1. Sâ”€â–’nâ”€â–’f','05435599766','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(551,12,6,2,'Melike','â”œÃ»zveren','melike545ozveren@gmail.com','23040051033','','Fen Fakâ”œâ•ltesi','Molekâ”œâ•ler Biyoloji ve Genetik','3. Sâ”€â–’nâ”€â–’f','5538842900','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(552,12,6,2,'Esma','AKYâ”€â–‘â”€Åâ”€â–‘T','esmaakyigit@gmail.com','25317271008','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Arapâ”œÄŸa â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','Hazâ”€â–’rlâ”€â–’k','05379824748','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(553,12,6,2,'Hasan','sarâ”€â–’','xsarihasan@gmail.com','250208002','','Sanat ve Sosyal Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1. Sâ”€â–’nâ”€â–’f','5302070742','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(554,12,6,2,'Zelal','diyen','zelaldiyen4212@gmail.com','22420031011','','Siyasal Bilgiler Fakâ”œâ•ltesi','â”€â–‘â”¼ÅŸletme','4. Sâ”€â–’nâ”€â–’f','5314677071','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(555,12,6,2,'edanur','yalâ”œÄŸâ”€â–’n','edanuryalcin01@gmail.com','242030020005','','Tarâ”€â–’m ve Doâ”€ÅŸa Bilimleri Fakâ”œâ•ltesi','Bitkisel â”œÂ£retim ve Teknolojileri (â”€â–‘ngilizce)','1. Sâ”€â–’nâ”€â–’f','05519623245','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(556,12,6,2,'Emine','Assaf','emineassaf00@gmail.com','25440011839','','Hemâ”¼ÅŸirelik Fakâ”œâ•ltesi','Hemâ”¼ÅŸirelik','1. Sâ”€â–’nâ”€â–’f','05318544139','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(557,12,6,2,'â”œÃ»zlem','Taâ”¼ÅŸ','otas2697@gmail.com','22020111019','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','Tâ”œâ•rk Dili ve Edebiyatâ”€â–’','4. Sâ”€â–’nâ”€â–’f','905551920163','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(558,12,6,2,'Bekir','Aydoâ”€ÅŸan','bekirbjk1907@gmail.com','574674','','Ankara â”œÂ£ni','Elektronik ticaret ve yâ”œÃ‚netimi','1. Sâ”€â–’nâ”€â–’f','5364200020','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(559,12,6,2,'Selin','Saburlu','selinsaburlu@gmail.com','253004023','','Beyâ”¼ÅŸehir Ali Akkanat â”€â–‘â”¼ÅŸletme Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1. Sâ”€â–’nâ”€â–’f','5426122525','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(560,12,6,2,'Taha','Erdoâ”€ÅŸan','tahaerdogan109@gmail.com','253004026','','Beyâ”¼ÅŸehir Ali Akkanat â”€â–‘â”¼ÅŸletme Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1. Sâ”€â–’nâ”€â–’f','05319619206','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(561,12,6,2,'Selahattin Doruk','Gâ”œâ•ndâ”œâ•z','gunduz18810@gmail.com','1926','','Makine','Makine teknolojileri savunma sanayii','3. Sâ”€â–’nâ”€â–’f','05461380722','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(562,12,6,2,'â”€â–‘layda','â”¼ÅAHâ”€â–‘N','ilaydasahin4846@gmail.com','21420091040','','Siyasal Bilgiler Fakâ”œâ•ltesi','Siyaset Bilimi ve Uluslararasâ”€â–’ â”€â–‘liâ”¼ÅŸkiler (â”€â–‘ngilizce)','3. Sâ”€â–’nâ”€â–’f','05432487109','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(563,12,6,2,'Beyzanur â”€â–‘kra','Yâ”€â–’ldâ”€â–’z','beyzanurikray@gmail.com','21420091044','','Siyasal Bilgiler Fakâ”œâ•ltesi','Siyaset Bilimi ve Uluslararasâ”€â–’ â”€â–‘liâ”¼ÅŸkiler (â”€â–‘ngilizce)','4. Sâ”€â–’nâ”€â–’f','05536294800','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(564,12,6,2,'Yunus Emre','KARADAâ”€Å','karadagyunusemre140@gmail.com','221030210037','','mâ”œâ•hendislik fakâ”œâ•ltesi','bilgisayar mâ”œâ•hendisliâ”€ÅŸi','4. Sâ”€â–’nâ”€â–’f','05315904943','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(565,12,6,2,'Ali Can','KORCAK','korcakalicann@gmail.com','2525705003','','Fen Fakâ”œâ•ltesi','â”€â–‘statistik','1. Sâ”€â–’nâ”€â–’f','05523629823','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(566,12,6,2,'Zehra','â”œÃ»zdur','zehraozdur6@gmail.com','250101075','','Bilgisayar ve Biliâ”¼ÅŸim Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','507 379 59 75','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(567,12,6,2,'Sâ”œâ•meyye','â”œÂ£nal','sunal7166@gmail.com','25720041011','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Saâ”€ÅŸlâ”€â–’k Yâ”œÃ‚netimi','1. Sâ”€â–’nâ”€â–’f','05526932252','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(568,12,6,2,'Sâ”œâ•eda Dilara','Tire','sdilaratire@gmail.com','25950011098','','Tâ”€â–’p Fakâ”œâ•ltesi','Tâ”€â–’p','1. Sâ”€â–’nâ”€â–’f','05551565616','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(569,12,6,2,'defne','kayserli','defnekayserli@gmail.com','25050021018','','Turizm Fakâ”œâ•ltesi','Turizm â”€â–‘â”¼ÅŸletmeciliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','05330519084','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(570,12,6,2,'Râ”œâ•meysa Eslem','Yanar','rumeysaeslemyanar@gmail.com','25050021005','','Turizm Fakâ”œâ•ltesi','Turizm â”€â–‘â”¼ÅŸletmeciliâ”€ÅŸi','1. Sâ”€â–’nâ”€â–’f','5541581633','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(571,12,6,2,'Mustafa','Kafes','Mkafes@erbakan.edu.tr','00000000000','','Nezahat Keleâ”¼ÅŸoâ”€ÅŸlu Saâ”€ÅŸlâ”€â–’k Bilimleri Fakâ”œâ•ltesi','Saâ”€ÅŸlâ”€â–’k Yâ”œÃ‚netimi','Mezun','05301153679','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(572,12,6,2,'Safiye','Ural','safiyeural4@gmail.com','24100011072','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','2. Sâ”€â–’nâ”€â–’f','05519392330','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(573,12,6,2,'Yusuf','â”œÃ§ekmez','ceknezyusuf@gmail.com','25310361041','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','Rehberlik ve Psikolojik Danâ”€â–’â”¼ÅŸmanlâ”€â–’k','1. Sâ”€â–’nâ”€â–’f','05302074043','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(574,12,6,2,'enes','altay','enesaltay4316@gmail.com','25410051404','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','2. Sâ”€â–’nâ”€â–’f','905015774316','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(575,12,6,2,'ahmet ali','urfalâ”€â–’','ahmetaliurfali@gmail.com','25040061034','','Fen Fakâ”œâ•ltesi','Biyoteknoloji','1. Sâ”€â–’nâ”€â–’f','05356625236','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(576,12,6,2,'Mehmet','Konukâ”œÄŸu','mehmetext68@gmail.com','231229084','','Bilgisayar ve Biliâ”¼ÅŸim Bilimleri Fakâ”œâ•ltesi','Yazâ”€â–’lâ”€â–’m Mâ”œâ•hendisliâ”€ÅŸi','4. Sâ”€â–’nâ”€â–’f','05536586801','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(577,12,6,2,'Asiye â”¼Åeyma','Aras','seyma02aras@gmail.com','200316048','','Mâ”œâ•hendislik ve Doâ”€ÅŸa Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','Mezun','05516432703','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(578,12,6,2,'Esat','yalnâ”€â–’zcalâ”€â–’','esatylnzcl@gmail.com','223311017','','Teknoloji Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','4. Sâ”€â–’nâ”€â–’f','05413157390','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(579,12,6,2,'Seher','BAHAR','bahar.seherr01@gmail.com','22318271110','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi','â”€â–‘ngilizce â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','4. Sâ”€â–’nâ”€â–’f','05314247078','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(580,12,6,2,'Gizem','Dede','gizemdede5555@gmail.com','232705012','','Fen Fakâ”œâ•ltesi','â”€â–‘statistik','3. Sâ”€â–’nâ”€â–’f','05432255257','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(581,12,6,2,'Esma','â”œÃ‚zkul','esmarada123@gmail.com','230905050','','â”€â–‘letiâ”¼ÅŸim Fakâ”œâ•ltesi','Gazetecilik','3. Sâ”€â–’nâ”€â–’f','05511095786','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(582,12,6,2,'Merve','Kahvecioâ”€ÅŸlu','kahvecioglumerve6@gmail.com','232632012','','Edebiyat Fakâ”œâ•ltesi','â”€â–‘ngiliz Dili ve Edebiyatâ”€â–’','2. Sâ”€â–’nâ”€â–’f','05511951870','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(583,12,6,2,'Rabia hira','Kara','kararabiahira@gmail.com','255914028','','Huâ”€ÅŸlu Meslek Yâ”œâ•ksek okulu /Selâ”œÄŸuk â”œâ•niversitesi','Biliâ”¼ÅŸim gâ”œâ•venlik teknolojisi','1. Sâ”€â–’nâ”€â–’f','5528204413','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(584,12,6,2,'Azra','Doâ”€ÅŸdu','azradogdu85@gmail.com','255914053','','Huâ”€ÅŸlu meslek yâ”œâ•ksekokulu','Biliâ”¼ÅŸim gâ”œâ•venlik teknolojileri','1. Sâ”€â–’nâ”€â–’f','05511441013','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(585,12,6,2,'Sâ”œâ•meyye','Altâ”€â–’ntaâ”¼ÅŸ','sumeyyealtintas32@gmail.com','231220003','','Bilgisayar ve Biliâ”¼ÅŸim Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','3. Sâ”€â–’nâ”€â–’f','05538955142','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(586,12,6,2,'Ebru','Tokataâ”¼ÅŸ','ebrutokatas@gmail.com','23410111059','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Finans ve Bankacâ”€â–’lâ”€â–’k','3. Sâ”€â–’nâ”€â–’f','05527796601','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(587,12,6,2,'Beyza','â”œÂ£lkâ”œâ•men','beyzaulkumen.98@gmail.com','21601713','','Mâ”œâ•hendislik ve Doâ”€ÅŸa Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','Mezun','05453882923','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(588,12,6,2,'Volkan','bal','volkanbal@nextgengrow.com.tr','52','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1. Sâ”€â–’nâ”€â–’f','05539778116','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(589,12,6,2,'Saliha','â”€â–‘pteâ”¼ÅŸ','salihaipts@gmail.com','258229062003','','Bilgisayar ve Biliâ”¼ÅŸim Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','Mezun','05558802885','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(590,12,6,2,'Gâ”œâ•lsâ”œâ•m','Bayrakdar','glsmbyrkdrr@gmail.com','241229031','','Bilgisayar ve Biliâ”¼ÅŸim Bilimleri Fakâ”œâ•ltesi','Yazâ”€â–’lâ”€â–’m Mâ”œâ•hendisliâ”€ÅŸi','2. Sâ”€â–’nâ”€â–’f','05050808366','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(591,12,6,2,'Yunus Emre','Coâ”¼ÅŸkun','webyemrec@gmail.com','211220037','','Bilgisayar ve Biliâ”¼ÅŸim Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','4. Sâ”€â–’nâ”€â–’f','05469797895','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(592,12,6,2,'Canan Kevsernur','Doâ”€ÅŸan','dogancanankevsernur@gmail.com','22100121028','','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi (â”€â–‘ngilizce)','3. Sâ”€â–’nâ”€â–’f','05069197478','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(593,12,6,2,'Oâ”€ÅŸuz','Yâ”€â–’ldâ”€â–’râ”€â–’m','muhammedoyildirim@gmail.com','25132048','','â”€â–‘ktisadi, â”€â–‘dari ve Sosyal Bilimler Fakâ”œâ•ltesi','Sigortacâ”€â–’lâ”€â–’k ve Sosyal Gâ”œâ•venlik','Mezun','05074184443','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(594,12,6,2,'BETâ”œÂ£L ZEHRA','ALTUNIâ”¼ÅIK','betulaltunskk@gmail.com','23410082033','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3. Sâ”€â–’nâ”€â–’f','05412107836','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(595,12,6,2,'Burak Efe','Altâ”€â–’ntop','burakealtintop@gmail.com','241238022','','Mâ”œâ•hendislik ve Doâ”€ÅŸa Bilimleri Fakâ”œâ•ltesi','Bilgisayar Mâ”œâ•hendisliâ”€ÅŸi','2. Sâ”€â–’nâ”€â–’f','05315150716','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(596,12,6,2,'â”¼ÅEYMA','ARIKAN','sarikan463@gmail.com','25081031104','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','1. Sâ”€â–’nâ”€â–’f','05333515203','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(597,12,6,2,'Merve','Altunsoy','mervealtunsoy2005@gmail.com','23090121072','','Gâ”œâ•zel Sanatlar ve Mimarlâ”€â–’k Fakâ”œâ•ltesi','â”¼Åehir ve Bâ”œÃ‚lge Planlama','3. Sâ”€â–’nâ”€â–’f','05510633115','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(598,12,6,2,'Rabia','Ok','rabiaok3500@gmail.com','22040051054','','Fen Fakâ”œâ•ltesi','Molekâ”œâ•ler Biyoloji ve Genetik','4. Sâ”€â–’nâ”€â–’f','05068525263','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(599,12,6,2,'Bahadâ”€â–’r Talha','Karaca','talhakaraca36@gmail.com','','','Bilgisayar mâ”œâ•hendisliâ”€ÅŸi 1','','','5510489934','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(600,12,6,2,'Azra Zeynep','Uyar','azrazeynepuyar@gmail.com','','','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','','','05061532081','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(601,12,6,2,'Zeynep','Hiâ”œÄŸyâ”€â–’lmaz','zeynephcylmz0@gmail.com','','','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','','','0539 898 52 75','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(602,12,6,2,'Alperen','â”œÃ§avuâ”¼ÅŸoâ”€ÅŸlu','alperenncavusoglu@gmail.com','','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi / Uluslararasâ”€â–’ Ticaret ve Finansman','','','5072437200','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(603,12,6,2,'Nisanur','SELEK','nisanur_selek4@hotmail.com','','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi/Havacâ”€â–’lâ”€â–’k Yâ”œÃ‚netimi/ 2.Sâ”€â–’nâ”€â–’f','','','5519420004','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(604,12,6,2,'Fatma','Varol','fvarol499@gmail.com','24410051806','','Ubf/YBS','','','05415340218','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(605,12,6,2,'Mustafa Can','Baâ”¼ÅŸalan','mstfbsln2@gmail.com','25100061027','','Mâ”œâ•hendislik fakâ”œâ•ltesi enerji sistemleri mâ”œâ•hendisliâ”€ÅŸi','','','05519898911','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(606,12,6,2,'FATâ”€â–‘MA','NDâ”€â–‘AYE','tm36354758@gmail.com','25410051704','','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','','','05069127556','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(607,12,6,2,'â”¼Åerifenur','sâ”œâ•mer','Serifenursmer@gmail.com','21100091035','','Mâ”œâ•hendislik fakâ”œâ•ltesi/makine mâ”œâ•hendisliâ”€ÅŸi','','','05522042559','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(608,12,6,2,'Ebrar','Demirci','ebrardeemirci@gmail.com','22420101042','','siyasal bilgiler/maliye','','','05384303841','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(609,12,6,2,'Sakâ”€â–’na','BILADAMA','biladamasakina@gmail.com','25440011701','','Hemâ”¼ÅŸirelik fakâ”œâ•ltesi/ Hemâ”¼ÅŸirelik','','','05518128504','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(610,12,6,2,'Zeynep','Aslantaâ”¼ÅŸ','aslantaszeynep21@gmail.com','22310481853','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu Eâ”€ÅŸitim Fakâ”œâ•ltesi Tâ”œâ•rkâ”œÄŸe â”œÃ»â”€ÅŸretmenliâ”€ÅŸi','','','05300527564','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(611,12,6,2,'Besna','Dalmâ”€â–’â”¼ÅŸ','dalmisbesna0@gmail.com','23820511002','','Fen bilimleri ens. /MBG','','','05439744503','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(612,12,6,2,'SIFA','UWIMANA','uwimanasifa268@gmail.com','24500011866','','â”€â–‘LAHâ”€â–‘YAT','','','05518129872','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(613,12,6,2,'Sajra','Tutmic','sajratutmic8@gmail.com','25100121824','','Mâ”œâ•hendislik Fakâ”œâ•ltesi- Endâ”œâ•stri mâ”œâ•hendisliâ”€ÅŸi','','','5315054308','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(614,12,6,2,'Merve','Nalinci','mervenalinci.mn@gmail.com','25900101001','','Meram Meslek Yâ”œâ•ksekokulu/Aâ”¼ÅŸâ”œÄŸâ”€â–’lâ”€â–’k','','','5076102323','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(615,12,6,2,'Makbule','Baz','bazzmakbule@gmail.com','25900101035','','Meram Meslek Yâ”œâ•ksekokulu / Otel lokanta ve ikram hizmetleri bâ”œÃ‚lâ”œâ•mâ”œâ•','','','05516692272','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(616,12,6,2,'Aygâ”œâ•l','','aygul.taspunar@iCloud.com','25900101022','','Meram meslek yâ”œâ•ksekokulu/ Aâ”¼ÅŸâ”œÄŸâ”€â–’lâ”€â–’k','','','5427663881','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(617,12,6,2,'Esma emine','kumru','Esmaeminekumru@gmail.com','25900101038','','Aâ”¼ÅŸâ”œÄŸâ”€â–’lâ”€â–’k','','','5510777532','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(618,12,6,2,'Hatice','Iâ”¼ÅŸâ”€â–’k','hatice13isik@gmail.com','25900101009','','Meram Meslek yâ”œâ•ksekokulu / Aâ”¼ÅŸâ”œÄŸâ”€â–’lâ”€â–’k','','','05526465262','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(619,12,6,2,'Hasna','Hussain','hasnahussain021@gmail.com','25400011890','','â”€â–‘lahiyat','','','5519579595','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(620,12,6,2,'Roukhaya ATTEIB ABDULLAH','YE','Roukhaya39@gmail.com','25400011903','','â”€â–‘lahiyat','','','551 508 46 87','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(621,12,6,2,'Gâ”œÃ‚nâ”œâ•l','Altun','gnlaltn2019@gmail.com','23310421048','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu eâ”€ÅŸitim fakâ”œâ•ltesi','','','05304857584','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(622,12,6,2,'Durdu Mehmet','Evleksiz','ozcnozg74@gmail.com','53310421053','','Ahmet keleâ”¼ÅŸoâ”€ÅŸlu eâ”€ÅŸitim fakâ”œâ•ltesi','','','05050029526','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(623,12,6,2,'Saido','maslah','saidomaxamed255@gmail.com','25400011898','','Ahmet Keleâ”¼ÅŸoâ”€ÅŸlu â”€â–‘lahiyat Fakâ”œâ•ltesi- â”€â–‘lahiyat','','','5511369318','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(624,12,6,2,'â”€â–‘klim','â”œÃ»zdinâ”œÄŸ','iklim.ozdinc.667@gmail.com','25440011081','','Hemâ”¼ÅŸirelik Fakâ”œâ•ltesi / Hemâ”¼ÅŸirelik','','','05372266065','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(625,12,6,2,'Merve','sakarya','Mervesakarya128@gmail.com','25440011086','','Hemâ”¼ÅŸirelik fakâ”œâ•ltesi/Hemâ”¼ÅŸirelik','','','0538 341 34 92','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(626,12,6,2,'Burhan','â”œÃ»zer','burhan33tr@gmail.com','25410051033','','Uygulamalâ”€â–’ bilimler fakâ”œâ•ltesi/ Yâ”œÃ‚netim biliâ”¼ÅŸim sistemleri','','','05313436205','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(627,12,6,2,'Seferiyenur','Berkama','nurseferiye45@gmail.com','23020061063','','Sosyal ve Beâ”¼ÅŸeri Bilimler Fakâ”œâ•ltesi','','','05522645764','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(628,12,6,2,'yaâ”€ÅŸmur','yâ”€â–’lmaz','yagmuryilmaz0645@gmail.com','24410051072','','uygulamali bilimler yonetim bilisim sistemleri','','','05392150474','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(629,12,6,2,'Zeinebou','Tandia','coumbatandia2003@gmail.com','23410111902','','Uygulamalâ”€â–’ bilimler fakâ”œâ•ltesi /Lojistik yâ”œÃ‚netimi','','','05376451464','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(630,12,6,2,'Zarifenur','â”œÃ§oker','Zarifecoker003@gmail.com','22100121004','','Mâ”œâ•hendislik Fakâ”œâ•ltesi/ Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi','','','5432276014','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(631,12,6,2,'Betâ”œâ•l','Dikik','betuldikik92@gmail.com','22100121032','','Mâ”œâ•hendislik Fakâ”œâ•ltesi Endâ”œâ•stri Mâ”œâ•hendisliâ”€ÅŸi','','','05511590937','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(632,12,6,2,'Asiye','Yurdakul','asiyeyurdakul11419@gmail.com','22410051071','','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','','','05330251559','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(633,12,6,2,'Yasimini','Umali','kavsieyassy@gmail.com','25400011914','','â”€â–‘LAHâ”€â–‘YAT','','','5515084377','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(634,12,6,2,'ABDIWAHAB MOHAMUD','ISSE','abdiwahabmohamudisse66@gmail.com','25030101803','','Havacâ”€â–’lâ”€â–’k ve Uzay Bilimleri Fakâ”œâ•ltesi / Havacâ”€â–’lâ”€â–’k ve Uzay Mâ”œâ•hendisliâ”€ÅŸi Bâ”œÃ‚lâ”œâ•mâ”œâ•','','','5079278411','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(635,12,6,2,'Semanur','Atâ”€â–’rak','atiraksemanur@gmail.com','24410051048','','Uygulamalâ”€â–’ Bilimler fakâ”œâ•ltesi','','','5524349465','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(636,12,6,2,'Sabrina','Medar','medarsabrina@gmail.com','25400011889','','â”€â–‘lahiyat fakâ”œâ•ltesi','','','05015539300','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(637,12,6,2,'SITI AISYAH','RAMADHANI','sitaisyahramadhani20@gmail.com','25400011904','','â”€â–‘LAHâ”€â–‘YAT','','','085811328315','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(638,12,6,2,'Hayanee','Awae','YARMEEDALING@GMAIL.COM','25400011897','','Ilahiyat fakâ”œâ•ltesi','','','05515105699','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(639,12,6,2,'Fatima â”¼Åerif','Ahmed','fsharif2028@gmail.com','25400011902','','Ahmet keleâ”¼ÅŸoglu ilahiyat /ilahiyat','','','501 562 56 80','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(640,12,6,2,'Berat','sarâ”€â–’','Sariberat183@gmail.com','25030101045','','Havacâ”€â–’lâ”€â–’k ve uzay mâ”œâ•hendislâ”€â–’gâ”€â–’','','','05434985389','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(641,12,6,2,'Fardowsa','','Fardowsmuseibrahim','24720041803','','Saâ”€ÅŸlâ”€â–’k yâ”œÃ‚netimi','','','05387832730','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(642,12,6,2,'Ahmet','Aâ”¼ÅŸkar','ahmetaskar0755@gmail.com','05518520553','','Uygulamalâ”€â–’ Bilimler / Uluslararasâ”€â–’ Ticaret','','','0551 852 0553','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(643,12,6,2,'Arda Genar','â”€â–‘pek','ardagenar42@gmail.com','25410141048','','Uygulamalâ”€â–’ Bilimler/Uluslararasâ”€â–’ Ticaret','','','05511479246','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(644,12,6,2,'Atlas','Isak','atlas13@gmail.com','25060505137','','Havacâ”€â–’lâ”€â–’k yâ”œÃ‚netimi','','','616643489','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(645,12,6,2,'Merve','can','Mervecn07@icloud.com','23310591003','','AKEF/Kimya â”œÃ‚â”€ÅŸretmenliâ”€ÅŸi','','','05314919759','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(646,12,6,2,'Elif','Yâ”€â–’lmaz','elifylmzz06@gmail.com','24400011117','','â”€â–‘lahiyat','','','05541428165','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(647,12,6,2,'Nidanur','Gâ”œâ•ler','nidanurrguler@gmail.com','24400041015','','â”€â–‘lahiyat','','','05510617527','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(648,12,6,2,'Sâ”œâ•meyye','Akâ”€â–’n','sumeyye.akin008@gmail.com','25100131021','','Mâ”œâ•hendislik fakâ”œâ•ltesi/ Metalurji ve malzeme mâ”œâ•hendisliâ”€ÅŸi','','','5516541706','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(649,12,6,2,'Fatma','Gâ”œâ•ngâ”œÃ‚r','fatmagungorr27@gmail.com','','','â”€â–‘â”¼ÅŸletme fakâ”œâ•ltesi','','','05447740827','active',NULL,'2026-07-01 18:47:14','2026-07-01 18:47:14'),(650,12,6,NULL,'Test','Kullanici','test@example.com','112233','Istanbul','Muh','Bilgisayar','3','12345','active',NULL,'2026-07-03 18:39:47','2026-07-03 18:39:47'),(651,12,6,NULL,'deneme','','admin@firma.com','2435234345','Konya/Selâ”œÄŸuklu','â”€â–‘ktisadi ve â”€â–‘dari Bilimler','Bilgisayar Mâ”œâ•h','4','(243) 243 24 32','suspended','','2026-07-03 18:41:31','2026-07-03 18:42:06');
/*!40000 ALTER TABLE `app_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_mentorship_notes`
--

DROP TABLE IF EXISTS `app_mentorship_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_mentorship_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pair_id` int(11) DEFAULT NULL,
  `writer_id` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `meeting_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_mentorship_notes`
--

LOCK TABLES `app_mentorship_notes` WRITE;
/*!40000 ALTER TABLE `app_mentorship_notes` DISABLE KEYS */;
INSERT INTO `app_mentorship_notes` VALUES (1,1,2,'hgfdfg hdfh dfhdfg hdf h','2026-07-01','2026-07-01 17:31:37'),(2,2,2,'ds fgdg sdg dsgf dsf gdsg','2026-07-01','2026-07-01 17:31:47');
/*!40000 ALTER TABLE `app_mentorship_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_mentorship_pairs`
--

DROP TABLE IF EXISTS `app_mentorship_pairs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_mentorship_pairs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `mentor_id` int(11) DEFAULT NULL,
  `mentee_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_mentorship_pairs`
--

LOCK TABLES `app_mentorship_pairs` WRITE;
/*!40000 ALTER TABLE `app_mentorship_pairs` DISABLE KEYS */;
INSERT INTO `app_mentorship_pairs` VALUES (1,31,6,3,2,'2026-07-01 17:31:32','completed'),(2,31,6,2,3,'2026-07-01 17:31:43','active'),(3,31,6,3,2,'2026-07-03 00:17:16','active');
/*!40000 ALTER TABLE `app_mentorship_pairs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_partners`
--

DROP TABLE IF EXISTS `app_partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `venue_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `map_link` varchar(255) DEFAULT NULL,
  `discount_detail` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_partners`
--

LOCK TABLES `app_partners` WRITE;
/*!40000 ALTER TABLE `app_partners` DISABLE KEYS */;
INSERT INTO `app_partners` VALUES (1,16,6,'fsa fassfadf asfas','asdff sdasdfa fdsa sadf fsasdf','','','f sadsafasf','4535325235','uploads/partners/prt_6a454af561483.jpeg',1,'2','2026-07-01 17:14:29');
/*!40000 ALTER TABLE `app_partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_social_post_comments`
--

DROP TABLE IF EXISTS `app_social_post_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_social_post_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_social_post_comments`
--

LOCK TABLES `app_social_post_comments` WRITE;
/*!40000 ALTER TABLE `app_social_post_comments` DISABLE KEYS */;
INSERT INTO `app_social_post_comments` VALUES (1,1,2,'gfdgsbfdbgd','uploads/social/soc_comment_6a454ce5ec973.jpeg','WhatsApp Image 2026-05-15 at 10.29.39.jpeg','2026-07-01 17:22:45');
/*!40000 ALTER TABLE `app_social_post_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_social_posts`
--

DROP TABLE IF EXISTS `app_social_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_social_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `creator_id` int(11) DEFAULT NULL,
  `assigned_to` varchar(255) DEFAULT NULL,
  `platform` varchar(255) DEFAULT NULL,
  `publish_date` date DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'draft',
  `content_text` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_social_posts`
--

LOCK TABLES `app_social_posts` WRITE;
/*!40000 ALTER TABLE `app_social_posts` DISABLE KEYS */;
INSERT INTO `app_social_posts` VALUES (1,32,6,2,'2','Instagram','2026-07-01','ggfdsgfsdfg','2026-07-01 17:20:11','published','hfggfovgâ”€â–’','soc_6a454cd8d727e.jpeg'),(2,32,6,2,'2','Instagram','2026-07-03','hgfd','2026-07-03 00:37:25','review',NULL,NULL);
/*!40000 ALTER TABLE `app_social_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_task_assignees`
--

DROP TABLE IF EXISTS `app_task_assignees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_task_assignees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `completed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_app_task_assignees_task` (`task_id`),
  KEY `idx_app_task_assignees_user` (`user_id`),
  CONSTRAINT `fk_app_task_assignees_task` FOREIGN KEY (`task_id`) REFERENCES `app_tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_app_task_assignees_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_task_assignees`
--

LOCK TABLES `app_task_assignees` WRITE;
/*!40000 ALTER TABLE `app_task_assignees` DISABLE KEYS */;
INSERT INTO `app_task_assignees` VALUES (1,1,2,'completed','2026-07-03 17:56:52'),(2,1,3,'completed','2026-07-03 17:56:56'),(3,2,2,'completed','2026-07-03 18:04:19'),(4,3,2,'acknowledged',NULL),(5,4,2,'completed','2026-07-03 18:20:35'),(6,5,2,'completed','2026-07-03 18:20:28'),(7,5,3,'completed','2026-07-03 18:20:25');
/*!40000 ALTER TABLE `app_task_assignees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_task_comments`
--

DROP TABLE IF EXISTS `app_task_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_task_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_app_task_comments_task` (`task_id`),
  KEY `idx_app_task_comments_user` (`user_id`),
  CONSTRAINT `fk_app_task_comments_task` FOREIGN KEY (`task_id`) REFERENCES `app_tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_app_task_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_task_comments`
--

LOCK TABLES `app_task_comments` WRITE;
/*!40000 ALTER TABLE `app_task_comments` DISABLE KEYS */;
INSERT INTO `app_task_comments` VALUES (1,1,2,'fsdafasdf','2026-07-03 15:56:31'),(2,2,2,'cxzczx','2026-07-03 16:12:02');
/*!40000 ALTER TABLE `app_task_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_task_team_members`
--

DROP TABLE IF EXISTS `app_task_team_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_task_team_members` (
  `team_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`team_id`,`user_id`),
  KEY `idx_app_task_team_members_user` (`user_id`),
  CONSTRAINT `fk_app_task_team_members_team` FOREIGN KEY (`team_id`) REFERENCES `app_task_teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_app_task_team_members_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_task_team_members`
--

LOCK TABLES `app_task_team_members` WRITE;
/*!40000 ALTER TABLE `app_task_team_members` DISABLE KEYS */;
INSERT INTO `app_task_team_members` VALUES (1,2),(1,3);
/*!40000 ALTER TABLE `app_task_team_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_task_teams`
--

DROP TABLE IF EXISTS `app_task_teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_task_teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_app_task_teams_created_by` (`created_by`),
  CONSTRAINT `fk_app_task_teams_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_task_teams`
--

LOCK TABLES `app_task_teams` WRITE;
/*!40000 ALTER TABLE `app_task_teams` DISABLE KEYS */;
INSERT INTO `app_task_teams` VALUES (1,'denemne','',2,'2026-07-03 15:55:41');
/*!40000 ALTER TABLE `app_task_teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_tasks`
--

DROP TABLE IF EXISTS `app_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `due_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_app_tasks_created_by` (`created_by`),
  CONSTRAINT `fk_app_tasks_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_tasks`
--

LOCK TABLES `app_tasks` WRITE;
/*!40000 ALTER TABLE `app_tasks` DISABLE KEYS */;
INSERT INTO `app_tasks` VALUES (1,'fdsafsda','',2,'pending','2026-07-16 18:56:00','2026-07-03 15:56:25'),(2,'fsdasfd','',2,'pending','2026-07-22 19:02:00','2026-07-03 16:02:46'),(3,'kiâ”¼ÅŸisel','',4,'pending','2026-07-15 19:18:00','2026-07-03 16:18:49'),(4,'kiâ”¼ÅŸisel','',4,'pending','2026-07-15 19:19:00','2026-07-03 16:19:10'),(5,'takâ”€â–’m','',4,'pending','2026-07-15 19:19:00','2026-07-03 16:19:37');
/*!40000 ALTER TABLE `app_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_trophies`
--

DROP TABLE IF EXISTS `app_trophies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_trophies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `award_type` varchar(255) DEFAULT NULL,
  `award_date` date DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_trophies`
--

LOCK TABLES `app_trophies` WRITE;
/*!40000 ALTER TABLE `app_trophies` DISABLE KEYS */;
INSERT INTO `app_trophies` VALUES (1,27,6,'sasvfdsaadsf','sadf fasdasfasfd','organization','2026-07-01','trophy_6a462984762b87.64757515.png','2026-07-01 17:49:12');
/*!40000 ALTER TABLE `app_trophies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_wiki_articles`
--

DROP TABLE IF EXISTS `app_wiki_articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_wiki_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `views` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_wiki_articles`
--

LOCK TABLES `app_wiki_articles` WRITE;
/*!40000 ALTER TABLE `app_wiki_articles` DISABLE KEYS */;
INSERT INTO `app_wiki_articles` VALUES (1,28,6,2,'safasdf safasd fasfsfvsdf','as dfasfdas asd f sadfsad fsdfa afdsasahsshds hdg hashaha f gjj d hfjdf jh sdsdfg agfsasg fag sf fasdfa sdsadfadsfadsfadsfFDSFASFDASF','fsad','2026-07-01 17:47:53',8),(2,28,6,2,'dsaASD','AdaSDDASAd','DSA','2026-07-03 00:44:55',15);
/*!40000 ALTER TABLE `app_wiki_articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `apps`
--

DROP TABLE IF EXISTS `apps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `module_type` varchar(50) NOT NULL,
  `department_id` int(11) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `director_only` tinyint(1) DEFAULT 0,
  `sidebar_category_key` varchar(64) DEFAULT NULL,
  `sidebar_category_label` varchar(120) DEFAULT NULL,
  `sidebar_order` int(11) DEFAULT 100,
  `is_primary` tinyint(1) DEFAULT 0,
  `config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`config`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_global_module` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_app_dept_slug` (`department_id`,`slug`),
  CONSTRAINT `apps_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `apps`
--

LOCK TABLES `apps` WRITE;
/*!40000 ALTER TABLE `apps` DISABLE KEYS */;
INSERT INTO `apps` VALUES (1,'Etkinlik','event','Etkinlik modâ”œâ•lâ”œâ•','event',6,'flag',0,1,0,'events','Etkinlik & Takvim',100,0,'{}','2026-07-01 11:39:19',0),(2,'Bootcamp Eâ”€ÅŸitimleri','bootcamp','Bootcamp Eâ”€ÅŸitimleri modâ”œâ•lâ”œâ•','bootcamp',6,'book-open',0,1,0,'events','Etkinlik & Takvim',100,0,'{}','2026-07-01 11:39:19',0),(6,'Form','form','Form modâ”œâ•lâ”œâ•','form',6,'file-text',0,1,0,'main','Ana Uygulamalar',100,1,'{}','2026-07-01 13:47:33',0),(7,'Dosya Yâ”œÃ‚neticisi','drive','Dosya Yâ”œÃ‚neticisi modâ”œâ•lâ”œâ•','drive',6,'folder',0,1,0,'main','Ana Uygulamalar',100,1,'{}','2026-07-01 13:47:33',0),(8,'Takvim','calendar','Takvim modâ”œâ•lâ”œâ•','calendar',6,'calendar',0,1,0,'main','Ana Uygulamalar',100,1,'{}','2026-07-01 13:47:33',0),(9,'Duyuru','announcement','Duyuru modâ”œâ•lâ”œâ•','announcement',6,'volume-2',0,1,0,'communication','â”€â–‘letiâ”¼ÅŸim & Baâ”¼ÅŸvurular',100,0,'{}','2026-07-01 13:47:33',0),(10,'Rapor','report','Rapor modâ”œâ•lâ”œâ•','report',6,'bar-chart',0,1,0,'finance','Finans & Raporlama',100,0,'{}','2026-07-01 13:47:33',0),(11,'Kurumsal Rehber','directory','Kurumsal Rehber modâ”œâ•lâ”œâ•','directory',6,'book',0,1,0,'people','â”œÂ£yeler & Aâ”€ÅŸ',100,0,'{}','2026-07-01 13:47:33',0),(12,'â”œÂ£ye Listesi','members','â”œÂ£ye Listesi modâ”œâ•lâ”œâ•','members',6,'users',0,1,0,'people','â”œÂ£yeler & Aâ”€ÅŸ',100,0,'{}','2026-07-01 13:47:33',0),(13,'Finans Paneli','finance','Finans Paneli modâ”œâ•lâ”œâ•','finance',6,'credit-card',0,1,0,'finance','Finans & Raporlama',100,0,'{}','2026-07-01 13:47:33',0),(14,'Aidat Takibi','dues','Aidat Takibi modâ”œâ•lâ”œâ•','dues',6,'check-circle',0,1,0,'finance','Finans & Raporlama',100,0,'{}','2026-07-01 13:47:33',0),(15,'Sponsorlar','sponsor','Sponsorlar modâ”œâ•lâ”œâ•','sponsor',6,'gift',0,1,0,'communication','â”€â–‘letiâ”¼ÅŸim & Baâ”¼ÅŸvurular',100,0,'{}','2026-07-01 13:47:33',0),(16,'Anlaâ”¼ÅŸmalâ”€â–’ Mekanlar','partner','Anlaâ”¼ÅŸmalâ”€â–’ Mekanlar modâ”œâ•lâ”œâ•','partner',6,'map-pin',0,1,0,'communication','â”€â–‘letiâ”¼ÅŸim & Baâ”¼ÅŸvurular',100,0,'{}','2026-07-01 13:47:33',0),(17,'Sertifika','certificate','Sertifika modâ”œâ•lâ”œâ•','certificate',6,'award',0,1,0,'knowledge','Bilgi & Dosyalar',100,0,'{}','2026-07-01 13:47:33',0),(18,'Envanter','inventory','Envanter modâ”œâ•lâ”œâ•','inventory',6,'archive',0,1,0,'operations','Operasyon & Araâ”œÄŸlar',100,0,'{}','2026-07-01 13:47:33',0),(19,'Hâ”€â–’zlâ”€â–’ Baâ”€ÅŸlantâ”€â–’lar','links','Hâ”€â–’zlâ”€â–’ Baâ”€ÅŸlantâ”€â–’lar modâ”œâ•lâ”œâ•','links',6,'link',0,1,0,'knowledge','Bilgi & Dosyalar',100,0,'{}','2026-07-01 13:47:33',0),(20,'Karekod','qr','Karekod modâ”œâ•lâ”œâ•','qr',6,'qr',0,1,0,'operations','Operasyon & Araâ”œÄŸlar',100,0,'{\"qrs\":[{\"id\":\"qr_6a454d830bcaf\",\"title\":\"adf fasdafs dfsad f adsfads\",\"value\":\"viperadev.com\",\"colorDark\":\"#ff0000\",\"colorLight\":\"#000000\",\"logoUrl\":\"http:\\/\\/localhost\\/innomis.tr\\/backend\\/public\\/uploads\\/qr_logos\\/qr_logo_6a454d830ba31.jpeg\",\"dotsType\":\"rounded\",\"cornersType\":\"extra-rounded\",\"created_at\":\"2026-07-01 19:25:23\"}]}','2026-07-01 13:47:33',0),(21,'Link Kâ”€â–’saltâ”€â–’câ”€â–’','shortener','Link Kâ”€â–’saltâ”€â–’câ”€â–’ modâ”œâ•lâ”œâ•','shortener',6,'scissors',0,1,0,'operations','Operasyon & Araâ”œÄŸlar',100,0,'{\"links\":[{\"code\":\"deneme\",\"url\":\"https:\\/\\/pin.it\\/3E0mB0Yng\",\"title\":\"\",\"clicks\":1,\"created_at\":\"2026-07-03 02:32:58\"},{\"code\":\"vipera\",\"url\":\"https:\\/\\/viperadev.com\",\"title\":\"vipera sitesi\",\"clicks\":0,\"created_at\":\"2026-07-01 19:26:47\"}]}','2026-07-01 13:47:33',0),(22,'Agile','agile','Agile modâ”œâ•lâ”œâ•','agile',6,'layout',0,1,0,'project','Proje Yâ”œÃ‚netimi',100,0,'{}','2026-07-01 13:47:33',0),(23,'â”¼Åifre Yâ”œÃ‚neticisi','passwords','â”¼Åifre Yâ”œÃ‚neticisi modâ”œâ•lâ”œâ•','passwords',6,'lock',0,1,0,'operations','Operasyon & Araâ”œÄŸlar',100,0,'{}','2026-07-01 13:47:33',0),(24,'Idea Hub','ideahub','Idea Hub modâ”œâ•lâ”œâ•','ideahub',6,'lightbulb',0,1,0,'project','Proje Yâ”œÃ‚netimi',100,0,'{}','2026-07-01 13:47:33',0),(25,'Tâ”œâ•zâ”œâ•k','bylaws','Tâ”œâ•zâ”œâ•k modâ”œâ•lâ”œâ•','bylaws',6,'feather',0,1,0,'governance','Yâ”œÃ‚netim & Denetim',100,0,'{}','2026-07-01 13:47:33',0),(26,'Denetim','audit','Denetim modâ”œâ•lâ”œâ•','audit',6,'shield',0,1,0,'governance','Yâ”œÃ‚netim & Denetim',100,0,'{}','2026-07-01 13:47:33',0),(27,'â”œÃ»dâ”œâ•ller','trophy','â”œÃ»dâ”œâ•ller modâ”œâ•lâ”œâ•','trophy',6,'target',0,1,0,'events','Etkinlik & Takvim',100,0,'{}','2026-07-01 13:47:33',0),(28,'Wiki','wiki','Wiki modâ”œâ•lâ”œâ•','wiki',6,'layers',0,1,0,'knowledge','Bilgi & Dosyalar',100,0,'{}','2026-07-01 13:47:33',0),(29,'Toplantâ”€â–’','meeting','Toplantâ”€â–’ modâ”œâ•lâ”œâ•','meeting',6,'video',0,1,0,'events','Etkinlik & Takvim',100,0,'{}','2026-07-01 13:47:33',0),(30,'Bâ”œâ•tâ”œÄŸe','budget','Bâ”œâ•tâ”œÄŸe modâ”œâ•lâ”œâ•','budget',6,'pie-chart',0,1,0,'finance','Finans & Raporlama',100,0,'{}','2026-07-01 13:47:33',0),(31,'Mentor','mentorship','Mentor modâ”œâ•lâ”œâ•','mentorship',6,'navigation',0,1,0,'people','â”œÂ£yeler & Aâ”€ÅŸ',100,0,'{}','2026-07-01 13:47:33',0),(32,'â”€â–‘â”œÄŸerik','social','â”€â–‘â”œÄŸerik modâ”œâ•lâ”œâ•','social',6,'share-2',0,1,0,'communication','â”€â–‘letiâ”¼ÅŸim & Baâ”¼ÅŸvurular',100,0,'{}','2026-07-01 13:47:33',0),(33,'Mezunlar','alumni','Mezunlar modâ”œâ•lâ”œâ•','alumni',6,'compass',0,1,0,'people','â”œÂ£yeler & Aâ”€ÅŸ',100,0,'{}','2026-07-01 13:47:33',0),(35,'Tâ”œâ•zâ”œâ•k','bylaws','Tâ”œâ•zâ”œâ•k modâ”œâ•lâ”œâ•','bylaws',1,'feather',0,0,0,'governance','Yâ”œÃ‚netim & Denetim',100,0,'{}','2026-07-01 17:54:20',0),(36,'Duyuru','announcement','Duyuru modâ”œâ•lâ”œâ•','announcement',1,'volume-2',0,0,0,'communication','â”€â–‘letiâ”¼ÅŸim & Baâ”¼ÅŸvurular',100,0,'{}','2026-07-01 18:02:28',0),(37,'Denetim','audit','Denetim modâ”œâ•lâ”œâ•','audit',1,'shield',0,0,0,'governance','Yâ”œÃ‚netim & Denetim',100,0,'{}','2026-07-01 18:02:28',0),(38,'Toplantâ”€â–’','meeting','Toplantâ”€â–’ modâ”œâ•lâ”œâ•','meeting',1,'video',0,0,0,'events','Etkinlik & Takvim',100,0,'{}','2026-07-01 18:02:28',0),(39,'â”œÂ£ye Listesi','members','â”œÂ£ye Listesi modâ”œâ•lâ”œâ•','members',3,'users',0,1,0,NULL,NULL,100,0,'{}','2026-07-01 18:48:36',0),(40,'Gelen Kutusu','inbox','Gelen Kutusu modâ”œâ•lâ”œâ•','inbox',6,'inbox',0,1,0,'communication','â”€â–‘letiâ”¼ÅŸim & Baâ”¼ÅŸvurular',100,0,'{}','2026-07-01 20:16:34',0),(41,'Baâ”¼ÅŸvurular','applications','Baâ”¼ÅŸvurular modâ”œâ•lâ”œâ•','applications',6,'file-text',0,1,0,'communication','â”€â–‘letiâ”¼ÅŸim & Baâ”¼ÅŸvurular',100,0,'{}','2026-07-01 21:01:07',0),(42,'Medya Yâ”œÃ‚neticisi','media','Medya Yâ”œÃ‚neticisi modâ”œâ•lâ”œâ•','media',6,'image',0,1,0,'knowledge','Bilgi & Dosyalar',100,0,'{}','2026-07-01 21:33:56',0),(43,'TEST','test-1782945173','test','form',6,NULL,0,1,0,NULL,NULL,100,0,'{\"fields\":[{\"name\":\"field_1782945254950\",\"required\":true,\"label\":\"Ad Soyad\",\"type\":\"text\"},{\"name\":\"field_1782945256000\",\"required\":true,\"label\":\"E-posta Adresi\",\"type\":\"email\"},{\"name\":\"field_1782945256944\",\"required\":true,\"label\":\"Sosyal Medya Hesabâ”€â–’nâ”€â–’z\",\"type\":\"social\",\"platform\":\"instagram\"}],\"settings\":{\"is_public\":true,\"category\":\"Genel\",\"success_message\":\"Form baâ”¼ÅŸarâ”€â–’yla gâ”œÃ‚nderildi.\"}}','2026-07-01 22:32:53',0),(44,'deneme','deneme-1782945420','','form',6,NULL,0,1,0,NULL,NULL,100,0,'{\"settings\":{\"is_public\":true}}','2026-07-01 22:37:00',0),(45,'deneme','deneme','','form',6,NULL,0,1,0,NULL,NULL,100,0,'{\"settings\":{\"is_public\":true}}','2026-07-01 22:43:35',0),(46,'deneme','deneme-1','','form',6,NULL,0,1,0,NULL,NULL,100,0,'{\"fields\":[{\"name\":\"field_1782946742149\",\"required\":false,\"label\":\"Eâ”€ÅŸitim Bilgileri\",\"type\":\"education\"},{\"name\":\"field_1782946836898\",\"required\":false,\"label\":\"Telefon Numarasâ”€â–’\",\"type\":\"phone\"},{\"name\":\"field_1782946837420\",\"required\":false,\"label\":\"E-posta Adresi\",\"type\":\"email\"},{\"name\":\"field_1782946839183\",\"required\":false,\"label\":\"Sosyal Medya Hesabâ”€â–’nâ”€â–’z\",\"type\":\"social\",\"platform\":\"instagram\"},{\"name\":\"field_1782946840780\",\"required\":false,\"label\":\"â”œÃ»â”€ÅŸrenci Numarasâ”€â–’\",\"type\":\"number\"},{\"name\":\"field_1782949340395\",\"label\":\"Yeni Soru\",\"type\":\"note\",\"required\":false,\"options\":[\"Seâ”œÄŸenek 1\"]}],\"settings\":{\"is_public\":true,\"category\":\"\",\"form_description\":\"\",\"success_message\":\"\",\"theme_color\":\"#0004ff\",\"header_image\":\"\"}}','2026-07-01 22:43:41',0),(47,'Gâ”œÃ‚rev Yâ”œÃ‚netimi','tasks',NULL,'task_manager',1,'check-square',0,1,0,NULL,NULL,100,0,NULL,'2026-07-03 14:52:08',1),(48,'Gâ”œÃ‚rev Yâ”œÃ‚neticisi','task_manager','Gâ”œÃ‚rev Yâ”œÃ‚neticisi modâ”œâ•lâ”œâ•','task_manager',6,'check-square',-1111111,1,0,'main','Ana Uygulamalar',100,1,'{}','2026-07-03 14:59:44',0),(49,'Gâ”œÃ‚rev Yâ”œÃ‚neticisi','task_manager','Gâ”œÃ‚rev Yâ”œÃ‚neticisi modâ”œâ•lâ”œâ•','task_manager',1,'check-square',0,1,0,'main','Ana Uygulamalar',100,1,'{}','2026-07-03 14:59:56',0);
/*!40000 ALTER TABLE `apps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendar_events`
--

DROP TABLE IF EXISTS `calendar_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendar_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `event_time` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  CONSTRAINT `calendar_events_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `apps` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendar_events`
--

LOCK TABLES `calendar_events` WRITE;
/*!40000 ALTER TABLE `calendar_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendar_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `color` varchar(7) DEFAULT '#00BCD4',
  `is_active` tinyint(1) DEFAULT 1,
  `is_management` tinyint(1) DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Yâ”œÃ‚netim','yonetim','Baâ”¼ÅŸkan ve Yardâ”€â–’mcâ”€â–’larâ”€â–’nâ”€â–’n yâ”œÃ‚netim paneli. Tâ”œâ•m departmanlarâ”€â–’ izleyebilir.','crown','#F59E0B',1,1,1,'2026-07-01 11:06:38'),(2,'Mezunlar','mezunlar','Eski â”œâ•yelerin iletiâ”¼ÅŸimde kaldâ”€â–’â”€ÅŸâ”€â–’ mezun paneli.','graduation-cap','#8B5CF6',1,0,1,'2026-07-01 11:06:38'),(3,'Sosyal Medya','sosyal-medya','Sosyal medya planlama ve iâ”œÄŸerik yâ”œÃ‚netimi.','share-2','#EC4899',1,0,1,'2026-07-01 11:06:38'),(4,'â”€â–‘nsan Kaynaklarâ”€â–’','insan-kaynaklari','â”œÂ£ye alâ”€â–’mâ”€â–’ ve insan kaynaklarâ”€â–’ takibi.','users','#10B981',1,0,1,'2026-07-01 11:06:38'),(5,'Kurumsal â”€â–‘liâ”¼ÅŸkiler','kurumsal-iliskiler','Kurum dâ”€â–’â”¼ÅŸâ”€â–’ iliâ”¼ÅŸkiler ve sponsorluk sâ”œâ•reâ”œÄŸleri.','briefcase','#3B82F6',1,0,1,'2026-07-01 11:06:38'),(6,'Ar-Ge & Teknoloji','arge-teknoloji','Teknik projelerin ve AR-GE sâ”œâ•reâ”œÄŸlerinin yâ”œÃ‚netimi.','cpu','#00BCD4',1,0,1,'2026-07-01 11:06:38'),(7,'Organizasyon','organizasyon','Etkinlik ve toplantâ”€â–’ planlama.','calendar','#F97316',1,0,1,'2026-07-01 11:06:38'),(8,'Temsilci','temsilci','Kampâ”œâ•s Temsilcileri','map-pin','#ef4444',1,0,1,'2026-07-01 12:43:02');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drive_items`
--

DROP TABLE IF EXISTS `drive_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drive_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `type` enum('folder','file') NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  KEY `department_id` (`department_id`),
  KEY `created_by` (`created_by`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `drive_items_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `apps` (`id`) ON DELETE CASCADE,
  CONSTRAINT `drive_items_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `drive_items_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `drive_items_ibfk_4` FOREIGN KEY (`parent_id`) REFERENCES `drive_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drive_items`
--

LOCK TABLES `drive_items` WRITE;
/*!40000 ALTER TABLE `drive_items` DISABLE KEYS */;
INSERT INTO `drive_items` VALUES (1,7,6,2,13,'file','WhatsApp Image 2026-05-15 at 10.29.30 (1).jpeg','uploads/drive/6a454b319a6f2_WhatsAppImage2026-05-15at10.29.301.jpeg',1514411,'image/jpeg','2026-07-01 17:15:29','2026-07-01 17:15:44'),(2,7,6,2,13,'file','WhatsApp Image 2026-05-15 at 10.29.31 (1).jpeg','uploads/drive/6a454b319d1b3_WhatsAppImage2026-05-15at10.29.311.jpeg',644250,'image/jpeg','2026-07-01 17:15:29','2026-07-01 17:15:44'),(3,7,6,2,13,'file','WhatsApp Image 2026-05-15 at 10.29.30 (2).jpeg','uploads/drive/6a454b31a078c_WhatsAppImage2026-05-15at10.29.302.jpeg',1458557,'image/jpeg','2026-07-01 17:15:29','2026-07-01 17:15:44'),(4,7,6,2,13,'file','WhatsApp Image 2026-05-15 at 10.29.30 (3).jpeg','uploads/drive/6a454b31a4109_WhatsAppImage2026-05-15at10.29.303.jpeg',1454245,'image/jpeg','2026-07-01 17:15:29','2026-07-01 17:15:44'),(5,7,6,2,13,'file','WhatsApp Image 2026-05-15 at 10.29.30.jpeg','uploads/drive/6a454b31a7abc_WhatsAppImage2026-05-15at10.29.30.jpeg',1581429,'image/jpeg','2026-07-01 17:15:29','2026-07-01 17:15:44'),(6,7,6,2,13,'file','WhatsApp Image 2026-05-15 at 10.29.31.jpeg','uploads/drive/6a454b31ab490_WhatsAppImage2026-05-15at10.29.31.jpeg',1453811,'image/jpeg','2026-07-01 17:15:29','2026-07-01 17:15:44'),(7,7,6,2,13,'file','WhatsApp Image 2026-05-15 at 10.29.32 (1).jpeg','uploads/drive/6a454b31adeb2_WhatsAppImage2026-05-15at10.29.321.jpeg',643687,'image/jpeg','2026-07-01 17:15:29','2026-07-01 17:15:44'),(8,7,6,2,NULL,'file','WhatsApp Image 2026-05-15 at 10.29.32.jpeg','uploads/drive/6a454b31b0496_WhatsAppImage2026-05-15at10.29.32.jpeg',660028,'image/jpeg','2026-07-01 17:15:29','2026-07-01 17:15:29'),(9,7,6,2,13,'file','WhatsApp Image 2026-05-15 at 10.29.32 (2).jpeg','uploads/drive/6a454b31b2b7b_WhatsAppImage2026-05-15at10.29.322.jpeg',695892,'image/jpeg','2026-07-01 17:15:29','2026-07-01 17:15:44'),(10,7,6,2,NULL,'file','WhatsApp Image 2026-05-15 at 10.29.33 (1).jpeg','uploads/drive/6a454b31b52b1_WhatsAppImage2026-05-15at10.29.331.jpeg',1021243,'image/jpeg','2026-07-01 17:15:29','2026-07-01 17:15:29'),(11,7,6,2,NULL,'file','WhatsApp Image 2026-05-15 at 10.29.33.jpeg','uploads/drive/6a454b31b7a2e_WhatsAppImage2026-05-15at10.29.33.jpeg',1033244,'image/jpeg','2026-07-01 17:15:29','2026-07-01 17:15:29'),(12,7,6,2,NULL,'file','WhatsApp Image 2026-05-15 at 10.29.34.jpeg','uploads/drive/6a454b31ba256_WhatsAppImage2026-05-15at10.29.34.jpeg',1020691,'image/jpeg','2026-07-01 17:15:29','2026-07-01 17:15:29'),(13,7,6,2,NULL,'folder','fdsfgfg',NULL,NULL,NULL,'2026-07-01 17:15:41','2026-07-01 17:15:41');
/*!40000 ALTER TABLE `drive_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dues_payments`
--

DROP TABLE IF EXISTS `dues_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dues_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount_paid` decimal(10,2) DEFAULT 0.00,
  `payment_date` date DEFAULT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `recorded_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dues_payments`
--

LOCK TABLES `dues_payments` WRITE;
/*!40000 ALTER TABLE `dues_payments` DISABLE KEYS */;
INSERT INTO `dues_payments` VALUES (1,1,2,0.00,NULL,NULL,'unpaid','2026-07-01 17:08:46',2),(2,2,3,0.00,NULL,NULL,'unpaid','2026-07-02 16:20:56',NULL),(3,2,2,0.00,NULL,NULL,'unpaid','2026-07-02 16:20:56',NULL),(4,3,3,0.00,NULL,NULL,'unpaid','2026-07-02 18:57:24',NULL),(5,3,2,0.00,NULL,NULL,'unpaid','2026-07-02 18:57:24',NULL);
/*!40000 ALTER TABLE `dues_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dues_periods`
--

DROP TABLE IF EXISTS `dues_periods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dues_periods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) DEFAULT 'TRY',
  `due_date` date DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dues_periods`
--

LOCK TABLES `dues_periods` WRITE;
/*!40000 ALTER TABLE `dues_periods` DISABLE KEYS */;
INSERT INTO `dues_periods` VALUES (1,14,6,'fdsafasf',50.00,'TRY',NULL,'2','2026-07-01 17:08:46'),(2,14,6,'gfddsfg',234.00,'TRY',NULL,'2','2026-07-02 16:20:56'),(3,14,6,'yâ”€â–’ut',897.00,'TRY',NULL,'2','2026-07-02 18:57:24');
/*!40000 ALTER TABLE `dues_periods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `finance_transactions`
--

DROP TABLE IF EXISTS `finance_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `finance_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL COMMENT 'income or expense',
  `category` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) DEFAULT 'TRY',
  `description` text DEFAULT NULL,
  `proxy_user_id` int(11) DEFAULT NULL,
  `proxy_status` varchar(50) DEFAULT 'delivered',
  `receipt_path` varchar(255) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `related_due_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `finance_transactions`
--

LOCK TABLES `finance_transactions` WRITE;
/*!40000 ALTER TABLE `finance_transactions` DISABLE KEYS */;
INSERT INTO `finance_transactions` VALUES (1,13,6,'income','Baâ”€ÅŸâ”€â–’â”¼ÅŸ',53245.00,'TRY','',NULL,'delivered',NULL,'2026-07-01','2','2026-07-01 17:07:08',NULL),(2,13,6,'expense','Yazâ”€â–’lâ”€â–’m/Sunucu',43242.00,'TRY','grrfsadgsdgsdfg',NULL,'delivered',NULL,'2026-07-01','2','2026-07-01 17:08:08',NULL),(4,13,6,'income','Baâ”€ÅŸâ”€â–’â”¼ÅŸ',654456.00,'TRY','',2,'delivered',NULL,'2026-07-01','2','2026-07-01 17:10:51',NULL),(5,13,6,'expense','Yazâ”€â–’lâ”€â–’m/Sunucu',543534.00,'TRY','',2,'delivered',NULL,'2026-07-01','2','2026-07-01 17:10:58',NULL);
/*!40000 ALTER TABLE `finance_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_entries`
--

DROP TABLE IF EXISTS `form_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `form_entries_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `apps` (`id`) ON DELETE CASCADE,
  CONSTRAINT `form_entries_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_entries`
--

LOCK TABLES `form_entries` WRITE;
/*!40000 ALTER TABLE `form_entries` DISABLE KEYS */;
INSERT INTO `form_entries` VALUES (1,6,6,2,'{\"name\": \"Yavuz Selim Yiâ”€ÅŸit\", \"feedback\": \"Sistem harika.\"}','2026-07-01 13:48:59'),(2,46,6,NULL,'{\"field_1782946742149\":{\"universite\":\"\",\"fakulte\":\"\",\"bolum\":\"\",\"sinif\":\"\"},\"field_1782946836898\":\"\",\"field_1782946837420\":\"\",\"field_1782946839183\":\"\",\"field_1782946840780\":\"\",\"_meta\":{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\",\"submitted_at\":\"2026-07-02 01:37:38\"}}','2026-07-01 23:37:38'),(3,46,6,NULL,'{\"field_1782946742149\":{\"universite\":\"\",\"fakulte\":\"\",\"bolum\":\"\",\"sinif\":\"\"},\"field_1782946836898\":\"\",\"field_1782946837420\":\"\",\"field_1782946839183\":\"\",\"field_1782946840780\":\"\",\"_meta\":{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\",\"submitted_at\":\"2026-07-02 01:37:41\"}}','2026-07-01 23:37:41'),(4,46,6,NULL,'{\"field_1782946742149\":{\"universite\":\"\",\"fakulte\":\"\",\"bolum\":\"\",\"sinif\":\"\"},\"field_1782946836898\":\"\",\"field_1782946837420\":\"\",\"field_1782946839183\":\"\",\"field_1782946840780\":\"\",\"_meta\":{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\",\"submitted_at\":\"2026-07-02 01:40:50\"}}','2026-07-01 23:40:50');
/*!40000 ALTER TABLE `form_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `passwords`
--

DROP TABLE IF EXISTS `passwords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `passwords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(11) DEFAULT NULL,
  `app_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `encrypted_password` varchar(255) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `passwords`
--

LOCK TABLES `passwords` WRITE;
/*!40000 ALTER TABLE `passwords` DISABLE KEYS */;
INSERT INTO `passwords` VALUES (1,6,23,'sfgf sdgsdf','https://fdsgsdgsdg.fgd','sadfasdfvavdsfvfsd@dsf.hdfg','MzdWTXpLWlFwRDE4Q0djUWVvYXpnZz09OjpxHyEooDijzYi962Ay8tjX','','2026-07-01 17:51:17');
/*!40000 ALTER TABLE `passwords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_events`
--

DROP TABLE IF EXISTS `site_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` datetime DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `tag_color` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'upcoming',
  `image_url` varchar(255) DEFAULT NULL,
  `target_date` datetime DEFAULT NULL,
  `registration_link` varchar(255) DEFAULT NULL,
  `speakers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`speakers`)),
  `agenda` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`agenda`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_events`
--

LOCK TABLES `site_events` WRITE;
/*!40000 ALTER TABLE `site_events` DISABLE KEYS */;
INSERT INTO `site_events` VALUES (1,'Yapay Zeka ve Gelecek','Yapay zeka ekosistemi.','2026-07-03 16:48:59','Online','Seminer',NULL,'upcoming',NULL,NULL,NULL,NULL,NULL,'2026-07-01 13:48:59');
/*!40000 ALTER TABLE `site_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_gallery`
--

DROP TABLE IF EXISTS `site_gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_url` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT 'Genel',
  `title` varchar(255) DEFAULT NULL,
  `span_class` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_gallery`
--

LOCK TABLES `site_gallery` WRITE;
/*!40000 ALTER TABLE `site_gallery` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_history`
--

DROP TABLE IF EXISTS `site_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(20) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT 'achievement',
  `link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_history`
--

LOCK TABLES `site_history` WRITE;
/*!40000 ALTER TABLE `site_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_projects`
--

DROP TABLE IF EXISTS `site_projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `tech_stack_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tech_stack_json`)),
  `github_link` varchar(255) DEFAULT NULL,
  `demo_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_projects`
--

LOCK TABLES `site_projects` WRITE;
/*!40000 ALTER TABLE `site_projects` DISABLE KEYS */;
INSERT INTO `site_projects` VALUES (1,'InnoMIS Connect','Topluluk yâ”œÃ‚netim sistemi.',NULL,'completed',NULL,NULL,NULL,'2026-07-01 13:48:59');
/*!40000 ALTER TABLE `site_projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_settings` (
  `key_name` varchar(100) NOT NULL,
  `json_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`json_value`)),
  PRIMARY KEY (`key_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES ('brand','{\"navbarLogo\":\"http:\\/\\/localhost\\/innomis.tr\\/backend\\/public\\/uploads\\/site-settings\\/navbarLogo_6a48039d8dea71.71102255_Yatay-Beyaz.png\",\"footerLogo\":\"http:\\/\\/localhost\\/innomis.tr\\/backend\\/public\\/uploads\\/site-settings\\/footerLogo_6a48039d8e2484.87561008_Yatay-Beyaz.png\",\"popupLogo\":\"http:\\/\\/localhost\\/innomis.tr\\/backend\\/public\\/uploads\\/site-settings\\/popupLogo_6a48039d8eac62.96343222_Ana-Logo-Arma.png\",\"heroMascot\":\"http:\\/\\/localhost\\/innomis.tr\\/backend\\/public\\/uploads\\/site-settings\\/heroMascot_6a48039d8ef785.02739476_Adsztasarm.png\",\"faqMascot\":\"http:\\/\\/localhost\\/innomis.tr\\/backend\\/public\\/uploads\\/site-settings\\/faqMascot_6a48039d8f3b72.61666053_Adsztasarm.png\",\"brandName\":\"InnoMIS Community\",\"footerDescription\":\"Teknoloji, inovasyon ve kariyer odaklÄ± Ã¶ÄŸrenci topluluÄŸu. Ã–ÄŸrenen, Ã¼reten ve paylaÅŸan Ã¶ÄŸrencileri aynÄ± Ã§atÄ± altÄ±nda buluÅŸturur.\"}'),('contact','{\"email\":\"info.innomis@gmail.com\",\"phone\":\"0555 555 55 55\",\"address\":\"Dere AÅŸÄ±klar, KÃ¶yceÄŸiz YerleÅŸkesi KÃ¶yceÄŸiz, DemeÃ§ Sk. No:42\\/3, 42005 Meram\\/Konya\",\"addressDetail\":\"Necmettin Erbakan Ãœniversitesi \\/ UygulamalÄ± Bilimler FakÃ¼ltesi\",\"mapEmbedUrl\":\"https:\\/\\/www.google.com\\/maps\\/embed?pb=!1m18!1m12!1m3!1d3151.727670977827!2d32.42436737521876!3d37.81989437197368!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14d08db8c0b2f56b%3A0x6b801a2f6fb39e80!2sNecmettin%20Erbakan%20%C3%9Cniversitesi%20Uygulamal%C4%B1%20Bilimler%20Fak%C3%BCltesi!5e0!3m2!1str!2str!4v1700000000000!5m2!1str!2str\",\"socials\":{\"instagram\":\"https:\\/\\/www.instagram.com\\/neuinnomis\\/\",\"x\":\"https:\\/\\/x.com\\/neuinnomis\",\"youtube\":\"https:\\/\\/www.youtube.com\\/@neuinnomis\",\"tiktok\":\"https:\\/\\/www.tiktok.com\\/@neuinnomis\",\"github\":\"https:\\/\\/github.com\\/neuinnomis\",\"linkedin\":\"https:\\/\\/www.linkedin.com\\/company\\/innomis-community\"}}'),('database_backup','{\"enabled\":0,\"token\":\"\",\"limit\":7}'),('faqs','[{\"q\":\"InnoMIS Community\\'ye kimler katÄ±labilir?\",\"a\":\"Teknoloji, giriÅŸimcilik, kariyer ve topluluk Ã§alÄ±ÅŸmalarÄ±na ilgi duyan Ã¼niversite Ã¶ÄŸrencileri katÄ±labilir.\"},{\"q\":\"Etkinliklere katÄ±lÄ±m Ã¼cretli mi?\",\"a\":\"EtkinliÄŸe gÃ¶re deÄŸiÅŸebilir. Duyurularda katÄ±lÄ±m koÅŸullarÄ± aÃ§Ä±kÃ§a belirtilir.\"},{\"q\":\"Ekiplerde gÃ¶rev almak mÃ¼mkÃ¼n mÃ¼?\",\"a\":\"Evet. Sosyal medya, etkinlik, Ar-Ge, sponsorluk, tasarÄ±m, yazÄ±lÄ±m ve operasyon ekiplerinde gÃ¶rev alÄ±nabilir.\"},{\"q\":\"Sponsor veya partner olarak nasÄ±l iletiÅŸime geÃ§ilir?\",\"a\":\"Ä°letiÅŸim formu, e-posta veya sosyal medya hesaplarÄ± Ã¼zerinden toplulukla iletiÅŸime geÃ§ilebilir.\"},{\"q\":\"Etkinlik duyurularÄ±nÄ± nereden takip edebilirim?\",\"a\":\"Instagram, LinkedIn ve web sitesi Ã¼zerinden gÃ¼ncel duyurular takip edilebilir.\"}]'),('form_routing','{\"all\":[6]}'),('hero','{\"titleLine1\":\"BiliÅŸimde YeniliÄŸe\",\"titleLine2\":\"Ä°novasyonda Zirveye!\",\"description\":\"InnoMIS Community; teknoloji, yapay zeka, giriÅŸimcilik ve kariyer odaklÄ± etkinliklerle Ã¶ÄŸrencileri Ã¼retmeye, Ã¶ÄŸrenmeye ve baÄŸlantÄ± kurmaya davet eden yenilikÃ§i bir Ã¶ÄŸrenci topluluÄŸudur.\",\"primaryCta\":{\"label\":\"TopluluÄŸa KatÄ±l\",\"href\":\"\\/kayit\"},\"secondaryCta\":{\"label\":\"Etkinlikleri KeÅŸfet\",\"href\":\"\\/events\"}}'),('popupConfig','{\"enabled\":false,\"storageKey\":\"innomis_announcement_seen_v2\",\"autoCloseSec\":5,\"badge\":\"dsgfsdgsdgfsdgf\",\"title\":\"fdgdfsgs\",\"description\":\"\",\"cta\":{\"label\":\"\",\"href\":\"\"},\"secondaryCta\":{\"label\":\"\",\"href\":\"\"}}'),('sponsors','[1,2]'),('stats','[{\"label\":\"Aktif Ãœye\",\"value\":\"500+\",\"desc\":\"Topluluk ekosisteminde yer alan Ã¶ÄŸrenci aÄŸÄ±.\"},{\"label\":\"Etkinlik\",\"value\":\"25+\",\"desc\":\"Workshop, panel, zirve ve buluÅŸmalar.\"},{\"label\":\"Ä°ÅŸ BirliÄŸi\",\"value\":\"15+\",\"desc\":\"Marka, topluluk ve sektÃ¶r paydaÅŸlarÄ±.\"}]');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_team_members`
--

DROP TABLE IF EXISTS `site_team_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_team_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `type` enum('board','department','campus') DEFAULT 'department',
  `image_url` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `quote` text DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `join_year` int(11) DEFAULT NULL,
  `status` enum('active','alumni') DEFAULT 'active',
  `community_history_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`community_history_json`)),
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_team_members`
--

LOCK TABLES `site_team_members` WRITE;
/*!40000 ALTER TABLE `site_team_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_team_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_team_profiles`
--

DROP TABLE IF EXISTS `site_team_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_team_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `display_name` varchar(150) DEFAULT NULL,
  `display_role` varchar(150) DEFAULT NULL,
  `display_department` varchar(150) DEFAULT NULL,
  `display_type` enum('board','department','campus') DEFAULT 'department',
  `image_url` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `github` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `community_history_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`community_history_json`)),
  `is_visible` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_site_team_user_department` (`user_id`,`department_id`),
  KEY `idx_site_team_visible` (`is_visible`,`display_type`,`sort_order`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `site_team_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `site_team_profiles_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_team_profiles`
--

LOCK TABLES `site_team_profiles` WRITE;
/*!40000 ALTER TABLE `site_team_profiles` DISABLE KEYS */;
INSERT INTO `site_team_profiles` VALUES (1,2,6,NULL,'Direktâ”œÃ‚r','Ar-Ge & Teknoloji','department',NULL,'https://linkedin.com/in/yavuz-selim-yigit','https://github.com/yselimygt',NULL,'[{\"year\":\"2026\",\"role\":\"Ar-Ge Direktâ”œÃ‚râ”œâ•\"},{\"year\":\"2025\",\"role\":\"Sosyal Medya â”€â–‘â”œÄŸerik â”œÂ£reticisi\"}]',1,0,'2026-07-01 12:55:04','2026-07-03 20:11:03'),(7,4,1,NULL,'Baâ”¼ÅŸkan','Yâ”œÃ‚netim','board',NULL,NULL,NULL,NULL,'[{\"year\":\"2026\",\"role\":\"Baâ”¼ÅŸkan\"},{\"year\":\"2025 Q2\",\"role\":\"Baâ”¼ÅŸkan Yardâ”€â–’mcâ”€â–’sâ”€â–’\"},{\"year\":\"2025 Q1\",\"role\":\"Kurumsal â”€â–‘liâ”¼ÅŸkiler Direktâ”œÃ‚râ”œâ•\"}]',1,0,'2026-07-03 19:00:20','2026-07-03 20:07:24');
/*!40000 ALTER TABLE `site_team_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sponsors`
--

DROP TABLE IF EXISTS `sponsors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sponsors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `sponsorship_type` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  CONSTRAINT `sponsors_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `apps` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sponsors`
--

LOCK TABLES `sponsors` WRITE;
/*!40000 ALTER TABLE `sponsors` DISABLE KEYS */;
INSERT INTO `sponsors` VALUES (1,15,6,2,'ViperaDev','fdasf asdsad asdffads','fdsavfsvfvsfdvfds@fsdg.ghfj','4523523523','public/uploads/sponsors/1782927413_ViperaDev.png','Teknoloji Sponsoru','active','fasd dasf asfd asdf asdf asfd','2026-07-01 13:48:59','2026-07-01 17:36:53'),(2,15,6,2,'zvcxzxcvzxcv','zxvczxvzxcv','xzvzxvzxvxv@fdg.gdf','','public/uploads/sponsors/1782927949_NexPanel-Aydinlik.png','xzcvxzvczxv','active','','2026-07-01 17:45:49','2026-07-01 17:45:49');
/*!40000 ALTER TABLE `sponsors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_logs`
--

DROP TABLE IF EXISTS `system_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(100) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=490 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_logs`
--

LOCK TABLES `system_logs` WRITE;
/*!40000 ALTER TABLE `system_logs` DISABLE KEYS */;
INSERT INTO `system_logs` VALUES (1,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-01 11:07:30'),(2,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"event\",\"bootcamp\"]}','2026-07-01 11:39:19'),(3,1,'CREATE_USER','User',2,'::1','{\"name\":\"Yavuz Selim Yiâ”€ÅŸit\",\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-01 11:42:30'),(4,1,'UPDATE_USER','User',2,'::1','{\"name\":\"Yavuz Selim Yiâ”€ÅŸit\",\"is_active\":1,\"is_super_admin\":0,\"can_manage_passwords\":0}','2026-07-01 11:43:09'),(5,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-01 11:43:24'),(6,2,'APP_INTERACTION','App',2,'::1','{\"department_id\":6,\"app_slug\":\"bootcamp\"}','2026-07-01 12:19:22'),(7,2,'APP_INTERACTION','App',2,'::1','{\"department_id\":6,\"app_slug\":\"bootcamp\"}','2026-07-01 12:20:40'),(8,2,'APP_INTERACTION','App',2,'::1','{\"department_id\":6,\"app_slug\":\"bootcamp\"}','2026-07-01 12:20:42'),(9,2,'APP_INTERACTION','App',2,'::1','{\"department_id\":6,\"app_slug\":\"bootcamp\"}','2026-07-01 12:22:49'),(10,2,'APP_INTERACTION','App',2,'::1','{\"department_id\":6,\"app_slug\":\"bootcamp\"}','2026-07-01 12:22:51'),(11,1,'LOGOUT','User',1,'::1',NULL,'2026-07-01 12:23:19'),(12,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-01 12:23:25'),(13,2,'APP_INTERACTION','App',1,'::1','{\"department_id\":6,\"app_slug\":\"event\"}','2026-07-01 12:29:41'),(14,2,'APP_INTERACTION','App',1,'::1','{\"department_id\":6,\"app_slug\":\"event\"}','2026-07-01 12:32:18'),(15,1,'LOGOUT','User',1,'::1',NULL,'2026-07-01 12:45:14'),(16,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-01 12:45:26'),(17,1,'SAVE_SITE_TEAM_PROFILE','User',2,'::1','{\"department_id\":6,\"is_visible\":1}','2026-07-01 12:55:04'),(18,1,'SAVE_SITE_TEAM_PROFILE','User',2,'::1','{\"department_id\":6,\"is_visible\":1}','2026-07-01 12:55:55'),(19,1,'UPDATE_SITE_SETTING','SiteSetting',NULL,'::1','{\"key\":\"popupConfig\"}','2026-07-01 13:18:49'),(20,1,'UPDATE_SITE_SETTING','SiteSetting',NULL,'::1','{\"key\":\"popupConfig\"}','2026-07-01 13:18:59'),(21,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"form\",\"drive\",\"calendar\",\"announcement\",\"report\",\"directory\",\"members\",\"finance\",\"dues\",\"sponsor\",\"event\",\"partner\",\"bootcamp\",\"certificate\",\"inventory\",\"links\",\"qr\",\"shortener\",\"agile\",\"passwords\",\"ideahub\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"meeting\",\"budget\",\"mentorship\",\"social\",\"alumni\"]}','2026-07-01 13:47:33'),(22,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-01 16:54:53'),(23,2,'APP_INTERACTION','App',18,'::1','{\"department_id\":6,\"app_slug\":\"inventory\"}','2026-07-01 17:05:52'),(24,2,'CREATE_FINANCE_TX','FinanceTransaction',1,'::1','{\"type\":\"income\",\"amount\":53245,\"department_id\":6}','2026-07-01 17:07:08'),(25,2,'CREATE_FINANCE_TX','FinanceTransaction',2,'::1','{\"type\":\"expense\",\"amount\":43242,\"department_id\":6}','2026-07-01 17:08:08'),(26,2,'UPDATE_FINANCE_TX','FinanceTransaction',2,'::1','{\"type\":\"expense\",\"amount\":43242,\"department_id\":6}','2026-07-01 17:08:32'),(27,2,'CREATE_DUES_PERIOD','DuesPeriod',1,'::1','{\"name\":\"fdsafasf\",\"amount\":50,\"department_id\":6}','2026-07-01 17:08:46'),(28,2,'TOGGLE_DUES_PAYMENT','DuesPayment',1,'::1','{\"status\":\"paid\",\"department_id\":6}','2026-07-01 17:10:35'),(29,2,'CREATE_FINANCE_TX','FinanceTransaction',4,'::1','{\"type\":\"income\",\"amount\":654456,\"department_id\":6}','2026-07-01 17:10:51'),(30,2,'RECEIVE_FINANCE_PROXY','FinanceTransaction',4,'::1','{\"department_id\":6}','2026-07-01 17:10:54'),(31,2,'CREATE_FINANCE_TX','FinanceTransaction',5,'::1','{\"type\":\"expense\",\"amount\":543534,\"department_id\":6}','2026-07-01 17:10:58'),(32,2,'RECEIVE_FINANCE_PROXY','FinanceTransaction',5,'::1','{\"department_id\":6}','2026-07-01 17:11:00'),(33,2,'TOGGLE_DUES_PAYMENT','DuesPayment',1,'::1','{\"status\":\"unpaid\",\"department_id\":6}','2026-07-01 17:11:06'),(34,2,'TOGGLE_DUES_PAYMENT','DuesPayment',1,'::1','{\"status\":\"paid\",\"department_id\":6}','2026-07-01 17:11:11'),(35,2,'APP_INTERACTION','App',9,'::1','{\"department_id\":6,\"app_slug\":\"announcement\"}','2026-07-01 17:11:21'),(36,2,'APP_INTERACTION','App',30,'::1','{\"department_id\":6,\"app_slug\":\"budget\"}','2026-07-01 17:13:51'),(37,2,'APP_INTERACTION','App',30,'::1','{\"department_id\":6,\"app_slug\":\"budget\"}','2026-07-01 17:13:55'),(38,2,'APP_INTERACTION','App',30,'::1','{\"department_id\":6,\"app_slug\":\"budget\"}','2026-07-01 17:13:59'),(39,2,'APP_INTERACTION','App',30,'::1','{\"department_id\":6,\"app_slug\":\"budget\"}','2026-07-01 17:14:08'),(40,2,'APP_INTERACTION','App',30,'::1','{\"department_id\":6,\"app_slug\":\"budget\"}','2026-07-01 17:14:10'),(41,2,'APP_INTERACTION','App',16,'::1','{\"department_id\":6,\"app_slug\":\"partner\"}','2026-07-01 17:14:29'),(42,2,'APP_INTERACTION','App',16,'::1','{\"department_id\":6,\"app_slug\":\"partner\"}','2026-07-01 17:14:33'),(43,2,'APP_INTERACTION','App',16,'::1','{\"department_id\":6,\"app_slug\":\"partner\"}','2026-07-01 17:14:34'),(44,2,'APP_INTERACTION','App',16,'::1','{\"department_id\":6,\"app_slug\":\"partner\"}','2026-07-01 17:14:35'),(45,2,'APP_INTERACTION','App',7,'::1','{\"department_id\":6,\"app_slug\":\"drive\"}','2026-07-01 17:15:29'),(46,2,'APP_INTERACTION','App',7,'::1','{\"department_id\":6,\"app_slug\":\"drive\"}','2026-07-01 17:15:29'),(47,2,'APP_INTERACTION','App',7,'::1','{\"department_id\":6,\"app_slug\":\"drive\"}','2026-07-01 17:15:29'),(48,2,'APP_INTERACTION','App',7,'::1','{\"department_id\":6,\"app_slug\":\"drive\"}','2026-07-01 17:15:29'),(49,2,'APP_INTERACTION','App',7,'::1','{\"department_id\":6,\"app_slug\":\"drive\"}','2026-07-01 17:15:29'),(50,2,'APP_INTERACTION','App',7,'::1','{\"department_id\":6,\"app_slug\":\"drive\"}','2026-07-01 17:15:29'),(51,2,'APP_INTERACTION','App',7,'::1','{\"department_id\":6,\"app_slug\":\"drive\"}','2026-07-01 17:15:29'),(52,2,'APP_INTERACTION','App',7,'::1','{\"department_id\":6,\"app_slug\":\"drive\"}','2026-07-01 17:15:29'),(53,2,'APP_INTERACTION','App',7,'::1','{\"department_id\":6,\"app_slug\":\"drive\"}','2026-07-01 17:15:29'),(54,2,'APP_INTERACTION','App',7,'::1','{\"department_id\":6,\"app_slug\":\"drive\"}','2026-07-01 17:15:29'),(55,2,'APP_INTERACTION','App',7,'::1','{\"department_id\":6,\"app_slug\":\"drive\"}','2026-07-01 17:15:29'),(56,2,'APP_INTERACTION','App',7,'::1','{\"department_id\":6,\"app_slug\":\"drive\"}','2026-07-01 17:15:29'),(57,2,'APP_INTERACTION','App',7,'::1','{\"department_id\":6,\"app_slug\":\"drive\"}','2026-07-01 17:15:41'),(58,2,'APP_INTERACTION','App',7,'::1','{\"department_id\":6,\"app_slug\":\"drive\"}','2026-07-01 17:15:44'),(59,2,'APP_INTERACTION','App',22,'::1','{\"department_id\":6,\"app_slug\":\"agile\"}','2026-07-01 17:16:46'),(60,2,'APP_INTERACTION','App',22,'::1','{\"department_id\":6,\"app_slug\":\"agile\"}','2026-07-01 17:16:54'),(61,2,'APP_INTERACTION','App',22,'::1','{\"department_id\":6,\"app_slug\":\"agile\"}','2026-07-01 17:16:56'),(62,2,'APP_INTERACTION','App',22,'::1','{\"department_id\":6,\"app_slug\":\"agile\"}','2026-07-01 17:16:56'),(63,2,'APP_INTERACTION','App',22,'::1','{\"department_id\":6,\"app_slug\":\"agile\"}','2026-07-01 17:16:56'),(64,2,'APP_INTERACTION','App',22,'::1','{\"department_id\":6,\"app_slug\":\"agile\"}','2026-07-01 17:16:56'),(65,2,'APP_INTERACTION','App',22,'::1','{\"department_id\":6,\"app_slug\":\"agile\"}','2026-07-01 17:16:57'),(66,2,'APP_INTERACTION','App',22,'::1','{\"department_id\":6,\"app_slug\":\"agile\"}','2026-07-01 17:16:57'),(67,2,'APP_INTERACTION','App',22,'::1','{\"department_id\":6,\"app_slug\":\"agile\"}','2026-07-01 17:16:58'),(68,2,'APP_INTERACTION','App',22,'::1','{\"department_id\":6,\"app_slug\":\"agile\"}','2026-07-01 17:16:58'),(69,2,'APP_INTERACTION','App',22,'::1','{\"department_id\":6,\"app_slug\":\"agile\"}','2026-07-01 17:17:05'),(70,2,'APP_INTERACTION','App',22,'::1','{\"department_id\":6,\"app_slug\":\"agile\"}','2026-07-01 17:17:14'),(71,2,'APP_INTERACTION','App',22,'::1','{\"department_id\":6,\"app_slug\":\"agile\"}','2026-07-01 17:17:15'),(72,2,'APP_INTERACTION','App',22,'::1','{\"department_id\":6,\"app_slug\":\"agile\"}','2026-07-01 17:17:15'),(73,2,'APP_INTERACTION','App',32,'::1','{\"department_id\":6,\"app_slug\":\"social\"}','2026-07-01 17:20:11'),(74,2,'APP_INTERACTION','App',32,'::1','{\"department_id\":6,\"app_slug\":\"social\"}','2026-07-01 17:22:24'),(75,2,'APP_INTERACTION','App',32,'::1','{\"department_id\":6,\"app_slug\":\"social\"}','2026-07-01 17:22:32'),(76,2,'APP_INTERACTION','App',32,'::1','{\"department_id\":6,\"app_slug\":\"social\"}','2026-07-01 17:22:33'),(77,2,'APP_INTERACTION','App',32,'::1','{\"department_id\":6,\"app_slug\":\"social\"}','2026-07-01 17:22:36'),(78,2,'APP_INTERACTION','App',32,'::1','{\"department_id\":6,\"app_slug\":\"social\"}','2026-07-01 17:22:37'),(79,2,'APP_INTERACTION','App',32,'::1','{\"department_id\":6,\"app_slug\":\"social\"}','2026-07-01 17:22:45'),(80,2,'APP_INTERACTION','App',32,'::1','{\"department_id\":6,\"app_slug\":\"social\"}','2026-07-01 17:22:46'),(81,2,'APP_INTERACTION','App',32,'::1','{\"department_id\":6,\"app_slug\":\"social\"}','2026-07-01 17:22:51'),(82,2,'APP_INTERACTION','App',32,'::1','{\"department_id\":6,\"app_slug\":\"social\"}','2026-07-01 17:22:52'),(83,2,'APP_INTERACTION','App',32,'::1','{\"department_id\":6,\"app_slug\":\"social\"}','2026-07-01 17:22:55'),(84,2,'APP_INTERACTION','App',32,'::1','{\"department_id\":6,\"app_slug\":\"social\"}','2026-07-01 17:22:57'),(85,2,'APP_INTERACTION','App',24,'::1','{\"department_id\":6,\"app_slug\":\"ideahub\"}','2026-07-01 17:24:32'),(86,2,'APP_INTERACTION','App',24,'::1','{\"department_id\":6,\"app_slug\":\"ideahub\"}','2026-07-01 17:24:34'),(87,2,'APP_INTERACTION','App',24,'::1','{\"department_id\":6,\"app_slug\":\"ideahub\"}','2026-07-01 17:24:37'),(88,2,'APP_INTERACTION','App',24,'::1','{\"department_id\":6,\"app_slug\":\"ideahub\"}','2026-07-01 17:24:39'),(89,2,'APP_INTERACTION','App',24,'::1','{\"department_id\":6,\"app_slug\":\"ideahub\"}','2026-07-01 17:24:43'),(90,2,'APP_INTERACTION','App',24,'::1','{\"department_id\":6,\"app_slug\":\"ideahub\"}','2026-07-01 17:24:45'),(91,2,'APP_INTERACTION','App',24,'::1','{\"department_id\":6,\"app_slug\":\"ideahub\"}','2026-07-01 17:24:48'),(92,2,'APP_INTERACTION','App',24,'::1','{\"department_id\":6,\"app_slug\":\"ideahub\"}','2026-07-01 17:24:51'),(93,2,'APP_INTERACTION','App',24,'::1','{\"department_id\":6,\"app_slug\":\"ideahub\"}','2026-07-01 17:24:54'),(94,2,'APP_INTERACTION','App',20,'::1','{\"department_id\":6,\"app_slug\":\"qr\"}','2026-07-01 17:25:23'),(95,2,'APP_INTERACTION','App',21,'::1','{\"department_id\":6,\"app_slug\":\"shortener\"}','2026-07-01 17:26:47'),(96,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-01 17:28:27'),(97,1,'CREATE_USER','User',3,'::1','{\"name\":\"Arda â”œÃ»zer\",\"email\":\"arda@innomis.tr\"}','2026-07-01 17:29:08'),(98,2,'APP_INTERACTION','App',33,'::1','{\"department_id\":6,\"app_slug\":\"alumni\"}','2026-07-01 17:29:29'),(99,2,'APP_INTERACTION','App',33,'::1','{\"department_id\":6,\"app_slug\":\"alumni\"}','2026-07-01 17:29:32'),(100,2,'APP_INTERACTION','App',31,'::1','{\"department_id\":6,\"app_slug\":\"mentorship\"}','2026-07-01 17:30:51'),(101,1,'ASSIGN_DEPARTMENT_TO_USER','User',3,'::1','{\"department_id\":6,\"role\":\"member\"}','2026-07-01 17:31:23'),(102,2,'APP_INTERACTION','App',31,'::1','{\"department_id\":6,\"app_slug\":\"mentorship\"}','2026-07-01 17:31:32'),(103,2,'APP_INTERACTION','App',31,'::1','{\"department_id\":6,\"app_slug\":\"mentorship\"}','2026-07-01 17:31:37'),(104,2,'APP_INTERACTION','App',31,'::1','{\"department_id\":6,\"app_slug\":\"mentorship\"}','2026-07-01 17:31:39'),(105,2,'APP_INTERACTION','App',31,'::1','{\"department_id\":6,\"app_slug\":\"mentorship\"}','2026-07-01 17:31:43'),(106,2,'APP_INTERACTION','App',31,'::1','{\"department_id\":6,\"app_slug\":\"mentorship\"}','2026-07-01 17:31:47'),(107,2,'APP_INTERACTION','App',11,'::1','{\"department_id\":6,\"app_slug\":\"directory\"}','2026-07-01 17:36:20'),(108,2,'APP_INTERACTION','App',15,'::1','{\"department_id\":6,\"app_slug\":\"sponsor\"}','2026-07-01 17:36:53'),(109,2,'APP_INTERACTION','App',15,'::1','{\"department_id\":6,\"app_slug\":\"sponsor\"}','2026-07-01 17:45:49'),(110,2,'APP_INTERACTION','App',28,'::1','{\"department_id\":6,\"app_slug\":\"wiki\"}','2026-07-01 17:47:53'),(111,2,'APP_INTERACTION','App',28,'::1','{\"department_id\":6,\"app_slug\":\"wiki\"}','2026-07-01 17:48:04'),(112,2,'APP_INTERACTION','App',27,'::1','{\"department_id\":6,\"app_slug\":\"trophy\"}','2026-07-01 17:49:12'),(113,2,'APP_INTERACTION','App',27,'::1','{\"department_id\":6,\"app_slug\":\"trophy\"}','2026-07-01 17:49:19'),(114,2,'APP_INTERACTION','App',17,'::1','{\"department_id\":6,\"app_slug\":\"certificate\"}','2026-07-01 17:50:09'),(115,2,'APP_INTERACTION','App',17,'::1','{\"department_id\":6,\"app_slug\":\"certificate\"}','2026-07-01 17:50:28'),(116,2,'APP_INTERACTION','App',17,'::1','{\"department_id\":6,\"app_slug\":\"certificate\"}','2026-07-01 17:50:34'),(117,2,'APP_INTERACTION','App',23,'::1','{\"department_id\":6,\"app_slug\":\"passwords\"}','2026-07-01 17:51:17'),(118,2,'APP_INTERACTION','App',23,'::1','{\"department_id\":6,\"app_slug\":\"passwords\"}','2026-07-01 17:51:37'),(119,1,'ASSIGN_DEPARTMENT_TO_USER','User',2,'::1','{\"department_id\":1,\"role\":\"director\"}','2026-07-01 17:53:24'),(120,1,'UPDATE_USER','User',2,'::1','{\"name\":\"Yavuz Selim Yiâ”€ÅŸit\",\"is_active\":1,\"is_super_admin\":0,\"can_manage_passwords\":0,\"is_representative\":0,\"is_alumni\":0}','2026-07-01 17:53:34'),(121,2,'LOGOUT','User',2,'::1',NULL,'2026-07-01 17:53:45'),(122,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-01 17:54:04'),(123,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',1,'::1','{\"modules\":[\"bylaws\"]}','2026-07-01 17:54:20'),(124,2,'APP_INTERACTION','App',35,'::1','{\"department_id\":1,\"app_slug\":\"bylaws\"}','2026-07-01 17:54:32'),(125,2,'APP_INTERACTION','App',35,'::1','{\"department_id\":1,\"app_slug\":\"bylaws\"}','2026-07-01 17:54:49'),(126,2,'APP_INTERACTION','App',35,'::1','{\"department_id\":1,\"app_slug\":\"bylaws\"}','2026-07-01 17:55:13'),(127,2,'APP_INTERACTION','App',35,'::1','{\"department_id\":1,\"app_slug\":\"bylaws\"}','2026-07-01 17:55:20'),(128,2,'APP_INTERACTION','App',35,'::1','{\"department_id\":1,\"app_slug\":\"bylaws\"}','2026-07-01 17:55:22'),(129,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',1,'::1','{\"modules\":[\"announcement\",\"bylaws\",\"audit\",\"meeting\"]}','2026-07-01 18:02:28'),(130,2,'APP_INTERACTION','App',38,'::1','{\"department_id\":1,\"app_slug\":\"meeting\"}','2026-07-01 18:02:57'),(131,2,'APP_INTERACTION','App',38,'::1','{\"department_id\":1,\"app_slug\":\"meeting\"}','2026-07-01 18:03:06'),(132,2,'APP_INTERACTION','App',38,'::1','{\"department_id\":1,\"app_slug\":\"meeting\"}','2026-07-01 18:03:10'),(133,2,'APP_INTERACTION','App',38,'::1','{\"department_id\":1,\"app_slug\":\"meeting\"}','2026-07-01 18:04:51'),(134,2,'APP_INTERACTION','App',38,'::1','{\"department_id\":1,\"app_slug\":\"meeting\"}','2026-07-01 18:05:29'),(135,2,'APP_INTERACTION','App',38,'::1','{\"department_id\":1,\"app_slug\":\"meeting\"}','2026-07-01 18:05:34'),(136,2,'APP_INTERACTION','App',38,'::1','{\"department_id\":1,\"app_slug\":\"meeting\"}','2026-07-01 18:05:36'),(137,2,'APP_INTERACTION','App',38,'::1','{\"department_id\":1,\"app_slug\":\"meeting\"}','2026-07-01 18:06:33'),(138,2,'APP_INTERACTION','App',38,'::1','{\"department_id\":1,\"app_slug\":\"meeting\"}','2026-07-01 18:06:34'),(139,2,'APP_INTERACTION','App',36,'::1','{\"department_id\":1,\"app_slug\":\"announcement\"}','2026-07-01 18:07:24'),(140,2,'APP_INTERACTION','App',36,'::1','{\"department_id\":1,\"app_slug\":\"announcement\"}','2026-07-01 18:07:28'),(141,2,'APP_INTERACTION','App',12,'::1','{\"department_id\":6,\"app_slug\":\"members\"}','2026-07-01 18:11:53'),(142,2,'APP_INTERACTION','App',12,'::1','{\"department_id\":6,\"app_slug\":\"members\"}','2026-07-01 18:47:13'),(143,2,'APP_INTERACTION','App',12,'::1','{\"department_id\":6,\"app_slug\":\"members\"}','2026-07-01 18:47:21'),(144,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',3,'::1','{\"modules\":[\"members\"]}','2026-07-01 18:48:36'),(145,2,'LOGOUT','User',2,'::1',NULL,'2026-07-01 18:48:43'),(146,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-01 18:48:57'),(147,1,'ASSIGN_DEPARTMENT_TO_USER','User',2,'::1','{\"department_id\":3,\"role\":\"member\"}','2026-07-01 18:49:12'),(148,2,'LOGOUT','User',2,'::1',NULL,'2026-07-01 18:49:18'),(149,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-01 18:49:33'),(150,2,'APP_INTERACTION','App',9,'::1','{\"department_id\":6,\"app_slug\":\"announcement\"}','2026-07-01 18:52:39'),(151,2,'APP_INTERACTION','App',9,'::1','{\"department_id\":6,\"app_slug\":\"announcement\"}','2026-07-01 18:52:42'),(152,2,'APP_INTERACTION','App',9,'::1','{\"department_id\":6,\"app_slug\":\"announcement\"}','2026-07-01 18:52:54'),(153,2,'APP_INTERACTION','App',9,'::1','{\"department_id\":6,\"app_slug\":\"announcement\"}','2026-07-01 18:53:11'),(154,2,'APP_INTERACTION','App',9,'::1','{\"department_id\":6,\"app_slug\":\"announcement\"}','2026-07-01 18:53:14'),(155,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-01 19:43:41'),(156,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"form\",\"drive\",\"report\",\"sponsor\",\"event\",\"links\",\"qr\",\"shortener\",\"audit\",\"mentorship\",\"social\"]}','2026-07-01 20:09:43'),(157,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"form\",\"drive\",\"report\",\"sponsor\",\"event\",\"links\",\"qr\",\"shortener\",\"audit\",\"trophy\",\"mentorship\",\"social\"]}','2026-07-01 20:09:46'),(158,1,'LOGOUT','User',1,'::1',NULL,'2026-07-01 20:11:21'),(159,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-01 20:11:24'),(160,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"form\",\"drive\",\"report\",\"sponsor\",\"event\",\"links\",\"qr\",\"shortener\",\"audit\",\"trophy\",\"mentorship\",\"social\",\"inbox\"]}','2026-07-01 20:16:34'),(161,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"form\",\"drive\",\"report\",\"sponsor\",\"event\",\"links\",\"qr\",\"shortener\",\"audit\",\"trophy\",\"mentorship\",\"social\",\"inbox\"]}','2026-07-01 20:28:50'),(162,1,'UPDATE_SITE_SETTING','SiteSetting',NULL,'::1','{\"key\":\"form_routing\"}','2026-07-01 20:35:44'),(163,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-01 20:50:35'),(164,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"form\",\"drive\",\"report\",\"sponsor\",\"event\",\"links\",\"qr\",\"shortener\",\"audit\",\"trophy\",\"mentorship\",\"social\",\"inbox\",\"applications\"]}','2026-07-01 21:01:07'),(165,2,'APP_INTERACTION','App',41,'::1','{\"department_id\":6,\"app_slug\":\"applications\"}','2026-07-01 21:15:04'),(166,2,'APP_INTERACTION','App',41,'::1','{\"department_id\":6,\"app_slug\":\"applications\"}','2026-07-01 21:15:07'),(167,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"form\",\"drive\",\"report\",\"sponsor\",\"event\",\"links\",\"qr\",\"shortener\",\"audit\",\"trophy\",\"mentorship\",\"social\",\"inbox\",\"applications\",\"media\"]}','2026-07-01 21:33:56'),(168,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"form\",\"drive\",\"report\",\"sponsor\",\"event\",\"links\",\"qr\",\"shortener\",\"audit\",\"trophy\",\"mentorship\",\"social\",\"inbox\",\"applications\",\"media\"]}','2026-07-01 21:45:57'),(169,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 21:52:39'),(170,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 21:54:06'),(171,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 21:54:10'),(172,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:01:35'),(173,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:01:41'),(174,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:45'),(175,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:45'),(176,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:45'),(177,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:45'),(178,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:45'),(179,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:45'),(180,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(181,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(182,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(183,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(184,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(185,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(186,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(187,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(188,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(189,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(190,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(191,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(192,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(193,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(194,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(195,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(196,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(197,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(198,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(199,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(200,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(201,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(202,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(203,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(204,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(205,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(206,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(207,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(208,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(209,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(210,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(211,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(212,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(213,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:53'),(214,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(215,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(216,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(217,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(218,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(219,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(220,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(221,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(222,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(223,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(224,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(225,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(226,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(227,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(228,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(229,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(230,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(231,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(232,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(233,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(234,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(235,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:03:54'),(236,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:06:59'),(237,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:00'),(238,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:02'),(239,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:03'),(240,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:04'),(241,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:06'),(242,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:08'),(243,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:09'),(244,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:10'),(245,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:11'),(246,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:13'),(247,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:14'),(248,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:15'),(249,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:16'),(250,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:17'),(251,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:18'),(252,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:19'),(253,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:21'),(254,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:22'),(255,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:23'),(256,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:07:23'),(257,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-01 22:12:26'),(258,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:19:40'),(259,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:19:45'),(260,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:19:52'),(261,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:19:56'),(262,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:20:10'),(263,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:20:14'),(264,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:21:19'),(265,2,'APP_INTERACTION','App',42,'::1','{\"department_id\":6,\"app_slug\":\"media\"}','2026-07-01 22:21:22'),(266,2,'CREATE_FORM','App',43,'::1','{\"title\":\"TEST\",\"department_id\":6}','2026-07-01 22:32:53'),(267,2,'APP_INTERACTION','App',43,'::1','{\"department_id\":6,\"app_slug\":\"test-1782945173\"}','2026-07-01 22:33:05'),(268,2,'APP_INTERACTION','App',43,'::1','{\"department_id\":6,\"app_slug\":\"test-1782945173\"}','2026-07-01 22:34:19'),(269,2,'CREATE_FORM','App',44,'::1','{\"title\":\"deneme\",\"department_id\":6}','2026-07-01 22:37:00'),(270,2,'CREATE_FORM','App',45,'::1','{\"title\":\"deneme\",\"department_id\":6}','2026-07-01 22:43:35'),(271,2,'CREATE_FORM','App',46,'::1','{\"title\":\"deneme\",\"department_id\":6}','2026-07-01 22:43:41'),(272,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-01 22:58:53'),(273,2,'APP_INTERACTION','App',46,'::1','{\"department_id\":6,\"app_slug\":\"deneme-1\"}','2026-07-01 22:59:05'),(274,2,'APP_INTERACTION','App',46,'::1','{\"department_id\":6,\"app_slug\":\"deneme-1\"}','2026-07-01 23:00:41'),(275,2,'APP_INTERACTION','App',46,'::1','{\"department_id\":6,\"app_slug\":\"deneme-1\"}','2026-07-01 23:01:05'),(276,2,'APP_INTERACTION','App',46,'::1','{\"department_id\":6,\"app_slug\":\"deneme-1\"}','2026-07-01 23:32:56'),(277,2,'APP_INTERACTION','App',46,'::1','{\"department_id\":6,\"app_slug\":\"deneme-1\"}','2026-07-01 23:37:26'),(278,2,'APP_INTERACTION','App',46,'::1','{\"department_id\":6,\"app_slug\":\"deneme-1\"}','2026-07-01 23:37:28'),(279,2,'PUBLIC_GLOBAL_APP_INTERACTION','App',46,'::1','{\"department_id\":6,\"app_slug\":\"deneme-1\"}','2026-07-01 23:37:38'),(280,2,'PUBLIC_GLOBAL_APP_INTERACTION','App',46,'::1','{\"department_id\":6,\"app_slug\":\"deneme-1\"}','2026-07-01 23:37:41'),(281,2,'PUBLIC_GLOBAL_APP_INTERACTION','App',46,'::1','{\"department_id\":6,\"app_slug\":\"deneme-1\"}','2026-07-01 23:40:50'),(282,2,'APP_INTERACTION','App',46,'::1','{\"department_id\":6,\"app_slug\":\"deneme-1\"}','2026-07-01 23:42:37'),(283,2,'APP_INTERACTION','App',46,'::1','{\"department_id\":6,\"app_slug\":\"deneme-1\"}','2026-07-01 23:43:57'),(284,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-02 00:12:50'),(285,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-02 08:50:22'),(286,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-02 08:50:31'),(287,1,'DELETE_SITE_HISTORY','SiteHistory',1,'::1','{\"title\":\"En â”€â–‘novatif Topluluk â”œÃ»dâ”œâ•lâ”œâ•\"}','2026-07-02 09:01:13'),(288,1,'UPDATE_TROPHY_FROM_SUPERADMIN','AppTrophy',1,'::1','{\"title\":\"sasvfdsaadsf\"}','2026-07-02 09:04:04'),(289,1,'UPDATE_SITE_SETTING','SiteSetting',NULL,'::1','{\"key\":\"sponsors\"}','2026-07-02 09:25:20'),(290,1,'UPDATE_SITE_SETTING','SiteSetting',NULL,'::1','{\"key\":\"stats\"}','2026-07-02 09:33:36'),(291,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-02 13:52:02'),(292,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-02 14:34:20'),(293,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"form\",\"drive\",\"calendar\",\"announcement\",\"report\",\"directory\",\"members\",\"finance\",\"dues\",\"sponsor\",\"event\",\"partner\",\"bootcamp\",\"certificate\",\"inventory\",\"links\",\"qr\",\"shortener\",\"agile\",\"passwords\",\"ideahub\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"meeting\",\"budget\",\"mentorship\",\"social\",\"alumni\",\"inbox\",\"applications\",\"media\"]}','2026-07-02 14:34:46'),(294,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"form\",\"drive\",\"calendar\",\"announcement\",\"report\",\"directory\",\"members\",\"finance\",\"dues\",\"sponsor\",\"event\",\"partner\",\"bootcamp\",\"certificate\",\"inventory\",\"links\",\"qr\",\"shortener\",\"agile\",\"passwords\",\"ideahub\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"meeting\",\"budget\",\"mentorship\",\"social\",\"alumni\",\"inbox\",\"applications\",\"media\"]}','2026-07-02 14:34:47'),(295,1,'LOGOUT','User',1,'::1',NULL,'2026-07-02 14:35:14'),(296,NULL,'LOGIN_FAILED','User',NULL,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-02 14:35:23'),(297,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-02 14:35:34'),(298,2,'LOGOUT','User',2,'::1',NULL,'2026-07-02 14:43:30'),(299,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-02 15:04:23'),(300,2,'TOGGLE_DUES_PAYMENT','DuesPayment',1,'::1','{\"status\":\"unpaid\",\"department_id\":6}','2026-07-02 16:18:47'),(301,2,'CREATE_DUES_PERIOD','DuesPeriod',2,'::1','{\"name\":\"gfddsfg\",\"amount\":234,\"department_id\":6}','2026-07-02 16:20:56'),(302,NULL,'LOGIN_FAILED','User',NULL,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-02 18:55:30'),(303,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-02 18:55:39'),(304,2,'CREATE_DUES_PERIOD','DuesPeriod',3,'::1','{\"name\":\"yâ”€â–’ut\",\"amount\":897,\"department_id\":6}','2026-07-02 18:57:24'),(305,NULL,'LOGIN_FAILED','User',NULL,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-02 19:15:06'),(306,NULL,'LOGIN_FAILED','User',NULL,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-02 19:15:21'),(307,2,'LOGOUT','User',2,'::1',NULL,'2026-07-02 19:15:35'),(308,NULL,'LOGIN_FAILED','User',NULL,'::1','{\"email\":\"admin@innomis.tr\"}','2026-07-02 19:16:05'),(309,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-02 19:16:17'),(310,NULL,'LOGIN_FAILED','User',NULL,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-02 19:16:36'),(311,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-02 19:16:46'),(312,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"drive\",\"calendar\",\"directory\",\"members\",\"sponsor\",\"event\",\"partner\",\"bootcamp\",\"certificate\",\"inventory\",\"links\",\"qr\",\"shortener\",\"agile\",\"passwords\",\"ideahub\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"meeting\",\"budget\",\"mentorship\",\"social\",\"alumni\",\"inbox\",\"applications\",\"media\"]}','2026-07-02 19:17:54'),(313,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"drive\",\"calendar\",\"members\",\"sponsor\",\"event\",\"partner\",\"bootcamp\",\"certificate\",\"inventory\",\"links\",\"qr\",\"shortener\",\"agile\",\"passwords\",\"ideahub\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"meeting\",\"budget\",\"mentorship\",\"social\",\"alumni\",\"inbox\",\"applications\",\"media\"]}','2026-07-02 19:18:14'),(314,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"sponsor\",\"event\",\"partner\",\"bootcamp\",\"certificate\",\"inventory\",\"links\",\"qr\",\"shortener\",\"agile\",\"passwords\",\"ideahub\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"meeting\",\"budget\",\"mentorship\",\"social\",\"alumni\",\"inbox\",\"applications\",\"media\"]}','2026-07-02 19:20:42'),(315,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"sponsor\",\"partner\",\"bootcamp\",\"certificate\",\"inventory\",\"links\",\"qr\",\"shortener\",\"agile\",\"passwords\",\"ideahub\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"meeting\",\"budget\",\"mentorship\",\"social\",\"alumni\",\"inbox\",\"applications\",\"media\"]}','2026-07-02 19:25:42'),(316,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"sponsor\",\"partner\",\"bootcamp\",\"certificate\",\"links\",\"qr\",\"shortener\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"meeting\",\"budget\",\"mentorship\",\"social\",\"alumni\",\"inbox\",\"applications\",\"media\"]}','2026-07-02 19:29:38'),(317,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"sponsor\",\"partner\",\"bootcamp\",\"certificate\",\"links\",\"qr\",\"shortener\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"meeting\",\"budget\",\"mentorship\",\"social\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-02 19:32:34'),(318,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"sponsor\",\"partner\",\"bootcamp\",\"certificate\",\"links\",\"qr\",\"shortener\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"meeting\",\"budget\",\"mentorship\",\"social\",\"alumni\",\"inbox\",\"applications\",\"media\"]}','2026-07-02 19:33:16'),(319,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"sponsor\",\"partner\",\"bootcamp\",\"certificate\",\"links\",\"qr\",\"shortener\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"meeting\",\"budget\",\"mentorship\",\"social\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-02 19:33:36'),(320,2,'APP_INTERACTION','App',29,'::1','{\"department_id\":6,\"app_slug\":\"meeting\"}','2026-07-02 19:34:41'),(321,2,'APP_INTERACTION','App',29,'::1','{\"department_id\":6,\"app_slug\":\"meeting\"}','2026-07-02 19:34:52'),(322,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"sponsor\",\"partner\",\"bootcamp\",\"certificate\",\"links\",\"qr\",\"shortener\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"budget\",\"mentorship\",\"social\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-02 19:36:53'),(323,2,'APP_INTERACTION','App',12,'::1','{\"department_id\":6,\"app_slug\":\"members\"}','2026-07-02 19:41:50'),(324,2,'APP_INTERACTION','App',12,'::1','{\"department_id\":6,\"app_slug\":\"members\"}','2026-07-02 19:58:11'),(325,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-03 00:04:54'),(326,2,'APP_INTERACTION','App',12,'::1','{\"department_id\":6,\"app_slug\":\"members\"}','2026-07-03 00:08:24'),(327,2,'APP_INTERACTION','App',12,'::1','{\"department_id\":6,\"app_slug\":\"members\"}','2026-07-03 00:08:26'),(328,2,'APP_INTERACTION','App',12,'::1','{\"department_id\":6,\"app_slug\":\"members\"}','2026-07-03 00:12:29'),(329,NULL,'LOGIN_FAILED','User',NULL,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-03 00:17:01'),(330,2,'APP_INTERACTION','App',31,'::1','{\"department_id\":6,\"app_slug\":\"mentorship\"}','2026-07-03 00:17:16'),(331,NULL,'LOGIN_FAILED','User',NULL,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-03 00:19:15'),(332,2,'LOGOUT','User',2,'::1',NULL,'2026-07-03 00:19:28'),(333,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-03 00:19:54'),(334,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-03 00:20:59'),(335,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"sponsor\",\"partner\",\"bootcamp\",\"certificate\",\"links\",\"qr\",\"shortener\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"budget\",\"social\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-03 00:21:41'),(336,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"partner\",\"bootcamp\",\"certificate\",\"links\",\"qr\",\"shortener\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"budget\",\"social\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-03 00:22:34'),(337,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"partner\",\"bootcamp\",\"certificate\",\"links\",\"shortener\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"budget\",\"social\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-03 00:29:58'),(338,2,'APP_INTERACTION','App',21,'::1','{\"department_id\":6,\"app_slug\":\"shortener\"}','2026-07-03 00:32:58'),(339,2,'APP_INTERACTION','App',32,'::1','{\"department_id\":6,\"app_slug\":\"social\"}','2026-07-03 00:37:25'),(340,2,'APP_INTERACTION','App',32,'::1','{\"department_id\":6,\"app_slug\":\"social\"}','2026-07-03 00:37:27'),(341,2,'APP_INTERACTION','App',32,'::1','{\"department_id\":6,\"app_slug\":\"social\"}','2026-07-03 00:37:29'),(342,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"sponsor\",\"partner\",\"bootcamp\",\"certificate\",\"links\",\"shortener\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"budget\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-03 00:38:28'),(343,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"sponsor\",\"bootcamp\",\"certificate\",\"links\",\"shortener\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"budget\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-03 00:39:08'),(344,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"bootcamp\",\"certificate\",\"links\",\"shortener\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"budget\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-03 00:39:59'),(345,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"bootcamp\",\"certificate\",\"links\",\"shortener\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"wiki\",\"budget\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-03 00:41:07'),(346,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"bootcamp\",\"certificate\",\"links\",\"shortener\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"budget\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-03 00:41:22'),(347,2,'APP_INTERACTION','App',28,'::1','{\"department_id\":6,\"app_slug\":\"wiki\"}','2026-07-03 00:44:55'),(348,2,'APP_INTERACTION','App',28,'::1','{\"department_id\":6,\"app_slug\":\"wiki\"}','2026-07-03 00:45:10'),(349,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"bootcamp\",\"certificate\",\"links\",\"shortener\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"trophy\",\"budget\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-03 00:48:04'),(350,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"bootcamp\",\"links\",\"shortener\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"trophy\",\"budget\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-03 00:48:27'),(351,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"bootcamp\",\"shortener\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"trophy\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-03 00:51:54'),(352,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"shortener\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"trophy\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-03 00:52:26'),(353,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"shortener\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-03 00:52:46'),(354,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"calendar\",\"members\",\"agile\",\"passwords\",\"bylaws\",\"audit\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-03 00:53:40'),(355,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"members\",\"agile\",\"passwords\",\"audit\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-03 00:56:01'),(356,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-03 12:50:14'),(357,2,'APP_INTERACTION','App',12,'::1','{\"department_id\":6,\"app_slug\":\"members\"}','2026-07-03 13:17:17'),(358,2,'APP_INTERACTION','App',12,'::1','{\"department_id\":6,\"app_slug\":\"members\"}','2026-07-03 13:17:19'),(359,2,'APP_INTERACTION','App',12,'::1','{\"department_id\":6,\"app_slug\":\"members\"}','2026-07-03 13:18:17'),(360,NULL,'LOGIN_FAILED','User',NULL,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-03 13:26:37'),(361,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-03 13:27:59'),(362,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"agile\",\"audit\",\"alumni\",\"inbox\",\"applications\"]}','2026-07-03 13:28:12'),(363,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"agile\",\"audit\",\"alumni\"]}','2026-07-03 13:49:26'),(364,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[]}','2026-07-03 13:52:39'),(365,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"form\",\"drive\",\"calendar\",\"announcement\",\"report\",\"directory\",\"members\",\"finance\"]}','2026-07-03 14:01:10'),(366,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"form\",\"drive\",\"calendar\",\"announcement\",\"report\",\"directory\",\"members\",\"finance\",\"dues\",\"sponsor\",\"event\",\"partner\",\"bootcamp\",\"certificate\",\"inventory\",\"links\",\"qr\",\"shortener\",\"agile\",\"passwords\",\"ideahub\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"meeting\",\"budget\",\"mentorship\",\"social\",\"alumni\",\"inbox\",\"applications\",\"media\"]}','2026-07-03 14:01:33'),(367,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"form\",\"drive\",\"calendar\",\"announcement\",\"report\",\"directory\",\"members\",\"finance\",\"dues\",\"sponsor\",\"event\",\"partner\",\"bootcamp\",\"certificate\",\"inventory\",\"links\",\"qr\",\"shortener\",\"agile\",\"passwords\",\"ideahub\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"meeting\",\"budget\",\"mentorship\",\"social\",\"alumni\",\"inbox\",\"applications\",\"media\"]}','2026-07-03 14:13:23'),(368,NULL,'LOGIN_FAILED','User',NULL,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-03 14:58:02'),(369,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-03 14:58:12'),(370,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"form\",\"drive\",\"calendar\",\"announcement\",\"report\",\"directory\",\"members\",\"finance\",\"dues\",\"sponsor\",\"event\",\"partner\",\"bootcamp\",\"certificate\",\"inventory\",\"links\",\"qr\",\"shortener\",\"agile\",\"passwords\",\"ideahub\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"meeting\",\"budget\",\"mentorship\",\"social\",\"alumni\",\"inbox\",\"applications\",\"media\",\"task_manager\"]}','2026-07-03 14:59:44'),(371,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',1,'::1','{\"modules\":[\"task_manager\"]}','2026-07-03 14:59:56'),(372,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',6,'::1','{\"modules\":[\"form\",\"drive\",\"calendar\",\"announcement\",\"report\",\"directory\",\"members\",\"finance\",\"dues\",\"sponsor\",\"event\",\"partner\",\"bootcamp\",\"certificate\",\"inventory\",\"links\",\"qr\",\"shortener\",\"agile\",\"passwords\",\"ideahub\",\"bylaws\",\"audit\",\"trophy\",\"wiki\",\"meeting\",\"budget\",\"mentorship\",\"social\",\"alumni\",\"inbox\",\"applications\",\"media\",\"task_manager\"]}','2026-07-03 15:00:16'),(373,1,'ASSIGN_APPS_TO_DEPARTMENT','Department',1,'::1','{\"modules\":[\"task_manager\"]}','2026-07-03 15:00:21'),(374,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 15:55:41'),(375,2,'APP_INTERACTION','App',49,'::1','{\"department_id\":1,\"app_slug\":\"task_manager\"}','2026-07-03 15:56:25'),(376,2,'APP_INTERACTION','App',49,'::1','{\"department_id\":1,\"app_slug\":\"task_manager\"}','2026-07-03 15:56:28'),(377,2,'APP_INTERACTION','App',49,'::1','{\"department_id\":1,\"app_slug\":\"task_manager\"}','2026-07-03 15:56:31'),(378,2,'APP_INTERACTION','App',49,'::1','{\"department_id\":1,\"app_slug\":\"task_manager\"}','2026-07-03 15:56:31'),(379,2,'APP_INTERACTION','App',49,'::1','{\"department_id\":1,\"app_slug\":\"task_manager\"}','2026-07-03 15:56:38'),(380,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 15:56:49'),(381,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 15:56:52'),(382,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 15:56:53'),(383,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 15:56:56'),(384,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 15:56:58'),(385,2,'APP_INTERACTION','App',49,'::1','{\"department_id\":1,\"app_slug\":\"task_manager\"}','2026-07-03 16:00:05'),(386,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:00:14'),(387,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:02:09'),(388,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:02:13'),(389,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:02:18'),(390,2,'APP_INTERACTION','App',49,'::1','{\"department_id\":1,\"app_slug\":\"task_manager\"}','2026-07-03 16:02:46'),(391,2,'LOGOUT','User',2,'::1',NULL,'2026-07-03 16:02:52'),(392,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-03 16:02:58'),(393,1,'REMOVE_DEPARTMENT_FROM_USER','User',2,'::1','{\"department_id\":1}','2026-07-03 16:03:23'),(394,1,'LOGOUT','User',1,'::1',NULL,'2026-07-03 16:03:27'),(395,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-03 16:03:40'),(396,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:03:50'),(397,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:03:55'),(398,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:03:56'),(399,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:04:02'),(400,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:04:09'),(401,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:04:12'),(402,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:04:13'),(403,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:04:16'),(404,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:04:17'),(405,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:04:19'),(406,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:04:21'),(407,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:09:07'),(408,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:09:10'),(409,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:09:16'),(410,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:11:59'),(411,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:12:02'),(412,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:12:02'),(413,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:13:50'),(414,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:13:56'),(415,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:13:57'),(416,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:13:57'),(417,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:13:59'),(418,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:14:02'),(419,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:14:02'),(420,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:14:02'),(421,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:14:05'),(422,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:14:06'),(423,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:14:08'),(424,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:14:10'),(425,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:14:11'),(426,2,'LOGOUT','User',2,'::1',NULL,'2026-07-03 16:16:48'),(427,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-03 16:16:53'),(428,1,'CREATE_USER','User',4,'::1','{\"name\":\"Rukiye Erdem\",\"email\":\"rukiyeerdem@innomis.tr\"}','2026-07-03 16:17:49'),(429,1,'LOGOUT','User',1,'::1',NULL,'2026-07-03 16:17:55'),(430,4,'LOGIN_SUCCESS','User',4,'::1','{\"email\":\"rukiyeerdem@innomis.tr\"}','2026-07-03 16:18:14'),(431,4,'APP_INTERACTION','App',49,'::1','{\"department_id\":1,\"app_slug\":\"task_manager\"}','2026-07-03 16:18:49'),(432,4,'APP_INTERACTION','App',49,'::1','{\"department_id\":1,\"app_slug\":\"task_manager\"}','2026-07-03 16:19:10'),(433,4,'APP_INTERACTION','App',49,'::1','{\"department_id\":1,\"app_slug\":\"task_manager\"}','2026-07-03 16:19:20'),(434,4,'APP_INTERACTION','App',49,'::1','{\"department_id\":1,\"app_slug\":\"task_manager\"}','2026-07-03 16:19:27'),(435,4,'APP_INTERACTION','App',49,'::1','{\"department_id\":1,\"app_slug\":\"task_manager\"}','2026-07-03 16:19:37'),(436,4,'LOGOUT','User',4,'::1',NULL,'2026-07-03 16:19:39'),(437,NULL,'LOGIN_FAILED','User',NULL,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-03 16:19:46'),(438,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-03 16:20:11'),(439,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:20:23'),(440,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:20:25'),(441,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:20:26'),(442,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:20:28'),(443,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:20:29'),(444,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:20:35'),(445,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:20:38'),(446,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:20:39'),(447,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 16:24:32'),(448,2,'LOGOUT','User',2,'::1',NULL,'2026-07-03 18:04:21'),(449,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-03 18:04:24'),(450,1,'REMOVE_DEPARTMENT_FROM_USER','User',4,'::1','{\"department_id\":1}','2026-07-03 18:04:39'),(451,1,'ASSIGN_DEPARTMENT_TO_USER','User',4,'::1','{\"department_id\":1,\"role\":\"president\"}','2026-07-03 18:08:12'),(452,1,'REMOVE_DEPARTMENT_FROM_USER','User',4,'::1','{\"department_id\":1}','2026-07-03 18:10:40'),(453,1,'ASSIGN_DEPARTMENT_TO_USER','User',4,'::1','{\"department_id\":1,\"role\":\"president\"}','2026-07-03 18:10:47'),(454,1,'REMOVE_DEPARTMENT_FROM_USER','User',4,'::1','{\"department_id\":1}','2026-07-03 18:13:59'),(455,1,'ASSIGN_DEPARTMENT_TO_USER','User',4,'::1','{\"department_id\":1,\"role\":\"president\"}','2026-07-03 18:15:05'),(456,1,'LOGOUT','User',1,'::1',NULL,'2026-07-03 18:15:19'),(457,NULL,'LOGIN_FAILED','User',NULL,'::1','{\"email\":\"rukiyeerdem@innomis.tr\"}','2026-07-03 18:15:28'),(458,4,'LOGIN_SUCCESS','User',4,'::1','{\"email\":\"rukiyeerdem@innomis.tr\"}','2026-07-03 18:15:37'),(459,4,'LOGOUT','User',4,'::1',NULL,'2026-07-03 18:18:28'),(460,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-03 18:18:50'),(461,1,'ASSIGN_DEPARTMENT_TO_USER','User',4,'::1','{\"department_id\":1,\"role\":\"president\"}','2026-07-03 18:19:06'),(462,1,'ASSIGN_DEPARTMENT_TO_USER','User',4,'::1','{\"department_id\":1,\"role\":\"vice_president\"}','2026-07-03 18:22:03'),(463,1,'ASSIGN_DEPARTMENT_TO_USER','User',4,'::1','{\"department_id\":1,\"role\":\"president\"}','2026-07-03 18:22:09'),(464,1,'LOGOUT','User',1,'::1',NULL,'2026-07-03 18:41:36'),(465,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-03 18:41:44'),(466,2,'APP_INTERACTION','App',12,'::1','{\"department_id\":6,\"app_slug\":\"members\"}','2026-07-03 18:42:06'),(467,2,'LOGOUT','User',2,'::1',NULL,'2026-07-03 18:45:31'),(468,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-03 18:45:35'),(469,1,'UPDATE_SITE_SETTING','SiteSetting',NULL,'::1','{\"key\":\"brand_contact\"}','2026-07-03 18:46:53'),(470,1,'LOGOUT','User',1,'::1',NULL,'2026-07-03 18:58:20'),(471,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-03 18:58:40'),(472,2,'LOGOUT','User',2,'::1',NULL,'2026-07-03 19:00:05'),(473,1,'LOGIN_SUCCESS','User',1,'::1','{\"email\":\"innox@innomis.tr\"}','2026-07-03 19:00:08'),(474,1,'TOGGLE_SITE_TEAM_VISIBILITY','User',4,'::1','{\"department_id\":1,\"is_visible\":1}','2026-07-03 19:00:20'),(475,1,'SAVE_SITE_TEAM_PROFILE','User',2,'::1','{\"department_id\":6,\"is_visible\":1}','2026-07-03 19:51:20'),(476,1,'SAVE_SITE_TEAM_PROFILE','User',4,'::1','{\"department_id\":1,\"is_visible\":1}','2026-07-03 20:07:24'),(477,1,'SAVE_SITE_TEAM_PROFILE','User',2,'::1','{\"department_id\":6,\"is_visible\":1}','2026-07-03 20:11:03'),(478,1,'SAVE_SITE_TEAM_PROFILE','User',4,'::1','{\"department_id\":1,\"is_visible\":1}','2026-07-03 20:11:23'),(479,1,'SAVE_SITE_TEAM_PROFILE','User',4,'::1','{\"department_id\":1,\"is_visible\":1}','2026-07-03 20:17:30'),(480,1,'LOGOUT','User',1,'::1',NULL,'2026-07-03 20:38:52'),(481,NULL,'LOGIN_FAILED','User',NULL,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-03 20:39:11'),(482,2,'LOGIN_SUCCESS','User',2,'::1','{\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-03 20:39:22'),(483,2,'UPDATE_PROFILE','User',2,'::1','{\"name\":\"Yavuz Selim Yiâ”€ÅŸit\",\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-03 20:39:45'),(484,2,'UPDATE_PROFILE','User',2,'::1','{\"name\":\"Yavuz Selim Yiâ”€ÅŸit\",\"email\":\"mail@yavuzselimyigit.com\"}','2026-07-03 20:46:23'),(485,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 21:06:36'),(486,2,'APP_INTERACTION','App',29,'::1','{\"department_id\":6,\"app_slug\":\"meeting\"}','2026-07-03 21:08:43'),(487,2,'APP_INTERACTION','App',22,'::1','{\"department_id\":6,\"app_slug\":\"agile\"}','2026-07-03 21:09:44'),(488,2,'APP_INTERACTION','App',22,'::1','{\"department_id\":6,\"app_slug\":\"agile\"}','2026-07-03 21:09:50'),(489,2,'APP_INTERACTION','App',48,'::1','{\"department_id\":6,\"app_slug\":\"task_manager\"}','2026-07-03 21:10:00');
/*!40000 ALTER TABLE `system_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tracker_items`
--

DROP TABLE IF EXISTS `tracker_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tracker_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  CONSTRAINT `tracker_items_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `apps` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tracker_items`
--

LOCK TABLES `tracker_items` WRITE;
/*!40000 ALTER TABLE `tracker_items` DISABLE KEYS */;
INSERT INTO `tracker_items` VALUES (1,22,6,2,'InnoMIS v2 Paneli','done',NULL,'2026-07-01 13:48:59','2026-07-01 13:48:59');
/*!40000 ALTER TABLE `tracker_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_departments`
--

DROP TABLE IF EXISTS `user_departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `role` enum('director','member','representative','president','vice_president') DEFAULT 'member',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_dept` (`user_id`,`department_id`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `user_departments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_departments_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_departments`
--

LOCK TABLES `user_departments` WRITE;
/*!40000 ALTER TABLE `user_departments` DISABLE KEYS */;
INSERT INTO `user_departments` VALUES (1,2,6,'director','2026-07-01 11:42:30'),(7,3,2,'member','2026-07-01 17:29:08'),(8,3,6,'member','2026-07-01 17:31:23'),(10,2,3,'member','2026-07-01 18:49:12'),(14,4,1,'president','2026-07-03 18:15:05');
/*!40000 ALTER TABLE `user_departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_super_admin` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `phone` varchar(50) DEFAULT NULL,
  `university` varchar(150) DEFAULT NULL,
  `faculty` varchar(150) DEFAULT NULL,
  `school_department` varchar(150) DEFAULT NULL,
  `grade` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `remember_token` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `theme_color` varchar(50) DEFAULT NULL,
  `can_manage_passwords` tinyint(1) DEFAULT 0,
  `is_representative` tinyint(1) DEFAULT 0,
  `is_alumni` tinyint(1) DEFAULT 0,
  `linkedin` varchar(255) DEFAULT NULL,
  `github` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `university_logo` varchar(255) DEFAULT NULL,
  `company_logo` varchar(255) DEFAULT NULL,
  `community_history_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`community_history_json`)),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Innox Admin','innox@innomis.tr','$2y$10$Tu0//Ewobk.RnuWVigz76e2X0ma8ay1IHCZMc6EHH.V.IMWcSKjyi',1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-01 11:06:38','2026-07-01 11:06:38',0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,'Yavuz Selim Yiâ”€ÅŸit','mail@yavuzselimyigit.com','$2y$10$v94tqPsP.lmH.X7VjR5MXuxrapKoEl.iQ2itDxZTFYugUaGGCH2V6',0,1,'','Necmettin Erbakan â”œÂ£niversitesi','Mâ”œâ•hendislik Fakâ”œâ•ltesi','Elektrik Elektronik Mâ”œâ•hendisliâ”€ÅŸi','3. Sâ”€â–’nâ”€â–’f','uploads/avatars/avatar_2_1783111583.webp','2026-07-01',NULL,'2026-07-01 11:42:30','2026-07-03 20:46:23',0,0,0,'','','','','/assets/uni/neuLogo.png','','[]'),(3,'Arda â”œÃ»zer','arda@innomis.tr','$2y$10$bCPHuugJhEey1E/N445I4e8N4OUb3mnjz0InJSByy57o.Ewe0FkPa',0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-01 17:29:08','2026-07-01 17:29:08',0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,'Rukiye Erdem','rukiyeerdem@innomis.tr','$2y$10$6a25ZdylIFPWJQ1JM0t2SedWzEIkoeamwc.WVgBgjZ0ccpoDk5aN6',0,1,NULL,'Necmettin Erbakan â”œÂ£niversitesi','Uygulamalâ”€â–’ Bilimler Fakâ”œâ•ltesi','Yâ”œÃ‚netim Biliâ”¼ÅŸim Sistemleri','3. Sâ”€â–’nâ”€â–’f',NULL,NULL,NULL,'2026-07-03 16:17:49','2026-07-03 20:20:52',0,0,0,NULL,NULL,NULL,NULL,'/assets/uni/neuLogo.png',NULL,NULL);
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

-- Dump completed on 2026-07-04  0:30:39
