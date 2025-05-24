DROP TABLE IF EXISTS `alumnos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alumnos` (
  `alumno_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`alumno_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumnos`
--

LOCK TABLES `alumnos` WRITE;
/*!40000 ALTER TABLE `alumnos` DISABLE KEYS */;
INSERT INTO `alumnos` (`alumno_id`, `nombre`, `email`, `password`) VALUES (13,'DANIEL ALEJANDRO AREVALO ROJAS','alejo.arevalorojas@gmail.com','alejandrorojas'),(12,'pedro peres','info@fiven.org','12345'),(11,'HARRY ROSALES PARRA','info@tdb.com.co','12345'),(10,'HARRY WALT ROSALES','admisiones@tecnologicodebogota.com','12345'),(8,'HARRY ROSALES','info@tecnologicodebogota.com','CIRUXCIRUX30');
/*!40000 ALTER TABLE `alumnos` ENABLE KEYS */;
UNLOCK TABLES;


