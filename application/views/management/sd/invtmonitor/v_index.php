<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Inventory Monitoring
</div>

<div class="modal" id="myModalDetail">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detail Item</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail"></div>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <div class="col-md-2">
      <select id="inp_type" class='required form-control'>
        <option value="FILTRO">FILTRO</option>
        <option value="BANDA">BANDA</option>
      </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary" id="btn_go">GO</button>
    </div>
  </div>
</div>


<div class="container-fluid" style="margin-top:30px;">
  <div id="report_view"></div>
</div>


<script>

$("#btn_go").click(function(){

    var type = $("#inp_type").val();

    $('#report_view').html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/management/sd/invtmonitor_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {type:type},
        success   :  function(respons){
            $('#report_view').fadeIn("5000");
            $('#report_view').html(respons);
        }
    });
})

</script>
