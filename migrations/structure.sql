/*
NovemBit i18n db structure
Database tables structures
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `i18n_translations` (
	`id` int (11),
	`type` int (11),
	`from_language` varchar (6),
	`to_language` varchar (6),
	`source` blob ,
	`translate` blob ,
	`level` int (11),
	`created_at` int (11),
	`updated_at` int (11)
);