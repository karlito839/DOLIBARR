<?php
define('NOTOKENRENEWAL',1);
require '../../main.inc.php';
llxHeader('', 'Nouvel Acteur');
print load_fiche_titre('Nouvel Acteur Réseau', '', 'user');

if (!empty($_POST['nom'])) {
  $nom = $db->escape(trim($_POST['nom']));
  $fk_user = (int)$_POST['fk_user'] ?: 'NULL';
  $fk_territoire = (int)$_POST['fk_territoire'] ?: 'NULL';
  $fk_secteur = (int)$_POST['fk_secteur'] ?: 1;
  $role = $db->escape($_POST['role']);
  $ref = 'ACT-'.time().'-'.rand(100,999);
  $db->query("INSERT INTO ".MAIN_DB_PREFIX."reseau_acteur (ref,label,type,fk_parent,fk_secteur,fk_zone,fk_territoire,fk_user,solde_uv,status,datec,type_acteur,role_acteur) VALUES ('$ref','$nom','acteur',NULL,$fk_secteur,NULL,$fk_territoire,$fk_user,0,1,NOW(),'commercial','$role')");
  header('Location: acteur_list.php?idmenu=55&mainmenu=reseau&leftmenu='); exit;
}

$users = $db->query("SELECT rowid, lastname, firstname FROM ".MAIN_DB_PREFIX."user WHERE statut=1 ORDER BY lastname");
$ters = $db->query("SELECT rowid, label FROM ".MAIN_DB_PREFIX."reseau_territoire ORDER BY label");
$sects = $db->query("SELECT rowid, label FROM ".MAIN_DB_PREFIX."reseau_secteur ORDER BY label");

print '<div style="background:#fff;padding:20px;max-width:650px;border:1px solid #ddd"><form method="POST">';
print '<label>Nom Acteur:</label><br><input type="text" name="nom" required placeholder="Ex: Marc - Abidjan Nord" style="width:100%;padding:8px;margin:10px 0"><br>';
print '<label>Utilisateur Dolibarr:</label><br><select name="fk_user" style="width:100%;padding:8px;margin:10px 0"><option value="0">-- Aucun --</option>';
while($u=$db->fetch_object($users)) print '<option value="'.$u->rowid.'">'.$u->lastname.' '.$u->firstname.'</option>';
print '</select><br>';
print '<label>Territoire:</label><br><select name="fk_territoire" style="width:100%;padding:8px;margin:10px 0"><option value="0">--</option>';
while($t=$db->fetch_object($ters)) print '<option value="'.$t->rowid.'">'.$t->label.'</option>';
print '</select><br>';
print '<label>Secteur:</label><br><select name="fk_secteur" style="width:100%;padding:8px;margin:10px 0">';
if($sects) while($s=$db->fetch_object($sects)) print '<option value="'.$s->rowid.'">'.$s->label.'</option>';
else print '<option value="1">Secteur par défaut</option>';
print '</select><br>';
print '<label>Rôle:</label><br><select name="role" style="width:100%;padding:8px;margin:10px 0"><option value="commercial">Commercial</option><option value="superviseur">Superviseur</option><option value="administrateur">Administrateur</option></select><br><br>';
print '<input type="submit" class="button" style="background:#9b7eb8" value="CREER ACTEUR"> <a href="acteur_list.php?idmenu=55&mainmenu=reseau&leftmenu=" class="button">Annuler</a></form></div>';
llxFooter();
