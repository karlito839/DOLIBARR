<?php
// api_transactions.class.php - FIX routing
dol_include_once('/custom/transactions/class/transaction.class.php');

class Transactions extends DolibarrApi
{
    public function __construct(){ global $db; $this->db=$db; }

    /**
     * @return array
     * @url GET ping
     */
    public function ping(){ return array('ok'=>1,'module'=>'transactions','time'=>date('c')); }

    /**
     * @return array
     * @url GET rechargements
     */
    public function rechargements(){
        global $db;
        $sql = "SELECT rowid, label, amount, type, status, datec FROM ".MAIN_DB_PREFIX."transactions_transaction ORDER BY rowid DESC LIMIT 100";
        $res=$db->query($sql);
        if(!$res) return array('error'=>$db->lasterror());
        $data=[]; while($o=$db->fetch_object($res)) $data[]=$o;
        return array('table'=>'llx_transactions_transaction','data'=>$data);
    }

    /**
     * @param int $id
     * @return array
     * @url GET getById/{id}
     */
    public function getById($id){
        global $db; $id=intval($id);
        $r=$db->query("SELECT * FROM ".MAIN_DB_PREFIX."transactions_transaction WHERE rowid=".$id);
        if($r && $db->num_rows($r)>0) return $db->fetch_object($r);
        return array('error'=>'not found','id'=>$id);
    }

    /**
     * @param int $id
     * @return array
     * @url POST confirmer/{id}
     */
    public function confirmer($id){
        global $db, $user;
        $id=intval($id);
        $db->query("UPDATE ".MAIN_DB_PREFIX."transactions_transaction SET status=1, fk_user_valid=".$user->id.", date_valid=NOW() WHERE rowid=".$id);
        return array('ok'=>1,'id'=>$id,'action'=>'confirmer','status'=>1);
    }

    /**
     * @param int $id
     * @return array
     * @url POST annuler/{id}
     */
    public function annuler($id){
        global $db;
        $id=intval($id);
        $db->query("UPDATE ".MAIN_DB_PREFIX."transactions_transaction SET status=-1 WHERE rowid=".$id);
        return array('ok'=>1,'id'=>$id,'action'=>'annuler','status'=>-1);
    }
}
class ApiTransactions extends Transactions {}
