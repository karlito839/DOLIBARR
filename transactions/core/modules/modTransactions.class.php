<?php
include_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';
class modTransactions extends DolibarrModules{
    public function __construct($db){
        $this->db=$db;
        $this->numero=700000;
        $this->family="financial";
        $this->name='transactions';
        $this->description='Module Transactions - Cas Reseau';
        $this->version='1.0';
        $this->const_name='MAIN_MODULE_TRANSACTIONS';
        $this->picto='bank';
        $this->dirs=array('/transactions/temp');
        $this->langfiles=array('transactions@transactions');
        $this->rights_class='transactions';
        $r=0;
        $this->rights[$r][0]=700001; $this->rights[$r][1]='Lire'; $this->rights[$r][2]='r'; $this->rights[$r][3]=1; $this->rights[$r][4]='read'; $r++;
        $this->rights[$r][0]=700002; $this->rights[$r][1]='Creer'; $this->rights[$r][2]='w'; $this->rights[$r][3]=0; $this->rights[$r][4]='write'; $r++;
        $this->rights[$r][0]=700003; $this->rights[$r][1]='Supprimer'; $this->rights[$r][2]='d'; $this->rights[$r][3]=0; $this->rights[$r][4]='delete'; $r++;
        $this->menu=array(); $r=0;
        $this->menu[$r++]=array('fk_menu'=>'','type'=>'top','titre'=>'Transactions','mainmenu'=>'transactions','leftmenu'=>'','url'=>'/custom/transactions/list.php','langs'=>'transactions@transactions','position'=>700000,'enabled'=>'$conf->transactions->enabled','perms'=>'$user->rights->transactions->read','target'=>'','user'=>2);
        $this->menu[$r++]=array('fk_menu'=>'fk_mainmenu=transactions','type'=>'left','titre'=>'RECHARGEMENT','mainmenu'=>'transactions','leftmenu'=>'rechargement','url'=>'','langs'=>'transactions@transactions','position'=>700001,'enabled'=>'$conf->transactions->enabled','perms'=>'$user->rights->transactions->read','target'=>'','user'=>2);
        $this->menu[$r++]=array('fk_menu'=>'fk_mainmenu=transactions,fk_leftmenu=rechargement','type'=>'left','titre'=>'Rechargement comptant','mainmenu'=>'transactions','leftmenu'=>'rechargement_comptant','url'=>'/custom/transactions/list.php?filter=rechargement_comptant','langs'=>'transactions@transactions','position'=>700002,'enabled'=>'$conf->transactions->enabled','perms'=>'$user->rights->transactions->read','target'=>'','user'=>2);
        $this->menu[$r++]=array('fk_menu'=>'fk_mainmenu=transactions,fk_leftmenu=rechargement','type'=>'left','titre'=>'Rechargement à crédit','mainmenu'=>'transactions','leftmenu'=>'rechargement_credit','url'=>'/custom/transactions/list.php?filter=rechargement_credit','langs'=>'transactions@transactions','position'=>700003,'enabled'=>'$conf->transactions->enabled','perms'=>'$user->rights->transactions->read','target'=>'','user'=>2);
        $this->menu[$r++]=array('fk_menu'=>'fk_mainmenu=transactions,fk_leftmenu=rechargement','type'=>'left','titre'=>'Rechargement avec acompte','mainmenu'=>'transactions','leftmenu'=>'rechargement_acompte','url'=>'/custom/transactions/list.php?filter=rechargement_acompte','langs'=>'transactions@transactions','position'=>700004,'enabled'=>'$conf->transactions->enabled','perms'=>'$user->rights->transactions->read','target'=>'','user'=>2);
        $this->menu[$r++]=array('fk_menu'=>'fk_mainmenu=transactions','type'=>'left','titre'=>'AUTRES','mainmenu'=>'transactions','leftmenu'=>'autres','url'=>'','langs'=>'transactions@transactions','position'=>700010,'enabled'=>'$conf->transactions->enabled','perms'=>'$user->rights->transactions->read','target'=>'','user'=>2);
        $this->menu[$r++]=array('fk_menu'=>'fk_mainmenu=transactions,fk_leftmenu=autres','type'=>'left','titre'=>'Recouvrement','mainmenu'=>'transactions','leftmenu'=>'recouvrement','url'=>'/custom/transactions/list.php?filter=recouvrement','langs'=>'transactions@transactions','position'=>700011,'enabled'=>'$conf->transactions->enabled','perms'=>'$user->rights->transactions->read','target'=>'','user'=>2);
        $this->menu[$r++]=array('fk_menu'=>'fk_mainmenu=transactions,fk_leftmenu=autres','type'=>'left','titre'=>'Retour UV','mainmenu'=>'transactions','leftmenu'=>'retour_uv','url'=>'/custom/transactions/list.php?filter=retour_uv','langs'=>'transactions@transactions','position'=>700012,'enabled'=>'$conf->transactions->enabled','perms'=>'$user->rights->transactions->read','target'=>'','user'=>2);
        $this->menu[$r++]=array('fk_menu'=>'fk_mainmenu=transactions,fk_leftmenu=autres','type'=>'left','titre'=>'Approvisionnement','mainmenu'=>'transactions','leftmenu'=>'approvisionnement','url'=>'/custom/transactions/list.php?filter=approvisionnement','langs'=>'transactions@transactions','position'=>700013,'enabled'=>'$conf->transactions->enabled','perms'=>'$user->rights->transactions->read','target'=>'','user'=>2);
    }
    public function init($options=''){ $this->remove($options); $sql=array("CREATE TABLE IF NOT EXISTS ".MAIN_DB_PREFIX."transactions (rowid INTEGER AUTO_INCREMENT PRIMARY KEY, ref VARCHAR(50), type_operation VARCHAR(20), montant DOUBLE, devise_source VARCHAR(10), client_id INTEGER, date_creation DATETIME, fk_user_creat INTEGER, status INTEGER) ENGINE=innodb;"); return $this->_init($sql,$options); }
    public function remove($options=''){ return $this->_remove(array(),$options); }
}