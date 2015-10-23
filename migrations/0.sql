# INITIAL ADMIN DB
# This is the schema of the redirect module database as of 09/01/2015
DROP TABLE IF EXISTS `{{NAILS_DB_PREFIX}}redirect`;
CREATE TABLE `{{NAILS_DB_PREFIX}}redirect` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT, `old_url` varchar(255) DEFAULT NULL, `new_url` varchar(255) DEFAULT NULL, `type` enum('301','302') DEFAULT '301', `created` datetime NOT NULL, `created_by` int(11) unsigned DEFAULT NULL, `modified` datetime NOT NULL, `modified_by` int(11) unsigned DEFAULT NULL, PRIMARY KEY (`id`), KEY `created_by` (`created_by`), KEY `modified_by` (`modified_by`), CONSTRAINT `{{NAILS_DB_PREFIX}}redirect_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `{{NAILS_DB_PREFIX}}user` (`id`) ON DELETE SET NULL, CONSTRAINT `{{NAILS_DB_PREFIX}}redirect_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `{{NAILS_DB_PREFIX}}user` (`id`) ON DELETE SET NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;