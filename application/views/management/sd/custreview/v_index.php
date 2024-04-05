<script>
// copy clipboard
var clipboard3 = new ClipboardJS('#copy_button_custreview', {
  target: function() {
    return document.querySelector('#tbl_report_custreview');
  }
});
//--

</script>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Customer Review
</div>

<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <!--<div class="col-md-2">
      <input type='text' name='inp_search_cust' value="" id='inp_search_cust' class='required form-control' placeholder='search customer' onchange=f_update_cust()>
    </div>
    <div class="col-md-2">
      <input type='text' name='inp_cust_code' value="" id='inp_cust_code' class='required form-control' placeholder='customer no' disabled>
    </div>
    <div class="col-md-3">
      <input type='text' name='inp_cust_name' value="" id='inp_cust_name' class='required form-control' placeholder='customer name' disabled>
    </div>-->
    <div class="col-md-1">
      <input type='text' name='datepicker_year' value="<?php echo date("Y"); ?>" id='datepicker_year' class='required form-control' placeholder='Year'>
    </div>
    <div class="col-md-1">
      <select id="inp_type" class='required form-control'>
        <option value="2">Amount</option>
        <option value="1">Quantity</option>
      </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary" id="btn_go">GO</button>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:30px;">
  <?php echo load_progress("progress"); ?>
  <div id="report_view"></div>
</div>

<?php
/*
$option="";
unset($autocomplete);
$i=0;
foreach($var_customer_data as $row){
    $value = $row['cust_no']." | ".$row['name'];
    $autocomplete[$i] = $value;
    $i++;
}

$js_array_autocomplete = json_encode($autocomplete);
*/
?>

<script>
/*
var option = "<?php //echo $option; ?>";
var counter=0;
var autocomplete = <?php //echo $js_array_autocomplete; ?>;

$( function() {
  $( "#inp_search_cust").autocomplete({
    source: autocomplete
  });
})
//--

function f_update_cust(){
    var cust = $("#inp_search_cust").val();
    cust = cust.split(" | ");

    $("#inp_cust_code").val(cust[0]);
    $("#inp_cust_name").val(cust[1]);

    $("#inp_search_cust").val("");
}
//---
*/

$("#btn_go").click(function(){
    //var cust_code = $("#inp_cust_code").val();
    //var cust_name = $("#inp_cust_name").val();
    var year = $("#datepicker_year").val();
    var type = $("#inp_type").val();

  /*  if(cust_code=="" || cust_name==""){
        show_error("Customer Data not completed");
        return false;
    }*/

    $("#report_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/management/sd/custreview_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, type:type},
        success   :  function(respons){
            $('#report_view').fadeIn("5000");
            $("#report_view").html(respons);
        }
    });


})
//---

</script>
