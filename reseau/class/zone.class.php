<?php
class ReseauZone extends CommonObject {
    public $module='reseau'; public $element='reseau_zone'; public $table_element='reseau_zone';
    public $fields=array(
        'rowid' => array('type'=>'integer', 'label'=>'ID', 'visible'=>0, 'notnull'=>1, 'position'=>1),
        'ref' => array('type'=>'varchar(128)', 'label'=>'Ref', 'visible'=>1, 'notnull'=>1, 'position'=>10),
        'label' => array('type'=>'varchar(255)', 'label'=>'Label', 'visible'=>1, 'notnull'=>1, 'position'=>20, 'css'=>'minwidth500'),
        'fk_territoire' => array('type'=>'integer:ReseauTerritoire:custom/reseau/class/territoire.class.php:1', 'label'=>'Territoire', 'visible'=>1, 'notnull'=>1, 'position'=>30, 'css'=>'maxwidth500'),
        'status' => array('type'=>'integer', 'label'=>'Status', 'visible'=>0, 'default'=>1),
        'datec' => array('type'=>'datetime', 'visible'=>0, 'position'=>500),
        'tms' => array('type'=>'timestamp', 'visible'=>0, 'position'=>501),
    );

    public function create(User $user, $notrigger=0){
        if(empty($this->ref)) $this->ref = $this->generateRef($this->label);
        return parent::create($user,$notrigger);
    }
    public function update(User $user, $notrigger=0){
        // Règle : ne peut pas changer de territoire si acteurs rattachés
        if($this->fk_territoire!= $this->oldcopy->fk_territoire){
            $nb = $this->countActeurs();
            if($nb>0){ $this->error='Impossible: '.$nb.' acteur(s) rattaché(s) à cette zone'; return -1; }
        }
        return parent::update($user,$notrigger);
    }
    private function countActeurs(){ global $db; $sql="SELECT COUNT(*) FROM ".MAIN_DB_PREFIX."reseau_acteur WHERE fk_zone=".$this->id; $res=$db->query($sql); $obj=$db->fetch_array($res); return $obj[0]; }
    private function generateRef($l){ $r=str_replace(' ','_',$l); return strtoupper(preg_replace('/[^A-Z0-9_]/','',$r)); }
}
