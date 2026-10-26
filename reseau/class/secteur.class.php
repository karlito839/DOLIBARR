<?php
class ReseauSecteur extends CommonObject {
    public $module='reseau'; public $element='reseau_secteur'; public $table_element='reseau_secteur';
    public $fields=array(
        'rowid' => array('type'=>'integer', 'label'=>'ID', 'visible'=>0, 'notnull'=>1),
        'ref' => array('type'=>'varchar(128)', 'label'=>'Ref', 'visible'=>1, 'notnull'=>1),
        'label' => array('type'=>'varchar(255)', 'label'=>'Label', 'visible'=>1, 'notnull'=>1, 'css'=>'minwidth500'),
        'fk_zone' => array('type'=>'integer:ReseauZone:custom/reseau/class/zone.class.php:1', 'label'=>'Zone', 'visible'=>1, 'notnull'=>1),
        // Champs auto-remplis pour API
        'fk_territoire' => array('type'=>'integer:ReseauTerritoire:custom/reseau/class/territoire.class.php:1', 'label'=>'Territoire', 'visible'=>1, 'noteditable'=>1),
        'status' => array('type'=>'integer', 'visible'=>0, 'default'=>1),
        'datec' => array('type'=>'datetime', 'visible'=>0), 'tms' => array('type'=>'timestamp', 'visible'=>0),
    );

    public function create(User $user, $notrigger=0){
        if(empty($this->ref)) $this->ref = strtoupper(str_replace(' ','_',$this->label));
        // Règle : Choisir un secteur renseigne auto sa zone et son territoire
        if($this->fk_zone){
            $zone = new ReseauZone($this->db); $zone->fetch($this->fk_zone);
            $this->fk_territoire = $zone->fk_territoire;
        }
        return parent::create($user,$notrigger);
    }
}
