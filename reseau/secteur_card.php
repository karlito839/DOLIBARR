<?php
require '../../main.inc.php';
require_once __DIR__.'/class/secteur.class.php';
require_once __DIR__.'/class/zone.class.php';

$action = GETPOST('action','aZ09'); $id = GETPOST('id','int');
$object = new ReseauSecteur($db);
if (!$user->rights->reseau->secteur->lire) accessforbidden();

if ($action == 'add') {
    $object->label = GETPOST('label','alphanohtml');
    $object->fk_zone = GETPOST('fk_zone','int');
    $res = $object->create($user);
    if ($res > 0) { header("Location: ".$_SERVER['PHP_SELF']."?id=".$res); exit; }
    else { setEventMessages($object->error, null, 'errors'); $action='create'; }
}
if ($action == 'update' && $id>0) {
    $object->fetch($id);
    $old_zone = $object->fk_zone;
    $object->label = GETPOST('label','alphanohtml');
    $object->fk_zone = GETPOST('fk_zone','int');
    if ($old_zone != $object->fk_zone) {
        $sql="SELECT COUNT(*) as nb FROM ".MAIN_DB_PREFIX."reseau_acteur WHERE fk_secteur=".$id;
        $resql=$db->query($sql); $o=$db->fetch_object($resql);
        if ($o->nb>0){ setEventMessages("Impossible : ".$o->nb." acteur(s) rattaché(s)", null, 'errors'); }
        else { $object->update($user); header("Location: ".$_SERVER['PHP_SELF']."?id=".$id); exit; }
    } else { $object->update($user); header("Location: ".$_SERVER['PHP_SELF']."?id=".$id); exit; }
}
if ($action == 'delete' && $id>0) { $object->fetch($id); $object->delete($user); header("Location: secteur_list.php"); exit; }

llxHeader('', 'Secteur');
if ($action == 'create') {
    print load_fiche_titre("Nouveau Secteur", '', 'fa-th-large');
    print '<form method="POST"><input type="hidden" name="token" value="'.newToken().'"><input type="hidden" name="action" value="add">';
    print '<table class="border centpercent">';
    print '<tr><td class="fieldrequired">Zone *</td><td><select name="fk_zone" class="flat minwidth500" required>';
    $sql="SELECT rowid,label FROM ".MAIN_DB_PREFIX."reseau_zone"; $res=$db->query($sql);
    while($obj=$db->fetch_object($res)) print '<option value="'.$obj->rowid.'">'.$obj->label.'</option>';
    print '</select><br><small>Choisir un secteur renseignera auto sa zone et son territoire (règle métier)</small></td></tr>';
    print '<tr><td>Ref</td><td><input type="text" disabled value="Auto si vide"></td></tr>';
    print '<tr><td class="fieldrequired">Label *</td><td><input type="text" name="label" class="flat minwidth500" required></td></tr>';
    print '</table><div class="center"><input type="submit" class="button" style="background:#6f42c1;color:white" value="ENREGISTRER"> <a class="button" href="secteur_list.php">RETOUR</a></div></form>';
} else {
    $object->fetch($id);
    print load_fiche_titre("Secteur : ".$object->label, '', 'fa-th-large');
    print '<table class="border centpercent"><tr><td>Ref</td><td>'.$object->ref.'</td></tr><tr><td>Label</td><td>'.$object->label.'</td></tr><tr><td>Zone</td><td>'.$object->fk_zone.'</td></tr><tr><td>Territoire auto</td><td>'.$object->fk_territoire.'</td></tr></table>';
    print '<div class="tabsAction"><a class="butAction" href="'.$_SERVER['PHP_SELF'].'?id='.$id.'&action=edit">Modifier</a> <a class="butActionDelete" href="'.$_SERVER['PHP_SELF'].'?id='.$id.'&action=delete&token='.newToken().'">Supprimer</a></div>';
}
llxFooter();
