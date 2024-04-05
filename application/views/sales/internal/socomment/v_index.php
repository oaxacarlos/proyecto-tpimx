<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Check SO
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-3">
      Tracking No
      <input type="text" value="" id="inp_track_no" class="form-control">
    </div>
    <div class="col-1">
      Process<br>
      <button class="btn btn-primary" id="btn_process">PROCESS</button>
    </div>
  </div>
</div>

<div class="container" style="margin-top:20px;">
    <div id="report_detail"></div>
</div>

<?php echo loading_body_full(); ?>

<script>

$("#btn_process").click(function(){

  $("#report_detail").html("Loading, Please wait...");

  var inp_track_no = $("#inp_track_no").val();

  if(inp_track_no == ""){
      show_error("Necesitas ingresar el numero");
      return false;
  }

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/internal/socomment/get_so",
        type      : 'post',
        dataType  : 'html',
        data      :  {inp_track_no:inp_track_no},
        success   :  function(data){
            $('#report_detail').fadeIn("5000");
            $("#report_detail").html(data);
        }
    });

  //--


})
//---

</script>
