<?php

class Fianet_Core_Model_Product extends Mage_Catalog_Model_Product
{
	public function getFianetProductType()
	{
		//Selection du type de produit FIA-NET selon les conditions suivantes
		//Type de produit par d�faut si rien n'est trouv�
		//En cas de cat�gories multiples c'est le type de produit le plus nombreux qui est choisit.
		$productType = Mage::getModel('fianet/configuration_global')
						->load('DEFAULT_TYPE_PRODUCT')
						->Value;
		$categoriesCollection = $this->getCategoryCollection();
		$configuredCategories = Mage::getModel('fianet/catproduct_association')
								->getCollection()
								->getConfiguredCategoriesCollection();
		
		$list = array();
		
		foreach ($categoriesCollection as $categorie)
		{
			$id = $categorie->getId();
			if (isset($configuredCategories[$id]))
			{
				$list[$id] = $configuredCategories[$id];
			}
		}
		
		$count = array();
		$max = 0;
		foreach ($list as $catId => $typeProduct)
		{
			$count[$typeProduct] = isset($count[$typeProduct]) == true ? $count[$typeProduct] + 1 : 1;
			if ($count[$typeProduct] > $max)
			{
				$max = $count[$typeProduct];
				$productType = $typeProduct;
			}
		}
		return ($productType);
	}
}