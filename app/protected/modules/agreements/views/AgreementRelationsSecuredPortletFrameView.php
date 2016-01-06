<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AgreementRelationsSecuredPortletFrameView
 *
 * @author ideas2it
 */
class AgreementRelationsSecuredPortletFrameView extends ModelRelationsSecuredPortletFrameView{
    
    private $showAsTabs;
    
    public function __construct($controllerId, $moduleId, $uniqueLayoutId, $params, $metadata, $portletsAreCollapsible = true, $portletsAreMovable = true, $showAsTabs = false, $layoutType = '100', $portletsAreRemovable = true) {        
        parent::__construct($controllerId, $moduleId, $uniqueLayoutId, $params, $metadata, $portletsAreCollapsible, $portletsAreMovable, $showAsTabs, $layoutType, $portletsAreRemovable);
    }

    //put your code here
    protected function renderPortlets($uniqueLayoutId, $portletsAreCollapsible = true, $portletsAreMovable = true, $portletsAreRemovable = true) {
        if (!$this->showAsTabs)
            {
                return $this->renderPortletHelper($uniqueLayoutId, $portletsAreCollapsible, $portletsAreMovable, $portletsAreRemovable);
            }
            assert('is_bool($portletsAreCollapsible) && $portletsAreCollapsible == false');
            assert('is_bool($portletsAreMovable) && $portletsAreMovable == false');
            return $this->renderPortletsTabbed();
    }
    protected function renderPortletsTabbed()
        {
            assert('count($this->portlets) == 1 || count($this->portlets) == 0');
            if (count($this->portlets) == 1)
            {
                $tabItems = array();
                foreach ($this->portlets[1] as $noteUsed => $portlet)
                {
                    $tabItems[$portlet->getTitle()] = array(
                        'id'      => $portlet->getUniquePortletPageId(),
                        'content' => $portlet->renderContent()
                    );
                }
                $cClipWidget = new CClipWidget();
                $cClipWidget->beginClip("JuiTabs");
                $cClipWidget->widget('zii.widgets.jui.CJuiTabs', array(
                    'id' => $this->uniqueLayoutId . '-portlet-tabs',
                    'tabs' => $tabItems
                ));
                $cClipWidget->endClip();
                return $cClipWidget->getController()->clips['JuiTabs'];
            }
        }
        protected function renderPortletHelper($uniqueLayoutId, $portletsAreCollapsible = true, $portletsAreMovable = true, $portletsAreRemovable = true){
            assert('is_string($uniqueLayoutId)');
            assert('is_bool($portletsAreCollapsible)');
            assert('is_bool($portletsAreMovable)');
            assert('is_bool($portletsAreRemovable)');
            $juiPortletsWidgetItems = array();
            foreach ($this->portlets as $column => $columnPortlets)
            {
                foreach ($columnPortlets as $position => $portlet)
                {
                    $className = get_class($portlet->getView());
                    if (method_exists($className, 'canUserRemove'))
                    {
                        $removable      = $className::canUserRemove();
                    }
                    else
                    {
                        $removable      = $portletsAreRemovable;
                    }
                    $additionalOptionMenuItems = array();
                    if (method_exists($className, 'getAdditionalOptionMenuItems'))
                    {
                        $additionalOptionMenuItems = $className::getAdditionalOptionMenuItems();
                    }
                    if($className == 'AgreementDetailsPortletView') {
                        $juiPortletsWidgetItems[$column][$position] = array(
                          'id'                        => $portlet->id,
                          'uniqueId'                  => $portlet->getUniquePortletPageId(),
                          'title'                     => $portlet->getTitle(),
                          'content'                   => $this::renderViewForAgreementWithAgmntProduct($portlet),////$portlet->renderContent(),
                          'headContent'               => $portlet->renderHeadContent(),
                          'editable'                  => $portlet->isEditable(),
                          'collapsed'                 => $portlet->collapsed,
                          'removable'                 => $removable,
                          'uniqueClass'               => $this->resolveUniqueClass($portlet),
                          'portletParams'             => $portlet->getPortletParams(),
                          'additionalOptionMenuItems' => $additionalOptionMenuItems,
                      );  
                    }
                    else{
                        $juiPortletsWidgetItems[$column][$position] = array(
                            'id'                        => $portlet->id,
                            'uniqueId'                  => $portlet->getUniquePortletPageId(),
                            'title'                     => $portlet->getTitle(),
                            'content'                   => $portlet->renderContent(),
                            'headContent'               => $portlet->renderHeadContent(),
                            'editable'                  => $portlet->isEditable(),
                            'collapsed'                 => $portlet->collapsed,
                            'removable'                 => $removable,
                            'uniqueClass'               => $this->resolveUniqueClass($portlet),
                            'portletParams'             => $portlet->getPortletParams(),
                            'additionalOptionMenuItems' => $additionalOptionMenuItems,
                        );
                    }
                                        
                }
            }   
            $cClipWidget = new CClipWidget();
            $cClipWidget->beginClip("JuiPortlets");
            $cClipWidget->widget('application.core.widgets.JuiPortlets', array(
                'uniqueLayoutId' => $uniqueLayoutId,
                'moduleId'       => $this->moduleId,
                'saveUrl'        => Yii::app()->createUrl($this->moduleId . '/defaultPortlet/SaveLayout'),
                'layoutType'     => $this->getLayoutType(),
                'items'          => $juiPortletsWidgetItems,
                'collapsible'    => $portletsAreCollapsible,
                'movable'        => $portletsAreMovable,
            ));
            $cClipWidget->endClip();
            return $cClipWidget->getController()->clips['JuiPortlets'];
        }
        protected function renderViewForAgreementWithAgmntProduct(Portlet $portlet) {
            if($this->params['relationModel']->modelClassNameToBean['Agreement']->id != null) {
                $id = $this->params['relationModel']->modelClassNameToBean['Agreement']->id;
                $data = $this::getAllAgreementProducts($this->params['relationModel']->modelClassNameToBean['Agreement']->id);
                if(count($data) > 0) {
                    $content = $portlet->renderContent();  
                    $tableCreation = '<div class="view-toolbar-container clearfix"><div class="panel"><div class="panelTitle">Agreement Products</div>';
                    //$tableCreation .= '<div><table style="padding-left: 3%; text-align: right; vertical-align: top;" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="pbTitle">&nbsp;</td><td id="thePage:theTable:theSelectedBlock:j_id3" class="pbButton "><input type="submit" name="thePage:theTable:theSelectedBlock:j_id3:j_id5" value="Estimator Summary" onclick="top.location.replace(\'/apex/OpportunitProductsSummary?id=006j000000Ljedx\');return false;" class="btn"></td></tr></tbody></table></div>';
                    //$tableCreation .= '<div class="form-toolbar clearfix"><a id="addProduct" name="Add Products" class="attachLoading z-button" href="/app/index.php/agreementProducts/default/AddProductsInAgreement?optId='.$id.'"><span class="z-spinner"></span><span class="z-icon"></span><span class="z-label">Add Product</span></a><div class="post-to-profile clearfix"> <a id="estimateSummary" name="Estimate Summary" class="attachLoading z-button" onclick="/" href="#"><span class="z-spinner"></span><span class="z-icon"></span><span class="z-label">Estimate Summary</span></a></div></div>';
                    
                    $tableCreation .= '<table style="padding-left: 3%; text-align: right; vertical-align: top;"  border="0" cellpadding="2" cellspacing="0" width="100%">
                        <colgroup span="5"></colgroup>';
                   
                    $tableCreation .= '<thead style="font-weight: bold; background-color:#E6E6E6; color: #999; vertical-align: inherit; padding: 5px;"><th style="font-weight: bold;">Product Code</th><th style="font-weight: bold;">Product</th><th style="font-weight: bold;">Quantity</th><th style="font-weight: bold;">Frequency</th><th style="font-weight: bold;">Unit of Measure</th></thead><tbody>';
                    foreach($data as $row) {
                        $tableCreation .= '<tr><td style="width: 20%;  padding-top: 2px; text-align: left;">'.$row['name'].'</td><td style="width: 40%;  padding-top: 2px; text-align: left;">'.$row['name'].'</td><td style="width: 13%;  padding-top: 2px; text-align: center;">'.$row['quantity'].'</td><td style="width: 13%;  padding-top: 2px; text-align: center;">'.$row['frequency'].'</td><td style="width: 14%;  padding-top: 2px; text-align: center;">'.$row['total_mhr'].'</td></tr>';
                    }
                    $tableCreation .= '</tbody></table></div></div>';
                    $content .= $tableCreation;
                    return $content;
                } else {
                    return $portlet->renderContent();
                }
            } else {
                return $portlet->renderContent();
            }
        }
        protected  function getAllAgreementProducts($id) {
            $mysql = 'SELECT * FROM agreementproduct WHERE agreement_id =\''.intval($id).'\''; 
            $rows            = ZurmoRedBean::getAll($mysql);
            return $rows;
        }
}
