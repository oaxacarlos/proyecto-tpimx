<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Item Rack
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      Item Code
      <input type="text" id="inp_item" class="form-control" onkeypress="f_check_input(event)" autofocus>
    </div>
    <div class="col-2">
      <button class="btn btn-primary" id="btn_process" style="display:none;">PROCESS</button>
    </div>
  </div>
</div>

<div class="container-fluid" id="report_view" style="margin-top:20px;"></div>

<script>

$("#btn_process").click(function(){
    var inp_item = $("#inp_item").val();

    if(inp_item == ""){
        show_error("You have to input Item");
        $("#inp_item").val("");
        return false;
    }

    $("#report_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/report/itemrack/get_rack_item",
        type      : "post",
        dataType  : 'html',
        data      : {id:inp_item},
        success   : function(respons){
          $('#progress').hide();
          $('#report_view').fadeIn("5000");
          $("#report_view").html(respons);
          $("#inp_item").val("");
        }
    })
})
//--

function f_check_input(){
    if(event.keyCode == 13){
        $("#btn_process").click();
    }
}

</script>
