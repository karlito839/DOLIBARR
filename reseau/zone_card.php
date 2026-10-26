<?php
require '../../main.inc.php';
require_once __DIR__.'/class/zone.class.php';
require_once __DIR__.'/class/territoire.class.php';

$action = GETPOST('action','aZ09'); $id = GETPOST('id','int');
$object = new ReseauZone($db);
if (!$user->rights->reseau->zone->lire) accessforbidden();

if ($action == 'add') {
    $object->label = GETPOST('label','alphanohtml');
    $object->fk_territoire = GETPOST('fk_territoire','int');
    $object->ref = '';
    $res = $object->create($user);
    if ($res > 0) { header("Location: ".$_SERVER['PHP_SELF']."?id=".$res); exit; }
    else { setEventMessages($object->error, null, 'errors'); $action='create'; }
}
if ($action == 'update' && $id>0) {
    $object->fetch($id);
    $old_territoire = $object->fk_territoire;
    $object->label = GETPOST('label','alphanohtml');
    $object->fk_territoire = GETPOST('fk_territoire','int');
    // Règle : pas de changement si acteurs rattachés
    if ($old_territoire != $object->fk_territoire) {
        $sql = "SELECT COUNT(*) as nb FROM ".MAIN_DB_PREFIX."reseau_acteur WHERE fk_zone=".$id;
        $resql=$db->query($sql); $o=$db->fetch_object($resql);
        if ($o->nb > 0) { setEventMessages("Impossible : ".$o->nb." acteur(s) rattaché(s) à cette zone", null, 'errors'); }
        else { $object->update($user); header("Location: ".$_SERVER['PHP_SELF']."?id=".$id); exit; }
    } else { $object->update($user); header("Location: ".$_SERVER['PHP_SELF']."?id=".$id); exit; }
}
if ($action == 'delete' && $id>0) { $object->fetch($id); $object->delete($user); header("Location: zone_list.php"); exit; }

llxHeader('', 'Zone');
$form = new Form($db);
if ($action == 'create') {
    print load_fiche_titre("Nouvelle Zone", '', 'fa-map-marker');
    print '<form method="POST"><input type="hidden" name="token" value="'.newToken().'"><input type="hidden" name="action" value="add">';
    print '<table class="border centpercent">';
    print '<tr><td class="fieldrequired">Territoire *</td><td>'; $form->select_dolusers('', '', '');
    print '<select name="fk_territoire" class="flat minwidth500" required>';
    $sql="SELECT rowid,label FROM ".MAIN_DB_PREFIX."reseau_territoire"; $res=$db->query($sql);
    while($obj=$db->fetch_object($res)) print '<option value="'.$obj->rowid.'">'.$obj->label.'</option>';
    print '</select></td></tr>';
    print '<tr><td>Ref</td><td><input type="text" disabled value="Auto si vide"></td></tr>';
    print '<tr><td class="fieldrequired">Label *</td><td><input type="text" name="label" class="flat minwidth500" required></td></tr>';
    print '</table><div class="center"><input type="submit" class="button" style="background:#6f42c1;color:white" value="ENREGISTRER"> <a class="button" href="zone_list.php">RETOUR</a></div></form>';
} else {
    $object->fetch($id);
    print load_fiche_titre("Zone : ".$object->label, '', 'fa-map-marker');
    print '<table class="border centpercent"><tr><td>Ref</td><td>'.$object->ref.'</td></tr><tr><td>Label</td><td>'.$object->label.'</td></tr><tr><td>Territoire</td><td>'.$object->fk_territoire.'</td></tr></table>';
    print '<div class="tabsAction"><a class="butAction" href="'.$_SERVER['PHP_SELF'].'?id='.$id.'&action=edit">Modifier</a> <a class="butActionDelete" href="'.$_SERVER['PHP_SELF'].'?id='.$id.'&action=delete&token='.newToken().'">Supprimer</a></div>';
}
llxFooter();
