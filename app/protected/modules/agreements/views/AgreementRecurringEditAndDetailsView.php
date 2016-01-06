<?php

/**
 * Render the create or edit agreeement for recurring type view.
 * 
 * @author Ramachandran.K (ramakavanan@gmail.com)
 */
 class AgreementRecurringEditAndDetailsView extends SecuredEditAndDetailsView
    {
        public static function getDefaultMetadata()
        {
            $metadata = array(
                'global' => array(
                    'toolbar' => array(
                        'elements' => array(
                            //array('type' => 'CancelLink', 'renderType' => 'Edit'),
                            array('type' => 'SaveButton', 'renderType' => 'Edit'),
							array('type' => 'CancelLink', 'renderType' => 'Edit'),
                            array('type' => 'ListLink',
                                'renderType' => 'Details',
                                'label' => "eval:Yii::t('Default', 'Return to List')"
                            ),
                            array('type' => 'EditLink', 'renderType' => 'Details'),
                            array('type' => 'AuditEventsModalListLink', 'renderType' => 'Details'),
				//array('type' => 'CopyLink',       'renderType' => 'Details'),
                        ),
                    ),
                    'derivedAttributeTypes' => array(
                        'DateTimeCreatedUser',
                        'DateTimeModifiedUser',
                    ),
                    'panelsDisplayType' => FormLayout::PANELS_DISPLAY_TYPE_ALL,
                    'panels' => array(
			    array(
				'title'=> 'Agreement Information',
				'rows' => array(
				    array('cells' =>
					array(
					    array(
						'detailViewOnly' => false,
						'elements' => array(
						    array('attributeName' => 'RecordType', 'type' => 'Text'),
						),
					    ),
					   array(
						'elements' => array(

						),
					    ),
					)
					
				    ),
				    array('cells' =>
					array(
					    array(
						'elements' => array(
						    array('attributeName' => 'account', 'type' => 'Account'),
						),
					    ),
					   array(
						'elements' => array(
						    array('attributeName' => 'Initial_Sales_Rep', 'type' => 'User'),
						),
					    ),
					),
					
				    ),
				array('cells' =>
					array(
					    array(
						'elements' => array(
						    array('attributeName' => 'name', 'type' => 'Text'),
						),
					    ),
					   array(
						'elements' => array(
						    array('attributeName' => 'Estimator_Approval_Date', 'type' => 'DateTime'),
						),
					    ),
					)
					
				    ),
				array('cells' =>
					array(
					    array(
						'elements' => array(
						    array('attributeName' => 'Status', 'type' => 'DropDown', 'addBlank' => true),
						),
					    ),
					   array(
						'elements' => array(
						  //    array('attributeName' => 'Agreement_Temp_ID', 'type' => 'Text'),
						),
					    ),
					)
					
				    ),
				array('cells' =>
					array(
					    array(
						'elements' => array(
						    array('attributeName' => 'Deactivation_Date', 'type' => 'Date'),
						),
					    ),
					   array(
						'elements' => array(
						   // array('attributeName' => 'OwnerExpirationNotice', 'type' => 'DropDown', 'addBlank' => true),
						),
					    ),
					)
					
				    ),

				array('cells' =>
					array(
					    array(
						'elements' => array(
						    array('attributeName' => 'Agreement_Type', 'type' => 'DropDown', 'addBlank' => true),
						),
					    ),
					   array(
						'elements' => array(
						   // array('attributeName' => 'Agreement_Temp_ID', 'type' => 'Text'),
						),
					    ),
					)
					
				    ),
				array('cells' =>
					array(
					    array(
						'elements' => array(
						    array('attributeName' => 'Estimator', 'type' => 'User'),
						),
					    ),
					   array(
						'elements' => array(
						    
						),
					    ),
					)
					
				    ),
				array('cells' =>
					array(
					    array(
						'elements' => array(
						    array('attributeName' => 'Estimator_Approval', 'type' => 'CheckBox'),
						),
					    ),
					   array(
						'elements' => array(
						   
						),
					    ),
					)
					
				    ),
				// it only for project view only
				/**array('cells' =>
					array(
					    array(
						'elements' => array(
						    array('attributeName' => 'Contract_Number', 'type' => 'Text'),
						),
					    ),
					   array(
						'elements' => array(
						    
						),
					    ),
					)
					
				    ),
				array('cells' =>
					array(
					    array(
						'elements' => array(
						    array('attributeName' => 'Account_Manager', 'type' => 'User'),
						),
					    ),
					   array(
						'elements' => array(
						    
						),
					    ),
					)
					
				    ),*/


				),
			    ),
			//Next Section with same panel
			array(
				'title'=> 'Description Information',
				'rows' => array(
				    array('cells' =>
					array(
					    array(
						'elements' => array(
						    array('attributeName' => 'Description', 'type' => 'TextArea'),
						),
					    ),
					   array(
						'elements' => array(
						   
						),
					    ),
					),
					
				    ),

				),
			    ),
			// Next Section
			array(
				'title'=> 'Agreement Information',
				'rows' => array(
				    array('cells' =>
					array(
					    array(
						'elements' => array(
						    array('attributeName' => 'Current_GPM', 'type' => 'Decimal'),
						),
					    ),
					   array(
						'elements' => array(
						   array('attributeName' => 'Total_Direct_Costs', 'type' => 'Decimal'),
						),
					    ),
					),
					
				    ),
				array('cells' =>
					array(
					    array(
						'elements' => array(
						    array('attributeName' => 'Current_Annual_Amount', 'type' => 'CurrencyValue'),
						),
					    ),
					   array(
						'elements' => array(
						   array('attributeName' => 'ContractTerm', 'type' => 'Integer'),
						),
					    ),
					),
					
				    ),
				array('cells' =>
					array(
					    array(
						'elements' => array(
						    array('attributeName' => 'First_Year_Amount', 'type' => 'CurrencyValue'),
						),
					    ),
					   array(
						'elements' => array(
						    array('attributeName' => 'StartDate', 'type' => 'Date'),
						),
					    ),
					)
					
				    ),
				array('cells' =>
					array(
					    array(
						'elements' => array(
						    array('attributeName' => 'SpecialTerms', 'type' => 'TextArea'),
						),
					    ),
					   array(
						'elements' => array(
						    array('attributeName' => 'Date_of_First_Service', 'type' => 'Date'),
						),
					    ),
					)
					
				    ),
				array('cells' =>
					array(
					    array(
						'elements' => array(
						 
						),
					    ),
					   array(
						'elements' => array(
						    array('attributeName' => 'Agreement_Expiration', 'type' => 'Date'),
						),
					    ),
					)
					
				    ),
				),
			    ),
			// Next Section
			array(
				'title'=> 'Signature Information',
				'rows' => array(
				    array('cells' =>
					array(
					    array(
						'elements' => array(
						    array('attributeName' => 'CustomerSigned', 'type' => 'User'),
						),
					    ),
					   array(
						'elements' => array(
						   array('attributeName' => 'CompanySigned', 'type' => 'User'),
						),
					    ),
					),
					
				    ),
				array('cells' =>
					array(
					    array(
						'elements' => array(
						//    array('attributeName' => 'CustomerSignedTitle', 'type' => 'Text'),
						),
					    ),
					   array(
						'elements' => array(
						    array('attributeName' => 'CompanySignedDate', 'type' => 'DateTime'),
						),
					    ),
					)
					
				    ),
				array('cells' =>
					array(
					    array(
						'elements' => array(
						    array('attributeName' => 'CustomerSignedDate', 'type' => 'DateTime'),
						),
					    ),
					   array(
						'elements' => array(
						   
						),
					    ),
					)
					
				    ),
				),
			    ),
			// Next Section
			array(
				'title'=> 'Address Information',
				'rows' => array(
				    array('cells' =>
					array(
					    array(
						'elements' => array(
						    array('attributeName' => 'BillingAddress', 'type' => 'Address'),
						),
					    ),
					   array(
						'elements' => array(
						   array('attributeName' => 'ShippingAddress', 'type' => 'Address'),
						),
					    ),
					),
					
				    )
				),
			    ),

			),
                ),
            );
            return $metadata;
        }

        protected function getNewModelTitleLabel()
        {
            return Yii::t('Default', 'Create AgreementsModuleSingularLabel',
                                     LabelUtil::getTranslationParamsForAllModules());
        }
    }
?>