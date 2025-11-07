-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: selfmed_db
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `sobrenome` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(100) NOT NULL,
  `data_nascimento` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Busta','Erthal','$2y$10$lneVO0j1CD5V3WJCVx0meuc1z6bQz3sqVwS6ImQLo3/PTSWGo7WUW','2025-10-03 17:16:10','vplgugs@gmail.com','2008-02-15'),(2,'ku','zinho','$2y$10$DN3GO9aSP.H5g8SsCwoIaOv6BGEKlMKndns7VQB7HIRS0ko2gBH0O','2025-10-03 17:59:20','ku@gmail.com','2006-12-16'),(3,'Kauan','Teixeira','$2y$10$MK5289hCulJ48QIhQCrREOgMLaz4iao.f0uTbKYllwaycbcVM54pm','2025-10-03 20:34:14','Kauan@gmail.com','2006-08-07'),(4,'João ','Pedro','$2y$10$GYfyqQdBUX6LZvEQpj8Wse.CdGdbrzcG5PZ6Dbm1mIUhkXBGaJhCe','2025-10-10 19:48:05','joao@gmail.com','2008-12-13'),(5,'agdAGDYIGYI','GDYUIGADYIGi','$2y$10$NOo2rIbmK/tmjdw.e8YM5ePTWUrd.FObWpWRsveDpFKiU1wNose5a','2025-10-15 19:11:53','agdyga@gmail.com','2008-05-11'),(6,'Edna Professora','Taborda','$2y$10$NvGa/goHK/PeiqhB8sK0bu01b4ftH1G967MlCcc01x1yCgBfm4RpO','2025-11-07 19:26:38','edna@gmail.com','1991-02-05');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-07 16:30:11
