<?php
class MoteurTerrain{
    public $db; public $error;
    public function __construct($db){ $this->db=$db; }
    private function distance($lat1,$lon1,$lat2,$lon2){
        if(!$lat1 || !$lon1 || !$lat2 || !$lon2) return 999999;
        $R=6371000; $dLat=deg2rad($lat2-$lat1); $dLon=deg2rad($lon2-$lon1);
        $a=sin($dLat/2)*sin($dLat/2)+cos(deg2rad($lat1))*cos(deg2rad($lat2))*sin($dLon/2)*sin($dLon/2);
        return $R*2*atan2(sqrt($a),sqrt(1-$a));
    }
    public function enregistrerVisite($data){
        $this->db->begin();
        try{
            $dist=$this->distance($data['lat_visite'],$data['lon_visite'],$data['lat_pdv'],$data['lon_pdv']);
            $max=getDolGlobalInt('TERRAIN_DISTANCE_MAX',10);
            if($data['lat_pdv']==0 && $data['lon_pdv']==0) $class='position_inconnue';
            else $class=($dist <= $max) ? 'sur_place' : 'a_distance';
            $sql="INSERT INTO ".MAIN_DB_PREFIX."terrain_visite (fk_user_visiteur,fk_pdv,date_visite,lat_visite,lon_visite,lat_pdv,lon_pdv,distance,classification,comment,date_creation,status) VALUES (".(int)$data['fk_user_visiteur'].",".(int)$data['fk_pdv'].",NOW(),".(float)$data['lat_visite'].",".(float)$data['lon_visite'].",".(float)$data['lat_pdv'].",".(float)$data['lon_pdv'].",".$dist.",'".$this->db->escape($class)."','".$this->db->escape($data['comment'])."',NOW(),1)";
            if(!$this->db->query($sql)) throw new Exception($this->db->lasterror());
            $idVisite=$this->db->last_insert_id(MAIN_DB_PREFIX."terrain_visite");
            // Fermer ligne programme si existe
            if(!empty($data['fk_programme'])){
                $sql="UPDATE ".MAIN_DB_PREFIX."terrain_programme_pdv SET status='fait', fk_visite=".$idVisite." WHERE fk_programme=".(int)$data['fk_programme']." AND fk_pdv=".(int)$data['fk_pdv']." AND status='a_faire' LIMIT 1";
                $this->db->query($sql);
                $sql="SELECT COUNT(*) as nb FROM ".MAIN_DB_PREFIX."terrain_programme_pdv WHERE fk_programme=".(int)$data['fk_programme']." AND status='a_faire'";
                $res=$this->db->query($sql); $obj=$this->db->fetch_object($res);
                if($obj->nb==0){ $sql="UPDATE ".MAIN_DB_PREFIX."terrain_programme SET status='termine' WHERE rowid=".(int)$data['fk_programme']; $this->db->query($sql); }
            }
            $this->db->commit(); return $idVisite;
        }catch(Exception $e){ $this->db->rollback(); $this->error=$e->getMessage(); return -1; }
    }
    public function enregistrerPosition($data){
        $sql="INSERT INTO ".MAIN_DB_PREFIX."terrain_position (fk_user,lat,lon,date_position,accuracy,status) VALUES (".(int)$data['fk_user'].",".(float)$data['lat'].",".(float)$data['lon'].",NOW(),".(int)$data['accuracy'].",1)";
        if(!$this->db->query($sql)){ $this->error=$this->db->lasterror(); return -1; }
        return $this->db->last_insert_id(MAIN_DB_PREFIX."terrain_position");
    }
    public function creerProgramme($data){
        $this->db->begin();
        try{
            $sql="INSERT INTO ".MAIN_DB_PREFIX."terrain_programme (fk_user_create,fk_user_visiteur,date_debut,date_fin,label,date_creation,status) VALUES (".(int)$data['fk_user_create'].",".(int)$data['fk_user_visiteur'].",'".$this->db->escape($data['date_debut'])."','".$this->db->escape($data['date_fin'])."','".$this->db->escape($data['label'])."',NOW(),'en_cours')";
            if(!$this->db->query($sql)) throw new Exception($this->db->lasterror());
            $idProg=$this->db->last_insert_id(MAIN_DB_PREFIX."terrain_programme");
            foreach($data['pdvs'] as $idPdv){ $sql="INSERT INTO ".MAIN_DB_PREFIX."terrain_programme_pdv (fk_programme,fk_pdv,status) VALUES ($idProg,".(int)$idPdv.",'a_faire')"; $this->db->query($sql); }
            $this->db->commit(); return $idProg;
        }catch(Exception $e){ $this->db->rollback(); $this->error=$e->getMessage(); return -1; }
    }
}