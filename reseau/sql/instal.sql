-- custom/reseau/sql/install.sql

-- 1. TABLE TERRITOIRE
DROP TABLE IF EXISTS llx_reseau_territoire;
CREATE TABLE llx_reseau_territoire (
									   rowid INTEGER AUTO_INCREMENT PRIMARY KEY,
									   ref VARCHAR(50) NOT NULL UNIQUE,
									   label VARCHAR(100) NOT NULL,
									   status SMALLINT DEFAULT 1,
									   fk_user_creat INTEGER,
									   datec DATETIME,
									   tms TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
									   INDEX idx_reseau_territoire_label (label)
) ENGINE=innodb;

-- 2. TABLE ZONE
DROP TABLE IF EXISTS llx_reseau_zone;
CREATE TABLE llx_reseau_zone (
								 rowid INTEGER AUTO_INCREMENT PRIMARY KEY,
								 ref VARCHAR(50) NOT NULL UNIQUE,
								 label VARCHAR(100) NOT NULL,
								 fk_territoire INTEGER NOT NULL,
								 status SMALLINT DEFAULT 1,
								 datec DATETIME,
								 tms TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
								 CONSTRAINT fk_reseau_zone_territoire FOREIGN KEY (fk_territoire) REFERENCES llx_reseau_territoire(rowid),
								 INDEX idx_reseau_zone_territoire (fk_territoire)
) ENGINE=innodb;

-- 3. TABLE SECTEUR
DROP TABLE IF EXISTS llx_reseau_secteur;
CREATE TABLE llx_reseau_secteur (
									rowid INTEGER AUTO_INCREMENT PRIMARY KEY,
									ref VARCHAR(50) NOT NULL UNIQUE,
									label VARCHAR(100) NOT NULL,
									fk_zone INTEGER NOT NULL,
									fk_territoire INTEGER NOT NULL,
									status SMALLINT DEFAULT 1,
									datec DATETIME,
									tms TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
									CONSTRAINT fk_reseau_secteur_zone FOREIGN KEY (fk_zone) REFERENCES llx_reseau_zone(rowid),
									CONSTRAINT fk_reseau_secteur_territoire FOREIGN KEY (fk_territoire) REFERENCES llx_reseau_territoire(rowid),
									INDEX idx_reseau_secteur_zone (fk_zone)
) ENGINE=innodb;

-- 4. TABLE ACTEUR - LA PLUS IMPORTANTE
DROP TABLE IF EXISTS llx_reseau_acteur;
CREATE TABLE llx_reseau_acteur (
								   rowid INTEGER AUTO_INCREMENT PRIMARY KEY,
								   ref VARCHAR(100) NOT NULL UNIQUE,
								   label VARCHAR(255) NOT NULL UNIQUE,
								   type_acteur VARCHAR(20) NOT NULL,
								   fk_parent INTEGER NULL,
								   fk_territoire INTEGER NOT NULL,
								   fk_zone INTEGER NOT NULL,
								   fk_secteur INTEGER NOT NULL,
								   fk_user INTEGER NULL UNIQUE,

	-- 4 compteurs en lecture seule
								   solde_uv DOUBLE DEFAULT 0,
								   especes_en_cours DOUBLE DEFAULT 0,
								   creances_a_recouvrer DOUBLE DEFAULT 0,
								   dette_envers_superieur DOUBLE DEFAULT 0,

								   plafond_credit DOUBLE DEFAULT 0,
								   status SMALLINT DEFAULT 0,
								   datec DATETIME,
								   tms TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

								   CONSTRAINT fk_reseau_acteur_parent FOREIGN KEY (fk_parent) REFERENCES llx_reseau_acteur(rowid),
								   CONSTRAINT fk_reseau_acteur_territoire FOREIGN KEY (fk_territoire) REFERENCES llx_reseau_territoire(rowid),
								   CONSTRAINT fk_reseau_acteur_zone FOREIGN KEY (fk_zone) REFERENCES llx_reseau_zone(rowid),
								   CONSTRAINT fk_reseau_acteur_secteur FOREIGN KEY (fk_secteur) REFERENCES llx_reseau_secteur(rowid),
								   CONSTRAINT fk_reseau_acteur_user FOREIGN KEY (fk_user) REFERENCES llx_user(rowid),

								   INDEX idx_reseau_acteur_type (type_acteur),
								   INDEX idx_reseau_acteur_parent (fk_parent),
								   INDEX idx_reseau_acteur_secteur (fk_secteur)
) ENGINE=innodb;
