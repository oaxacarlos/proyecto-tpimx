<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Salesman Weekly
</div>

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <div class="col-1">
      <span class="badge badge-primary">Year</span>
      <input type="text" id="inp_year" name="inp_year" value="<?php echo date("Y"); ?>"  class="form-control">
    </div>

    <div class="col-1">
      <span class="badge badge-primary">Month</span>
      <select id="inp_month" name="inp_month" class="form-control">
        <?php
          $selected = date("m");
          echo generate_month($selected);
        ?>
      </select>
    </div>

    <div class="col-1">
      <button class="btn btn-primary" id="btn_go" style="margin-top:18px;">GO</button>
    </div>

  </div>
</div>

<div class="container" style="margin-top:18px;">
  <div id="report_result"></div>
</div>

<script>

$("#btn_go").click(function(){
    var year = $("#inp_year").val();
    var month = $("#inp_month").val();

    if(year == ""){
        show_error("Year could not blank");
        return false
    }

    gen_report(year, month);
})
//---

function gen_report(year, month){
    $("#report_result").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/salesmanweekly/report",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
            $('#report_result').fadeIn("5000");
            $("#report_result").html(respons);
        }
    });
}

</script>
