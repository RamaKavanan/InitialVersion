/**
 * Copy the product template data for creation of product
 */
function copyProductTemplateDataForProduct(templateId, url)
{
    url = url + "?id=" + templateId;
    $.ajax(
        {
            type: 'GET',
            url: url,
            dataType: 'json',
            success: function(data)
                     {
                         $("#ProductCategoriesForm_ProductCategory_ids").tokenInput("clear");
                         $(data.categoryOutput).each(function(index)
                         {
                            $("#ProductCategoriesForm_ProductCategory_ids").tokenInput("add", {id: this.id, name: this.name});
                         });
                         $('#Product_type_value').val(data.productType);
                         $('#Product_priceFrequency_value').val(data.productPriceFrequency);
                         $('#Product_sellPrice_currency_id').val(data.productSellPriceCurrency);
                         $('#Product_sellPrice_value').val(data.productSellPriceValue);
                         $('#Product_name').val(data.productName);
                         if($("#Product_description").length > 0)
                         {
                            $('#Product_description').val(data.productDescription);
                         }
                     }
        }
    );
}

/**
 * Adds the product row to the product portlet on details view
 */
function addProductRowToPortletGridView(productTemplateId, url, relationAttributeName, relationModelId, uniquePortletPageId, errorInProcess)
{
    url = url + "&id=" + productTemplateId + "&relationModelId=" + relationModelId + "&relationAttributeName=" + relationAttributeName+"&redirect=true";
    $.ajax(
        {
            type: 'GET',
            url: url,
            beforeSend: function(xhr)
                       {
                           $('#modalContainer').html('');
                           $(this).makeLargeLoadingSpinner(true, '#modalContainer');
                       },
            success: function(dataOrHtml, textStatus, xmlReq)
                     {
                         $(this).processAjaxSuccessUpdateHtmlOrShowDataOnFailure(dataOrHtml, uniquePortletPageId);
                     },
            complete:function(XMLHttpRequest, textStatus)
                     {
                       $('#modalContainer').dialog('close');
                       //$('#product_opportunity_name').val('');
                       //$('#product_opportunity_id').val('');
                       //$('#product-configuration-form').hide('slow');
                       //juiPortlets.refresh();
                     },
            error:function(xhr, textStatus, errorThrown)
                  {
                      alert(errorInProcess);
                  }
        }
    );
}

jQuery('#list-view-rowSelector_all').live('click', function() {
    var checked = this.checked;

    //custom checkbox style
    if (this.checked){
        jQuery(this).parent().addClass('c_on');
    }
    else
    {
        jQuery(this).parent().removeClass('c_on');
    }

    jQuery("input[name='list-view-rowSelector\[\]']").each(function()
    {
        this.checked = checked;
        updateListViewSelectedIds('list-view', $(this).val(), checked);

        //custom checkbox style
        if (this.checked){
            jQuery(this).parent().addClass('c_on');
        }
        else
        {
            jQuery(this).parent().removeClass('c_on');
        }
    });
});

jQuery("input[name='list-view-rowSelector\[\]']").live('click', function() {
    jQuery('#list-view-rowSelector_all').attr( 'checked', jQuery("input[name='list-view-rowSelector\[\]']").length == jQuery("input[name='list-view-rowSelector\[\]']:checked").length);
    updateListViewSelectedIds('list-view', $(this).val(), $(this).attr('checked'));

    //custom checkbox style
    if ( jQuery('#list-view-rowSelector_all').attr( 'checked') === 'checked' ){
        jQuery('#list-view-rowSelector_all').parent().addClass('c_on');
    }
    else
    {
        jQuery('#list-view-rowSelector_all').parent().removeClass('c_on');
    }
    if ( this.checked )
    {
        jQuery(this).parent().addClass('c_on');
    }
    else
    {
        jQuery(this).parent().removeClass('c_on');
    }
});

function updateListViewSelectedIds(gridViewId, selectedId, selectedValue) {
    var array = new Array ();
    var processed = false;
    jQuery.each($('#' + gridViewId + "-selectedIds").val().split(','), function(i, value)
        {
            if(selectedId == value)
            {
                if(selectedValue)
                {
                    array.push(value);
                }
                processed = true;
            }
            else
            {
                if(value != '')
                {
                    array.push(value);
                }
            }
         }
     );
    if(!processed && selectedValue)
    {
        array.push(selectedId);
    }
    $('#' + gridViewId + "-selectedIds").val(array.toString());
}

function addProductInOpportunity(opportunityId) {
	if($("#list-view-selectedIds").val() != null && $("#list-view-selectedIds").val() != '') {
		//alert("Selected Ids"+$("#list-view-selectedIds").val());
		var ids=$("#list-view-selectedIds").val();
		
		var data = new Array();
		jQuery.each(jQuery("input[name='list-view-rowSelector\[\]']:checked"), function(i, value) {
			console.log(value.id);
			if($("#frequency_"+value.id.split('_')[1]) != null){
				data.push(value.value+':'+$("#quantity_"+value.id.split('_')[1]).val()+':'+$("#frequency_"+value.id.split('_')[1]).val());
			} else {
				data.push(value.value+':'+$("#quantity_"+value.id.split('_')[1]).val()+':');
			}
		});
		var url = '/app/index.php/opportunityProducts/default/AddOpportunityProducts?ids='+ids+'&data='+data.toString()+'&optId='+opportunityId;
		window.location.href = url;
	} else {
		alert("Please select atleast one product");	
	}
}

