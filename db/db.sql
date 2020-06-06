-- MySQL dump 10.13  Distrib 5.7.30, for Linux (x86_64)
--
-- Host: localhost    Database: beejee
-- ------------------------------------------------------
-- Server version	5.7.30-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `description` text,
  `status` enum('new','done') NOT NULL DEFAULT 'new',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES (1,1,'I don\'t care what\'s in your hair\r\nI just wanna know what\'s on your mind\r\nI used to say I wanna die before I\'m old\r\nBut because of you, I might think twice','done','2020-06-05 11:38:12','2020-06-06 16:08:06'),(2,2,'She looks like the real thing\nShe tastes like the real thing\nMy fake plastic love','new','2020-06-05 11:39:54',NULL),(3,1,'The rain comes falling from the sky\nTo fill the stream that fills the sea\nAnd that\'s where life began for you and me\nSo the next time you see rain it ain\'t bad\nDon\'t complain, it rains for you','new','2020-06-05 13:07:09',NULL),(4,1,'Deliver your children to the good, good life\nGive\'em peace and shelter and a fork and a knife\nShine a light in the morning and a light at night\nAnd if a thing goes wrong, you\'d better make it right','new','2020-06-05 13:14:42',NULL),(5,1,'Well, I had me a woman\nShe was good and clean\nShe spent all day\nWith the washing machine\nBut when it come to lovin\'\nShe was never around\nShe was out getting dirty\nAll over town','new','2020-06-05 13:15:27',NULL),(6,2,'I look at you all, see the love there that\'s sleeping\nWhile my guitar gently weeps\nI look at the floor and I see it needs sweeping\nStill my guitar gently weeps','new','2020-06-05 13:20:12',NULL);
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `login` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'John','john@example.com',NULL,NULL,'2020-06-05 11:36:46',NULL),(2,'Martin','martin@example.com',NULL,NULL,'2020-06-05 11:37:27',NULL),(3,'test','test@example.com',NULL,NULL,'2020-06-06 11:15:42',NULL),(16,'Admin','admin@example.com','admin','$2y$10$kHd6sagDeb1gkl2NQ602DucCwCxYFRiFw0xhz9BxYoSbimoykBsvi','2020-06-06 14:42:09',NULL),(21,'test','test@test.com',NULL,NULL,'2020-06-06 18:07:17',NULL),(24,'<script>alert(‘test’);</script>','test2@example.com',NULL,NULL,'2020-06-06 18:16:03',NULL);
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

-- Dump completed on 2020-06-06 21:27:36
