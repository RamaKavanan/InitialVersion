<?php
    /**
     * A chart view for displaying a chart showing all agreements by source.
     *
     */
    class OpportunitiesBySourceChartView extends ChartView implements PortletViewInterface
    {
        public function renderContent()
        {
            $accessContent = $this->resolveContentIfCurrentUserCanAccessChartByModule(
                                        'OpportunitiesModule', 'OpportunitiesModulePluralLabel');
            if ($accessContent != null)
            {
                return $accessContent;
            }
            $chartDataProviderType = $this->getChartDataProviderType();
            $chartDataProvider     = ChartDataProviderFactory::createByType($chartDataProviderType);
            ControllerSecurityUtil::resolveCanCurrentUserAccessModule(
                                        $chartDataProvider->getModel()->getModuleClassName(), true);
            $chartData = $chartDataProvider->getChartData();
            Yii::import('ext.amcharts.AmChartMaker');
            $amChart = new AmChartMaker();
            $amChart->data = $chartData;
            $amChart->id =  $this->uniqueLayoutId;
            $amChart->type = $this->resolveViewAndMetadataValueByName('type');
            $amChart->addSerialGraph('value', 'column');
            $amChart->xAxisName        = $chartDataProvider->getXAxisName();
            $amChart->yAxisName        = $chartDataProvider->getYAxisName();
            $amChart->yAxisUnitContent = Yii::app()->locale->getCurrencySymbol(Yii::app()->currencyHelper->getCodeForCurrentUserForDisplay());
            $javascript = $amChart->javascriptChart();
            Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $this->uniqueLayoutId, $javascript);
            $cClipWidget = new CClipWidget();
            $cClipWidget->beginClip("Chart");
            $cClipWidget->widget('application.core.widgets.AmChart', array(
                    'id'        => $this->uniqueLayoutId,
            ));
            $cClipWidget->endClip();
            return $cClipWidget->getController()->clips['Chart'];
        }

        public function getPortletParams()
        {
            return array();
        }

        public function renderPortletHeadContent()
        {
            return null;
        }

        public static function getDefaultMetadata()
        {
            return array(
                'perUser' => array(
                    'title' => "eval:Zurmo::t('OpportunitiesModule', 'Opportunities By Lead Source', LabelUtil::getTranslationParamsForAllModules())",
                    'type'  => ChartRules::TYPE_PIE_3D,
                ),
                'global' => array(
                ),
            );
        }

        /**
         * What kind of PortletRules this view follows
         * @return PortletRulesType as string.
         */
        public static function getPortletRulesType()
        {
            return 'Chart';
        }

        /**
         * The view's module class name.
         */
        public static function getModuleClassName()
        {
            return 'OpportunitiesModule';
        }

        public function getChartDataProviderType()
        {
            return 'OpportunitiesBySource';
        }

        /**
         * Override to add a description for the view to be shown when adding a portlet
         */
        public static function getPortletDescription()
        {
        }
    }
?>
