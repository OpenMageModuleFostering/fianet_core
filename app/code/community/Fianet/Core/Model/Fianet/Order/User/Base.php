<?php

/**
 * 2000-2012 FIA-NET
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is available
 * through the world-wide-web at this URL: http://www.opensource.org/licenses/OSL-3.0
 * If you are unable to obtain it through the world-wide-web, please contact us
 * via http://www.fia-net-group.com/formulaire.php so we can send you a copy immediately.
 *
 *  @author Quadra Informatique <ecommerce@quadra-informatique.fr>
 *  @copyright 2000-2012 FIA-NET
 *  @version Release: $Revision: 0.9.0 $
 *  @license http://www.opensource.org/licenses/OSL-3.0  Open Software License (OSL 3.0)
 */
class Fianet_Core_Model_Fianet_Order_User_Base {

    protected $type;
    //Qualité des clients par défaut
    public $qualite = 2;
    public $titre;
    public $nom;
    public $prenom;
    public $societe;
    public $telhome;
    public $teloffice;
    public $telmobile;
    public $telfax;
    public $email;
    public $site_conso = null;

    protected function __construct() {

    }

    public function set_quality_professional() {
        $this->qualite = 1;
    }

    public function set_quality_nonprofessional() {
        $this->qualite = 2;
    }

    public function get_xml() {
        $xml = '';
        $xml .= "\t" . '<utilisateur type="' . $this->type . '" qualite="' . $this->qualite . '">' . "\n";
        if ($this->titre != '') {
            if ($this->titre == 'f') {
                $this->titre = 'mme';
            }
            $xml .= "\t\t" . '<nom titre="' . $this->titre . '">' . Mage::getModel('fianet/functions')->clean_invalid_char($this->nom) . '</nom>' . "\n";
        } else {
            $xml .= "\t\t" . '<nom>' . Mage::getModel('fianet/functions')->clean_invalid_char($this->nom) . '</nom>' . "\n";
        }
        $xml .= "\t\t" . '<prenom>' . Mage::getModel('fianet/functions')->clean_invalid_char($this->prenom) . '</prenom>' . "\n";
        if ($this->societe != '') {
            $xml .= "\t\t" . '<societe>' . Mage::getModel('fianet/functions')->clean_invalid_char($this->societe) . '</societe>' . "\n";
        }
        if ($this->telhome != '') {
            $xml .= "\t\t" . '<telhome>' . Mage::getModel('fianet/functions')->clean_invalid_char($this->telhome) . '</telhome>' . "\n";
        }
        if ($this->teloffice != '') {
            $xml .= "\t\t" . '<teloffice>' . Mage::getModel('fianet/functions')->clean_invalid_char($this->teloffice) . '</teloffice>' . "\n";
        }
        if ($this->telmobile != '') {
            $xml .= "\t\t" . '<telmobile>' . Mage::getModel('fianet/functions')->clean_invalid_char($this->telmobile) . '</telmobile>' . "\n";
        }
        if ($this->telfax != '') {
            $xml .= "\t\t" . '<telfax>' . Mage::getModel('fianet/functions')->clean_invalid_char($this->telfax) . '</telfax>' . "\n";
        }
        if ($this->email != '') {
            $xml .= "\t\t" . '<email>' . Mage::getModel('fianet/functions')->clean_invalid_char($this->email) . '</email>' . "\n";
        }
        if ($this->site_conso != null) {
            $xml .= $this->site_conso->get_xml();
        }
        $xml .= "\t" . '</utilisateur>' . "\n";

        return ($xml);
    }

}