<?php
require '../../main.inc.php';
$langs->load("terrain@terrain");

llxHeader('', 'Programmes');
print load_fiche_titre('Programmes de visites', '', 'map');

// --- CREATION SIMPLE SANS CLASSE ---
if (GETPOST('action') == 'add') {
    $fk_visiteur = GETPOST('fk_user_visiteur', 'int');
    $label = $db->escape(GETPOST('label', 'alphanohtml'));
    $d1 = $db->escape(GETPOST('date_debut', 'alpha'));
    $d2 = $db->escape(GETPOST('date_fin', 'alpha'));
    $territoires = GETPOST('fk_territoires', 'array');

    if ($fk_visiteur && $label && $d1) {
        $sql = "INSERT INTO ".MAIN_DB_PREFIX."terrain_programme (fk_user_create,fk_user_visiteur,date_debut,date_fin,label,status,datec)
                VALUES (".$user->id.",$fk_visiteur,'$d1','$d2','$label','valide',NOW())";
        $db->query($sql);
        $id_prog = $db->last_insert_id(MAIN_DB_PREFIX."terrain_programme");

        if ($id_prog && !empty($territoires)) {
            foreach ($territoires as $fk_terr) {
                $fk_terr = (int)$fk_terr;
                $db->query("INSERT INTO ".MAIN_DB_PREFIX."terrain_programme_ligne (fk_programme,fk_territoire,status) VALUES ($id_prog,$fk_terr,'a_faire')");
            }
        }
        setEventMessages("Programme $id_prog créé", null);
    }
}

// --- FORM ---
print '<div style="max-width:900px;background:#fff;padding:20px;border:1px solid #ddd;margin-bottom:20px">';
print '<form method="POST"><input type="hidden" name="action" value="add"><input type="hidden" name="token" value="'.newToken().'">';

print 'Visiteur <select name="fk_user_visiteur" class="flat" required><option value="">--</option>';
$resU = $db->query("SELECT rowid, lastname, firstname FROM ".MAIN_DB_PREFIX."user WHERE statut=1 ORDER BY lastname");
while($u=$db->fetch_object($resU)) print '<option value="'.$u->rowid.'">'.$u->lastname.' '.$u->firstname.'</option>';
print '</select> ';

print ' Du <input type="date" name="date_debut" class="flat" value="'.date('Y-m-d').'" required>';
print ' Au <input type="date" name="date_fin" class="flat" value="'.date('Y-m-d',strtotime('+5 days')).'" required>';
print ' Label <input type="text" name="label" class="flat" placeholder="Ex: Tournee S42" required size="20"><br><br>';

print 'Territoires : <br><select name="fk_territoires[]" class="flat" multiple style="width:100%;height:130px">';
$resT = $db->query("SELECT rowid, label FROM ".MAIN_DB_PREFIX."reseau_territoire ORDER BY label");
if($resT) while($t=$db->fetch_object($resT)) print '<option value="'.$t->rowid.'">'.$t->label.'</option>';
print '</select><br><br>';

print '<input type="submit" class="button" style="background:#7c5cbf" value="CREER LE PROGRAMME">';
print '</form></div>';

// --- LISTE ---
print '<table class="liste"><tr class="liste_titre"><td>ID</td><td>Visiteur</td><td>Periode</td><td>Label</td><td>Status</td></tr>';
$res = $db->query("SELECT rowid, fk_user_visiteur, date_debut, date_fin, label, status FROM ".MAIN_DB_PREFIX."terrain_programme ORDER BY rowid DESC");
if($res) while($o=$db->fetch_object($res)) {
    print '<tr class="oddeven"><td>'.$o->rowid.'</td><td>'.$o->fk_user_visiteur.'</td><td>'.$o->date_debut.' -> '.$o->date_fin.'</td><td>'.$o->label.'</td><td>'.$o->status.'</td></tr>';
} else {
    print '<tr><td colspan="5">Erreur SQL: '.$db->lasterror().'</td></tr>';
}
print '</table>';

llxFooter();
$db->close();
