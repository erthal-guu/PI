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
-- Table structure for table `consultas`
--

DROP TABLE IF EXISTS `consultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consultas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `sintomas` text NOT NULL,
  `duracao_sintomas` varchar(50) NOT NULL,
  `intensidade` int NOT NULL,
  `resposta_ia` text,
  `data_consulta` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `consultas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultas`
--

LOCK TABLES `consultas` WRITE;
/*!40000 ALTER TABLE `consultas` DISABLE KEYS */;
INSERT INTO `consultas` VALUES (1,1,'dor de cabeça','4-7-dias',8,'Olá! Entendo que você está passando por um momento desconfortável com esses sintomas. Vamos avaliar juntos e encontrar o melhor caminho para você se sentir melhor.\n\n**1. Resumo dos sintomas relatados:**\nVocê relatou uma dor de cabeça com duração de 4 a 7 dias e intensidade de 8/10. Isso indica um desconforto significativo que pode estar afetando sua rotina diária.\n\n**2. Possíveis causas:**\nExistem várias possíveis causas para dor de cabeça, mas aqui estão três das mais prováveis:\n- **Tensão muscular:** Estresse, ansiedade ou má postura podem levar a tensão muscular na cabeça e no pescoço, resultando em dor de cabeça.\n- **Migraña:** Uma condição crônica que causa dores de cabeça intensas, geralmente acompanhadas de sensibilidade à luz e som, e sometimes enjoo.\n- **Sinusite:** Infecção ou inflamação dos seios paranasais pode causar dor de cabeça, especialmente na região da testa e dos olhos.\n\n**3. Recomendações iniciais de cuidados:**\nEnquanto aguarda uma consulta médica, aqui estão algumas recomendações para aliviar os sintomas:\n- Mantenha-se hidratado beber bastante água.\n- Evite estimulos como luz forte e barulho.\n- Tente técnicas de relaxamento, como meditação ou respiração profunda, para reduzir o estresse.\n- Se possível, faça pausas regulares para alongar e mover o corpo.\n\n**4. Sinais de alerta que exigem atendimento médico urgente:**\nSe você notar qualquer um dos seguintes sintomas, procure atendimento médico imediatamente:\n- Dor de cabeça súbita e intensa, conhecida como \"a pior dor de cabeça da vida\".\n- Confusão, dificuldade para falar ou andar.\n- Febre alta acompanhada de dor de cabeça.\n- Perda de visão ou visão dupla.\n\n**5. Sugestão de especialista médico apropriado:**\nPara uma avaliação mais precisa e tratamento adequado, recomendo consultar um **Neurologista**. Eles estão mais preparados para diagnosticar e tratar condições relacionadas à dor de cabeça, incluindo migraña, tensão muscular e outras possíveis causas.\n\nLembre-se, essas são apenas orientações iniciais. É crucial consultar um médico para uma avaliação completa e um plano de tratamento personalizado. Se você estiver se sentindo mal ou se os sintomas piorarem, não hesite em procurar atendimento médico. Você não está sozinho, e há ajuda disponível. Cuide-se e mantenha-se positivo!','2025-10-15 17:16:41');
/*!40000 ALTER TABLE `consultas` ENABLE KEYS */;
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
