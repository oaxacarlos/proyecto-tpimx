<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Print Barcode
</div>

<div class="container-fluid" style="margin-top:20px;">
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="barcode_master-tab" data-toggle="tab" href="#barcode_master" role="tab" aria-controls="barcode_master" aria-selected="true">Barcode Master</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="barcode_sn-tab" data-toggle="tab" href="#barcode_sn" role="tab" aria-controls="barcode_sn" aria-selected="true">Barcode SN</a>
    </li>
  </ul>
</div>

<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="barcode_master" role="tabpanel" aria-labelledby="barcode_master-tab">
    <div class="container-fluid" style="margin-top:10px;">
      <div class="row">
        <div class="col-3">
          Master Barcode
          <input type="text" id="inp_barcode_master" class='form-control' autofocus onkeypress="f_check_input_barcode_master(event)">
        </div>
        <div class="col-3">
          -<br>
          <button class="btn btn-primary" id="btn_barcode_master">PROCESS</button>
        </div>
      </div>
    </div>
    <div class="container-fluid" style="margin-top:10px;" id="report_barcode_master"></div>
  </div>

  <div class="tab-pane fade show" id="barcode_sn" role="tabpanel" aria-labelledby="barcode_sn-tab">
    <div class="container-fluid" style="margin-top:10px;">
      <div class="row">
        <div class="col-3">
          SN
          <input type="text" id="inp_barcode_sn" class='form-control' onkeypress="f_check_input_barcode_sn()">
        </div>
        <div class="col-3">
          -<br>
          <button class="btn btn-primary" id="btn_barcode_sn">PROCESS</button>
        </div>
      </div>
    </div>
    <div class="container-fluid" style="margin-top:10px;" id="report_barcode_sn"></div>
  </div>
</div>

<script>

$("#btn_barcode_master").click(function(){
    var id = $("#inp_barcode_master").val();
    $("#report_barcode_master").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/report/printbarcode/get_barcode_master",
        type      : "post",
        dataType  : 'html',
        data      : {id:id},
        success   : function(respons){
          $('#report_barcode_master').fadeIn("5000");
          $("#report_barcode_master").html(respons);
          $("#inp_barcode_master").val("");
          $("#inp_barcode_master").focus();
        }
    })
});
//----

function f_check_input_barcode_master(){
    if(event.keyCode == 13){
        $("#btn_barcode_master").click();
    }
}
//---

$("#btn_barcode_sn").click(function(){
    var id = $("#inp_barcode_sn").val();
    $("#report_barcode_sn").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/report/printbarcode/get_barcode_sn",
        type      : "post",
        dataType  : 'html',
        data      : {id:id},
        success   : function(respons){
          $('#report_barcode_sn').fadeIn("5000");
          $("#report_barcode_sn").html(respons);
          $("#inp_barcode_sn").val("");
          $("#inp_barcode_sn").focus();
        }
    })
});
//----

function f_check_input_barcode_sn(){
    if(event.keyCode == 13){
        $("#btn_barcode_sn").click();
    }
}
//---

</script>
