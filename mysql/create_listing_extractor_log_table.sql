CREATE TABLE `listings_extractor_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resourceId` varchar(200) DEFAULT NULL,
  `url` varchar(700) DEFAULT NULL,
  `searchTerms` varchar(200) DEFAULT NULL,
  `checksum` varchar(45) DEFAULT NULL,
  `data` longtext,
  `realUrl` varchar(700) DEFAULT NULL,
  `realChecksum` varchar(45) DEFAULT NULL,
  `realData` longtext,
  `dateCreated` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This is where all cached import.io extractors will go';
