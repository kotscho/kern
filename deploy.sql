-- MySQL dump 10.13  Distrib 5.1.62, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: kern
-- ------------------------------------------------------
-- Server version	5.1.62-0ubuntu0.11.10.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `application_nodes`
--
-- whatever/test ==================================

DROP TABLE IF EXISTS `deployer_test`;
CREATE TABLE `deployer_test` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `dummy` varchar(20) NOT NULL,
  PRIMARY KEY(`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

DROP TABLE IF EXISTS `application_nodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_nodes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(20) NOT NULL,
  `parent_ID` int(11) NOT NULL DEFAULT '0',
  `startdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `description` varchar(200) NOT NULL,
  `type` enum('public','member') NOT NULL DEFAULT 'public',
  `stagename` varchar(80) NOT NULL,
  `mods` text NOT NULL COMMENT 'mods stores the module id and the module position of the modules assigned to a specific cathegory',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=143 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `application_nodes`
--

LOCK TABLES `application_nodes` WRITE;
/*!40000 ALTER TABLE `application_nodes` DISABLE KEYS */;
INSERT INTO `application_nodes` VALUES (140,'pub',0,1324072800,1324072800,'bubi','test','member','stage',''),(142,'pub',140,1324072800,1324072800,'bubichild','child of bubi','member','stage','');
/*!40000 ALTER TABLE `application_nodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `application_nodes_material`
--

DROP TABLE IF EXISTS `application_nodes_material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_nodes_material` (
  `APPLICATION_NODES_ID` int(11) NOT NULL,
  `MATERIAL_ID` int(11) NOT NULL,
  `CREATED` int(11) NOT NULL,
  `CREATED_BY` int(11) NOT NULL,
  PRIMARY KEY (`APPLICATION_NODES_ID`,`MATERIAL_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `application_nodes_material`
--

LOCK TABLES `application_nodes_material` WRITE;
/*!40000 ALTER TABLE `application_nodes_material` DISABLE KEYS */;
/*!40000 ALTER TABLE `application_nodes_material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `application_nodes_types`
--

DROP TABLE IF EXISTS `application_nodes_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_nodes_types` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `application_nodes_types`
--

LOCK TABLES `application_nodes_types` WRITE;
/*!40000 ALTER TABLE `application_nodes_types` DISABLE KEYS */;
INSERT INTO `application_nodes_types` VALUES (1,'main-menu'),(2,'top-menu'),(3,'bottom-menu'),(4,'right-menu'),(5,'left-menu');
/*!40000 ALTER TABLE `application_nodes_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authors`
--

DROP TABLE IF EXISTS `authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authors` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(100) NOT NULL,
  `WHOAMI` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authors`
--

LOCK TABLES `authors` WRITE;
/*!40000 ALTER TABLE `authors` DISABLE KEYS */;
INSERT INTO `authors` VALUES (7,'dummy','dummy author...');
/*!40000 ALTER TABLE `authors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categs`
--

DROP TABLE IF EXISTS `categs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESCR` varchar(60) NOT NULL,
  `LINK` varchar(40) NOT NULL,
  `TYPE` varchar(20) NOT NULL DEFAULT 'user',
  `APPLICATION` enum('native','external') NOT NULL DEFAULT 'native',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categs`
--

LOCK TABLES `categs` WRITE;
/*!40000 ALTER TABLE `categs` DISABLE KEYS */;
INSERT INTO `categs` VALUES (5,'Διαχείριση χρηστών','user.php','admin','native'),(6,'Content','content.php','user','native'),(7,'Κατηγορίες','categ.php','user','native'),(8,'Υλικό','material.php','admin','native'),(9,'Templates','templates.php','user','native'),(11,'Banners','banners.php','user','native'),(12,'Συντάκτες','authors.php','user','native'),(13,'Σύνδεσμοι','links.php','user','native'),(14,'Μέλη','members.php','user','native'),(15,'Newsletter','newsletter.php','user','native'),(16,'Σχόλια','comments.php','user','native'),(17,'Game configurator','gameconfigurator.php','user','external'),(18,'Point system','pointsystem.php','user','external'),(19,'Soccer players','player.php','user','external'),(20,'Countries','country.php','user','external'),(21,'Teams','team.php','user','external'),(22,'Enter results','gameresults.php','user','external'),(23,'Manager team setup','manager-team-setup.php','user','native'),(24,'Match setup','match-setup.php','user','native'),(26,'Mods','mods.php','user','native');
/*!40000 ALTER TABLE `categs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TYPE` varchar(40) NOT NULL,
  `OBJECT_ID` int(11) NOT NULL,
  `CONTENT` text NOT NULL,
  `CREATED` int(11) NOT NULL,
  `POSTED_BY` int(11) NOT NULL,
  `STATUS` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(100) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `CONTENT` text NOT NULL,
  `CREATED` int(11) NOT NULL,
  `CREATED_BY` int(11) NOT NULL,
  `STATUS` int(11) NOT NULL,
  `UPDATED_ON` int(11) NOT NULL,
  `TEASER` text,
  `COMMENTABLE` enum('NO','YES') NOT NULL DEFAULT 'YES',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=137 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content`
--

LOCK TABLES `content` WRITE;
/*!40000 ALTER TABLE `content` DISABLE KEYS */;
INSERT INTO `content` VALUES (136,'Η φιλοσοφία μας','e389f7c61ebe5465da532b2bbff38000','<p>Ροή χαμηλός παράδειγμα τη, αυτήν καθυστερούσε με μια, πως αν έτοιμος δίνοντας επιτυχία. Έχω τύπους διακοπής διοικητικό να, ώς ποσοστό γνωρίζουμε νέα. Συγγραφής απομόνωση περισσότερες στο τα, σου σε σύστημα κακόκεφους. Πελάτες κακόκεφος ώς νέο, μόλις εξαρτάται ναί αν. Τα έστειλε αλλάζοντας δημιουργείς που, νέες κόλπα συνάδελφος τι μια. Τα όλα σωστά δεδομένη μετρήσεις, των να στην παραγωγικά διαπιστώνεις.<br />\r\n<br />\r\nΆρα δε σημεία ρωτήσει προβλήματα, εργαλείων προσλάμβανες να ναι, σίγουρος χρησιμοποιούνταν δύο δε. Τη που τότε νέου, μάτσο παρατηρούμενη το των. Λοιπόν ευκολότερο αποκλειστικούς πω δύο, από έτσι χρονοδιαγράμματος οι, λες ας μόλις δούλευε. Τον τύπους δουλεύουν οι. Ένα ώς τρέξει εφαρμογής αποθηκευτικού.<br />\r\n<br />\r\nΏς λες νόμιζες ανταγωνιστικούς. Μη άρα φίλος περισσότερες χρησιμοποιούσες, σου οι τρόποι φαινόμενο ανακλύψεις. Δε νέου μπουν προσπάθεια πες, ότι τι τέτοιο προβλήματα, πιο να λετπά μάλλον. Ότι περισσότερες δημιουργήσεις οι, ροή μόλις σύστημα προγραμματιστής δε. Παραγωγικής χρησιμοποιήσει με της, ως ροή άρθρων γι\'αυτό αναφέρονται.<br />\r\n<br />\r\nΣαν ας βήμα πρώτες, θα ναί ωραίο παράγει παραγωγικά, κλπ κι χαρά μάτσο κειμένων. Όσο ατόμου δημιουργίες τα, τη στο αγοράζοντας διολισθήσεις χαρακτηριστικών, γράψει βιαστικά προσλάμβανες το και. Χαμηλός απόλαυσε από το, ας και γέλασαν διαφήμιση. Να έχουν άγχος μια, πάτο πουλάς κρατήσουν κλπ ας, στο ώς μάτσο συνηθίζουν συνεντεύξεις. Τι όσο κώδικάς καθορίζουν, με άρα μπουν μέσης. Αν δουλεύει χρονοδιαγράμματος ότι, νέα σε αυτός γράψει μειώσει, ναι πάτο νόμιζες δημιουργώντας ας.<br />\r\n<br />\r\nΤων τα είχαμε απαρατήρητο, το ένα όροφο γνωρίζουμε επεξεργασία. Την τα παίρνουν επιδιορθώσεις, σας δουλεύουν εσωτερικών εργοστασίου τι, μόλις χρήματα τεσσαρών ως πιο. Οι εμπορικά οέλεγχος σας, το ότι σημαντικό καθορίζουν. Πεδία τεράστιο περισσότερες αν και, κόλπα χαρτιού διάσημα ας στη. Ναί κάνε συνεντεύξης τα, πιο ωραίο γίνεται ώς, μπορούσε εξοργιστικά εγώ ας. Στήλες γνωρίζουμε τεκμηριώνει ναί ας, με στις υπηρεσία σημαντικό μην, χρόνου εσφαλμένη έξι μη.<br />\r\n<br />\r\nΠιο στην αποστηθίσει ώς, ροή μέσης συνεχώς καθυστερεί μα. Ημέρα αγοράζοντας ανά σε, εταιρείες συγγραφείς τη όλη, γραφικά εκτελείται ήδη να. Ρουτίνα υψηλότερη για ως. Ματ πάρα απαραίτητο μη. Πάρα τελικά εκείνου την πω, δε δούλευε ευκολότερο μια.<br />\r\n<br />\r\nΌτι άτομο μάλλον ώς. Τα των κύκλο διαφήμιση καθυστερεί, μα μαγικά στέλνοντάς εξακολουθεί ότι. Τότε συντακτικό μην σε. Τέτοιο χρησιμοποίησέ μας ως, από με τρόποι καλύτερο. Πάρεις διακοπή ας ματ, μου αλφα μάτσο εκτελέσεις να.<br />\r\n<br />\r\nΈξι μα έγραψες λιγότερους, να γεύματος δημιουργική συγκεντρωμένοι ροή. Πάντα πιθανό συγχρόνως να δεν. Ώς μας πάντως εμπορικά τεράστιο, τα νύχτας συγγραφείς από, ροή τη κάτι συγχωνευτεί. Από παίρνει υποψήφιο δοκιμάσεις αν, μα μάλλον λαμβάνουν μπαίνοντας την, άμεση μετράει ανακλύψεις οι κλπ. Έγραψες διοίκηση καταλάθος μα σου, τη ώρα τρόπο προσθέσει, πήρε λιγότερους εξοργιστικά έχω τη. Το μέσης δημιουργια έξι, μα υλικό κανένας παρατηρούμενη όλα, ένα τα πρώτες νόμιζες.<br />\r\n<br />\r\nΤη προσθέσει συντακτικό παρατηρούμενη όχι, στα στέλνοντάς αποδείξεις χρησιμοποιούσες σε. Μου να έτσι μιας επιστρέφουν, ότι έκδοση εξοργιστικά θα. Άγχος τελευταία χαρακτηριστικό μου οι. Μου ποσοστό διαδίκτυο κι, πρώτης συνεχώς τη σου. Γλιτώσει δυστυχής μη κλπ, όσο οι μέση σφαλμάτων μεταγλωτιστής, τώρα έχουν αρέσει σαν ως. Τα ότι περισσότερο χρησιμοποίησέ.<br />\r\n<br />\r\nΣου να τύπου επενδυτής, μέση όταν παρατηρούμενη ματ μη. Εντολές ιδιαίτερα προσπαθήσεις σε έχω. Εμπορικά απίστευτα δημιουργική ήδη με, στο πω δίνοντας εργαλείων. Το κόλπα πετούν λιγότερο έξι, νέων μάλλον διαχειριστής θα πιο.<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </p>',1271852867,7,1,1286096434,'Ροή χαμηλός παράδειγμα τη, αυτήν καθυστερούσε με μια, πως αν έτοιμος δίνοντας επιτυχία. Έχω τύπους διακοπής διοικητικό να, ώς ποσοστό γνωρίζουμε νέα. Συγγραφής απομόνωση περισσότερες στο τα, σου σε σύστημα κακόκεφους. Πελάτες κακόκεφος ώς νέο, μόλις εξαρτάται ναί αν. Τα έστειλε αλλάζοντας δημιουργείς που, νέες κόλπα συνάδελφος τι μια. Τα όλα σωστά δεδομένη μετρήσεις, των να στην παραγωγικά διαπιστώνεις.\r\n','NO');
/*!40000 ALTER TABLE `content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_category`
--

DROP TABLE IF EXISTS `content_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_category` (
  `CONTENT_ID` int(11) NOT NULL,
  `CATEGORY_ID` int(11) NOT NULL,
  PRIMARY KEY (`CONTENT_ID`,`CATEGORY_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_category`
--

LOCK TABLES `content_category` WRITE;
/*!40000 ALTER TABLE `content_category` DISABLE KEYS */;
INSERT INTO `content_category` VALUES (125,7),(125,19),(129,22),(130,19),(130,22),(135,16),(136,1),(136,56),(138,78),(139,79),(142,80),(143,61),(144,62),(145,64),(146,66),(147,67),(148,68),(149,70),(150,72),(151,73),(152,81);
/*!40000 ALTER TABLE `content_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_material`
--

DROP TABLE IF EXISTS `content_material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_material` (
  `CONTENT_ID` int(11) NOT NULL,
  `MATERIAL_ID` int(11) NOT NULL,
  PRIMARY KEY (`CONTENT_ID`,`MATERIAL_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='match table for attachments';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_material`
--

LOCK TABLES `content_material` WRITE;
/*!40000 ALTER TABLE `content_material` DISABLE KEYS */;
INSERT INTO `content_material` VALUES (0,20),(0,21),(0,23);
/*!40000 ALTER TABLE `content_material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery`
--

DROP TABLE IF EXISTS `gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(100) NOT NULL,
  `CREATED` int(11) NOT NULL,
  `CREATED_BY` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery`
--

LOCK TABLES `gallery` WRITE;
/*!40000 ALTER TABLE `gallery` DISABLE KEYS */;
/*!40000 ALTER TABLE `gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_content`
--

DROP TABLE IF EXISTS `gallery_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery_content` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FULLNAME` varchar(100) NOT NULL,
  `MIME` varchar(80) NOT NULL,
  `CREATED` int(11) NOT NULL,
  `CREATED_BY` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_content`
--

LOCK TABLES `gallery_content` WRITE;
/*!40000 ALTER TABLE `gallery_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `gallery_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_gallery_content`
--

DROP TABLE IF EXISTS `gallery_gallery_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery_gallery_content` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `GALLERY_ID` int(11) NOT NULL,
  `GALLERY_CONTENT_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_gallery_content`
--

LOCK TABLES `gallery_gallery_content` WRITE;
/*!40000 ALTER TABLE `gallery_gallery_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `gallery_gallery_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(50) NOT NULL,
  `CREATED` int(11) NOT NULL,
  `CREATED_BY` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'su',1255943900,'kdos'),(2,'user',1255943900,'kdos');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `links`
--

DROP TABLE IF EXISTS `links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `links` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `VENDOR` varchar(40) NOT NULL,
  `URL` varchar(100) NOT NULL,
  `INFO` varchar(100) NOT NULL,
  `CREATED` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `links`
--

LOCK TABLES `links` WRITE;
/*!40000 ALTER TABLE `links` DISABLE KEYS */;
INSERT INTO `links` VALUES (8,'feleki','http://feleki.wordpress.com/','feleki blog',1271852125),(9,'Σύνδεσμος Αιγυπτιωτών','http://www.synaige.gr','Σύνδεσμος Αιγυπτιωτών',1271852270),(10,'ΕΝΤΟΣ','http://www.defacto.gr/','Εκδόσεις ΕΝΤΟΣ',1271852322),(11,'alfavita','http://www.alfavita.gr','alfavita',1271852345),(12,'Συλλαβή','http://www.facebook.com/group.php?gid=54175425895','καφέ Συλλαβή',1271852482);
/*!40000 ALTER TABLE `links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `material`
--

DROP TABLE IF EXISTS `material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `material` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(100) NOT NULL,
  `TYPE` varchar(20) DEFAULT NULL,
  `CREATED` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `material`
--

LOCK TABLES `material` WRITE;
/*!40000 ALTER TABLE `material` DISABLE KEYS */;
INSERT INTO `material` VALUES (1,'Photo0145.jpg','jpeg',1335218400);
/*!40000 ALTER TABLE `material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `memberrole`
--

DROP TABLE IF EXISTS `memberrole`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `memberrole` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role` (`role`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `memberrole`
--

LOCK TABLES `memberrole` WRITE;
/*!40000 ALTER TABLE `memberrole` DISABLE KEYS */;
/*!40000 ALTER TABLE `memberrole` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` int(10) unsigned NOT NULL,
  `status` int(10) unsigned NOT NULL,
  `session` binary(16) NOT NULL,
  `lastLogin` int(11) unsigned NOT NULL,
  `latestViewedTransactions` int(11) unsigned NOT NULL,
  `email` varchar(50) COLLATE utf8_bin NOT NULL,
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `password` varchar(50) COLLATE utf8_bin NOT NULL,
  `teamName` varchar(50) COLLATE utf8_bin NOT NULL,
  `budget` decimal(10,2) NOT NULL DEFAULT '0.00',
  `availableCash` decimal(10,2) NOT NULL,
  `registrationDate` int(11) unsigned DEFAULT NULL,
  `gender` enum('MALE','FEMALE') COLLATE utf8_bin DEFAULT NULL,
  `dateOfBirth` int(11) unsigned DEFAULT NULL,
  `location` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `photo` tinytext COLLATE utf8_bin,
  `country` int(10) unsigned DEFAULT NULL,
  `supportsTeam` int(10) unsigned DEFAULT NULL,
  `transactionsToday` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `transactionsWeek` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `transfersWeek` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `transfersSeason` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `history` text COLLATE utf8_bin,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `teamName` (`teamName`),
  FULLTEXT KEY `email_2` (`email`,`username`,`teamName`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members`
--

LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */;
INSERT INTO `members` VALUES (1,0,1,'\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0',0,0,'doskaskonstantinos@gmail.com','doskaskonstantinos@gmail.com','123','team2','0.00','25000.00',0,'FEMALE',92872800,'Athens','',1,1,0,0,0,0,NULL),(2,0,3,'\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0',0,0,'blabla_1@yahoo.com','blabla_1@yahoo.com','202cb962ac59075b964b07152d234b70','team1','0.00','2455.00',0,'MALE',1289253600,'Athens','',1,1,0,0,0,0,NULL),(3,0,3,'\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0',0,0,'blabla_2@yahoo.com','blabla_2@yahoo.com','202cb962ac59075b964b07152d234b70','team3','0.00','0.00',0,'MALE',1289253600,'Athens','',1,1,0,0,0,0,NULL),(4,0,1,'\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0',0,0,'blabla_4@yahoo.com','blabla_4@yahoo.com','202cb962ac59075b964b07152d234b70','team','0.00','0.00',1289303156,'MALE',1289253600,'Athens','',1,1,0,0,0,0,NULL),(5,0,1,'\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0',0,0,'blabla_5@yahoo.com','blabla_5@yahoo.com','202cb962ac59075b964b07152d234b70','team5','0.00','2000.00',1289305388,'FEMALE',310946400,'Athens','',1,1,0,0,0,0,NULL),(6,0,1,'\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0',0,0,'blabla_6@yahoo.com','blabla_6@yahoo.com','6074c6aa3488f3c2dddff2a7ca821aab','team145','0.00','12000.00',1289305454,'MALE',1289253600,'Thessaloniki','',1,1,0,0,0,0,NULL);
/*!40000 ALTER TABLE `members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `memberstatus`
--

DROP TABLE IF EXISTS `memberstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `memberstatus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `memberstatus`
--

LOCK TABLES `memberstatus` WRITE;
/*!40000 ALTER TABLE `memberstatus` DISABLE KEYS */;
INSERT INTO `memberstatus` VALUES (1,'ACTIVE'),(2,'LOCKED'),(3,'BANNED');
/*!40000 ALTER TABLE `memberstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,'main-menu','main-menu'),(2,'top-menu','top-menu'),(3,'bottom-menu','bottom-menu'),(4,'left-menu','left-menu'),(5,'right-menu','right-menu');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus_application_nodes`
--

DROP TABLE IF EXISTS `menus_application_nodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus_application_nodes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `menus_ID` int(11) NOT NULL,
  `application_nodes_ID` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus_application_nodes`
--

LOCK TABLES `menus_application_nodes` WRITE;
/*!40000 ALTER TABLE `menus_application_nodes` DISABLE KEYS */;
/*!40000 ALTER TABLE `menus_application_nodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_search`
--

DROP TABLE IF EXISTS `mod_search`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `searchstring` varchar(80) DEFAULT NULL,
  `choose` enum('yes','no','maybe') DEFAULT 'yes',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_search`
--

LOCK TABLES `mod_search` WRITE;
/*!40000 ALTER TABLE `mod_search` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_search` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_social`
--

DROP TABLE IF EXISTS `mod_social`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_social` (
  `disable` enum('yes','no') DEFAULT 'yes'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_social`
--

LOCK TABLES `mod_social` WRITE;
/*!40000 ALTER TABLE `mod_social` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_social` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mods`
--

DROP TABLE IF EXISTS `mods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mods` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `status` enum('installed','inactive') NOT NULL DEFAULT 'inactive',
  `author` varchar(40) DEFAULT NULL,
  `version` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mods`
--

LOCK TABLES `mods` WRITE;
/*!40000 ALTER TABLE `mods` DISABLE KEYS */;
INSERT INTO `mods` VALUES (0,'social','standard','installed','K.Doskas','0.2');
/*!40000 ALTER TABLE `mods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter`
--

DROP TABLE IF EXISTS `newsletter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CREATED` int(11) NOT NULL,
  `TITLE` varchar(200) NOT NULL,
  `CONTENT` text NOT NULL,
  `SENT` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter`
--

LOCK TABLES `newsletter` WRITE;
/*!40000 ALTER TABLE `newsletter` DISABLE KEYS */;
INSERT INTO `newsletter` VALUES (4,1265186828,'test','<p>e truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?&quot; &quot;But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rat</p>','');
/*!40000 ALTER TABLE `newsletter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_categs`
--

DROP TABLE IF EXISTS `user_categs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_categs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_ID` int(11) NOT NULL,
  `CATEG_ID` int(11) NOT NULL,
  `_READ_` enum('Y','N') NOT NULL DEFAULT 'Y',
  `_WRITE_` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=199 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_categs`
--

LOCK TABLES `user_categs` WRITE;
/*!40000 ALTER TABLE `user_categs` DISABLE KEYS */;
INSERT INTO `user_categs` VALUES (80,2,5,'Y','Y'),(81,2,6,'Y','Y'),(82,2,7,'Y','Y'),(83,2,8,'Y','Y'),(93,2,9,'Y','Y'),(94,2,10,'Y','Y'),(95,2,11,'Y','Y'),(100,2,12,'Y','Y'),(102,2,13,'Y','Y'),(104,2,14,'Y','Y'),(106,2,15,'Y','Y'),(108,2,16,'Y','Y'),(119,2,17,'Y','Y'),(120,2,18,'Y','Y'),(121,2,19,'Y','Y'),(122,2,20,'Y','Y'),(123,2,21,'Y','Y'),(124,3,5,'Y','Y'),(125,3,6,'Y','Y'),(126,3,7,'Y','Y'),(127,3,8,'Y','Y'),(128,3,12,'Y','Y'),(129,3,13,'Y','Y'),(130,3,14,'Y','Y'),(131,3,15,'Y','Y'),(132,3,16,'Y','Y'),(133,3,17,'Y','Y'),(134,3,18,'Y','Y'),(135,3,19,'Y','Y'),(136,3,20,'Y','Y'),(137,3,21,'Y','Y'),(138,3,22,'Y','Y'),(139,2,22,'Y','Y'),(140,4,5,'Y','Y'),(141,4,6,'Y','Y'),(142,4,7,'Y','Y'),(143,4,8,'Y','Y'),(144,4,12,'Y','Y'),(145,4,13,'Y','Y'),(146,4,14,'Y','Y'),(147,4,15,'Y','Y'),(148,4,16,'Y','Y'),(149,4,17,'Y','Y'),(150,4,18,'Y','Y'),(151,4,19,'Y','Y'),(152,4,20,'Y','Y'),(153,4,21,'Y','Y'),(154,4,22,'Y','Y'),(155,5,5,'Y','Y'),(156,5,6,'Y','Y'),(157,5,7,'Y','Y'),(158,5,8,'Y','Y'),(159,5,12,'Y','Y'),(160,5,13,'Y','Y'),(161,5,14,'Y','Y'),(162,5,15,'Y','Y'),(163,5,16,'Y','Y'),(164,5,17,'Y','Y'),(165,5,18,'Y','Y'),(166,5,19,'Y','Y'),(167,5,20,'Y','Y'),(168,5,21,'Y','Y'),(169,5,22,'Y','Y'),(170,6,5,'Y','Y'),(171,6,6,'Y','Y'),(172,6,7,'Y','Y'),(173,6,8,'Y','Y'),(174,6,12,'Y','Y'),(175,6,13,'Y','Y'),(176,6,14,'Y','Y'),(177,6,15,'Y','Y'),(178,6,16,'Y','Y'),(179,6,17,'Y','Y'),(180,6,18,'Y','Y'),(181,6,19,'Y','Y'),(182,6,20,'Y','Y'),(183,6,21,'Y','Y'),(184,6,22,'Y','Y'),(185,2,23,'Y','Y'),(186,3,23,'Y','Y'),(187,4,23,'Y','Y'),(188,5,23,'Y','Y'),(189,6,23,'Y','Y'),(190,2,24,'Y','Y'),(191,3,24,'Y','Y'),(192,4,24,'Y','Y'),(193,5,24,'Y','Y'),(194,6,24,'Y','Y'),(198,2,26,'Y','Y');
/*!40000 ALTER TABLE `user_categs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL,
  `created` int(11) NOT NULL COMMENT 'inserting php timestamp here',
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `groupID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'kotscho','6881ea273eb00114ac211470312a8f5a',1287060372,'Constantine','Doskas',1),(3,'soccer','90873c2aa0b1f74fd559cfc175446422',1295017126,'soccer','manager',1),(4,'saik0','36f17c3939ac3e7b2fc9396fa8e953ea',1290897774,'saik0','saik0',1),(5,'mangeo','e8e4e881d0e90635ce532db0dbc2b757',1295017115,'man','geo',1),(6,'dionisis','74e67805c7294117fe5ab0f70062c75d',1291034001,'dionisis','mar',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-05-04  9:31:49
