

/*Data for the table `franky` */

insert  into `franky`(`php`,`css`,`js`,`jquery`,`permisos`,`constante`,`url`,`nombre`,`ajax`,`status`,`editable`,`modulo`) values ('pasarela.php','','','','','SOCIALLOGIN_PASARELA','social-login/pasarela/[provider]/','Social login pasarela','',1,0,'sociallogin'),('callback.php','','','','','SOCIALLOGIN_CALLBACK','social-login/callback/[provider]/','Social login callback','',1,0,'sociallogin');

/*Table structure for table `redes_sociales` */

DROP TABLE IF EXISTS `redes_sociales`;


CREATE TABLE `redes_sociales` (
  `id_user` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `red` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id_red` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `info` text COLLATE utf8_unicode_ci NOT NULL,
  `registro` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `redes_sociales_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

