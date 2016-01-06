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

/**
 * Script For Make DropDown Category In Opportunities Add Product Page By Thamodaran
 */
function makeDropDownCategory(data, count){
    element = '<select id="Category_'+count+'" style="width:75%;">';
        for(i=0; i < data.length; i++){
            element += '<option value="'+data[i]+'">'+data[i]+'</option>';
        }
    element += '</select>';
    return element;
}


/**
 * Script For adding costbook product to Opportunities 
 * @author Thamodaran   
 */
function addProductInOpportunity(opportunityId, url) {
   if(url.id != "GoBack"){
       if($("#list-view-selectedIds").val() != null && $("#list-view-selectedIds").val() != '' && $("#list-view-selectedIds").val() != undefined) {
            var ids=$("#list-view-selectedIds").val();
            var arr = ids.split(',');
            $("#selectedProductCnt").val(arr.length);
            addProductJsonObj = [];
            addProductItem = {};
            jQuery.each(jQuery("input[name='list-view-rowSelector\[\]']:checked"), function(i, value) {
                if($("#frequency_"+value.id.split('_')[1]) != null && $("#frequency_"+value.id.split('_')[1]).val() != undefined){
                    addProductItem ["costBookId"] = value.value;
                    addProductItem ["add_Quantity"] = $("#quantity_"+value.id.split('_')[1]).val();
                    addProductItem ["add_Frequency"] = $("#frequency_"+value.id.split('_')[1]).val();
                    addProductItem ["add_Category"] = $("#Category_"+value.id.split('_')[1]).val();
                    addProductJsonObj.push(addProductItem);
                    addProductItem = {};
                } else {
                    addProductItem ["costBookId"] = value.value;
                    addProductItem ["add_Quantity"] = $("#quantity_"+value.id.split('_')[1]).val();
                    addProductItem ["add_Frequency"] = '';
                    addProductItem ["add_Category"] = $("#Category_"+value.id.split('_')[1]).val();
                    addProductJsonObj.push(addProductItem);
                    addProductItem = {};
                }
            });
            if(url.id == 'Save' || url.id == 'saveAndMore'){
                url = '/app/index.php/opportunityProducts/default/AddOpportunityProducts?ids='+ids+'&addJsonObj='+JSON.stringify(addProductJsonObj)+'&optId='+opportunityId+'&urlId='+url.id;
            }  
            $.ajax({
                url : url,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if(data != 'saveAndMore'){                
                         var url = '/app/index.php/opportunities/default/details?id='+opportunityId;
                         window.location.href = url;
                        }
                    else{
                        var url = '/app/index.php/opportunityProducts/default/AddProductsInOpportunity?optId='+opportunityId;
                        window.location.href = url;
                    }
                },
                error: function(data) {
                   console.log(data);
                }
            });
       }else {
                alert("Please select atleast one product");	
            }
   }else{
            var url = '/app/index.php/opportunities/default/details?id='+opportunityId;
            window.location.href = url;
    }
}




/**
 * Script for update quantity and frequency value in Opportunities Add Product Page By Thamodaran
 */
function updateSelectedProducts(opportunityId) {
    $("#showresults").text("Updating...!");
    var cnt = $("#Selected_Products_Ids").val();
    jsonObj = [];
    item = {};
    for(var i=0; i < cnt; i++) {
        if($("#updateFrequency_"+i).val() != null && $("#updateFrequency_"+i).val() != undefined){
            item ["product_ids"] = $('#list_View_Producted_SelectedIds_'+i).val();
            item ["Quantity"] = $('#updateQuantity_'+i).val();
            item ["Frequency"] = $('#updateFrequency_'+i).val();
            jsonObj.push(item);
            item = {};
            //console.log(jsonObj);
        } else {
            item ["product_ids"] = $('#list_View_Producted_SelectedIds_'+i).val();
            item ["Quantity"] = $('#updateQuantity_'+i).val();
            item ["Frequency"] = '';
            jsonObj.push(item);
            item = {};
        }
    }
    url = '/app/index.php/opportunityProducts/default/updateOpportunityProducts?jsonObj='+JSON.stringify(jsonObj)+'&optId='+opportunityId;
    $.ajax({
        type: 'GET',
        url: url,
        dataType: 'json',
        success: function(data) {
            console.log(data);
            var url = '/app/index.php/opportunityProducts/default/AddProductsInOpportunity?optId='+opportunityId;
            window.location.href = url;
            $("#showresults").text("Updated");
            setTimeout("$('#showresults').fadeIn('slow').hide();", 2000);
        },
        error: function(data) {
            console.log(data);
        }
    });
}


/**
 * Script for search products in Opportunities Add Product Page By Thamodaran
 */
function searchProducts(opportunityId) {
    $( "#searchProducts" ).focus();
    if(opportunityId != null){
    var url = '/app/index.php/opportunityProducts/default/GetAddProductSearch?category='+$('#oppt_AddProductcategory_value').val()+'&costOfGoods='+ $('#oppt_AddProductcostofgoodssold_value').val()+'&addProductoptId='+ opportunityId;
    $.ajax({
        url : url,
        type : 'GET',
        dataType: 'json',
        success : function(data)
        {
            var appendText = '';
            if($("#recordType_Ids").val() != 'Recurring Final'){
                appendText += '<table class="items selected_products_table"><div style="background-color:#E0D1D1;  color:black; padding:0.5%; font-weight:bold;"> Choose Products </div> <thead><tr>  <th class="checkbox-column" id="list-view-rowSelector">  <label class="hasCheckBox">  <input value="1" name="list-view-rowSelector_all" id="list-view-rowSelector_all" type="checkbox">   </label>  </th>  <th id="list-view_c1">  Product Code  </th>  <th id="list-view_c2">  <a class="sort-link" href="/app/index.php/opportunityProducts/default/index?pportunityProduct_sort=name">  Product Name  </a>  </th>  <th id="list-view_c3"> Unit of Measure </th> <th id="list-view_c4"> Unit Direct Cost </th> <th id="list-view_c5"> Quantity </th> <th id="list-view_c7"> Category </th> </tr> </thead>'; 
                var items = {};
                var categoryDatas = [];
                var categoryData = [];
                
                for(var i =0; i < data.length; i++) {
                    if(items[[data[i].productcode]] == undefined){
                       items[[data[i].productcode]] = [];
                    }
                    var productcode = data[i].productcode;
                    items[productcode].push(data[i]);
                }
                var counter=0;
                $.each( items, function( productKey, productValue ) {
                   $(this).data('serial', counter++);
                   
                    $.each( productValue, function( key, value ){
                        categoryDatas.push(value.Category);
                    });
                    var categoryRes = makeDropDownCategory(categoryDatas, counter);
                    categoryDatas = [];
                    appendText += "<tr id='"+productKey+"'>  <td class='checkbox-column'>   <label class='hasCheckBox'>  <input id='list-view-rowSelector_"+counter+"' type='checkbox' name='list-view-rowSelector[]' value='"+productValue[0].id+"'/>    </label>  </td>     <td>"+productValue[0].productcode+" </td>  <td>"+productValue[0].productname+"</td>   <td>"+productValue[0].UnitOfMeasure+"</td>  <td>"+productValue[0].unitdirectcost+"</td> <td><input type='text' id='quantity_"+counter+"' value='1.0'/></td> <td> "+categoryRes+" </td><input value='' name = 'list-view-selectedIds' id = 'list-view-selectedIds' type = 'hidden'></td></tr>";
                });
            }
            else{
                appendText += '<table class="items selected_products_table"><div style="background-color:#E0D1D1;  color:black; padding:0.5%; font-weight:bold;"> Choose Products </div> <tbody><tr>  <th class="checkbox-column" id="list-view-rowSelector">  <label class="hasCheckBox">  <input value="1" name="list-view-rowSelector_all" id="list-view-rowSelector_all" type="checkbox">  </label>  </th>  <th id="list-view_c1">  Product Code  </th>  <th id="list-view_c2">  <a class="sort-link" href="/app/index.php/opportunityProducts/default/index?pportunityProduct_sort=name">  Product Name  </a>  </th>  <th id="list-view_c3"> Unit of Measure </th> <th id="list-view_c4"> Unit Direct Cost </th> <th id="list-view_c5"> Quantity </th> <th id="list-view_c5"> Frequency </th> <th id="list-view_c7"> Category </th> </tr> </thead>'; 
                var items = {};
                var categoryDatas = [];
                var categoryData = [];
                for(var i =0; i < data.length; i++) {
                    if(items[[data[i].productcode]] == undefined){
                       items[[data[i].productcode]] = [];
                    }
                    var productcode = data[i].productcode;
                    items[productcode].push(data[i]);
                }
                var counter=0;
                $.each( items, function( productKey, productValue ) {
                    $(this).data('serial', counter++);
                    $.each( productValue, function( key, value ){
                        categoryDatas.push(value.Category);
                    });
                    var categoryRes = makeDropDownCategory(categoryDatas, counter);
                    categoryDatas = [];
                    appendText += "<tr id='"+productKey+"'>  <td class='checkbox-column'>   <label class='hasCheckBox'>  <input id='list-view-rowSelector_"+counter+"' type='checkbox' name='list-view-rowSelector[]' value='"+productValue[0].id+"'/>    </label>  </td>     <td>"+productValue[0].productcode+" </td>  <td>"+productValue[0].productname+"</td>   <td>"+productValue[0].UnitOfMeasure+"</td>  <td>"+productValue[0].unitdirectcost+"</td> <td><input type='text' id='quantity_"+counter+"' value='1.0'/> <td><input type='text' id='frequency_"+counter+"' value='1'/></td> </td> <td> "+categoryRes+" </td><input value='' name = 'list-view-selectedIds' id = 'list-view-selectedIds' type = 'hidden'></td></tr>";
                });        
            }
            appendText += '</table> </div></div></div>';
            $('#searchProducts').html(appendText);
        },
        error : function()
        {
            alert("No searched Products");
        }
    });
    }
}
