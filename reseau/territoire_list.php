<?php
define('NOTOKENRENEWAL',1);
require '../../main.inc.php';
llxHeader('', 'Territoires');

if (!empty($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $db->query("DELETE FROM ".MAIN_DB_PREFIX."reseau_territoire WHERE rowid=".$id);
  $res = $db->query("SELECT ref, label, status FROM ".MAIN_DB_PREFIX."reseau_territoire ORDER BY rowid ASC");
  $rows=[]; while($o=$db->fetch_object($res)) $rows[]=$o;
  $db->query("DELETE FROM ".MAIN_DB_PREFIX."reseau_territoire");
  $db->query("ALTER TABLE ".MAIN_DB_PREFIX."reseau_territoire AUTO_INCREMENT=1");
  foreach($rows as $r){
    $db->query("INSERT INTO ".MAIN_DB_PREFIX."reseau_territoire (ref,label,status,datec) VALUES ('".$db->escape($r->ref)."','".$db->escape($r->label)."',1,NOW())");
  }
  header('Location: territoire_list.php?idmenu=52&mainmenu=reseau&leftmenu='); exit;
}

print load_fiche_titre('Territoires', '', 'globe');
print '<a href="territoire_card.php?idmenu=52&mainmenu=reseau&leftmenu=" class="button" style="background:#9b7eb8;margin-bottom:15px">NOUVEAU</a>';
$res = $db->query("SELECT rowid, ref, label FROM ".MAIN_DB_PREFIX."reseau_territoire ORDER BY rowid ASC");
print '<table class="liste" style="width:100%"><tr class="liste_titre"><td>ID</td><td>Ref</td><td>Label</td><td>Action</td></tr>';
$i=0;
while($o=$db->fetch_object($res)){
  $i++;
  print '<tr class="oddeven"><td><b>'.$i.'</b></td><td>'.$o->ref.'</td><td>'.$o->label.'</td>';
  print '<td><a href="?delete='.$o->rowid.'&idmenu=52&mainmenu=reseau&leftmenu=" onclick="return confirm(\'Supprimer ?\')" style="background:#ff4444;color:white;padding:6px 12px;text-decoration:none">Supprimer</a></td></tr>';
}
print '</table>';
llxFooter();
