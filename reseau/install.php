<?php
require '../../main.inc.php';
echo "<h2>Création des tables Reseau</h2>";
$tables = [
 "llx_reseau_territoire" => "CREATE TABLE llx_reseau_territoire (rowid INT AUTO_INCREMENT PRIMARY KEY, ref VARCHAR(128) NOT NULL, label VARCHAR(255) NOT NULL, status INT DEFAULT 1, datec DATETIME) ENGINE=INNODB",
 "llx_reseau_zone" => "CREATE TABLE llx_reseau_zone (rowid INT AUTO_INCREMENT PRIMARY KEY, ref VARCHAR(128) NOT NULL, label VARCHAR(255) NOT NULL, fk_territoire INT NOT NULL, status INT DEFAULT 1, datec DATETIME) ENGINE=INNODB",
 "llx_reseau_secteur" => "CREATE TABLE llx_reseau_secteur (rowid INT AUTO_INCREMENT PRIMARY KEY, ref VARCHAR(128) NOT NULL, label VARCHAR(255) NOT NULL, fk_zone INT NOT NULL, status INT DEFAULT 1, datec DATETIME) ENGINE=INNODB",
 "llx_reseau_acteur" => "CREATE TABLE llx_reseau_acteur (rowid INT AUTO_INCREMENT PRIMARY KEY, ref VARCHAR(128) NOT NULL, label VARCHAR(255) NOT NULL, type VARCHAR(20) NOT NULL, fk_secteur INT NOT NULL, status INT DEFAULT 1, datec DATETIME) ENGINE=INNODB"
];
foreach($tables as $name=>$sql){
  $res = $db->query($sql);
  if($res) echo "$name : <b style='color:green'>CREE</b><br>";
  else echo "$name : <b style='color:red'>".$db->lasterror()."</b><br>SQL: $sql<br><br>";
}
echo "<br><a href='territoire_list.php'>Aller à Territoires</a>";