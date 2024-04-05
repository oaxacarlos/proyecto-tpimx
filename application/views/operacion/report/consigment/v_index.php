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
      Consigment
</div>



<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <div class="col-md-2">
      <input type='text' name='datepicker_from' value="<?php echo date("Y-m-01"); ?>" id='datepicker_from' class='required form-control' placeholder='Period From'>
    </div>
    <div class="col-md-2">
      <input type='text' name='datepicker_to' value="<?php echo date("Y-m-d"); ?>" id='datepicker_to' class='required form-control' placeholder='Period To'>
    </div>
    <div class="col-md-2">
      <select id="inp_consign" class="form-control">
          <option value=''>-</option>
          <option value='MX_CSG_MTK'>Mecanica Tek</option>
          <option value='MX_CSG_SGM'>Sigma</option>
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

<script>

$("#btn_go").click(function(){
    var date_from = $("#datepicker_from").val();
    var date_to = $("#datepicker_to").val();
    var consign = $("#inp_consign").val();

    if(consign == ""){
        show_error("Consigment must be choosen");
        return false;
    }
    else if(check_from_to(date_from,date_to)){
        $("#report_view").hide();
        $('#progress').show();

        $.ajax({
            url       : "<?php echo base_url();?>index.php/operacion/report/get_consigment_data",
            type      : 'post',
            dataType  : 'html',
            data      :  {date_from:date_from, date_to:date_to,consign:consign},
            success   :  function(respons){
                $('#progress').hide();
                $('#report_view').fadeIn("5000");
                $("#report_view").html(respons);
            }
        });
    }
})
//--

</script>
