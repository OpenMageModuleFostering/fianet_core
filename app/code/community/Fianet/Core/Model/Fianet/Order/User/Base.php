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
 *  @author FIA-NET <support-boutique@fia-net.com>
 *  @copyright 2000-2012 FIA-NET
 *  @version Release: $Revision: 1.0.1 $
 *  @license http://www.opensource.org/licenses/OSL-3.0  Open Software License (OSL 3.0)
 */
class Fianet_Core_Model_Fianet_Order_User_Base {

    protected $_type;
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

    public function setQualityProfessional() {
        $this->qualite = 1;
    }

    public function setQualityNotProfessional() {
        $this->qualite = 2;
    }

    public function getXml() {
        $xml = '';
        $xml .= "\t" . '<utilisateur type="' . $this->_type . '" qualite="' . $this->qualite . '">' . "\n";
        if ($this->titre != '') {
            if ($this->titre == 'f') {
                $this->titre = 'mme';
            }
            $xml .= "\t\t" . '<nom titre="' . $this->titre . '"><![CDATA[' . Mage::getModel('fianet/functions')->cleanInvalidChar($this->nom) . ']]></nom>' . "\n";
        } else {
            $xml .= "\t\t" . '<nom><![CDATA[' . Mage::getModel('fianet/functions')->cleanInvalidChar($this->nom) . ']]></nom>' . "\n";
        }
        $xml .= "\t\t" . '<prenom><![CDATA[' . Mage::getModel('fianet/functions')->cleanInvalidChar($this->prenom) . ']]></prenom>' . "\n";
        //company not used in the first time for fianet
        /*if ($this->societe != '') {
            $xml .= "\t\t" . '<societe><![CDATA[' . Mage::getModel('fianet/functions')->cleanInvalidChar($this->societe) . ']]></societe>' . "\n";
        }*/
        if ($this->telhome != '') {
            $xml .= "\t\t" . '<telhome><![CDATA[' . Mage::getModel('fianet/functions')->cleanInvalidChar($this->telhome) . ']]></telhome>' . "\n";
        }
        if ($this->teloffice != '') {
            $xml .= "\t\t" . '<teloffice><![CDATA[' . Mage::getModel('fianet/functions')->cleanInvalidChar($this->teloffice) . ']]></teloffice>' . "\n";
        }
        if ($this->telmobile != '') {
            $xml .= "\t\t" . '<telmobile><![CDATA[' . Mage::getModel('fianet/functions')->cleanInvalidChar($this->telmobile) . ']]></telmobile>' . "\n";
        }
        if ($this->telfax != '') {
            $xml .= "\t\t" . '<telfax><![CDATA[' . Mage::getModel('fianet/functions')->cleanInvalidChar($this->telfax) . ']]></telfax>' . "\n";
        }
        if ($this->email != '') {
            $xml .= "\t\t" . '<email><![CDATA[' . Mage::getModel('fianet/functions')->cleanInvalidChar($this->email) . ']]></email>' . "\n";
        }

        $xml .= "\t" . '</utilisateur>' . "\n";

        return ($xml);
    }

}