/*
SQLyog Enterprise - MySQL GUI v7.02 
MySQL - 5.5.24-log : Database - enote
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`enote` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `enote`;

/*Table structure for table `adjunto` */

DROP TABLE IF EXISTS `adjunto`;

CREATE TABLE `adjunto` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `googleDriveId` varchar(250) DEFAULT NULL,
  `fechaCreacion` datetime NOT NULL,
  `notaId` int(11) unsigned NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `linkDescarga` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `adjuntoGDIdUnique` (`googleDriveId`),
  KEY `FK_nota_adjunto` (`notaId`),
  CONSTRAINT `FK_nota_adjunto` FOREIGN KEY (`notaId`) REFERENCES `nota` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Data for the table `adjunto` */

/*Table structure for table `agenda` */

DROP TABLE IF EXISTS `agenda`;

CREATE TABLE `agenda` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  `fechaCreacion` datetime NOT NULL,
  `fechaEdicion` datetime NOT NULL,
  `usuarioId` int(11) unsigned NOT NULL,
  `googleDriveId` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agendaGDIdUnique` (`googleDriveId`),
  KEY `FK_agenda` (`usuarioId`),
  CONSTRAINT `FK_agenda` FOREIGN KEY (`usuarioId`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=507 DEFAULT CHARSET=latin1;

/*Data for the table `agenda` */

insert  into `agenda`(`id`,`nombre`,`fechaCreacion`,`fechaEdicion`,`usuarioId`,`googleDriveId`) values (464,'Agenda_1','2012-12-08 19:32:21','2012-12-08 19:32:22',1,'0B4SDkBteLMNhVHNGVm9hUFJXWVE'),(465,'Agenda_2','2012-12-08 19:32:22','2012-12-08 19:32:23',1,'0B4SDkBteLMNhVTBBTUxNTE5jWWc'),(466,'Agenda_3','2012-12-08 19:32:23','2012-12-08 19:32:24',1,'0B4SDkBteLMNhT2tmaTRmbjliZzg'),(467,'Agenda_4','2012-12-08 19:32:24','2012-12-08 19:32:25',1,'0B4SDkBteLMNhNW8zQW95am9ITVE'),(468,'Agenda_5','2012-12-08 19:32:25','2012-12-08 19:32:27',1,'0B4SDkBteLMNhNlF5NFVlUkhOV28'),(469,'Agenda_6','2012-12-08 19:32:27','2012-12-08 19:32:28',1,'0B4SDkBteLMNhYmlhV0RUUG1sT0k'),(470,'Agenda_7','2012-12-08 19:32:28','2012-12-08 19:32:29',1,'0B4SDkBteLMNhc1VEMzhjdFg5RU0'),(471,'Agenda_8','2012-12-08 19:32:29','2012-12-08 19:32:30',1,'0B4SDkBteLMNhZ2FlZmdCdkhmSms'),(472,'Agenda_9','2012-12-08 19:32:30','2012-12-08 19:32:32',1,'0B4SDkBteLMNhUEk0RmFNZ3ZtZnc'),(473,'Agenda_10','2012-12-08 19:32:32','2012-12-08 19:32:33',1,'0B4SDkBteLMNhb21yLTE4MjRBbnc'),(474,'Agenda_11','2012-12-08 19:32:33','2012-12-08 19:32:34',1,'0B4SDkBteLMNhRGthZ3lUcVFCaWs'),(475,'Agenda_12','2012-12-08 19:32:34','2012-12-08 19:32:35',1,'0B4SDkBteLMNhcjd3SURwQXBzeTQ'),(476,'Agenda_13','2012-12-08 19:32:35','2012-12-08 19:32:37',1,'0B4SDkBteLMNheHVZTS1PcHFualE'),(477,'Agenda_14','2012-12-08 19:32:37','2012-12-08 19:32:38',1,'0B4SDkBteLMNhRGxwSWYteW45eUU'),(478,'Agenda_15','2012-12-08 19:32:38','2012-12-08 19:32:39',1,'0B4SDkBteLMNhb0o4d1lrSGxNOHc'),(479,'Agenda_16','2012-12-08 19:32:39','2012-12-08 19:32:40',1,'0B4SDkBteLMNhYW5UWlVEV0pMZnM'),(480,'Agenda_17','2012-12-08 19:32:40','2012-12-08 19:32:41',1,'0B4SDkBteLMNhbWhCTlpjUmwxLWs'),(481,'Agenda_18','2012-12-08 19:32:41','2012-12-08 19:32:43',1,'0B4SDkBteLMNhX2E0TXlNcERuTmM'),(482,'Agenda_19','2012-12-08 19:32:43','2012-12-08 19:32:44',1,'0B4SDkBteLMNhSE9VYUVpS09RYTQ'),(483,'Agenda_20','2012-12-08 19:32:44','2012-12-08 19:32:46',1,'0B4SDkBteLMNhM1hSZDk1VGdlazg'),(484,'Agenda_21','2012-12-08 19:32:46','2012-12-08 19:32:47',1,'0B4SDkBteLMNhalJFclo0SGNhSEk'),(485,'Agenda_22','2012-12-08 19:32:47','2012-12-08 19:32:48',1,'0B4SDkBteLMNhUV8xQjhOZUdpc00'),(486,'Agenda_23','2012-12-08 19:32:48','2012-12-08 19:32:49',1,'0B4SDkBteLMNhUlRVN1RVVVU2Mk0'),(487,'Agenda_24','2012-12-08 19:32:49','2012-12-08 19:32:50',1,'0B4SDkBteLMNha2tuV1FuM3NKNDA'),(490,'Agenda_26','2012-12-08 19:35:20','2012-12-08 19:35:20',1,'0B4SDkBteLMNhN3VuaUhBNzNHcjQ'),(492,'Agenda_27','2012-12-08 19:35:23','2012-12-08 19:35:23',1,'0B4SDkBteLMNhcTZ4UVBmQmc3bjQ'),(494,'Agenda_26','2012-12-08 19:36:56','2012-12-08 19:36:58',1,'0B4SDkBteLMNhdHlVUUQ3TUxFbTg'),(495,'Agenda_27','2012-12-08 19:36:58','2012-12-08 19:37:01',1,'0B4SDkBteLMNhY1NxcGRaMHJ6dHc'),(496,'Agenda_28','2012-12-08 19:37:01','2012-12-08 19:37:02',1,'0B4SDkBteLMNhM1Z5OGNrM0JfUEU'),(497,'Agenda_29','2012-12-08 19:37:02','2012-12-08 19:37:04',1,'0B4SDkBteLMNhQXZNVTFRU2FnWFE'),(498,'Agenda_30','2012-12-08 19:37:04','2012-12-08 19:37:06',1,'0B4SDkBteLMNhaVZDX2NTQnRLWXc'),(499,'Agenda_31','2012-12-08 19:37:06','2012-12-08 19:37:09',1,'0B4SDkBteLMNhbGoyVDRsS0hCX2c'),(500,'Agenda_32','2012-12-08 19:37:09','2012-12-08 19:37:10',1,'0B4SDkBteLMNhS1JmdENhWGZneEk'),(501,'Agenda_33','2012-12-08 19:37:10','2012-12-08 19:37:12',1,'0B4SDkBteLMNhX0stendJYVNBLUE'),(502,'Agenda_34','2012-12-08 19:37:12','2012-12-08 19:37:17',1,'0B4SDkBteLMNhdXpaWjNYYTdqN3c'),(503,'Agenda_35','2012-12-08 19:37:17','2012-12-08 19:37:20',1,'0B4SDkBteLMNhZ0MzTko0SjZMREk'),(504,'Agenda_36','2012-12-08 19:37:20','2012-12-08 19:37:21',1,'0B4SDkBteLMNhTE1CMjJrcmFsMzA'),(505,'Agenda_37','2012-12-08 19:37:21','2012-12-08 19:37:23',1,'0B4SDkBteLMNhZFZXUkpKVTVvdlk'),(506,'Agenda_38','2012-12-08 19:37:23','2012-12-08 19:37:26',1,'0B4SDkBteLMNhalg2bzNnX19WOU0');

/*Table structure for table `etiqueta` */

DROP TABLE IF EXISTS `etiqueta`;

CREATE TABLE `etiqueta` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(75) NOT NULL,
  `fechaCreacion` datetime NOT NULL,
  `fechaEdicion` datetime NOT NULL,
  `usuarioId` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_usuario_etiqueta` (`usuarioId`),
  CONSTRAINT `FK_etiqueta` FOREIGN KEY (`usuarioId`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `etiqueta` */

/*Table structure for table `nota` */

DROP TABLE IF EXISTS `nota`;

CREATE TABLE `nota` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `texto` text NOT NULL,
  `fechaCreacion` datetime NOT NULL,
  `fechaEdicion` datetime NOT NULL,
  `agendaId` int(11) unsigned NOT NULL,
  `googleDriveId` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `notaGoogleDriveIdUnique` (`googleDriveId`),
  KEY `FK_usuario_nota` (`agendaId`),
  CONSTRAINT `FK_agenda_nota` FOREIGN KEY (`agendaId`) REFERENCES `agenda` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=latin1;

/*Data for the table `nota` */

insert  into `nota`(`id`,`titulo`,`texto`,`fechaCreacion`,`fechaEdicion`,`agendaId`,`googleDriveId`) values (10,'Nota_1','texto1','2012-12-08 19:41:06','2012-12-08 19:41:08',464,'0B4SDkBteLMNhd05nZEZQLTh3Y28'),(11,'Nota_2','texto2','2012-12-08 19:41:08','2012-12-08 19:41:10',464,'0B4SDkBteLMNhY2JEZlZhTVNCNW8'),(12,'Nota_3','texto3','2012-12-08 19:41:10','2012-12-08 19:41:13',464,'0B4SDkBteLMNhYXZTeDJlNXUtQWs'),(13,'Nota_4','texto4','2012-12-08 19:41:13','2012-12-08 19:41:14',464,'0B4SDkBteLMNhZXFYSk9YUHpuNm8'),(14,'Nota_5','texto5','2012-12-08 19:41:14','2012-12-08 19:41:16',464,'0B4SDkBteLMNhWnhjYWVPa0o1Z0k'),(15,'Nota_6','texto6','2012-12-08 19:41:16','2012-12-08 19:41:17',464,'0B4SDkBteLMNhaHJMczhrRjl5NDg'),(16,'Nota_7','texto7','2012-12-08 19:41:17','2012-12-08 19:41:19',464,'0B4SDkBteLMNhaE1mVE9uMFZHNXc'),(17,'Nota_8','texto8','2012-12-08 19:41:19','2012-12-08 19:41:20',464,'0B4SDkBteLMNhRk9fMTd1aHdyY2M'),(18,'Nota_9','texto9','2012-12-08 19:41:20','2012-12-08 19:41:21',464,'0B4SDkBteLMNhVXQ2YTVtT0ItQkE'),(19,'Nota_10','texto10','2012-12-08 19:41:21','2012-12-08 19:41:24',464,'0B4SDkBteLMNhTF83Uy1CSjlUTnc'),(20,'Nota_11','texto11','2012-12-08 19:41:24','2012-12-08 19:41:26',464,'0B4SDkBteLMNhNFZuT1hKV0R0U00'),(21,'Nota_12','texto12','2012-12-08 19:41:26','2012-12-08 19:41:28',464,'0B4SDkBteLMNhazBST0xmdm1FeGc'),(22,'Nota_13','texto13','2012-12-08 19:41:28','2012-12-08 19:41:30',464,'0B4SDkBteLMNhVlR5eGJHaFp6cU0'),(23,'Nota_14','texto14','2012-12-08 19:41:30','2012-12-08 19:41:31',464,'0B4SDkBteLMNhN2QtRzNUQU1CRG8'),(24,'Nota_15','texto15','2012-12-08 19:41:31','2012-12-08 19:41:34',464,'0B4SDkBteLMNhMEFfNmZwRWl0ZXc'),(25,'Nota_16','texto16','2012-12-08 19:41:34','2012-12-08 19:41:35',464,'0B4SDkBteLMNhVjRJR1BhQlJXbDg'),(28,'Nota_1','texto1','2012-12-08 19:41:58','2012-12-08 19:41:58',464,'0B4SDkBteLMNhOXRnMGdpZ1dUb2M'),(30,'Nota_2','texto2','2012-12-08 19:41:59','2012-12-08 19:41:59',464,'0B4SDkBteLMNhUGNBTkFrYmYyams'),(32,'Nota_3','texto3','2012-12-08 19:42:01','2012-12-08 19:42:01',464,'0B4SDkBteLMNhU2R2LVBMN0JRZVk'),(34,'Nota_4','texto4','2012-12-08 19:42:03','2012-12-08 19:42:03',464,'0B4SDkBteLMNhWWk3T1ladXE4ekk'),(36,'Nota_5','texto5','2012-12-08 19:42:05','2012-12-08 19:42:05',464,'0B4SDkBteLMNhdHk0cFI1YWZrR0k'),(38,'Nota_6','texto6','2012-12-08 19:42:07','2012-12-08 19:42:07',464,'0B4SDkBteLMNhUTBiZ0w4TXFmNTA'),(40,'Nota_7','texto7','2012-12-08 19:42:09','2012-12-08 19:42:09',464,'0B4SDkBteLMNhT2RDejdMaHROOGs'),(42,'Nota_8','texto8','2012-12-08 19:42:10','2012-12-08 19:42:10',464,'0B4SDkBteLMNhUkhVcmZuUVVmaFU'),(44,'Nota_9','texto9','2012-12-08 19:42:12','2012-12-08 19:42:12',464,'0B4SDkBteLMNhdWpsZWs1bkljNG8'),(46,'Nota_10','texto10','2012-12-08 19:42:15','2012-12-08 19:42:15',464,'0B4SDkBteLMNhWlh0eGE4RTZEN1U'),(48,'Nota_11','texto11','2012-12-08 19:42:17','2012-12-08 19:42:17',464,'0B4SDkBteLMNhVkJ4UmlSM0p1X1E'),(50,'Nota_12','texto12','2012-12-08 19:42:18','2012-12-08 19:42:18',464,'0B4SDkBteLMNhc0Q3Q295UnE0NEU'),(52,'Nota_1','texto1','2012-12-08 19:42:55','2012-12-08 19:42:56',464,'0B4SDkBteLMNhVGp6ajNoUTFiakk'),(53,'Nota_2','texto2','2012-12-08 19:42:56','2012-12-08 19:42:58',464,'0B4SDkBteLMNhWVdEOU1TU1JHYXc'),(54,'Nota_3','texto3','2012-12-08 19:42:58','2012-12-08 19:43:00',464,'0B4SDkBteLMNhcGo2MXpaSUprVms'),(55,'Nota_4','texto4','2012-12-08 19:43:00','2012-12-08 19:43:01',464,'0B4SDkBteLMNhMkYxMlNxYmdJVTQ'),(56,'Nota_5','texto5','2012-12-08 19:43:01','2012-12-08 19:43:03',464,'0B4SDkBteLMNhTkd3bDB0VmRaOXc'),(57,'Nota_6','texto6','2012-12-08 19:43:03','2012-12-08 19:43:05',464,'0B4SDkBteLMNhelNRRXBJSVhNUlU'),(58,'Nota_7','texto7','2012-12-08 19:43:05','2012-12-08 19:43:07',464,'0B4SDkBteLMNhY3Z0NXBHaDg5cWM'),(59,'Nota_8','texto8','2012-12-08 19:43:07','2012-12-08 19:43:09',464,'0B4SDkBteLMNhd2tMcE05ZVUzMzQ'),(60,'Nota_9','texto9','2012-12-08 19:43:09','2012-12-08 19:43:11',464,'0B4SDkBteLMNhVVp3c29RV2h5dVE'),(61,'Nota_10','texto10','2012-12-08 19:43:11','2012-12-08 19:43:12',464,'0B4SDkBteLMNhbmhmRlpJZ1lFdTQ'),(62,'Nota_11','texto11','2012-12-08 19:43:12','2012-12-08 19:43:13',464,'0B4SDkBteLMNhSzhjLXMwUXZHNDA'),(63,'Nota_12','texto12','2012-12-08 19:43:13','2012-12-08 19:43:15',464,'0B4SDkBteLMNhdlZYOERmbko5Zk0'),(64,'Nota_13','texto13','2012-12-08 19:43:15','2012-12-08 19:43:17',464,'0B4SDkBteLMNhWDZfR2hlLXdYSHc'),(65,'Nota_14','texto14','2012-12-08 19:43:17','2012-12-08 19:43:18',464,'0B4SDkBteLMNhV2JEV3RPNXdLSHc'),(66,'Nota_15','texto15','2012-12-08 19:43:18','2012-12-08 19:43:20',464,'0B4SDkBteLMNhUV8tZXl0QVRXNDA'),(67,'Nota_16','texto16','2012-12-08 19:43:20','2012-12-08 19:43:21',464,'0B4SDkBteLMNhVnF0Y2ZzMk9hZFU'),(68,'Nota_17','texto17','2012-12-08 19:43:21','2012-12-08 19:43:23',464,'0B4SDkBteLMNhTDVrc1d2V1A0RDA'),(69,'Nota_18','texto18','2012-12-08 19:43:23','2012-12-08 19:43:24',464,'0B4SDkBteLMNhNGxwZG4ybHBJeTA'),(70,'Nota_19','texto19','2012-12-08 19:43:24','2012-12-08 19:43:24',464,'');

/*Table structure for table `nota_etiqueta` */

DROP TABLE IF EXISTS `nota_etiqueta`;

CREATE TABLE `nota_etiqueta` (
  `notaId` int(11) unsigned NOT NULL,
  `etiquetaId` int(11) unsigned NOT NULL,
  `fechaCreacion` datetime NOT NULL,
  PRIMARY KEY (`notaId`,`etiquetaId`),
  KEY `FK_etiqueta_nota_etiqueta` (`etiquetaId`),
  CONSTRAINT `FK_etiqueta_nota_etiqueta` FOREIGN KEY (`etiquetaId`) REFERENCES `etiqueta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_nota_nota_etiqueta` FOREIGN KEY (`notaId`) REFERENCES `nota` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `nota_etiqueta` */

/*Table structure for table `usuario` */

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varchar(250) NOT NULL,
  `clave` varchar(250) NOT NULL,
  `googleDriveId` varchar(250) DEFAULT NULL,
  `refresh_token` varchar(250) DEFAULT NULL,
  `access_token` varchar(250) DEFAULT NULL,
  `created` int(11) unsigned DEFAULT NULL,
  `expires_in` int(11) unsigned DEFAULT NULL,
  `fechaCreacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarioUnique` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=latin1;

/*Data for the table `usuario` */

insert  into `usuario`(`id`,`usuario`,`clave`,`googleDriveId`,`refresh_token`,`access_token`,`created`,`expires_in`,`fechaCreacion`) values (1,'test','202cb962ac59075b964b07152d234b70','101176667724903860376','1/VWzTQ2QNpoPZ5Zt6MNJaU-Q0dMFU6ZkpcDQuafhDDQM','ya29.AHES6ZSVKBOJZsYx0e0urXcxV2BGP_HW20bZ62BCTmLcv6I4jZqXB3w',1355024695,3600,'2012-12-08 23:14:56'),(72,'test2','202cb962ac59075b964b07152d234b70','101176667724903860376','1/RClEQJRYeDTbc4mlzbzRcF813HtYnQZviqzrNWblzgg','ya29.AHES6ZSzL3XVNCAb4y6Mij5a2Kl4n34he8KF06-gDKsyOw',1355010089,3600,'2012-12-08 19:11:30');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
