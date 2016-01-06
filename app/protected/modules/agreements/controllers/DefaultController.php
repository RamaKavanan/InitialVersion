<?php
    
    /**
     * Agreement controller to control the route functionality
     *
     * @author Ramachandran.K (ramakavanan@gmail.com)
     */
    class AgreementsDefaultController extends ZurmoModuleController   {
        /**public function filters()
        {
            $modelClassName   = $this->getModule()->getPrimaryModelName();
            $viewClassName    = $modelClassName . 'EditAndDetailsView';
            return array_merge(parent::filters(),
                array(
                    array(
                        ZurmoBaseController::REQUIRED_ATTRIBUTES_FILTER_PATH . ' + create, createFromRelation, edit',
                        'moduleClassName' => get_class($this->getModule()),
                        'viewClassName'   => $viewClassName,
                   ),
                    array(
                        ZurmoModuleController::ZERO_MODELS_CHECK_FILTER_PATH . ' + list, index',
                        'controller' => $this,
                   ),
               )
            );
        }*/

        public function actionList()
        {
            $pageSize    = Yii::app()->pagination->resolveActiveForCurrentUserByType(
                           'listPageSize', get_class($this->getModule()));
            $agreement = new Agreement(false);
            $searchForm  = new AgreementsSearchForm($agreement);
            $listAttributesSelector = new ListAttributesSelector('AgreementsListView', get_class($this->getModule()));
            $searchForm->setListAttributesSelector($listAttributesSelector);
            $dataProvider = $this->resolveSearchDataProvider(
                $searchForm,
                $pageSize,
                null,
                'AgreementsSearchView'
            );
            if (isset($_GET['ajax']) && $_GET['ajax'] == 'list-view')
            {
                $mixedView = $this->makeListView(
                    $searchForm,
                    $dataProvider
                );
                $view = new AgreementsPageView($mixedView);
            }
            else
            {
                $activeActionElementType = $this->resolveActiveElementTypeForKanbanBoard($searchForm);
                $mixedView = $this->makeActionBarSearchAndListView($searchForm, $dataProvider,
                             'AgreementsSecuredActionBarForSearchAndListView', null, $activeActionElementType);
		//$mixedView = $this->makeActionBarSearchAndListView($searchForm, $dataProvider);
                $view      = new AgreementsPageView(ZurmoDefaultViewUtil::
                             makeStandardViewForCurrentUser($this, $mixedView));
            }
            echo $view->render();
        }

        public function actionDetails($id, $kanbanBoard = false)
        {
            $agmnt = static::getModelAndCatchNotFoundAndDisplayError('Agreement', intval($id));
            ControllerSecurityUtil::resolveAccessCanCurrentUserReadModel($agmnt);
            AuditEvent::logAuditEvent('ZurmoModule', ZurmoModule::AUDIT_EVENT_ITEM_VIEWED, array(strval($agmnt), 'AgreementsModule'), $agmnt);
            if (KanbanUtil::isKanbanRequest() === false)
            {
                $breadCrumbView          = StickySearchUtil::resolveBreadCrumbViewForDetailsControllerAction($this, 'AgreementsSearchView', $agmnt);
                $detailsAndRelationsView = $this->makeDetailsAndRelationsView($agmnt, 'AgreementsModule',
                                                                          'AgreementDetailsAndRelationsView',
                                                                          Yii::app()->request->getRequestUri(), $breadCrumbView);
                    $view = new AgreementsPageView(ZurmoDefaultViewUtil::
                                             makeStandardViewForCurrentUser($this, $detailsAndRelationsView));
            }
            else
            {
                $view = TasksUtil::resolveTaskKanbanViewForRelation($agmnt, $this->getModule()->getId(), $this,
                                                                        'TasksForAgreementKanbanView', 'AgreementsPageView');
            }
            echo $view->render();
        }

        public function actionCreate()
        {
            $this->actionCreateByModel(new Agreement());
        }

        public function actionCreateFromRelation($relationAttributeName, $relationModelId, $relationModuleId, $redirectUrl)
        {
            $agmnt = $this->resolveNewModelByRelationInformation( new Agreement(),
                                                                                $relationAttributeName,
                                                                                (int)$relationModelId,
                                                                                $relationModuleId);
            $this->actionCreateByModel($agmnt, $redirectUrl);
        }

        protected function actionCreateByModel(Agreement $agmnt, $redirectUrl = null)
        {
            $agmtRecordView = new AgmntRecordType($this, NULL );
            $view =  new AgrmntRecordModelView(ZurmoDefaultViewUtil::makeStandardViewForCurrentUser($this, $agmtRecordView));
            echo $view->render();
        }

	public function actionProjectType($redirectUrl = null) {
            	Yii::app()->clientScript->registerScript('some-name',
                    '$(\'select[id$="_currency_id"]\').each(function() {
                        $(this).parent().parent().replaceWith($("<div>$</div>").toggleClass( "replaceDoller" ));
                        });');
                $agmnt = new Agreement();
		$agmnt->RecordType = 'Project Agreement';
		$agmnt->ContractTerm = 0;
		$titleBarAndEditView = new AgreementProjectEditAndDetailsView('Edit', $this->getId(), 
					$this->getModule()->getId(), $this->attemptToSaveModelFromPost($agmnt, $redirectUrl));
           	$view = new AgreementsPageView(ZurmoDefaultViewUtil::
                                        makeStandardViewForCurrentUser($this, $titleBarAndEditView));
		echo $view->render();
	}
	
	public function actionRecurringType($redirectUrl = null) {
            	Yii::app()->clientScript->registerScript('some-name',
                    '$(\'select[id$="_currency_id"]\').each(function() {
                        $(this).parent().parent().replaceWith($("<div>$</div>").toggleClass( "replaceDoller" ));
                        });');
                $agmnt = new Agreement();
		$agmnt->RecordType = 'Recurring Agreement';
		$currencies                       = Currency::getAll();
		$projectAmount                    = new CurrencyValue();
		$projectAmount->value             = 0.0;
		$projectAmount->currency          = $currencies[0];
		$agmnt->Project_Agreement_Amount = $projectAmount;
		$titleBarAndEditView = new AgreementRecurringEditAndDetailsView('Edit', $this->getId(), 
					$this->getModule()->getId(), $this->attemptToSaveModelFromPost($agmnt, $redirectUrl));
           	$view = new AgreementsPageView(ZurmoDefaultViewUtil::
                                        makeStandardViewForCurrentUser($this, $titleBarAndEditView));
		echo $view->render();
	}

        public function actionEdit($id, $redirectUrl = null)
        {
            Yii::app()->clientScript->registerScript('some-name',
                '$(\'select[id$="_currency_id"]\').each(function() {
                    $(this).parent().parent().replaceWith($("<div>$</div>").toggleClass( "replaceDoller" ));
                      });');
            $agmnt = Agreement::getById(intval($id));
            ControllerSecurityUtil::resolveAccessCanCurrentUserWriteModel($agmnt);
            $this->processEdit($agmnt, $redirectUrl);
        }

        public function actionCopy($id)
        {
            $copyToagrmnt  = new Agreement();
            $postVariableName   = get_class($copyToagrmnt);
            if (!isset($_POST[$postVariableName]))
            {
                $agmnt    = Agreement::getById((int)$id);
		$copyToagrmnt->RecordType = $agmnt->RecordType;
		$copyToagrmnt->Project_Agreement_Amount = $agmnt->Project_Agreement_Amount;
		$copyToagrmnt->Current_GPM = $agmnt->Current_GPM;
		if($agmnt->ContractTerm > 0) {
			$copyToagrmnt->ContractTerm = $agmnt->ContractTerm;
		} else {
			$copyToagrmnt->ContractTerm = 0;
		}
		$copyToagrmnt->ContractTerm = $agmnt->ContractTerm;
		$copyToagrmnt->Status = $agmnt->Status;
		$copyToagrmnt->account = $agmnt->account;
                ControllerSecurityUtil::resolveAccessCanCurrentUserReadModel($agmnt);
                ZurmoCopyModelUtil::copy($agmnt, $copyToagrmnt);
		
            }
            $this->processEdit($copyToagrmnt);
        }

        protected function processEdit(Agreement $agmnt, $redirectUrl = null)
        {
	    if($agmnt->RecordType == 'Recurring Agreement' ) {
	     	$view = new AgreementsPageView(ZurmoDefaultViewUtil::
                                         makeStandardViewForCurrentUser($this,new AgreementRecurringEditAndDetailsView('Edit', $this->getId(), 
					$this->getModule()->getId(), $this->attemptToSaveModelFromPost($agmnt,  $redirectUrl))));
		echo $view->render();
	    } else {
		//$agmnt->ContractTerm = 0;
   	    	$view = new AgreementsPageView(ZurmoDefaultViewUtil::
                                         makeStandardViewForCurrentUser($this,new AgreementProjectEditAndDetailsView('Edit', $this->getId(), 
					$this->getModule()->getId(), $this->attemptToSaveModelFromPost($agmnt,  $redirectUrl))));
		echo $view->render();
	    }
        }

        /**
         * Action for displaying a mass edit form and also action when that form is first submitted.
         * When the form is submitted, in the event that the quantity of models to update is greater
         * than the pageSize, then once the pageSize quantity has been reached, the user will be
         * redirected to the makeMassEditProgressView.
         * In the mass edit progress view, a javascript refresh will take place that will call a refresh
         * action, usually massEditProgressSave.
         * If there is no need for a progress view, then a flash message will be added and the user will
         * be redirected to the list view for the model.  A flash message will appear providing information
         * on the updated records.
         * @see Controler->makeMassEditProgressView
         * @see Controller->processMassEdit
         * @see
         */
        public function actionMassEdit()
        {
            $pageSize = Yii::app()->pagination->resolveActiveForCurrentUserByType(
                            'massEditProgressPageSize');
            $agmnt = new Agreement(false);
            $activeAttributes = $this->resolveActiveAttributesFromMassEditPost();
            $dataProvider = $this->getDataProviderByResolvingSelectAllFromGet(
                new AgreementsSearchForm($agmnt),
                $pageSize,
                Yii::app()->user->userModel->id,
                null,
                'AgreementsSearchView');
            $selectedRecordCount = static::getSelectedRecordCountByResolvingSelectAllFromGet($dataProvider);
            $agmnt = $this->processMassEdit(
                $pageSize,
                $activeAttributes,
                $selectedRecordCount,
                'AgreementsPageView',
                $agmnt,
                AgreementsModule::getModuleLabelByTypeAndLanguage('Plural'),
                $dataProvider
            );
            $massEditView = $this->makeMassEditView(
                $agmnt,
                $activeAttributes,
                $selectedRecordCount,
               AgreementsModule::getModuleLabelByTypeAndLanguage('Plural')
            );
            $view = new AgreementsPageView(ZurmoDefaultViewUtil::
                                         makeStandardViewForCurrentUser($this, $massEditView));
            echo $view->render();
        }

        /**
         * Action called in the event that the mass edit quantity is larger than the pageSize.
         * This action is called after the pageSize quantity has been updated and continues to be
         * called until the mass edit action is complete.  For example, if there are 20 records to update
         * and the pageSize is 5, then this action will be called 3 times.  The first 5 are updated when
         * the actionMassEdit is called upon the initial form submission.
         */
        public function actionMassEditProgressSave()
        {
            $pageSize = Yii::app()->pagination->resolveActiveForCurrentUserByType(
                            'massEditProgressPageSize');
            $agmnt = new Agreement(false);
            $dataProvider = $this->getDataProviderByResolvingSelectAllFromGet(
                new AgreementsSearchForm($agmnt),
                $pageSize,
                Yii::app()->user->userModel->id,
                null,
                'AgreementsSearchView'
            );
            $this->processMassEditProgressSave(
                'Agreement',
                $pageSize,
                AgreementsModule::getModuleLabelByTypeAndLanguage('Plural'),
                $dataProvider
            );
        }

        /**
         * Action for displaying a mass delete form and also action when that form is first submitted.
         * When the form is submitted, in the event that the quantity of models to delete is greater
         * than the pageSize, then once the pageSize quantity has been reached, the user will be
         * redirected to the makeMassDeleteProgressView.
         * In the mass delete progress view, a javascript refresh will take place that will call a refresh
         * action, usually makeMassDeleteProgressView.
         * If there is no need for a progress view, then a flash message will be added and the user will
         * be redirected to the list view for the model.  A flash message will appear providing information
         * on the delete records.
         * @see Controler->makeMassDeleteProgressView
         * @see Controller->processMassDelete
         * @see
         */
        public function actionMassDelete()
        {
            $pageSize = Yii::app()->pagination->resolveActiveForCurrentUserByType(
                            'massDeleteProgressPageSize');
            $agmnt = new Agreement(false);

            $activeAttributes = $this->resolveActiveAttributesFromMassDeletePost();
            $dataProvider = $this->getDataProviderByResolvingSelectAllFromGet(
                new AgreementsSearchForm($agmnt),
                $pageSize,
                Yii::app()->user->userModel->id,
                null,
                'AgreementsSearchView'
            );
            $selectedRecordCount = static::getSelectedRecordCountByResolvingSelectAllFromGet($dataProvider);
            $agmnt = $this->processMassDelete(
                $pageSize,
                $activeAttributes,
                $selectedRecordCount,
                'AgreementsPageView',
                $agmnt,
                AgreementsModule::getModuleLabelByTypeAndLanguage('Plural'),
                $dataProvider
            );
            $massDeleteView = $this->makeMassDeleteView(
                $agmnt,
                $activeAttributes,
                $selectedRecordCount,
                AgreementsModule::getModuleLabelByTypeAndLanguage('Plural')
            );
            $view = new AgreementsPageView(ZurmoDefaultViewUtil::
                                         makeStandardViewForCurrentUser($this, $massDeleteView));
            echo $view->render();
        }

        /**
         * Action called in the event that the mass delete quantity is larger than the pageSize.
         * This action is called after the pageSize quantity has been delted and continues to be
         * called until the mass delete action is complete.  For example, if there are 20 records to delete
         * and the pageSize is 5, then this action will be called 3 times.  The first 5 are updated when
         * the actionMassDelete is called upon the initial form submission.
         */
        public function actionMassDeleteProgress()
        {
            $pageSize = Yii::app()->pagination->resolveActiveForCurrentUserByType(
                            'massDeleteProgressPageSize');
            $agmnt = new Agreement(false);
            $dataProvider = $this->getDataProviderByResolvingSelectAllFromGet(
                new AgreementsSearchForm($agmnt),
                $pageSize,
                Yii::app()->user->userModel->id,
                null,
                'AgreementsSearchView'
            );
            $this->processMassDeleteProgress(
                'Agreement',
                $pageSize,
                AgreementsModule::getModuleLabelByTypeAndLanguage('Plural'),
                $dataProvider
            );
        }

        public function actionModalList()
        {
            $modalListLinkProvider = new SelectFromRelatedEditModalListLinkProvider(
                                            $_GET['modalTransferInformation']['sourceIdFieldId'],
                                            $_GET['modalTransferInformation']['sourceNameFieldId'],
                                            $_GET['modalTransferInformation']['modalId']
            );
            echo ModalSearchListControllerUtil::setAjaxModeAndRenderModalSearchList($this, $modalListLinkProvider);
        }

        public function actionDelete($id)
        {
            $agmnt = Agreement::GetById(intval($id));
            $agmnt->delete();
            $this->redirect(array($this->getId() . '/index'));
        }

        /**
         * Override to provide an agreement specific label for the modal page title.
         * @see ZurmoModuleController::actionSelectFromRelatedList()
         */
        public function actionSelectFromRelatedList($portletId,
                                                    $uniqueLayoutId,
                                                    $relationAttributeName,
                                                    $relationModelId,
                                                    $relationModuleId,
                                                    $stateMetadataAdapterClassName = null)
        {
            parent::actionSelectFromRelatedList($portletId,
                                                    $uniqueLayoutId,
                                                    $relationAttributeName,
                                                    $relationModelId,
                                                    $relationModuleId);
        }

        protected static function getSearchFormClassName()
        {
            return 'AgreementsSearchForm';
        }

        public function actionExport()
        {
            $this->export('AgreementsSearchView');
        }
    }
?>