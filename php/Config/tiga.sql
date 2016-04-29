-- Adminer 4.2.4 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) DEFAULT NULL,
  `password` varchar(300) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `token` varchar(200) DEFAULT NULL,
  `life` int(11) unsigned DEFAULT NULL,
  `level` varchar(45) DEFAULT NULL COMMENT '[A]dmin, [E]ditor, [G]uest',
  `status` char(1) DEFAULT NULL COMMENT '[A]ctive, [D]isable',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user` (`id`, `login`, `password`, `name`, `token`, `life`, `level`, `status`) VALUES
(1, 'admin', 'admin#123', 'Administrator', '4jK*$FoC:gbOe])wIHs(+:<QMUzb{nLzxOOi<%[8', 0, 'A', 'A'),
(2,	'editor', 'editor#123', 'Editor', 'v!BPf#hePX30Ks>GK~çjj', 0, 'E', 'A'),
(5,	'guest', 'guest#123',	'Guest', 'IK:QernGB9azWQuh-A6BD', 0, 'G', 'A'),
(6,	'test', 'test#123',	'Disabled User', '![vw*$3lP_z!#IVfe.#n8NiTKBE8<F*=40/B5@tq',	0, 'A', 'D');