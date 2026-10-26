<?php
class ReseauActeur extends CommonObject {
    public $module='reseau'; public $element='reseau_acteur'; public $table_element='reseau_acteur';
    public $fields=array(
        'rowid' => array('type'=>'integer', 'visible'=>0, 'notnull'=>1),
        'ref' => array('type'=>'varchar(128)', 'label'=>'ContactMarchand', 'visible'=>1, 'notnull'=>1, 'unique'=>1, 'searchall'=>1), // = contact marchand unique
        'label' => array('type'=>'varchar(255)', 'label'=>'NomPointVente', 'visible'=>1, 'notnull'=>1, 'css'=>'minwidth500'),
        'type' => array('type'=>'select', 'label'=>'TypeActeur', 'visible'=>1, 'notnull'=>1, 'arrayofkeyval'=>array('ADV'=>'ADV','Sous_ADV'=>'Sous_ADV','Commercial'=>'Commercial','PDV'=>'PDV')),
        'fk_parent' => array('type'=>'integer:ReseauActeur:custom/reseau/class/acteur.class.php:1', 'label'=>'Superieur', 'visible'=>1),
        'fk_secteur' => array('type'=>'integer:ReseauSecteur:custom/reseau/class/secteur.class.php:1', 'label'=>'Secteur', 'visible'=>1, 'notnull'=>1),
        'fk_zone' => array('type'=>'integer', 'visible'=>0, 'noteditable'=>1),
        'fk_territoire' => array('type'=>'integer', 'visible'=>0, 'noteditable'=>1),
        'fk_user' => array('type'=>'integer:User:user/class/user.class.php:1', 'label'=>'CompteUtilisateurLie', 'visible'=>1),
        'contact_marchand' => array('type'=>'varchar(128)', 'label'=>'ContactMarchand', 'visible'=>0), // doublon pour compat
        // Compteurs - jamais négatifs
        'solde_uv' => array('type'=>'double(24,8)', 'label'=>'SoldeUV', 'visible'=>1, 'default'=>0, 'isameasure'=>1),
        'especes_en_cours' => array('type'=>'double(24,8)', 'visible'=>1, 'default'=>0),
        'creances_a_recouvrer' => array('type'=>'double(24,8)', 'visible'=>1, 'default'=>0),
        'dette_envers_superieur' => array('type'=>'double(24,8)', 'visible'=>1, 'default'=>0),
        'plafond_credit' => array('type'=>'double(24,8)', 'visible'=>1, 'default'=>0),
        'status' => array('type'=>'integer', 'label'=>'Status', 'visible'=>1, 'default'=>0, 'arrayofkeyval'=>array(0=>'Inactif',1=>'Actif')), // créé inactif
        'datec' => array('type'=>'datetime', 'visible'=>0), 'tms' => array('type'=>'timestamp', 'visible'=>0),
    );

    public function create(User $user, $notrigger=0){
        $this->ref = $this->contact_marchand; // Le contact est la ref
        if(empty($this->ref)){ $this->error='Contact marchand obligatoire'; return -1; }
        $this->status = 0; // Toujours créé inactif
        $res = $this->verifierRegles(); if($res<0) return $res;
        // Remplir zone + territoire depuis secteur
        $secteur = new ReseauSecteur($this->db); $secteur->fetch($this->fk_secteur);
        $this->fk_zone = $secteur->fk_zone; $this->fk_territoire = $secteur->fk_territoire;
        return parent::create($user,$notrigger);
    }

    public function verifierRegles(){
        global $db;
        // 1. Contact obligatoire et unique = ref
        if(empty($this->ref)) { $this->error='Contact marchand obligatoire'; return -1; }
        // 2. Montants jamais négatifs
        foreach(['solde_uv','especes_en_cours','creances_a_recouvrer','dette_envers_superieur','plafond_credit'] as $f){
            if($this->$f < 0){ $this->error=$f.' ne peut être négatif'; return -1; }
        }
        // 3. Hiérarchie
        if($this->type=='ADV' &&!empty($this->fk_parent)){ $this->error='ADV n\'a pas de supérieur'; return -1; }
        if($this->type=='Sous_ADV'){
            if(empty($this->fk_parent)){ $this->error='Sous_ADV dépend d\'un ADV'; return -1; }
            // vérifier que parent est ADV
        }
        if($this->type=='Commercial' && empty($this->fk_parent)){ $this->error='Commercial dépend d\'un Sous_ADV'; return -1; }
        if($this->type=='PDV' && empty($this->fk_parent)){ $this->error='PDV dépend d\'un Commercial'; return -1; }
        // 4. PDV ne se connecte pas
        if($this->type=='PDV' &&!empty($this->fk_user)){ $this->error='Un PDV ne peut pas être lié à un compte utilisateur'; return -1; }
        // 5. Un compte n'est lié qu'à un seul acteur
        if(!empty($this->fk_user)){
            $sql="SELECT COUNT(*) FROM ".MAIN_DB_PREFIX."reseau_acteur WHERE fk_user=".$this->fk_user." AND rowid!= ".($this->id?:0);
            $res=$db->query($sql); if($res){ $o=$db->fetch_array($res); if($o[0]>0){ $this->error='Ce compte est déjà lié à un autre acteur'; return -1; } }
        }
        // 6. Ne peut pas être cloné - bloqué dans card.php (pas de bouton clone)
        // 7. Enregistrer ne recalcule jamais les compteurs - ne pas recalculer dans update
        return 1;
    }

    public function activer($user){ $this->fetch($this->id); $this->status=1; return $this->update($user,1); }
    public function desactiver($user){ $this->fetch($this->id); $this->status=0; return $this->update($user,1); }
}
