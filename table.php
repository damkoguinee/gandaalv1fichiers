<?php require '_header.php';

$bdd='activites';   

$DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nomact` varchar(50) NOT NULL,
    `mensualite` double DEFAULT '0',
    `promoact` int(4) DEFAULT '2023',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ");


$bdd='inscriptactivites';   

$DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `idact` int(2) NOT NULL,
    `matinscrit` varchar(50) NOT NULL,
    `mensualite` double DEFAULT '0',
    `remise` float DEFAULT '0',
    `promoact` int(4) DEFAULT '2023',
    `dateop` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ");


$bdd='activitespaiement'; 


$DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numeropaie` varchar(50) NOT NULL,
  `matp` varchar(50) NOT NULL,
  `elevetype` varchar(50) DEFAULT 'interne',
  `idact` int(11) NOT NULL,
  `moisp` varchar(50) NOT NULL,
  `montantf` double NOT NULL,
  `montantp` double NOT NULL,
  `anneep` int(11) NOT NULL,
  `dateop` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ");

$bdd='activitespaiehistorique'; 


$DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numeropaie` varchar(50) NOT NULL,
  `matp` varchar(50) NOT NULL,
  `elevetype` varchar(50) DEFAULT 'interne',
  `idact` int(11) NOT NULL,
  `moisp` varchar(50) NOT NULL,
  `montantf` double NOT NULL,
  `montantp` double NOT NULL,
  `remise` float DEFAULT NULL,
  `devise` varchar(10) NOT NULL DEFAULT 'gnf',
  `taux` float NOT NULL DEFAULT '1',
  `personnel` int(11) DEFAULT NULL,
  `anneep` int(11) NOT NULL,
  `modep` varchar(50) DEFAULT 'espèces',
  `caisse` int(11) NOT NULL,
  `numcheque` varchar(50) NOT NULL,
  `banquecheque` varchar(50) DEFAULT NULL,
  `promoact` int(11) NOT NULL,
  `dateop` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ");


$bdd='elevexterne'; 


$DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `matex` varchar(15) CHARACTER SET latin1 NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) CHARACTER SET latin1 NOT NULL,
  `sexe` varchar(1) CHARACTER SET latin1 NOT NULL,
  `naissance` date NOT NULL,
  `pere` varchar(50) CHARACTER SET latin1 NOT NULL,
  `mere` varchar(50) CHARACTER SET latin1 NOT NULL,
  `tel` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `telpere` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `telmere` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `dateenreg` datetime NOT NULL,
  `origine` varchar(100) DEFAULT NULL,
  `etat` varchar(50) NOT NULL DEFAULT 'actif',
  PRIMARY KEY (`id`),
  UNIQUE KEY `matex` (`matex`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ");


$bdd='categoriedep';


$DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ");


$bdd='categorievers';


$DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ");


$bdd='versement';


$DB->insert("CREATE TABLE IF NOT EXISTS `".$bdd."`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numcmd` varchar(10) DEFAULT NULL,
  `nom_client` varchar(155) NOT NULL,
  `montant` double NOT NULL,
  `devisevers` varchar(20) NOT NULL,
  `taux` float NOT NULL DEFAULT '1',
  `numcheque` varchar(50) DEFAULT NULL,
  `banquecheque` varchar(100) DEFAULT NULL,
  `categorie` int(2) NOT NULL,
  `motif` varchar(150) DEFAULT NULL,
  `type_versement` varchar(15) NOT NULL,
  `comptedep` varchar(50) DEFAULT NULL,
  `personnel` int(2) DEFAULT NULL,
  `date_versement` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ");



