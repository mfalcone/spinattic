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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE likes;

CREATE TABLE `likes` (
  `idtour` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO likes VALUES("19"," 190.138.180.90","2014-03-25 10:37:16");
INSERT INTO likes VALUES("17"," 190.210.29.49","2014-03-26 07:05:08");
INSERT INTO likes VALUES("10"," 190.210.29.49","2014-03-26 06:59:30");
INSERT INTO likes VALUES("9"," 181.14.159.176","2014-03-27 09:04:15");
INSERT INTO likes VALUES("36"," 190.225.151.18","2014-05-16 09:19:00");
INSERT INTO likes VALUES("55"," 190.225.151.18","2014-05-19 12:00:00");
INSERT INTO likes VALUES("19"," 72.43.12.186","2014-05-19 12:00:59");
INSERT INTO likes VALUES("36"," 72.43.12.186","2014-05-19 12:06:28");
INSERT INTO likes VALUES("19"," 190.225.151.18","2014-05-19 12:06:47");
INSERT INTO likes VALUES("46"," 190.225.151.18","2014-05-19 12:08:48");



DROP TABLE panos;

CREATE TABLE `panos` (
  `id` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `name` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO panos VALUES("1","0","1","2014-03-14 10:21:54","minion.jpg");
INSERT INTO panos VALUES("2","1","1","2014-03-16 18:05:07","minneapolis twilight 2x1-L.jpg");
INSERT INTO panos VALUES("3","1","1","2014-03-16 18:16:14","minneapolis twilight 2x1-L.jpg");
INSERT INTO panos VALUES("4","1","1","2014-03-16 18:29:51","minneapolis twilight 2x1-L.jpg");
INSERT INTO panos VALUES("5","1","1","2014-03-16 18:30:11","url.jpeg");
INSERT INTO panos VALUES("6","1","1","2014-03-16 18:41:25","f9d_12872_17660625.jpeg");
INSERT INTO panos VALUES("7","1","1","2014-03-16 18:48:23","minneapolis twilight 2x1-L.jpg");
INSERT INTO panos VALUES("8","1","1","2014-03-18 07:02:26","12_baja.jpg");
INSERT INTO panos VALUES("9","1","1","2014-03-18 07:05:34","12.jpg");
INSERT INTO panos VALUES("10","1","1","2014-03-18 07:16:44","311logo.jpg");
INSERT INTO panos VALUES("11","1","1","2014-03-18 07:17:24","biohazard.jpg");
INSERT INTO panos VALUES("12","1","1","2014-03-18 07:17:40","311logo.jpg");
INSERT INTO panos VALUES("13","1","1","2014-03-18 08:56:32","front5.jpg");
INSERT INTO panos VALUES("14","1","1","2014-03-18 08:56:42","front4.jpg");
INSERT INTO panos VALUES("15","1","1","2014-03-18 10:49:50","311logo.jpg");
INSERT INTO panos VALUES("16","1","1","2014-03-18 11:18:26","311logo.jpg");
INSERT INTO panos VALUES("17","1","1","2014-03-18 12:56:08","311logo.jpg");
INSERT INTO panos VALUES("18","1","1","2014-03-18 14:23:20","311logo.jpg");
INSERT INTO panos VALUES("19","1","1","2014-03-18 16:48:24","front5.jpg");
INSERT INTO panos VALUES("20","1","1","2014-03-21 12:12:00","biohazard.jpg");
INSERT INTO panos VALUES("21","1","1","2014-03-21 12:18:18","311logo.jpg");
INSERT INTO panos VALUES("22","1","1","2014-03-21 12:18:45","big.jpg");
INSERT INTO panos VALUES("23","1","1","2014-03-21 12:21:12","big.jpg");
INSERT INTO panos VALUES("24","1","1","2014-03-21 12:21:25","biohazard.jpg");
INSERT INTO panos VALUES("25","1","1","2014-03-21 12:22:52","big.jpg");
INSERT INTO panos VALUES("26","1","1","2014-03-21 12:23:05","biohazard.jpg");
INSERT INTO panos VALUES("27","1","1","2014-03-24 12:11:24","yaddo_gardens_01 Panorama.jpg");
INSERT INTO panos VALUES("28","1","1","2014-03-24 12:11:39","cajonAzul-dic2012-02.jpg");
INSERT INTO panos VALUES("29","1","1","2014-03-24 12:13:42","12.jpg");
INSERT INTO panos VALUES("30","1","1","2014-03-24 12:19:24","yaddo_gardens_01 Panorama.jpg");
INSERT INTO panos VALUES("31","1","1","2014-03-24 12:25:59","12_baja.jpg");
INSERT INTO panos VALUES("32","1","1","2014-03-24 12:28:00","coneyisland_beach_01_panorama.jpg");
INSERT INTO panos VALUES("33","1","1","2014-03-25 11:47:12","12_baja.jpg");
INSERT INTO panos VALUES("34","1","1","2014-03-26 10:33:24","front5.jpg");
INSERT INTO panos VALUES("35","1","1","2014-03-26 10:33:27","front6.jpg");
INSERT INTO panos VALUES("36","1","1","2014-03-26 10:46:37","front5.jpg");
INSERT INTO panos VALUES("37","1","1","2014-03-26 10:47:14","front6.jpg");
INSERT INTO panos VALUES("38","1","1","2014-03-26 10:49:17","front5.jpg");
INSERT INTO panos VALUES("39","1","1","2014-03-26 10:50:24","front5.jpg");
INSERT INTO panos VALUES("40","1","1","2014-03-26 11:00:57","front5.jpg");
INSERT INTO panos VALUES("41","1","1","2014-03-26 11:01:38","front5.jpg");
INSERT INTO panos VALUES("42","1","1","2014-03-26 11:01:42","front6.jpg");
INSERT INTO panos VALUES("43","1","1","2014-03-26 11:03:32","front5.jpg");
INSERT INTO panos VALUES("44","1","1","2014-03-26 11:05:38","front5.jpg");
INSERT INTO panos VALUES("45","1","1","2014-03-26 11:06:24","front6.jpg");
INSERT INTO panos VALUES("46","1","1","2014-03-26 11:08:06","front5.jpg");
INSERT INTO panos VALUES("47","1","1","2014-03-26 11:08:10","front6.jpg");
INSERT INTO panos VALUES("48","1","1","2014-03-26 11:20:50","311logo.jpg");
INSERT INTO panos VALUES("49","1","1","2014-03-26 11:27:04","front5.jpg");
INSERT INTO panos VALUES("50","1","1","2014-03-26 11:27:08","front6.jpg");
INSERT INTO panos VALUES("51","1","1","2014-03-26 11:29:36","front5.jpg");
INSERT INTO panos VALUES("52","1","1","2014-03-26 11:30:10","front6.jpg");
INSERT INTO panos VALUES("53","1","1","2014-03-26 11:37:37","front5.jpg");
INSERT INTO panos VALUES("54","1","1","2014-03-26 11:37:39","front6.jpg");
INSERT INTO panos VALUES("55","1","1","2014-03-26 11:40:07","front5.jpg");
INSERT INTO panos VALUES("56","1","1","2014-03-26 11:40:11","front6.jpg");
INSERT INTO panos VALUES("57","1","1","2014-03-26 11:43:45","front5.jpg");
INSERT INTO panos VALUES("58","1","1","2014-03-26 11:44:19","front6.jpg");
INSERT INTO panos VALUES("59","1","1","2014-03-26 11:59:37","front5.jpg");
INSERT INTO panos VALUES("60","1","1","2014-03-26 12:00:23","front5.jpg");
INSERT INTO panos VALUES("61","1","1","2014-03-26 12:01:27","front5.jpg");
INSERT INTO panos VALUES("62","1","1","2014-03-26 12:03:21","front5.jpg");
INSERT INTO panos VALUES("63","1","1","2014-03-26 12:05:49","front5.jpg");
INSERT INTO panos VALUES("64","1","1","2014-03-26 12:06:58","front5.jpg");
INSERT INTO panos VALUES("65","1","1","2014-03-26 12:10:01","front5.jpg");
INSERT INTO panos VALUES("66","1","1","2014-03-26 12:13:08","front5.jpg");
INSERT INTO panos VALUES("67","1","1","2014-03-26 12:13:48","311logo.jpg");
INSERT INTO panos VALUES("68","1","1","2014-03-26 12:18:55","front5.jpg");
INSERT INTO panos VALUES("69","1","1","2014-03-26 12:21:19","front5.jpg");
INSERT INTO panos VALUES("70","1","1","2014-03-26 12:22:48","front6.jpg");
INSERT INTO panos VALUES("71","1","1","2014-03-26 14:47:16","311logo.jpg");
INSERT INTO panos VALUES("72","1","1","2014-03-26 14:59:52","311logo.jpg");
INSERT INTO panos VALUES("73","1","1","2014-03-26 15:00:01","biohazard.jpg");
INSERT INTO panos VALUES("74","1","1","2014-03-26 15:11:05","front5.jpg");
INSERT INTO panos VALUES("75","1","1","2014-03-26 15:11:10","front6.jpg");
INSERT INTO panos VALUES("76","1","1","2014-03-26 15:13:58","front6.jpg");
INSERT INTO panos VALUES("77","1","1","2014-03-27 08:52:04","12_baja.jpg");
INSERT INTO panos VALUES("78","1","1","2014-03-27 08:54:06","cajonAzul-dic2012-02.jpg");
INSERT INTO panos VALUES("79","1","1","2014-03-27 08:55:37","12.jpg");
INSERT INTO panos VALUES("80","1","1","2014-03-27 10:38:40","12_baja.jpg");
INSERT INTO panos VALUES("81","1","1","2014-03-27 10:39:15","yaddo_gardens_01 Panorama.jpg");
INSERT INTO panos VALUES("82","1","1","2014-03-27 10:41:05","cajonAzul-dic2012-02.jpg");
INSERT INTO panos VALUES("83","1","1","2014-03-27 10:42:31","12.jpg");
INSERT INTO panos VALUES("84","1","1","2014-03-27 10:42:37","yaddo_gardens_01 Panorama - Copy.jpg");
INSERT INTO panos VALUES("85","1","1","2014-03-27 10:42:53","yaddo_gardens_01 Panorama.jpg");
INSERT INTO panos VALUES("86","1","1","2014-03-27 10:43:00","yaddo_gardens_01 Panorama - Copy (2).jpg");
INSERT INTO panos VALUES("87","1","1","2014-03-27 10:52:38","yaddo_gardens_01 Panorama - Copy (2).jpg");
INSERT INTO panos VALUES("88","1","1","2014-03-27 10:52:41","yaddo_gardens_01 Panorama - Copy.jpg");
INSERT INTO panos VALUES("89","1","1","2014-03-27 10:52:41","yaddo_gardens_01 Panorama.jpg");
INSERT INTO panos VALUES("90","1","1","2014-03-28 08:37:49","yaddo_gardens_01 Panorama - Copy.jpg");
INSERT INTO panos VALUES("91","1","1","2014-03-28 08:52:02","island.jpg");
INSERT INTO panos VALUES("92","1","1","2014-03-28 08:52:23","forest).jpg");
INSERT INTO panos VALUES("93","0","1","2014-04-01 06:24:13","12.jpg");
INSERT INTO panos VALUES("94","1","1","2014-04-01 06:29:24","12_baja.jpg");
INSERT INTO panos VALUES("95","1","1","2014-04-06 15:15:47","12_baja.jpg");
INSERT INTO panos VALUES("96","1","1","2014-04-06 15:33:20","12.jpg");
INSERT INTO panos VALUES("97","1","1","2014-04-08 11:17:14","front0.jpg");
INSERT INTO panos VALUES("98","1","1","2014-04-08 11:17:27","12_baja.jpg");
INSERT INTO panos VALUES("99","1","1","2014-04-08 11:19:22","front0.jpg");
INSERT INTO panos VALUES("100","1","1","2014-04-08 11:21:09","front0.jpg");
INSERT INTO panos VALUES("101","1","1","2014-04-08 11:25:54","12_baja.jpg");
INSERT INTO panos VALUES("102","1","1","2014-04-08 11:27:09","12_baja.jpg");
INSERT INTO panos VALUES("103","1","1","2014-04-08 11:28:32","12_baja.jpg");
INSERT INTO panos VALUES("104","1","1","2014-04-24 10:16:42","americade_2011_01 Panorama.jpg");
INSERT INTO panos VALUES("105","1","1","2014-04-28 13:46:04","americade_2011_01 Panorama.jpg");
INSERT INTO panos VALUES("106","1","1","2014-04-28 13:55:04","island_upload_test.jpg");
INSERT INTO panos VALUES("107","1","1","2014-04-28 13:56:53","americade_2011_01_b Panorama.jpg");
INSERT INTO panos VALUES("108","1","1","2014-05-12 07:41:15","test.jpg");
INSERT INTO panos VALUES("109","1","6","2014-05-12 07:47:23","test.jpg");
INSERT INTO panos VALUES("110","1","6","2014-05-12 07:50:32","test.jpg");
INSERT INTO panos VALUES("111","1","6","2014-05-12 07:52:09","test.jpg");
INSERT INTO panos VALUES("112","1","6","2014-05-12 07:53:16","test.jpg");
INSERT INTO panos VALUES("113","1","6","2014-05-12 07:53:42","test.jpg");
INSERT INTO panos VALUES("114","1","6","2014-05-12 07:54:35","test.jpg");
INSERT INTO panos VALUES("115","1","6","2014-05-12 07:55:33","test.jpg");
INSERT INTO panos VALUES("116","1","6","2014-05-12 07:56:55","test.jpg");
INSERT INTO panos VALUES("117","1","6","2014-05-12 07:57:55","test.jpg");
INSERT INTO panos VALUES("118","1","6","2014-05-12 07:58:15","test.jpg");
INSERT INTO panos VALUES("119","1","6","2014-05-12 08:22:02","test.jpg");
INSERT INTO panos VALUES("120","1","18","2014-05-16 08:55:17","12_baja.jpg");
INSERT INTO panos VALUES("121","1","18","2014-05-16 08:56:55","cajonAzul-dic2012-02.jpg");
INSERT INTO panos VALUES("122","1","18","2014-05-16 09:04:35","12.jpg");
INSERT INTO panos VALUES("123","1","18","2014-05-16 09:51:16","12_baja.jpg");
INSERT INTO panos VALUES("124","1","18","2014-05-16 09:52:19","cajonAzul-dic2012-02.jpg");



DROP TABLE panosxtour;

CREATE TABLE `panosxtour` (
  `ord` int(11) NOT NULL,
  `idpano` int(11) NOT NULL,
  `idtour` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `id` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO panosxtour VALUES("0","2","4","minneapolis twilight 2x1-L.jpg","1","1");
INSERT INTO panosxtour VALUES("0","3","5","minneapolis twilight 2x1-L.jpg","2","1");
INSERT INTO panosxtour VALUES("0","4","6","minneapolis twilight 2x1-L.jpg","3","1");
INSERT INTO panosxtour VALUES("0","5","7","url.jpeg","4","1");
INSERT INTO panosxtour VALUES("0","6","6","f9d_12872_17660625.jpeg","5","1");
INSERT INTO panosxtour VALUES("0","7","6","minneapolis twilight 2x1-L.jpg","6","1");
INSERT INTO panosxtour VALUES("1","8","9","12_baja.jpg","7","1");
INSERT INTO panosxtour VALUES("0","9","9","Montañita2asd","8","1");
INSERT INTO panosxtour VALUES("0","10","8","311logo.jpgsdf","9","1");
INSERT INTO panosxtour VALUES("1","11","8","biohazard.","10","1");
INSERT INTO panosxtour VALUES("1","13","10","front5.jpg","12","1");
INSERT INTO panosxtour VALUES("2","12","8","311logo.jpgaaa","11","1");
INSERT INTO panosxtour VALUES("0","14","10","front4.jpg","13","1");
INSERT INTO panosxtour VALUES("0","15","11","311logo.jpg","14","1");
INSERT INTO panosxtour VALUES("0","16","12","311logo.jpg","15","1");
INSERT INTO panosxtour VALUES("0","17","13","311logo.jpg","16","1");
INSERT INTO panosxtour VALUES("0","18","14","311logo.jpg","17","1");
INSERT INTO panosxtour VALUES("0","19","15","escena","18","1");
INSERT INTO panosxtour VALUES("0","20","16","asdasdsdasd","19","1");
INSERT INTO panosxtour VALUES("0","21","16","add","20","1");
INSERT INTO panosxtour VALUES("0","22","16","big","21","1");
INSERT INTO panosxtour VALUES("0","23","16","big","22","1");
INSERT INTO panosxtour VALUES("0","24","16","biohazard","23","1");
INSERT INTO panosxtour VALUES("0","25","16","big","24","1");
INSERT INTO panosxtour VALUES("0","26","16","biohazard","25","1");
INSERT INTO panosxtour VALUES("0","27","18","yaddo_gardens_01 Panorama","26","1");
INSERT INTO panosxtour VALUES("1","28","17","Forest and Mountain","27","1");
INSERT INTO panosxtour VALUES("0","40","21","front5","34","1");
INSERT INTO panosxtour VALUES("2","29","17","Mountain","28","1");
INSERT INTO panosxtour VALUES("0","30","19","Yaddo Gardens","29","1");
INSERT INTO panosxtour VALUES("0","32","19","Coney Island Beach","31","1");
INSERT INTO panosxtour VALUES("0","39","20","front5","33","1");
INSERT INTO panosxtour VALUES("0","33","17","mountain 2","32","1");
INSERT INTO panosxtour VALUES("0","41","22","front5","35","1");
INSERT INTO panosxtour VALUES("0","42","22","front6","36","1");
INSERT INTO panosxtour VALUES("0","44","23","front5","37","1");
INSERT INTO panosxtour VALUES("0","45","23","front6","38","1");
INSERT INTO panosxtour VALUES("1","52","25","front6","41","1");
INSERT INTO panosxtour VALUES("0","51","25","front5","40","1");
INSERT INTO panosxtour VALUES("0","48","24","311logo","39","1");
INSERT INTO panosxtour VALUES("0","57","26","front5","42","1");
INSERT INTO panosxtour VALUES("1","58","26","front6","43","1");
INSERT INTO panosxtour VALUES("0","69","31","front5","44","1");
INSERT INTO panosxtour VALUES("1","70","31","front6","45","1");
INSERT INTO panosxtour VALUES("0","114","50","test","74","1");
INSERT INTO panosxtour VALUES("0","104","46","americade_2011_01 Panorama","70","1");
INSERT INTO panosxtour VALUES("1","78","33","Cajón del azul","50","1");
INSERT INTO panosxtour VALUES("0","77","33","Montaña 1","49","1");
INSERT INTO panosxtour VALUES("2","79","33","Montaña 2","51","1");
INSERT INTO panosxtour VALUES("0","80","35","12_baja","52","1");
INSERT INTO panosxtour VALUES("2","81","34","Pano 3.5","53","1");
INSERT INTO panosxtour VALUES("1","91","36","2","60","1");
INSERT INTO panosxtour VALUES("1","82","35","cajonAzul-dic2012-02","54","1");
INSERT INTO panosxtour VALUES("2","83","35","12","55","1");
INSERT INTO panosxtour VALUES("1","87","34","Pano 3","56","1");
INSERT INTO panosxtour VALUES("0","88","34","Pano 2","57","1");
INSERT INTO panosxtour VALUES("3","89","34","Pano 4","58","1");
INSERT INTO panosxtour VALUES("2","90","36","3","59","1");
INSERT INTO panosxtour VALUES("0","92","36","1","61","1");
INSERT INTO panosxtour VALUES("0","93","37","12","62","0");
INSERT INTO panosxtour VALUES("1","94","37","12_baja","63","1");
INSERT INTO panosxtour VALUES("0","96","38","Montaña","64","1");
INSERT INTO panosxtour VALUES("0","97","39","front0","65","1");
INSERT INTO panosxtour VALUES("0","99","41","front0","66","1");
INSERT INTO panosxtour VALUES("0","100","42","front0","67","1");
INSERT INTO panosxtour VALUES("0","101","43","12_baja","68","1");
INSERT INTO panosxtour VALUES("0","102","44","","69","1");
INSERT INTO panosxtour VALUES("0","106","48","island_upload_test","71","1");
INSERT INTO panosxtour VALUES("0","113","49","test","73","1");
INSERT INTO panosxtour VALUES("0","112","49","test","72","1");
INSERT INTO panosxtour VALUES("0","116","52","test","75","1");
INSERT INTO panosxtour VALUES("0","117","53","test","76","1");
INSERT INTO panosxtour VALUES("0","118","54","test","77","1");
INSERT INTO panosxtour VALUES("0","119","51","test","78","1");
INSERT INTO panosxtour VALUES("0","124","55","cajonAzul-dic2012-02","80","1");
INSERT INTO panosxtour VALUES("1","123","55","12_baja","79","1");



DROP TABLE tags;

CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

INSERT INTO tags VALUES("1","Montaña");
INSERT INTO tags VALUES("2","mountain");
INSERT INTO tags VALUES("3","argentina");
INSERT INTO tags VALUES("4","bariloche");
INSERT INTO tags VALUES("5","patagonia");
INSERT INTO tags VALUES("6","tag1");
INSERT INTO tags VALUES("7","gf");
INSERT INTO tags VALUES("8","gardens");
INSERT INTO tags VALUES("9","yaddo");
INSERT INTO tags VALUES("10","test");
INSERT INTO tags VALUES("11","garden");
INSERT INTO tags VALUES("12","flowers");
INSERT INTO tags VALUES("13","");
INSERT INTO tags VALUES("14","test HB");
INSERT INTO tags VALUES("15","Code Igniter");
INSERT INTO tags VALUES("16","fwe");
INSERT INTO tags VALUES("17","swq");
INSERT INTO tags VALUES("18","vds");
INSERT INTO tags VALUES("19","asdasd");
INSERT INTO tags VALUES("20","dwed");
INSERT INTO tags VALUES("21","111");
INSERT INTO tags VALUES("22","haha");
INSERT INTO tags VALUES("23","this looks good");
INSERT INTO tags VALUES("24","great job guys!!!");
INSERT INTO tags VALUES("25","tour");
INSERT INTO tags VALUES("26","mountain lion");
INSERT INTO tags VALUES("27","beautiful");
INSERT INTO tags VALUES("28","Saratoga");
INSERT INTO tags VALUES("29","Cajón Azul");
INSERT INTO tags VALUES("30","Patagonia Argentina");
INSERT INTO tags VALUES("31","El Bolsón");
INSERT INTO tags VALUES("32","Cerro López");
INSERT INTO tags VALUES("33","360");
INSERT INTO tags VALUES("34","sdsdsd");
INSERT INTO tags VALUES("35","motorcycle");
INSERT INTO tags VALUES("36","lake george");
INSERT INTO tags VALUES("37","americade");



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
  `state` varchar(50) NOT NULL,
  `version_xml` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO tours VALUES("9","Montaña Ariel","","Ruta Provincial 11, Tercero Arriba Department, Cordoba, Argentina","Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque elementum, est eu dapibus condimentum, mauris massa ultrices nunc, eget suscipit elit purus accumsan leo. Fusce tristique ultricies purus id ullamcorper. Maecenas ac risus et ipsum malesuada tempus. Integer auctor nunc at euismod gravida. Pellentesque diam tellus, aliquet ut metus et, faucibus porta massa. Aenean convallis, arcu eget tempus auctor, massa sapien vulputate magna, eget volutpat ligula tortor in lectus. Phasellus nunc nibh, gravida vel sollicitudin sed, ornare sit amet tellus. Curabitur sed nunc enim. Sed eget nunc dui. Praesent eu augue arcu. Cras elementum nisl in tellus fermentum vehicula.\n","Destinations","Montaña,mountain,argentina,bariloche,patagonia","-32.66527320406991","-63.984375","on","on","on","","_public","1","8","1","Spinattic","0","2014-03-27 11:10:32","publish","5");
INSERT INTO tours VALUES("10","Nuevo Tour","","Kapiri Mposhi, Central, Zambia","Una descripción","Hotels","tag1","-14.059854584689559","28.125","on","on","on","","_public","1","6","1","Spinattic","0","2014-03-27 10:52:27","draft","5");
INSERT INTO tours VALUES("34","This is the test tour. 3/27/14",""," NY 13601, Interstate 81,  NY 13606, Adams Center, Watertown,  NY, Jefferson County,  USA, New York, United States","description of the tour\n","Destinations","great job guys!!!,mountain lion","43.90729248239986","-75.9814453125","on","on","on","on","_public","0","3","1","Spinattic","0","2014-03-27 11:43:03","publish","7");
INSERT INTO tours VALUES("35","Tour Ariel test 222",""," Santa Cruz Province, Ruta Nacional 26, Sarmiento Department, Chubut Province, Argentina","description of the tour description of the tour description of the tour description of the tour ","Destinations","great job guys!!!,mountain,patagonia","-45.98169518512228","-69.609375","on","on","on","on","_public","0","1","1","Spinattic","0","2014-03-27 11:06:29","publish","3");
INSERT INTO tours VALUES("36","Yaddo Garden","","8-32 Henning Road,  NY 12866, Saratoga Springs,  NY, Saratoga County,  USA, New York, United States","Beautiful location in Saratoga!","Nature","beautiful,Yaddo,Saratoga","43.07873710124251","-73.75632762908936","on","on","on","on","_public","2","8","1","Spinattic","0","2014-03-28 08:54:15","publish","2");
INSERT INTO tours VALUES("15","test 15","","Unnamed Road, Bonnyville No. 87,  AB T0A 2E0, La Corey,  AB, Division No. 12, Alberta T0A, Alberta, Canada","jk","Hotels","gf","54.61280241527344","-110.390625","on","on","on","","_public","0","4","1","Spinattic","0","2014-03-18 16:56:19","publish","1");
INSERT INTO tours VALUES("33","Tour test 2","","Ruta Nacional 22, Avellaneda Department, Río Negro Province, Argentina","Tour text description here Tour text description here Tour text description here Tour text description here Tour text description here Tour text description here Tour text description here Tour text description here Tour text description here Tour text description here Tour text description here Tour text description here Tour text description here Tour text description here Tour text description here Tour text description here ","Destinations","mountain,montaña,tour,test,patagonia","-39.266284422130646","-65.390625","on","on","on","on","_public","0","1","1","Spinattic","0","2014-03-27 08:57:13","draft","3");
INSERT INTO tours VALUES("17","Tour Ariel test","","Ruta Provincial 13, Ultracan, La Pampa Province, Argentina","lala la la la la allaaa lala la la la la allaaa lala la la la la allaaa lala la la la la allaaa lala la la la la allaaa lala la la la la allaaa lala la la la la allaaa lala la la la la allaaa lala la la la la allaaa lala la la la la allaaa ","Destinations","Montaña,mountain,argentina,bariloche,patagonia","-37.0551771066608","-65.390625","on","on","on","","_public","1","4","1","Spinattic","0","2014-03-27 08:48:12","publish","2");
INSERT INTO tours VALUES("18","Yaddo Test","","7 Concord Drive,  NY 12866, Saratoga Springs,  NY, Saratoga County,  アルバニー＝スケネクタディ＝トロイ,  USA, New York, United States","Yaddo gardens in Saratoga Springs","Destinations","gardens, yaddo, test","43.08300589207029","-73.75336647033691","on","on","on","","_public","0","5","1","Spinattic","0","2014-03-24 12:17:02","publish","1");
INSERT INTO tours VALUES("19","Yaddo gardens","","101-111 County Road 80, 111 Round Lake Road,  NY 12019, Ballston Lake,  NY, Saratoga County,  アルバニー＝スケネクタディ＝トロイ,  USA, New York, United States","gardens","Destinations","garden, flowers, ","42.93508455712815","-73.82589340209961","on","on","on","on","_public","3","8","1","Spinattic","0","2014-03-24 12:21:22","publish","1");
INSERT INTO tours VALUES("38","Tour test Ariel 06/04",""," San Carlos de Bariloche, Cacique Prayel 4301-4399, Bariloche, Río Negro Province, Argentina","Tour description - Tour test Ariel 06/04 Tour description - Tour test Ariel 06/04 Tour description - Tour test Ariel 06/04 Tour description - Tour test Ariel 06/04 Tour description - Tour test Ariel 06/04 Tour description - Tour test Ariel 06/04 ","Destinations","tour,mountain,360,bariloche","-41.15345460108329","-71.3397216796875","on","on","on","on","_public","0","2","1","Spinattic","0","2014-04-06 15:36:20","publish","2");
INSERT INTO tours VALUES("37","Cerro López","","Ruta Nacional 40, Bariloche, Río Negro Province, Argentina","This is a panorama picture from Río Negro, Argentina.","Destinations","Patagonia Argentina,bariloche,Cerro López","-41.950936945218","-71.52923583984375","on","on","","on","_public","0","11","1","Spinattic","0","2014-04-02 12:35:12","publish","4");
INSERT INTO tours VALUES("45","Untitled 45","","","","","","","","","","","","","0","0","1","Spinattic","0","2014-04-24 10:14:59","draft","0");
INSERT INTO tours VALUES("46","Americade 2011","","21 Mohican Street,  NY 12845, Lake George,  NY, Warren County, Glens Falls,  USA, New York, United States","Americade Festival in Lake George, NY_June 2011","Events","motorcycle,lake george,americade","43.42119585198685","-73.71620178222656","on","on","on","on","_public","1","10","1","Spinattic","0","2014-04-24 11:07:04","publish","4");
INSERT INTO tours VALUES("39","Untitled 39","","","","","","","","","","","","_public","0","0","1","Spinattic","0","2014-04-08 11:17:09","draft","0");
INSERT INTO tours VALUES("41","Untitled 41","","","","","","","","","","","","_public","0","0","1","Spinattic","0","2014-04-08 11:19:17","draft","0");
INSERT INTO tours VALUES("42","Untitled 42","","","","","","","","","","","","_public","0","0","1","Spinattic","0","2014-04-08 11:21:04","draft","0");
INSERT INTO tours VALUES("43","Untitled 43","","","","","","","","","","","","_public","0","0","1","Spinattic","0","2014-04-08 11:25:40","draft","0");
INSERT INTO tours VALUES("44","Untitled 44","","","","","","","","","","","","_public","0","1","1","Spinattic","0","2014-04-08 11:26:54","draft","0");
INSERT INTO tours VALUES("49","Untitled 49","","","","","","","","","","","","","0","0","1","Spinattic","0","2014-05-12 07:53:15","draft","0");
INSERT INTO tours VALUES("48","Untitled 48","","","","","","","","","","","","","0","0","1","Spinattic","0","2014-04-28 13:52:38","draft","0");
INSERT INTO tours VALUES("50","Untitled 50","","","","","","","","","","","","","0","0","1","Spinattic","0","2014-05-12 07:54:33","draft","0");
INSERT INTO tours VALUES("51","Untitled 51 nuevo","","","","","","","","","","","","_public","0","0","6","Swing Band","0","2014-05-12 08:22:18","draft","1");
INSERT INTO tours VALUES("52","Untitled 52","","","","","","","","","","","","","0","0","6","Spinattic","0","2014-05-12 07:56:54","draft","0");
INSERT INTO tours VALUES("53","un toru","","","","","","","","","","","","_public","0","0","6","Swing Band","0","2014-05-12 08:21:43","draft","1");
INSERT INTO tours VALUES("54","Untitled 54","","","","","","","","","","","","","0","0","6","Swing Band","0","2014-05-12 07:58:14","draft","0");
INSERT INTO tours VALUES("55","mi tour","","Lihuel Calel, La Pampa Province, Argentina","lalla","Miscellaneous","Montaña","-38.169114135560854","-65.390625","on","on","on","on","_public","1","2","18","Santiago Marull","0","2014-05-16 11:35:49","publish","2");



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
  `sol_date` datetime NOT NULL,
  `hashregistro` varchar(500) NOT NULL,
  `fnac` varchar(10) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `avatar` varchar(500) NOT NULL DEFAULT 'avatar.jpg',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

INSERT INTO users VALUES("1","Spinattic","hbiancardi@hotmail.com","","","","www.rockandrule.com.ar","","","e10adc3949ba59abbe56e057f20f883e","0000-00-00 00:00:00","","10/11/1975","1","1.jpg");
INSERT INTO users VALUES("14","Ariel Gustavo Micheletti","arielgfoto@gmail.com","Argentina","Santa Fe","Rosario","www.ciudadesferica.com","arielmch","arielmch","ea66295af3bab4394e845dbcab6d2370","2014-05-14 11:51:42","b927e595d5d8029a93dfea946b9f4729","03/10/1983","1","avatar.jpg");
INSERT INTO users VALUES("7","Ariel Micheletti","ariel@ciudadesferica.com","Argentina","Santa Fe","Rosario","www.ciudadesferica.com","arielmch","arielmch","0cc175b9c0f1b6a831c399e269772661","2014-05-16 11:48:22","ee5410bf96b02c156f2a57afbae0963f","","1","avatar.jpg");
INSERT INTO users VALUES("15","Verónica Peyrano","veronicapeyrano@hotmail.com","","","","","","","c2dfee78ffc7b5dc9e6268d7aa721a56","2014-05-14 11:59:16","82b87dfb1e9b19bbd548b7269e83a5d5","","1","avatar.jpg");
INSERT INTO users VALUES("17","test user","neoswingarg@gmail.com","","","","","","","e10adc3949ba59abbe56e057f20f883e","2014-05-15 08:19:21","67aea3e3ba8a94c42bf27afab273c5a9","","1","avatar.jpg");
INSERT INTO users VALUES("18","Santiago Marull","santiagomarull@gmail.com","Argentina","Santa Fe","Rosario","www.santiagomarull.com","santiagomarull","santiagomarull","f501a8928398ff5210fd486a248e1a52","2014-05-16 08:46:59","96e0c70d73204c2b7d434578373494ad","","1","avatar.jpg");
INSERT INTO users VALUES("19","Derrick Clark","dclark@virtualmedia360.net","United States of America","New York","Glens Falls","virtualmedia360.net","derrick_clark_","derrick.clark.37","06787233f0dd3c043cd5a61cba01a741","2014-05-16 12:47:27","6fed49ca9b47a88ad80383a8e47cd716","03/10/1985","1","avatar.jpg");
INSERT INTO users VALUES("20","Derrick Clark","derrickrayclark@gmail.com","Uzbekistan","New York","Glens Falls","virtualmedia360.net","","","06787233f0dd3c043cd5a61cba01a741","2014-05-19 11:32:50","781a7b611d71700a2569898cc082de79","03/10/1985","1","avatar.jpg");
INSERT INTO users VALUES("21","Ariel Perez","arielgmch@gmail.com","","","","","","","ea66295af3bab4394e845dbcab6d2370","2014-05-19 11:33:15","fce2c002d818d1eb7d6652099f3c2141","","1","avatar.jpg");



DROP TABLE views;

CREATE TABLE `views` (
  `idtour` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO views VALUES("26"," 190.192.38.95","2014-03-14 09:56:44");
INSERT INTO views VALUES("1"," 190.192.38.95","2014-03-14 11:33:44");
INSERT INTO views VALUES("2"," 190.192.38.95","2014-03-14 12:00:56");
INSERT INTO views VALUES("40"," 190.192.38.95","2014-03-14 12:01:04");
INSERT INTO views VALUES("1"," 190.195.223.5","2014-03-16 16:56:38");
INSERT INTO views VALUES("2"," 190.195.223.5","2014-03-16 16:57:45");
INSERT INTO views VALUES("3"," 190.195.223.5","2014-03-16 16:57:54");
INSERT INTO views VALUES("9"," 190.138.180.90","2014-03-18 07:22:10");
INSERT INTO views VALUES("9"," 190.210.29.49","2014-03-18 07:32:39");
INSERT INTO views VALUES("9"," 173.252.120.112","2014-03-18 07:32:45");
INSERT INTO views VALUES("14"," 190.210.29.49","2014-03-18 14:25:14");
INSERT INTO views VALUES("10"," 190.195.223.5","2014-03-18 15:34:01");
INSERT INTO views VALUES("10"," 173.252.120.117","2014-03-18 15:34:08");
INSERT INTO views VALUES("10"," 181.109.123.78","2014-03-18 16:21:41");
INSERT INTO views VALUES("15"," 190.195.223.5","2014-03-18 16:56:26");
INSERT INTO views VALUES("15"," 173.252.120.112","2014-03-18 16:56:32");
INSERT INTO views VALUES("17"," 190.138.180.90","2014-03-24 12:17:05");
INSERT INTO views VALUES("18"," 72.43.12.186","2014-03-24 12:17:38");
INSERT INTO views VALUES("18"," 69.171.237.15","2014-03-24 12:17:41");
INSERT INTO views VALUES("18"," 190.138.180.90","2014-03-24 12:19:21");
INSERT INTO views VALUES("19"," 72.43.12.186","2014-03-24 12:21:36");
INSERT INTO views VALUES("19"," 69.171.237.13","2014-03-24 12:21:37");
INSERT INTO views VALUES("19"," 190.138.180.90","2014-03-25 10:36:01");
INSERT INTO views VALUES("8"," 190.210.29.49","2014-03-25 10:39:40");
INSERT INTO views VALUES("1"," 190.210.29.49","2014-03-25 10:39:48");
INSERT INTO views VALUES("10"," 190.210.29.49","2014-03-25 10:40:02");
INSERT INTO views VALUES("10"," 190.195.223.5","2014-03-25 11:43:24");
INSERT INTO views VALUES("10"," 173.252.120.114","2014-03-26 05:36:26");
INSERT INTO views VALUES("9"," 190.210.29.49","2014-03-26 06:06:35");
INSERT INTO views VALUES("9"," 173.252.120.117","2014-03-26 06:06:38");
INSERT INTO views VALUES("7"," 190.210.29.49","2014-03-26 06:33:13");
INSERT INTO views VALUES("19"," 190.210.29.49","2014-03-26 07:08:53");
INSERT INTO views VALUES("33"," 190.195.223.5","2014-03-26 15:15:33");
INSERT INTO views VALUES("19"," 190.195.223.5","2014-03-26 15:43:31");
INSERT INTO views VALUES("18"," 190.195.223.5","2014-03-26 15:58:26");
INSERT INTO views VALUES("17"," 181.14.159.176","2014-03-27 08:48:20");
INSERT INTO views VALUES("32"," 72.43.12.186","2014-03-27 08:51:03");
INSERT INTO views VALUES("33"," 181.14.159.176","2014-03-27 08:58:52");
INSERT INTO views VALUES("32"," 181.14.159.176","2014-03-27 09:02:46");
INSERT INTO views VALUES("9"," 181.14.159.176","2014-03-27 09:04:16");
INSERT INTO views VALUES("11"," 190.210.29.49","2014-03-27 10:32:59");
INSERT INTO views VALUES("34"," 72.43.12.186","2014-03-27 11:02:40");
INSERT INTO views VALUES("34"," 173.252.101.112","2014-03-27 11:02:42");
INSERT INTO views VALUES("35"," 181.14.159.176","2014-03-27 11:06:42");
INSERT INTO views VALUES("36"," 181.14.159.176","2014-03-27 11:50:22");
INSERT INTO views VALUES("36"," 72.43.12.186","2014-03-28 08:54:53");
INSERT INTO views VALUES("36"," 173.252.101.117","2014-03-28 08:54:55");
INSERT INTO views VALUES("37"," 181.14.159.176","2014-04-01 06:37:13");
INSERT INTO views VALUES("17"," 72.43.12.186","2014-04-02 12:07:38");
INSERT INTO views VALUES("17"," 173.252.120.113","2014-04-02 12:07:41");
INSERT INTO views VALUES("37"," 72.43.12.186","2014-04-02 12:18:26");
INSERT INTO views VALUES("37"," 173.252.120.119","2014-04-02 12:18:29");
INSERT INTO views VALUES("37"," 190.30.242.146","2014-04-02 17:40:28");
INSERT INTO views VALUES("37"," 190.195.223.5","2014-04-02 17:47:58");
INSERT INTO views VALUES("38"," 190.30.242.146","2014-04-06 15:19:34");
INSERT INTO views VALUES("36"," 181.14.159.176","2014-04-08 11:40:25");
INSERT INTO views VALUES("36"," 181.14.152.219","2014-04-08 12:32:40");
INSERT INTO views VALUES("38"," 190.195.223.5","2014-04-08 12:49:10");
INSERT INTO views VALUES("19"," 67.248.2.240","2014-04-10 13:02:40");
INSERT INTO views VALUES("19"," 66.220.152.112","2014-04-10 13:02:42");
INSERT INTO views VALUES("37"," 67.248.2.240","2014-04-10 13:03:46");
INSERT INTO views VALUES("37"," 66.220.152.119","2014-04-10 13:03:49");
INSERT INTO views VALUES("15"," 67.248.2.240","2014-04-10 13:08:58");
INSERT INTO views VALUES("15"," 66.220.152.115","2014-04-10 13:08:59");
INSERT INTO views VALUES("9"," 72.43.12.186","2014-04-10 13:10:40");
INSERT INTO views VALUES("9"," 173.252.120.116","2014-04-10 13:10:44");
INSERT INTO views VALUES("37"," 181.14.152.219","2014-04-10 13:13:14");
INSERT INTO views VALUES("44"," 190.195.223.5","2014-04-22 14:26:30");
INSERT INTO views VALUES("37"," 190.195.223.5","2014-04-22 14:26:51");
INSERT INTO views VALUES("37"," 173.252.120.117","2014-04-22 14:26:57");
INSERT INTO views VALUES("46"," 67.248.2.240","2014-04-24 11:07:25");
INSERT INTO views VALUES("46"," 173.252.101.116","2014-04-24 11:07:27");
INSERT INTO views VALUES("46"," 72.43.12.186","2014-04-24 11:08:51");
INSERT INTO views VALUES("46"," 96.39.30.170","2014-05-04 07:16:03");
INSERT INTO views VALUES("46"," 69.171.230.113","2014-05-04 07:16:11");
INSERT INTO views VALUES("46"," 200.3.94.38","2014-05-06 06:44:46");
INSERT INTO views VALUES("8"," 200.3.94.38","2014-05-06 06:47:57");
INSERT INTO views VALUES("46"," 200.3.95.35","2014-05-06 07:28:10");
INSERT INTO views VALUES("36"," 201.252.112.170","2014-05-07 11:40:44");
INSERT INTO views VALUES("46"," 201.252.112.170","2014-05-07 12:12:56");
INSERT INTO views VALUES("46"," 190.195.223.5","2014-05-10 14:48:17");
INSERT INTO views VALUES("36"," 200.3.94.119","2014-05-12 08:22:34");
INSERT INTO views VALUES("34"," 200.3.94.34","2014-05-15 10:07:06");
INSERT INTO views VALUES("55"," 190.225.151.18","2014-05-16 09:00:16");
INSERT INTO views VALUES("36"," 190.225.151.18","2014-05-16 09:17:47");
INSERT INTO views VALUES("37"," 72.43.12.186","2014-05-16 12:45:07");
INSERT INTO views VALUES("18"," 72.43.12.186","2014-05-16 13:32:01");
INSERT INTO views VALUES("55"," 190.225.151.18","2014-05-19 11:59:48");
INSERT INTO views VALUES("19"," 72.43.12.186","2014-05-19 12:00:18");
INSERT INTO views VALUES("46"," 190.225.151.18","2014-05-19 12:02:28");
INSERT INTO views VALUES("36"," 72.43.12.186","2014-05-19 12:06:23");
INSERT INTO views VALUES("55"," 72.43.12.186","2014-05-19 12:15:58");



