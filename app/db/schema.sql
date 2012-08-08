-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.24-0ubuntu0.12.04.1 - (Ubuntu)
-- Server OS:                    debian-linux-gnu
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2012-08-07 10:35:31
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping structure for table Solas-Match-test.archived_task
CREATE TABLE IF NOT EXISTS `archived_task` (
  `archived_task_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` bigint(20) NOT NULL,
  `organisation_id` int(10) unsigned NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `word_count` int(10) unsigned DEFAULT NULL,
  `source_id` int(10) unsigned DEFAULT NULL COMMENT 'foreign key from the `language` table',
  `target_id` int(10) unsigned DEFAULT NULL COMMENT 'foreign key from the `language` table',
  `created_time` datetime NOT NULL,
  `archived_time` datetime NOT NULL,
  PRIMARY KEY (`archived_task_id`),
  KEY `source` (`source_id`),
  KEY `target` (`target_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `archived_task`
	COLLATE='utf8_unicode_ci',
	ENGINE=MyISAM,
	CONVERT TO CHARSET utf8;

-- Dumping data for table Solas-Match-test.archived_task: 0 rows
/*!40000 ALTER TABLE `archived_task` DISABLE KEYS */;
/*!40000 ALTER TABLE `archived_task` ENABLE KEYS */;


-- Dumping structure for table Solas-Match-test.badges
CREATE TABLE IF NOT EXISTS `badges` (
  `badge_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`badge_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `badges`
	COLLATE='utf8_unicode_ci',
	ENGINE=MyISAM,
	CONVERT TO CHARSET utf8;

-- Dumping data for table Solas-Match-test.badges: ~3 rows (approximately)
/*!40000 ALTER TABLE `badges` DISABLE KEYS */;
REPLACE INTO `badges` (`badge_id`, `title`, `description`) VALUES
	(3, 'Profile-Filler', 'Filled in required info for user profile.'),
	(4, 'Registered', 'Successfully set up an account'),
	(5, 'Native-Language', 'Filled in your native language on your user profile.');
/*!40000 ALTER TABLE `badges` ENABLE KEYS */;






-- Dumping structure for table Solas-Match-test.language
CREATE TABLE IF NOT EXISTS `language` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT '"en", for example',
  `en_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '"English", for example',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `language`
	COLLATE='utf8_unicode_ci',
	ENGINE=MyISAM,
	CONVERT TO CHARSET utf8;

-- Dumping data for table Solas-Match-test.language: 0 rows
/*!40000 ALTER TABLE `language` DISABLE KEYS */;
/*!40000 ALTER TABLE `language` ENABLE KEYS */;


-- Dumping structure for table Solas-Match-test.old_task_file
CREATE TABLE IF NOT EXISTS `old_task_file` (
  `task_id` bigint(20) unsigned NOT NULL,
  `file_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `path` text COLLATE utf8_unicode_ci NOT NULL,
  `filename` text COLLATE utf8_unicode_ci NOT NULL,
  `content_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Mime type',
  `user_id` int(11) DEFAULT NULL COMMENT 'Can be null while users table is empty! Remove this option once logins working',
  `upload_time` datetime NOT NULL,
  PRIMARY KEY (`task_id`,`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `old_task_file`
	COLLATE='utf8_unicode_ci',
	ENGINE=MyISAM,
	CONVERT TO CHARSET utf8;

-- Dumping data for table Solas-Match-test.old_task_file: 0 rows
/*!40000 ALTER TABLE `old_task_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `old_task_file` ENABLE KEYS */;


-- Dumping structure for table Solas-Match-test.organisation
CREATE TABLE IF NOT EXISTS `organisation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `home_page` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `biography` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`home_page`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `organisation`
	CHANGE COLUMN `name` `name` VARCHAR(128) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci' AFTER `id`,
	CHANGE COLUMN `home_page` `home_page` VARCHAR(128) NOT NULL COLLATE 'utf8_unicode_ci' AFTER `name`,
	CHANGE COLUMN `biography` `biography` VARCHAR(255) NOT NULL COLLATE 'utf8_unicode_ci' AFTER `home_page`,
	ADD UNIQUE INDEX (`name`, `home_page`);

ALTER TABLE `organisation`
	COLLATE='utf8_unicode_ci',
	ENGINE=MyISAM,
	CONVERT TO CHARSET utf8;

-- Dumping data for table Solas-Match-test.organisation: 0 rows
/*!40000 ALTER TABLE `organisation` DISABLE KEYS */;
/*!40000 ALTER TABLE `organisation` ENABLE KEYS */;





-- Dumping structure for table Solas-Match-test.organisation_member
CREATE TABLE IF NOT EXISTS `organisation_member` (
  `user_id` int(10) unsigned NOT NULL,
  `organisation_id` int(10) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `organisation_member`
	COLLATE='utf8_unicode_ci',
	ENGINE=MyISAM,
	CONVERT TO CHARSET utf8;

-- Dumping data for table Solas-Match-test.organisation_member: 0 rows
/*!40000 ALTER TABLE `organisation_member` DISABLE KEYS */;
/*!40000 ALTER TABLE `organisation_member` ENABLE KEYS */;


-- --------------------------------------------------------

--
-- Table structure for table `org_request_queue`
--

CREATE TABLE IF NOT EXISTS `org_request_queue` (
  `request_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `request_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`request_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;
ALTER TABLE `org_request_queue`
	COLLATE=`utf8_unicode_ci`,
	ENGINE=MyISAM,
	CONVERT TO CHARSET utf8;

-- --------------------------------------------------------


-- Dumping structure for table Solas-Match-test.tag
CREATE TABLE IF NOT EXISTS `tag` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `tag`
	COLLATE='utf8_unicode_ci',
	ENGINE=MyISAM,
	CONVERT TO CHARSET utf8;

-- Dumping data for table Solas-Match-test.tag: 0 rows
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;


-- Dumping structure for table Solas-Match-test.task
CREATE TABLE IF NOT EXISTS `task` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` int(10) unsigned NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `word_count` int(10) unsigned DEFAULT NULL,
  `source_id` int(10) unsigned DEFAULT NULL COMMENT 'foreign key from the `language` table',
  `target_id` int(10) unsigned DEFAULT NULL COMMENT 'foreign key from the `language` table',
  `created_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `source` (`source_id`),
  KEY `target` (`target_id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `task`
	COLLATE='utf8_unicode_ci',
	ENGINE=MyISAM,
	CONVERT TO CHARSET utf8;
-- Dumping data for table Solas-Match-test.task: 0 rows
/*!40000 ALTER TABLE `task` DISABLE KEYS */;
/*!40000 ALTER TABLE `task` ENABLE KEYS */;


-- Dumping structure for table Solas-Match-test.task_claim
CREATE TABLE IF NOT EXISTS `task_claim` (
  `claim_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `claimed_time` datetime NOT NULL,
  PRIMARY KEY (`claim_id`),
  KEY `task_user` (`task_id`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `task_claim`
	COLLATE='utf8_unicode_ci',
	ENGINE=MyISAM,
	CONVERT TO CHARSET utf8;

-- Dumping data for table Solas-Match-test.task_claim: 0 rows
/*!40000 ALTER TABLE `task_claim` DISABLE KEYS */;
/*!40000 ALTER TABLE `task_claim` ENABLE KEYS */;


-- Dumping structure for table Solas-Match-test.task_file_version
CREATE TABLE IF NOT EXISTS `task_file_version` (
  `task_id` bigint(20) NOT NULL,
  `version_id` int(11) NOT NULL COMMENT 'Gets incremented within the code',
  `filename` text COLLATE utf8_unicode_ci NOT NULL,
  `content_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'Null while we don''t have logging in',
  `upload_time` datetime NOT NULL,
  KEY `task_id` (`task_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `task_file_version`
	COLLATE='utf8_unicode_ci',
	ENGINE=MyISAM,
	CONVERT TO CHARSET utf8;

-- Dumping data for table Solas-Match-test.task_file_version: 0 rows
/*!40000 ALTER TABLE `task_file_version` DISABLE KEYS */;
/*!40000 ALTER TABLE `task_file_version` ENABLE KEYS */;


-- Dumping structure for table Solas-Match-test.task_file_version_download
CREATE TABLE IF NOT EXISTS `task_file_version_download` (
  `task_id` bigint(20) unsigned NOT NULL,
  `file_id` int(10) unsigned NOT NULL,
  `version_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `time_downloaded` datetime NOT NULL,
  KEY `task_id` (`task_id`,`file_id`,`version_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `task_file_version_download`
	COLLATE='utf8_unicode_ci',
	ENGINE=MyISAM,
	CONVERT TO CHARSET utf8;

-- Dumping data for table Solas-Match-test.task_file_version_download: 0 rows
/*!40000 ALTER TABLE `task_file_version_download` DISABLE KEYS */;
/*!40000 ALTER TABLE `task_file_version_download` ENABLE KEYS */;


-- Dumping structure for table Solas-Match-test.task_tag
CREATE TABLE IF NOT EXISTS `task_tag` (
  `task_id` bigint(20) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `task_tag` (`task_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `task_tag`
	COLLATE='utf8_unicode_ci',
	ENGINE=MyISAM,
	CONVERT TO CHARSET utf8;

-- Dumping data for table Solas-Match-test.task_tag: 0 rows
/*!40000 ALTER TABLE `task_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `task_tag` ENABLE KEYS */;


-- Dumping structure for table Solas-Match-test.translator
CREATE TABLE IF NOT EXISTS `translator` (
  `user_id` int(11) NOT NULL,
  `role_added` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `translator`
	COLLATE='utf8_unicode_ci',
	ENGINE=MyISAM,
	CONVERT TO CHARSET utf8;

-- Dumping data for table Solas-Match-test.translator: 0 rows
/*!40000 ALTER TABLE `translator` DISABLE KEYS */;
/*!40000 ALTER TABLE `translator` ENABLE KEYS */;


-- Dumping structure for table Solas-Match-test.user
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `display_name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(128) COLLATE utf8_unicode_ci NOT NULL,
  `biography` text COLLATE utf8_unicode_ci,
  `native_language` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nonce` int(11) unsigned NOT NULL,
  `created_time` datetime NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `user`
	COLLATE='utf8_unicode_ci',
	ENGINE=MyISAM,
	CONVERT TO CHARSET utf8;




-- Dumping structure for table Solas-Match-test.user_badges
CREATE TABLE IF NOT EXISTS `user_badges` (
  `user_id` int(11) NOT NULL,
  `badge_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`badge_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `user_badges`
	COLLATE='utf8_unicode_ci',
	ENGINE=MyISAM,
	CONVERT TO CHARSET utf8;
-- Dumping data for table Solas-Match-test.user_badges: ~0 rows (approximately)
/*!40000 ALTER TABLE `user_badges` DISABLE KEYS */;
REPLACE INTO `user_badges` (`user_id`, `badge_id`) VALUES
	(45, 4);
/*!40000 ALTER TABLE `user_badges` ENABLE KEYS */;


-- Dumping structure for table Solas-Match-test.user_tag
CREATE TABLE IF NOT EXISTS `user_tag` (
  `user_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `user_tag`
	COLLATE='utf8_unicode_ci',
	ENGINE=MyISAM,
	CONVERT TO CHARSET utf8;

-- Dumping data for table Solas-Match-test.user_tag: ~0 rows (approximately)
/*!40000 ALTER TABLE `user_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_tag` ENABLE KEYS */;


-- Dumping structure for table Solas-Match-test.user_task_score
CREATE TABLE IF NOT EXISTS `user_task_score` (
  `user_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `score` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`user_id`,`task_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `user_task_score`
	COLLATE='utf8_unicode_ci',
	ENGINE=MyISAM,
	CONVERT TO CHARSET utf8;

-- Dumping data for table Solas-Match-test.user_task_score: ~0 rows (approximately)
/*!40000 ALTER TABLE `user_task_score` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_task_score` ENABLE KEYS */;


-- Dumping structure for procedure Solas-Match-test.findOganisation
DROP PROCEDURE IF EXISTS `findOganisation`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `findOganisation`(IN `id` INT)
    COMMENT 'finds an organisation by the data passed in.'
BEGIN
	SELECT *
	FROM organisation o
	WHERE o.id=id; 
END//
DELIMITER ;


-- Dumping structure for procedure Solas-Match-test.findOrganisationsUserBelongsTo
DROP PROCEDURE IF EXISTS `findOrganisationsUserBelongsTo`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `findOrganisationsUserBelongsTo`(IN `id` INT)
BEGIN
	SELECT organisation_id
	FROM organisation_member
	WHERE user_id = id;
END//
DELIMITER ;


-- Dumping structure for procedure Solas-Match-test.getOrgByUser
DROP PROCEDURE IF EXISTS `getOrgByUser`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `getOrgByUser`(IN `id` INT)
BEGIN
	SELECT *
	FROM organisation o
	WHERE o.id IN (SELECT organisation_id
						 FROM organisation_member
					 	 WHERE user_id=id); 
END//
DELIMITER ;


-- Dumping structure for procedure Solas-Match-test.getOrgMembers
DROP PROCEDURE IF EXISTS `getOrgMembers`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `getOrgMembers`(IN `id` INT)
BEGIN
	SELECT user_id
	FROM organisation_member 
	WHERE organisation_id=id;
END//
DELIMITER ;


-- Dumping structure for procedure Solas-Match-test.getUserBadges
DROP PROCEDURE IF EXISTS `getUserBadges`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserBadges`(IN `id` INT)
BEGIN
SELECT badge_id
FROM user_badges
WHERE user_id = id;
END//
DELIMITER ;


-- Dumping structure for procedure Solas-Match-test.getUserTags
DROP PROCEDURE IF EXISTS `getUserTags`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserTags`(IN `id` INT)
BEGIN
	SELECT label
	FROM user_tag
	JOIN tag ON user_tag.tag_id = tag.tag_id
	WHERE user_id = id; 
END//
DELIMITER ;


-- Dumping structure for procedure Solas-Match-test.organisationInsertAndUpdate
DROP PROCEDURE IF EXISTS `organisationInsertAndUpdate`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `organisationInsertAndUpdate`(IN `id` INT(10), IN `url` TEXT, IN `companyName` VARCHAR(255), IN `bio` TEXT)
BEGIN
	if id='' then set id=null;end if;
	if url='' then set url=null;end if;
	if companyName='' then set companyName=null;end if;
	if bio='' then set bio=null;end if;

	
	if id is null and not exists(select * from organisation o where (o.home_page= url or o.home_page= concat("http://",url) ) and o.name=companyName)then
	-- set insert
	insert into organisation (name,home_page, biography) values (companyName,url,bio);

	else 
		set @first = true;
		set @q= "update organisation o set ";-- set update
		if bio is not null then 
#set paramaters to be updated
			set @q = CONCAT(@q," o.biography='",bio,"'") ;
			set @first = false;
		end if;
		if url is not null then 
			if (@first = false) then 
				set @q = CONCAT(@q,",");
				set @first = false;
			end if;
			set @q = CONCAT(@q," o.home_page='",url,"'") ;
		end if;
		if companyName is not null then 
			if (@first = false) then 
				set @q = CONCAT(@q,",");
				set @first = false;
			end if;
			set @q = CONCAT(@q," o.name='",companyName,"'") ;
		end if;
	
#		set where
		if id is not null then 
			set @q = CONCAT(@q," where  o.id= ",id);
		elseif url is not null and companyName is not null then 
			set @q = CONCAT(@q," where o.home_page='",url,"' and o.name='",companyName,"'");
		end if;
	PREPARE stmt FROM @q;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
#
	end if;
	
	select o.id as 'result' from organisation o where (o.home_page= url or o.home_page= concat("http://",url) ) and o.name=companyName;
END//
DELIMITER ;


-- Dumping structure for procedure Solas-Match-test.removeUserTag
DROP PROCEDURE IF EXISTS `removeUserTag`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `removeUserTag`(IN `id` INT, IN `tagID` INT)
    COMMENT 'unsubscripse a user for the given tag'
BEGIN
	if EXISTS(  SELECT user_id, tag_id
	                FROM user_tag
	                WHERE user_id = id
	                AND tag_id = tagID) then                 
		DELETE 	FROM user_tag	WHERE user_id=id AND tag_id =tagID; 
		select 1 as 'result';
	else
	select 0 as 'result';
	end if;
END//
DELIMITER ;


-- Dumping structure for procedure Solas-Match-test.userFindByUserData
DROP PROCEDURE IF EXISTS `userFindByUserData`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `userFindByUserData`(IN `id` INT, IN `pass` VARBINARY(128), IN `email` VARCHAR(256), IN `role` TINYINT)
BEGIN
	if(id is not null and pass is not null) then
		select * from user where user_id = id and password= pass;
   elseif(id is not null and role=1) then
		select * from user where user_id = id and EXISTS (select * from organisation_member where user_id = id);
	elseif(id is not null) then
 		select * from user where user_id = id;
   elseif (email is not null) then
   	select * from user u where u.email = email;
	end if;
END//
DELIMITER ;


-- Dumping structure for procedure Solas-Match-test.userInsertAndUpdate
DROP PROCEDURE IF EXISTS `userInsertAndUpdate`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `userInsertAndUpdate`(IN `email` VARCHAR(256), IN `nonce` int(11), IN `pass` char(128), IN `bio` TEXT, IN `name` VARCHAR(128), IN `lang` VARCHAR(256), IN `id` INT)
    COMMENT 'adds a user if it dosent exists. updates it if it allready exisits.'
BEGIN
	if pass='' then set pass=null;end if;
	if bio='' then set bio=null;end if;
	if id='' then set id=null;end if;
	if nonce='' then set nonce=null;end if;
	if name='' then set name=null;end if;
	if email='' then set email=null;end if;
	if lang='' then set lang=null;end if;
	
	if id is null and not exists(select * from user u where u.email= email)then
	-- set insert
	insert into user (email,nonce,password,created_time,display_name,biography,native_language) values (email,nonce,pass,NOW(),name,bio,lang);
#	set @q="insert into user (email,nonce,password,created_time,display_name,biography,native_language) values ('"+email+"',"+nonce+",'"+pass+"',"+NOW()+",'"+name+"','"+bio+"','"+lang+"');";
	else 
		set @first = true;
		set @q= "update user u set ";-- set update
		if bio is not null then 
#set paramaters to be updated
			set @q = CONCAT(@q," u.biography='",bio,"'") ;
			set @first = false;
		end if;
		if lang is not null then 
			if (@first = false) then 
				set @q = CONCAT(@q,",");
				set @first = false;
			end if;
			set @q = CONCAT(@q," u.native_language='",lang,"'") ;
		end if;
		if name is not null then 
				if (@first = false) then 
				set @q = CONCAT(@q,",");
				set @first = false;
			end if;
			set @q = CONCAT(@q," u.display_name='",name,"'");
		
		end if;
		
		if email is not null then 
			if (@first = false) then 
				set @q = CONCAT(@q,",");
				set @first = false;
			end if;
			set @q = CONCAT(@q," u.email='",email,"'");
		
		end if;
		if nonce is not null then 
			if (@first = false) then 
				set @q = CONCAT(@q,",");
				set @first = false;
			end if;
			set @q = CONCAT(@q," u.nonce=",nonce) ;
		
		end if;
		
		if pass is not null then 
			if (@first = false) then 
				set @q = CONCAT(@q,",");
				set @first = false;
			end if;
			set @q = CONCAT(@q," u.password='",pass,"'");
		
		end if;
#		set where
	
		if id is not null then 
			set @q = CONCAT(@q," where  u.user_id= ",id);
#    	allows email to be changed but not user_id
		
		elseif email is not null then 
			set @q = CONCAT(@q," where  u.email= ,",email,"'");-- allows anything but email and user_id to change
		else
			set @q = CONCAT(@q," where  u.email= null AND u.user_id=null");-- will always fail to update anyting
		end if;
	PREPARE stmt FROM @q;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	end if;
	
	select u.user_id from user u where u.email= email;
END//
DELIMITER ;


-- Dumping structure for procedure Solas-Match-test.userLikeTag
DROP PROCEDURE IF EXISTS `userLikeTag`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `userLikeTag`(IN `id` INT, IN `tagID` INT)
BEGIN
	if not EXISTS(  SELECT user_id, tag_id
	                FROM user_tag
	                WHERE user_id = id
	                AND tag_id = tagID) then                 
		INSERT INTO user_tag (user_id, tag_id)VALUES (id,tagID);
		select 1 as 'result';
	else
	select 0 as 'result';
	end if;
END//
DELIMITER ;


-- Dumping structure for trigger Solas-Match-test.validateHomepageInsert
DROP TRIGGER IF EXISTS `validateHomepageInsert`;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `validateHomepageInsert` BEFORE INSERT ON `organisation` FOR EACH ROW BEGIN
	if (new.home_page not like "http://*" OR new.home_page not like "https://*") then
	set new.home_page = concat("http://",new.home_page);
	end if;
END//
DELIMITER ;
SET SQL_MODE=@OLD_SQL_MODE;


-- Dumping structure for trigger Solas-Match-test.validateHomepageUpdate
DROP TRIGGER IF EXISTS `validateHomepageUpdate`;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `validateHomepageUpdate` BEFORE UPDATE ON `organisation` FOR EACH ROW BEGIN
	if (new.home_page not like "http://*" OR new.home_page not like "https://*") then
	set new.home_page = concat("http://",new.home_page);
	end if;
END//
DELIMITER ;
SET SQL_MODE=@OLD_SQL_MODE;
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
