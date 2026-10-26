<?php
require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/custom/transactions/class/transaction.class.php';
require_once DOL_DOCUMENT_ROOT.'/custom/transactions/class/moteurtransactions.class.php';
require_once DOL_DOCUMENT_ROOT.'/custom/transactions/core/lib/transactions.lib.php';
$langs->load("transactions@transactions");
$id=GETPOST('id','int'); $action=GETPOST('action','aZ09');
$object=new Transaction($db);
if($id>0) $object->fetch($id);
if($action=='add'){
  $moteur=new MoteurTransactions($db);
  $data=array('ref'=>GETPOST('ref'),'montant'=>GETPOST('montant','float'),'devise_source'=>GETPOST('devise_source'),'client_id'=>1,'user_id'=>$user->id);
  $type=GETPOST('type_operation');
  $res=$moteur->execute($type,$data);
  if($res>0){ setEventMessages("Cas $type enregistre", null, 'mesgs'); header("Location: list.php?filter=$type"); exit; }
  else{ setEventMessages("Erreur: ".$moteur->error, null, 'errors'); }
}
llxHeader('', 'Transaction', '', '', 0, 0, '', '', '', 'mod-transactions page-card');
if($action=='create'){
  print load_fiche_titre('Nouvelle Transaction - Choix du Cas', '', 'bank');
  $head=transaction_prepare_head($object); print dol_get_fiche_head($head,'card','Transaction',0,'bank');
  print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'"><input type="hidden" name="token" value="'.newToken().'"><input type="hidden" name="action" value="add">';
  print '<table class="border centpercent">';
  print '<tr><td width="200">Cas</td><td><select name="type_operation" class="flat">';
  print '<optgroup label="RECHARGEMENT"><option value="rechargement_comptant">Rechargement comptant</option><option value="rechargement_credit">Rechargement à crédit</option><option value="rechargement_acompte">Rechargement avec acompte</option></optgroup>';
  print '<optgroup label="AUTRES"><option value="recouvrement">Recouvrement</option><option value="retour_uv">Retour UV</option><option value="approvisionnement">Approvisionnement</option></optgroup>';
  print '</select></td></tr>';
  print '<tr><td>Reference</td><td><input type="text" class="flat" name="ref" value="TR-'.dol_print_date(dol_now(),'%Y%m%d%H%M%S').'"></td></tr>';
  print '<tr><td>Montant</td><td><input type="number" step="0.01" class="flat" name="montant" value="10000"></td></tr>';
  print '<tr><td>Devise</td><td><input type="text" class="flat" name="devise_source" value="XOF" size="5"></td></tr>';
  print '</table><br><input type="submit" class="button" value="Executer"></form>';
  print dol_get_fiche_end();
}else{
  $head=transaction_prepare_head($object); print dol_get_fiche_head($head,'card','Fiche',0,'bank');
  print '<table class="border centpercent"><tr><td>Ref</td><td>'.$object->ref.'</td></tr><tr><td>Cas</td><td>'.$object->type_operation.'</td></tr><tr><td>Montant</td><td>'.price($object->montant).'</td></tr></table>';
  print dol_get_fiche_end();
}
llxFooter(); $db->close();