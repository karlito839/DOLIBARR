<?php
require '../../main.inc.php';
global $db,$user;
$action = GETPOST('action','aZ09');
if($action=='add'){
    $fk_territoire = (int)GETPOST('fk_territoire','int');
    $fk_user = (int)GETPOST('fk_user_visiteur','int'); if(!$fk_user) $fk_user=$user->id;
    $type = $db->escape(GETPOST('type_visite','alpha')); if(!$type) $type='Prospection';
    $comment = $db->escape(GETPOST('commentaire','restricthtml'));
    $date_input = GETPOST('date_visite','alpha');
    $timestamp = $date_input ? strtotime($date_input.' 12:00:00') : time();
    if($timestamp >= strtotime(date('Y-m-d'))){
        $cols=[]; $r=$db->query("SHOW COLUMNS FROM ".MAIN_DB_PREFIX."terrain_visite"); while($c=$db->fetch_object($r)) $cols[]=$c->Field;
        $d=[];
        if(in_array('fk_user_visiteur',$cols)) $d['fk_user_visiteur']=$fk_user;
        if(in_array('fk_pdv',$cols)) $d['fk_pdv']=1;
        if(in_array('fk_territoire',$cols)) $d['fk_territoire']=$fk_territoire?:'NULL';
        if(in_array('type_visite',$cols)) $d['type_visite']="'$type'";
        if(in_array('classification',$cols)) $d['classification']="'sur_place'";
        if(in_array('comment_text',$cols)) $d['comment_text']="'".($type.' - '.$comment)."'";
        if(in_array('distance',$cols)) $d['distance']=0;
        if(in_array('lat_visite',$cols)) $d['lat_visite']="'5.359'";
        if(in_array('lon_visite',$cols)) $d['lon_visite']="'".'-4.008'."'";
        if(in_array('lat_pdv',$cols)) $d['lat_pdv']="'5.360'";
        if(in_array('lon_pdv',$cols)) $d['lon_pdv']="'".'-4.009'."'";
        if(in_array('date_creation',$cols)) $d['date_creation']="'".$db->idate(dol_now())."'";
        if(in_array('date_visite',$cols)) $d['date_visite']="'".$db->idate($timestamp)."'";
        if(in_array('status',$cols)) $d['status']=1;
        $sql="INSERT INTO ".MAIN_DB_PREFIX."terrain_visite (".implode(',',array_keys($d)).") VALUES (".implode(',',array_values($d)).")";
        if($db->query($sql)){ header("Location: visites.php?idmenu=89&mainmenu=terrain&leftmenu="); exit; }
    }
}
llxHeader('', 'Nouvelle Visite');
print load_fiche_titre('Nouvelle Visite', '', 'globe');
$ters=$db->query("SELECT rowid,label FROM ".MAIN_DB_PREFIX."reseau_territoire ORDER BY label");
$acteurs=$db->query("SELECT fk_user,label,role_acteur FROM ".MAIN_DB_PREFIX."reseau_acteur WHERE fk_user IS NOT NULL ORDER BY label");
$today=date('Y-m-d');
print '<form method="POST" action="'.$_SERVER['PHP_SELF'].'?idmenu=89&mainmenu=terrain&leftmenu="><input type="hidden" name="token" value="'.newToken().'"><input type="hidden" name="action" value="add"><table style="width:100%;max-width:900px">';
print '<tr><td style="width:280px;padding:12px">Date de visite *</td><td><input type="date" name="date_visite" value="'.$today.'" min="'.$today.'" style="padding:8px;width:220px;border:1px solid #999" required></td></tr>';
print '<tr><td style="padding:12px">Territoire</td><td><select name="fk_territoire" style="width:100%;max-width:400px;padding:8px"><option value="0">-- Choisir --</option>'; if($ters) while($t=$db->fetch_object($ters)) print '<option value="'.$t->rowid.'">'.$t->label.'</option>'; print '</select></td></tr>';
print '<tr><td style="padding:12px">Acteur (Commercial / Superviseur / Administrateur)</td><td><select name="fk_user_visiteur" style="width:100%;max-width:400px;padding:8px"><option value="'.$user->id.'">Moi - '.$user->lastname.'</option>'; if($acteurs) while($a=$db->fetch_object($acteurs)) print '<option value="'.$a->fk_user.'">'.$a->label.' - '.ucfirst($a->role_acteur).'</option>'; print '</select></td></tr>';
print '<tr><td style="padding:12px">Type de Visite</td><td><select name="type_visite" style="width:100%;max-width:400px;padding:8px"><option value="Prospection" selected>Prospection</option><option value="Visite">Visite</option><option value="Recouvrement">Recouvrement</option><option value="Livraison">Livraison</option></select></td></tr>';
print '<tr><td style="padding:12px;vertical-align:top">Commentaire</td><td><textarea name="commentaire" rows="6" style="width:100%;max-width:600px;padding:10px;border:1px solid #ccc"></textarea></td></tr>';
print '<tr><td></td><td style="padding-top:20px"><input type="submit" class="button" value="CREER" style="background:#7c5cbf;color:white;padding:10px 30px;font-weight:bold"></td></tr>';
print '</table></form>'; llxFooter();
