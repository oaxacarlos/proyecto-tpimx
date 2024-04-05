
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
    <div class="col-md-2">
      <button class="btn btn-primary" id="btn_go">GO</button>
    </div>
  </div>
</div>
<div>
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



<?php

$option = "";
unset($autocomplete);
$i = 0;
foreach ($var_customer_data as $row) {
  $value = $row['cust_no'] . " | " . $row['name'];
  $autocomplete[$i] = $value;
  $i++;
}

$js_array_autocomplete = json_encode($autocomplete);

?>

<script>
  var option = "<?php echo $option; ?>";
  var counter = 0;
  var autocomplete = <?php echo $js_array_autocomplete; ?>;
  var slscode = "<?php echo $var_sls_code; ?>";
  $(function() {
    $("#inp_search_cust").autocomplete({
      source: autocomplete
    });
  })
  var autocomplete = <?php echo $js_array_autocomplete; ?>;
  var slscode = "<?php echo $var_sls_code; ?>";


  function f_update_cust() {
    var cust = $("#inp_search_cust").val();
    cust = cust.split(" | ");

    $("#inp_cust_code").val(cust[0]);
    $("#inp_cust_name").val(cust[1]);

    $("#inp_search_cust").val("");
  }

  $("#btn_go").click(function() {
    var cust_code = $("#inp_cust_code").val();
    var cust_name = $("#inp_cust_name").val();
    var year = $("#datepicker_year").val();

    if (cust_code == "" || cust_name == "") {
      show_error("Customer Data not completed");
      return false;
    }
    gen_report_salesyear(cust_code, cust_name, year)
  })

  function gen_report_salesyear(cust_code, cust_name, year) {
    $("#report_salesreport_view").html("Loading, Please wait...");

    $.ajax({
      url: "<?php echo base_url(); ?>index.php/sales/report/sales_yeardata",
      type: 'post',
      dataType: 'html',
      data: {
        cust_code: cust_code,
        cust_name: cust_name,
        year: year,
      },
      success: function(respons) {
        $('#report_salesreport_view').fadeIn("5000");
        $("#report_salesreport_view").html(respons);
      }
    });
  }
</script>