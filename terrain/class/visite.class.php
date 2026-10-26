<?php
require_once DOL_DOCUMENT_ROOT.'/core/class/commonobject.class.php';
class Visite extends CommonObject {
    public $table_element='terrain_visite'; public $element='terrain_visite';
    public $fields=array(
        'rowid'=>array('type'=>'integer','label'=>'ID','visible'=>0),
        'fk_user_visiteur'=>array('type'=>'integer','label'=>'Acteur','visible'=>1),
        'fk_pdv'=>array('type'=>'integer','label'=>'PDV','visible'=>1),
        'date_visite'=>array('type'=>'datetime','label'=>'Date','visible'=>1),
        'lat_visite'=>array('type'=>'double','label'=>'Lat Tel','visible'=>1),
        'lon_visite'=>array('type'=>'double','label'=>'Lon Tel','visible'=>1),
        'lat_pdv'=>array('type'=>'double','label'=>'Lat PDV','visible'=>1),
        'lon_pdv'=>array('type'=>'double','label'=>'Lon PDV','visible'=>1),
        'distance'=>array('type'=>'double','label'=>'Distance','visible'=>1),
        'classification'=>array('type'=>'varchar','label'=>'Class','visible'=>1),
        'fk_programme'=>array('type'=>'integer','label'=>'Programme','visible'=>1),
        'comment_text'=>array('type'=>'text','label'=>'Commentaire','visible'=>1)
    );
    public function __construct($db){ $this->db=$db; }
    public function create($user,$notrigger=0){
        $this->db->query("INSERT INTO ".MAIN_DB_PREFIX."terrain_visite (fk_user_visiteur,fk_pdv,date_visite,lat_visite,lon_visite,lat_pdv,lon_pdv,distance,classification,fk_programme,comment_text,date_creation,status) VALUES (".intval($this->fk_user_visiteur).",".intval($this->fk_pdv).",NOW(),".floatval($this->lat_visite).",".floatval($this->lon_visite).",".floatval($this->lat_pdv).",".floatval($this->lon_pdv).",".floatval($this->distance).",'".$this->db->escape($this->classification)."',".intval($this->fk_programme).",'".$this->db->escape($this->comment_text)."',NOW(),1)");
        return $this->db->last_insert_id(MAIN_DB_PREFIX."terrain_visite");
    }
}
