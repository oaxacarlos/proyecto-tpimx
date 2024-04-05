<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Timeline Sales Order
</div>

<div class="container-fluid" style="margin-top:30px;">
  <div class="row">
    <div class="col-md-2">
      <input type='text' class='form-control' placeholder="Sales Order No" id="inp_so_no">
    </div>
    <div class="col-2">
    <button class="btn btn-primary" id="btn_go">GO</button>
    </div>
  </div>

</div>

<div class="container-fluid" style="margin-top:30px;">
  <div class="spinner-border text-danger" id="repot_view_load" style="display:none;"></div>
  <div id="report_view"></div>
</div>

<script>
  $("#btn_go").click(function(){

      // check if blank
      if($("#inp_so_no").val()==""){
          show_error("You must fill Sales Order No");
          return false;
      }
      else{
        var doc_no = $("#inp_so_no").val();

          $("#report_view").hide();
          $('#repot_view_load').show();

        // get the shipment list
        $.ajax({
            url       : "<?php echo base_url();?>index.php/wms/report/timelineso/get_shipment_list",
            type      : 'post',
            dataType  : 'json',
            data      :  {doc_no:doc_no},
            success   :  function(data){
                if(data.status == 0){
                    show_error("No Data");
                    $("#inp_so_no").val("");
                }
                else{
                    $("#report_view").hide();
                    $("#report_view").empty();
                    $('#repot_view_load').show();
                    show_shipment_line(data.ship_no);
                    $('#report_view').fadeIn("5000");
                    $("#report_view").show();
                    //$('#repot_view_load').hide();
                }
            }
        });
      }

  })
  //---

  function show_shipment_line(data){
      $('#repot_view_load').show();

      for(i=0;i<data.length;i++){
        var doc_no = data[i];

        $.ajax({
            url       : "<?php echo base_url();?>index.php/wms/report/timelinewhshipment/get_data",
            type      : 'post',
            dataType  : 'html',
            data      :  {doc_no:doc_no},
            success   :  function(respons){
                $("#report_view").append(respons);
                $('#repot_view_load').hide();
            }
        });
      }
  }


</script>
