CREATE TABLE llx_reseau_zone (
  rowid integer AUTO_INCREMENT PRIMARY KEY,
  ref varchar(128) NOT NULL,
  label varchar(255) NOT NULL,
  fk_territoire integer NOT NULL,
  status integer DEFAULT 1,
  datec datetime,
  tms timestamp
) ENGINE=innodb;