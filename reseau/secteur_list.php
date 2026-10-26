<?php
require '../../main.inc.php';

if ($_POST['action']=='add'){
  $label = $db->escape(GETPOST('label','alpha'));
  $fk_zone = (int)GETPOST('fk_zone','int');

  $resmax = $db->query("SELECT MAX(rowid) as maxid FROM ".MAIN_DB_PREFIX."reseau_secteur");
  $objmax = $db->fetch_object($resmax);
  $next = (int)($objmax->maxid+1);
  if ($next < 1) $next = 1;
  $ref = "SECT-".date('Y')."-".str_pad($next, 4, '0', STR_PAD_LEFT);

  $db->query("INSERT INTO ".MAIN_DB_PREFIX."reseau_secteur (ref,label,fk_zone,status,datec) VALUES ('$ref','$label',$fk_zone,1,NOW())");
  header("Location: secteur_list.php"); exit;
}

llxHeader();

if ($_GET['action']=='create'){
  print '<h2>Nouveau Secteur</h2><form method="POST"><input type="hidden" name="action" value="add">';
  print 'Ref <input type="text" placeholder="Auto : SECT-2026-0001" style="background:#eee" readonly> <small style="color:green">auto</small><br><br>';
  print 'Label <input type="text" name="label" required style="width:400px"><br><br>';
  print 'Zone <select name="fk_zone">';
  $r=$db->query("SELECT rowid,label FROM ".MAIN_DB_PREFIX."reseau_zone ORDER BY label");
  while($o=$db->fetch_object($r)) print "<option value='$o->rowid'>$o->label</option>";
  print '</select><br><br>';
  print '<button class="button" type="submit">CREER</button> <a class="button" href="secteur_list.php">ANNULER</a></form>';
} else {
  print '<h2>Secteurs</h2><a class="button" href="secteur_list.php?action=create">+ NOUVEAU</a><br><br>';
  $res=$db->query("SELECT s.rowid, s.ref, s.label, s.datec, z.label as zlabel FROM ".MAIN_DB_PREFIX."reseau_secteur s LEFT JOIN ".MAIN_DB_PREFIX."reseau_zone z ON z.rowid=s.fk_zone ORDER BY s.rowid DESC");
  print '<table class="liste"><tr class="liste_titre"><td>Ref (auto)</td><td>Label</td><td>Zone</td><td>Date</td></tr>';
  while($o=$db->fetch_object($res)) print "<tr class='oddeven'><td>$o->ref</td><td>$o->label</td><td>$o->zlabel</td><td>$o->datec</td></tr>";
  print '</table>';
}

llxFooter();
