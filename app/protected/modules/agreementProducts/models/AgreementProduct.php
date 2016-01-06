<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AgreementProduct extends Item {
    public function __toString()
        {
            if (trim($this->name) == '')
            {
                return Yii::t('Default', '(Unnamed)');
            }
            return $this->name;
        }

        public static function getModuleClassName()
        {
            return 'AgreementProductsModule';
        }

        public static function canSaveMetadata()
        {
            return true;
        }

        public static function getDefaultMetadata()
        {
            $metadata = parent::getDefaultMetadata();
            $metadata[__CLASS__] = array(
                'members' => array(
                    'name',
                    'Assembly_Frequency',
                    'Assembly_Product_Code',
                    'Assembly_Quantity',
		   'Category',
			'Category_GPM',
			'Cloned_Product',
			'Frequency',
			'minFrequency',
			'minQuantity',
			'Old_Id',
			'Old_Id_name',
			'Product_Code',
			'Quantity',
			'Total_MHR',
                ),
               'relations' => array(
			'agreement'	      => array(static::HAS_ONE,   'Agreement'),
			'Assembly_Product'    => array(static::HAS_ONE,   'Costbook'),
			'Product'             => array(static::HAS_ONE,   'Costbook'),
		),
                'rules' => array(
                    array('name',           'required'),
		    array('agreement',           'required'),
                    array('name',           'type',           'type'  => 'string'),
                    array('name',           'length',         'max'   => 100),
                    array('Assembly_Frequency',   'length',         'max'   => 12),
                    array('Assembly_Frequency',   'numerical',      'precision' => 2),
                    array('Assembly_Frequency',   'type',           'type'   => 'float'),
		    array('Assembly_Product_Code',           'type',           'type'  => 'string'),
                    array('Assembly_Quantity',   'length',         'max'   => 12),
                    array('Assembly_Quantity',   'numerical',      'precision' => 2),
                    array('Assembly_Quantity',   'type',           'type'   => 'float'),
                    array('Category',           'type',           'type'  => 'string'),
                    array('Category_GPM',   'length',         'max'   => 12),
                    array('Category_GPM',   'numerical',      'precision' => 2),
                    array('Category_GPM',   'type',           'type'   => 'float'),
                    array('Cloned_Product',           'type',           'type'  => 'boolean'),
                    array('Frequency',   'length',         'max'   => 12),
                    array('Frequency',   'numerical',      'precision' => 2),
                    array('Frequency',   'type',           'type'   => 'float'),
                    array('minFrequency',   'length',         'max'   => 12),
                    array('minFrequency',   'numerical',      'precision' => 2),
                    array('minFrequency',   'type',           'type'   => 'float'),
                    array('minQuantity',   'length',         'max'   => 12),
                    array('minQuantity',   'numerical',      'precision' => 2),
                    array('minQuantity',   'type',           'type'   => 'float'),
                    array('Old_Id',    'type',           'type'  => 'string'),
                    array('Old_Id_name',    'type',           'type'  => 'string'),
                    array('Product_Code',    'type',           'type'  => 'string'),
                    array('Quantity',   'length',         'max'   => 18),
                    array('Quantity',   'numerical',      'precision' => 2),
                    array('Quantity',   'type',           'type'   => 'float'),
		    array('Total_MHR',   'length',         'max'   => 18),
                    array('Total_MHR',   'numerical',      'precision' => 2),
                    array('Total_MHR',   'type',           'type'   => 'float'),
                ),
                'elements' => array(
		    'agreement'		=> 'Agreement',
                    'Assembly_Product'   => 'Costbook',
                    'Product'   => 'Costbook',		  
                    'name'   => 'Text',
                    'Assembly_Frequency'   => 'Decimal',
                    'Assembly_Product_Code'   => 'Text',
                    'Assembly_Quantity'   => 'Decimal',
                    'Category'   => 'Text',
                    'Category_GPM'   => 'Decimal',
                    'Cloned_Product'   => 'CheckBox',
                    'Frequency'   => 'Decimal',
                    'minFrequency'   => 'Decimal',
                    'minQuantity'   => 'Decimal',	
                    'Old_Id'   => 'Text',	
                    'Old_Id_name'   => 'Text',		
                    'Product_Code'   => 'Text',
                    'Quantity'       => 'Decimal',
                    'Total_MHR'       => 'Decimal',
                ),
                'defaultSortAttribute' => 'name',
                'noAudit' => array(
                ),
            );
            return $metadata;
        }

        public static function isTypeDeletable()
        {
            return true;
        }

        protected static function translatedAttributeLabels($language)
        {
            $params = LabelUtil::getTranslationParamsForAllModules();
            return array_merge(parent::translatedAttributeLabels($language),
                array(
		    'agreement'		=> Zurmo::t('AgreementProductsModule', 'Agreement',  $params, null, $language),
		    'Assembly_Product'   => Zurmo::t('AgreementProductsModule', 'Assembly Product',  $params, null, $language),
                    'Product'   => Zurmo::t('AgreementProductsModule', 'Product',  $params, null, $language),	  
                    'name'   => Zurmo::t('AgreementProductsModule', 'Agreement Products Name',  $params, null, $language),
                    'Assembly_Frequency'   => Zurmo::t('AgreementProductsModule', 'Assembly Frequency',  $params, null, $language),
                    'Assembly_Product_Code'   => Zurmo::t('AgreementProductsModule', 'Assembly Product Code',  $params, null, $language),
                    'Assembly_Quantity'   => Zurmo::t('AgreementProductsModule', 'Assembly Quantity',  $params, null, $language),
                    'Category'   => Zurmo::t('AgreementProductsModule', 'Category',  $params, null, $language),
                    'Category_GPM'   => Zurmo::t('AgreementProductsModule', 'Category GPM',  $params, null, $language),
                    'Cloned_Product'   => Zurmo::t('AgreementProductsModule', 'Cloned Product',  $params, null, $language),
                    'Frequency'   => Zurmo::t('AgreementProductsModule', 'Frequency',  $params, null, $language),
                    'minFrequency'   => Zurmo::t('AgreementProductsModule', 'MinFrequency',  $params, null, $language),
                    'minQuantity'   => Zurmo::t('AgreementProductsModule', 'MinQuantity',  $params, null, $language),	
                    'Old_Id'   => Zurmo::t('AgreementProductsModule', 'Old Id',  $params, null, $language),
                    'Old_Id_name'   => Zurmo::t('AgreementProductsModule', 'Old Id name',  $params, null, $language),	
                    'Product_Code'   => Zurmo::t('AgreementProductsModule', 'Product Code',  $params, null, $language),
                    'Quantity'       => Zurmo::t('AgreementProductsModule', 'Quantity',  $params, null, $language),
                    'Total_MHR'       => Zurmo::t('AgreementProductsModule', 'Total MHR',  $params, null, $language),
                )
            );
        }
}
?>
