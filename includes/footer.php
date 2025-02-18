</div>
<?php if(!isset($page)): ?>
<footer class="main-footer sticky footer-type-1">
    <div class="footer-inner">
        <!-- Add your copyright text here -->
       	<div class="footer-text">
            &copy; Copyright <?php echo date('Y'); ?> 
            <strong><?php echo get_option('site_name'); ?></strong> All Rights Reserved 
        </div>
		 <!-- Go to Top Link, just add rel="go-top" to any link to add this functionality -->
        <div class="go-up">
            <a href="#" rel="go-top">
                <i class="fa-angle-up"></i>
            </a>
        </div>
    </div>
</footer>
<?php endif; ?>
	</div>
	<!--================== MainContent Area Ends Here ===========================-->
		</div>
		<!--********** Main Container End ***********-->

<script type="text/javascript" language="javascript" src="js/bootstrap.min.js"></script>
<script src="js/croppic.min.js"></script>
<script type="text/javascript" language="javascript" src="js/select2.js"></script>

<script type="text/javascript" language="javascript" src="js/jquery-ui-1.11.4.min.js"></script>
<script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>
<?php if(isset($page)): ?>
<script>
$("#productscontainer").on('click', '#prevpage', function(e){ 
	e.preventDefault();
	var page = $(this).attr("data-page");
	var cat_id = $("#product_cat_id").val();
	
	$.ajax({
	 data: {
	  	pn: page,
		category_id: cat_id
	 },
	 type: 'POST',
	 dataType: 'json',
	 url: 'includes/autocomplete.php',
	 beforeSend: function() {
    	$('#productscontainer').html("<img src='images/loading.gif' class='loader' width='200px' style='position:absolute;top:50%;left:50%;margin-top:-100px;margin-left:-100px;' />");
  	},
	 success: function(response) {
		   var products_data = response.dataproduct;
		   $('#productscontainer').html(products_data);
	   }
	});	
});

$("#productscontainer").on('click', '#nextpage', function(e){ 
	e.preventDefault();
	var page = $(this).attr("data-page");
	var cat_id = $("#product_cat_id").val();
	
	$.ajax({
	 data: {
	  	pn: page,
		category_id: cat_id
	 },
	 type: 'POST',
	 dataType: 'json',
	 url: 'includes/autocomplete.php',
	 beforeSend: function() {
    	$('#productscontainer').html("<img src='images/loading.gif' class='loader' width='200px' style='position:absolute;top:50%;left:50%;margin-top:-100px;margin-left:-100px;' />");
  	},
	 success: function(response) {
		   var products_data = response.dataproduct;
		   $('#productscontainer').html(products_data);
	   }
	});	
});


$("#product_cat_id").change(function(){ 
	var category_id = $(this).val();

	$.ajax({
	 data: {
	  	category_id: category_id
	 },
	 type: 'POST',
	 dataType: 'json',
	 url: 'includes/autocomplete.php',
	 beforeSend: function() {
    	$('#productscontainer').html("<img src='images/loading.gif' class='loader' width='200px' style='position:absolute;top:50%;left:50%;margin-top:-100px;margin-left:-100px;' />");
  	},
	 success: function(response) {
		   var products_data = response.dataproduct;
		   $('#productscontainer').html(products_data);
	   }
	});	
});
$(document).on('click', '.pos_product_id', function () {
	var product_id = $(this).attr("value");
	product_id = parseFloat(product_id);
    getProduct(product_id);
	//ajax stuff
    return false;	
});

$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
  	
  $(function() {
    $( "#to" ).autocomplete({
	      source: "includes/autocomplete.php",
	      minLength: 2,
		  select: function(event, ui) {
			  		var product_id = ui.item.id;
					var client_id = $('#client_id').val();
					//get product ends here.
					if(product_id == '') { 
						alert('Please select product.');
					} else if(client_id == '') { 
						alert('Please select client first. So prices can be taken from that client\'\s price level.');
					} else {
						getProduct(product_id);	 
					}
			   $(this).val("");
			   event.preventDefault();
  			}	
  	});	
  });
function Cashreceived() { 
	var getreceviedcash = $('#cash_received').val();
	var grandtotalval = $('.grandtotle').val();
	$('#print_cash_received').html(getreceviedcash);
	var balanceamount = getreceviedcash - grandtotalval;
	$('#print_remainginBalance').html(Math.round(balanceamount));
}
function update_total() { 
	var grand_total = 0;
	
	i = 1;	
	$('.itemtotal').each(function(i) {
        var total = $(this).val();
		
		total = parseFloat(total);
		
		grand_total = parseFloat(total+grand_total);
    });
	$('#grand_total').html(grand_total.toFixed(2));
	$('.grandtotle').val(grand_total.toFixed(2));
	//
	//Updating total Field.
	var items_total = 0;
	var taxes_total = 0;
	i = 1;
	$('.selling_price').each(function(i) {
        var item_total = $(this).val();
		var array_index = $(this).index('.selling_price');
		
		var quantity = $(".qty").get(array_index).value;
		var tax = $(".tax_rate").get(array_index).value;
		
		item_total = parseFloat(item_total)*parseFloat(quantity);
		items_total = parseFloat(items_total+item_total);
    	
		tax_total = parseFloat(tax)*parseFloat(quantity);
		taxes_total = parseFloat(taxes_total+tax_total);
	});
	$('.totalamount').html(items_total.toFixed(2));
	$('.taxamount').html(taxes_total.toFixed(2));
	$('.numberofitems').html($(".selling_price").length);
	
}//Update total function ends here.

function getProduct(product_id) {
	$.ajax({
	 data: {
	  	product_id: product_id,
	  	client_id: $("#client_id").val(),
		data_type: 'pos_data'
	 },
	 type: 'POST',
	 dataType: 'json',
	 url: 'includes/get_sale_data.php',
	 success: function(response) {
	   var product_name = response.product_name;
	   var product_price = response.product_price;
	   var tax = response.tax;
	   
	   var content_1 = "<tr class='item-row'><td><div class='delete-wpr'><input type='hidden' name='product_id[]' value='"+product_id+"'><a class='delme' href='javascript:;' title='Remove row'>X</a></div></td>";
	   var content_2 = "<td>"+product_name+"</td>";
	   var content_3 = "<td><input type='text' class='qty' name='qty[]' value='1'></td>";
	   
	   var content_4 = "<td><input type='text' readonly='readonly' class='tax_rate' name='tax_rate[]' value='"+tax+"'></td>";
	   
	   quantity = 1;
	   
	   var warehouse_id = <?php echo get_option($_SESSION['store_id'].'_default_warehouse'); ?>;
	   
	   var total = parseFloat(product_price)*parseFloat(quantity);
	   var tax = parseFloat(tax)*parseFloat(quantity);
	   var grand_total = total+tax;
	   
	   var content_5 = "<td><input type='text' onchange='update_total();'  class='selling_price' name='selling_price[]' value='"+product_price+"'></td><td><input type='text' class='itemtotal' readonly='readonly' value='"+grand_total+"' /><input type='hidden' name='warehouse_id[]' value='"+warehouse_id+"' /></td></tr>"; 
	   
	   $(".item-row:first").before(content_1+content_2+content_3+content_4+content_5);
	   
	   update_total();
	   }
	});
}
	$('#items').on('change', '.qty', function() { 
		var array_index = $(this).index('.qty');
		var product_price = $(".selling_price").get(array_index).value;
		var product_quantity = $(".qty").get(array_index).value;
		if(isNaN(product_quantity)) { 
			alert("Quantity needs to be a number!");
			$(".qty").get(array_index).value = 1;
			$(".qty").get(array_index).focus();
			return false;
		}
		var product_tax = $(".tax_rate").get(array_index).value;
		
		var total = parseFloat(product_price)*parseFloat(product_quantity);
	    var tax = parseFloat(product_tax)*parseFloat(product_quantity);
	    var grand_total = total+tax;
		
		$(".itemtotal").get(array_index).value = grand_total;
		
		update_total();
	});
	
	$('#items').on('change', '.selling_price', function() { 
		var array_index = $(this).index('.selling_price');
		var product_price = $(".selling_price").get(array_index).value;
		var product_quantity = $(".qty").get(array_index).value;
		if(isNaN(product_quantity)) { 
			alert("Quantity needs to be a number!");
			$(".qty").get(array_index).value = 1;
			$(".qty").get(array_index).focus();
			return false;
		}
		var product_tax = $(".tax_rate").get(array_index).value;
		
		var total = parseFloat(product_price)*parseFloat(product_quantity);
	    var tax = parseFloat(product_tax)*parseFloat(product_quantity);
	    var grand_total = total+tax;
		
		$(".itemtotal").get(array_index).value = grand_total;
		
		update_total();
	});
		
	$(document).ready(function(e) {  
	//delete Row.
	$('#items').on('click', '.delme', function() {
		   $(this).parents('.item-row').remove();
		    update_total();
		});
    });
	<?php if($_GET["si"] != ""){?>
	$(document).ready(function(e) {  
		    update_total();
    });
	<?php } ?>
</script>
<?php endif; ?>
<script type="text/javascript">
tinymce.init({
    selector: "textarea.tinyst",
	menubar : false,
	toolbar: "styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
	placeholder : String
 });
</script>
<script type="text/javascript">
	$(document).ready(function() {$(".autofill").select2(); });
</script>

<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('#wc_table').dataTable();
	} );
	function confirm_delete() { 
		var del = confirm('Do you really want to delete this record?');
		if(del == true) { 
			return true;
		} else { 
			return false;
		}
	}//delete_confirmation ends here.
</script>

<script type="text/javascript">
	$(function() {
		$(".datepick").datepicker({
			inline: true,
			dateFormat: 'yy-mm-dd',
		});
	});
	var croppicContaineroutputOptions = {
			uploadUrl:'includes/img_save_to_file.php',
			cropUrl:'includes/img_crop_to_file.php', 
			outputUrlId:'cropOutput',
			modal:false,
			loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> '
	}
	var cropContaineroutput = new Croppic('cropContaineroutput', croppicContaineroutputOptions);
</script>
</body>
</html>