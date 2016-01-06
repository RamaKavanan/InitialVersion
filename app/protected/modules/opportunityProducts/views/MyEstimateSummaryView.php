<?php

    /**
     * The view that forms the basis of every page. It renders
     * the XHtml html, header, body, etc, and renders its contained
     * view within the body. After rending the page and before
     * returning it it validates the XHtml against the XHtml schema
     * and renders directly to the browser any errors it finds
     * before returning the rendered page to the caller.
     */
    class MyEstimateSummaryView extends View    {
        /**
         * Flags that the error handler was called.
         */
        public static $foundErrors = false;

        public static $xhtmlValidationErrors = array();

        private $containedView;

        /**
         * Constructs the page view specifying the view that it
         * will contain.
         */
        public function __construct(View $containedView)
        {
            $this->containedView = $containedView;
        }

        public function render()
        {
            if (SHOW_PERFORMANCE)
            {
                $startTime = microtime(true);
            }
            static::registerAllPagesScriptFiles();
            $content = $this->renderXHtmlStart()     .
                       $this->renderXHtmlHead()      .
                       $this->renderXHtmlBodyStart() .
                       parent::render()              .
                       $this->renderXHtmlBeforeBodyEnd() .
                       $this->renderXHtmlBodyEnd()   .
                       $this->renderXHtmlEnd();
            Yii::app()->getClientScript()->render($content);
            if (YII_DEBUG)
            {
                if (defined('XHTML_VALIDATION') && XHTML_VALIDATION)
                {
                    $this->validate($content);
                    if (!empty(self::$xhtmlValidationErrors))
                    {
                        foreach (self::$xhtmlValidationErrors as $error)
                        {
                            $content = $this->appendContentBeforeXHtmlBodyEndAndXHtmlEnd($content, $error);
                        }
                    }
                }
            }
            if (YII_DEBUG && Yii::app()->isApplicationInstalled())
            {
                $dbInfoHtml = '<span style="background-color: lightgreen; color: green">Database: \'' . Yii::app()->db->connectionString . '\', username: \'' . Yii::app()->db->username . '\'.</span><br />';
                $content = $this->appendContentBeforeXHtmlBodyEndAndXHtmlEnd($content, $dbInfoHtml);
            }

            return $content;


        }

        /**
         * Validates the page content against the XHTML schema
         * and writes the problems directly to output in bright
         * red on yellow. Is public for access by unit tests.
         */
        public static function validate($content)
        {
            $wrapper = '<span style="background-color: yellow; color: #c00000;"><b>{text}</b></span><br />';
            $valid = false;
            try
            {
                $xhtmlValidationErrors = W3CValidatorServiceUtil::validate($content);

                if (count($xhtmlValidationErrors))
                {
                    foreach ($xhtmlValidationErrors as $xhtmlValidationError)
                    {
                        self::$xhtmlValidationErrors[] = str_replace('{text}', $xhtmlValidationError, $wrapper);
                    }
                }
                else
                {
                    $valid = true;
                }

                if (!count(self::$xhtmlValidationErrors))
                {
                    $valid = true;
                }
            }
            catch (Exception $e)
            {
                self::$xhtmlValidationErrors[] = str_replace('{text}', 'Error accessing W3C validation service.', $wrapper);
                self::$xhtmlValidationErrors[] = str_replace('{text}', $e->getMessage(), $wrapper);
            }
            return $valid;
        }

        /**
         * Error handler that writes the errors directly to
         * output in bright red on yellow.
         */
        public static function schemeValidationErrorHandler($errno, $errstr, $errfile, $errline)
        {
            static $first = true;

            if ($first)
            {
                self::$xhtmlValidationErrors[] = '<span style="background-color: yellow; color: #c00000;"><b>THIS IS NOT A VALID XHTML FILE</b></span><br />';
                $first = false;
            }
            self::$xhtmlValidationErrors[] = "<span style=\"background-color: yellow; color: #c00000;\">$errstr</span><br />";

            self::$foundErrors = true;
        }

        protected function renderContent()
        {
            return $this->containedView->render();
        }

        /**
         * Renders the xml declaration, doctype, and the html start tag.
         */
        protected function renderXHtmlStart()
        {
            $themeUrl  = Yii::app()->themeManager->baseUrl;
            $theme    = Yii::app()->theme->name;
            $backgroundTexture = Yii::app()->themeManager->getActiveBackgroundTexture();
            $classContent = null;
            if ($backgroundTexture != null)
            {
                $classContent .= ' ' . $backgroundTexture;
            }
            if (!MINIFY_SCRIPTS && Yii::app()->isApplicationInstalled())
            {
                Yii::app()->clientScript->registerScriptFile(
                    Yii::app()->getAssetManager()->publish(
                        Yii::getPathOfAlias('application.core.views.assets')) . '/less-1.2.0.min.js');
            }
            Yii::app()->clientScript->registerScriptFile(
                Yii::app()->getAssetManager()->publish(
                    Yii::getPathOfAlias('application.core.views.assets')) . '/ZurmoDialog.js');
            Yii::app()->clientScript->registerScriptFile(
                Yii::app()->getAssetManager()->publish(
                    Yii::getPathOfAlias('application.core.views.assets')) . '/interactions.js');
            Yii::app()->clientScript->registerScriptFile(
                Yii::app()->getAssetManager()->publish(
                    Yii::getPathOfAlias('application.core.views.assets')) . '/mobile-interactions.js');
            Yii::app()->clientScript->registerScriptFile(
                Yii::app()->getAssetManager()->publish(
                    Yii::getPathOfAlias('application.core.views.assets')) . '/jquery.truncateText.js');
            return '<!DOCTYPE html>' .
                   '<!--[if IE 8]><html class="zurmo ie8' . $classContent . '" lang="en"><![endif]-->' .
                   '<!--[if gt IE 8]><!--><html class="zurmo' . $classContent . '" lang="en"><!--<![endif]-->';
        }

        protected function renderScripts()     {
            Yii::app()->clientScript->registerScriptFile(
                Yii::app()->getAssetManager()->publish(
                    Yii::getPathOfAlias('application.modules.opportunityProducts.elements.assets')) . '/OpportunityProductTemplateUtils.js');
        }

        /**
         * Renders the XHtml before the ending body tag
         */
        protected function renderXHtmlBeforeBodyEnd()
        {
            return Yii::app()->userInterface->renderXHtmlBeforeBodyEndContent();
        }

        /**
         * Renders the XHtml header element containing the title
         * and the default stylesheets screen, print, and ie. Additional
         * stylesheets can be specified by overriding getStyles() in
         * the extending class.
         */
        protected function renderXHtmlHead()
        {
            $title    = trim($this->getTitleAs());
            $subtitle = trim($this->getSubtitle());
            if ($subtitle != '')
            {
                $title = "$title - $subtitle";
            }
            
            $defaultThemeName       = 'default';
            $defaultThemeBaseUrl    = Yii::app()->themeManager->baseUrl . '/' . $defaultThemeName;
            $themeName              = Yii::app()->theme->name;
            $themeBaseUrl           = Yii::app()->themeManager->baseUrl . '/' . $themeName;
            $cs = Yii::app()->getClientScript();
            $specialCssContent = null;
            $publishedAssetsPath = Yii::app()->assetManager->publish(
                    Yii::getPathOfAlias("application.core.views.assets.fonts"));
            $specialCssContent .= "<style>" .
                                    "@font-face" .
                                    "{" .
                                        "font-family: 'zurmo_gamification_symbly_rRg';" .
                                        "src: url('{$publishedAssetsPath}/zurmogamificationsymblyregular-regular-webfont.eot');" .
                                        "src: url('{$publishedAssetsPath}/zurmogamificationsymblyregular-regular-webfont.eot?#iefix') format('embedded-opentype'), " .
                                        "url('{$publishedAssetsPath}/zurmogamificationsymblyregular-regular-webfont.woff') format('woff'), " .
                                        "url('{$publishedAssetsPath}/zurmogamificationsymblyregular-regular-webfont.ttf') format('truetype'), " .
                                        "url('{$publishedAssetsPath}/zurmogamificationsymblyregular-regular-webfont.svg#zurmo_gamification_symbly_rRg') format('svg');" .
                                        "font-weight: normal;" .
                                        "font-style: normal;" .
                                        "unicode-range: U+00-FFFF;" . // Not Coding Standard
                                    "}" .
                                  "</style>";
            if (!MINIFY_SCRIPTS && Yii::app()->isApplicationInstalled())
            {
                $specialCssContent .= '<link rel="stylesheet/less" type="text/css" id="default-theme" href="' .
                                                                                $themeBaseUrl . '/less/default-theme.less"/>';
                $specialCssContent .= '<!--[if lt IE 9]><link rel="stylesheet/less" type="text/css" href="' .
                                                                        $themeBaseUrl . '/less/ie.less"/><![endif]-->';
            }
            else
            {
                Yii::app()->themeManager->registerThemeColorCss();
                if (file_exists("themes/$themeName/css/commercial.css"))
                {
                    $cs->registerCssFile($themeBaseUrl . '/css/commercial.css' .
                        ZurmoAssetManager::getCssAndJavascriptHashQueryString("themes/$themeName/css/commercial.css"));
                }
                if (file_exists("themes/$themeName/css/custom.css"))
                {
                    $cs->registerCssFile($themeBaseUrl . '/css/custom.css' .
                        ZurmoAssetManager::getCssAndJavascriptHashQueryString("themes/$themeName/css/custom.css"));
                }
            }
            if (MINIFY_SCRIPTS)
            {
                Yii::app()->minScript->generateScriptMap('css');
                Yii::app()->minScript->generateScriptMap('css-color');
                if (!YII_DEBUG && !defined('IS_TEST'))
                {
                    Yii::app()->minScript->generateScriptMap('js');
                }
            }
            if (Yii::app()->browser->getName() == 'msie' && Yii::app()->browser->getVersion() < 9)
            {
                $cs->registerCssFile($themeBaseUrl . '/css/ie.css' .
                    ZurmoAssetManager::getCssAndJavascriptHashQueryString("themes/$themeName/css/ie.css"), 'screen, projection');
            }

            foreach ($this->getStyles() as $style)
            {
                if ($style != 'ie')
                {
                    if (file_exists("themes/$themeName/css/$style.css"))
                    {
                        // Begin Not Coding Standard
                        $cs->registerCssFile($themeBaseUrl . '/css/' . $style. '.css' .
                            ZurmoAssetManager::getCssAndJavascriptHashQueryString("themes/$themeName/css/$style.css"));
                        // End Not Coding Standard
                    }
                }
            }
            if (file_exists("themes/$themeName/ico/favic.png"))
            {
                $cs->registerLinkTag('shortcut icon', null, $themeBaseUrl . '/ico/favic.png');
            }
            else
            {
                $cs->registerLinkTag('shortcut icon', null, $defaultThemeBaseUrl . '/ico/favic.png');
            }
            return '<head>' .
                   '<meta charset="utf-8">' .
                   '<meta http-equiv="X-UA-Compatible" content="IE=edge" />' . // Not Coding Standard
                   '<meta name="viewport"  content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">' . // Not Coding Standard
                   '<meta name="apple-mobile-web-app-capable" content="yes" />' . // Not Coding Standard
                   '<link rel="apple-touch-icon" sizes="144x144" href="' . $defaultThemeBaseUrl . '/images/touch-icon-iphone4.png" />'  . //also add 57px, 72px, 144px // Not Coding Standard
                   $specialCssContent .
                   '<title>' . $title . '</title>'  .
                   '</head>';
        }

        /**
         * Returns the application subtitle. Can be overridden in the extending class.
         */
        protected function getSubtitle()
        {
            return 'Estimate Summary';
        }
        
        protected function getTitleAs()
        {
            return 'GICRM';
        }
        

        /**
         * Returns an empty array of styles, being the names of stylesheets
         * without a css extention. Can be overridden in the extending class
         * to specify stylesheets additional to those rendered by default.
         * @see renderXHtmlHead()
         */
        protected function getStyles()
        {
            return array();
        }

        /**
         * Renders the body start tag.
         */
        protected function renderXHtmlBodyStart()
        {
            $classContent      = Yii::app()->themeManager->getActiveThemeColor();
            if (Yii::app()->userInterface->isMobile())
            {
                $classContent .= ' mobile-app';
            }
            if (Yii::app()->isApplicationInSandboxMode())
            {
                $classContent .= ' sandbox-mode';
            }
            Yii::app()->userInterface->resolveCollapseClassForBody($classContent);
            return '<body class="' . $classContent . '">';
        }

        /**
         * Renders the body end tag.
         */
        protected function renderXHtmlBodyEnd()
        {
            return '</body>';
        }

        /**
         * Renders the html end tag.
         */
        protected function renderXHtmlEnd()
        {
            return '</html>';
        }

        public static function makeNonHtmlDuplicateCountAndQueryContent()
        {
            $content = null;
            $duplicateData = Yii::app()->performance->getRedBeanQueryLogger()->getDuplicateQueriesData();
            foreach ($duplicateData as $query => $count)
            {
                $content .= 'Count: ' . $count . '--Query: ' . $query . "\n";
            }
            return $content;
        }

        /**
         * Register into clientScript->scriptFiles any scripts that should load on all pages
         * @see getScriptFilesThatLoadOnAllPages
         */
        public static function registerAllPagesScriptFiles()
        {
            Yii::app()->clientScript->registerCoreScript('jquery');
            Yii::app()->clientScript->registerCoreScript('jquery.ui');
        }

        /**
         * @return array of script files that are loaded on all pages @see registerAllPagesScriptFiles
         */
        public static function getScriptFilesThatLoadOnAllPages()
        {
            //When debug is on, the application never minifies
            $scriptData = array();
            if (MINIFY_SCRIPTS && !YII_DEBUG)
            {
                foreach (Yii::app()->minScript->usingAjaxShouldNotIncludeJsPathAliasesAndFileNames as $data)
                {
                   $scriptData[] = Yii::app()->getAssetManager()->getPublishedUrl(Yii::getPathOfAlias($data[0])) . $data[1];
                }
            }
            return $scriptData;
        }

        /**
         * Add additional html conent before html body end("</body>") tag and html end tag ("</html>")
         * @param string $content
         * @param string $additionalContent
         * @return string
         */
        public function appendContentBeforeXHtmlBodyEndAndXHtmlEnd($content, $additionalContent)
        {
            $content = str_replace($this->renderXHtmlBodyEnd() . $this->renderXHtmlEnd() ,
                                   $additionalContent . $this->renderXHtmlBodyEnd() . $this->renderXHtmlEnd(),
                                   $content );
            return $content;
        }

	
    }
?>
