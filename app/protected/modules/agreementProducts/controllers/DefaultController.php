<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AgreementProductsDefaultController extends ZurmoModuleController {
    /**public function filters()  {
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
        
    public function actionList()  {
        $pageSize                       = Yii::app()->pagination->resolveActiveForCurrentUserByType(
             'listPageSize', get_class($this->getModule()));
        $agmntPrdct                         = new AgreementProduct(false);
        $searchForm                     = new AgreementProductsSearchForm($agmntPrdct);
        $dataProvider = $this->resolveSearchDataProvider(
              $searchForm,
              $pageSize,
              null,
              'AgreementProductsSearchView'
        );
        if (isset($_GET['ajax']) && $_GET['ajax'] == 'list-view')     {
                $mixedView = $this->makeListView(
                    $searchForm,
                    $dataProvider
                );
                $view = new AgreementProductsPageView($mixedView);
            }
            else
            {
                $mixedView = $this->makeActionBarSearchAndListView($searchForm, $dataProvider);
                $view = new AgreementProductsPageView(ZurmoDefaultViewUtil::
                    makeStandardViewForCurrentUser($this, $mixedView));
            }
            echo $view->render();
        }
        
        public function actionCreate() {
            $editAndDetailsView = $this->makeEditAndDetailsView(
                                            $this->attemptToSaveModelFromPost(new AgreementProduct()), 'Edit');
            $view = new AgreementProductsPageView(ZurmoDefaultViewUtil::
                                         makeStandardViewForCurrentUser($this, $editAndDetailsView));
            echo $view->render();
        }
        
        public function actionDetails($id) {
            $agmntPrdct = static::getModelAndCatchNotFoundAndDisplayError('AgreementProduct', intval($id));
            $breadCrumbView          = StickySearchUtil::resolveBreadCrumbViewForDetailsControllerAction($this, 'AgreementProductsSearchView', $agmntPrdct);
            ControllerSecurityUtil::resolveAccessCanCurrentUserReadModel($agmntPrdct);
            AuditEvent::logAuditEvent('ZurmoModule', ZurmoModule::AUDIT_EVENT_ITEM_VIEWED, array(strval($agmntPrdct), 'AgreementProductsModule'), $agmntPrdct);
            $titleBarAndEditView = $this->makeEditAndDetailsView($agmntPrdct, 'Details');
            $view = new AgreementProductsPageView(ZurmoDefaultViewUtil::
                                         makeStandardViewForCurrentUser($this, $titleBarAndEditView));
            echo $view->render();
        }
        
        public function actionMassDelete()  {
            $pageSize = Yii::app()->pagination->resolveActiveForCurrentUserByType(
                            'massDeleteProgressPageSize');
            $agmntPrdct = new AgreementProduct(false);

            $activeAttributes = $this->resolveActiveAttributesFromMassDeletePost();
            $dataProvider = $this->getDataProviderByResolvingSelectAllFromGet(
                new AgreementProductsSearchForm($agmntPrdct),
                $pageSize,
                Yii::app()->user->userModel->id,
                null,
                'AgreementProductsSearchView');
            $selectedRecordCount = $this->getSelectedRecordCountByResolvingSelectAllFromGet($dataProvider);
            $agmntPrdct = $this->processMassDelete(
                $pageSize,
                $activeAttributes,
                $selectedRecordCount,
                'AgreementProductsPageView',
                $agmntPrdct,
                AgreementProductsModule::getModuleLabelByTypeAndLanguage('Plural'),
                $dataProvider
            );
            $massDeleteView = $this->makeMassDeleteView(
                $agmntPrdct,
                $activeAttributes,
                $selectedRecordCount,
                AgreementProductsModule::getModuleLabelByTypeAndLanguage('Plural')
            );
            $view = new AgreementProductsPageView(ZurmoDefaultViewUtil::
                                         makeStandardViewForCurrentUser($this, $massDeleteView));
            echo $view->render();
        }
        
        public function actionExport(){
            $this->export('AgreementProductsSearchView');
        }

	public function actionModalList() {
            $modalListLinkProvider = new SelectFromRelatedEditModalListLinkProvider(
                                            $_GET['modalTransferInformation']['sourceIdFieldId'],
                                            $_GET['modalTransferInformation']['sourceNameFieldId']
            );
            echo ModalSearchListControllerUtil::setAjaxModeAndRenderModalSearchList($this, $modalListLinkProvider,
                                                Yii::t('Default', 'AgreementProductModuleSingularLabel Search',
                                                LabelUtil::getTranslationParamsForAllModules()));
        }
	
	//Implementation for creating Agreement product from agreement
	public function actionCreateFromRelation($relationAttributeName, $relationModelId, $relationModuleId, $redirectUrl)     {
            $agmntPrdct             = $this->resolveNewModelByRelationInformation( new AgreementProduct(),
                                                                                $relationAttributeName,
                                                                                (int)$relationModelId,
                                                                                $relationModuleId);
            $this->actionCreateByModel($agmntPrdct, $redirectUrl);
        }

	protected function actionCreateByModel(AgreementProduct $agmntPrdct, $redirectUrl = null)     {
            $titleBarAndEditView = $this->makeEditAndDetailsView(
                                            $this->attemptToSaveModelFromPost($agmntPrdct, $redirectUrl), 'Edit');
            $view = new AgreementProductsPageView(ZurmoDefaultViewUtil::
                                         makeStandardViewForCurrentUser($this, $titleBarAndEditView));
            echo $view->render();
        }

	public function actionEdit($id, $redirectUrl = null)   {
            $agmntPrdct = AgreementProduct::getById(intval($id));
            ControllerSecurityUtil::resolveAccessCanCurrentUserWriteModel($agmntPrdct);
            $this->processEdit($agmntPrdct, $redirectUrl);
        }
	
	protected function processEdit(AgreementProduct $agmntPrdct, $redirectUrl = null)   {
            $view    = new AgreementProductsPageView(ZurmoDefaultViewUtil::
                            makeStandardViewForCurrentUser($this,
                            $this->makeEditAndDetailsView(
                                $this->attemptToSaveModelFromPost($agmntPrdct, $redirectUrl), 'Edit')));
            echo $view->render();
        }

	public function actionDelete($id)     {
            $agmntPrdct = AgreementProduct::GetById(intval($id));
            ControllerSecurityUtil::resolveAccessCanCurrentUserDeleteModel($agmntPrdct);
            $agmntPrdct->delete();
            $this->redirect(array($this->getId() . '/index'));
        }

	//Override the parent class

	public function actionSelectFromRelatedList($portletId,
                                                    $uniqueLayoutId,
                                                    $relationAttributeName,
                                                    $relationModelId,
                                                    $relationModuleId,
                                                    $stateMetadataAdapterClassName = null)
        {
            $portlet               = Portlet::getById((int)$portletId);
            
            $modalListLinkProvider = new AgreementProductTemplateSelectFromRelatedListModalListLinkProvider(
                                            $relationAttributeName,
                                            (int)$relationModelId,
                                            $relationModuleId,
                                            $portlet->getUniquePortletPageId(),
                                            $uniqueLayoutId,
                                            (int)$portlet->id,
                                            $this->getModule()->getId()
            );
  
            echo ModalSearchListControllerUtil::
                 setAjaxModeAndRenderModalSearchList($this, $modalListLinkProvider, $stateMetadataAdapterClassName);
        }
        
        
        public function actionAddAgreementRelation($relationModuleId, $portletId, $uniqueLayoutId,
                            $id, $relationModelId, $relationAttributeName, $redirect) {
            $agmntProduct = AgreementProduct::GetById(intval($id));
            $agmnt = Agreement::GetById(intval($relationModelId));
            if($agmntProduct ->agreement  != $agmnt ) {
                $agmntProduct ->agreement  = $agmnt;
                $agmntProduct->save();
            }
            if((bool) $redirect){
                $isViewLocked = ZurmoDefaultViewUtil::getLockKeyForDetailsAndRelationsView('lockPortletsForDetailsAndRelationsView');
                $redirectUrl  = Yii::app()->createUrl('/' . $relationModuleId . '/default/details', array('id' => $relationModelId));
                $this->redirect(array('/' . $relationModuleId . '/defaultPortlet/modalRefresh',
                                        'portletId'            => $portletId,
                                        'uniqueLayoutId'       => $uniqueLayoutId,
                                        'redirectUrl'          => $redirectUrl,
                                        'portletParams'        => array(  'relationModuleId' => $relationModuleId,
                                                                          'relationModelId'  => $relationModelId),
                                        'portletsAreRemovable' => !$isViewLocked));
            }
        }
        
        public function actionAddProductsInAgreement($optId) {
            $data = Costbook::getAll();
            $agmntView = new AgreementView($data, $optId, NULL );
            $view =  new MyCustomView(ZurmoDefaultViewUtil::makeStandardViewForCurrentUser($this, $agmntView));
            echo $view->render();
        }
        
        public function actionAddAgreementProducts($ids, $data, $optId) {
            if($ids != null && $data != null) {
                $costbookDatas = explode(',', $data);
                foreach($costbookDatas as $costbookData) {
                    list($id, $quantity, $frequency) = explode(':', $costbookData);
                    $costbook = Costbook::getById($id);
                    AgreementProductUtils::addAgreementProductsCalculation($costbook,$quantity,$frequency,$optId);
                }
            }
           $this->redirect(array('/agreements/default/details?id='.$optId));
        }
}	
?>
