<?php
require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/custom/transactions/class/transaction.class.php';
$langs->load("transactions@transactions");
$filter=GETPOST('filter','aZ09');
llxHeader('', 'Transactions', '', '', 0, 0, '', '', '', 'mod-transactions page-list');
print '<div class="fiche">';
$label=$filter?ucfirst(str_replace('_',' ',$filter)):"Toutes";
print load_fiche_titre('Liste - '.$label.' <a href="card.php?action=create" class="butAction">Nouvelle Transaction</a>', '', 'bank');
print '<div class="div-table-responsive"><table class="tagtable liste"><tr class="liste_titre"><th>Ref</th><th>Cas</th><th>Montant</th><th>Devise</th><th>Date</th><th>Action</th></tr>';
$sql="SELECT rowid, ref, type_operation, montant, devise_source, date_creation FROM ".MAIN_DB_PREFIX."transactions";
if($filter) $sql.=" WHERE type_operation='".$db->escape($filter)."'";
$sql.=" ORDER BY date_creation DESC LIMIT 100";
$res=$db->query($sql);
if($res){ while($obj=$db->fetch_object($res)){ echo '<tr class="oddeven"><td><a href="card.php?id='.$obj->rowid.'">'.$obj->ref.'</a></td><td><span class="badge">'.str_replace('_',' ',$obj->type_operation).'</span></td><td>'.price($obj->montant).'</td><td>'.$obj->devise_source.'</td><td>'.$obj->date_creation.'</td><td><a href="card.php?id='.$obj->rowid.'" class="butActionSmall">Voir</a></td></tr>'; } }
print '</table></div></div>';
llxFooter(); $db->close();