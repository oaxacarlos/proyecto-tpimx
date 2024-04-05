<script>
// copy clipboard
var clipboard3 = new ClipboardJS('#copy_button_customer', {
  target: function() {
    return document.querySelector('#tbl_report_custreview');
  }
});
//--

</script>


<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Salesman Sales Detail
</div>

<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <div class="col-md-2">
      <input type='text' name='inp_search_cust' value="" id='inp_search_cust' class='required form-control' placeholder='search customer' onchange=f_update_cust()>
    </div>
    <div class="col-md-2">
      <input type='text' name='inp_cust_code' value="" id='inp_cust_code' class='required form-control' placeholder='customer no' disabled>
    </div>
    <div class="col-md-3">
      <input type='text' name='inp_cust_name' value="" id='inp_cust_name' class='required form-control' placeholder='customer name' disabled>
    </div>
    <div class="col-md-1">
      <input type='text' name='datepicker_year' value="<?php echo date("Y"); ?>" id='datepicker_year' class='required form-control' placeholder='Year'>
    </div>
    <div class="col-md-1">
      <select id="inp_type" class='required form-control'>
        <option value="1">Quantity</option>
        <option value="2">Amount</option>
      </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary" id="btn_go">GO</button>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="salesreport-tab" data-toggle="tab" href="#salesreport" role="tab" aria-controls="salesreport" aria-selected="true">SalesReport</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="customerreport-tab" data-toggle="tab" href="#customerreport" role="tab" aria-controls="customerreport" aria-selected="true">CustomerAllReport</a>
    </li>
  </ul>

  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="salesreport" role="tabpanel" aria-labelledby="salesreport-tab">
      <div class="container-fluid" style="margin-top:20px;">
          <div id="report_fill_rate_view" style="margin-top:20px;"></div>
          <div id="report_sales_item_cat_view" style="margin-top:20px;"></div>
          <div id="report_salesreport_view"></div>
      </div>
    </div>
    <div class="tab-pane fade show" id="customerreport" role="tabpanel" aria-labelledby="customerreport-tab">
      <div class="container-fluid" style="margin-top:20px;">
          <div id="report_customerreport_view"></div>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="slscode" value="<?php echo $var_sls_code; ?>">

<?php

$option="";
unset($autocomplete);
$i=0;
foreach($var_customer_data as $row){
    $value = $row['cust_no']." | ".$row['name'];
    $autocomplete[$i] = $value;
    $i++;
}

$js_array_autocomplete = json_encode($autocomplete);

?>

<script>

var option = "<?php echo $option; ?>";
var counter=0;
var autocomplete = <?php echo $js_array_autocomplete; ?>;
var slscode = "<?php echo $var_sls_code; ?>";

$( function() {
  $( "#inp_search_cust").autocomplete({
    source: autocomplete
  });
})
//---

function f_update_cust(){
    var cust = $("#inp_search_cust").val();
    cust = cust.split(" | ");

    $("#inp_cust_code").val(cust[0]);
    $("#inp_cust_name").val(cust[1]);

    $("#inp_search_cust").val("");
}
//---

$("#btn_go").click(function(){
    var cust_code = $("#inp_cust_code").val();
    var cust_name = $("#inp_cust_name").val();
    var year = $("#datepicker_year").val();
    var type = $("#inp_type").val();
    var slscode = $("#slscode").val();

    if(cust_code=="" || cust_name==""){
        show_error("Customer Data not completed");
        return false;
    }

    gen_report_customerreport(slscode,year, type);
    //gen_report_customer_fill_rate(year, cust_code, type);
    //gen_report_customer_sales_item_cat(cust_code, cust_name, year, type);
    //gen_report_salesreport(cust_code, cust_name, year, type);

    gen_report_customercatreport(slscode,year, type);

})
//---

function gen_report_salesreport(cust_code, cust_name, year, type){
    $("#report_salesreport_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesman_salesreport_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {cust_code:cust_code,cust_name:cust_name, year:year, type:type},
        success   :  function(respons){
            $('#report_salesreport_view').fadeIn("5000");
            $("#report_salesreport_view").html(respons);
        }
    });
}
//----

function gen_report_customerreport(sls_code, year, type){
    $("#report_customerreport_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesman_customerreport_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {sls_code:sls_code, year:year, type:type},
        success   :  function(respons){
            $('#report_customerreport_view').fadeIn("5000");
            $("#report_customerreport_view").html(respons);
        }
    });
}
//----

function gen_report_customer_fill_rate(year, customer, type){
  $("#report_fill_rate_view").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/customer/fill_rate_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {cust_code:customer,year:year, type:type},
      success   :  function(respons){
        $('#report_fill_rate_view').fadeIn("5000");
        $("#report_fill_rate_view").html(respons);
      }
  });
}
//--

function gen_report_customer_sales_item_cat(cust_code, cust_name, year, type){
    $("#report_sales_item_cat_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/customer/sales_item_cat",
        type      : 'post',
        dataType  : 'html',
        data      :  {cust_code:cust_code,cust_name:cust_name, year:year, type:type},
        success   :  function(respons){
          $('#report_sales_item_cat_view').fadeIn("5000");
          $("#report_sales_item_cat_view").html(respons);
        }
    });
}
//---

</script>
