CREATE TABLE llx_reseau_secteur (
  rowid integer AUTO_INCREMENT PRIMARY KEY,
  ref varchar(128) NOT NULL,
  label varchar(255) NOT NULL,
  fk_zone integer NOT NULL,
  fk_territoire integer,
  status integer DEFAULT 1,
  datec datetime,
  tms timestamp
) ENGINE=innodb;