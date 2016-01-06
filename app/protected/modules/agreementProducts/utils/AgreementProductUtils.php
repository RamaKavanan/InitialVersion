<?php

    /**
     * Class utilized by opportunity product selection
     * 
     * @author Ramachandran.K 
     */
    class AgreementProductUtils   {

        public function makeCostBookProductSelection($datas, $opportunityId) {

		$content = AgreementProductUtils::appendButton($opportunityId);

		$content .= '<hr/><div class="cgrid-view type-opportunityProducts" id="list-view">
				<div class="summary">5 result(s)</div>
				<div class="items-wrapper">
				<table class="items">
				<thead>
				<tr>
				<th class="checkbox-column" id="list-view-rowSelector"><label class="hasCheckBox"><input value="1" name="list-view-rowSelector_all" id="list-view-rowSelector_all" type="checkbox"></label></th><th id="list-view_c1">Product Code</th><th id="list-view_c2"><a class="sort-link" href="/app/index.php/opportunityProducts/default/index?OpportunityProduct_sort=name">Product Name</a></th><th id="list-view_c3">Unit of Measure</th>
				<th id="list-view_c4">Unit Direct Cost</th><th id="list-view_c5">Quantity</th><th id="list-view_c6">Frequency</th><th id="list-view_c7">Category</th>
				</tr>
				</thead>
				<tbody>';
		$count = 0;
		$content1='';
		foreach($datas as $data) {
		$content .=  '<tr class="odd"><td class="checkbox-column"><label class="hasCheckBox"><input value="'. $data -> id .'" id="list-view-rowSelector_'.$count.'" name="list-view-rowSelector[]" type="checkbox"></label></td><td>'. $data -> productcode .'</td><td>'. $data -> productkey .'</td><td>'. $data -> unitofmeasure .'</td><td>'. $data -> unitdirectcost .'</td><td><input type="text" id="quantity_'. $count .'" value="1.0"></td><td><input type="text" id="frequency_'. $count .'" value="1"></td><td>'. $data -> category .'</td></tr>';
		$count++;	
	
		}
		$content .= '</tbody></table><input value="" name="list-view-selectedIds" id="list-view-selectedIds" type="hidden"></div></div>';
		return $content;
	}

	public function appendButton($opportunityId) {
		$content = '<div class="view-toolbar-container clearfix"><nav class="clearfix"><div class="default-button" id="CreateMenuActionElement--yt1"><a href="#" onclick="javascript:addProductInOpportunity(\''.$opportunityId.'\');" class="button-action"><i class="icon-create"></i><span class="button-label">Create</span></a></div>	</nav></div>';
		return $content;
	}

	public function addAgreementProductsCalculation(Costbook $costbook,$quantity,$frequency,$optId) {
		$tQty = 0;
		$opportunityProduct = new OpportunityProduct();
                $opportunityProduct->Quantity = (float) $quantity;
                $opportunityProduct->Frequency = (float) $frequency; 
                $opportunityProduct->name = 'Love Name - '.$costbook->id;	
		
		//$opportunityProduct->Product = $costbook;
		$tQty = (float)$quantity;
		if($opportunityProduct->Frequency > 0) {
			$tQty *= $opportunityProduct->Frequency;
		}

		//Labor Product calculation
		if($costbook->costofgoodssold == 'Labor') {
			$currencies                       = Currency::getAll();
			$opportunityProduct->Total_MHR = round($costbook->costperunit * $tQty);
			if($costbook->departmentreference != null) {
				$deptReference = DepartmentReference::GetById(intval($costbook->departmentreference));
				$burdenCost                    = new CurrencyValue();
				$burdenCost->value             = round(($deptReference->burdonCost * $tQty),2);
				$burdenCost->currency          = $currencies[0];
				$opportunityProduct->Burden_Cost = $burdenCost;
				$laborCost                    = new CurrencyValue();
				$laborCost->value             = round(($deptReference->laborCost * $tQty),2);
				$laborCost->currency          = $currencies[0];
				$opportunityProduct->Labor_Cost = $laborCost;
			}
		}
		$opportunityProduct->Category_GPM = 40;
		$totalDirectCost = new CurrencyValue();
		$totalDirectCost->value             = round(($opportunityProduct->Labor_Cost->value+$opportunityProduct->Burden_Cost->value),2);
		$totalDirectCost->currency          = $currencies[0];
		$opportunityProduct->Total_Direct_Cost = $totalDirectCost;
		$finalCost = new CurrencyValue();
		$finalCost->value             =  round(($opportunityProduct->Total_Direct_Cost->value/(1-($opportunityProduct->Category_GPM/100))),2);
		$finalCost->currency          =  $currencies[0];
		$opportunityProduct->Final_Cost = $finalCost;
		$opportunityProduct->opportunity = $opportunity = Opportunity::GetById(intval($optId));
		$opportunityProduct->save();
	}
    }
?>






