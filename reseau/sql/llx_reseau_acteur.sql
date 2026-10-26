CREATE TABLE llx_reseau_acteur (
  rowid integer AUTO_INCREMENT PRIMARY KEY,
  ref varchar(128) NOT NULL,
  label varchar(255) NOT NULL,
  type varchar(20) NOT NULL,
  fk_parent integer,
  fk_secteur integer NOT NULL,
  fk_zone integer,
  fk_territoire integer,
  fk_user integer,
  solde_uv double(24,8) DEFAULT 0,
  especes_en_cours double(24,8) DEFAULT 0,
  creances_a_recouvrer double(24,8) DEFAULT 0,
  dette_envers_superieur double(24,8) DEFAULT 0,
  plafond_credit double(24,8) DEFAULT 0,
  status integer DEFAULT 0,
  datec datetime,
  tms timestamp
) ENGINE=innodb;