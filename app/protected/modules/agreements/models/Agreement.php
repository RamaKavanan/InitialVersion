<?php

    /**
     * Agreement model have the elements and its relation as well as rules.
     *
     * @author Ramachandran.K (ramakavanan@gmail.com)
     */
    class Agreement extends OwnedSecurableItem implements StarredInterface
    {
        public static function getByName($name)
        {
            return self::getByNameOrEquivalent('name', $name);
        }

        public function __toString()
        {
            try
            {
                if (trim($this->name) == '')
                {
                    return Zurmo::t('Core', '(Unnamed)');
                }
                return $this->name;
            }
            catch (AccessDeniedSecurityException $e)
            {
                return '';
            }
        }

        public static function getModuleClassName()
        {
            return 'AgreementsModule';
        }

        public static function translatedAttributeLabels($language)
        {
            $params = LabelUtil::getTranslationParamsForAllModules();
            return array_merge(parent::translatedAttributeLabels($language), array(
                'account'     => Zurmo::t('AccountsModule',      'AccountsModuleSingularLabel', $params, null, $language),
                'meetings'    => Zurmo::t('MeetingsModule',      'MeetingsModulePluralLabel', $params, null, $language),
                'notes'       => Zurmo::t('NotesModule',         'NotesModulePluralLabel', $params, null, $language),
                'tasks'       => Zurmo::t('TasksModule',         'TasksModulePluralLabel', $params, null, $language),
		'ActivatedBy'		=> Zurmo::t('AgreementsModule', 'Activated By',  $params, null, $language),
			'CompanySigned'		=> Zurmo::t('AgreementsModule', 'Company Signed By',  $params, null, $language),
			'CustomerSigned'	=> Zurmo::t('AgreementsModule', 'Customer Signed By',  $params, null, $language),
			'Account_Manager'	=> Zurmo::t('AgreementsModule', 'Account Project Manager',  $params, null, $language),
			'Estimator'		=> Zurmo::t('AgreementsModule', 'Estimator',  $params, null, $language),
			'Initial_Sales_Rep'	=> Zurmo::t('AgreementsModule', 'Initial Sales Representive',  $params, null, $language),
			'BillingAddress'	=> Zurmo::t('AgreementsModule', 'Billing Address',  $params, null, $language),
			'ShippingAddress'	=> Zurmo::t('AgreementsModule', 'Shipping Address',  $params, null, $language),
                    	'ActivatedDate'		=> Zurmo::t('AgreementsModule', 'Activated Date',  $params, null, $language),
			'CompanySignedDate'	=> Zurmo::t('AgreementsModule', 'Company Signed Date',  $params, null, $language),
			'EndDate'		=> Zurmo::t('AgreementsModule', 'Agreement End Date',  $params, null, $language),
			'name'			=> Zurmo::t('AgreementsModule', 'Agreement Name',  $params, null, $language),
			'RecordType'		=> Zurmo::t('AgreementsModule', 'Agreement Record Type',  $params, null, $language),
			'StartDate'		=> Zurmo::t('AgreementsModule', 'Agreement Start Date',  $params, null, $language),
			'ContractTerm'		=> Zurmo::t('AgreementsModule', 'Agreement Term (Months)',  $params, null, $language),
			'CustomerSignedDate'	=> Zurmo::t('AgreementsModule', 'Customer Signed Date',  $params, null, $language),
			'CustomerSignedTitle'	=> Zurmo::t('AgreementsModule', 'Customer Signed Title',  $params, null, $language),
			'Description'		=> Zurmo::t('AgreementsModule', 'Description',  $params, null, $language),
			'OwnerExpirationNotice'	=> Zurmo::t('AgreementsModule', 'Owner Expiration Notice',  $params, null, $language),
			'Pricebook2'		=> Zurmo::t('AgreementsModule', 'Price Book',  $params, null, $language),
			'SpecialTerms'		=> Zurmo::t('AgreementsModule', 'Special Terms',  $params, null, $language),
			'Status'		=> Zurmo::t('AgreementsModule', 'Status',  $params, null, $language),
			'Proposal_OID'		=> Zurmo::t('AgreementsModule', '(Do Not Edit This) ',  $params, null, $language),
			'Agreement_Temp_ID'	=> Zurmo::t('AgreementsModule', '(For Internal Use Only)',  $params, null, $language),
			'Used_MHR'		=> Zurmo::t('AgreementsModule', '% Used MHR',  $params, null, $language),
			'Agreement_Type'	=> Zurmo::t('AgreementsModule', 'Agreement Type',  $params, null, $language),
			'Anticipated_Start_Date'	=> Zurmo::t('AgreementsModule', 'Anticipated Start Date',  $params, null, $language),
			'Clone_Approval'	=> Zurmo::t('AgreementsModule', 'Clone Approval',  $params, null, $language),
			'Cloned_From'		=> Zurmo::t('AgreementsModule', 'Cloned From',  $params, null, $language),
			'Clone_Process'		=> Zurmo::t('AgreementsModule', 'Clone Process',  $params, null, $language),
			'Clone_Process_Email'	=> Zurmo::t('AgreementsModule', 'Clone Process Email',  $params, null, $language),
			'Contract_Number'	=> Zurmo::t('AgreementsModule', 'Agreement Number',  $params, null, $language),
			'Current_Annual_Amount'	=> Zurmo::t('AgreementsModule', 'Current Annual Amount',  $params, null, $language),
			'Current_GPM'		=> Zurmo::t('AgreementsModule', 'Current GPM',  $params, null, $language),
			'Customer_Signed_Value'	=> Zurmo::t('AgreementsModule', 'Customer Signed Value',  $params, null, $language),
			'Date_of_First_Service'	=> Zurmo::t('AgreementsModule', 'Date of First Service',  $params, null, $language),
			'Deactivate'		=> Zurmo::t('AgreementsModule', 'Deactivate',  $params, null, $language),
			'Deactivation_Date'	=> Zurmo::t('AgreementsModule', 'Deactivation Date',  $params, null, $language),
			'Estimator_Approval'	=> Zurmo::t('AgreementsModule', 'Estimator Approval',  $params, null, $language),
			'Estimator_Approval_Date'	=> Zurmo::t('AgreementsModule', 'Estimator Approval Date',  $params, null, $language),
			'Estimator_Auto_Approval'	=> Zurmo::t('AgreementsModule', 'Estimator Auto Approval ',  $params, null, $language),
			'Evergreen'		=> Zurmo::t('AgreementsModule', 'Evergreen',  $params, null, $language),
			'First_Year_Amount'	=> Zurmo::t('AgreementsModule', 'First Year Amount',  $params, null, $language),
			'GPM_Change'		=> Zurmo::t('AgreementsModule', 'GPM Change',  $params, null, $language),
			'Hours_Remaining_MHR'	=> Zurmo::t('AgreementsModule', 'Hours Remaining MHR',  $params, null, $language),
			'LO_AG_ID_Old'		=> Zurmo::t('AgreementsModule', 'LO AG ID Old',  $params, null, $language),
			'Management_Approval'	=> Zurmo::t('AgreementsModule', 'Management Approval',  $params, null, $language),
			'Management_Approval_Date'	=> Zurmo::t('AgreementsModule', 'Management Approval Date',  $params, null, $language),
			'Old_Agreement_ID'	=> Zurmo::t('AgreementsModule', 'Old Agreement ID',  $params, null, $language),
			'Old_Agreement_Number'	=> Zurmo::t('AgreementsModule', 'Old Agreement Number',  $params, null, $language),
			'Previous_Amount'	=> Zurmo::t('AgreementsModule', 'Previous Amount',  $params, null, $language),
			'Previous_GPM'		=> Zurmo::t('AgreementsModule', 'Previous GPM',  $params, null, $language),
			'Price_Change'		=> Zurmo::t('AgreementsModule', 'Price Change',  $params, null, $language),
			'Project_Agreement_Amount'	=> Zurmo::t('AgreementsModule', 'Project Agreement Amount',  $params, null, $language),
			'Proposed_Gross_Profit_Margin'	=> Zurmo::t('AgreementsModule', 'Proposed Gross Profit Margin',  $params, null, $language),
			'Agreement_Expiration'	=> Zurmo::t('AgreementsModule', 'Renewal Date',  $params, null, $language),
			'Role_Match'		=> Zurmo::t('AgreementsModule', 'Role Match',  $params, null, $language),
			'Sales_Rep'		=> Zurmo::t('AgreementsModule', 'Sales Rep',  $params, null, $language),
			'Set_Owner_to_Creator'	=> Zurmo::t('AgreementsModule', 'Set Owner to Creator',  $params, null, $language),
			'Total_Agreement_Products'	=> Zurmo::t('AgreementsModule', 'Total Agreement Products',  $params, null, $language),
			'Total_Available_MHR'	=> Zurmo::t('AgreementsModule', 'Total Available MHR',  $params, null, $language),
			'Total_Direct_Costs'	=> Zurmo::t('AgreementsModule', 'Total Direct Costs',  $params, null, $language),
			'Total_Non_Agreement_Products'	=> Zurmo::t('AgreementsModule', 'Total Non-Agreement Products',  $params, null, $language),
			'Total_Products_Tracked'	=> Zurmo::t('AgreementsModule', 'Total Products Tracked',  $params, null, $language),
			'URL_Host'		=> Zurmo::t('AgreementsModule', 'URL Host',  $params, null, $language),
			'URL_Name'		=> Zurmo::t('AgreementsModule', 'URL Name',  $params, null, $language),
			'Website'		=> Zurmo::t('AgreementsModule', 'Website',  $params, null, $language),
			'XREF'			=> Zurmo::t('AgreementsModule', 'XREF',  $params, null, $language),
			'Year_to_Date_MHR'	=> Zurmo::t('AgreementsModule', 'Year to Date MHR',  $params, null, $language),
		));
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
			'ActivatedDate',
			'CompanySignedDate',
			'EndDate',
			'RecordType',
			'StartDate',
			'ContractTerm',
			'CustomerSignedDate',
			'CustomerSignedTitle',
			'Description',
			'Pricebook2',
			'SpecialTerms',
			'Proposal_OID',
			'Agreement_Temp_ID',
			'Used_MHR',
			'Anticipated_Start_Date',
			'Clone_Approval',
			'Clone_Process',
			'Contract_Number',
			'Current_GPM',
			'Date_of_First_Service',
			'Deactivate',
			'Deactivation_Date',
			'Estimator_Approval',
			'Estimator_Approval_Date',
			'Estimator_Auto_Approval',
			'Evergreen',
			'GPM_Change',
			'Hours_Remaining_MHR',
			'LO_AG_ID_Old',
			'Management_Approval',
			'Management_Approval_Date',
			'Old_Agreement_ID',
			'Old_Agreement_Number',
			'Previous_GPM',
			'Proposed_Gross_Profit_Margin',
			'Agreement_Expiration',
			'Role_Match',
			'Set_Owner_to_Creator',
			'Total_Agreement_Products',
			'Total_Available_MHR',
			'Total_Direct_Costs',
			'Total_Non_Agreement_Products',
			'Total_Products_Tracked',
			'URL_Host',
			'URL_Name',
			'Website',
			'XREF',
			'Year_to_Date_MHR',
                ),
                'relations' => array(
                    'account'             => array(RedBeanModel::HAS_ONE,   'Account'),
			'ActivatedBy'  => array(static::HAS_ONE,  'User', static::NOT_OWNED,static::LINK_TYPE_SPECIFIC, 'ActivatedBy'),
                        'agreementProduct'             => array(RedBeanModel::HAS_MANY,   'AgreementProduct'),
			'BillingAddress' => array(RedBeanModel::HAS_ONE,   'Address',          RedBeanModel::OWNED,
                                                RedBeanModel::LINK_TYPE_SPECIFIC, 'BillingAddress'),
			//'CompanySigned'  => array(static::HAS_ONE,  'User', static::NOT_OWNED,
                          //           static::LINK_TYPE_SPECIFIC, 'CompanySigned'),
			'CompanySigned'  => array(static::HAS_ONE,  'User', static::NOT_OWNED,
                                     static::LINK_TYPE_SPECIFIC, 'CompanySigned'),
			'CustomerSigned'  => array(static::HAS_ONE,  'User', static::NOT_OWNED,
                                     static::LINK_TYPE_SPECIFIC, 'CustomerSigned'),
			'ShippingAddress' => array(RedBeanModel::HAS_ONE,   'Address',          RedBeanModel::OWNED,
                                                RedBeanModel::LINK_TYPE_SPECIFIC, 'ShippingAddress'),
			'Account_Manager'  => array(static::HAS_ONE,  'User', static::NOT_OWNED,
                                     static::LINK_TYPE_SPECIFIC, 'Account_Manager'),
			'Estimator'  => array(static::HAS_ONE,  'User', static::NOT_OWNED,
                                     static::LINK_TYPE_SPECIFIC, 'Estimator'),
			'Initial_Sales_Rep' => array(static::HAS_ONE,  'User', static::NOT_OWNED,
                                     static::LINK_TYPE_SPECIFIC, 'Initial_Sales_Rep'),
			'Sales_Rep' => array(static::HAS_ONE,  'User', static::NOT_OWNED,
                                     static::LINK_TYPE_SPECIFIC, 'Sales_Rep'),
			'OwnerExpirationNotice' => array(RedBeanModel::HAS_ONE,   'OwnedCustomField', RedBeanModel::OWNED,
                                                RedBeanModel::LINK_TYPE_SPECIFIC, 'OwnerExpirationNotice'),
			'Status' => array(RedBeanModel::HAS_ONE,   'OwnedCustomField', RedBeanModel::OWNED,
                                                RedBeanModel::LINK_TYPE_SPECIFIC, 'Status'),
			'Agreement_Type' => array(RedBeanModel::HAS_ONE,   'OwnedCustomField', RedBeanModel::OWNED,
                                                RedBeanModel::LINK_TYPE_SPECIFIC, 'Agreement_Type'),
			'Cloned_From'             => array(RedBeanModel::HAS_ONE,   'Agreement'),
			'Clone_Process_Email'   => array(RedBeanModel::HAS_ONE,   'Email',            RedBeanModel::OWNED,
                                                RedBeanModel::LINK_TYPE_SPECIFIC, 'Clone_Process_Email'),
			'Current_Annual_Amount'      => array(RedBeanModel::HAS_ONE,   'CurrencyValue', RedBeanModel::OWNED, 
						RedBeanModel::LINK_TYPE_SPECIFIC, 'Current_Annual_Amount'),
			'Customer_Signed_Value'      => array(RedBeanModel::HAS_ONE,   'CurrencyValue', RedBeanModel::OWNED,
						RedBeanModel::LINK_TYPE_SPECIFIC, 'Customer_Signed_Value'),
			'First_Year_Amount'      => array(RedBeanModel::HAS_ONE,   'CurrencyValue', RedBeanModel::OWNED,
						RedBeanModel::LINK_TYPE_SPECIFIC, 'First_Year_Amount'),
			'Previous_Amount'      => array(RedBeanModel::HAS_ONE,   'CurrencyValue', RedBeanModel::OWNED,
						RedBeanModel::LINK_TYPE_SPECIFIC, 'Previous_Amount'),
			'Price_Change'      => array(RedBeanModel::HAS_ONE,   'CurrencyValue', RedBeanModel::OWNED,
						RedBeanModel::LINK_TYPE_SPECIFIC, 'Price_Change'),
			'Project_Agreement_Amount'      => array(RedBeanModel::HAS_ONE,   'CurrencyValue', RedBeanModel::OWNED,
						RedBeanModel::LINK_TYPE_SPECIFIC, 'Project_Agreement_Amount'),
                ),
                'derivedRelationsViaCastedUpModel' => array(
                    'meetings' => array(static::MANY_MANY, 'Meeting', 'activityItems'),
                    'notes'    => array(static::MANY_MANY, 'Note',    'activityItems'),
                    'tasks'    => array(static::MANY_MANY, 'Task',    'activityItems'),
                ),
                'rules' => array(
                   	array('account',        'required'),
                	array('name',           'type',           'type'  => 'string'),
                    	array('name',           'length',         'max'   => 100),
			array('ActivatedDate', 'type', 'type' => 'datetime'),
			array('CompanySignedDate', 'type', 'type' => 'datetime'),
			array('EndDate', 'type', 'type' => 'datetime'),
			array('RecordType',           'type',           'type'  => 'string'),
                    	array('RecordType',           'length',         'max'   => 100),
			array('StartDate', 'type', 'type' => 'date'),
			//array('StartDate',        'required'),
			array('Status',        'required'),
			array('ContractTerm', 'type', 'type' => 'integer'),
			array('ContractTerm',        'required'),
			array('CustomerSignedDate', 'type', 'type' => 'datetime'),
			array('CustomerSignedTitle', 'type', 'type' => 'string'),
			array('Description',    'type',           'type'  => 'string'),
			array('Pricebook2',    'type',           'type'  => 'string'),
			array('SpecialTerms',    'type',           'type'  => 'string'),
			array('Proposal_OID',    'type',           'type'  => 'string'),
			array('Agreement_Temp_ID',    'type',           'type'  => 'string'),
			array('Used_MHR',   'length',         'max'   => 18),
                    	array('Used_MHR',   'numerical',      'precision' => 2),
                    	array('Used_MHR',   'type',           'type'   => 'float'),
			array('Anticipated_Start_Date',    'type',           'type'  => 'datetime'),
			array('Clone_Approval',    'type',           'type'  => 'boolean'),
			array('Clone_Process',    'type',           'type'  => 'string'),
			array('Contract_Number',    'type',           'type'  => 'string'),
			array('Contract_Number',    'length',         'max'   => 100),
			array('Current_GPM',        'required'),
			array('Current_GPM',    'type',           'type'  => 'float'),
			array('Current_GPM',   'length',         'max'   => 3),
			array('Current_GPM',   'numerical',      'precision' => 2),
			array('Project_Agreement_Amount',        'required'),
			array('Date_of_First_Service',    'type',           'type'  => 'date'),
			array('Deactivate',    'type',           'type'  => 'boolean'),
			array('Deactivation_Date',    'type',           'type'  => 'date'),
			array('Estimator_Approval',    'type',           'type'  => 'boolean'),
			array('Estimator_Approval_Date',    'type',           'type'  => 'datetime'),
			array('Estimator_Auto_Approval',    'type',           'type'  => 'boolean'),
			array('Evergreen',    'type',           'type'  => 'boolean'),
			array('GPM_Change',    'type',           'type'  => 'float'),
			array('GPM_Change',   'length',         'max'   => 3),
			array('GPM_Change',   'numerical',      'precision' => 2),
			array('Hours_Remaining_MHR',    'type',           'type'  => 'float'),
			array('Hours_Remaining_MHR',   'length',         'max'   => 18),
			array('Hours_Remaining_MHR',   'numerical',      'precision' => 2),
			array('LO_AG_ID_Old',    'type',           'type'  => 'string'),
			array('Management_Approval',    'type',           'type'  => 'boolean'),
			array('Management_Approval_Date',    'type',           'type'  => 'datetime'),
			array('Old_Agreement_ID',    'type',           'type'  => 'string'),
			array('Old_Agreement_Number',    'type',           'type'  => 'string'),
			array('Previous_GPM',    'type',           'type'  => 'float'),
			array('Previous_GPM',   'length',         'max'   => 3),
			array('Previous_GPM',   'numerical',      'precision' => 2),
			array('Proposed_Gross_Profit_Margin',    'type',           'type'  => 'float'),
			array('Proposed_Gross_Profit_Margin',   'length',         'max'   => 18),
			array('Proposed_Gross_Profit_Margin',   'numerical',      'precision' => 2),
			array('Agreement_Expiration',    'type',           'type'  => 'date'),
			array('Role_Match',    'type',           'type'  => 'float'),
			array('Set_Owner_to_Creator',    'type',           'type'  => 'boolean'),
			array('Total_Agreement_Products',    'type',           'type'  => 'float'),
			array('Total_Available_MHR',    'type',           'type'  => 'float'),
			array('Total_Direct_Costs',    'type',           'type'  => 'float'),
			array('Total_Non_Agreement_Products',    'type',           'type'  => 'float'),
			array('Total_Products_Tracked',    'type',           'type'  => 'float'),
			array('URL_Host',    'type',           'type'  => 'string'),
			array('URL_Name',    'type',           'type'  => 'string'),
			array('Website',     'url',     'defaultScheme' => 'http'),
			array('XREF',    'type',           'type'  => 'integer'),
			array('Year_to_Date_MHR',    'type',           'type'  => 'float'),
                ),
                'elements' => array(
                    'account'		=> 'Account',
			'ActivatedBy'		=> 'User',
			'CompanySigned'		=> 'User',
			'CustomerSigned'	=> 'User',
			'Account_Manager'	=> 'User',
			'Estimator'		=> 'User',
			'Initial_Sales_Rep'	=> 'User',
			'BillingAddress'	=> 'Address',
			'ShippingAddress'	=> 'Address',
			'name'			=> 'Text',
                    	'ActivatedDate'		=> 'DateTime',
			'CompanySignedDate'	=> 'DateTime',
			'EndDate'		=> 'DateTime',
			'RecordType'		=> 'Text',
			'StartDate'		=> 'Date',
			'ContractTerm'		=> 'Integer',
			'CustomerSignedDate'	=> 'DateTime',
			'CustomerSignedTitle'	=> 'Text',
			'Description'		=> 'TextArea',
			'Pricebook2'		=> 'Text',
			'SpecialTerms'		=> 'TextArea',
			'Proposal_OID'		=> 'Text',
			'Agreement_Temp_ID'	=> 'Text',
			'Used_MHR'		=> 'Decimal',
			'Anticipated_Start_Date'	=> 'DateTime',
			'Clone_Approval'	=> 'CheckBox',
			'Cloned_From'		=> 'Agreement',
			'Clone_Process'		=> 'Text',
			'Clone_Process_Email'	=> 'EmailAddressInformation',
			'Contract_Number'	=> 'Text',
			'Current_Annual_Amount'	=> 'CurrencyValue',
			'Current_GPM'		=> 'Decimal',
			'Customer_Signed_Value'	=> 'CurrencyValue',
			'Date_of_First_Service'	=> 'Date',
			'Deactivate'		=> 'CheckBox',
			'Deactivation_Date'	=> 'Date',
			'Estimator_Approval'	=> 'CheckBox',
			'Estimator_Approval_Date'	=> 'DateTime',
			'Estimator_Auto_Approval'	=> 'CheckBox',
			'Evergreen'		=> 'CheckBox',
			'First_Year_Amount'	=> 'CurrencyValue',
			'GPM_Change'		=> 'Decimal',
			'Hours_Remaining_MHR'	=> 'Decimal',
			'LO_AG_ID_Old'		=> 'Text',
			'Management_Approval'	=> 'CheckBox',
			'Management_Approval_Date'	=> 'DateTime',
			'Old_Agreement_ID'	=> 'Text',
			'Old_Agreement_Number'	=> 'Text',
			'Previous_Amount'	=> 'CurrencyValue',
			'Previous_GPM'		=> 'Decimal',
			'Price_Change'		=> 'CurrencyValue',
			'Project_Agreement_Amount'	=> 'CurrencyValue',
			'Proposed_Gross_Profit_Margin'	=> 'Decimal',
			'Agreement_Expiration'	=> 'Date',
			'Role_Match'		=> 'Decimal',
			'Sales_Rep'		=> 'User',
			'Set_Owner_to_Creator'	=> 'CheckBox',
			'Total_Agreement_Products'	=> 'Decimal',
			'Total_Available_MHR'	=> 'Decimal',
			'Total_Direct_Costs'	=> 'Decimal',
			'Total_Non_Agreement_Products'	=> 'Decimal',
			'Total_Products_Tracked'	=> 'Decimal',
			'URL_Host'		=> 'Text',
			'URL_Name'		=> 'Text',
			'XREF'			=> 'Integer',
			'Year_to_Date_MHR'	=> 'Decimal',
                ),
                'customFields' => array(
                    'OwnerExpirationNotice'   => 'OwnerExpirationNotices',
		    'Status'		      => 'AgreementStatus',
		    'Agreement_Type' 	      => 'AgreementTypes',
                ),
                'defaultSortAttribute' => 'name',
                'noAudit' => array(
                    'description'
                ),
            );
            return $metadata;
        }

        public static function isTypeDeletable()
        {
            return true;
        }

        public static function getRollUpRulesType()
        {
            return 'Agreement';
        }

        public static function hasReadPermissionsOptimization()
        {
            return true;
        }

		protected function afterSave()
        {
			$this->Contract_Number = 'Agmnt-'.$this->id;
			$this->save();
			parent::afterSave();

        }


    }
?>
