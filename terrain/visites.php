<?php
require '../../main.inc.php';
global $db;
$action = GETPOST('action','aZ09');
$id = (int)GETPOST('id','int');
if($action=='delete' && $id>0){
    $db->query("DELETE FROM ".MAIN_DB_PREFIX."terrain_visite WHERE rowid=".$id);
    header("Location: visites.php?idmenu=89&mainmenu=terrain&leftmenu="); exit;
}
llxHeader('', 'Liste Visites');
print '<style>html,body{margin:0!important;padding:0!important} div#id-top{margin-top:0!important} .side-nav{margin-top:0!important} .fiche{margin-top:10px!important}</style>';
print load_fiche_titre('Liste des Visites', '', 'globe');
print '<a href="nouvelle_visite.php?idmenu=89&mainmenu=terrain&leftmenu=" class="button" style="background:#7c5cbf;color:white">+ NOUVELLE VISITE</a><br><br>';
$sql = "SELECT * FROM ".MAIN_DB_PREFIX."terrain_visite ORDER BY rowid DESC";
$res = $db->query($sql);
print '<table class="liste" style="width:100%;border-collapse:collapse"><tr class="liste_titre"><td style="padding:8px">ID</td><td>Territoire</td><td>Date Visite</td><td>Date Creation</td><td>Commentaire</td><td>User</td><td>Distance</td><td>Action</td></tr>';
if($res) while($o=$db->fetch_object($res)){
  $datev = !empty($o->date_visite) ? $o->date_visite : $o->date_creation;
  print '<tr class="oddeven"><td style="padding:8px">'.$o->rowid.'</td><td>'.($o->fk_territoire??'-').'</td><td>'.dol_print_date($db->jdate($datev),'%d/%m/%Y %H:%M').'</td><td>'.dol_print_date($db->jdate($o->date_creation),'%d/%m/%Y').'</td><td>'.dol_trunc($o->comment_text,100).'</td><td>'.$o->fk_user_visiteur.'</td><td>'.round($o->distance,3).' km</td><td><a href="visites.php?idmenu=89&mainmenu=terrain&leftmenu=&action=delete&id='.$o->rowid.'" class="button" style="background:#e74c3c;color:white;padding:4px 10px" onclick="return confirm(\'Supprimer ?\')">SUPPRIMER</a></td></tr>';
}
print '</table>'; llxFooter();
