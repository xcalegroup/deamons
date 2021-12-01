CREATE TABLE `deamon_handler` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `unik_id` varchar(200) NOT NULL,
 `stop` tinyint(4) NOT NULL DEFAULT 0,
 `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

CREATE TABLE `deamon_jobs` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `deamon_id` int(11) NOT NULL,
 `job_id` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
 `class` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
 `data` text COLLATE utf8_unicode_ci NOT NULL,
 `created` timestamp NULL DEFAULT current_timestamp(),
 `updated` timestamp NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1975 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_c