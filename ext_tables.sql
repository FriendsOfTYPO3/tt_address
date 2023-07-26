#
# Table structure for table 'tt_address'
#
CREATE TABLE tt_address (
    gender varchar(1) DEFAULT '' NOT NULL,
    name tinytext,
    slug varchar(2048),
    first_name tinytext,
    middle_name tinytext,
    last_name tinytext,
    birthday bigint(20) DEFAULT '0' NOT NULL,
    title varchar(255) DEFAULT '' NOT NULL,
    title_suffix varchar(100) DEFAULT '' NOT NULL,
    email varchar(255) DEFAULT '' NOT NULL,
    phone varchar(30) DEFAULT '' NOT NULL,
    mobile varchar(30) DEFAULT '' NOT NULL,
    www varchar(255) DEFAULT '' NOT NULL,
    address tinytext,
    building varchar(255) DEFAULT '' NOT NULL,
    room varchar(255) DEFAULT '' NOT NULL,
    company varchar(255) DEFAULT '' NOT NULL,
    position varchar(255) DEFAULT '' NOT NULL,
    city varchar(255) DEFAULT '' NOT NULL,
    zip varchar(20) DEFAULT '' NOT NULL,
    region varchar(255) DEFAULT '' NOT NULL,
    country varchar(128) DEFAULT '' NOT NULL,
    fax varchar(30) DEFAULT '' NOT NULL,
    description text,
    skype varchar(255) DEFAULT '',
    twitter varchar(255) DEFAULT '',
    facebook varchar(255) DEFAULT '',
    instagram varchar(255) DEFAULT '',
    tiktok varchar(255) DEFAULT '',
    linkedin varchar(255) DEFAULT '',
    latitude decimal(10,8) default NULL,
    longitude decimal(11,8) default NULL,

    image tinyblob,
    categories int(11) DEFAULT '0' NOT NULL,

    KEY parent (pid),
    KEY pid_email (pid,email(191))
);
