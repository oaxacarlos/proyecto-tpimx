<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Customer Selling Price
</div>

<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <div class="col-md-2">
      <span class="badge badge-primary">Customer No *</span>
      <input type='text' name='inp_cust_code' value="" id='inp_cust_code' class='required form-control' placeholder='customer no'>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <div class="col-md-2">
      <span class="badge badge-primary">Item No 1 *</span>
      <input type='text' name='inp_item_no' value="" id='inp_item_no' class='required form-control' placeholder='Item No'>
    </div>
    <div class="col-md-2">
      <span class="badge badge-primary">Item No 2</span>
      <input type='text' name='inp_item_no2' value="" id='inp_item_no2' class='required form-control' placeholder='Item No'>
    </div>
    <div class="col-md-2">
      <span class="badge badge-primary">Item No 3</span>
      <input type='text' name='inp_item_no3' value="" id='inp_item_no3' class='required form-control' placeholder='Item No'>
    </div>
    <div class="col-md-2">
      <span class="badge badge-primary">Item No 4</span>
      <input type='text' name='inp_item_no4' value="" id='inp_item_no4' class='required form-control' placeholder='Item No'>
    </div>
    <div class="col-md-2">
      <span class="badge badge-primary">Item No 5</span>
      <input type='text' name='inp_item_no5' value="" id='inp_item_no5' class='required form-control' placeholder='Item No'>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary" id="btn_go" style="margin-top:18px;">GO</button>
    </div>
  </div>
</div>



<div class="container-fluid" style="margin-top:20px;">
    <div id="report_selling_price"></div>
    <div id="report_selling_price2"></div>
    <div id="report_selling_price3"></div>
    <div id="report_selling_price4"></div>
    <div id="report_selling_price5"></div>
</div>

<?php

//-- customer
unset($autocomplete);
$i=0;
foreach($var_customer_data as $row){
    $value = $row['cust_no']."|".$row['name'];
    $autocomplete[$i] = $value;
    $i++;
}

$js_array_autocomplete = json_encode($autocomplete);
//--

//-- item
unset($autocomplete_item);
$i=0;
foreach($var_item_data as $row){
    $value = $row['code']."|".$row['name'];
    $autocomplete_item[$i] = $value;
    $i++;
}

$js_array_autocomplete_item = json_encode($autocomplete_item);
//---

?>

<script>

var autocomplete = <?php echo $js_array_autocomplete; ?>;
var autocomplete_item = <?php echo $js_array_autocomplete_item; ?>;

$( function() {
  $( "#inp_cust_code").autocomplete({
    source: autocomplete
  });

  $( "#inp_item_no").autocomplete({ source: autocomplete_item });
  $( "#inp_item_no2").autocomplete({ source: autocomplete_item });
  $( "#inp_item_no3").autocomplete({ source: autocomplete_item });
  $( "#inp_item_no4").autocomplete({ source: autocomplete_item });
  $( "#inp_item_no5").autocomplete({ source: autocomplete_item });
})
//---

    $("#btn_go").click(function(){

        var cust_no = $("#inp_cust_code").val();
        var item_no = $("#inp_item_no").val();
        var item_no2 = $("#inp_item_no2").val();
        var item_no3 = $("#inp_item_no3").val();
        var item_no4 = $("#inp_item_no4").val();
        var item_no5 = $("#inp_item_no5").val();

        if(cust_no == ""){
            show_error("You haven't fill Customer No");
            return false;
        }

        if(item_no == ""){
            show_error("You haven't fill Item No");
            return false;
        }
        get_price(cust_no, item_no, "#report_selling_price"); // item 2

        // item 2
        if(item_no2 != "") get_price(cust_no, item_no2, "#report_selling_price2");
        else $("#report_selling_price2").html("");

        // item 3
        if(item_no3 != "") get_price(cust_no, item_no3, "#report_selling_price3");
        else $("#report_selling_price3").html("");

        // item 4
        if(item_no4 != "") get_price(cust_no, item_no4, "#report_selling_price4");
        else $("#report_selling_price4").html("");

        // item 5
        if(item_no5 != "") get_price(cust_no, item_no5, "#report_selling_price5");
        else $("#report_selling_price5").html("");
    })
    //---

    function get_price(cust_no, item_no, view){
        // process
        $(view).html("Loading, Please Wait...");

        cust_no_temp = cust_no.split("|");
        cust_no = cust_no_temp[0];

        item_no_temp = item_no.split("|");
        item_no = item_no_temp[0];

        $.ajax({
            url       : "<?php echo base_url();?>index.php/sales/report/sellingprice_data",
            type      : 'post',
            dataType  : 'html',
            data      :  {cust_no:cust_no, item_code:item_no},
            success   :  function(respons){
                $(view).fadeIn("5000");
                $(view).html(respons);
            }
        });
    }
</script>
