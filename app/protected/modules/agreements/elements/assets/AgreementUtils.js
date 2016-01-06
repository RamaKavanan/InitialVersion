
function createNewAgreement() {
	if($("#Agreement_RecordType_value").val() != null && $("#Agreement_RecordType_value").val() != '') {
		//alert("Selected Ids"+$("#list-view-selectedIds").val());
		var recordType = $("#Agreement_RecordType_value").val();
		var url = '/app/index.php/agreements/default/projectType';
		if(recordType != null && recordType == 'Recurring Agreement' ) {
			url = '/app/index.php/agreements/default/recurringType';
		}
		window.location.href = url;
	} else {
		alert("Please select atleast one product");	
	}
}

