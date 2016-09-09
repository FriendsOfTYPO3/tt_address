#
# Table structure for table 'tt_address'
#
CREATE TABLE tt_address (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	gender varchar(1) DEFAULT '' NOT NULL,
	name tinytext,
	first_name tinytext,
	middle_name tinytext,
	last_name tinytext,
	birthday int(11) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	phone varchar(30) DEFAULT '' NOT NULL,
	mobile varchar(30) DEFAULT '' NOT NULL,
	www varchar(255) DEFAULT '' NOT NULL,
	address tinytext,
	building varchar(20) DEFAULT '' NOT NULL,
	room varchar(15) DEFAULT '' NOT NULL,
	company varchar(255) DEFAULT '' NOT NULL,
	position varchar(255) DEFAULT '' NOT NULL,
	city varchar(255) DEFAULT '' NOT NULL,
	zip varchar(20) DEFAULT '' NOT NULL,
	region varchar(255) DEFAULT '' NOT NULL,
	country varchar(128) DEFAULT '' NOT NULL,
	image tinyblob,
	fax varchar(30) DEFAULT '' NOT NULL,
	deleted tinyint(3) DEFAULT '0',
	description text,
	skype varchar(50) DEFAULT '' NOT NULL,
	twitter varchar(50) DEFAULT '' NOT NULL,
	facebook varchar(50) DEFAULT '' NOT NULL,
	linkedin varchar(50) DEFAULT '' NOT NULL,
	categories int(11) DEFAULT '0' NOT NULL,
	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(30) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,
	t3_origuid int(11) DEFAULT '0' NOT NULL,
	latitude decimal(14,12) default NULL,
	longitude decimal(15,12) default NULL,
	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY pid (pid,email)
);
