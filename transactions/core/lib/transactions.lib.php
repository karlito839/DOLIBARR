<?php
function transaction_prepare_head($object){
    global $langs; $langs->load("transactions@transactions");
    $h=0; $head=array();
    $head[$h][0]=DOL_URL_ROOT."/custom/transactions/card.php?id=".(is_object($object)?$object->id:'');
    $head[$h][1]="Fiche"; $head[$h][2]='card'; $h++;
    $head[$h][0]=DOL_URL_ROOT."/custom/transactions/list.php";
    $head[$h][1]="Liste"; $head[$h][2]='list'; $h++;
    return $head;
}