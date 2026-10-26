<?php
define('NOTOKENRENEWAL',1);
require '../../main.inc.php';
llxHeader('', 'Acteurs');

if (!empty($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $db->query("DELETE FROM ".MAIN_DB_PREFIX."reseau_acteur WHERE rowid=".$id);
  $res = $db->query("SELECT ref, label, type, fk_secteur, fk_zone, fk_territoire, fk_user, type_acteur, role_acteur FROM ".MAIN_DB_PREFIX."reseau_acteur ORDER BY rowid ASC");
  $rows=[]; while($o=$db->fetch_object($res)) $rows[]=$o;
  $db->query("DELETE FROM ".MAIN_DB_PREFIX."reseau_acteur");
  $db->query("ALTER TABLE ".MAIN_DB_PREFIX."reseau_acteur AUTO_INCREMENT=1");
  foreach($rows as $r){
    $db->query("INSERT INTO ".MAIN_DB_PREFIX."reseau_acteur (ref,label,type,fk_secteur,fk_zone,fk_territoire,fk_user,solde_uv,status,datec,type_acteur,role_acteur) VALUES ('".$db->escape($r->ref)."','".$db->escape($r->label)."','".$db->escape($r->type)."',".(int)$r->fk_secteur.",".($r->fk_zone?$r->fk_zone:'NULL').",".($r->fk_territoire?$r->fk_territoire:'NULL').",".($r->fk_user?$r->fk_user:'NULL').",0,1,NOW(),'".$db->escape($r->type_acteur)."','".$db->escape($r->role_acteur)."')");
  }
  header('Location: acteur_list.php?idmenu=55&mainmenu=reseau&leftmenu='); exit;
}

print load_fiche_titre('Acteurs Réseau', '', 'user');
print '<a href="acteur_card.php?idmenu=55&mainmenu=reseau&leftmenu=" class="button" style="background:#9b7eb8;margin-bottom:15px">NOUVEAU</a>';

$res = $db->query("SELECT a.rowid, a.label, a.role_acteur, u.lastname, t.label as terr FROM ".MAIN_DB_PREFIX."reseau_acteur a LEFT JOIN ".MAIN_DB_PREFIX."user u ON u.rowid=a.fk_user LEFT JOIN ".MAIN_DB_PREFIX."reseau_territoire t ON t.rowid=a.fk_territoire ORDER BY a.rowid ASC");
print '<table class="liste" style="width:100%"><tr class="liste_titre"><td>ID</td><td>Acteur</td><td>Utilisateur</td><td>Territoire</td><td>Rôle</td><td>Action</td></tr>';
$i=0; while($o=$db->fetch_object($res)){ $i++; print '<tr class="oddeven"><td><b>'.$i.'</b></td><td>'.$o->label.'</td><td>'.$o->lastname.'</td><td>'.$o->terr.'</td><td>'.$o->role_acteur.'</td><td><a href="?delete='.$o->rowid.'&idmenu=55&mainmenu=reseau&leftmenu=" onclick="return confirm(\'Supprimer ?\')" style="background:#ff4444;color:white;padding:5px 10px;text-decoration:none">Supprimer</a></td></tr>'; }
print '</table>';
llxFooter();
