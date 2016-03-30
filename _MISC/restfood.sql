-- MySQL dump 10.13  Distrib 5.5.42, for osx10.6 (i386)
--
-- Host: localhost    Database: restfood
-- ------------------------------------------------------
-- Server version	5.5.42

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
-- Table structure for table `allergens`
--

DROP TABLE IF EXISTS `allergens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `allergens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_67F79FB4D17F50A6` (`uuid`),
  UNIQUE KEY `UNIQ_67F79FB45E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `allergens`
--

LOCK TABLES `allergens` WRITE;
/*!40000 ALTER TABLE `allergens` DISABLE KEYS */;
INSERT INTO `allergens` VALUES (2,'bfb36960-df46-480a-8ddc-59f012dfd9e8','lactosa'),(3,'bc110eaf-78a3-4219-9b56-29e0042b9ffe','fructosa'),(4,'466f0583-d669-44bf-b327-b6814b561701','huevo'),(5,'a6624a84-6296-428e-8c1c-ace2a3cd2a3e','gluten'),(11,'842012dd-fdc2-4fa3-b9a6-f7b54f2ee943','apio'),(12,'1cfe90fd-21fa-4add-8639-baee290e8e5a','marisco');
/*!40000 ALTER TABLE `allergens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dishes`
--

DROP TABLE IF EXISTS `dishes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dishes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `uuid` char(36) COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_584DD35DD17F50A6` (`uuid`),
  UNIQUE KEY `UNIQ_584DD35D5E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dishes`
--

LOCK TABLES `dishes` WRITE;
/*!40000 ALTER TABLE `dishes` DISABLE KEYS */;
INSERT INTO `dishes` VALUES (1,'espaguetis a la carbonara','dbc67769-78f0-4ebf-a090-9ef5fda82173'),(2,'sopa de marisco','796da024-fd14-4837-b4d5-94ac591687de'),(3,'Secreto ibérico al rulo de cabra','c2321c0d-0f89-4ab6-8c9f-fc55b9b994c3'),(4,'Carrilleras de cerdo en salsa','85bebea1-ee0e-47e2-ab67-124bba0e978e'),(5,'Canelones de verduras','56f5eebe-f8cd-4812-9f27-54d40c148008'),(6,'ensalada de temporada','1d6a3a30-fadb-466e-aa22-6f109edc836f'),(7,'minestrone','11760675-dfd9-44dc-84f5-1c797a4ea242'),(8,'escalopín de ternera','5c201c1a-20ce-4886-b3e8-2e8a8d5afab7');
/*!40000 ALTER TABLE `dishes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dishes_ingredients`
--

DROP TABLE IF EXISTS `dishes_ingredients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dishes_ingredients` (
  `dish_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  PRIMARY KEY (`dish_id`,`ingredient_id`),
  KEY `IDX_837A1997148EB0CB` (`dish_id`),
  KEY `IDX_837A1997933FE08C` (`ingredient_id`),
  CONSTRAINT `FK_837A1997933FE08C` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`),
  CONSTRAINT `FK_837A1997148EB0CB` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dishes_ingredients`
--

LOCK TABLES `dishes_ingredients` WRITE;
/*!40000 ALTER TABLE `dishes_ingredients` DISABLE KEYS */;
INSERT INTO `dishes_ingredients` VALUES (1,5),(1,13),(2,12),(2,21),(2,22),(2,26),(2,27),(2,30),(3,1),(3,9),(3,31),(4,9),(4,20),(4,21),(4,22),(4,32),(5,1),(5,14),(5,15),(5,20),(5,21),(5,22),(5,33),(6,1),(6,7),(6,8),(6,11),(6,19),(6,20),(6,21),(6,22),(6,23),(6,26),(7,1),(7,7),(7,8),(7,11),(7,19),(7,20),(7,21),(7,22),(7,23),(7,26),(8,11),(8,24),(8,25);
/*!40000 ALTER TABLE `dishes_ingredients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingredients`
--

DROP TABLE IF EXISTS `ingredients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingredients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `uuid` char(36) COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4B60114FD17F50A6` (`uuid`),
  UNIQUE KEY `UNIQ_4B60114F5E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredients`
--

LOCK TABLES `ingredients` WRITE;
/*!40000 ALTER TABLE `ingredients` DISABLE KEYS */;
INSERT INTO `ingredients` VALUES (1,'queso','b92df309-bf2e-4910-9f2e-85d9e20f081e'),(2,'leche','8f3e314a-a699-43d2-91f6-284262942642'),(4,'chocolate','d49053eb-e009-4929-90ce-7228ee03dd2e'),(5,'nata','dbb37aff-8005-4804-b7fe-6e7ef0c38ae1'),(6,'mantequilla','3da928bc-5faf-49d6-8be3-ba4a96c709f3'),(7,'fresas','945758ca-4cec-429d-ab2b-3edf1f9166d9'),(8,'manzana','2f881495-4453-43ef-8983-80a97d3213f9'),(9,'harina de trigo','27258381-d880-4818-ac24-48368a28960f'),(10,'levadura de trigo','aed2436e-dd08-48fa-a3e2-d10bafbdbd51'),(11,'huevo','9a9db338-8ea0-4b16-9b65-af0e38b9f089'),(12,'fideos','38b40a64-982e-438f-be35-3ca3258cbeb7'),(13,'espaguettis','66bd6adc-28eb-4ebb-a17e-949b832b0c62'),(14,'puerro','1c77d2f2-eec9-4efa-8260-ca3e329df6a8'),(15,'apio','5b9def05-341c-44bb-ae25-9201a60b7516'),(16,'bacon','c11571b4-33c7-4d64-ad23-b838b0a7e352'),(17,'picadillo de cerdo','25da7d43-d3d8-4af6-89fe-39b35cbc126e'),(18,'lentejas','161bc03b-18a9-41c4-acab-74b9178ed626'),(19,'chorizo','8ad21e6f-c1c8-45fe-bacc-2fbffd920ca2'),(20,'zanahoria','c0088a0d-28d2-4b3f-898b-805ebb327017'),(21,'cebolla','1eb936e2-2544-4405-a9e5-556c9b3830b5'),(22,'ajo','774b3528-5ed2-43c8-988d-9769d7cf3fdd'),(23,'remolacha','542c8145-9ac7-495f-8f72-0a26b23481f1'),(24,'pan rallado','234cb318-0def-4661-ae6b-76ae89f6f61a'),(25,'filete de ternera','47df2f5f-74a9-4510-a770-905c6c5e61a6'),(26,'gambas','737d302b-042a-44f6-ac55-1df07a9ef53f'),(27,'almejas','7dd0afab-753e-4542-accc-1fdbd1d20c8f'),(28,'ostras','dae386b8-3a05-4e31-8e57-fa0c8b3a0541'),(29,'langostinos','2f880bab-9cc8-4fe4-9789-119e7189c239'),(30,'salsa de pescado','2c31bc8e-4294-440d-9cb5-8780facc963a'),(31,'secreto de cerdo ibérico','b9a914fb-2f2c-4819-b0eb-1fb26e3f2be2'),(32,'carrilleras de cerdo','2c1e7138-ed4d-43ee-bdf0-7ed3ab133674'),(33,'placas de canelones','d4da1313-ba4d-460a-9fab-a30e8bd8eaf6');
/*!40000 ALTER TABLE `ingredients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingredients_allergens`
--

DROP TABLE IF EXISTS `ingredients_allergens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingredients_allergens` (
  `ingredient_id` int(11) NOT NULL,
  `allergen_id` int(11) NOT NULL,
  PRIMARY KEY (`ingredient_id`,`allergen_id`),
  KEY `IDX_6886B58E933FE08C` (`ingredient_id`),
  KEY `IDX_6886B58E6E775A4A` (`allergen_id`),
  CONSTRAINT `FK_6886B58E6E775A4A` FOREIGN KEY (`allergen_id`) REFERENCES `allergens` (`id`),
  CONSTRAINT `FK_6886B58E933FE08C` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredients_allergens`
--

LOCK TABLES `ingredients_allergens` WRITE;
/*!40000 ALTER TABLE `ingredients_allergens` DISABLE KEYS */;
INSERT INTO `ingredients_allergens` VALUES (1,2),(2,2),(4,2),(4,5),(5,2),(6,2),(7,3),(8,3),(9,5),(10,5),(11,4),(12,4),(12,5),(13,4),(13,5),(14,3),(15,3),(15,11),(18,3),(19,3),(20,3),(21,3),(22,3),(23,3),(24,5),(26,12),(27,12),(28,12),(29,12),(30,12),(33,4),(33,5);
/*!40000 ALTER TABLE `ingredients_allergens` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-30 19:57:50
