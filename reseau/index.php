<?php
require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
llxHeader('', 'Reseau');
print '<h2>Module Reseau</h2>';
print '<a href="territoire_card.php?action=create&token='.newToken().'" class="button">Nouveau Territoire</a> ';
print '<a href="territoire_list.php?token='.newToken().'" class="button">Liste Territoires</a> <br><br>';

$res = $db->query("SHOW TABLES LIKE '%reseau%'");
print '<h3>Tables Reseau</h3><ul>';
while($obj=$db->fetch_array($res)){ print '<li>'.$obj[0].'</li>'; }
print '</ul>';

llxFooter();
