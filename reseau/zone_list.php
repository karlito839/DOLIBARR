<?php
require '../../main.inc.php';

if ($_POST['action']=='add'){
  $label = $db->escape(GETPOST('label','alpha'));
  $fk_territoire = (int)GETPOST('fk_territoire','int');

  $resmax = $db->query("SELECT MAX(rowid) as maxid FROM ".MAIN_DB_PREFIX."reseau_zone");
  $objmax = $db->fetch_object($resmax);
  $next = (int)($objmax->maxid+1);
  if ($next < 1) $next = 1;
  $ref = "ZONE-".date('Y')."-".str_pad($next, 4, '0', STR_PAD_LEFT);

  $db->query("INSERT INTO ".MAIN_DB_PREFIX."reseau_zone (ref,label,fk_territoire,status,datec) VALUES ('$ref','$label',$fk_territoire,1,NOW())");
  header("Location: zone_list.php"); exit;
}

llxHeader();

if ($_GET['action']=='create'){
  print '<h2>Nouvelle Zone</h2><form method="POST"><input type="hidden" name="action" value="add">';
  print 'Ref <input type="text" placeholder="Auto : ZONE-2026-0001" style="background:#eee" readonly> <small style="color:green">auto</small><br><br>';
  print 'Label <input type="text" name="label" required style="width:400px"><br><br>';
  print 'Territoire <select name="fk_territoire">';
  $r=$db->query("SELECT rowid,label FROM ".MAIN_DB_PREFIX."reseau_territoire ORDER BY label");
  while($o=$db->fetch_object($r)) print "<option value='$o->rowid'>$o->label</option>";
  print '</select><br><br>';
  print '<button class="button" type="submit">CREER</button> <a class="button" href="zone_list.php">ANNULER</a></form>';
} else {
  print '<h2>Zones</h2><a class="button" href="zone_list.php?action=create">+ NOUVEAU</a><br><br>';
  $res=$db->query("SELECT z.rowid, z.ref, z.label, z.datec, t.label as tlabel FROM ".MAIN_DB_PREFIX."reseau_zone z LEFT JOIN ".MAIN_DB_PREFIX."reseau_territoire t ON t.rowid=z.fk_territoire ORDER BY z.rowid DESC");
  print '<table class="liste"><tr class="liste_titre"><td>Ref (auto)</td><td>Label</td><td>Territoire</td><td>Date</td></tr>';
  while($o=$db->fetch_object($res)) print "<tr class='oddeven'><td>$o->ref</td><td>$o->label</td><td>$o->tlabel</td><td>$o->datec</td></tr>";
  print '</table>';
}

llxFooter();
