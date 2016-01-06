<?php
    class CostbookUtils {

        public function updateAssemlblyProductStep2(Costbook $costbook, $detailIds, $model_id) {
            try {
                $costbook->assemblydetail = $detailIds;
                if(!$costbook->save()) {
                    throw new Exception('Exception While saving model');
                } else {
                    return 1;
                }
            } catch(Exception $ex) {
                echo $ex; die;
            }
	}

        function totalCostCalculation(Costbook $costbook, $ids) {
            try {
                if($costbook != null) {
                    if($ids != null) {
                        $assemblyDetails = explode(";", $ids);
                        $totalDirectCost = 0.0;
                        $totalManHour = 0.0;
                        $totalRatio = 0.0;

                        foreach($assemblyDetails as $assemblyDetail) {
                            $productCode = explode("|", $assemblyDetail);
                            $cb = Costbook::getByProductCode($productCode[1]);
                            $ratio = $productCode[2];
                            if($cb[0]->costofgoodssold->value == 'Labor') {
                                $totalDirectCost += (intval($cb[0]->laborCost) * $ratio) + (intval($cb[0]->burdenCost) * $ratio);	
                            } else if($cb[0]->costofgoodssold->value == 'Material'){
                                $totalDirectCost += $cb[0]->unitdirectcost * $ratio;
                            } else if($cb[0]->costofgoodssold->value == 'Equipment') {
                                $totalDirectCost += $cb[0]->unitdirectcost * $ratio;
                            } else if($cb[0]->costofgoodssold->value == 'Subcontractor') {
                                $totalDirectCost += $cb[0]->unitdirectcost * $ratio;
                            } else if($cb[0]->costofgoodssold->value == 'Other') {
                                $totalDirectCost += $cb[0]->unitdirectcost * $ratio;
                            }
                            $totalManHour += $ratio * $cb[0]->costperunit;
                            $totalRatio += $ratio;
			}
			$costbook->unitdirectcost = $totalDirectCost;
			$costbook->costperunit = $totalDirectCost;

			if(!$costbook->save()) {
                            throw new Exception('Exception While saving model');
                        } else {
                            return 1;
                        }
                    } else {
                        throw new Exception('Please select a product');	
                    }
                } else {
                    throw new Exception('Cost book should not be null');
                }
            } catch (Exception $ex) {		
                throw new Exception($ex);		
            }
	}


    }
?>
