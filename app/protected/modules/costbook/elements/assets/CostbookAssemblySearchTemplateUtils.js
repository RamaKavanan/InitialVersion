/**
 * Copy the product template data for creation of product
 */

var cnt = 0;
function addProductsInAssembly(productcode, inputid)
{
    if( $("#currAssemblyCnt").val() != undefined) {
        currCount = $('#currAssemblyCnt').val();
    } else if( $("#currAssemblyCnt").val() == undefined) {
        var currCount = $('input[name*="detailRatio"]').length;
    }

    var cnt = $('input[name*="detailRatio"]').length;

    var uom = 'uom';
    var appendText = '';

    if( $("#ratio"+inputid).val() == '0.0' ) {
        alert('Ratio must be greater than 0');
        $("#ratio"+inputid).focus();
        return false;
    } else {
        $("#row_"+inputid).hide();
        var url = '/app/index.php/costbook/default/getDataByProductCode?productcode='+productcode;
        appendText += '';

        $.ajax(
        {
            type: 'GET',
            url: url,
            dataType: 'json',
            success: function(data) {
                var results = data.split('$##$');
                appendText += '<tr><td width="15%"><label id="detailRow_'+currCount+'">'+results[1]+'<input type="hidden" id="hidProductCode'+currCount+'" value="'+results[1]+'" /></label></td><td width=30%>'+results[0]+'</td><td width="15%"><input type="text" id="detail_ratio'+currCount+'" name="detailRatio" value="" maxlength="4" style="width:120px;" /></td><td width="20%">'+results[2]+'</td><td width="20%">per '+$('#hidUOM').val()+'</td><input type="hidden" id="currAssemblyCnt" name="currAssemblyCnt" value="" /></tr>';
                $('#detail_products').append(appendText);
                $("#detail_ratio"+currCount).val($("#ratio"+inputid).val());
                $("#currAssemblyCnt").val(parseInt(currCount)+parseInt(1));
                currCount++;
            }
        }
        );
    }
}

function saveAssemblyStep2() {
    if( $("input[name=detailRatio]").val() == undefined ) {
        alert('Please add atleast one product');
        return false;
    } else {
        var cnt = $('input[name*="detailRatio"]').length;
	var product_code = [];
	var ratio = [];

	var str_pc = '';
	var str_ratio = '';

	for (var i=0; i < cnt; i++) {
            product_code.push($('#hidProductCode'+i).val());
            ratio.push($('#detail_ratio'+i).val());

            str_pc += 'GICRM|'+$('#hidProductCode'+i).val()+'|'+$('#detail_ratio'+i).val()+';'; 
	}
	str_pc = str_pc.substring(0, str_pc.length - 1);

	var url = '/app/index.php/costbook/default/saveAssemblyStep2?ids='+str_pc+'&model_id='+$("#hidModelId").val();

	$.ajax(
        {
            type: 'GET',
            url: url,
            success: function(data) { 
                if(data == 1) {
                    window.location.href = '/app/index.php/costbook/default/AssemblyStep3?id='+$("#hidModelId").val();
                } else {
                    //alert('Error in Adding')    
                    return false;
                }    
            },
            failure: function() {
                console.log('fail');
                return false;
            }
        }
        );
    }
}
