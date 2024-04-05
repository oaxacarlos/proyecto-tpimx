<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Sales Contribution
</div>


<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <div class="col-md-1">
      <select id="inp_brand" name="inp_brand" class='required form-control'>
        <option value="1">Sakura</option>
        <option value="2">Toyopower</option>
      </select>
    </div>
    <div class="col-md-2">
      <select id="inp_cat" name="inp_cat" class='required form-control'>
        <option value="HD" >HD-Sakura</option>
        <option value="AT">AT-Sakura</option>
        <option value="MC">MC-Sakura</option>
        <option value="WVB">WVB-Toyopower</option>
        <option value="RAW">RAW-Toyopower</option>
        <option value="DVB">DVB-Toyopower</option>
        <option value="AGB">AGB-Toyopower</option>
        <option value="MRB">MRB-Toyopower</option>
        <option value="BCB">BCB-Toyopower</option>
        <option value="BVB">BVB-Toyopower</option>
        <option value="ITB">ITB-Toyopower</option>
      </select>
    </div>
    <div class="col-md-1">
      <input type='text' name='datepicker_year' value="<?php echo date("Y"); ?>" id='datepicker_year' class='required form-control' placeholder='Year'>
    </div>
    <div class="col-md-1">
      <select id="inp_type" class='required form-control'>
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

<script>

$("#btn_go").click(function(){
    var brand = $("#inp_brand").val();
    var cat = $("#inp_cat").val();
    var year = $("#datepicker_year").val();
    var type = $("#inp_type").val();

    if(year == ""){
        show_error("You must fill YEAR");
        return false;
    }

    $('#report_view').html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/contribution_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year,brand:brand, cat:cat, type:type},
        success   :  function(respons){
            $('#report_view').fadeIn("5000");
            $('#report_view').html(respons);
        }
    });
})

</script>
