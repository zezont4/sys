# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Generation Time: 2015-10-03 05:44:01 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table 0_halakat
# ------------------------------------------------------------

DROP TABLE IF EXISTS `0_halakat`;

CREATE TABLE `0_halakat` (
  `AutoNo` int(4) NOT NULL AUTO_INCREMENT COMMENT 'تسلسل',
  `HName` varchar(50) NOT NULL COMMENT 'اسم الحلقة',
  `EdarahID` int(4) NOT NULL COMMENT 'رقم الإدارة',
  `hide` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'مخفي = 1',
  PRIMARY KEY (`AutoNo`),
  KEY `EdarahID` (`EdarahID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='الحلقات';



# Dump of table 0_quran
# ------------------------------------------------------------

DROP TABLE IF EXISTS `0_quran`;

CREATE TABLE `0_quran` (
  `number` int(3) NOT NULL,
  `title` varchar(20) NOT NULL,
  UNIQUE KEY `number` (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='سور القرآن';



# Dump of table 0_students
# ------------------------------------------------------------

DROP TABLE IF EXISTS `0_students`;

CREATE TABLE `0_students` (
  `st_no` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `StID` bigint(10) NOT NULL COMMENT 'السجل المدني',
  `StName1` varchar(15) NOT NULL COMMENT 'اسم الطالب',
  `StName2` varchar(15) DEFAULT NULL COMMENT 'اسم الأب',
  `StName3` varchar(15) DEFAULT NULL COMMENT 'اسم الجد',
  `StName4` varchar(15) DEFAULT NULL COMMENT 'العائلة',
  `StBurthDate` int(8) DEFAULT NULL COMMENT 'تاريخ الميلاد',
  `StMobileNo` varchar(15) DEFAULT NULL COMMENT 'رقم الجوال',
  `FatherMobileNo` varchar(15) DEFAULT NULL COMMENT 'جوال ولي الأمر',
  `StEdarah` int(4) DEFAULT NULL COMMENT 'رقم الإدارة',
  `StHalaqah` int(4) DEFAULT NULL COMMENT 'رقم الحلقة',
  `home_study` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'منتسب',
  `RegesterTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hide` tinyint(1) NOT NULL COMMENT 'مخفي=1',
  `guardian_name` varchar(50) DEFAULT '' COMMENT 'اسم ولي الأمر',
  PRIMARY KEY (`st_no`),
  UNIQUE KEY `StID` (`StID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='جدول الطلاب';



# Dump of table 0_teachers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `0_teachers`;

CREATE TABLE `0_teachers` (
  `t_no` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TID` bigint(10) NOT NULL COMMENT 'السجل المدني',
  `TName1` varchar(15) DEFAULT NULL COMMENT 'الاسم',
  `TName2` varchar(15) DEFAULT NULL COMMENT 'اسم الأب',
  `TName3` varchar(15) DEFAULT NULL COMMENT 'اسم الجد',
  `TName4` varchar(15) DEFAULT NULL COMMENT 'العائلة',
  `TEdarah` int(4) DEFAULT NULL COMMENT 'رقم الإدارة',
  `THalaqah` int(4) DEFAULT NULL COMMENT 'رقم الحلقة',
  `TMobileNo` varchar(15) DEFAULT NULL COMMENT 'جوال المعلم',
  `hide` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'مخفي=1',
  `RegesterTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'تاريخ التسجيل',
  PRIMARY KEY (`t_no`),
  UNIQUE KEY `TID` (`TID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table 0_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `0_users`;

CREATE TABLE `0_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(15) NOT NULL COMMENT 'اسم المستخدم',
  `password` varchar(15) DEFAULT NULL COMMENT 'كلمة المرور',
  `user_group` varchar(15) DEFAULT NULL COMMENT 'الصلاحية',
  `arabic_name` varchar(50) DEFAULT NULL,
  `mobile_no` varchar(10) DEFAULT NULL COMMENT 'رقم جوال الإدارة',
  `sex` tinyint(4) NOT NULL COMMENT '0=female , 1=male , 2=all',
  `can_edit` tinyint(4) NOT NULL COMMENT '1=yes له صلاحية الاضافة والتعديل والحضف',
  `hidden` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'hidden=1',
  `e_type` tinyint(1) DEFAULT NULL COMMENT 'edarah=1,program=2,others=0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UserName` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='جدول المستخدمين';



# Dump of table 0_years
# ------------------------------------------------------------

DROP TABLE IF EXISTS `0_years`;

CREATE TABLE `0_years` (
  `y_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `year_name` varchar(50) NOT NULL COMMENT 'اسم العام الدراسي',
  `y_start_date` int(11) NOT NULL COMMENT 'تاريخ البداية',
  `y_end_date` int(11) NOT NULL COMMENT 'تاريخ النهاية',
  `default_y` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'الافتراضي = 1',
  PRIMARY KEY (`y_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='العام الدراسي';



# Dump of table er_bra3m
# ------------------------------------------------------------

DROP TABLE IF EXISTS `er_bra3m`;

CREATE TABLE `er_bra3m` (
  `AutoNo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ترقيم تلقائي',
  `StID` bigint(10) NOT NULL COMMENT 'الطالب',
  `TeacherID` bigint(10) NOT NULL COMMENT 'المعلم',
  `HalakahID` int(11) NOT NULL COMMENT 'الحلقة',
  `EdarahID` int(11) NOT NULL COMMENT 'الإدارة',
  `SchoolLevelID` int(11) NOT NULL COMMENT 'الصف الدراسي',
  `DarajahID` tinyint(4) NOT NULL COMMENT 'الدرجة في السلم',
  `Money` smallint(6) NOT NULL COMMENT 'الجائزة',
  `DDate` int(8) NOT NULL COMMENT 'تاريخ الدرجة',
  PRIMARY KEY (`AutoNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='سلم البراعم';



# Dump of table er_ertiqa_names
# ------------------------------------------------------------

DROP TABLE IF EXISTS `er_ertiqa_names`;

CREATE TABLE `er_ertiqa_names` (
  `murtaqa_id` mediumint(3) NOT NULL COMMENT 'رقم المرتقى',
  `murtaqa_name` varchar(20) NOT NULL COMMENT 'اسم المرتقى',
  `murtaqa_points` tinyint(1) NOT NULL COMMENT 'النقاط',
  `hide` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'مخفي=1',
  PRIMARY KEY (`murtaqa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='اسماء المرتقيات';



# Dump of table er_ertiqaexams
# ------------------------------------------------------------

DROP TABLE IF EXISTS `er_ertiqaexams`;

CREATE TABLE `er_ertiqaexams` (
  `AutoNo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'تسلسل',
  `RegesterTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'وقت الحجز',
  `StID` bigint(10) NOT NULL COMMENT 'رقم الطالب',
  `TeacherID` bigint(10) NOT NULL COMMENT 'رقم المعلم',
  `EdarahID` int(4) NOT NULL COMMENT 'رقم الإدارة',
  `HalakahID` int(4) NOT NULL COMMENT 'رقم الحلقة',
  `ErtiqaID` tinyint(2) NOT NULL COMMENT 'رقم المرتقى',
  `H_SolokGrade` tinyint(2) NOT NULL COMMENT 'درجة السلوك',
  `H_MwadabahGrade` tinyint(2) NOT NULL COMMENT 'درحة المواظبة',
  `TestExamDate` int(8) DEFAULT NULL COMMENT 'تاريخ الاختبار التجريبي',
  `TestExamDegree` smallint(3) NOT NULL COMMENT 'درجة الاختبار التجريبي',
  `FinalExamDegree` smallint(3) DEFAULT NULL COMMENT 'درجة الاختبار النهائي',
  `FinalExamDate` int(8) DEFAULT NULL COMMENT 'تاريخ الاختبار النهائي',
  `FinalExamDay` tinyint(1) DEFAULT NULL COMMENT 'يوم الاختبار النهائي',
  `FinalExamStatus` tinyint(1) DEFAULT '0' COMMENT 'الغياب والتأجيل',
  `AnswerTime` varchar(50) DEFAULT NULL COMMENT 'وقت الرد',
  PRIMARY KEY (`AutoNo`),
  KEY `StID` (`StID`),
  KEY `ErtiqaID` (`ErtiqaID`),
  KEY `TeacherID` (`TeacherID`),
  KEY `ErtiqaID_2` (`ErtiqaID`),
  KEY `EdarahID` (`EdarahID`),
  KEY `HalakahID` (`HalakahID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='جدول اختبارات الارتقاء';



# Dump of table er_old_exam
# ------------------------------------------------------------

DROP TABLE IF EXISTS `er_old_exam`;

CREATE TABLE `er_old_exam` (
  `AutoNo` int(11) NOT NULL AUTO_INCREMENT,
  `Name1` varchar(100) DEFAULT NULL,
  `Name2` varchar(100) DEFAULT NULL,
  `Name3` varchar(100) DEFAULT NULL,
  `Name4` varchar(100) DEFAULT NULL,
  `TName` varchar(510) DEFAULT NULL,
  `Halakah` varchar(100) DEFAULT NULL,
  `mosqe` varchar(500) DEFAULT NULL,
  `Edarah` varchar(510) DEFAULT NULL,
  `Murtaqa` varchar(510) DEFAULT NULL,
  `Degree` int(11) DEFAULT NULL,
  `Mark` varchar(510) DEFAULT NULL,
  `StMoney` int(11) DEFAULT NULL,
  `TMoney` int(11) DEFAULT NULL,
  `ExamDate` int(11) DEFAULT NULL,
  `Mokhtaber` varchar(510) DEFAULT NULL,
  `degree1` int(11) DEFAULT NULL,
  `full1` int(11) DEFAULT NULL,
  UNIQUE KEY `AutoNo` (`AutoNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table er_shahadah
# ------------------------------------------------------------

DROP TABLE IF EXISTS `er_shahadah`;

CREATE TABLE `er_shahadah` (
  `ExamNo` int(11) NOT NULL COMMENT 'رقم الاختبار',
  `Sora1Name` tinyint(3) DEFAULT NULL COMMENT 'اسم السورة الأولى',
  `Sora1Discount` decimal(4,2) DEFAULT '0.00' COMMENT 'الدرجة المحسومة',
  `Sora2Name` tinyint(3) DEFAULT NULL,
  `Sora2Discount` decimal(4,2) DEFAULT '0.00',
  `Sora3Name` tinyint(3) DEFAULT NULL,
  `Sora3Discount` decimal(4,2) DEFAULT '0.00',
  `Sora4Name` tinyint(3) DEFAULT NULL,
  `Sora4Discount` decimal(4,2) DEFAULT '0.00',
  `Sora5Name` tinyint(3) DEFAULT NULL,
  `Sora5Discount` decimal(4,2) DEFAULT '0.00',
  `Ek_slok` double DEFAULT '20' COMMENT 'السلوك في الاختبار',
  `Ek_mwathbah` tinyint(2) DEFAULT '20' COMMENT 'المواظبة في الاختبار',
  `Degree` tinyint(11) NOT NULL DEFAULT '0' COMMENT 'درجة الاختبار',
  `Money` smallint(3) NOT NULL DEFAULT '0' COMMENT 'المكافأة',
  `teacher_money` smallint(3) DEFAULT NULL COMMENT 'حافز المعلم',
  `edarah_money` smallint(3) DEFAULT NULL COMMENT 'حافز الإدارة',
  `ExamPoints` decimal(3,2) DEFAULT NULL COMMENT 'نقاط الاختبار',
  `MarkName_Short` varchar(4) DEFAULT NULL COMMENT 'رمز التقدير',
  `MarkName_Long` varchar(15) DEFAULT NULL COMMENT 'التقدير',
  `MukhtaberTeacher` smallint(4) NOT NULL COMMENT 'رقم المعلم المختبر',
  `MukhtaberTeacher2` smallint(4) DEFAULT NULL COMMENT 'رقم المعلم المختبر',
  `Sora6Name` tinyint(3) DEFAULT NULL,
  `Sora6Discount` decimal(4,2) DEFAULT NULL,
  `Sora7Name` tinyint(3) DEFAULT NULL,
  `Sora7Discount` decimal(4,2) DEFAULT NULL,
  `Sora8Name` tinyint(3) DEFAULT NULL,
  `Sora8Discount` decimal(4,2) DEFAULT NULL,
  `Sora9Name` tinyint(3) DEFAULT NULL,
  `Sora9Discount` decimal(4,2) DEFAULT NULL,
  `Sora10Name` tinyint(3) DEFAULT NULL,
  `Sora10Discount` decimal(4,2) DEFAULT NULL,
  UNIQUE KEY `ExamNo` (`ExamNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='الشهادة';



# Dump of table groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `permission` text NOT NULL,
  `sorting` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table ms_etqan_rgstr
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ms_etqan_rgstr`;

CREATE TABLE `ms_etqan_rgstr` (
  `EdarahID` smallint(6) NOT NULL,
  `HalakahID` smallint(6) NOT NULL,
  `TeacherID` varchar(10) NOT NULL,
  `StID` varchar(10) NOT NULL,
  `ErtiqaID` tinyint(4) NOT NULL COMMENT 'آخر مرتقى',
  `MsbkhID` tinyint(4) NOT NULL COMMENT 'نوع المسابقة',
  `SchoolLevelID` tinyint(4) NOT NULL COMMENT 'السنة الدراسية',
  `AutoNo` int(11) NOT NULL AUTO_INCREMENT,
  `RDate` varchar(8) NOT NULL COMMENT 'تاريخ التسجيل',
  PRIMARY KEY (`AutoNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='المتقدمين لمسابقة الاتقان';



# Dump of table ms_fahd_featured_teacher
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ms_fahd_featured_teacher`;

CREATE TABLE `ms_fahd_featured_teacher` (
  `auto_no` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ترقيم تلقائي',
  `full_degree` decimal(5,2) NOT NULL COMMENT 'الدرجة الكاملة',
  `teacher_id` varchar(10) NOT NULL COMMENT 'رقم المعلم',
  `t_edarah` smallint(6) NOT NULL COMMENT 'رقم الإدارة',
  `f_t_date` int(8) DEFAULT NULL,
  `f_1a_n` smallint(6) DEFAULT NULL COMMENT 'درجة تقييم المشرف',
  `f_1a_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_2a_n` smallint(6) DEFAULT NULL COMMENT 'عدد نجاح المرتقيات',
  `f_2a_t` smallint(6) DEFAULT NULL COMMENT 'اجمالي الطلاب',
  `f_2a_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_2b_n` smallint(6) DEFAULT NULL COMMENT 'عدد نجاح البراعم',
  `f_2b_t` smallint(6) DEFAULT NULL COMMENT 'اجمالي الطلاب',
  `f_2b_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_3a_n` smallint(6) DEFAULT NULL COMMENT 'المشاركة في مسابقة الأفراد',
  `f_3a_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_3b_n` smallint(6) DEFAULT NULL COMMENT 'المشاركة في مسابقة الحلق ',
  `f_3b_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_4a_n` smallint(6) DEFAULT NULL COMMENT 'درجة لكل طالب تأهل للمرحلة النهائية',
  `f_4a_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_4b_n` smallint(6) DEFAULT NULL COMMENT 'فوز طالب بالمرحلة النهائية بالأفراد ',
  `f_4b_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_5a_n` smallint(6) DEFAULT NULL COMMENT 'دورة الجزرية ',
  `f_5a_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_5b_n` smallint(6) DEFAULT NULL COMMENT 'تحفة الأطفال ',
  `f_5b_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_5c_n` smallint(6) DEFAULT NULL COMMENT 'دورة تطويرية لهذا العام ( أربع ساعات ) ',
  `f_5c_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_6a_n` smallint(6) DEFAULT NULL COMMENT 'عدد حضور الاجتماعات ',
  `f_6a_t` smallint(6) DEFAULT NULL COMMENT 'اجمالي الاجتماعات',
  `f_6a_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_7a_n` smallint(6) DEFAULT NULL COMMENT 'استخدام منهجية الجمعية ',
  `f_7a_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_8a_n` smallint(6) DEFAULT NULL COMMENT 'المرتقى',
  `f_8a_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_9a_n` smallint(6) DEFAULT NULL COMMENT 'حضور أي برنامج تعليمي ',
  `f_9a_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_10a_n` smallint(6) DEFAULT NULL COMMENT 'المشاركة في الدورات الخارجية',
  `f_10a_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_11a_n` smallint(6) DEFAULT NULL COMMENT 'فكرة جديدة',
  `f_11a_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_12a_n` smallint(6) DEFAULT NULL COMMENT 'معه إجازة',
  `f_12a_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_13a_n` smallint(6) DEFAULT NULL COMMENT 'معه الشاطبية',
  `f_13a_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_14a_n` smallint(6) DEFAULT NULL COMMENT 'تمثيله أو أحد طلابه في المسابقات الخارجية',
  `f_14a_d` decimal(4,2) DEFAULT NULL COMMENT 'الدرجة',
  `f_15a_n` smallint(6) NOT NULL,
  `f_15a_d` decimal(4,0) NOT NULL COMMENT 'شفاء الصدور',
  `f_15b_n` smallint(6) NOT NULL,
  `f_15b_d` decimal(4,2) NOT NULL COMMENT 'رتل',
  `approved` int(1) NOT NULL DEFAULT '0' COMMENT 'معتمد = 1',
  `max_degree` int(3) NOT NULL COMMENT 'الدرجة العظمى',
  PRIMARY KEY (`auto_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='الملعلم المتميز';



# Dump of table ms_fahd_rgstr
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ms_fahd_rgstr`;

CREATE TABLE `ms_fahd_rgstr` (
  `EdarahID` smallint(6) NOT NULL,
  `HalakahID` smallint(6) NOT NULL,
  `HalakahID2` smallint(6) DEFAULT NULL COMMENT 'الحلقة الأساسية، إذا كانت الأولى احتياط',
  `TeacherID` varchar(10) NOT NULL,
  `StID` varchar(10) NOT NULL,
  `st_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'أساسي أو احتياط',
  `ErtiqaID` tinyint(4) NOT NULL COMMENT 'آخر مرتقى',
  `MsbkhID` tinyint(4) NOT NULL COMMENT 'نوع المسابقة',
  `SchoolLevelID` tinyint(4) NOT NULL COMMENT 'السنة الدراسية',
  `AutoNo` int(11) NOT NULL AUTO_INCREMENT,
  `RDate` varchar(8) NOT NULL COMMENT 'تاريخ التسجيل',
  PRIMARY KEY (`AutoNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='المتقدمين لمسابقة الفهد';



# Dump of table ms_fahd_years
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ms_fahd_years`;

CREATE TABLE `ms_fahd_years` (
  `y_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `year_name` varchar(50) NOT NULL COMMENT 'اسم العام الدراسي',
  `y_start_date` int(11) NOT NULL COMMENT 'تاريخ البداية',
  `y_end_date` int(11) NOT NULL COMMENT 'تاريخ النهاية',
  PRIMARY KEY (`y_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='العام الدراسي لمسابقات الفهد';



# Dump of table ms_shabab_rgstr
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ms_shabab_rgstr`;

CREATE TABLE `ms_shabab_rgstr` (
  `EdarahID` smallint(6) NOT NULL,
  `HalakahID` smallint(6) NOT NULL,
  `TeacherID` varchar(10) NOT NULL,
  `StID` varchar(10) NOT NULL,
  `ErtiqaID` tinyint(4) NOT NULL COMMENT 'آخر مرتقى',
  `MsbkhID` tinyint(4) NOT NULL COMMENT 'نوع المسابقة',
  `SchoolLevelID` tinyint(4) NOT NULL COMMENT 'السنة الدراسية',
  `AutoNo` int(11) NOT NULL AUTO_INCREMENT,
  `RDate` varchar(8) NOT NULL COMMENT 'تاريخ التسجيل',
  PRIMARY KEY (`AutoNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='المتقدمين لمسابقة رعاية الشباب';



# Dump of table trmez
# ------------------------------------------------------------

DROP TABLE IF EXISTS `trmez`;

CREATE TABLE `trmez` (
  `t_auto_no` int(11) NOT NULL AUTO_INCREMENT,
  `t_id` smallint(6) NOT NULL,
  `t_key` varchar(10) NOT NULL,
  `t_val` varchar(40) NOT NULL,
  `t_val_g` varchar(50) DEFAULT NULL,
  `sorting` smallint(6) NOT NULL DEFAULT '0',
  `t_hide` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`t_auto_no`),
  UNIQUE KEY `t_id` (`t_id`,`t_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table view_0_halakat
# ------------------------------------------------------------

DROP VIEW IF EXISTS `view_0_halakat`;

CREATE TABLE `view_0_halakat` (
   `AutoNo` INT(4) NOT NULL DEFAULT '0',
   `HName` VARCHAR(50) NOT NULL,
   `EdarahID` INT(4) NOT NULL,
   `hide` TINYINT(1) NOT NULL DEFAULT '0',
   `o_hide` VARCHAR(4) NULL DEFAULT NULL,
   `Sex` TINYINT(4) NOT NULL,
   `arabic_name` VARCHAR(50) NULL DEFAULT NULL
) ENGINE=MyISAM;



# Dump of table view_0_students
# ------------------------------------------------------------

DROP VIEW IF EXISTS `view_0_students`;

CREATE TABLE `view_0_students` (
   `home_study` TINYINT(4) NOT NULL DEFAULT '0',
   `StName1` VARCHAR(15) NOT NULL,
   `StName2` VARCHAR(15) NULL DEFAULT NULL,
   `StName3` VARCHAR(15) NULL DEFAULT NULL,
   `StName4` VARCHAR(15) NULL DEFAULT NULL,
   `StID` BIGINT(10) NOT NULL,
   `Stfullname` VARCHAR(63) NULL DEFAULT NULL,
   `StBurthDate` INT(8) NULL DEFAULT NULL,
   `StMobileNo` VARCHAR(15) NULL DEFAULT NULL,
   `FatherMobileNo` VARCHAR(15) NULL DEFAULT NULL,
   `StEdarah` INT(4) NULL DEFAULT NULL,
   `StHalaqah` INT(4) NULL DEFAULT NULL,
   `arabic_name` VARCHAR(50) NULL DEFAULT NULL,
   `e_sex` TINYINT(4) NULL DEFAULT NULL,
   `HName` VARCHAR(50) NULL DEFAULT NULL,
   `h_hide` TINYINT(1) NULL DEFAULT '0',
   `hide` TINYINT(1) NOT NULL,
   `TID` BIGINT(10) NULL DEFAULT NULL,
   `o_hide` VARCHAR(4) NULL DEFAULT NULL,
   `O_BurthDate` VARCHAR(10) NULL DEFAULT NULL
) ENGINE=MyISAM;



# Dump of table view_0_teachers
# ------------------------------------------------------------

DROP VIEW IF EXISTS `view_0_teachers`;

CREATE TABLE `view_0_teachers` (
   `hide` TINYINT(1) NOT NULL DEFAULT '0',
   `o_hide` VARCHAR(4) NULL DEFAULT NULL,
   `TID` BIGINT(10) NOT NULL,
   `TName1` VARCHAR(15) NULL DEFAULT NULL,
   `TName2` VARCHAR(15) NULL DEFAULT NULL,
   `TName3` VARCHAR(15) NULL DEFAULT NULL,
   `TName4` VARCHAR(15) NULL DEFAULT NULL,
   `TEdarah` INT(4) NULL DEFAULT NULL,
   `THalaqah` INT(4) NULL DEFAULT NULL,
   `Tfullname` VARCHAR(63) NULL DEFAULT NULL,
   `e_sex` TINYINT(4) NULL DEFAULT NULL,
   `arabic_name` VARCHAR(50) NULL DEFAULT NULL,
   `HName` VARCHAR(50) NULL DEFAULT NULL,
   `h_hide` TINYINT(1) NULL DEFAULT '0',
   `TMobileNo` VARCHAR(15) NULL DEFAULT NULL
) ENGINE=MyISAM;



# Dump of table view_0_users
# ------------------------------------------------------------

DROP VIEW IF EXISTS `view_0_users`;

CREATE TABLE `view_0_users` (
   `id` INT(11) NOT NULL DEFAULT '0',
   `username` VARCHAR(15) NOT NULL,
   `password` VARCHAR(15) NULL DEFAULT NULL,
   `user_group` VARCHAR(15) NULL DEFAULT NULL,
   `arabic_name` VARCHAR(50) NULL DEFAULT NULL,
   `mobile_no` VARCHAR(10) NULL DEFAULT NULL,
   `sex` TINYINT(4) NOT NULL,
   `can_edit` TINYINT(4) NOT NULL,
   `hidden` TINYINT(4) NOT NULL DEFAULT '0',
   `e_type` TINYINT(1) NULL DEFAULT NULL,
   `o_user_group` VARCHAR(20) NULL DEFAULT NULL,
   `o_sex` VARCHAR(40) NULL DEFAULT NULL,
   `o_hide` VARCHAR(40) NULL DEFAULT NULL
) ENGINE=MyISAM;



# Dump of table view_er_bra3m
# ------------------------------------------------------------

DROP VIEW IF EXISTS `view_er_bra3m`;

CREATE TABLE `view_er_bra3m` (
   `AutoNo` INT(11) NOT NULL DEFAULT '0',
   `StID` BIGINT(10) NOT NULL,
   `TeacherID` BIGINT(10) NOT NULL,
   `HalakahID` INT(11) NOT NULL,
   `EdarahID` INT(11) NOT NULL,
   `SchoolLevelID` INT(11) NOT NULL,
   `DarajahID` TINYINT(4) NOT NULL,
   `Money` SMALLINT(6) NOT NULL,
   `DDate` INT(8) NOT NULL,
   `O_StFullName` VARCHAR(63) NULL DEFAULT NULL,
   `O_TFullName` VARCHAR(63) NULL DEFAULT NULL,
   `O_arabic_name` VARCHAR(50) NULL DEFAULT NULL,
   `O_HName` VARCHAR(50) NULL DEFAULT NULL,
   `O_DDate` VARCHAR(10) NOT NULL DEFAULT ''
) ENGINE=MyISAM;



# Dump of table view_er_ertiqaexams
# ------------------------------------------------------------

DROP VIEW IF EXISTS `view_er_ertiqaexams`;

CREATE TABLE `view_er_ertiqaexams` (
   `home_study` TINYINT(4) NULL DEFAULT '0',
   `e_type` TINYINT(1) NULL DEFAULT NULL,
   `AutoNo` INT(11) NOT NULL DEFAULT '0',
   `StName1` VARCHAR(15) NULL DEFAULT NULL,
   `StName2` VARCHAR(15) NULL DEFAULT NULL,
   `StName3` VARCHAR(15) NULL DEFAULT NULL,
   `StName4` VARCHAR(15) NULL DEFAULT NULL,
   `O_StudentName` VARCHAR(63) NULL DEFAULT NULL,
   `O_StudentName3` VARCHAR(47) NULL DEFAULT NULL,
   `O_TeacherName` VARCHAR(63) NULL DEFAULT NULL,
   `O_TeacherName3` VARCHAR(47) NULL DEFAULT NULL,
   `O_TestExamDate` VARCHAR(10) NULL DEFAULT NULL,
   `O_FinalExamDate` VARCHAR(10) NULL DEFAULT NULL,
   `O_BurthDate` VARCHAR(10) NULL DEFAULT NULL,
   `O_Edarah` VARCHAR(50) NULL DEFAULT NULL,
   `O_HName` VARCHAR(50) NULL DEFAULT NULL,
   `O_MurtaqaName` VARCHAR(20) NULL DEFAULT NULL,
   `StMobileNo` VARCHAR(15) NULL DEFAULT NULL,
   `FatherMobileNo` VARCHAR(15) NULL DEFAULT NULL,
   `RegesterTime` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
   `ErtiqaID` TINYINT(2) NOT NULL,
   `HalakahID` INT(4) NOT NULL,
   `StID` BIGINT(10) NOT NULL,
   `TeacherID` BIGINT(10) NOT NULL,
   `EdarahID` INT(4) NOT NULL,
   `TestExamDegree` SMALLINT(3) NOT NULL,
   `TestExamDate` INT(8) NULL DEFAULT NULL,
   `FinalExamStatus` TINYINT(1) NULL DEFAULT '0',
   `FinalExamDate` INT(8) NULL DEFAULT NULL,
   `AnswerTime` VARCHAR(50) NULL DEFAULT NULL,
   `H_SolokGrade` TINYINT(2) NOT NULL,
   `H_MwadabahGrade` TINYINT(2) NOT NULL,
   `Sora1Name` TINYINT(3) NULL DEFAULT NULL,
   `ExamPoints` DECIMAL(3) NULL DEFAULT NULL,
   `Sora1Discount` DECIMAL(4) NULL DEFAULT '0.00',
   `Sora2Name` TINYINT(3) NULL DEFAULT NULL,
   `Sora2Discount` DECIMAL(4) NULL DEFAULT '0.00',
   `Sora3Name` TINYINT(3) NULL DEFAULT NULL,
   `Sora3Discount` DECIMAL(4) NULL DEFAULT '0.00',
   `Sora4Name` TINYINT(3) NULL DEFAULT NULL,
   `Sora4Discount` DECIMAL(4) NULL DEFAULT '0.00',
   `Sora5Name` TINYINT(3) NULL DEFAULT NULL,
   `Sora5Discount` DECIMAL(4) NULL DEFAULT '0.00',
   `Sora6Name` TINYINT(3) NULL DEFAULT NULL,
   `Sora6Discount` DECIMAL(4) NULL DEFAULT NULL,
   `Sora7Name` TINYINT(3) NULL DEFAULT NULL,
   `Sora7Discount` DECIMAL(4) NULL DEFAULT NULL,
   `Sora8Name` TINYINT(3) NULL DEFAULT NULL,
   `Sora8Discount` DECIMAL(4) NULL DEFAULT NULL,
   `Sora9Name` TINYINT(3) NULL DEFAULT NULL,
   `Sora9Discount` DECIMAL(4) NULL DEFAULT NULL,
   `Sora10Name` TINYINT(3) NULL DEFAULT NULL,
   `Sora10Discount` DECIMAL(4) NULL DEFAULT NULL,
   `Ek_mwathbah` TINYINT(2) NULL DEFAULT '20',
   `Ek_slok` DOUBLE NULL DEFAULT '20',
   `MarkName_Short` VARCHAR(4) NULL DEFAULT NULL,
   `MarkName_Long` VARCHAR(15) NULL DEFAULT NULL,
   `Money` SMALLINT(3) NULL DEFAULT '0',
   `teacher_money` SMALLINT(3) NULL DEFAULT NULL,
   `edarah_money` SMALLINT(3) NULL DEFAULT NULL,
   `FinalExamDegree` TINYINT(11) NULL DEFAULT '0',
   `MukhtaberTeacher` SMALLINT(4) NULL DEFAULT NULL,
   `MukhtaberTeacher2` SMALLINT(4) NULL DEFAULT NULL,
   `teacher_hide` TINYINT(1) NULL DEFAULT '0',
   `edarah_hide` TINYINT(4) NULL DEFAULT '0',
   `edarah_sex` TINYINT(4) NULL DEFAULT NULL
) ENGINE=MyISAM;



# Dump of table view_er_shahadah
# ------------------------------------------------------------

DROP VIEW IF EXISTS `view_er_shahadah`;

CREATE TABLE `view_er_shahadah` (
   `ExamNo` INT(11) NOT NULL,
   `Sora1Name` TINYINT(3) NULL DEFAULT NULL,
   `Sora1Discount` DECIMAL(4) NULL DEFAULT '0.00',
   `Sora2Name` TINYINT(3) NULL DEFAULT NULL,
   `Sora2Discount` DECIMAL(4) NULL DEFAULT '0.00',
   `Sora3Name` TINYINT(3) NULL DEFAULT NULL,
   `Sora3Discount` DECIMAL(4) NULL DEFAULT '0.00',
   `Sora4Name` TINYINT(3) NULL DEFAULT NULL,
   `Sora4Discount` DECIMAL(4) NULL DEFAULT '0.00',
   `Sora5Name` TINYINT(3) NULL DEFAULT NULL,
   `Sora5Discount` DECIMAL(4) NULL DEFAULT '0.00',
   `Ek_slok` DOUBLE NULL DEFAULT '20',
   `Ek_mwathbah` TINYINT(2) NULL DEFAULT '20',
   `Degree` TINYINT(11) NOT NULL DEFAULT '0',
   `Money` SMALLINT(3) NOT NULL DEFAULT '0',
   `MarkName_Short` VARCHAR(4) NULL DEFAULT NULL,
   `MarkName_Long` VARCHAR(15) NULL DEFAULT NULL,
   `MukhtaberTeacher` SMALLINT(4) NOT NULL,
   `O_Sora1Name` VARCHAR(20) NOT NULL,
   `O_Sora2Name` VARCHAR(20) NOT NULL,
   `O_Sora3Name` VARCHAR(20) NOT NULL,
   `O_Sora4Name` VARCHAR(20) NOT NULL,
   `O_Sora5Name` VARCHAR(20) NOT NULL
) ENGINE=MyISAM;





# Replace placeholder table for view_er_shahadah with correct view syntax
# ------------------------------------------------------------

DROP TABLE `view_er_shahadah`;

CREATE VIEW `view_er_shahadah`
AS SELECT
   `er`.`ExamNo` AS `ExamNo`,
   `er`.`Sora1Name` AS `Sora1Name`,
   `er`.`Sora1Discount` AS `Sora1Discount`,
   `er`.`Sora2Name` AS `Sora2Name`,
   `er`.`Sora2Discount` AS `Sora2Discount`,
   `er`.`Sora3Name` AS `Sora3Name`,
   `er`.`Sora3Discount` AS `Sora3Discount`,
   `er`.`Sora4Name` AS `Sora4Name`,
   `er`.`Sora4Discount` AS `Sora4Discount`,
   `er`.`Sora5Name` AS `Sora5Name`,
   `er`.`Sora5Discount` AS `Sora5Discount`,
   `er`.`Ek_slok` AS `Ek_slok`,
   `er`.`Ek_mwathbah` AS `Ek_mwathbah`,
   `er`.`Degree` AS `Degree`,
   `er`.`Money` AS `Money`,
   `er`.`MarkName_Short` AS `MarkName_Short`,
   `er`.`MarkName_Long` AS `MarkName_Long`,
   `er`.`MukhtaberTeacher` AS `MukhtaberTeacher`,
   `q1`.`title` AS `O_Sora1Name`,
   `q2`.`title` AS `O_Sora2Name`,
   `q3`.`title` AS `O_Sora3Name`,
   `q4`.`title` AS `O_Sora4Name`,
   `q5`.`title` AS `O_Sora5Name`
FROM (((((`er_shahadah` `er` join `0_quran` `q1`) join `0_quran` `q2`) join `0_quran` `q3`) join `0_quran` `q4`) join `0_quran` `q5`) where ((`er`.`Sora1Name` = `q1`.`number`) and (`er`.`Sora2Name` = `q2`.`number`) and (`er`.`Sora3Name` = `q3`.`number`) and (`er`.`Sora4Name` = `q4`.`number`) and (`er`.`Sora5Name` = `q5`.`number`));


# Replace placeholder table for view_er_bra3m with correct view syntax
# ------------------------------------------------------------

DROP TABLE `view_er_bra3m`;

CREATE VIEW `view_er_bra3m`
AS SELECT
   `b`.`AutoNo` AS `AutoNo`,
   `b`.`StID` AS `StID`,
   `b`.`TeacherID` AS `TeacherID`,
   `b`.`HalakahID` AS `HalakahID`,
   `b`.`EdarahID` AS `EdarahID`,
   `b`.`SchoolLevelID` AS `SchoolLevelID`,
   `b`.`DarajahID` AS `DarajahID`,
   `b`.`Money` AS `Money`,
   `b`.`DDate` AS `DDate`,concat_ws(' ',`s`.`StName1`,
   `s`.`StName2`,
   `s`.`StName3`,
   `s`.`StName4`) AS `O_StFullName`,concat_ws(' ',`t`.`TName1`,
   `t`.`TName2`,
   `t`.`TName3`,
   `t`.`TName4`) AS `O_TFullName`,
   `e`.`arabic_name` AS `O_arabic_name`,
   `h`.`HName` AS `O_HName`,concat_ws('/',substr(cast(`b`.`DDate` as char charset utf8),1,4),substr(cast(`b`.`DDate` as char charset utf8),5,2),substr(cast(`b`.`DDate` as char charset utf8),7,2)) AS `O_DDate`
FROM ((((`er_bra3m` `b` left join `0_students` `s` on((`b`.`StID` = `s`.`StID`))) left join `0_teachers` `t` on((`b`.`TeacherID` = `t`.`TID`))) left join `0_users` `e` on((`b`.`EdarahID` = `e`.`id`))) left join `0_halakat` `h` on((`b`.`HalakahID` = `h`.`AutoNo`)));


# Replace placeholder table for view_0_halakat with correct view syntax
# ------------------------------------------------------------

DROP TABLE `view_0_halakat`;

CREATE ALGORITHM=UNDEFINED DEFINER=`C277890_qprogram`@`%` SQL SECURITY DEFINER VIEW `view_0_halakat`
AS SELECT
   `h`.`AutoNo` AS `AutoNo`,
   `h`.`HName` AS `HName`,
   `h`.`EdarahID` AS `EdarahID`,
   `h`.`hide` AS `hide`,(case `h`.`hide` when '1' then 'مخفي' when '0' then 'ظاهر' end) AS `o_hide`,
   `e`.`sex` AS `Sex`,
   `e`.`arabic_name` AS `arabic_name`
FROM (`0_users` `e` join `0_halakat` `h` on((`h`.`EdarahID` = `e`.`id`)));


# Replace placeholder table for view_0_teachers with correct view syntax
# ------------------------------------------------------------

DROP TABLE `view_0_teachers`;

CREATE VIEW `view_0_teachers`
AS SELECT
   `t`.`hide` AS `hide`,(case `t`.`hide` when '1' then 'مخفي' when '0' then 'ظاهر' end) AS `o_hide`,
   `t`.`TID` AS `TID`,
   `t`.`TName1` AS `TName1`,
   `t`.`TName2` AS `TName2`,
   `t`.`TName3` AS `TName3`,
   `t`.`TName4` AS `TName4`,
   `t`.`TEdarah` AS `TEdarah`,
   `t`.`THalaqah` AS `THalaqah`,concat_ws(' ',`t`.`TName1`,
   `t`.`TName2`,
   `t`.`TName3`,
   `t`.`TName4`) AS `Tfullname`,
   `e`.`sex` AS `e_sex`,
   `e`.`arabic_name` AS `arabic_name`,
   `h`.`HName` AS `HName`,
   `h`.`hide` AS `h_hide`,
   `t`.`TMobileNo` AS `TMobileNo`
FROM ((`0_teachers` `t` left join `0_users` `e` on((`e`.`id` = `t`.`TEdarah`))) left join `0_halakat` `h` on((`h`.`AutoNo` = `t`.`THalaqah`))) order by concat_ws(' ',`t`.`TName1`,`t`.`TName2`,`t`.`TName3`,`t`.`TName4`);


# Replace placeholder table for view_0_users with correct view syntax
# ------------------------------------------------------------

DROP TABLE `view_0_users`;

CREATE VIEW `view_0_users`
AS SELECT
   `u`.`id` AS `id`,
   `u`.`username` AS `username`,
   `u`.`password` AS `password`,
   `u`.`user_group` AS `user_group`,
   `u`.`arabic_name` AS `arabic_name`,
   `u`.`mobile_no` AS `mobile_no`,
   `u`.`sex` AS `sex`,
   `u`.`can_edit` AS `can_edit`,
   `u`.`hidden` AS `hidden`,
   `u`.`e_type` AS `e_type`,
   `g`.`name` AS `o_user_group`,
   `t2`.`t_val` AS `o_sex`,
   `t3`.`t_val` AS `o_hide`
FROM (((`0_users` `u` left join `groups` `g` on((`u`.`user_group` = `g`.`id`))) left join `trmez` `t2` on((`u`.`sex` = `t2`.`t_key`))) left join `trmez` `t3` on((`u`.`hidden` = `t3`.`t_key`))) where ((`t2`.`t_id` = 2) and (`t3`.`t_id` = 3));


# Replace placeholder table for view_0_students with correct view syntax
# ------------------------------------------------------------

DROP TABLE `view_0_students`;

CREATE VIEW `view_0_students`
AS SELECT
   `s`.`home_study` AS `home_study`,
   `s`.`StName1` AS `StName1`,
   `s`.`StName2` AS `StName2`,
   `s`.`StName3` AS `StName3`,
   `s`.`StName4` AS `StName4`,
   `s`.`StID` AS `StID`,concat_ws(' ',`s`.`StName1`,
   `s`.`StName2`,
   `s`.`StName3`,
   `s`.`StName4`) AS `Stfullname`,
   `s`.`StBurthDate` AS `StBurthDate`,
   `s`.`StMobileNo` AS `StMobileNo`,
   `s`.`FatherMobileNo` AS `FatherMobileNo`,
   `s`.`StEdarah` AS `StEdarah`,
   `s`.`StHalaqah` AS `StHalaqah`,
   `e`.`arabic_name` AS `arabic_name`,
   `e`.`sex` AS `e_sex`,
   `h`.`HName` AS `HName`,
   `h`.`hide` AS `h_hide`,
   `s`.`hide` AS `hide`,
   `t`.`TID` AS `TID`,(case `s`.`hide` when '1' then 'مخفي' when '0' then 'ظاهر' end) AS `o_hide`,concat_ws('/',substr(cast(`s`.`StBurthDate` as char charset utf8),1,4),substr(cast(`s`.`StBurthDate` as char charset utf8),5,2),substr(cast(`s`.`StBurthDate` as char charset utf8),7,2)) AS `O_BurthDate`
FROM (((`0_students` `s` left join `0_users` `e` on((`e`.`id` = `s`.`StEdarah`))) left join `0_halakat` `h` on((`h`.`AutoNo` = `s`.`StHalaqah`))) left join `0_teachers` `t` on(((`t`.`THalaqah` = `s`.`StHalaqah`) and (`t`.`hide` = '0')))) order by concat_ws(' ',`s`.`StName1`,`s`.`StName2`,`s`.`StName3`,`s`.`StName4`);


# Replace placeholder table for view_er_ertiqaexams with correct view syntax
# ------------------------------------------------------------

DROP TABLE `view_er_ertiqaexams`;

CREATE VIEW `view_er_ertiqaexams`
AS SELECT
   `st`.`home_study` AS `home_study`,
   `edarat`.`e_type` AS `e_type`,
   `ex`.`AutoNo` AS `AutoNo`,
   `st`.`StName1` AS `StName1`,
   `st`.`StName2` AS `StName2`,
   `st`.`StName3` AS `StName3`,
   `st`.`StName4` AS `StName4`,concat_ws(' ',`st`.`StName1`,
   `st`.`StName2`,
   `st`.`StName3`,
   `st`.`StName4`) AS `O_StudentName`,concat_ws(' ',`st`.`StName1`,
   `st`.`StName2`,
   `st`.`StName4`) AS `O_StudentName3`,concat_ws(' ',`t`.`TName1`,
   `t`.`TName2`,
   `t`.`TName3`,
   `t`.`TName4`) AS `O_TeacherName`,concat_ws(' ',`t`.`TName1`,
   `t`.`TName2`,
   `t`.`TName4`) AS `O_TeacherName3`,concat_ws('/',substr(cast(`ex`.`TestExamDate` as char charset utf8),1,4),substr(cast(`ex`.`TestExamDate` as char charset utf8),5,2),substr(cast(`ex`.`TestExamDate` as char charset utf8),7,2)) AS `O_TestExamDate`,concat_ws('/',substr(cast(`ex`.`FinalExamDate` as char charset utf8),1,4),substr(cast(`ex`.`FinalExamDate` as char charset utf8),5,2),substr(cast(`ex`.`FinalExamDate` as char charset utf8),7,2)) AS `O_FinalExamDate`,concat_ws('/',substr(cast(`st`.`StBurthDate` as char charset utf8),1,4),substr(cast(`st`.`StBurthDate` as char charset utf8),5,2),substr(cast(`st`.`StBurthDate` as char charset utf8),7,2)) AS `O_BurthDate`,
   `edarat`.`arabic_name` AS `O_Edarah`,
   `h`.`HName` AS `O_HName`,
   `e`.`murtaqa_name` AS `O_MurtaqaName`,
   `st`.`StMobileNo` AS `StMobileNo`,
   `st`.`FatherMobileNo` AS `FatherMobileNo`,
   `ex`.`RegesterTime` AS `RegesterTime`,
   `ex`.`ErtiqaID` AS `ErtiqaID`,
   `ex`.`HalakahID` AS `HalakahID`,
   `ex`.`StID` AS `StID`,
   `ex`.`TeacherID` AS `TeacherID`,
   `ex`.`EdarahID` AS `EdarahID`,
   `ex`.`TestExamDegree` AS `TestExamDegree`,
   `ex`.`TestExamDate` AS `TestExamDate`,
   `ex`.`FinalExamStatus` AS `FinalExamStatus`,
   `ex`.`FinalExamDate` AS `FinalExamDate`,
   `ex`.`AnswerTime` AS `AnswerTime`,
   `ex`.`H_SolokGrade` AS `H_SolokGrade`,
   `ex`.`H_MwadabahGrade` AS `H_MwadabahGrade`,
   `sh`.`Sora1Name` AS `Sora1Name`,
   `sh`.`ExamPoints` AS `ExamPoints`,
   `sh`.`Sora1Discount` AS `Sora1Discount`,
   `sh`.`Sora2Name` AS `Sora2Name`,
   `sh`.`Sora2Discount` AS `Sora2Discount`,
   `sh`.`Sora3Name` AS `Sora3Name`,
   `sh`.`Sora3Discount` AS `Sora3Discount`,
   `sh`.`Sora4Name` AS `Sora4Name`,
   `sh`.`Sora4Discount` AS `Sora4Discount`,
   `sh`.`Sora5Name` AS `Sora5Name`,
   `sh`.`Sora5Discount` AS `Sora5Discount`,
   `sh`.`Sora6Name` AS `Sora6Name`,
   `sh`.`Sora6Discount` AS `Sora6Discount`,
   `sh`.`Sora7Name` AS `Sora7Name`,
   `sh`.`Sora7Discount` AS `Sora7Discount`,
   `sh`.`Sora8Name` AS `Sora8Name`,
   `sh`.`Sora8Discount` AS `Sora8Discount`,
   `sh`.`Sora9Name` AS `Sora9Name`,
   `sh`.`Sora9Discount` AS `Sora9Discount`,
   `sh`.`Sora10Name` AS `Sora10Name`,
   `sh`.`Sora10Discount` AS `Sora10Discount`,
   `sh`.`Ek_mwathbah` AS `Ek_mwathbah`,
   `sh`.`Ek_slok` AS `Ek_slok`,
   `sh`.`MarkName_Short` AS `MarkName_Short`,
   `sh`.`MarkName_Long` AS `MarkName_Long`,
   `sh`.`Money` AS `Money`,
   `sh`.`teacher_money` AS `teacher_money`,
   `sh`.`edarah_money` AS `edarah_money`,
   `sh`.`Degree` AS `FinalExamDegree`,
   `sh`.`MukhtaberTeacher` AS `MukhtaberTeacher`,
   `sh`.`MukhtaberTeacher2` AS `MukhtaberTeacher2`,
   `t`.`hide` AS `teacher_hide`,
   `edarat`.`hidden` AS `edarah_hide`,
   `edarat`.`sex` AS `edarah_sex`
FROM ((((((`er_ertiqaexams` `ex` left join `0_students` `st` on((`ex`.`StID` = `st`.`StID`))) left join `0_teachers` `t` on((`ex`.`TeacherID` = `t`.`TID`))) left join `er_ertiqa_names` `e` on((`ex`.`ErtiqaID` = `e`.`murtaqa_id`))) left join `0_users` `edarat` on((`edarat`.`id` = `ex`.`EdarahID`))) left join `0_halakat` `h` on((`ex`.`HalakahID` = `h`.`AutoNo`))) left join `er_shahadah` `sh` on((`ex`.`AutoNo` = `sh`.`ExamNo`)));

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
