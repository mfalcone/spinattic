DROP TABLE categories;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

INSERT INTO categories VALUES("2","Universities");
INSERT INTO categories VALUES("3","Events");
INSERT INTO categories VALUES("4","Real Estate");
INSERT INTO categories VALUES("5","Hotels");
INSERT INTO categories VALUES("6","Resorts");
INSERT INTO categories VALUES("7","Restaurants");
INSERT INTO categories VALUES("8","Museums");
INSERT INTO categories VALUES("9","Destinations");
INSERT INTO categories VALUES("10","Vehicles");
INSERT INTO categories VALUES("11","Parks");
INSERT INTO categories VALUES("12","Nature");
INSERT INTO categories VALUES("13","Miscellaneous");



DROP TABLE comments;

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `idtour` int(11) NOT NULL,
  `comments` varchar(5000) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO comments VALUES("1","1","The HepCat","2","un comentario","2013-06-19 07:50:06");
INSERT INTO comments VALUES("2","1","The HepCat","2","otro comentario","2013-06-19 07:50:16");
INSERT INTO comments VALUES("3","1","The HepCat","2","otro comentario mas","2013-06-19 07:50:24");



DROP TABLE likes;

CREATE TABLE `likes` (
  `idtour` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO likes VALUES("46"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("46"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("46"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("46"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("46"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("46"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("46"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("46"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("46"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("45"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("45"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("45"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("45"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("45"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("44"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("44"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("45"," 150.70.97.119","2013-06-22 00:17:04");
INSERT INTO likes VALUES("45"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("44"," 150.70.97.116","2013-06-22 00:17:04");
INSERT INTO likes VALUES("45"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("45"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("44"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("46"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("46"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("46"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("46"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("41"," 200.43.32.41","2013-06-22 00:17:04");
INSERT INTO likes VALUES("44"," 200.43.32.41","2013-06-25 08:17:08");
INSERT INTO likes VALUES("41"," 200.43.32.41","2013-06-25 08:17:13");
INSERT INTO likes VALUES("45"," 150.70.97.114","2013-06-25 08:17:44");
INSERT INTO likes VALUES("45"," 150.70.75.37","2013-06-25 08:19:42");
INSERT INTO likes VALUES("41"," 150.70.172.107","2013-06-25 08:20:59");
INSERT INTO likes VALUES("26"," 200.43.32.41","2013-06-25 08:21:34");
INSERT INTO likes VALUES("23"," 200.43.32.41","2013-06-25 08:21:40");
INSERT INTO likes VALUES("18"," 200.43.32.41","2013-06-25 08:21:45");
INSERT INTO likes VALUES("17"," 200.43.32.41","2013-06-25 08:21:48");
INSERT INTO likes VALUES("26"," 150.70.173.40","2013-06-25 08:22:21");
INSERT INTO likes VALUES("17"," 150.70.173.50","2013-06-25 08:23:14");
INSERT INTO likes VALUES("18"," 150.70.97.118","2013-06-25 08:24:20");
INSERT INTO likes VALUES("23"," 150.70.64.208","2013-06-25 08:24:59");
INSERT INTO likes VALUES("18"," 150.70.64.208","2013-06-25 08:25:05");
INSERT INTO likes VALUES("17"," 150.70.172.209","2013-06-25 08:29:21");
INSERT INTO likes VALUES("30"," 200.43.32.41","2013-06-25 08:30:07");
INSERT INTO likes VALUES("1"," 200.43.32.41","2013-06-25 08:30:15");
INSERT INTO likes VALUES("2"," 200.43.32.41","2013-06-25 08:30:20");
INSERT INTO likes VALUES("6"," 200.43.32.41","2013-06-25 08:30:25");
INSERT INTO likes VALUES("6"," 150.70.97.114","2013-06-25 08:31:16");
INSERT INTO likes VALUES("30"," 150.70.97.121","2013-06-25 08:31:20");
INSERT INTO likes VALUES("1"," 150.70.97.120","2013-06-25 08:31:27");
INSERT INTO likes VALUES("1"," 150.70.64.214","2013-06-25 08:32:49");
INSERT INTO likes VALUES("2"," 150.70.97.116","2013-06-25 08:34:58");
INSERT INTO likes VALUES("46"," 150.70.172.204","2013-06-25 08:45:12");
INSERT INTO likes VALUES("1"," 150.70.97.41","2013-06-25 08:45:24");
INSERT INTO likes VALUES("30"," 150.70.97.41","2013-06-25 08:45:58");
INSERT INTO likes VALUES("40"," 150.70.97.116","2013-06-27 06:45:41");
INSERT INTO likes VALUES("40"," 150.70.75.38","2013-06-27 06:46:53");
INSERT INTO likes VALUES("45"," 200.3.95.39","2013-06-27 06:47:58");
INSERT INTO likes VALUES("44"," 200.3.95.39","2013-06-27 06:48:05");
INSERT INTO likes VALUES("45"," 150.70.172.107","2013-06-27 06:54:22");
INSERT INTO likes VALUES("32"," 200.3.95.39","2013-06-27 06:58:09");
INSERT INTO likes VALUES("32"," 150.70.97.114","2013-06-27 06:59:38");
INSERT INTO likes VALUES("44"," 150.70.172.204","2013-06-27 07:06:47");
INSERT INTO likes VALUES("45"," 150.70.97.41","2013-06-27 07:12:51");
INSERT INTO likes VALUES("8"," 200.43.32.41","2013-06-27 07:23:18");
INSERT INTO likes VALUES("8"," 150.70.97.112","2013-06-27 07:25:01");
INSERT INTO likes VALUES("37"," 200.43.32.41","2013-06-27 07:36:26");
INSERT INTO likes VALUES("46"," 200.43.32.41","2013-06-27 07:37:33");
INSERT INTO likes VALUES("45"," 200.43.32.41","2013-06-27 07:37:52");
INSERT INTO likes VALUES("46"," 150.70.97.121","2013-06-27 07:39:00");
INSERT INTO likes VALUES("46"," 150.70.64.214","2013-06-27 07:39:33");
INSERT INTO likes VALUES("44"," 150.70.97.127","2013-06-27 07:40:16");
INSERT INTO likes VALUES("37"," 150.70.172.107","2013-06-27 07:41:08");
INSERT INTO likes VALUES("44"," 150.70.75.37","2013-06-27 07:41:22");
INSERT INTO likes VALUES("8"," 150.70.172.204","2013-06-27 08:04:43");
INSERT INTO likes VALUES("33"," 190.210.29.49","2013-06-27 12:04:51");
INSERT INTO likes VALUES("46"," 72.43.12.186","2013-06-27 12:05:01");
INSERT INTO likes VALUES("46"," 24.103.176.42","2013-06-27 12:06:02");
INSERT INTO likes VALUES("46"," 190.210.29.49","2013-06-27 12:06:18");
INSERT INTO likes VALUES("41"," 72.43.12.186","2013-06-27 12:29:09");
INSERT INTO likes VALUES("44"," 72.43.12.186","2013-06-27 12:29:12");
INSERT INTO likes VALUES("39"," 72.43.12.186","2013-06-27 12:29:15");
INSERT INTO likes VALUES("44"," 190.210.29.49","2013-06-27 12:29:16");
INSERT INTO likes VALUES("45"," 190.210.29.49","2013-06-27 12:29:17");
INSERT INTO likes VALUES("40"," 190.210.29.49","2013-06-27 12:29:20");
INSERT INTO likes VALUES("39"," 190.210.29.49","2013-06-27 12:29:21");
INSERT INTO likes VALUES("33"," 24.103.176.42","2013-06-27 12:29:21");
INSERT INTO likes VALUES("38"," 190.210.29.49","2013-06-27 12:29:22");
INSERT INTO likes VALUES("37"," 190.210.29.49","2013-06-27 12:29:23");
INSERT INTO likes VALUES("46"," 200.3.95.39","2013-06-28 06:27:14");
INSERT INTO likes VALUES("8"," 201.212.120.22","2013-07-03 15:17:41");
INSERT INTO likes VALUES("8"," 72.43.12.186","2013-07-04 10:04:36");
INSERT INTO likes VALUES("46"," 190.30.111.236","2013-07-09 18:30:36");
INSERT INTO likes VALUES("8"," 190.30.111.236","2013-07-10 10:08:11");
INSERT INTO likes VALUES("37"," 200.43.32.34","2013-07-11 08:29:57");
INSERT INTO likes VALUES("46"," 200.43.32.34","2013-07-11 08:30:07");
INSERT INTO likes VALUES("37"," 150.70.97.126","2013-07-11 08:31:21");
INSERT INTO likes VALUES("46"," 150.70.97.122","2013-07-11 08:33:09");
INSERT INTO likes VALUES("46"," 150.70.75.37","2013-07-11 08:34:07");
INSERT INTO likes VALUES("40"," 72.43.12.186","2013-07-17 13:10:46");
INSERT INTO likes VALUES("41"," 72.43.12.186","2013-07-18 06:12:06");
INSERT INTO likes VALUES("40"," 200.3.95.36","2013-07-22 08:16:06");
INSERT INTO likes VALUES("40"," 150.70.173.43","2013-07-22 08:17:44");
INSERT INTO likes VALUES("41"," 190.230.221.225","2013-07-22 20:08:40");
INSERT INTO likes VALUES("40"," 190.230.221.225","2013-07-22 20:08:41");
INSERT INTO likes VALUES("39"," 190.230.221.225","2013-07-22 20:11:20");
INSERT INTO likes VALUES("41"," 74.70.187.154","2014-01-01 06:38:27");
INSERT INTO likes VALUES("39"," 201.235.173.30","2013-07-25 12:13:13");
INSERT INTO likes VALUES("20"," 150.70.97.114","2013-08-06 07:17:37");
INSERT INTO likes VALUES("40"," 200.43.32.34","2013-08-06 07:17:44");
INSERT INTO likes VALUES("41"," 200.43.32.34","2013-08-06 07:17:46");
INSERT INTO likes VALUES("17"," 200.43.32.34","2013-08-06 07:17:53");
INSERT INTO likes VALUES("15"," 200.43.32.34","2013-08-06 07:18:06");
INSERT INTO likes VALUES("41"," 150.70.97.117","2013-08-06 07:18:20");
INSERT INTO likes VALUES("17"," 150.70.97.118","2013-08-06 07:19:12");
INSERT INTO likes VALUES("20"," 150.70.64.208","2013-08-06 07:19:13");
INSERT INTO likes VALUES("41"," 150.70.75.38","2013-08-06 07:19:16");
INSERT INTO likes VALUES("15"," 150.70.97.115","2013-08-06 07:19:34");
INSERT INTO likes VALUES("17"," 150.70.64.214","2013-08-06 07:20:50");
INSERT INTO likes VALUES("15"," 150.70.64.208","2013-08-06 07:21:43");
INSERT INTO likes VALUES("2"," 150.70.172.101","2013-08-06 07:23:09");
INSERT INTO likes VALUES("40"," 150.70.172.204","2013-08-06 07:32:52");
INSERT INTO likes VALUES("16"," 200.43.32.34","2013-08-06 07:43:56");
INSERT INTO likes VALUES("16"," 150.70.97.119","2013-08-06 07:45:18");
INSERT INTO likes VALUES("16"," 150.70.64.208","2013-08-06 07:46:42");
INSERT INTO likes VALUES("7"," 200.43.32.34","2013-08-07 07:04:49");
INSERT INTO likes VALUES("7"," 150.70.173.45","2013-08-07 07:06:16");
INSERT INTO likes VALUES("7"," 150.70.172.209","2013-08-07 07:12:26");
INSERT INTO likes VALUES("8"," 200.43.32.34","2013-08-07 07:50:13");
INSERT INTO likes VALUES("8"," 150.70.173.46","2013-08-07 07:51:47");
INSERT INTO likes VALUES("8"," 150.70.172.107","2013-08-07 07:52:57");



DROP TABLE panos;

CREATE TABLE `panos` (
  `id` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `name` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO panos VALUES("1","1","1","2013-12-20 05:56:25","scene-name-1");
INSERT INTO panos VALUES("2","1","1","2013-12-20 07:05:31","scene-name-2");
INSERT INTO panos VALUES("3","1","1","2013-12-20 07:09:45","scene-name-3");
INSERT INTO panos VALUES("4","1","1","2014-01-02 11:36:24","scene-name-4");
INSERT INTO panos VALUES("5","1","1","2014-01-02 11:36:26","scene-name-5");
INSERT INTO panos VALUES("6","1","1","2014-01-14 09:58:29","scene-name-6");
INSERT INTO panos VALUES("7","1","1","2014-01-14 09:58:58","scene-name-7");
INSERT INTO panos VALUES("8","1","1","2014-01-15 09:34:56","scene-name-8");



DROP TABLE panosxtour;

CREATE TABLE `panosxtour` (
  `ord` int(11) NOT NULL,
  `idpano` int(11) NOT NULL,
  `idtour` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO panosxtour VALUES("0","444","42");



DROP TABLE tags;

CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO tags VALUES("1","rwe");
INSERT INTO tags VALUES("2","snow");



DROP TABLE tours;

CREATE TABLE `tours` (
  `id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `friendly_url` varchar(255) NOT NULL,
  `location` varchar(1000) NOT NULL,
  `description` varchar(5000) NOT NULL,
  `category` varchar(255) NOT NULL,
  `tags` varchar(1000) NOT NULL,
  `lat` varchar(50) NOT NULL,
  `lon` varchar(50) NOT NULL,
  `allow_comments` varchar(2) NOT NULL,
  `allow_social` varchar(2) NOT NULL,
  `allow_embed` varchar(2) NOT NULL,
  `allow_votes` varchar(2) NOT NULL,
  `privacy` varchar(50) NOT NULL,
  `likes` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `comments` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=161 DEFAULT CHARSET=utf8;

INSERT INTO tours VALUES("40","Cerro Otto","www.ciudadesferica.com/demo/cerrootto/"," San Carlos de Bariloche, Subida Otto, Bariloche, Río Negro Province, Argentina","Cerro Otto Bariloche. Confitería giratoria en el Cetto Otto, Bariloche - Patagonia - Argentina","Destinations","","-41.144241691484616","-71.37607097625732","","","","","_public","6","21","1","Spinattic","0","2013-07-17 12:43:37");
INSERT INTO tours VALUES("2","Titulo 2","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","5","2","1","Spinattic","0","2013-06-26 10:42:51");
INSERT INTO tours VALUES("3","Titulo 3","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","2","0","1","Spinattic","0","2013-06-27 10:42:51");
INSERT INTO tours VALUES("4","Titulo 4","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","0","0","1","Spinattic","0","2013-06-28 10:42:51");
INSERT INTO tours VALUES("5","Titulo 5","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","0","0","1","Spinattic","0","2013-06-29 10:42:51");
INSERT INTO tours VALUES("6","Titulo 6","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","2","0","1","Spinattic","0","2013-06-30 10:42:51");
INSERT INTO tours VALUES("7","Titulo 7","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","3","7","1","Spinattic","0","2013-07-01 10:42:51");
INSERT INTO tours VALUES("8","Titulo 8","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-19.909736","-44.6875","0","0","0","0","_notlisted","9","63","1","Spinattic","0","2013-07-02 10:42:51");
INSERT INTO tours VALUES("9","Titulo 9","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","1","0","1","Spinattic","0","2013-07-03 10:42:51");
INSERT INTO tours VALUES("10","Titulo 10","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","1","0","1","Spinattic","0","2013-07-04 10:42:51");
INSERT INTO tours VALUES("11","Titulo 11","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","0","0","1","Spinattic","0","2013-07-05 10:42:51");
INSERT INTO tours VALUES("12","Titulo 12","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","11","3","1","Spinattic","0","2013-07-06 10:42:51");
INSERT INTO tours VALUES("13","Titulo 13","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","2","0","1","Spinattic","0","2013-07-07 10:42:51");
INSERT INTO tours VALUES("14","Titulo 14","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","2","0","1","Spinattic","0","2013-07-08 10:42:51");
INSERT INTO tours VALUES("15","Titulo 15","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","3","3","1","Spinattic","0","2013-07-09 10:42:51");
INSERT INTO tours VALUES("16","Titulo 16","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","3","0","1","Spinattic","0","2013-07-10 10:42:51");
INSERT INTO tours VALUES("17","Titulo 17","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","7","0","1","Spinattic","0","2013-07-11 10:42:51");
INSERT INTO tours VALUES("18","Titulo 18","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","3","0","1","Spinattic","0","2013-07-12 10:42:51");
INSERT INTO tours VALUES("19","Titulo 19","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","0","0","1","Spinattic","0","2013-07-13 10:42:51");
INSERT INTO tours VALUES("20","Titulo 20","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","5","0","1","Spinattic","0","2013-07-14 10:42:51");
INSERT INTO tours VALUES("21","Titulo 21","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","2","0","1","Spinattic","0","2013-07-15 10:42:51");
INSERT INTO tours VALUES("22","Titulo 22","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","5","4","1","Spinattic","0","2013-07-16 10:42:51");
INSERT INTO tours VALUES("23","Titulo 23","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","9","0","1","Spinattic","0","2013-07-17 10:42:51");
INSERT INTO tours VALUES("24","Titulo 24","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","10","0","1","Spinattic","0","2013-07-18 10:42:51");
INSERT INTO tours VALUES("25","Titulo 25","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","2","5","1","Spinattic","0","2013-07-19 10:42:51");
INSERT INTO tours VALUES("26","Titulo 26","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","8","0","1","Spinattic","0","2013-07-20 10:42:51");
INSERT INTO tours VALUES("27","Titulo 27","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","0","3","1","Spinattic","0","2013-07-21 10:42:51");
INSERT INTO tours VALUES("28","Titulo 28","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","0","0","1","Spinattic","0","2013-07-22 10:42:51");
INSERT INTO tours VALUES("29","Titulo 29","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","5","0","1","Spinattic","0","2013-07-23 10:42:51");
INSERT INTO tours VALUES("30","Titulo 30","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","hernan","-39.909736","-64.6875","0","0","0","0","_notlisted","9","7","2","Test User","6","2013-07-24 10:42:51");
INSERT INTO tours VALUES("31","Titulo 31","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","","-39.909736","-64.6875","0","0","0","0","_notlisted","1","0","1","Spinattic","0","2013-07-25 10:42:51");
INSERT INTO tours VALUES("32","Titulo 32","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","","-39.909736","-64.6875","0","0","0","0","_notlisted","2","0","1","Spinattic","0","2013-07-26 10:42:51");
INSERT INTO tours VALUES("33","Titulo 33","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","","-39.909736","-64.6875","0","0","0","0","_notlisted","5","6","1","Spinattic","0","2013-07-27 10:42:51");
INSERT INTO tours VALUES("39","Hotel Villa Huinid","www.ciudadesferica.com/demo/villahuinid/","Avenida Exequiel Bustillo 2600-2798, Los Cipresales, San Carlos de Bariloche, Bariloche, Río Negro Province, Argentina","Tour Virtual por el hotel Villa Huinid. Bariloche - Patagonia - Argentina\nHotel 5 estrellas en Barilche.","Hotels","","-41.1329552999372","-71.34026885032654","","","","","_public","2","12","1","Spinattic","0","2013-07-17 12:12:03");
INSERT INTO tours VALUES("35","Titulo 35","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","","-39.909736","-64.6875","0","0","0","0","_notlisted","2","2","1","Spinattic","0","2013-07-29 10:42:51");
INSERT INTO tours VALUES("36","Titulo 36","www.ciudadesferica.com/demo/cumelen/#/parque","100 Lake Lane, Heber Springs, Condado de Cleburne, Clayton, Arkansas 72543, EEUU, Estados Unidos","Este es el comentario desde la base de datos","","","-39.909736","-64.6875","0","0","0","0","_private","0","7","1","Spinattic","0","2013-07-30 10:42:51");
INSERT INTO tours VALUES("37","Titulo 37 mod","www.ciudadesferica.com/demo/cumelen/#/parque","Dar Tama, Wadi Fira, Chad","Este es el comentario desde la base de datos mod","Destinations","the,tag","14.672881316038076","21.796875","on","","on","","_private","5","6","1","Spinattic","0","2013-07-31 10:42:51");
INSERT INTO tours VALUES("41","SUNY Geneseo","virtualmedia360.net/tours/geneseo","College Circle,  NY 14454, Geneseo, Livingston,  NY, Rochester,  USA, New York","SUNY Geneseo! This was one of our first custom tours for a college. Make sure you are able to find the rooftop 360. ","Universities","","42.7964806731367","-77.82069504261017","","","","","_notlisted","6","33","1","Spinattic","0","2013-07-17 13:04:08");



DROP TABLE users;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `website` varchar(500) NOT NULL,
  `twitter` varchar(500) NOT NULL,
  `facebook` varchar(500) NOT NULL,
  `password` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO users VALUES("1","Spinattic","ariel@ciudadesferica.com","Argentina","Santa Fe","Rosario","www.spinattic.com","www.facebook.com/spinattic","www.twitter.com/spinattic","hernan1");
INSERT INTO users VALUES("2","Test User","","","","","","","","");



DROP TABLE views;

CREATE TABLE `views` (
  `idtour` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO views VALUES("45"," 200.43.32.41","2013-06-25 09:49:13");
INSERT INTO views VALUES("46"," 200.43.32.41","2013-06-25 09:49:21");
INSERT INTO views VALUES("46"," 150.70.97.123","2013-06-25 09:50:22");
INSERT INTO views VALUES("45"," 150.70.173.48","2013-06-25 09:50:26");
INSERT INTO views VALUES("44"," 200.43.32.41","2013-06-25 09:50:36");
INSERT INTO views VALUES("46"," 150.70.75.38","2013-06-25 09:52:21");
INSERT INTO views VALUES("44"," 150.70.97.126","2013-06-25 09:53:40");
INSERT INTO views VALUES("38"," 200.43.32.41","2013-06-25 09:53:48");
INSERT INTO views VALUES("38"," 150.70.97.116","2013-06-25 09:54:37");
INSERT INTO views VALUES("38"," 150.70.64.214","2013-06-25 09:56:33");
INSERT INTO views VALUES("38"," 150.70.97.41","2013-06-25 10:08:17");
INSERT INTO views VALUES("46"," 200.3.95.39","2013-06-25 10:35:06");
INSERT INTO views VALUES("27"," 200.3.95.39","2013-06-25 10:36:03");
INSERT INTO views VALUES("8"," 200.3.95.39","2013-06-25 10:36:16");
INSERT INTO views VALUES("46"," 150.70.173.44","2013-06-25 10:36:25");
INSERT INTO views VALUES("8"," 150.70.97.125","2013-06-25 10:37:19");
INSERT INTO views VALUES("27"," 150.70.64.208","2013-06-25 10:38:24");
INSERT INTO views VALUES("8"," 150.70.64.208","2013-06-25 10:38:24");
INSERT INTO views VALUES("27"," 150.70.97.113","2013-06-25 10:40:23");
INSERT INTO views VALUES("46"," 150.70.172.107","2013-06-25 10:42:11");
INSERT INTO views VALUES("8"," 150.70.173.48","2013-06-25 11:16:42");
INSERT INTO views VALUES("8"," 150.70.172.209","2013-06-25 11:21:38");
INSERT INTO views VALUES("8"," 150.70.172.101","2013-06-26 08:14:02");
INSERT INTO views VALUES("8"," 200.43.32.41","2013-06-26 09:56:40");
INSERT INTO views VALUES("8"," 150.70.97.127","2013-06-26 09:58:17");
INSERT INTO views VALUES("8"," 150.70.75.38","2013-06-26 09:58:30");
INSERT INTO views VALUES("22"," 200.43.32.41","2013-06-26 09:58:42");
INSERT INTO views VALUES("22"," 150.70.173.54","2013-06-26 09:59:23");
INSERT INTO views VALUES("22"," 150.70.172.107","2013-06-26 10:03:25");
INSERT INTO views VALUES("22"," 150.70.97.41","2013-06-26 10:08:55");
INSERT INTO views VALUES("8"," 150.70.97.117","2013-06-26 11:12:24");
INSERT INTO views VALUES("8"," 150.70.64.214","2013-06-26 11:17:02");
INSERT INTO views VALUES("8"," 66.220.152.117","2013-06-27 06:28:49");
INSERT INTO views VALUES("8"," 190.210.29.49","2013-06-27 06:28:50");
INSERT INTO views VALUES("8"," 150.70.97.121","2013-06-27 06:33:29");
INSERT INTO views VALUES("8"," 150.70.75.37","2013-06-27 06:33:46");
INSERT INTO views VALUES("36"," 200.3.95.39","2013-06-27 06:57:48");
INSERT INTO views VALUES("36"," 150.70.97.122","2013-06-27 06:58:48");
INSERT INTO views VALUES("36"," 150.70.64.208","2013-06-27 07:17:01");
INSERT INTO views VALUES("8"," 150.70.172.107","2013-06-27 07:29:21");
INSERT INTO views VALUES("37"," 200.43.32.41","2013-06-27 07:36:22");
INSERT INTO views VALUES("43"," 200.43.32.41","2013-06-27 07:41:26");
INSERT INTO views VALUES("43"," 150.70.97.114","2013-06-27 07:42:39");
INSERT INTO views VALUES("37"," 150.70.172.101","2013-06-27 07:43:18");
INSERT INTO views VALUES("43"," 150.70.75.38","2013-06-27 07:44:12");
INSERT INTO views VALUES("43"," 150.70.64.214","2013-06-27 08:22:12");
INSERT INTO views VALUES("43"," 150.70.97.112","2013-06-27 08:23:01");
INSERT INTO views VALUES("43"," 150.70.75.37","2013-06-27 08:23:33");
INSERT INTO views VALUES("43"," 150.70.172.107","2013-06-27 08:26:44");
INSERT INTO views VALUES("43"," 150.70.97.113","2013-06-27 08:34:30");
INSERT INTO views VALUES("43"," 150.70.172.101","2013-06-27 08:36:13");
INSERT INTO views VALUES("43"," 150.70.97.123","2013-06-27 09:34:41");
INSERT INTO views VALUES("30"," 200.43.32.41","2013-06-27 09:46:12");
INSERT INTO views VALUES("30"," 150.70.172.107","2013-06-27 09:50:15");
INSERT INTO views VALUES("46"," 150.70.97.116","2013-06-27 09:50:36");
INSERT INTO views VALUES("46"," 150.70.75.37","2013-06-27 09:51:37");
INSERT INTO views VALUES("46"," 190.210.29.49","2013-06-27 12:03:45");
INSERT INTO views VALUES("33"," 190.210.29.49","2013-06-27 12:04:18");
INSERT INTO views VALUES("46"," 72.43.12.186","2013-06-27 12:04:26");
INSERT INTO views VALUES("46"," 24.103.176.42","2013-06-27 12:05:47");
INSERT INTO views VALUES("45"," 190.210.29.49","2013-06-27 12:28:21");
INSERT INTO views VALUES("44"," 24.103.176.42","2013-06-27 12:28:48");
INSERT INTO views VALUES("33"," 24.103.176.42","2013-06-27 12:29:19");
INSERT INTO views VALUES("44"," 190.210.29.49","2013-06-27 12:29:33");
INSERT INTO views VALUES("8"," 190.210.29.49","2013-07-01 04:08:37");
INSERT INTO views VALUES("43"," 200.3.94.38","2013-07-01 06:32:35");
INSERT INTO views VALUES("43"," 150.70.97.122","2013-07-01 06:35:55");
INSERT INTO views VALUES("43"," 150.70.172.101","2013-07-01 06:43:44");
INSERT INTO views VALUES("45"," 200.3.95.36","2013-07-02 09:48:30");
INSERT INTO views VALUES("45"," 150.70.97.117","2013-07-02 09:49:32");
INSERT INTO views VALUES("45"," 150.70.75.38","2013-07-02 09:50:13");
INSERT INTO views VALUES("8"," 200.3.94.35","2013-07-02 10:21:41");
INSERT INTO views VALUES("8"," 150.70.172.204","2013-07-02 10:45:35");
INSERT INTO views VALUES("8"," 200.3.95.36","2013-07-02 11:17:36");
INSERT INTO views VALUES("8"," 150.70.97.117","2013-07-02 11:19:23");
INSERT INTO views VALUES("8"," 150.70.64.208","2013-07-02 11:19:47");
INSERT INTO views VALUES("8"," 201.212.120.22","2013-07-03 15:07:52");
INSERT INTO views VALUES("8"," 72.43.12.186","2013-07-04 10:04:16");
INSERT INTO views VALUES("8"," 200.3.94.35","2013-07-05 11:03:03");
INSERT INTO views VALUES("46"," 190.30.111.236","2013-07-09 18:18:01");
INSERT INTO views VALUES("149"," 200.3.95.36","2013-07-10 07:39:01");
INSERT INTO views VALUES("149"," 150.70.173.43","2013-07-10 07:40:22");
INSERT INTO views VALUES("8"," 190.30.111.236","2013-07-10 10:02:42");
INSERT INTO views VALUES("8"," 200.3.95.36","2013-07-11 07:24:22");
INSERT INTO views VALUES("8"," 150.70.172.209","2013-07-11 07:26:58");
INSERT INTO views VALUES("50"," 200.3.95.36","2013-07-11 07:29:54");
INSERT INTO views VALUES("47"," 200.3.95.36","2013-07-11 07:30:00");
INSERT INTO views VALUES("50"," 150.70.173.44","2013-07-11 07:31:29");
INSERT INTO views VALUES("47"," 150.70.173.55","2013-07-11 07:31:33");
INSERT INTO views VALUES("46"," 200.3.95.36","2013-07-11 07:33:59");
INSERT INTO views VALUES("46"," 150.70.173.51","2013-07-11 07:36:10");
INSERT INTO views VALUES("8"," 150.70.97.41","2013-07-11 07:56:45");
INSERT INTO views VALUES("8"," 200.43.32.34","2013-07-11 08:29:45");
INSERT INTO views VALUES("8"," 150.70.97.127","2013-07-11 08:32:58");
INSERT INTO views VALUES("45"," 201.212.120.22","2013-07-11 10:17:32");
INSERT INTO views VALUES("42"," 200.3.95.36","2013-07-11 10:23:51");
INSERT INTO views VALUES("42"," 150.70.173.52","2013-07-11 10:25:15");
INSERT INTO views VALUES("42"," 150.70.172.101","2013-07-11 10:45:32");
INSERT INTO views VALUES("38"," 201.212.120.22","2013-07-17 11:49:12");
INSERT INTO views VALUES("39"," 201.212.120.22","2013-07-17 12:16:50");
INSERT INTO views VALUES("39"," 72.43.12.186","2013-07-17 12:37:44");
INSERT INTO views VALUES("40"," 72.43.12.186","2013-07-17 13:04:47");
INSERT INTO views VALUES("41"," 72.43.12.186","2013-07-17 13:05:18");
INSERT INTO views VALUES("41"," 24.103.176.42","2013-07-17 13:15:29");
INSERT INTO views VALUES("39"," 24.103.176.42","2013-07-17 13:15:46");
INSERT INTO views VALUES("40"," 24.103.176.42","2013-07-17 13:17:05");
INSERT INTO views VALUES("40"," 201.212.120.22","2013-07-19 11:50:23");
INSERT INTO views VALUES("41"," 201.212.120.22","2013-07-19 11:50:39");
INSERT INTO views VALUES("41"," 69.171.229.115","2013-07-19 11:51:28");
INSERT INTO views VALUES("41"," 200.3.95.36","2013-07-19 11:51:29");
INSERT INTO views VALUES("41"," 150.70.97.114","2013-07-19 11:52:21");
INSERT INTO views VALUES("41"," 150.70.75.38","2013-07-19 11:54:36");
INSERT INTO views VALUES("41"," 150.70.173.43","2013-07-19 11:58:20");
INSERT INTO views VALUES("41"," 150.70.172.209","2013-07-19 12:00:43");
INSERT INTO views VALUES("42"," 200.43.32.34","2013-07-22 07:11:16");
INSERT INTO views VALUES("42"," 150.70.173.53","2013-07-22 07:12:41");
INSERT INTO views VALUES("41"," 200.43.32.34","2013-07-22 07:46:11");
INSERT INTO views VALUES("41"," 150.70.173.51","2013-07-22 07:47:15");
INSERT INTO views VALUES("40"," 200.3.95.36","2013-07-22 08:16:08");
INSERT INTO views VALUES("38"," 200.3.95.36","2013-07-22 08:17:45");
INSERT INTO views VALUES("40"," 150.70.173.53","2013-07-22 08:17:47");
INSERT INTO views VALUES("38"," 150.70.173.48","2013-07-22 08:19:11");
INSERT INTO views VALUES("40"," 190.210.29.49","2013-07-22 08:26:33");
INSERT INTO views VALUES("38"," 150.70.172.204","2013-07-22 08:39:41");
INSERT INTO views VALUES("40"," 200.3.94.38","2013-07-22 09:42:11");
INSERT INTO views VALUES("40"," 150.70.172.107","2013-07-22 09:45:36");
INSERT INTO views VALUES("8"," 200.3.94.38","2013-07-22 10:00:43");
INSERT INTO views VALUES("8"," 150.70.173.50","2013-07-22 10:02:27");
INSERT INTO views VALUES("8"," 200.3.94.35","2013-07-22 10:07:42");
INSERT INTO views VALUES("40"," 200.3.94.35","2013-07-22 10:16:14");
INSERT INTO views VALUES("8"," 190.210.29.49","2013-07-22 10:16:44");
INSERT INTO views VALUES("40"," 150.70.97.125","2013-07-22 10:17:22");
INSERT INTO views VALUES("8"," 150.70.172.101","2013-07-22 10:23:52");
INSERT INTO views VALUES("33"," 200.3.94.35","2013-07-22 10:28:33");
INSERT INTO views VALUES("33"," 150.70.173.51","2013-07-22 10:29:26");
INSERT INTO views VALUES("33"," 150.70.172.209","2013-07-22 10:31:18");
INSERT INTO views VALUES("33"," 173.252.112.118","2013-07-22 10:35:13");
INSERT INTO views VALUES("41"," 190.230.221.225","2013-07-22 20:08:43");
INSERT INTO views VALUES("39"," 190.230.221.225","2013-07-22 20:11:33");
INSERT INTO views VALUES("39"," 69.171.237.14","2013-07-22 20:11:36");
INSERT INTO views VALUES("42"," 200.3.95.36","2013-07-23 11:00:09");
INSERT INTO views VALUES("42"," 150.70.173.45","2013-07-23 11:03:14");
INSERT INTO views VALUES("42"," 150.70.172.101","2013-07-23 11:19:40");
INSERT INTO views VALUES("42"," 190.225.112.87","2013-07-23 21:10:04");
INSERT INTO views VALUES("40"," 190.225.112.87","2013-07-23 21:10:11");
INSERT INTO views VALUES("8"," 201.235.173.30","2013-07-25 12:09:12");
INSERT INTO views VALUES("46"," 201.235.173.30","2013-07-25 12:12:45");
INSERT INTO views VALUES("1111"," 201.235.173.30","2013-07-25 12:12:54");
INSERT INTO views VALUES("39"," 201.235.173.30","2013-07-25 12:12:59");
INSERT INTO views VALUES("41"," 201.235.173.30","2013-07-25 12:37:17");
INSERT INTO views VALUES("8"," 190.210.29.49","2013-07-26 07:38:16");
INSERT INTO views VALUES("8"," 173.252.110.119","2013-07-26 07:38:24");
INSERT INTO views VALUES("8"," 190.210.29.49","2013-07-29 07:44:59");
INSERT INTO views VALUES("41"," 190.210.29.49","2013-07-29 10:58:47");
INSERT INTO views VALUES("41"," 69.171.224.114","2013-07-29 10:58:54");
INSERT INTO views VALUES("40"," 190.210.29.49","2013-07-30 06:57:30");
INSERT INTO views VALUES("40"," 173.252.110.118","2013-07-30 06:57:35");
INSERT INTO views VALUES("37"," 190.230.196.62","2013-07-30 08:24:48");
INSERT INTO views VALUES("37"," 173.252.110.113","2013-07-30 08:25:06");
INSERT INTO views VALUES("41"," 190.210.29.49","2013-08-02 04:55:42");
INSERT INTO views VALUES("2"," 200.3.94.35","2013-08-02 10:45:17");
INSERT INTO views VALUES("2"," 173.252.112.116","2013-08-02 10:46:37");
INSERT INTO views VALUES("8"," 200.3.94.35","2013-08-02 10:46:53");
INSERT INTO views VALUES("8"," 173.252.112.115","2013-08-02 10:46:59");
INSERT INTO views VALUES("40"," 190.224.82.218","2013-08-03 10:28:33");
INSERT INTO views VALUES("41"," 190.192.45.129","2013-08-03 16:31:51");
INSERT INTO views VALUES("36"," 190.192.45.129","2013-08-03 16:32:14");
INSERT INTO views VALUES("36"," 173.252.112.119","2013-08-03 16:32:19");
INSERT INTO views VALUES("8"," 150.70.173.45","2013-08-05 06:12:24");
INSERT INTO views VALUES("8"," 150.70.172.204","2013-08-05 06:17:08");
INSERT INTO views VALUES("41"," 200.3.94.35","2013-08-05 06:25:49");
INSERT INTO views VALUES("41"," 150.70.97.127","2013-08-05 06:26:32");
INSERT INTO views VALUES("41"," 150.70.64.208","2013-08-05 06:28:22");
INSERT INTO views VALUES("8"," 190.224.82.218","2013-08-05 07:31:12");
INSERT INTO views VALUES("8"," 200.3.95.36","2013-08-05 07:39:30");
INSERT INTO views VALUES("8"," 150.70.173.40","2013-08-05 07:41:47");
INSERT INTO views VALUES("8"," 190.210.29.49","2013-08-05 10:40:15");
INSERT INTO views VALUES("40"," 72.43.12.186","2013-08-06 11:29:16");
INSERT INTO views VALUES("40"," 173.252.73.115","2013-08-06 11:29:40");
INSERT INTO views VALUES("37"," 72.43.12.186","2013-08-06 11:30:09");
INSERT INTO views VALUES("37"," 173.252.73.112","2013-08-06 11:30:15");
INSERT INTO views VALUES("41"," 72.43.12.186","2013-08-06 11:30:48");
INSERT INTO views VALUES("41"," 173.252.73.113","2013-08-06 11:30:53");
INSERT INTO views VALUES("8"," 150.70.173.42","2013-08-07 06:43:37");
INSERT INTO views VALUES("8"," 150.70.173.48","2013-08-07 06:48:18");
INSERT INTO views VALUES("8"," 150.70.172.107","2013-08-07 06:51:26");
INSERT INTO views VALUES("8"," 150.70.97.121","2013-08-07 06:53:16");
INSERT INTO views VALUES("8"," 150.70.64.214","2013-08-07 06:54:38");
INSERT INTO views VALUES("8"," 200.43.32.34","2013-08-07 07:02:06");
INSERT INTO views VALUES("8"," 150.70.97.115","2013-08-07 07:03:30");
INSERT INTO views VALUES("7"," 200.43.32.34","2013-08-07 07:04:59");
INSERT INTO views VALUES("8"," 150.70.97.120","2013-08-07 07:05:20");
INSERT INTO views VALUES("8"," 150.70.97.117","2013-08-07 07:05:20");
INSERT INTO views VALUES("8"," 150.70.64.208","2013-08-07 07:05:23");
INSERT INTO views VALUES("7"," 150.70.173.49","2013-08-07 07:06:55");
INSERT INTO views VALUES("7"," 150.70.97.122","2013-08-07 07:07:18");
INSERT INTO views VALUES("7"," 150.70.97.121","2013-08-07 07:07:22");
INSERT INTO views VALUES("7"," 150.70.64.214","2013-08-07 07:08:17");
INSERT INTO views VALUES("7"," 150.70.64.208","2013-08-07 07:08:26");
INSERT INTO views VALUES("8"," 150.70.172.209","2013-08-07 07:12:07");
INSERT INTO views VALUES("7"," 190.210.29.49","2013-08-07 07:25:20");
INSERT INTO views VALUES("12"," 200.43.32.34","2013-08-07 07:51:08");
INSERT INTO views VALUES("12"," 150.70.97.126","2013-08-07 07:52:18");
INSERT INTO views VALUES("25"," 200.43.32.34","2013-08-07 07:53:39");
INSERT INTO views VALUES("25"," 150.70.97.115","2013-08-07 07:54:18");
INSERT INTO views VALUES("12"," 150.70.64.208","2013-08-07 07:54:38");
INSERT INTO views VALUES("15"," 200.43.32.34","2013-08-07 07:55:06");
INSERT INTO views VALUES("41"," 200.43.32.34","2013-08-07 07:55:30");
INSERT INTO views VALUES("41"," 150.70.97.124","2013-08-07 07:56:16");
INSERT INTO views VALUES("15"," 150.70.97.112","2013-08-07 07:56:17");
INSERT INTO views VALUES("25"," 150.70.64.214","2013-08-07 07:56:40");
INSERT INTO views VALUES("15"," 150.70.64.208","2013-08-07 07:57:40");
INSERT INTO views VALUES("40"," 190.224.82.218","2013-08-07 11:33:03");
INSERT INTO views VALUES("41"," 190.224.82.218","2013-08-08 21:18:17");
INSERT INTO views VALUES("41"," 24.103.176.42","2013-09-11 13:33:30");
INSERT INTO views VALUES("41"," 173.252.110.118","2013-09-11 13:33:32");
INSERT INTO views VALUES("39"," 24.103.176.42","2013-09-11 14:03:33");
INSERT INTO views VALUES("39"," 173.252.110.113","2013-09-11 14:03:34");
INSERT INTO views VALUES("36"," 24.103.176.42","2013-09-11 14:17:42");
INSERT INTO views VALUES("36"," 173.252.110.118","2013-09-11 14:17:43");
INSERT INTO views VALUES("39"," 24.103.176.42","2013-11-15 14:22:49");
INSERT INTO views VALUES("39"," 173.252.112.114","2013-11-15 14:22:50");
INSERT INTO views VALUES("41"," 74.70.187.154","2013-11-24 19:16:15");
INSERT INTO views VALUES("41"," 69.171.237.8","2013-11-24 19:16:18");
INSERT INTO views VALUES("40"," 74.70.187.154","2013-11-24 19:17:24");
INSERT INTO views VALUES("40"," 69.171.237.8","2013-11-24 19:17:26");
INSERT INTO views VALUES("25"," 74.70.187.154","2013-11-24 19:19:33");
INSERT INTO views VALUES("25"," 69.171.237.14","2013-11-24 19:19:35");
INSERT INTO views VALUES("39"," 74.70.187.154","2013-11-24 19:24:25");
INSERT INTO views VALUES("39"," 69.171.237.12","2013-11-24 19:24:26");
INSERT INTO views VALUES("40"," 72.43.12.186","2013-11-25 05:41:52");
INSERT INTO views VALUES("40"," 24.103.176.42","2013-11-25 05:48:19");
INSERT INTO views VALUES("41"," 24.103.176.42","2013-11-25 05:49:46");
INSERT INTO views VALUES("41"," 72.43.12.186","2013-11-25 05:50:24");
INSERT INTO views VALUES("8"," 190.210.29.49","2013-12-02 06:07:12");
INSERT INTO views VALUES("8"," 173.252.110.116","2013-12-02 06:07:19");
INSERT INTO views VALUES("35"," 190.210.29.49","2013-12-02 06:07:43");
INSERT INTO views VALUES("35"," 173.252.110.114","2013-12-02 06:07:44");
INSERT INTO views VALUES("42"," 200.3.94.35","2013-12-11 06:03:03");
INSERT INTO views VALUES("42"," 173.252.112.113","2013-12-11 06:03:14");
INSERT INTO views VALUES("42"," 190.139.140.224","2013-12-20 05:58:59");
INSERT INTO views VALUES("41"," 74.70.187.154","2014-01-01 06:37:51");
INSERT INTO views VALUES("41"," 69.171.224.119","2014-01-01 06:38:14");
INSERT INTO views VALUES("8"," 190.210.29.49","2014-01-02 11:23:06");
INSERT INTO views VALUES("8"," 66.220.158.114","2014-01-02 11:23:14");



