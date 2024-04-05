<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'Stock-Inventory'
          }
        ],
    });

    $("#datepicker_from").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $("#datepicker_to").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $.datetimepicker.setLocale('en');
});
</script>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Invoices Detail
</div>

<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <div class="col-md-2">
      <span class="badge badge-primary">From *</span>
      <input type='text' name='datepicker_check' value="<?php echo date("Y-m-01"); ?>" id='datepicker_from' class='required form-control' placeholder='Period From'>
    </div>
    <div class="col-md-2">
      <span class="badge badge-primary">To *</span>
      <input type='text' name='datepicker_check' value="<?php echo date("Y-m-d"); ?>" id='datepicker_to' class='required form-control' placeholder='Period To'>
    </div>
    <div class="col-md-2">
      <span class="badge badge-primary">DOC TYPE *</span>
      <select id="doc_type" name="doc_type" class='required form-control'>
        <option value="invc">Invoices</option>
        <!--<option value="cm">Credit Note</option>
        <option value="all">All</option>-->
      </select>
    </div>
    <div class="col-md-2">
      <span class="badge badge-primary">Customer No *</span>
      <input type='text' name='inp_cust_code' value="" id='inp_cust_code' class='required form-control' placeholder='customer no'>
    </div>
    <div class="col-md-2">
      <button class="btn btn-primary" id="btn_go" style="margin-top:18px;">GO</button>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:30px;">
  <?php echo load_progress("progress"); ?>
  <div id="report_view"></div>
</div>

<?php

//-- customer
unset($autocomplete);
$i=0;

if($var_user == ""){
    $value = "ALL|ALL CUSTOMER";
    $autocomplete[$i] = $value;
    $i++;
}

foreach($var_customer as $row){
    $value = $row['cust_no']."|".$row['name'];
    $autocomplete[$i] = $value;
    $i++;
}

$js_array_autocomplete = json_encode($autocomplete);
//--

?>

<script>

var autocomplete = <?php echo $js_array_autocomplete; ?>;

$( function() {
  $( "#inp_cust_code").autocomplete({ source: autocomplete });

})
//---

$("#btn_go").click(function(){

    var cust_no = $("#inp_cust_code").val();
    var from = $("#datepicker_from").val();
    var to = $("#datepicker_to").val();
    var doc_type = $("#doc_type").val();

    if(from == ""){
      show_error("You haven't fill DATE FROM");
      return false;
    }

    if(to == ""){
      show_error("You haven't fill DATE TO");
      return false;
    }

    if(from > to){
      show_error("FECHA DE no permitir mayor que FECHA HASTA");
      return false;
    }

    if(cust_no == ""){
        show_error("You haven't fill Customer No");
        return false;
    }

    $("#report_view").html(progressbar_dynamic());

    cust_no_temp = cust_no.split("|");
    cust_no = cust_no_temp[0];

    $.ajax({
        url       : "<?php echo base_url();?>index.php/management/sd/invoicesdetail_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {cust_no:cust_no, from:from, to:to, doc_type:doc_type},
        success   :  function(respons){
            $("#report_view").fadeIn("5000");
            $("#report_view").html(respons);
        }
    });

})
//---

</script>
