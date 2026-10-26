<?php
// custom/reseau/core/modules/modReseau.class.php
include_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';

class modReseau extends DolibarrModules {
    public function __construct($db){
        global $langs;
        $this->db=$db;
        $this->numero=500001;
        $this->rights_class='reseau';
        $this->family='crm';
        $this->module_position='10';
        $this->name='reseau';
        $this->description='Gestion Reseau Distribution ADV/SOUS_ADV/COMMERCIAL/PDV';
        $this->version='1.0';
        $this->const_name='MAIN_MODULE_'.strtoupper($this->name);
        $this->picto='building';

        $this->module_parts=array('triggers'=>0,'login'=>0,'substitutions'=>0,'menus'=>0,'tpl'=>0,'barcode'=>0,'models'=>0,'css'=>array('/reseau/css/reseau.css'),'js'=>array('/reseau/js/reseau.js'));

        $this->dirs=array('/reseau/temp');
        $this->config_page_url=array('about.php@reseau');
        $this->hidden=false;
        $this->depends=array();
        $this->requiredby=array();
        $this->conflictwith=array();
        $this->langfiles=array('reseau@reseau');
        $this->warnings_activation=array();
        $this->warnings_activation_ext=array();

        // Menus Top
        $this->menu=array();
        $r=0;
        $this->menu[$r++]=array('fk_menu'=>'fk_mainmenu=home','type'=>'top','titre'=>'Reseau','mainmenu'=>'reseau','leftmenu'=>'','url'=>'/reseau/index.php','langs'=>'reseau@reseau','position'=>1000,'enabled'=>'$conf->reseau->enabled','perms'=>'$user->rights->reseau->read','target'=>'','user'=>2);

        // Left Menu
        $this->menu[$r++]=array('fk_menu'=>'fk_mainmenu=reseau','type'=>'left','titre'=>'Territoires','mainmenu'=>'reseau','leftmenu'=>'territoire','url'=>'/reseau/territoire_list.php','langs'=>'reseau@reseau','position'=>1001,'enabled'=>'$conf->reseau->enabled','perms'=>'$user->rights->reseau->read','target'=>'','user'=>2);
        $this->menu[$r++]=array('fk_menu'=>'fk_mainmenu=reseau','type'=>'left','titre'=>'Zones','mainmenu'=>'reseau','leftmenu'=>'zone','url'=>'/reseau/zone_list.php','langs'=>'reseau@reseau','position'=>1002,'enabled'=>'$conf->reseau->enabled','perms'=>'$user->rights->reseau->read','target'=>'','user'=>2);
        $this->menu[$r++]=array('fk_menu'=>'fk_mainmenu=reseau','type'=>'left','titre'=>'Secteurs','mainmenu'=>'reseau','leftmenu'=>'secteur','url'=>'/reseau/secteur_list.php','langs'=>'reseau@reseau','position'=>1003,'enabled'=>'$conf->reseau->enabled','perms'=>'$user->rights->reseau->read','target'=>'','user'=>2);
        $this->menu[$r++]=array('fk_menu'=>'fk_mainmenu=reseau','type'=>'left','titre'=>'Acteurs','mainmenu'=>'reseau','leftmenu'=>'acteur','url'=>'/reseau/acteur_list.php','langs'=>'reseau@reseau','position'=>1004,'enabled'=>'$conf->reseau->enabled','perms'=>'$user->rights->reseau->read','target'=>'','user'=>2);

        $this->rights=array();
        $this->rights[0]=array(500001,'Lire reseau','r','read','read');
        $this->rights[1]=array(500002,'Creer/Modifier reseau','w','write','write');
        $this->rights[2]=array(500003,'Supprimer reseau','d','delete','delete');
    }
    public function init($options=''){
        $sql=array();
        $this->_load_tables('/reseau/sql/');
        return $this->_init($sql,$options);
    }
    public function remove($options=''){
        $sql=array();
        return $this->_remove($sql,$options);
    }
}
