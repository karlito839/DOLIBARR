<?php
class JournalTerrain{
    public $db;
    public function __construct($db){ $this->db=$db; }
    public function getVisites($fkUser,$limit=100){
        $sql="SELECT v.*, u.login FROM ".MAIN_DB_PREFIX."terrain_visite v LEFT JOIN ".MAIN_DB_PREFIX."user u ON u.rowid=v.fk_user_visiteur WHERE v.status=1";
        if($fkUser) $sql.=" AND v.fk_user_visiteur=".(int)$fkUser;
        $sql.=" ORDER BY v.date_visite DESC LIMIT ".(int)$limit;
        return $this->db->query($sql);
    }
    public function getProgrammes($fkUser){
        $sql="SELECT * FROM ".MAIN_DB_PREFIX."terrain_programme WHERE status='en_cours'";
        if($fkUser) $sql.=" AND fk_user_visiteur=".(int)$fkUser;
        $sql.=" ORDER BY date_debut DESC";
        return $this->db->query($sql);
    }
    public function getPositionsJour($fkUser,$date){
        $sql="SELECT * FROM ".MAIN_DB_PREFIX."terrain_position WHERE fk_user=".(int)$fkUser." AND DATE(date_position)='".$this->db->escape($date)."' ORDER BY date_position";
        return $this->db->query($sql);
    }
}