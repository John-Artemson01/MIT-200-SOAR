/*
SQLyog Community v12.2.2 (64 bit)
MySQL - 10.4.32-MariaDB : Database - medical_records
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`medical_records` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `medical_records`;

/*Table structure for table `educational_info` */

DROP TABLE IF EXISTS `educational_info`;

CREATE TABLE `educational_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `academic_level` varchar(50) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `year_level` varchar(10) DEFAULT NULL,
  `section` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `educational_info` */

insert  into `educational_info`(`id`,`student_id`,`academic_level`,`course`,`year_level`,`section`) values 
(1,1,'College','BSCS','4','BSCS 4A'),
(2,2,'COLLEGE','BSIT','4','BSIT 4A'),
(3,5,'COLLEGE','BS ENTREP','3','BSENTREP 3A');

/*Table structure for table `staff` */

DROP TABLE IF EXISTS `staff`;

CREATE TABLE `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `designation` varchar(50) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `staff` */

insert  into `staff`(`id`,`name`,`designation`,`contact_number`) values 
(1,'Dr. John Doe','Doctor','09123456789'),
(2,'Nurse Jane Smith','Nurse','09234567890'),
(3,'Mr. Samuel Carter',' Medical Technician.','09987654321');

/*Table structure for table `students` */

DROP TABLE IF EXISTS `students`;

CREATE TABLE `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `contacts` varchar(15) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `student_number` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_number` (`student_number`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `students` */

insert  into `students`(`id`,`name`,`contacts`,`age`,`student_number`) values 
(1,'John Artemson De Guzman ','09123456789',24,'1'),
(2,'DARYLL ENRIQUEZ','09123456789',25,'2'),
(5,'JAY MARVIN JOSEPH DE GUZMAN','09123456789',22,'3');

/*Table structure for table `triage` */

DROP TABLE IF EXISTS `triage`;

CREATE TABLE `triage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `medical_history` text DEFAULT NULL,
  `status` enum('pending','accepted','archived') DEFAULT 'pending',
  `assigned_staff` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `triage` */

insert  into `triage`(`id`,`student_id`,`medical_history`,`status`,`assigned_staff`) values 
(1,1,'HEALTHY','pending',1),
(2,2,'HEALTHY','accepted',2),
(3,5,'HEALTHY','archived',3);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
