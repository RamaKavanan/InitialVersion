<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class CategoriesModule extends SecurableModule
    {
        const RIGHT_CREATE_CATEGORIES = 'Create Categories';
        const RIGHT_DELETE_CATEGORIES = 'Delete Categories';
        const RIGHT_ACCESS_CATEGORIES = 'Access Categories Tab';

        public function getDependencies()
        {
            return array(
                'configuration',
                'zurmo',
            );
        }

        public function getRootModelNames()
        {
            return array('Category');
        }

        public static function getTranslatedRightsLabels()
        {
            $params                              = LabelUtil::getTranslationParamsForAllModules();
            $labels                              = array();
            $labels[self::RIGHT_CREATE_CATEGORIES] = Zurmo::t('CategoriesModule', 'Create CategoriesModulePluralLabel',     $params);
            $labels[self::RIGHT_DELETE_CATEGORIES] = Zurmo::t('CategoriesModule', 'Delete CategoriesModulePluralLabel',     $params);
            $labels[self::RIGHT_ACCESS_CATEGORIES] = Zurmo::t('CategoriesModule', 'Access CategoriesModulePluralLabel Tab', $params);
            return $labels;
        }

        public static function getDefaultMetadata()
        {
            $metadata = array();
            $metadata['global'] = array(
                'adminTabMenuItems' => array(
                    array(
                        'label'  => "eval:Zurmo::t('CategoriesModule', 'CategoriesModulePluralLabel', \$translationParams)",
                        'url'    => array('/categories/default'),
                        'right'  => self::RIGHT_ACCESS_CATEGORIES,
                        'mobile' => true,
                    ),
                ),
                'designerMenuItems' => array(
                    'showFieldsLink' => true,
                    'showGeneralLink' => true,
                    'showLayoutsLink' => true,
                    'showMenusLink' => true,
                ),
				'headerMenuItems' => array(
                    array(
                        'label'  => "eval:Zurmo::t('CategoriesModule', 'Categories')",
                        'url'    => array('/categories/default'),
                        'right'  => self::RIGHT_ACCESS_CATEGORIES,
                        'order'  => 6,
                        'mobile' => false,
                    ),
				),
                'globalSearchAttributeNames' => array(
                    'name'
                )
            );
            return $metadata;
        }

        public static function getPrimaryModelName()
        {
            return 'Category';
        }

        public static function getSingularCamelCasedName()
        {
            return 'Category';
        }

        public static function getAccessRight()
        {
            return self::RIGHT_ACCESS_CATEGORIES;
        }

        public static function getCreateRight()
        {
            return self::RIGHT_CREATE_CATEGORIES;
        }

        public static function getDeleteRight()
        {
            return self::RIGHT_DELETE_CATEGORIES;
        }

        public static function getGlobalSearchFormClassName()
        {
            return 'CategoriesSearchForm';
        }

        protected static function getSingularModuleLabel($language)
        {
            return Zurmo::t('CategoriesModule', 'Category', array(), null, $language);
        }

        protected static function getPluralModuleLabel($language)
        {
            return Zurmo::t('CategoriesModule', 'Category', array(), null, $language);
        }

        public static function isAutomaticCodeDisabled()
        {
            $metadata = static::getMetadata();
            if (isset($metadata['global']['automaticDisable']))
            {
                return (bool) $metadata['global']['automaticDisable'];
            }
            return false;
        }

    }
?>