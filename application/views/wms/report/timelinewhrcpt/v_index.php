<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Timeline Warehouse Reciept
</div>

<div class="container-fluid" style="margin-top:30px;">
  <div class="row">
    <div class="col-md-2">
      <input type='text' class='form-control' placeholder="Warehouse Receipt No" id="inp_whrcpt_no" value="<?php if($get_doc_no) echo $get_doc_no ?>">
    </div>
    <div class="col-2">
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

      // check if blank
      if($("#inp_whrcpt_no").val()==""){
          show_error("You must fill Warehouse Receipt No");
          return false;
      }
      else{
        var doc_no = $("#inp_whrcpt_no").val();

        $("#report_view").hide();
        $('#progress').show();

        $.ajax({
            url       : "<?php echo base_url();?>index.php/wms/report/timelinewhrcpt/get_data",
            type      : 'post',
            dataType  : 'html',
            data      :  {doc_no:doc_no},
            success   :  function(respons){
                $('#progress').hide();
                $('#report_view').fadeIn("5000");
                $("#report_view").html(respons);
            }
        });
      }
  })
//---

$(document).ready(function() {
    if($("#inp_whrcpt_no").val()) $("#btn_go").click();
});

</script>
