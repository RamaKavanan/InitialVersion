<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class DepartmentReferencesModule extends SecurableModule
    {
        const RIGHT_CREATE_DEPARTMENTREFERENCES = 'Create DepartmentReferences';
        const RIGHT_DELETE_DEPARTMENTREFERENCES = 'Delete DepartmentReferences';
        const RIGHT_ACCESS_DEPARTMENTREFERENCES = 'Access DepartmentReferences Tab';

        public function getDependencies()
        {
            return array(
                'configuration',
                'zurmo',
            );
        }

        public function getRootModelNames()
        {
            return array('DepartmentReference');
        }

        public static function getTranslatedRightsLabels()
        {
            $params                              = LabelUtil::getTranslationParamsForAllModules();
            $labels                              = array();
            $labels[self::RIGHT_CREATE_DEPARTMENTREFERENCES] = Zurmo::t('DepartmentReferencesModule', 'Create DepartmentReferencesModulePluralLabel',     $params);
            $labels[self::RIGHT_DELETE_DEPARTMENTREFERENCES] = Zurmo::t('DepartmentReferencesModule', 'Delete DepartmentReferencesModulePluralLabel',     $params);
            $labels[self::RIGHT_ACCESS_DEPARTMENTREFERENCES] = Zurmo::t('DepartmentReferencesModule', 'Access DepartmentReferencesModulePluralLabel Tab', $params);
            return $labels;
        }

        public static function getDefaultMetadata()
        {
            $metadata = array();
            $metadata['global'] = array(
                'tabMenuItems' => array(
                    array(
                        'label'  => "Department Reference",
                        'url'    => array('/departmentReferences/default'),
                        'right'  => self::RIGHT_ACCESS_DEPARTMENTREFERENCES,
                    ),
                ),
                'designerMenuItems' => array(
                    'showFieldsLink' => true,
                    'showGeneralLink' => true,
                    'showLayoutsLink' => true,
                    'showMenusLink' => true,
                ),
                'globalSearchAttributeNames' => array(
                    'name'
                )
            );
            return $metadata;
        }

        public static function getPrimaryModelName()
        {
            return 'DepartmentReference';
        }

        public static function getSingularCamelCasedName()
        {
            return 'DepartmentReference';
        }

        public static function getAccessRight()
        {
            return self::RIGHT_ACCESS_DEPARTMENTREFERENCES;
        }

        public static function getCreateRight()
        {
            return self::RIGHT_CREATE_DEPARTMENTREFERENCES;
        }

        public static function getDeleteRight()
        {
            return self::RIGHT_DELETE_DEPARTMENTREFERENCES;
        }

        public static function getGlobalSearchFormClassName()
        {
            return 'DepartmentReferencesSearchForm';
        }

        protected static function getSingularModuleLabel($language)
        {
            return Zurmo::t('DepartmentReferencesModule', 'DepartmentReference', array(), null, $language);
        }

        protected static function getPluralModuleLabel($language)
        {
            return Zurmo::t('DepartmentReferencesModule', 'DepartmentReference', array(), null, $language);
        }
    }
?>