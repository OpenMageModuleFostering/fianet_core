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
class Fianet_Core_Model_Product extends Mage_Catalog_Model_Product {

    public function getFianetProductType() {
        //Selection du type de produit FIA-NET selon les conditions suivantes
        //Type de produit par défaut si rien n'est trouvé
        //En cas de catégories multiples c'est le type de produit le plus nombreux qui est choisit.
        $productType = Mage::getModel('fianet/configuration_global')
                        ->load('DEFAULT_TYPE_PRODUCT')
                        ->Value;
        $categoriesCollection = $this->getCategoryCollection();
        $configuredCategories = Mage::getModel('fianet/catproduct_association')
                ->getCollection()
                ->getConfiguredCategoriesCollection();

        $list = array();

        foreach ($categoriesCollection as $categorie) {
            $id = $categorie->getId();
            if (isset($configuredCategories[$id])) {
                $list[$id] = $configuredCategories[$id];
            }
        }

        $count = array();
        $max = 0;
        foreach ($list as $catId => $typeProduct) {
            $count[$typeProduct] = isset($count[$typeProduct]) == true ? $count[$typeProduct] + 1 : 1;
            if ($count[$typeProduct] > $max) {
                $max = $count[$typeProduct];
                $productType = $typeProduct;
            }
        }
        return ($productType);
    }

}