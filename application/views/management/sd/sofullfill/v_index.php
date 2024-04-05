<script>
// copy clipboard
var clipboard = new ClipboardJS('#copy_button_cust_bo', {
  target: function() {
    return document.querySelector('#tbl_cust_bo');
  }
});

// copy clipboard
var clipboard2 = new ClipboardJS('#copy_button_cust_top30', {
  target: function() {
     return document.querySelector('#tbl_cust_top30');
  }
});

// copy clipboard
var clipboard3 = new ClipboardJS('#copy_button_so_detail', {
  target: function() {
    return document.querySelector('#tbl_so_detail');
  }
});

// copy clipboard
var clipboard3 = new ClipboardJS('#copy_button_so_sku', {
  target: function() {
    return document.querySelector('#tbl_so_sku');
  }
});

// copy clipboard
var clipboard3 = new ClipboardJS('#copy_button_so_summary', {
  target: function() {
    return document.querySelector('#tbl_so_summary');
  }
});


</script>

<style>
  tr{
    font-size: 12px;
  }

  th#title{
    text-align: center;
  }

  td#number{
    text-align: right;
  }

  

</style>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Sales Order Full Fill
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

<div class="container-fluid" style="margin-top:20px;">
  <ul class="nav nav-tabs" id="myTab" role="tablist">

    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="sosummary-tab" data-toggle="tab" href="#sosummary" role="tab" aria-controls="sosummary" aria-selected="false">SO Summary</a>
    </li>

    <li class="nav-item" role="presentation">
      <a class="nav-link" id="custbo-tab" data-toggle="tab" href="#custbo" role="tab" aria-controls="custbo" aria-selected="false">Cust BackOrder</a>
    </li>

    <li class="nav-item" role="presentation">
      <a class="nav-link" id="custtop30-tab" data-toggle="tab" href="#custtop30" role="tab" aria-controls="custtop30" aria-selected="false">Cust TOP 30</a>
    </li>

    <li class="nav-item" role="presentation">
      <a class="nav-link" id="sosku-tab" data-toggle="tab" href="#sosku" role="tab" aria-controls="sosku" aria-selected="false">SO by SKU</a>
    </li>

    <li class="nav-item" role="presentation">
      <a class="nav-link" id="sodetail-tab" data-toggle="tab" href="#sodetail" role="tab" aria-controls="sodetail" aria-selected="true">SO Detail</a>
    </li>
  </ul>

  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="sosummary" role="tabpanel" aria-labelledby="sosummary-tab">
      <div class="container-fluid" style="margin-top:20px;">
          <div id="report_sosummary_view"></div>
      </div>
    </div>
    <div class="tab-pane fade" id="custbo" role="tabpanel" aria-labelledby="custbo-tab">
      <div class="container-fluid show" style="margin-top:20px;">
          <div id="report_custbo_view"></div>
      </div>
    </div>
    <div class="tab-pane fade" id="custtop30" role="tabpanel" aria-labelledby="custtop30-tab">
      <div class="container-fluid show" style="margin-top:20px;">
          <div id="report_custtop30_view"></div>
      </div>
    </div>
    <div class="tab-pane fade" id="sosku" role="tabpanel" aria-labelledby="sosku-tab">
      <div class="container-fluid show" style="margin-top:20px;">
          <div id="report_sosku_view"></div>
      </div>
    </div>
    <div class="tab-pane fade" id="sodetail" role="tabpanel" aria-labelledby="sodetail-tab">
      <div class="container-fluid show" style="margin-top:20px;">
          <div id="report_sodetail_view"></div>
      </div>
    </div>
  </div>
</div>


<script>
$("#btn_go").click(function(){
    var year = $("#inp_year").val();
    var month = $("#inp_month").val();

    if(year == ""){
        show_error("Year could not blank");
        return false
    }

    gen_report_sosummary(year,month);
    gen_report_custbo(year,month);
    gen_report_custtop30(year,month);
    gen_report_sosku(year,month);
    gen_report_sodetail(year,month);

})
//---

function gen_report_sodetail(year, month){
    $("#report_sodetail_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/management/sd/sofullfill_so_detail",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
            $('#report_sodetail_view').fadeIn("5000");
            $("#report_sodetail_view").html(respons);
        }
    });
}
//---

function gen_report_sosummary(year, month){
    $("#report_sosummary_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/management/sd/sofullfill_so_summary",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
            $('#report_sosummary_view').fadeIn("5000");
            $("#report_sosummary_view").html(respons);
        }
    });
}
//---

function gen_report_custbo(year, month){
    $("#report_custbo_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/management/sd/sofullfill_cust_bo",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
            $('#report_custbo_view').fadeIn("5000");
            $("#report_custbo_view").html(respons);
        }
    });
}
//---

function gen_report_custtop30(year, month){
    $("#report_custtop30_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/management/sd/sofullfill_cust_top30",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
            $('#report_custtop30_view').fadeIn("5000");
            $("#report_custtop30_view").html(respons);
        }
    });
}
//---

function gen_report_sosku(year, month){
    $("#report_sosku_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/management/sd/sofullfill_so_sku",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
            $('#report_sosku_view').fadeIn("5000");
            $("#report_sosku_view").html(respons);
        }
    });
}
//---

</script>
