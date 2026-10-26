<?php
require_once DOL_DOCUMENT_ROOT.'/custom/terrain/class/moteurterrain.class.php';
class TerrainApi extends DolibarrApi{
    public function __construct(){ global $db; $this->db=$db; }
    /**
     * @url POST /visite
     */
    public function postVisite($fk_pdv,$lat,$lon,$comment=''){
        global $user; $moteur=new MoteurTerrain($this->db);
        // Récup lat/lon PDV depuis table reseau ou societe
        $sql="SELECT latitude, longitude FROM ".MAIN_DB_PREFIX."societe WHERE rowid=".(int)$fk_pdv; $res=$this->db->query($sql); $obj=$this->db->fetch_object($res);
        $data=array('fk_user_visiteur'=>$user->id,'fk_pdv'=>$fk_pdv,'lat_visite'=>$lat,'lon_visite'=>$lon,'lat_pdv'=>$obj?$obj->latitude:0,'lon_pdv'=>$obj?$obj->longitude:0,'comment'=>$comment);
        $id=$moteur->enregistrerVisite($data);
        if($id<0) throw new RestException(500,$moteur->error);
        return array('id'=>$id);
    }
    /**
     * @url POST /position
     */
    public function postPosition($lat,$lon,$accuracy=0){
        global $user; $moteur=new MoteurTerrain($this->db);
        $id=$moteur->enregistrerPosition(array('fk_user'=>$user->id,'lat'=>$lat,'lon'=>$lon,'accuracy'=>$accuracy));
        return array('id'=>$id);
    }
}