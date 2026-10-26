<?php
// custom/reseau/ajax/get_parents.php
require '../../../main.inc.php';

$type = GETPOST('type','aZ09');

// Securite Dolibarr
if (!$user->id) { http_response_code(403); exit; }

$hierarchie = [
    'SOUS_ADV' => 'ADV',
    'COMMERCIAL' => 'SOUS_ADV',
    'PDV' => 'COMMERCIAL'
];

// Si type = ADV ou vide -> pas de parent
if (!isset($hierarchie[$type])) {
    echo json_encode([]);
    exit;
}

$type_parent_attendu = $hierarchie[$type];

$sql = "SELECT rowid, label, ref FROM ".MAIN_DB_PREFIX."reseau_acteur";
$sql .= " WHERE type_acteur = '".$db->escape($type_parent_attendu)."'";
$sql .= " AND status = 1";
$sql .= " ORDER BY label ASC";

$resql = $db->query($sql);
$out = [];

if ($resql) {
    while ($obj = $db->fetch_object($resql)) {
        $out[] = [
            'id' => $obj->rowid,
            'label' => $obj->label.' ['.$obj->ref.']'
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($out);
