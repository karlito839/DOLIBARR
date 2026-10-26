<?php
class ReseauTerritoire extends CommonObject {
    public $module='reseau'; public $element='reseau_territoire'; public $table_element='reseau_territoire';
    public $picto='fa-map'; public $isextrafieldmanaged=1;

    public $fields=array(
        'rowid' => array('type'=>'integer', 'label'=>'ID', 'visible'=>0, 'notnull'=>1, 'position'=>1),
        'ref' => array('type'=>'varchar(128)', 'label'=>'Ref', 'visible'=>1, 'notnull'=>1, 'position'=>10, 'searchall'=>1),
        'label' => array('type'=>'varchar(255)', 'label'=>'Label', 'visible'=>1, 'notnull'=>1, 'position'=>20, 'searchall'=>1, 'css'=>'minwidth500', 'cssview'=>'wordbreak'),
        'status' => array('type'=>'integer', 'label'=>'Status', 'visible'=>0, 'position'=>90, 'default'=>1, 'arrayofkeyval'=>array(0=>'Inactif',1=>'Actif')),
        'datec' => array('type'=>'datetime', 'label'=>'DateCreation', 'visible'=>0, 'position'=>500),
        'tms' => array('type'=>'timestamp', 'label'=>'DateModification', 'visible'=>0, 'position'=>501),
    );

    public function create(User $user, $notrigger=0){
        if(empty($this->label)) { $this->error='Label obligatoire'; return -1; }
        if(empty($this->ref)) $this->ref = $this->generateRefFromLabel($this->label);
        return parent::create($user,$notrigger);
    }
    private function generateRefFromLabel($label){
        $ref = dol_sanitizeFileName($label);
        $ref = str_replace(' ', '_', $label);
        $ref = strtoupper($ref);
        $ref = preg_replace('/[^A-Z0-9_]/','',$ref);
        return $ref;
    }
    public function verifierRegles(){
        global $db;
        // Une zone ou un secteur ne peut pas changer de niveau superieur tant que des acteurs y sont rattachés -> vérifié dans Zone/Secteur
        // Nom garde ses espaces, ref est technique déduit
        return 1;
    }
}
