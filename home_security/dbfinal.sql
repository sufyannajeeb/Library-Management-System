/*
SQLyog Community v13.1.6 (64 bit)
MySQL - 5.7.9 : Database - homesecurity
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`homesecurity` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `homesecurity`;

/*Table structure for table `alert` */

DROP TABLE IF EXISTS `alert`;

CREATE TABLE `alert` (
  `alert_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `image` varchar(3000) DEFAULT NULL,
  `date` varchar(100) DEFAULT NULL,
  `time` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`alert_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

/*Data for the table `alert` */

insert  into `alert`(`alert_id`,`user_id`,`image`,`date`,`time`) values 
(8,2,'static/frame_2_1710238874.jpg','2024-03-12','15:51:14'),
(7,2,'static/frame_2_1710237789.jpg','2024-03-12','15:33:10');

/*Table structure for table `complaint` */

DROP TABLE IF EXISTS `complaint`;

CREATE TABLE `complaint` (
  `complaint_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `complaint` varchar(300) DEFAULT NULL,
  `reply` varchar(300) DEFAULT NULL,
  `date` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`complaint_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `complaint` */

insert  into `complaint`(`complaint_id`,`user_id`,`complaint`,`reply`,`date`) values 
(1,1,'oh man damn','hiii','11-12-2001'),
(2,2,'Yfyfg','pending','2024-03-10 21:11:55.765264'),
(3,2,'ififir','pending','2024-03-12 11:05:15.255581');

/*Table structure for table `family` */

DROP TABLE IF EXISTS `family`;

CREATE TABLE `family` (
  `family_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `phone` varchar(200) DEFAULT NULL,
  `place` varchar(200) DEFAULT NULL,
  `district` varchar(200) DEFAULT NULL,
  `type` varchar(200) DEFAULT NULL,
  `photo` varchar(2500) DEFAULT NULL,
  PRIMARY KEY (`family_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `family` */

insert  into `family`(`family_id`,`user_id`,`name`,`email`,`phone`,`place`,`district`,`type`,`photo`) values 
(1,2,'Ttt','Tt@gmail.com','1234567890','Gfv','Ghg','Friend','static/images/64ae9d61-3c92-463c-9944-0f921c07d9581710085073478.png'),
(2,2,'djdjd','dbdhd@gmail.xom','8383838782','usiei','diid','Family','static/images/30715e3b-4bbf-498c-8fd1-c1e08e3ecc481710221704485.png'),
(3,2,'jini','ehher@gmail.com','8393849932','hdhdh','dhhdhd','friend','static/images/b7fb24d6-4e67-4210-a6c9-d8a48609c46d1710229564504.png'),
(4,2,'Ronald','rono@gmail.com','6373828218','thrissur','thrissur','Friend','static/images/01701f24-c8d3-4e04-ad25-32a4e76f1cb61710240851969.png');

/*Table structure for table `friendsorfamily` */

DROP TABLE IF EXISTS `friendsorfamily`;

CREATE TABLE `friendsorfamily` (
  `friendsorfamily_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) DEFAULT NULL,
  `phone` varchar(300) DEFAULT NULL,
  `email` varchar(300) DEFAULT NULL,
  `address` varchar(300) DEFAULT NULL,
  `place` varchar(300) DEFAULT NULL,
  `pin` varchar(300) DEFAULT NULL,
  `photo` varchar(5000) DEFAULT NULL,
  PRIMARY KEY (`friendsorfamily_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `friendsorfamily` */

/*Table structure for table `login` */

DROP TABLE IF EXISTS `login`;

CREATE TABLE `login` (
  `login_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(300) DEFAULT NULL,
  `password` varchar(300) DEFAULT NULL,
  `usertype` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`login_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `login` */

insert  into `login`(`login_id`,`username`,`password`,`usertype`) values 
(1,'admin','admin','admin'),
(2,'3','3','user'),
(3,'1','1','police'),
(4,'Deepu','Deepu','user');

/*Table structure for table `police` */

DROP TABLE IF EXISTS `police`;

CREATE TABLE `police` (
  `police_id` int(11) NOT NULL AUTO_INCREMENT,
  `login_id` int(11) DEFAULT NULL,
  `name` varchar(300) DEFAULT NULL,
  `phone` varchar(300) DEFAULT NULL,
  `email` varchar(300) DEFAULT NULL,
  `address` varchar(300) DEFAULT NULL,
  `place` varchar(300) DEFAULT NULL,
  `pin` varchar(300) DEFAULT NULL,
  `photo` varchar(5000) DEFAULT NULL,
  PRIMARY KEY (`police_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `police` */

insert  into `police`(`police_id`,`login_id`,`name`,`phone`,`email`,`address`,`place`,`pin`,`photo`) values 
(3,3,'Deepu Pauly','8330805387','deepupauly03@gmail.com','Thekkekkara (H)','thrissur','680721','static/24c32886-e36e-43d7-a6ec-0ff6e921c318istockphoto-1404735742-1024x1024.jpg');

/*Table structure for table `report` */

DROP TABLE IF EXISTS `report`;

CREATE TABLE `report` (
  `report_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `description` varchar(300) DEFAULT NULL,
  `reply` varchar(300) DEFAULT NULL,
  `status` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`report_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `report` */

insert  into `report`(`report_id`,`user_id`,`description`,`reply`,`status`) values 
(1,1,'rdtfyg','erdfgvh','Action taken'),
(2,2,'Hdgdh','pending','pending'),
(3,2,'Hlooooo','pending','pending');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `login_id` int(11) DEFAULT NULL,
  `name` varchar(300) DEFAULT NULL,
  `email` varchar(300) DEFAULT NULL,
  `phone` varchar(300) DEFAULT NULL,
  `place` varchar(300) DEFAULT NULL,
  `district` varchar(300) DEFAULT NULL,
  `photo` varchar(5000) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`user_id`,`login_id`,`name`,`email`,`phone`,`place`,`district`,`photo`) values 
(1,3,'kuttu','kuttu@gmail.com','3456','kuttanalor','kuttanor','static/24c32886-e36e-43d7-a6ec-0ff6e921c318istockphoto-1404735742-1024x1024.jpg'),
(2,4,'Ywyw','De@gmail.com','1234567890','Tsr','Tsr','static/images/e882cc59-7c73-4184-a949-31bd097550fe1710083430387.png');

/*Table structure for table `visitor_log` */

DROP TABLE IF EXISTS `visitor_log`;

CREATE TABLE `visitor_log` (
  `visitor_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `family_id` int(11) DEFAULT NULL,
  `date` varchar(100) DEFAULT NULL,
  `time` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`visitor_log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `visitor_log` */

insert  into `visitor_log`(`visitor_log_id`,`family_id`,`date`,`time`) values 
(1,3,'2024-03-12','15:03:15'),
(2,3,'2024-03-12','15:13:36'),
(3,4,'2024-03-12','16:24:47');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
