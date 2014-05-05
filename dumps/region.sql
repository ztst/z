-- MySQL dump 10.13  Distrib 5.6.14, for Linux (x86_64)
--
-- Host: localhost    Database: znaika_dima
-- ------------------------------------------------------
-- Server version	5.6.14-62.0

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
-- Table structure for table `region`
--

DROP TABLE IF EXISTS `region`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `region` (
  `region_id` int(11) NOT NULL AUTO_INCREMENT,
  `region_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`region_id`),
  UNIQUE KEY `UNIQ_F62F17687856931` (`region_name`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `region`
--

LOCK TABLES `region` WRITE;
/*!40000 ALTER TABLE `region` DISABLE KEYS */;
INSERT INTO `region` VALUES (1,'Адыгея'),(2,'Алтай'),(23,'Алтайский край'),(32,'Амурская область'),(33,'Архангельская область'),(34,'Астраханская область'),(3,'Башкортостан'),(35,'Белгородская область'),(36,'Брянская область'),(4,'Бурятия'),(37,'Владимирская область'),(38,'Волгоградская область'),(39,'Вологодская область'),(40,'Воронежская область'),(5,'Дагестан'),(81,'Еврейская АО'),(24,'Забайкальский край'),(41,'Ивановская область'),(6,'Ингушетия'),(42,'Иркутская область'),(7,'Кабардино-Балкария'),(43,'Калининградская область'),(8,'Калмыкия'),(44,'Калужская область'),(25,'Камчатский край'),(9,'Карачаево-Черкесия'),(10,'Карелия'),(45,'Кемеровская область'),(46,'Кировская область'),(11,'Коми'),(47,'Костромская область'),(26,'Краснодарский край'),(27,'Красноярский край'),(12,'Крым'),(48,'Курганская область'),(49,'Курская область'),(50,'Ленинградская область'),(51,'Липецкая область'),(52,'Магаданская область'),(13,'Марий Эл'),(14,'Мордовия'),(78,'Москва'),(53,'Московская область'),(54,'Мурманская область'),(82,'Ненецкий АО'),(55,'Нижегородская область'),(56,'Новгородская область'),(57,'Новосибирская область'),(58,'Омская область'),(59,'Оренбургская область'),(60,'Орловская область'),(61,'Пензенская область'),(28,'Пермский край'),(29,'Приморский край'),(62,'Псковская область'),(63,'Ростовская область'),(64,'Рязанская область'),(65,'Самарская область'),(79,'Санкт-Петербург'),(66,'Саратовская область'),(15,'Саха (Якутия)'),(67,'Сахалинская область'),(68,'Свердловская область'),(80,'Севастополь'),(16,'Северная Осетия — Алания'),(69,'Смоленская область'),(30,'Ставропольский край'),(70,'Тамбовская область'),(17,'Татарстан'),(71,'Тверская область'),(72,'Томская область'),(73,'Тульская область'),(18,'Тыва (Тува)'),(74,'Тюменская область'),(19,'Удмуртия'),(75,'Ульяновская область'),(31,'Хабаровский край'),(20,'Хакасия'),(83,'Ханты-Мансийский АО — Югра'),(76,'Челябинская область'),(21,'Чечня'),(22,'Чувашия'),(84,'Чукотский АО'),(85,'Ямало-Ненецкий АО'),(77,'Ярославская область');
/*!40000 ALTER TABLE `region` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-04-18 12:37:27
