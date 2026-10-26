<?php
class Reseau extends DolibarrApi
{
    public function __construct(){ global $db; $this->db=$db; }
    public function ping(){ return array('ok'=>1,'module'=>'reseau','time'=>date('c')); }
    public function moi(){ global $user; return array('ok'=>1,'login'=>$user->login,'id'=>$user->id); }
    public function acteurs(){
        global $db;
        $res=$db->query("SELECT rowid, ref, label, type, fk_secteur, fk_zone, fk_territoire, status, solde_uv FROM ".MAIN_DB_PREFIX."reseau_acteur ORDER BY rowid DESC LIMIT 200");
        if(!$res) return array('error'=>$db->error(), 'sql'=>$db->lastquery);
        $list=[]; while($o=$db->fetch_object($res)) $list[]=$o; return $list;
    }
    public function secteurs(){ global $db; $r=$db->query("SELECT rowid, ref, label FROM ".MAIN_DB_PREFIX."reseau_secteur LIMIT 100"); $l=[]; if($r) while($o=$db->fetch_object($r)) $l[]=$o; return $l; }
    public function zones(){ global $db; $r=$db->query("SELECT * FROM ".MAIN_DB_PREFIX."reseau_zone LIMIT 100"); $l=[]; if($r) while($o=$db->fetch_object($r)) $l[]=$o; return $l; }
    public function territoires(){ global $db; $r=$db->query("SELECT * FROM ".MAIN_DB_PREFIX."reseau_territoire LIMIT 100"); $l=[]; if($r) while($o=$db->fetch_object($r)) $l[]=$o; return $l; }
    public function statistiques(){
        global $db;
        $a=$db->query("SELECT COUNT(*) as n FROM ".MAIN_DB_PREFIX."reseau_acteur"); $nb_a=$a?$db->fetch_object($a)->n:0;
        $s=$db->query("SELECT COUNT(*) as n FROM ".MAIN_DB_PREFIX."reseau_secteur"); $nb_s=$s?$db->fetch_object($s)->n:0;
        return array('ok'=>1,'nb_acteurs'=>$nb_a,'nb_secteurs'=>$nb_s,'nb_territoires'=>0,'nb_zones'=>0);
    }
    public function cle_client(){ global $user; return array('ok'=>1,'api_key'=>$user->api_key); }
}
class ApiReseau extends Reseau {}
