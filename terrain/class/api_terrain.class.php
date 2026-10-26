<?php
class Terrain extends DolibarrApi
{
    public $db;
    public function __construct(){ global $db; $this->db=$db; }
    /** @return array @url GET ping */
    public function ping(){ return ['module'=>'terrain','ok'=>1,'time'=>date('c')]; }
    /** @return array @url GET visites */
    public function getVisites(){
        $sql="SELECT * FROM ".MAIN_DB_PREFIX."terrain_visite ORDER BY rowid DESC LIMIT 200";
        $res=$this->db->query($sql);
        if(!$res) throw new RestException(500, "GET: ".$this->db->lasterror());
        $d=[]; while($o=$this->db->fetch_object($res)) $d[]=$o; return $d;
    }
    /** @param array $request_data @return array @url POST visites */
    public function createVisite($request_data){
        $fk_pdv=intval($request_data['pdv_id'] ?? 0);
        $fk_user=intval($request_data['acteur_id'] ?? 1);
        $lat_v=floatval($request_data['lat_telephone'] ?? 0);
        $lon_v=floatval($request_data['lon_telephone'] ?? 0);
        $lat_p=floatval($request_data['lat_pdv'] ?? 0);
        $lon_p=floatval($request_data['lon_pdv'] ?? 0);
        $fk_prog_raw=intval($request_data['programme_id'] ?? 0);
        $comment=$this->db->escape($request_data['commentaire'] ?? 'test');

        // Verif FK PDV existe, sinon on prend le 1er soc dispo
        $r=$this->db->query("SELECT rowid FROM ".MAIN_DB_PREFIX."societe ORDER BY rowid LIMIT 1");
        $firstSoc = $this->db->fetch_object($r);
        if($fk_pdv==0 && $firstSoc) $fk_pdv=$firstSoc->rowid;

        // fk_programme NULL si 0 pour eviter FK constraint
        $fk_prog_sql = $fk_prog_raw>0 ? $fk_prog_raw : "NULL";

        $dist=0; $class='sur_place';
        if($lat_v && $lon_v && $lat_p && $lon_p){
            $dist = sqrt(pow($lat_v-$lat_p,2)+pow($lon_v-$lon_p,2))*111;
            if($dist>0.1) $class='a_distance';
        }

        $sql="INSERT INTO ".MAIN_DB_PREFIX."terrain_visite (fk_user_visiteur,fk_pdv,date_visite,lat_visite,lon_visite,lat_pdv,lon_pdv,distance,classification,fk_programme,comment_text,date_creation,status) VALUES ($fk_user,$fk_pdv,NOW(),$lat_v,$lon_v,$lat_p,$lon_p,$dist,'$class',$fk_prog_sql,'$comment',NOW(),1)";
        $res=$this->db->query($sql);
        if(!$res) throw new RestException(500, "INSERT: ".$this->db->lasterror()." | SQL=$sql");
        return ['ok'=>1,'id'=>$this->db->last_insert_id(MAIN_DB_PREFIX."terrain_visite"),'pdv_utilise'=>$fk_pdv,'distance'=>$dist,'class'=>$class];
    }
    /** @return array @url GET programmes */
    public function getProgrammes(){ $r=$this->db->query("SELECT * FROM ".MAIN_DB_PREFIX."terrain_programme LIMIT 100"); $d=[]; if($r) while($o=$this->db->fetch_object($r)) $d[]=$o; return $d; }
    /** @return array @url GET positions */
    public function getPositions(){ $r=$this->db->query("SELECT * FROM ".MAIN_DB_PREFIX."terrain_position LIMIT 100"); $d=[]; if($r) while($o=$this->db->fetch_object($r)) $d[]=$o; return $d; }
}
class ApiTerrain extends Terrain {}
