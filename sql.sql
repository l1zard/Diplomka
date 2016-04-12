-- --------------------------------------------------------
-- Hostitel:                     127.0.0.1
-- Verze serveru:                5.6.24 - MySQL Community Server (GPL)
-- OS serveru:                   Win32
-- HeidiSQL Verze:               9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Exportování struktury databáze pro
DROP DATABASE IF EXISTS `sazkovka`;
CREATE DATABASE IF NOT EXISTS `sazkovka` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci */;
USE `sazkovka`;


-- Exportování struktury pro tabulka sazkovka.druh_platby
DROP TABLE IF EXISTS `druh_platby`;
CREATE TABLE IF NOT EXISTS `druh_platby` (
  `id_druh_platby` int(11) NOT NULL AUTO_INCREMENT,
  `typ_platby` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `popis` text COLLATE utf8_czech_ci,
  PRIMARY KEY (`id_druh_platby`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.druh_platby: ~2 rows (přibližně)
/*!40000 ALTER TABLE `druh_platby` DISABLE KEYS */;
INSERT INTO `druh_platby` (`id_druh_platby`, `typ_platby`, `popis`) VALUES
	(1, 'Platební karta', NULL),
	(2, 'Bankovní účet', NULL);
/*!40000 ALTER TABLE `druh_platby` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.hrac
DROP TABLE IF EXISTS `hrac`;
CREATE TABLE IF NOT EXISTS `hrac` (
  `id_hrac` int(11) NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `prijmeni` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `datum_narozeni` date NOT NULL,
  `id_klub` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_hrac`),
  KEY `IX_Relationship15` (`id_klub`),
  CONSTRAINT `Relationship15` FOREIGN KEY (`id_klub`) REFERENCES `klub` (`id_klub`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.hrac: ~0 rows (přibližně)
/*!40000 ALTER TABLE `hrac` DISABLE KEYS */;
/*!40000 ALTER TABLE `hrac` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.klub
DROP TABLE IF EXISTS `klub`;
CREATE TABLE IF NOT EXISTS `klub` (
  `id_klub` int(11) NOT NULL AUTO_INCREMENT,
  `nazev_klubu` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `stadion` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `rok_zalozeni` int(11) DEFAULT NULL,
  `webova_stranka` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `informace` text COLLATE utf8_czech_ci,
  `logo` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id_klub`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.klub: ~3 rows (přibližně)
/*!40000 ALTER TABLE `klub` DISABLE KEYS */;
INSERT INTO `klub` (`id_klub`, `nazev_klubu`, `stadion`, `rok_zalozeni`, `webova_stranka`, `informace`, `logo`) VALUES
	(1, 'Sparta Praha', 'Letná', 1893, 'www.sparta.cz', NULL, 'ac_sparta_praha.png'),
	(2, 'Plzeň', 'Štruncovy sady', 1911, 'www.fcviktoria.cz', '', 'plz.png'),
	(4, 'test', 'test', 1993, '', '', 'plz.png');
/*!40000 ALTER TABLE `klub` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.komentar
DROP TABLE IF EXISTS `komentar`;
CREATE TABLE IF NOT EXISTS `komentar` (
  `id_komentar` int(11) NOT NULL AUTO_INCREMENT,
  `datum_vlozeni` datetime NOT NULL,
  `text` text COLLATE utf8_czech_ci NOT NULL,
  `id_uzivatel` int(11) NOT NULL,
  `id_novinka` int(11) NOT NULL,
  PRIMARY KEY (`id_komentar`),
  KEY `IX_Relationship12` (`id_uzivatel`),
  KEY `IX_Relationship13` (`id_novinka`),
  CONSTRAINT `Relationship12` FOREIGN KEY (`id_uzivatel`) REFERENCES `uzivatel` (`id_uzivatel`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship13` FOREIGN KEY (`id_novinka`) REFERENCES `novinka` (`id_novinka`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.komentar: ~0 rows (přibližně)
/*!40000 ALTER TABLE `komentar` DISABLE KEYS */;
/*!40000 ALTER TABLE `komentar` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.liga
DROP TABLE IF EXISTS `liga`;
CREATE TABLE IF NOT EXISTS `liga` (
  `id_liga` int(11) NOT NULL AUTO_INCREMENT,
  `nazev_ligy` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `id_narodnost` int(11) DEFAULT NULL,
  `logo` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id_liga`),
  KEY `IX_Relationship21` (`id_narodnost`),
  CONSTRAINT `Relationship21` FOREIGN KEY (`id_narodnost`) REFERENCES `narodnost` (`id_narodnost`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.liga: ~2 rows (přibližně)
/*!40000 ALTER TABLE `liga` DISABLE KEYS */;
INSERT INTO `liga` (`id_liga`, `nazev_ligy`, `id_narodnost`, `logo`) VALUES
	(1, 'Synot Liga', 1, NULL),
	(2, 'Liga mistrů', 2, NULL);
/*!40000 ALTER TABLE `liga` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.narodnost
DROP TABLE IF EXISTS `narodnost`;
CREATE TABLE IF NOT EXISTS `narodnost` (
  `id_narodnost` int(11) NOT NULL AUTO_INCREMENT,
  `narodnost` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `zkratka` varchar(10) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id_narodnost`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.narodnost: ~2 rows (přibližně)
/*!40000 ALTER TABLE `narodnost` DISABLE KEYS */;
INSERT INTO `narodnost` (`id_narodnost`, `narodnost`, `zkratka`) VALUES
	(1, 'Česká Republika', 'CZ'),
	(2, 'Evropská unie', 'EU');
/*!40000 ALTER TABLE `narodnost` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.novinka
DROP TABLE IF EXISTS `novinka`;
CREATE TABLE IF NOT EXISTS `novinka` (
  `id_novinka` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `text` text COLLATE utf8_czech_ci NOT NULL,
  `komentare` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_novinka`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.novinka: ~0 rows (přibližně)
/*!40000 ALTER TABLE `novinka` DISABLE KEYS */;
/*!40000 ALTER TABLE `novinka` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.platba
DROP TABLE IF EXISTS `platba`;
CREATE TABLE IF NOT EXISTS `platba` (
  `id_platba` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vytvoreni_platby` datetime NOT NULL,
  `potvrzeni_platby` datetime DEFAULT NULL,
  `castka` int(11) NOT NULL,
  `id_uzivatel` int(11) NOT NULL,
  `id_druh_platby` int(11) NOT NULL,
  `id_stav` int(11) NOT NULL DEFAULT '1',
  `payId` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `paymentStatus` int(11) DEFAULT '1',
  PRIMARY KEY (`id_platba`),
  KEY `IX_Relationship9` (`id_uzivatel`),
  KEY `IX_Relationship10` (`id_druh_platby`),
  KEY `IX_Relationship11` (`id_stav`),
  CONSTRAINT `Relationship10` FOREIGN KEY (`id_druh_platby`) REFERENCES `druh_platby` (`id_druh_platby`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship11` FOREIGN KEY (`id_stav`) REFERENCES `stav` (`id_stav`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship9` FOREIGN KEY (`id_uzivatel`) REFERENCES `uzivatel` (`id_uzivatel`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=123475 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.platba: ~6 rows (přibližně)
/*!40000 ALTER TABLE `platba` DISABLE KEYS */;
INSERT INTO `platba` (`id_platba`, `vytvoreni_platby`, `potvrzeni_platby`, `castka`, `id_uzivatel`, `id_druh_platby`, `id_stav`, `payId`, `paymentStatus`) VALUES
	(123466, '2016-03-30 19:39:08', NULL, 1835, 2, 1, 1, 'da1be022e8119BC', 7),
	(123470, '2016-03-30 19:56:26', NULL, 123, 2, 1, 1, 'b000932f4b1f7BC', 1),
	(123471, '2016-03-30 19:57:26', NULL, 123, 2, 1, 1, '2954070b7d131BC', 3),
	(123472, '2016-03-30 20:01:36', NULL, 200, 2, 1, 1, 'fb0bc54304cceBC', 7),
	(123473, '2016-03-30 20:03:20', '2016-03-30 20:03:45', 100, 2, 1, 1, '890c69666542fBC', 7),
	(123474, '2016-03-31 02:12:25', '2016-03-31 02:12:28', 150, 2, 1, 1, 'e6873dd2c80f3BC', 3);
/*!40000 ALTER TABLE `platba` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.prilezitost
DROP TABLE IF EXISTS `prilezitost`;
CREATE TABLE IF NOT EXISTS `prilezitost` (
  `id_prilezitost` int(11) NOT NULL AUTO_INCREMENT,
  `kurz` decimal(10,2) DEFAULT NULL,
  `id_zapas` int(11) NOT NULL,
  `id_typ_prilezitosti` int(11) DEFAULT NULL,
  `id_stav_prilezitost` int(11) DEFAULT '1',
  PRIMARY KEY (`id_prilezitost`),
  KEY `IX_Relationship14` (`id_zapas`),
  KEY `IX_Relationship19` (`id_typ_prilezitosti`),
  KEY `IX_Relationship20` (`id_stav_prilezitost`),
  CONSTRAINT `Relationship14` FOREIGN KEY (`id_zapas`) REFERENCES `zapas` (`id_zapas`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship19` FOREIGN KEY (`id_typ_prilezitosti`) REFERENCES `typ_prilezitost` (`id_typ_prilezitost`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship20` FOREIGN KEY (`id_stav_prilezitost`) REFERENCES `stav_prilezitost` (`id_stav_prilezitost`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.prilezitost: ~6 rows (přibližně)
/*!40000 ALTER TABLE `prilezitost` DISABLE KEYS */;
INSERT INTO `prilezitost` (`id_prilezitost`, `kurz`, `id_zapas`, `id_typ_prilezitosti`, `id_stav_prilezitost`) VALUES
	(24, 2.00, 6, 2, 1),
	(25, 2.30, 6, 3, 1),
	(26, 3.00, 6, 1, 1),
	(27, 1.80, 7, 2, 1),
	(28, 2.90, 7, 1, 1),
	(29, 3.20, 7, 3, 1);
/*!40000 ALTER TABLE `prilezitost` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.prilezitost_tiketu
DROP TABLE IF EXISTS `prilezitost_tiketu`;
CREATE TABLE IF NOT EXISTS `prilezitost_tiketu` (
  `id_prilezitost` int(11) NOT NULL,
  `id_tiket` int(11) NOT NULL,
  PRIMARY KEY (`id_prilezitost`,`id_tiket`),
  KEY `Relationship4` (`id_tiket`),
  CONSTRAINT `Relationship3` FOREIGN KEY (`id_prilezitost`) REFERENCES `prilezitost` (`id_prilezitost`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship4` FOREIGN KEY (`id_tiket`) REFERENCES `tiket` (`id_tiket`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.prilezitost_tiketu: ~3 rows (přibližně)
/*!40000 ALTER TABLE `prilezitost_tiketu` DISABLE KEYS */;
INSERT INTO `prilezitost_tiketu` (`id_prilezitost`, `id_tiket`) VALUES
	(25, 1),
	(28, 1),
	(25, 2);
/*!40000 ALTER TABLE `prilezitost_tiketu` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.role
DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id_role` int(11) NOT NULL AUTO_INCREMENT,
  `nazev_role` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `popis_role` varchar(300) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.role: ~2 rows (přibližně)
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` (`id_role`, `nazev_role`, `popis_role`) VALUES
	(1, 'superadmin', 'superadmin'),
	(2, 'user', 'user');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.sezona
DROP TABLE IF EXISTS `sezona`;
CREATE TABLE IF NOT EXISTS `sezona` (
  `id_sezony` int(11) NOT NULL AUTO_INCREMENT,
  `sezona` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `aktivni` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_sezony`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.sezona: ~1 rows (přibližně)
/*!40000 ALTER TABLE `sezona` DISABLE KEYS */;
INSERT INTO `sezona` (`id_sezony`, `sezona`, `aktivni`) VALUES
	(1, '2015/2016', 1);
/*!40000 ALTER TABLE `sezona` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.stav
DROP TABLE IF EXISTS `stav`;
CREATE TABLE IF NOT EXISTS `stav` (
  `id_stav` int(11) NOT NULL AUTO_INCREMENT,
  `stav` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id_stav`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.stav: ~4 rows (přibližně)
/*!40000 ALTER TABLE `stav` DISABLE KEYS */;
INSERT INTO `stav` (`id_stav`, `stav`) VALUES
	(1, 'Vytvořena'),
	(2, 'Zaplacena'),
	(3, 'Zrušena'),
	(4, 'Vrácena');
/*!40000 ALTER TABLE `stav` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.stav_prilezitost
DROP TABLE IF EXISTS `stav_prilezitost`;
CREATE TABLE IF NOT EXISTS `stav_prilezitost` (
  `id_stav_prilezitost` int(11) NOT NULL AUTO_INCREMENT,
  `stav` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id_stav_prilezitost`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.stav_prilezitost: ~4 rows (přibližně)
/*!40000 ALTER TABLE `stav_prilezitost` DISABLE KEYS */;
INSERT INTO `stav_prilezitost` (`id_stav_prilezitost`, `stav`) VALUES
	(1, 'nevyhodnocena'),
	(2, 'vyhra'),
	(3, 'prohra'),
	(4, 'zrušena');
/*!40000 ALTER TABLE `stav_prilezitost` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.tabulka
DROP TABLE IF EXISTS `tabulka`;
CREATE TABLE IF NOT EXISTS `tabulka` (
  `id_klub` int(11) NOT NULL,
  `id_liga` int(11) NOT NULL,
  `id_sezony` int(11) NOT NULL,
  `vyhry` int(11) NOT NULL,
  `remizy` int(11) NOT NULL,
  `prohry` int(11) NOT NULL,
  `vstrelene_goly` int(11) NOT NULL,
  `obdrzene_goly` int(11) NOT NULL,
  `pocet_bodu` int(11) NOT NULL,
  PRIMARY KEY (`id_klub`,`id_liga`,`id_sezony`),
  KEY `Relationship2` (`id_liga`),
  KEY `FK_tabulka_sezona` (`id_sezony`),
  CONSTRAINT `FK_tabulka_sezona` FOREIGN KEY (`id_sezony`) REFERENCES `sezona` (`id_sezony`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship1` FOREIGN KEY (`id_klub`) REFERENCES `klub` (`id_klub`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship2` FOREIGN KEY (`id_liga`) REFERENCES `liga` (`id_liga`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.tabulka: ~4 rows (přibližně)
/*!40000 ALTER TABLE `tabulka` DISABLE KEYS */;
INSERT INTO `tabulka` (`id_klub`, `id_liga`, `id_sezony`, `vyhry`, `remizy`, `prohry`, `vstrelene_goly`, `obdrzene_goly`, `pocet_bodu`) VALUES
	(1, 1, 1, 0, 1, 0, 2, 2, 1),
	(1, 2, 1, 0, 0, 0, 0, 0, 0),
	(2, 1, 1, 0, 1, 0, 2, 2, 1),
	(2, 2, 1, 0, 0, 0, 0, 0, 0);
/*!40000 ALTER TABLE `tabulka` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.tiket
DROP TABLE IF EXISTS `tiket`;
CREATE TABLE IF NOT EXISTS `tiket` (
  `id_tiket` int(11) NOT NULL AUTO_INCREMENT,
  `castka` int(11) NOT NULL,
  `datum_vytvoreni` datetime NOT NULL,
  `datum_vyhodnoceni` datetime DEFAULT NULL,
  `vyhra` int(11) DEFAULT NULL,
  `celkovy_kurz` decimal(10,2) DEFAULT NULL,
  `id_uzivatel` int(11) NOT NULL,
  `id_stav` int(11) DEFAULT '1',
  PRIMARY KEY (`id_tiket`),
  KEY `IX_Relationship16` (`id_uzivatel`),
  KEY `FK_tiket_stav_prilezitost` (`id_stav`),
  CONSTRAINT `FK_tiket_stav_prilezitost` FOREIGN KEY (`id_stav`) REFERENCES `stav_prilezitost` (`id_stav_prilezitost`),
  CONSTRAINT `Relationship16` FOREIGN KEY (`id_uzivatel`) REFERENCES `uzivatel` (`id_uzivatel`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.tiket: ~2 rows (přibližně)
/*!40000 ALTER TABLE `tiket` DISABLE KEYS */;
INSERT INTO `tiket` (`id_tiket`, `castka`, `datum_vytvoreni`, `datum_vyhodnoceni`, `vyhra`, `celkovy_kurz`, `id_uzivatel`, `id_stav`) VALUES
	(1, 100, '2016-04-05 12:16:38', NULL, NULL, NULL, 2, 1),
	(2, 100, '2016-04-05 16:05:22', NULL, NULL, NULL, 2, 1);
/*!40000 ALTER TABLE `tiket` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.typ_prilezitost
DROP TABLE IF EXISTS `typ_prilezitost`;
CREATE TABLE IF NOT EXISTS `typ_prilezitost` (
  `id_typ_prilezitost` int(11) NOT NULL AUTO_INCREMENT,
  `typ` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id_typ_prilezitost`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.typ_prilezitost: ~3 rows (přibližně)
/*!40000 ALTER TABLE `typ_prilezitost` DISABLE KEYS */;
INSERT INTO `typ_prilezitost` (`id_typ_prilezitost`, `typ`) VALUES
	(1, 'remiza'),
	(2, 'domaci'),
	(3, 'hoste');
/*!40000 ALTER TABLE `typ_prilezitost` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.uzivatel
DROP TABLE IF EXISTS `uzivatel`;
CREATE TABLE IF NOT EXISTS `uzivatel` (
  `id_uzivatel` int(11) NOT NULL AUTO_INCREMENT,
  `uzivatelske_jmeno` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `heslo` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `datum_narozeni` date NOT NULL,
  `zustatek` int(11) NOT NULL DEFAULT '0',
  `jmeno` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `prijmeni` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `telefon` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `datum_registrace` date NOT NULL,
  `id_role` int(11) NOT NULL,
  PRIMARY KEY (`id_uzivatel`),
  UNIQUE KEY `uzivatelske_jmeno` (`uzivatelske_jmeno`),
  UNIQUE KEY `email` (`email`),
  KEY `IX_Relationship8` (`id_role`),
  CONSTRAINT `Relationship8` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.uzivatel: ~3 rows (přibližně)
/*!40000 ALTER TABLE `uzivatel` DISABLE KEYS */;
INSERT INTO `uzivatel` (`id_uzivatel`, `uzivatelske_jmeno`, `heslo`, `email`, `datum_narozeni`, `zustatek`, `jmeno`, `prijmeni`, `telefon`, `datum_registrace`, `id_role`) VALUES
	(1, 'superadmin', '955db0b81ef1989b4a4dfeae8061a9a6', 'superadmin@4win.cz', '0000-00-00', 0, 'Michal', 'Malý', '737474245', '0000-00-00', 1),
	(2, 'Lizardor', '955db0b81ef1989b4a4dfeae8061a9a6', 'lizardor@4win.cz', '1992-01-15', 1935, 'Michal', 'Malý', '737474245', '2016-02-09', 2),
	(3, 'Timonek', '955db0b81ef1989b4a4dfeae8061a9a6', 'timonek@4win.cz', '1991-12-31', 0, 'Jirka', 'Vácha', '742714536', '2016-02-09', 2);
/*!40000 ALTER TABLE `uzivatel` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.zapas
DROP TABLE IF EXISTS `zapas`;
CREATE TABLE IF NOT EXISTS `zapas` (
  `id_zapas` int(11) NOT NULL AUTO_INCREMENT,
  `datum_zapasu` datetime NOT NULL,
  `kolo` int(11) NOT NULL,
  `skore_domaci` int(11) DEFAULT NULL,
  `skore_hoste` int(11) DEFAULT NULL,
  `Informace` text COLLATE utf8_czech_ci,
  `id_hoste` int(11) NOT NULL,
  `id_klub` int(11) NOT NULL,
  `id_liga` int(11) NOT NULL DEFAULT '1',
  `zobrazit` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_zapas`),
  KEY `IX_Relationship17` (`id_hoste`),
  KEY `IX_Relationship18` (`id_klub`),
  KEY `id_liga` (`id_liga`),
  CONSTRAINT `FK_zapas_liga` FOREIGN KEY (`id_liga`) REFERENCES `liga` (`id_liga`),
  CONSTRAINT `Relationship17` FOREIGN KEY (`id_hoste`) REFERENCES `klub` (`id_klub`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship18` FOREIGN KEY (`id_klub`) REFERENCES `klub` (`id_klub`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.zapas: ~2 rows (přibližně)
/*!40000 ALTER TABLE `zapas` DISABLE KEYS */;
INSERT INTO `zapas` (`id_zapas`, `datum_zapasu`, `kolo`, `skore_domaci`, `skore_hoste`, `Informace`, `id_hoste`, `id_klub`, `id_liga`, `zobrazit`) VALUES
	(6, '2016-03-30 13:00:00', 6, 2, 2, '', 2, 1, 1, 1),
	(7, '2016-04-02 15:00:00', 3, NULL, NULL, '', 1, 2, 1, 1);
/*!40000 ALTER TABLE `zapas` ENABLE KEYS */;


-- Exportování struktury pro tabulka sazkovka.zprava
DROP TABLE IF EXISTS `zprava`;
CREATE TABLE IF NOT EXISTS `zprava` (
  `id_zprava` int(11) NOT NULL AUTO_INCREMENT,
  `datum_zpravy` datetime NOT NULL,
  `text` longtext COLLATE utf8_czech_ci NOT NULL,
  `id_odesilatel` int(11) NOT NULL,
  `id_prijemce` int(11) NOT NULL,
  PRIMARY KEY (`id_zprava`),
  KEY `IX_Relationship5` (`id_odesilatel`),
  KEY `IX_Relationship6` (`id_odesilatel`),
  KEY `IX_Relationship7` (`id_prijemce`),
  CONSTRAINT `Relationship5` FOREIGN KEY (`id_odesilatel`) REFERENCES `uzivatel` (`id_uzivatel`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship6` FOREIGN KEY (`id_odesilatel`) REFERENCES `uzivatel` (`id_uzivatel`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Relationship7` FOREIGN KEY (`id_prijemce`) REFERENCES `uzivatel` (`id_uzivatel`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- Exportování dat pro tabulku sazkovka.zprava: ~0 rows (přibližně)
/*!40000 ALTER TABLE `zprava` DISABLE KEYS */;
/*!40000 ALTER TABLE `zprava` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
