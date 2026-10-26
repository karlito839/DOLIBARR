<?php
require_once DOL_DOCUMENT_ROOT.'/core/class/commonobject.class.php';
class Transaction extends CommonObject{
    public $element='transactions'; public $table_element='transactions'; public $picto='bank';
    public $rowid; public $ref; public $type_operation; public $montant; public $devise_source; public $date_creation;
    public function __construct($db){ $this->db=$db; }
    public function fetch($id){
        $sql="SELECT * FROM ".MAIN_DB_PREFIX."transactions WHERE rowid=".(int)$id;
        $res=$this->db->query($sql);
        if($res){ $obj=$this->db->fetch_object($res); if($obj){ foreach($obj as $k=>$v) $this->$k=$v; $this->id=$obj->rowid; return 1; } }
        return -1;
    }
}