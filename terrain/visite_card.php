<?php
require '../../main.inc.php';
dol_include_once('/custom/terrain/class/visite.class.php');

llxHeader('', 'Visites Terrain');

// Suppression avec réordonnancement 1,2,3
if (!empty($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $db->query("DELETE FROM ".MAIN_DB_PREFIX."terrain_visite WHERE rowid=".$id);
  // réordonner
  $res = $db->query("SELECT * FROM ".MAIN_DB_PREFIX."terrain_visite ORDER BY rowid ASC");
  $rows=[]; while($o=$db->fetch_object($res)) $rows[]=$o;
  $db->query("DELETE FROM ".MAIN_DB_PREFIX."terrain_visite");
  $db->query("ALTER TABLE ".MAIN_DB_PREFIX."terrain_visite AUTO_INCREMENT=1");
  foreach($rows as $r){
    // on ré-insère avec INSERT direct pour garder les données
    $db->query("INSERT INTO ".MAIN_DB_PREFIX."terrain_visite (fk_user_visiteur, fk_pdv, lat_visite, lon_visite, lat_pdv, lon_pdv, distance, classification, fk_programme, comment_text, datec) VALUES (".(int)$r->fk_user_visiteur.",".(int)$r->fk_pdv.",'".$db->escape($r->lat_visite)."','".$db->escape($r->lon_visite)."','".$db->escape($r->lat_pdv)."','".$db->escape($r->lon_pdv)."',".(float)$r->distance.",'".$db->escape($r->classification)."',".(int)$r->fk_programme.",'".$db->escape($r->comment_text)."', NOW())");
  }
  header('Location: visites.php?idmenu=88&mainmenu=terrain&leftmenu='); exit;
}

print load_fiche_titre('Visites Terrain', '', 'globe');
print '<a href="visite.php?action=create&idmenu=88&mainmenu=terrain&leftmenu=" class="button" style="background:#9b7eb8;margin-bottom:15px">+ NOUVELLE VISITE</a>';

$sql = "SELECT v.rowid, v.fk_pdv, v.fk_user_visiteur, v.datec, v.classification, v.distance, s.nom as pdv_nom, u.lastname
        FROM ".MAIN_DB_PREFIX."terrain_visite v
        LEFT JOIN ".MAIN_DB_PREFIX."societe s ON s.rowid=v.fk_pdv
        LEFT JOIN ".MAIN_DB_PREFIX."user u ON u.rowid=v.fk_user_visiteur
        ORDER BY v.rowid DESC";

$res = $db->query($sql);
if(!$res){ print '<div style="background:red;color:white;padding:10px">'.$db->lasterror().'</div>'; }
else{
  print '<table class="liste" style="width:100%"><tr class="liste_titre"><td>ID</td><td>Territoire / PDV</td><td>Date</td><td>Classification</td><td>Distance</td><td>Action</td></tr>';
  $i = $db->num_rows($res);
  // On veut 1,2,3 comme avant donc on inverse
  $all=[]; while($o=$db->fetch_object($res)) $all[]=$o;
  $all = array_reverse($all);
  $c=0;
  foreach($all as $o){
    $c++;
    $pdv = $o->pdv_nom ? $o->pdv_nom.' (#'.$o->fk_pdv.')' : 'PDV #'.$o->fk_pdv;
    print '<tr class="oddeven"><td><b>'.$c.'</b></td><td>'.$pdv.'<br><small>'.$o->lastname.'</small></td><td>'.$o->datec.'</td><td><span style="padding:3px 8px;background:'.($o->classification=='sur_place'?'#c8f5c8':'#ffe0a0').'">'.$o->classification.'</span></td><td>'.round($o->distance,3).' km</td><td><a href="?delete='.$o->rowid.'&idmenu=88&mainmenu=terrain&leftmenu=" onclick="return confirm(\'Supprimer ?\')" style="background:#ff4444;color:white;padding:5px 10px;text-decoration:none">Supprimer</a></td></tr>';
  }
  print '</table>';
  if($c==0) print '<div style="padding:20px;background:#fff3cd">Aucune visite trouvée, mais la table existe. Vérifie que ta création passe bien par <b>visite.php?action=create</b></div>';
}

llxFooter();
