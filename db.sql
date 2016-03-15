-- Adminer 4.2.2fx MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `druh_platby`;
CREATE TABLE `druh_platby` (
  `id_druh_platby` int(11) NOT NULL AUTO_INCREMENT,
  `typ_platby` varchar(100) NOT NULL,
  `popis` text,
  PRIMARY KEY (`id_druh_platby`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `hrac`;
CREATE TABLE `hrac` (
  `id_hrac` int(11) NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(20) NOT NULL,
  `prijmeni` varchar(20) NOT NULL,
  `datum_narozeni` date NOT NULL,
  `id_klub` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_hrac`),
  KEY `IX_Relationship15` (`id_klub`),
  CONSTRAINT `Relationship15` FOREIGN KEY (`id_klub`) REFERENCES `klub` (`id_klub`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `klub`;
CREATE TABLE `klub` (
  `id_klub` int(11) NOT NULL AUTO_INCREMENT,
  `nazev_klubu` varchar(100) NOT NULL,
  `stadion` varchar(100) DEFAULT NULL,
  `rok_zalozeni` int(11) DEFAULT NULL,
  `webova_stranka` varchar(100) DEFAULT NULL,
  `informace` text,
  PRIMARY KEY (`id_klub`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `komentar`;
CREATE TABLE `komentar` (
  `id_komentar` int(11) NOT NULL AUTO_INCREMENT,
  `datum_vlozeni` datetime NOT NULL,
  `text` text NOT NULL,
  `id_uzivatel` int(11) NOT NULL,
  `id_novinka` int(11) NOT NULL,
  PRIMARY KEY (`id_komentar`),
  KEY `IX_Relationship12` (`id_uzivatel`),
  KEY `IX_Relationship13` (`id_novinka`),
  CONSTRAINT `Relationship12` FOREIGN KEY (`id_uzivatel`) REFERENCES `uzivatel` (`id_uzivatel`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship13` FOREIGN KEY (`id_novinka`) REFERENCES `novinka` (`id_novinka`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `liga`;
CREATE TABLE `liga` (
  `id_liga` int(11) NOT NULL AUTO_INCREMENT,
  `nazev_ligy` varchar(100) NOT NULL,
  `sezona` varchar(20) NOT NULL,
  PRIMARY KEY (`id_liga`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `novinka`;
CREATE TABLE `novinka` (
  `id_novinka` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(200) NOT NULL,
  `text` text NOT NULL,
  `komentare` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_novinka`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `platba`;
CREATE TABLE `platba` (
  `id_platba` int(11) NOT NULL AUTO_INCREMENT,
  `datum_platby` datetime NOT NULL,
  `castka` int(11) NOT NULL,
  `id_uzivatel` int(11) NOT NULL,
  `id_druh_platby` int(11) NOT NULL,
  `id_stav` int(11) NOT NULL,
  PRIMARY KEY (`id_platba`),
  KEY `IX_Relationship9` (`id_uzivatel`),
  KEY `IX_Relationship10` (`id_druh_platby`),
  KEY `IX_Relationship11` (`id_stav`),
  CONSTRAINT `Relationship10` FOREIGN KEY (`id_druh_platby`) REFERENCES `druh_platby` (`id_druh_platby`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship11` FOREIGN KEY (`id_stav`) REFERENCES `stav` (`id_stav`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship9` FOREIGN KEY (`id_uzivatel`) REFERENCES `uzivatel` (`id_uzivatel`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `prilezitost`;
CREATE TABLE `prilezitost` (
  `id_prilezitost` int(11) NOT NULL AUTO_INCREMENT,
  `kurz` decimal(10,2) DEFAULT NULL,
  `id_zapas` int(11) NOT NULL,
  PRIMARY KEY (`id_prilezitost`),
  KEY `IX_Relationship14` (`id_zapas`),
  CONSTRAINT `Relationship14` FOREIGN KEY (`id_zapas`) REFERENCES `zapas` (`id_zapas`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `prilezitost_tiketu`;
CREATE TABLE `prilezitost_tiketu` (
  `id_prilezitost` int(11) NOT NULL,
  `id_tiket` int(11) NOT NULL,
  PRIMARY KEY (`id_prilezitost`,`id_tiket`),
  KEY `Relationship4` (`id_tiket`),
  CONSTRAINT `Relationship3` FOREIGN KEY (`id_prilezitost`) REFERENCES `prilezitost` (`id_prilezitost`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship4` FOREIGN KEY (`id_tiket`) REFERENCES `tiket` (`id_tiket`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `nazev_role` varchar(100) NOT NULL,
  `popis_role` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `role` (`role_id`, `nazev_role`, `popis_role`) VALUES
(1,	'superadmin',	'full_authorization');

DROP TABLE IF EXISTS `stav`;
CREATE TABLE `stav` (
  `id_stav` int(11) NOT NULL AUTO_INCREMENT,
  `stav` varchar(100) NOT NULL,
  PRIMARY KEY (`id_stav`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tabulka`;
CREATE TABLE `tabulka` (
  `id_klub` int(11) NOT NULL,
  `id_liga` int(11) NOT NULL,
  `vyhry` int(11) NOT NULL,
  `remizy` int(11) NOT NULL,
  `prohry` int(11) NOT NULL,
  `vstrelene_goly` int(11) NOT NULL,
  `obdrzene_goly` int(11) NOT NULL,
  PRIMARY KEY (`id_klub`,`id_liga`),
  KEY `Relationship2` (`id_liga`),
  CONSTRAINT `Relationship1` FOREIGN KEY (`id_klub`) REFERENCES `klub` (`id_klub`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship2` FOREIGN KEY (`id_liga`) REFERENCES `liga` (`id_liga`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tiket`;
CREATE TABLE `tiket` (
  `id_tiket` int(11) NOT NULL AUTO_INCREMENT,
  `castka` int(11) NOT NULL,
  `datum_vytvoreni` datetime NOT NULL,
  `datum_vyhodnoceni` datetime DEFAULT NULL,
  `vyhra` int(11) DEFAULT NULL,
  `celkovy_kurz` decimal(10,2) DEFAULT NULL,
  `id_uzivatel` int(11) NOT NULL,
  PRIMARY KEY (`id_tiket`),
  KEY `IX_Relationship16` (`id_uzivatel`),
  CONSTRAINT `Relationship16` FOREIGN KEY (`id_uzivatel`) REFERENCES `uzivatel` (`id_uzivatel`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `uzivatel`;
CREATE TABLE `uzivatel` (
  `id_uzivatel` int(11) NOT NULL AUTO_INCREMENT,
  `uzivatelske_jmeno` varchar(50) NOT NULL,
  `heslo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `datum_narozeni` date NOT NULL,
  `zustatek` int(11) NOT NULL DEFAULT '0',
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id_uzivatel`),
  UNIQUE KEY `uzivatelske_jmeno` (`uzivatelske_jmeno`),
  UNIQUE KEY `email` (`email`),
  KEY `IX_Relationship8` (`role_id`),
  CONSTRAINT `uzivatel_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `uzivatel` (`id_uzivatel`, `uzivatelske_jmeno`, `heslo`, `email`, `datum_narozeni`, `zustatek`, `role_id`) VALUES
(1,	'superadmin',	'955db0b81ef1989b4a4dfeae8061a9a6',	'superadmin@sazkovka.cz',	'0000-00-00',	0,	1);

DROP TABLE IF EXISTS `zapas`;
CREATE TABLE `zapas` (
  `id_zapas` int(11) NOT NULL AUTO_INCREMENT,
  `datum_zapasu` datetime NOT NULL,
  `kolo` int(11) NOT NULL,
  `skore_domaci` int(11) DEFAULT NULL,
  `skore_hoste` int(11) DEFAULT NULL,
  `Informace` text,
  `id_hoste` int(11) NOT NULL,
  `id_klub` int(11) NOT NULL,
  PRIMARY KEY (`id_zapas`),
  KEY `IX_Relationship17` (`id_hoste`),
  KEY `IX_Relationship18` (`id_klub`),
  CONSTRAINT `Relationship17` FOREIGN KEY (`id_hoste`) REFERENCES `klub` (`id_klub`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship18` FOREIGN KEY (`id_klub`) REFERENCES `klub` (`id_klub`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `zprava`;
CREATE TABLE `zprava` (
  `id_zprava` int(11) NOT NULL AUTO_INCREMENT,
  `datum_zpravy` datetime NOT NULL,
  `text` longtext NOT NULL,
  `id_odesilatel` int(11) NOT NULL,
  `id_prijemce` int(11) NOT NULL,
  PRIMARY KEY (`id_zprava`),
  KEY `IX_Relationship5` (`id_odesilatel`),
  KEY `IX_Relationship6` (`id_odesilatel`),
  KEY `IX_Relationship7` (`id_prijemce`),
  CONSTRAINT `Relationship5` FOREIGN KEY (`id_odesilatel`) REFERENCES `uzivatel` (`id_uzivatel`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship6` FOREIGN KEY (`id_odesilatel`) REFERENCES `uzivatel` (`id_uzivatel`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship7` FOREIGN KEY (`id_prijemce`) REFERENCES `uzivatel` (`id_uzivatel`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2015-10-30 06:30:12