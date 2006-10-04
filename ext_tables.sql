#
# Table structure for table 'tt_address'
#
CREATE TABLE tt_address (
  uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
  pid int(11) unsigned DEFAULT '0' NOT NULL,
  tstamp int(11) unsigned DEFAULT '0' NOT NULL,
  hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
  name tinytext NOT NULL,
  title varchar(40) DEFAULT '' NOT NULL,
  email varchar(80) DEFAULT '' NOT NULL,
  phone varchar(30) DEFAULT '' NOT NULL,
  mobile varchar(30) DEFAULT '' NOT NULL,
  www varchar(80) DEFAULT '' NOT NULL,
  address tinytext NOT NULL,
  company varchar(80) DEFAULT '' NOT NULL,
  city varchar(80) DEFAULT '' NOT NULL,
  zip varchar(20) DEFAULT '' NOT NULL,
  country varchar(30) DEFAULT '' NOT NULL,
  image tinyblob NOT NULL,
  fax varchar(30) DEFAULT '' NOT NULL,
  deleted tinyint(3) unsigned DEFAULT '0' NOT NULL,
  description text NOT NULL,
  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY pid (pid,email)
);
