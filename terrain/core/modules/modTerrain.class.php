<?php
include_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';

class modTerrain extends DolibarrModules
{
    function __construct($db){
        global $conf;
        $this->db=$db;
        $this->numero=701000;
        $this->rights_class='terrain';
        $this->family='other';
        $this->module_position=1000;
        $this->name=preg_replace('/^mod/i','',get_class($this));
        $this->description='Module Terrain';
        $this->version='1.0';
        $this->const_name='MAIN_MODULE_'.strtoupper($this->name);
        $this->picto='fa-map';
        $this->module_parts=array('triggers'=>0);
        $this->dirs=array();
        $this->config_page_url=array('terrain.php@terrain');
        $this->depends=array();
        $this->requiredby=array();
        $this->conflictwith=array();
        $this->langfiles=array("terrain@terrain");
        $this->rights=array();
        $this->rights[0]=array(701001,'Lire','r','terrain','lire');
        $this->rights[1]=array(701002,'Ecrire','w','terrain','write_own');

        $this->menu=array();
        $r=0;

        $this->menu[$r++]=array(
            'fk_menu'=>'',
            'type'=>'top',
            'titre'=>'Terrain',
            'mainmenu'=>'terrain',
            'leftmenu'=>'',
            'url'=>'/custom/terrain/visites.php',
            'langs'=>'terrain@terrain',
            'position'=>701000,
            'enabled'=>'$conf->terrain->enabled',
            'perms'=>'$user->rights->terrain->lire',
            'target'=>'',
            'user'=>2
        );

        $this->menu[$r++]=array(
            'fk_menu'=>'fk_mainmenu=terrain',
            'type'=>'left',
            'titre'=>'Visites',
            'mainmenu'=>'terrain',
            'leftmenu'=>'terrain_visites',
            'url'=>'/custom/terrain/visites.php',
            'langs'=>'terrain@terrain',
            'position'=>701002,
            'enabled'=>'$conf->terrain->enabled',
            'perms'=>'$user->rights->terrain->lire',
            'target'=>'',
            'user'=>2
        );

        $this->menu[$r++]=array(
            'fk_menu'=>'fk_mainmenu=terrain',
            'type'=>'left',
            'titre'=>'Programmes',
            'mainmenu'=>'terrain',
            'leftmenu'=>'terrain_programmes',
            'url'=>'/custom/terrain/programmes.php',
            'langs'=>'terrain@terrain',
            'position'=>701011,
            'enabled'=>'$conf->terrain->enabled',
            'perms'=>'$user->rights->terrain->lire',
            'target'=>'',
            'user'=>2
        );

        $this->menu[$r++]=array(
            'fk_menu'=>'fk_mainmenu=terrain',
            'type'=>'left',
            'titre'=>'Carte',
            'mainmenu'=>'terrain',
            'leftmenu'=>'terrain_carte',
            'url'=>'/custom/terrain/carte.php',
            'langs'=>'terrain@terrain',
            'position'=>701021,
            'enabled'=>'$conf->terrain->enabled',
            'perms'=>'$user->rights->terrain->lire',
            'target'=>'',
            'user'=>2
        );

        $this->menu[$r++]=array(
            'fk_menu'=>'fk_mainmenu=terrain',
            'type'=>'left',
            'titre'=>'Positions',
            'mainmenu'=>'terrain',
            'leftmenu'=>'terrain_positions',
            'url'=>'/custom/terrain/carte.php?mode=positions',
            'langs'=>'terrain@terrain',
            'position'=>701022,
            'enabled'=>'$conf->terrain->enabled',
            'perms'=>'$user->rights->terrain->lire',
            'target'=>'',
            'user'=>2
        );
    }

    function init($options=''){
        $this->remove($options);
        $sql=array();
        return $this->_init($sql,$options);
    }

    function remove($options=''){
        $sql=array();
        return $this->_remove($sql,$options);
    }
}
