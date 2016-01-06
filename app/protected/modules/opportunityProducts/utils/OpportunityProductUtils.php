<?php

    /**
     * Class utilized by opportunity product selection
     * 
     * @author Ramachandran.K 
     */
    class OpportunityProductUtils   {

        public function addProductFindDuplicate($category, $addProductoptId, $productCode){
              $opptproducts = OpportunityProduct::getOpptPdctBypdctCode($category,$addProductoptId, $productCode);  
              if($opptproducts != null){
                  return TRUE;
              }else {
                  return FALSE;
              }
        }

        public function makeDropDownByCategory($data, $uniqId, $opportunityId, $prdctCode) {
            $countOfDatas=0;
            $datas = explode(',',$data);
            $element = '<select id="Category_'.$uniqId.'" style="width:75%;">';
            for($i=0;$i < count($datas);$i++) {
                // Manual Query for remove Selected category from category dropdown in Add Product Page
                $optProduct = OpportunityProduct::getOpptPdctBypdctCode(ltrim($datas[$i]),$opportunityId, $prdctCode);
                if( $optProduct == null) {
                    $element .= '<option value="'.ltrim($datas[$i]).'">'.ltrim($datas[$i]).'</option>'; 
                    $countOfDatas = count($datas);
                 } 
             }
            $element .= '</select>';
            if($countOfDatas != null){
                return $element;
            }
            else{
                return null;
            }
        }
        
        public static function getCurrencyType() {
            return '$';
        }

        public function makeCostBookProductSelection($datas, $opportunityId) {
            $categories = Category::getAll();
            $TotalDirectCost=0;
            $content = '<div>';
            $opportunityProducts = OpportunityProduct::getAllByOpptId(intval($opportunityId));
            $opportunity = Opportunity::getById(intval($opportunityId));
                $countOfSelectedRow = 0;
                if($opportunity->recordType->value =='Recurring Final') {
                        $content .= '<div class="cgrid-view type-opportunityProducts" id="list-view">
                                <div class="summary">
                                    5 result(s)
                                </div>
                                <div id="add_Product_list_table" class="table_border_width">
                                    <table id="add_Product_List_table_Value" class="items">
                                        <tr>
                                            <td><label id="totalMhr"> Total Mhr '.OpportunityProductUtils::getCurrencyType().': 0 </label></td>
                                            <td><label id="totalDirectCost"> Total Direct Cost '.OpportunityProductUtils::getCurrencyType().': 0 </label></td>
                                            <td><label id="budget"> Budget '.OpportunityProductUtils::getCurrencyType().': 0 </label></td>
                                        </tr>
                                        <tr>
                                            <td><label id="Revenue_MHR"> Revenue / MHR '.OpportunityProductUtils::getCurrencyType().': 0.0 </label></td>
                                            <td><label id="Aggregate_GPM"> Aggregate GPM %: 0 </label></td>
                                            <td><label id="Suggested_Price"> Suggested Price '.OpportunityProductUtils::getCurrencyType().': 0 </label></td>
                                        </tr>
                                    </table>
                                </div>';
                               
                                if(count($opportunityProducts) > 0){
                                    $content .= '<div id="selected_products" class="table_border_width" style="padding: 0%;">
                                        <div class="align_left" style="background-color:#E0D1D1; color:black;padding:0.5%; font-weight:bold;">
                                             Selected Products <span id="showresults" style="color:green; font-weight:none;"></span>
                                        </div>
                                            <div style="margin:0.5% 0% 0.5% 45%">
                                                <a href="#" onclick="javascript:updateSelectedProducts(\''.$opportunityId.'\');">
                                                    <span class="z-label">
                                                        Update Values
                                                    </span>
                                                </a>
                                            </div>';
                                    $content .='<table class="items selected_products_table">
                                                <tr style="color:black; padding:0.5%;">
                                                    <th>Product Code</th>
                                                    <th>Product Name</th>
                                                    <th>Unit of Measure</th>
                                                    <th>Quantity</th>
                                                    <th>Frequency</th>
                                                    <th>MH</th>
                                                    <th>L+B</th>
                                                    <th>M</th>
                                                    <th>E</th>
                                                    <th>S</th>
                                                    <th>O</th>
                                                    <th>Total Direct Cost</th>
                                                </tr>';
                                    
                                    foreach($opportunityProducts as $row) {
                                       $opportunityPdctMap[$row->Category][] = $row;
                                    }
                                    $CategoryKeyCount = 0;
                                    $totalMhr = 0;
                                    $totalDirectCost = 0;
                                    $totalFinalPrice = 0.0;
				                    $actualGPM = 0;
                                    $totalRevenue = 0.0;
                                    foreach($opportunityPdctMap as $CategoryKey => $opportunityArray){
                                        $content .='<tr>
                                              <th colspan="12" class="align_left" style="background-color:gray;color:white;">'.$CategoryKey.'</th>
                                              <input type="hidden" name="CategoryKey" id="CategoryKey_'.$CategoryKeyCount.'" value="'.$CategoryKey.'">
                                            </tr>';
                                        foreach ($opportunityArray as $opportunityKey => $opportunitypdt){
                                            $totalMhr += $opportunitypdt->Total_MHR;
                                            $totalDirectCost += $opportunitypdt->Total_Direct_Cost->value;
                                            $totalFinalPrice += $opportunitypdt->Final_Cost->value;
                                            $content .='<tr>
                                                <td>'.$opportunitypdt->costbook->productcode.'</td>
                                                <input value='.$opportunitypdt->id.' name="list_View_Add_Product_SelectedIds"id="list_View_Producted_SelectedIds_'.$countOfSelectedRow.'" type="hidden">
                                                <td>'.$opportunitypdt->name.'</td>
                                                <td>'.$opportunitypdt->costbook->unitofmeasure.'</td>
                                                <td><input type="text" name="updateFrequency&Quantity" id="updateQuantity_'.$countOfSelectedRow.'" value='.$opportunitypdt->Quantity.'></td>
                                                <td><input name="updateFrequency&Quantity" type="text" id="updateFrequency_'.$countOfSelectedRow.'" value='.$opportunitypdt->Frequency.'></td>
                                                <td>'.$opportunitypdt->Total_MHR.'</td>
                                                <td>'.OpportunityProductUtils::getCurrencyType().(($opportunitypdt->Labor_Cost->value)+($opportunitypdt->Burden_Cost->value)).'</td>
                                                <td>'.OpportunityProductUtils::getCurrencyType().$opportunitypdt->Materials_Cost.'</td>
                                                <td>'.OpportunityProductUtils::getCurrencyType().$opportunitypdt->Equipment_Cost.'</td>
                                                <td>'.OpportunityProductUtils::getCurrencyType().$opportunitypdt->Sub_Cost.'</td>
                                                <td>'.OpportunityProductUtils::getCurrencyType().$opportunitypdt->Other_Cost.'</td>
                                                <td>'.OpportunityProductUtils::getCurrencyType().$opportunitypdt->Total_Direct_Cost->value.'</td>
                                            </tr>';
                                            $countOfSelectedRow++;
                                          }
                                          $CategoryKeyCount++;
                                    }
                                    if($totalFinalPrice > 0) {
						$actualGPM = 	(($totalFinalPrice - $totalDirectCost)/$totalFinalPrice)*100;		
					}
                                    if($totalMhr > 0) {
                                        $totalRevenue = $totalFinalPrice/$totalMhr;				
                                    }
                                    Yii::app()->clientScript->registerScript('calculationForAddProductScreenRecurring',
                                        '$("#totalMhr").text("Total Mhr '.OpportunityProductUtils::getCurrencyType().': '.$totalMhr.'");
                                         $("#totalDirectCost").text("Total Direct Cost '.OpportunityProductUtils::getCurrencyType().': '.$totalDirectCost.'");   
                                         $("#budget").text("Budget '.OpportunityProductUtils::getCurrencyType().' : '.$opportunity->budget->value.'");
                                         $("#Suggested_Price").text("Suggested Price '.OpportunityProductUtils::getCurrencyType().': '.sprintf('%.2f', $totalFinalPrice).'");
					 $("#Aggregate_GPM").text("Aggregate GPM %: '.sprintf('%.2f', $actualGPM).'");
                                         //$("#Roundedvalue").text(" Rounded value").css({"color":"red"});    
					 $("#Revenue_MHR").text("Revenue / MHR '.OpportunityProductUtils::getCurrencyType().': '.sprintf('%.2f',$totalRevenue).'");    
                                     ');
                                 $content .='<input value="'.$countOfSelectedRow.'" name="Selected_Products_Ids" id="Selected_Products_Ids" type="hidden">';
                          $content .=' </table></div></td></tr></table></div>';
                          
                            }
                        $content .='<div class="table_border_width" id="add_product_search" style="padding: 0px;">
                                         <div class="panel">
                                            <div class="align_left" style="color:black; background-color:#E0D1D1; color:black; padding:0.5%; font-weight:bold;">Search</div>
                                            <table class="form-fields">
                                                <colgroup><col class="col-0"><col class="col-1"></colgroup>
                                                <tbody>
                                                    <tr>
                                                        <th width="20%">
                                                            <label for="oppt_AddProductcategory_value">Select Category</label>
                                                        </th>
                                                        <td colspan="1" >
                                                            <div class="hasDropDown">
                                                                <span class="select-arrow"></span>
                                                                    <select id="oppt_AddProductcategory_value" name="Costbook[assemblycategory][value]">
                                                                        <option value="All">All</option>';
                                                                          foreach($categories as $values) {
                                                                          $content .= '<option value="'.$values->name.'">'.$values->name.'</option>';
                                                                          }
                                                                    $content .= '</select>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            <label for="costofgoodssoldassembly">Select COGS</label>
                                                        </th>
                                                        <td colspan="1" style="margin: 0px;">
                                                            <div class="hasDropDown">
                                                                <span class="select-arrow"></span>
                                                                    <select id="oppt_AddProductcostofgoodssold_value" name="Costbook[costofgoodssoldassembly][value][assemblycategory][value]">
                                                                        <option selected="selected" value="All">All</option>
                                                                        <option value="Labor">Labor</option>
                                                                        <option value="Equipment">Equipment</option>
                                                                        <option value="Material">Material</option>
                                                                        <option value="Subcontractor">Subcontractor</option>
                                                                        <option value="Other">Other</option>            
                                                                    </select>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="costofgoodssoldassembly"></label>
                                                        </td>
                                                        <td colspan="1">
                                                        <div style="margin-left:32%;">                                                            
                                                            <a id="search" onClick="javascript:searchProducts(\''.$opportunityId.'\');" class="attachLoading z-button cancel-button" name="Search" href="#">
                                                                <span class="z-spinner"></span>
                                                                <span class="z-icon"></span>
                                                                <span class="z-label">Search</span>
                                                            </a>
                                                        </div>    
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                         </div>
                                         <div id="result_div"></div>
                                     </div>
                                </div>';
                       $content .= OpportunityProductUtils::appendButton($opportunityId);                                                                    
                       $content .='<div class="items-wrapper" id="addProductWrapper">
                       <input type="hidden" id="selectedProductCnt" value="'.$countOfSelectedRow.'" />    
                                        <div id="searchProducts">';} 
                        
                else {
                    $content .= '<div class="cgrid-view type-opportunityProducts" id="list-view">
                                <div class="summary">
                                    5 result(s)
                                </div>
                                <div id="add_product_outer">
                                    <div id="add_Product_list_table" class="table_border_width">
                                        <table id="add_Product_List_table_Value" class="items">
                                            <tr>
                                                <td><label id="totalMhr"> Total Mhr '.OpportunityProductUtils::getCurrencyType().': 0 </label></td>
                                                <td><label id="totalDirectCost"> Total Direct Cost '.OpportunityProductUtils::getCurrencyType().': 0 </label></td>
                                                <td><label id="budget"> Budget '.OpportunityProductUtils::getCurrencyType().': 0 </label></td>
                                            </tr>
                                            <tr>
                                                <td><label id="Revenue_MHR"> Revenue / MHR '.OpportunityProductUtils::getCurrencyType().': 0.0 </label></td>
                                                <td><label id="Aggregate_GPM"> Aggregate GPM %: 0 </label></td>
                                                <td><label id="Suggested_Price"> Suggested Price '.OpportunityProductUtils::getCurrencyType().': 0 </label></td>
                                            </tr>
                                        </table>
                                    </div>';
                                
                                if(count($opportunityProducts) > 0){
                                    $content .= '<div id="selected_products" class="table_border_width" style="padding: 0px;">
                                    
                                        <div class="align_left" style="background-color:#E0D1D1; color:black;padding:0.5%; font-weight:bold;">
                                             Selected Products <span id="showresults" style="color:green; font-weight:none;"></span>
                                        </div>
                                            <div style="margin:0.5% 0% 0.5% 45%">
                                                <a href="#" onclick="javascript:updateSelectedProducts(\''.$opportunityId.'\');">
                                                    <span class="z-label">
                                                        Update Values
                                                    </span>
                                                </a>
                                            </div>';
                                    $content .='<table class="items selected_products_table">
                                                <tr style="color:black; padding:0.5%;">
                                                    <th>Product Code</th>
                                                    <th>Product Name</th>
                                                    <th>Unit of Measure</th>
                                                    <th>Quantity</th>
                                                    <th>MH</th>
                                                    <th>L+B</th>
                                                    <th>M</th>
                                                    <th>E</th>
                                                    <th>S</th>
                                                    <th>O</th>
                                                    <th>Total Direct Cost</th>
                                                </tr>';
                                    
                                    foreach($opportunityProducts as $row) {
                                       $opportunityPdctMap[$row->Category][] = $row;
                                    }
                                    $CategoryKeyCount = 0;
                                    $totalMhr = 0;
                                    $totalDirectCost = 0;
				                    $totalFinalPrice = 0.0;
				                    $actualGPM = 0;
                                    $totalRevenue = 0.0;
                                    foreach($opportunityPdctMap as $CategoryKey => $opportunityArray){
                                        $content .='<tr>
                                              <th colspan="11" class="align_left" style="background-color:gray; color:white;">'.$CategoryKey.'</th>
                                              <input type="hidden" name="CategoryKey" id="CategoryKey_'.$CategoryKeyCount.'" value="'.$CategoryKey.'">
                                            </tr>';
                                        foreach ($opportunityArray as $opportunityKey => $opportunitypdt){
                                            $totalMhr += $opportunitypdt->Total_MHR;
                                            $totalDirectCost += $opportunitypdt->Total_Direct_Cost->value;
					    $totalFinalPrice += $opportunitypdt->Final_Cost->value;
                                            $content .='<tr>
                                                <td>'.$opportunitypdt->costbook->productcode.'</td>
                                                <input value='.$opportunitypdt->costbook->productcode.' name="productCode" id="productCode_'.$countOfSelectedRow.'" type="hidden">    
                                                <input value='.$opportunitypdt->id.' name="list_View_Add_Product_SelectedIds"id="list_View_Producted_SelectedIds_'.$countOfSelectedRow.'" type="hidden">
                                                <td>'.$opportunitypdt->name.'</td>
                                                <td>'.$opportunitypdt->costbook->unitofmeasure.'</td>
                                                <td><input type="text" id="updateQuantity_'.$countOfSelectedRow.'" value='.$opportunitypdt->Quantity.'></td>
                                                <td>'.$opportunitypdt->Total_MHR.'</td>    
                                                <td>'.OpportunityProductUtils::getCurrencyType().(($opportunitypdt->Labor_Cost->value)+($opportunitypdt->Burden_Cost->value)).'</td>
                                                <td>'.OpportunityProductUtils::getCurrencyType().$opportunitypdt->Materials_Cost.'</td>
                                                <td>'.OpportunityProductUtils::getCurrencyType().$opportunitypdt->Equipment_Cost.'</td>
                                                <td>'.OpportunityProductUtils::getCurrencyType().$opportunitypdt->Sub_Cost.'</td>
                                                <td>'.OpportunityProductUtils::getCurrencyType().$opportunitypdt->Other_Cost.'</td>
                                                <td>'.OpportunityProductUtils::getCurrencyType().$opportunitypdt->Total_Direct_Cost->value.'</td>
                                            </tr>';
                                            $countOfSelectedRow++;
                                          }
                                    $CategoryKeyCount++;
                                    }
					if($totalFinalPrice > 0) {
						$actualGPM = 	(($totalFinalPrice - $totalDirectCost)/$totalFinalPrice)*100;		
					}
					if($totalMhr > 0) {
						$totalRevenue = $totalFinalPrice/$totalMhr;				
					}
                                    
                                     Yii::app()->clientScript->registerScript('calculationForAddProductScreen',
                                        '$("#totalMhr").text("Total Mhr '.OpportunityProductUtils::getCurrencyType().': '.$totalMhr.'");
                                         $("#totalDirectCost").text("Total Direct Cost '.OpportunityProductUtils::getCurrencyType().': '.$totalDirectCost.'");   
                                         $("#budget").text("Budget '.OpportunityProductUtils::getCurrencyType().': '.$opportunity->budget->value.'");   
					 $("#Suggested_Price").text("Suggested Price '.OpportunityProductUtils::getCurrencyType().': '.sprintf('%.2f', $totalFinalPrice).'");
					 $("#Aggregate_GPM").text("Aggregate GPM %: '.sprintf('%.2f', $actualGPM).'");
                                         //$("#Roundedvalue").text(" Rounded value").css({"color":"red"});
					 $("#Revenue_MHR").text("Revenue / MHR '.OpportunityProductUtils::getCurrencyType().': '.sprintf('%.2f',$totalRevenue).'");
                                        //alert("'.$totalMhr.'");
                                     ');

                                    
                          $content .='<input value="'.$countOfSelectedRow.'" name="Selected_Products_Ids" id="Selected_Products_Ids" type="hidden">';
                          $content .=' </table></div></td></tr></table></div>
                            </div>';
                    }
                       $content .='<div class="table_border_width" id="add_product_search" style="padding: 0px;">
                                         <div class="panel">
                                            <div class="align_left" style="color:black; background-color:#E0D1D1; color:black; padding:0.5%; font-weight:bold;">Search</div>
                                            <table class="form-fields items">
                                                <colgroup><col class="col-0"><col class="col-1"></colgroup>
                                                <tbody>
                                                    <tr>
                                                        <th width="20%">
                                                            <label for="oppt_AddProductcategory_value">Select Category</label>
                                                        </th>
                                                        <td colspan="1">
                                                            <div class="hasDropDown">
                                                                <span class="select-arrow"></span>
                                                                    <select id="oppt_AddProductcategory_value" name="Costbook[assemblycategory][value]">
                                                                        <option value="All">All</option>';
                                                                      foreach($categories as $values) {
                                                                      $content .= '<option value="'.$values->name.'">'.$values->name.'</option>';
                                                                      }
                                                                    $content .= '</select>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            <label for="costofgoodssoldassembly">Select COGS</label>
                                                        </th>
                                                        <td colspan="1">
                                                            <div class="hasDropDown">
                                                                <span class="select-arrow"></span>
                                                                    <select id="oppt_AddProductcostofgoodssold_value" name="Costbook[costofgoodssoldassembly][value][assemblycategory][value]">
                                                                        <option selected="selected" value="All">All</option>
                                                                        <option value="Labor">Labor</option>
                                                                        <option value="Equipment">Equipment</option>
                                                                        <option value="Material">Material</option>
                                                                        <option value="Subcontractor">Subcontractor</option>
                                                                        <option value="Other">Other</option>            
                                                                    </select>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="costofgoodssoldassembly"></label>
                                                        </td>
                                                        <td colspan="1">
                                                        <div style="margin-left:32%;">    
                                                            <a id="search" onclick="javascript:searchProducts(\''.$opportunityId.'\');" class="attachLoading cancel-button" name="Search" href="#">
                                                                <span class="z-spinner"></span>
                                                                <span class="z-icon"></span>
                                                                <span class="z-label">Search</span>
                                                            </a>
                                                        </div>    
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                         </div>
                                         <div id="result_div"></div>
                                     </div>';
                       $content .= OpportunityProductUtils::appendButton($opportunityId);
                       $content .='<div class="items-wrapper" id="addProductWrapper">
                       <input type="hidden" id="selectedProductCnt" value="'.$countOfSelectedRow.'" />
                       <div id="searchProducts"><a id="" href="searchResult"></a>';
                 } 
                        
                $count = 0;
        $content1='';
  
               if($opportunity->recordType->value =='Recurring Final') {
                   foreach($datas as $data) {
                       $dispCategory = OpportunityProductUtils::makeDropDownByCategory($data -> category, $count, $opportunityId, $data -> productcode);
                $count++;   
            }
        } else {
            foreach($datas as $data) {
                            $dispCategory = OpportunityProductUtils::makeDropDownByCategory($data -> category, $count, $opportunityId, $data -> productcode);
                $count++;   
            }
        }
        $content .= '</tbody>
                         </table>
                            <input value="" name="list-view-selectedIds" id="list-view-selectedIds" type="hidden">
                         </div>
                         </div></div>
                         <input value="'.$opportunity->recordType->value.'" name="recordType" id="recordType_Ids" type="hidden">';
                    
        return $content;
    }

    public function appendButton($opportunityId) {
        $content = '<div class="float-bar">
                        <div class="view-toolbar-container clearfix" style="border-top-width: 0px;">
                            <div class="form-toolbar" border-top-width: 0px; id="appendBtn">
                                <a href="#" id="GoBack" onclick="javascript:addProductInOpportunity(\''.$opportunityId.'\', this);" class="attachLoading cancel-button">
                                    <span class="z-label appendButton">
                                        Go Back
                                    </span>
                                </a>

                                <a href="#" id="saveAndMore" onclick="javascript:addProductInOpportunity(\''.$opportunityId.'\', this);" name="saveAddMore" class="attachLoading">                                            
                                    <span class="z-label">
                                        Save Add More
                                    </span>
                                </a>
                                
                                <a class="attachLoading " id="Save" onclick="javascript:addProductInOpportunity(\''.$opportunityId.'\', this);" href="#">
                                    <span class="z-label">
                                        Save
                                    </span>
                                </a>
                                
                
                            </div>
                        </div>
                    </div>                 ';
        return $content;
    }

    public function addOpportunityProductsCalculation(Costbook $costbook,$quantity,$frequency,$optId,$category) {
        try{
            $tQty = 0;
            $opportunityProduct = new OpportunityProduct();
                $opportunityProduct->Quantity = (float) $quantity;
            if($frequency != '') {  
                    $opportunityProduct->Frequency = (float) $frequency; 
            }
            $opportunityProduct->Category = $category;
                        $opportunityProduct->Product_Code = $costbook->productcode;
                $opportunityProduct->name = $costbook->productname; 
            $currencies                       = Currency::getAll();

            $opportunityProduct->costbook = $costbook;
            $tQty = (float)$quantity;
            if($opportunityProduct->Frequency > 0) {
                $tQty *= $opportunityProduct->Frequency;
            }

            //Labor Product calculation
            if($costbook->costofgoodssold->value == 'Labor') {
//                $opportunityProduct->Total_MHR = round($costbook->costperunit * $tQty);
                $opportunityProduct->Total_MHR = $tQty;
                if(intval($costbook->laborCost) > 0 && intval($costbook->burdenCost) > 0) {
                    $burdenCost                    = new CurrencyValue();
                    $burdenCost->value             = round((intval($costbook->burdenCost) * $tQty),2);
                    $burdenCost->currency          = $currencies[0];
                    $opportunityProduct->Burden_Cost = $burdenCost;
                    $laborCost                    = new CurrencyValue();
                    $laborCost->value             = round((intval($costbook->laborCost) * $tQty),2);
                    $laborCost->currency          = $currencies[0];
                    $opportunityProduct->Labor_Cost = $laborCost;
                } else if(intval($costbook->laborCost) > 0 &&  intval($costbook->burdenCost) <= 0) {
                    $burdenCost                    = new CurrencyValue();
                    $burdenCost->value             = 0.0;
                    $burdenCost->currency          = $currencies[0];
                    $opportunityProduct->Burden_Cost = $burdenCost;
                    $laborCost                    = new CurrencyValue();
                    $laborCost->value             = round((intval($costbook->laborCost) * $tQty),2);
                    $laborCost->currency          = $currencies[0];
                    $opportunityProduct->Labor_Cost = $laborCost;
                } else if(intval($costbook->burdenCost) > 0 && intval($costbook->laborCost) <= 0) {
                    $burdenCost                    = new CurrencyValue();
                    $burdenCost->value             = round((intval($costbook->burdenCost) * $tQty),2);
                    $burdenCost->currency          = $currencies[0];
                    $opportunityProduct->Burden_Cost = $burdenCost;
                    $laborCost                    = new CurrencyValue();
                    $laborCost->value             = 0.0;
                    $laborCost->currency          = $currencies[0];
                    $opportunityProduct->Labor_Cost = $laborCost;

                } else {
                    $burdenCost                    = new CurrencyValue();
                    $burdenCost->value             = 0.0;
                    $burdenCost->currency          = $currencies[0];
                    $opportunityProduct->Burden_Cost = $burdenCost;
                    $laborCost                    = new CurrencyValue();
                    $laborCost->value             = 0.0;
                    $laborCost->currency          = $currencies[0];
                    $opportunityProduct->Labor_Cost = $laborCost;
                }
                //$opportunityProduct->Total_MHR = $tQty;
            } else {
                $burdenCost                    = new CurrencyValue();
                $burdenCost->value             = 0.0;
                $burdenCost->currency          = $currencies[0];
                $opportunityProduct->Burden_Cost = $burdenCost;
                $laborCost                    = new CurrencyValue();
                $laborCost->value             = 0.0;
                $laborCost->currency          = $currencies[0];
                $opportunityProduct->Labor_Cost = $laborCost;
            }

            if($costbook->costofgoodssold->value == 'Material') {
               // $opportunityProduct->Total_MHR = round($tQty);
                $materialCost                    = new CurrencyValue();
                $materialCost->value             = round((intval($costbook->unitdirectcost)*$tQty),2);
                $materialCost->currency          = $currencies[0];
                $opportunityProduct->Materials_Cost = $materialCost;
            } else {
                $materialCost                    = new CurrencyValue();
                $materialCost->value             = 0.0;
                $materialCost->currency          = $currencies[0];
                $opportunityProduct->Materials_Cost = $materialCost;
            }

            if($costbook->costofgoodssold->value == 'Equipment') {
                //$opportunityProduct->Total_MHR = round($tQty);
                $eqmtCost                    = new CurrencyValue();
                $eqmtCost->value             = round($tQty,2);
                $eqmtCost->currency          = $currencies[0];
                $opportunityProduct->Equipment_Cost = $eqmtCost;
            } else {
                $eqmtCost                    = new CurrencyValue();
                $eqmtCost->value             = 0.0;
                $eqmtCost->currency          = $currencies[0];
                $opportunityProduct->Equipment_Cost = $eqmtCost;            
            }

            if($costbook->costofgoodssold->value == 'Subcontractor') {
                //$opportunityProduct->Total_MHR = round($tQty);

                $subcontCost                    = new CurrencyValue();
                $subcontCost->value             = round((intval($costbook->unitdirectcost)*$tQty),2);
                $subcontCost->currency          = $currencies[0];
                $opportunityProduct->Sub_Cost = $subcontCost;
            } else {
                $subcontCost                    = new CurrencyValue();
                $subcontCost->value             = 0.0;
                $subcontCost->currency          = $currencies[0];
                $opportunityProduct->Sub_Cost = $subcontCost;
            }

            if($costbook->costofgoodssold->value == 'Other') {
               // $opportunityProduct->Total_MHR = round($tQty);
                $otherCost                    = new CurrencyValue();
                $otherCost->value             = round((intval($costbook->unitdirectcost)*$tQty),2);
                $otherCost->currency          = $currencies[0];
                $opportunityProduct->Other_Cost = $otherCost;
            } else {
                $otherCost                    = new CurrencyValue();
                $otherCost->value             = 0.0;
                $otherCost->currency          = $currencies[0];
                $opportunityProduct->Other_Cost = $otherCost;
            }
            $values = Category::getCategoryByName($category);
            if($values != '') {   
                $opportunityProduct->Category_GPM = $values[0]->targetgpm;
            } else {
                $opportunityProduct->Category_GPM = 40;
            }    
            $totalDirectCost = new CurrencyValue();
            $totalDirectCost->value             = round(($opportunityProduct->Labor_Cost->value+$opportunityProduct->Burden_Cost->value + $opportunityProduct->Materials_Cost->value + $opportunityProduct->Equipment_Cost->value + $opportunityProduct->Sub_Cost->value + $opportunityProduct->Other_Cost->value),2);
            $totalDirectCost->currency          = $currencies[0];
            $opportunityProduct->Total_Direct_Cost = $totalDirectCost;
            $finalCost = new CurrencyValue();
            $finalCost->value             =  round(($opportunityProduct->Total_Direct_Cost->value/(1-($opportunityProduct->Category_GPM/100))),2);
            $finalCost->currency          =  $currencies[0];
            $opportunityProduct->Final_Cost = $finalCost;
            $opportunityProduct->opportunity = $opportunity = Opportunity::GetById(intval($optId));
            if(!$opportunityProduct->save()) {
                throw new Exception();
            }
            else return TRUE;
        } catch( Exception $ex) {
            //echo 'Exception occured'.$ex;       die;
             return FALSE;
        }
    }
        
        public function updateOpportunityProduct($opptPdct, $quantity, $frequency) {
            try{
                $currencies                       = Currency::getAll();
                $tQty = (float)$quantity;
                $opptPdct->Quantity = $tQty;
            if((float)$frequency > 0) { 
                        $opptPdct->Frequency = $frequency;
                        $tQty *= $opptPdct->Frequency;
            }
           if($opptPdct->costbook->costofgoodssold->value == 'Labor') {
  //              $opptPdct->Total_MHR = round($opptPdct->costbook->costperunit * $tQty);
               $opptPdct->Total_MHR = round($tQty);
               if(intval($opptPdct->costbook->laborCost) > 0 && intval($opptPdct->costbook->burdenCost) > 0) {
                    $burdenCost                    = new CurrencyValue();
                    $burdenCost->value             = round((intval($opptPdct->costbook->burdenCost) * $tQty),2);
                    $burdenCost->currency          = $currencies[0];
                    $opptPdct->Burden_Cost = $burdenCost;
                    $laborCost                    = new CurrencyValue();
                    $laborCost->value             = round((intval($opptPdct->costbook->laborCost) * $tQty),2);
                    $laborCost->currency          = $currencies[0];
                    $opptPdct->Labor_Cost = $laborCost;
                } 
                else if(intval($opptPdct->costbook->laborCost) > 0 &&  intval($opptPdct->costbook->burdenCost) <= 0) {
                    $burdenCost                    = new CurrencyValue();
                    $burdenCost->value             = 0.0;
                    $burdenCost->currency          = $currencies[0];
                    $opptPdct->Burden_Cost = $burdenCost;
                    $laborCost                    = new CurrencyValue();
                    $laborCost->value             = round((intval($opptPdct->costbook->laborCost) * $tQty),2);
                    $laborCost->currency          = $currencies[0];
                    $opptPdct->Labor_Cost = $laborCost;
                }
                else if(intval($opptPdct->costbook->burdenCost) > 0 && intval($opptPdct->costbook->laborCost) <= 0) {
                    $burdenCost                    = new CurrencyValue();
                    $burdenCost->value             = round((intval($opptPdct->costbook->burdenCost) * $tQty),2);
                    $burdenCost->currency          = $currencies[0];
                    $opptPdct->Burden_Cost = $burdenCost;
                    $laborCost                    = new CurrencyValue();
                    $laborCost->value             = 0.0;
                    $laborCost->currency          = $currencies[0];
                    $opptPdct->Labor_Cost = $laborCost;
                }
                else {
                    $burdenCost                    = new CurrencyValue();
                    $burdenCost->value             = 0.0;
                    $burdenCost->currency          = $currencies[0];
                    $opptPdct->Burden_Cost = $burdenCost;
                    $laborCost                    = new CurrencyValue();
                    $laborCost->value             = 0.0;
                    $laborCost->currency          = $currencies[0];
                    $opptPdct->Labor_Cost = $laborCost;
                }
           }
           else {
                $burdenCost                    = new CurrencyValue();
                $burdenCost->value             = 0.0;
                $burdenCost->currency          = $currencies[0];
                $opptPdct->Burden_Cost = $burdenCost;
                $laborCost                    = new CurrencyValue();
                $laborCost->value             = 0.0;
                $laborCost->currency          = $currencies[0];
                $opptPdct->Labor_Cost = $laborCost;
           }
        if($opptPdct->costbook->costofgoodssold->value == 'Material') {
          //  $opptPdct->Total_MHR = round($tQty);
            $materialCost                    = new CurrencyValue();
            $materialCost->value             = round((intval($opptPdct->costbook->unitdirectcost)*$tQty),2);
            $materialCost->currency          = $currencies[0];
            $opptPdct->Materials_Cost = $materialCost;
        } else {
            $materialCost                    = new CurrencyValue();
            $materialCost->value             = 0.0;
            $materialCost->currency          = $currencies[0];
            $opptPdct->Materials_Cost = $materialCost;
        }

        if($opptPdct->costbook->costofgoodssold->value == 'Equipment') {
          //  $opptPdct->Total_MHR = round($tQty);
            $eqmtCost                    = new CurrencyValue();
            $eqmtCost->value             = round($tQty,2);
            $eqmtCost->currency          = $currencies[0];
            $opptPdct->Equipment_Cost = $eqmtCost;
        } else {
            $eqmtCost                    = new CurrencyValue();
            $eqmtCost->value             = 0.0;
            $eqmtCost->currency          = $currencies[0];
            $opptPdct->Equipment_Cost = $eqmtCost;          
        }
        if($opptPdct->costbook->costofgoodssold->value == 'Subcontractor') {
           // $opptPdct->Total_MHR = round($tQty);
            $subcontCost                    = new CurrencyValue();
            $subcontCost->value             = round((intval($opptPdct->costbook->unitdirectcost)*$tQty),2);
            $subcontCost->currency          = $currencies[0];
            $opptPdct->Sub_Cost = $subcontCost;
        } else {
            $subcontCost                    = new CurrencyValue();
            $subcontCost->value             = 0.0;
            $subcontCost->currency          = $currencies[0];
            $opptPdct->Sub_Cost = $subcontCost;
        }
         if($opptPdct->costbook->costofgoodssold->value == 'Other') {
          //  $opptPdct->Total_MHR = round($tQty);
            $otherCost                    = new CurrencyValue();
            $otherCost->value             = round((intval($opptPdct->costbook->unitdirectcost)*$tQty),2);
            $otherCost->currency          = $currencies[0];
            $opptPdct->Other_Cost = $otherCost;
        } else {
            $otherCost                    = new CurrencyValue();
            $otherCost->value             = 0.0;
            $otherCost->currency          = $currencies[0];
            $opptPdct->Other_Cost = $otherCost;
        }
            $totalDirectCost = new CurrencyValue();
            $totalDirectCost->value             = round(($opptPdct->Labor_Cost->value+$opptPdct->Burden_Cost->value + $opptPdct->Materials_Cost->value + $opptPdct->Equipment_Cost->value + $opptPdct->Sub_Cost->value + $opptPdct->Other_Cost->value),2);
            $totalDirectCost->currency          = $currencies[0];
            $opptPdct->Total_Direct_Cost = $totalDirectCost;
            $finalCost = new CurrencyValue();
            $finalCost->value             =  round(($opptPdct->Total_Direct_Cost->value/(1-($opptPdct->Category_GPM/100))),2);
            $finalCost->currency          =  $currencies[0];
            $opptPdct->Final_Cost = $finalCost;
        if(!$opptPdct->save()) {
                throw new Exception();
        }
        return true;
            } catch(Exception $ex) {
                return false;
            }
            
        }

        public function makeOpportunityProductSelection($datas, $opportunityId) {
            $content = '';
            $opptProducts = OpportunityProduct::getAllByOpptId(intval($opportunityId));
            $opportunity = Opportunity::getById($opportunityId);
            $count = count($opptProducts);
            $totalDirectCost = 0;
            $totalMH = 0;
            $suggestedPrice = 0;
            $opptPdctMap;
            if(count($opptProducts) > 0) {
                foreach($opptProducts as $row) {
                    $opptPdctMap[$row->Category][] = $row;
                }
            $tableCreation = '';
            $tableCreation .= '<div class="view-toolbar-container clearfix">
                                    <div style="background-color:#E0D1D1; color:black;padding:0.5%; font-weight:bold; font-size: 13px;">
                                        Estimate Summary
                                    </div>
                                    <div style="font-weight: bold; padding: 10px;">Number Of Products :'.$count.'</div>';
            $tableCreation .= '<table  border="1" width="100%" class="items">
                             <colgroup span="5"></colgroup>';
                             
            $tableCreation .= '<thead style="font-weight: bold; background-color:#E6E6E6; color: black;padding: 5px;">
                                   <tr style="border: 1px solid gray;">
                                       <th colspan="13" style="font-weight: bold;padding: 10px;text-align:center;">Opportunity Products</th>
                                   </tr>                                         
                                        <th style="font-weight: bold;padding: 10px;text-align:center;">Product Code</th>
                                        <th style="font-weight: bold;padding: 10px;text-align:center;">Product Name</th>
                                        <th style="font-weight: bold;padding: 10px;text-align:center;">Unit of Measure</th>
                                        <th style="font-weight: bold;padding: 10px;text-align:center;">Quantity</th>
                                        <th style="font-weight: bold;padding: 10px;text-align:center;">Frequency</th>
                                        <th style="font-weight: bold;padding: 10px;text-align:center;">MH</th>
                                        <th style="font-weight: bold;padding: 10px;text-align:center;">L</th>
                                        <th style="font-weight: bold;padding: 10px;text-align:center;">B</th>
                                        <th style="font-weight: bold;padding: 10px;text-align:center;">M</th>
                                        <th style="font-weight: bold;padding: 10px;text-align:center;">E</th>
                                        <th style="font-weight: bold;padding: 10px;text-align:center;">S</th>
                                        <th style="font-weight: bold;padding: 10px;text-align:center;">O</th>
                                        <th style="font-weight: bold;padding-top: 10px;text-align:center;">Total Direct Cost</th>
                                   </thead><tbody>';
             foreach ($opptPdctMap as $key => $optpdctArray)  {
                $tableCreation .= '<th  style="padding: 3px;font-weight: bold;background-color:gray;color:white;" colspan="13">'.$key.'</th>';
                 foreach ($optpdctArray as $optKey => $optpdt){
                    $totalDirectCost += $optpdt->Total_Direct_Cost->value;
                    $suggestedPrice += $optpdt->Final_Cost->value;
                    $totalMH += $optpdt->Total_MHR;
                    $tableCreation .= '<tr>
                            <td style="width: 8%; text-align: left; padding: 3px;">'.$optpdt->costbook->productcode.'</td>
                            <td style="width: 15%; text-align: left; padding: 3px;">'.$optpdt->name.'</td>
                            <td style="width: 4%; text-align: center; padding: 3px;">'.$optpdt->costbook->unitofmeasure.'</td>
                            <td style="width: 5%; text-align: center; padding: 3px">'.$optpdt->Quantity.'</td>
                            <td style="width: 5%; text-align: center; padding: 3px">'.$optpdt->Frequency.'</td>
                            <td style="width: 6%; text-align: center; padding: 3px">'.$optpdt->Total_MHR.'</td>
                            <td style="width: 6%;padding-top: 2px; text-align: right; padding: 3px">'.OpportunityProductUtils::getCurrencyType() .$optpdt->Labor_Cost.'</td>
                            <td style="width: 6%;  padding-top: 2px; text-align: right; padding: 3px">'.OpportunityProductUtils::getCurrencyType() .$optpdt->Burden_Cost.'</td>
                            <td style="width: 6%;padding-top: 2px; text-align: right; padding: 3px">'.OpportunityProductUtils::getCurrencyType() .$optpdt->Materials_Cost.'</td>
                            <td style="width: 6%;padding-top: 2px; text-align: right; padding: 3px">'.OpportunityProductUtils::getCurrencyType() .$optpdt->Equipment_Cost.'</td>
                            <td style="width: 6%;  padding-top: 2px; text-align: right; padding: 3px">'.OpportunityProductUtils::getCurrencyType() .$optpdt->Sub_Cost.'</td>
                            <td style="width: 6%;  padding-top: 2px; text-align: right; padding: 3px">'.OpportunityProductUtils::getCurrencyType() .$optpdt->Other_Cost.'</td>
                            <td style="width: 16%;  padding-top: 2px; text-align: right; padding: 3px">'.OpportunityProductUtils::getCurrencyType() .$optpdt->Total_Direct_Cost->value.'</td>
                        </tr>';
                  }
            
             }
                $tableCreation .= '</tbody></table>';
                $tableCreation .= '<table style="margin-left: 20%; margin-top:2%;" border="0"
                                cellpadding="2" width="60%" text-align="right">
                        <tr>
                            <td rowspan="2" style="text-align:center; font-weight: bold;color:black;">Direct Cost</td>
                            <td style="text-align:right; font-weight: bold;color:black;">Total</td>
                            <td style="text-align:right;"></td>
                            <td style="text-align:right; font-weight: bold;color:black;">Suggested</td>
                            <td style="text-align:right; font-weight: bold;color:black;">Final</td>
                        </tr>
                        <tr>
                            <td style="text-align:right; color:black;">'.OpportunityProductUtils::getCurrencyType() .number_format($totalDirectCost,2).'</td>
                            <td style="text-align:right; font-weight: bold;color:black;">Price</td>
                            <td style="text-align:right; color:green;">'.OpportunityProductUtils::getCurrencyType() .number_format($suggestedPrice,2).'</td>
                            <td style="text-align:right; color:green;">'.OpportunityProductUtils::getCurrencyType() .number_format($opportunity->amount->value,2).'</td>
                        </tr>
                        <tr>
                            <td style="text-align:center; font-weight: bold;color:black;">MH</td>
                            <td style="text-align:right; color:black;">'.$totalMH.'</td>
                            <td style="text-align:right; font-weight: bold;color:black;">Rev/MH</td>
                            <td style="text-align:right; color:black;">'.OpportunityProductUtils::getCurrencyType() .number_format(($suggestedPrice/$totalMH),2).'</td>
                            <td style="text-align:right;">'.OpportunityProductUtils::getCurrencyType() .number_format(($opportunity->amount->value/$totalMH),2).'</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:right;"></td>
                            <td style="text-align:right; font-weight: bold;color:black;">Aggregate GPM%</td>
                            <td style="text-align:right; color:black;">'.number_format(((($suggestedPrice - $totalDirectCost)/$suggestedPrice)*100),2).' </td>
                            <td style="text-align:right; color:black;">'.number_format(((($opportunity->amount->value -$totalDirectCost )/$opportunity->amount->value)*100),2).'</td>
                        </tr>
                    </table>';
 		$tableCreation .= '</div><br/><br/><div style="background-color:#E0D1D1; color:black;padding:0.5%; margin-bottom:1%; font-weight:bold; font-size: 13px;">Charts</div>';
 -                $tableCreation .= OpportunityProductUtils::estimatorSummaryPiechartView($opportunityId);
 -                $tableCreation .= '<hr>';
        	  $tableCreation .= '</div>';
                $content .= $tableCreation;
            }
            $content .= '</tbody></table>
                <input value="" name="list-view-selectedIds" id="list-view-selectedIds" type="hidden">
        </div>';
            return $content;
	}

        protected function estimatorSummaryPiechartView($id) { 
            $l = new OpportunityProductsEstimatorSummaryChartView('OpportunityProductsEstimatorSummaryChartView',null,null);
          //  return $l->renderContent();
            return $l->setOpptId($id);
        }

    }
?>
