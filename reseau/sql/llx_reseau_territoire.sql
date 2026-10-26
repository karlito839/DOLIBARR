CREATE TABLE llx_reseau_territoire (
  rowid integer AUTO_INCREMENT PRIMARY KEY,
  ref varchar(128) NOT NULL,
  label varchar(255) NOT NULL,
  status integer DEFAULT 1,
  datec datetime,
  tms timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=innodb;