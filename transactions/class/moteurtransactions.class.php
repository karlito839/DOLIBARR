<?php
class MoteurTransactions{
    public $db; public $error;
    public function __construct($db){ $this->db=$db; }
    public function execute($type,$data){
        $this->db->begin();
        try{
            $allowed=array('rechargement_comptant','rechargement_credit','rechargement_acompte','recouvrement','retour_uv','approvisionnement');
            if(!in_array($type,$allowed)) throw new Exception("Cas inconnu: $type");
            $sql="INSERT INTO ".MAIN_DB_PREFIX."transactions (ref,type_operation,montant,devise_source,client_id,date_creation,fk_user_creat,status) VALUES ('".$this->db->escape($data['ref'])."','".$this->db->escape($type)."',".(float)$data['montant'].",'".$this->db->escape($data['devise_source'])."',".(int)$data['client_id'].",NOW(),".(int)$data['user_id'].",1)";
            $res=$this->db->query($sql);
            if(!$res) throw new Exception($this->db->lasterror());
            $this->db->commit(); return 1;
        }catch(Exception $e){ $this->db->rollback(); $this->error=$e->getMessage(); return -1; }
    }
}