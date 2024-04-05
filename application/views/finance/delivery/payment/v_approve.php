<style>
.modal {
  padding: 0 !important; // override inline padding-right added from js
}
.modal .modal-dialog {
  width: 100%;
  max-width: none;
  height: 100%;
  margin: 0;
}
.modal .modal-content {
  height: 100%;
  border: 0;
  border-radius: 0;
}
.modal .modal-body {
  overflow-y: auto;
}

</style>

<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      "paging": false,
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'TPM-DeliveryPayment'
          }
        ],

    });

    $("#inp_payment_date").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });


});
</script>

<style>
  tr{
    font-size:12px;
  }
</style>

<div class="modal" id="myModalDetail">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail<span id="modal_detail_title" style='margin-left:5px;'></span></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_data"></div>
    </div>
  </div>
</div>

<div class="container" style="margin-top:30px;">
    <span class="col-md-1">
      <input type="text" value="<?php echo date("Y-m-d"); ?>" id='inp_payment_date' readonly>
    </span>
    <span class="col-md-2">
      <button class="btn btn-primary" onclick=f_approve()>APPROVE</button>
    </span>
</div>

<div class="container-fluid">
  <button class="btn btn-warning btn-sm" id="btn_check_all">Check All</button>
  <button class="btn btn-danger btn-sm" id="btn_uncheck_all" style="margin-left:10px;">UnCheck All</button>
</div>

<div style="margin-top:30px;">
  <table class="table table-sm table-striped" id="DataTable">
    <thead>
      <tr>
        <th></th>
        <th>Doc Date</th>
        <th>Doc No</th>
        <th>Sending Date</th>
        <th>Destination</th>
        <th>State</th>
        <th>Vendor No</th>
        <th>Vendor Name</th>
        <th>Delivery Status</th>
        <th>Received Date</th>
        <th>Customer Name</th>
        <th>SubTotal</th>
        <th>Total</th>
        <th>% Cost</th>
        <th>Remarks</th>
        <th>Tracking No</th>
        <th>Folio</th>

        <th class="table-dark">Vendor Invc No</th>
        <th class="table-dark">Vendor Invc Date</th>
        <th class="table-dark">Vendor SubTotal</th>
        <th class="table-dark">Vendor Total</th>
        <th class="table-dark">Payment Date</th>
        <th class="table-dark">UUID</th>
        <th class="table-dark">Remark</th>
        <th class="table-dark">Action</th>
        <th class="table-dark" style="display:none;"></th>
      </tr>
    </thead>
    <tbody>
      <?php
        $i=0;
        foreach($var_data as $row){
            echo "<tr>";
              echo "<td><input type='checkbox' id='no_".$i."' name='no_".$i."'></td>";
              echo "<td>".$row["doc_date"]."</td>";
              echo "<td id='doc_no_".$i."'>".$row["doc_no"]."</td>";
              echo "<td>".$row["delv_date"]."</td>";
              echo "<td>".$row["destination"]."</td>";
              echo "<td>".$row["state"]."</td>";
              echo "<td>".$row["vendor_no"]."</td>";
              echo "<td>".$row["vendor_name"]."</td>";
              echo "<td>".$row["delv_status"]."</td>";
              echo "<td>".$row["receiv_date"]."</td>";
              echo "<td>".$row["invc_cust_name"]."</td>";
              echo "<td style='text-align:right;'>".format_number($row["subtotal2"],1,2)."</td>";
              echo "<td style='text-align:right;'>".format_number($row["total2"],1,2)."</td>";
              echo "<td style='text-align:right;'>".$row["percentage_cost"]."</td>";
              echo "<td>".$row["remark1"]."</td>";
              echo "<td>".$row["tracking_no"]."</td>";
              echo "<td>".$row["folio"]."</td>";
              echo "<td>".$row["invc_vendor_no"]."</td>";
              echo "<td>".$row["invc_vendor_date"]."</td>";
              echo "<td id='tbl_invc_vendor_subtotal_".$row["doc_no"]."'>".round($row["invc_vendor_subtotal"],2)."</td>";
              echo "<td id='tbl_invc_vendor_total_".$row["doc_no"]."'>".round($row["invc_vendor_total"],2)."</td>";
              echo "<td>".$row["payment_date"]."</td>";
              echo "<td>".$row["uuid"]."</td>";
              echo "<td>".$row["remark2"]."</td>";
              echo "<td><button class='btn btn-outline-primary btn-sm' onclick=f_show_detail('".$row["doc_no"]."')>DETAIL</button></td>";
              echo "<td style='display:none;'>".$i."</td>"; // 2023-11-03
            echo "</tr>";
            $i++;
        }
      ?>
    </tbody>
  </table>

  <input type="hidden" id="inp_total_row" value="<?php echo $i; ?>">

</div>

<?php echo loading_body_full(); ?>

<script>

function f_approve(){
    var total_row = parseInt($("#inp_total_row").val());

    if(total_row == 0){
        return false;
    }
    else{
        var have_data = 0;
        var data = [];
        var count = 0;

        for(i=0;i<total_row;i++){
            if($("#no_"+i).is(":checked")){
                have_data++;
                data[count] = $("#doc_no_"+i).text();
                count++;
            }
        }

        if(have_data == 0) return false;
    }

    // if everything is ok
    swal({
      title: "Are you sure ?",
      html: "Update the PAYMENT information, after UPDATED you can not change anymore...",
      type: "question",
      showCancelButton: true,
      confirmButtonText: "Yes",
      showLoaderOnConfirm: true,
      closeOnConfirm: false
    }).then(function (result) {
        if(result.value){
          $("#loading_text").text("Updating Payment this document, Please wait...");
          $('#loading_body').show();

          var payment_approve_date = $("#inp_payment_date").val();

          $.ajax({
              url  : "<?php echo base_url();?>index.php/finance/delivery/payment/approve_updated",
              type : "post",
              dataType  : 'html',
              data : {id:JSON.stringify(data), payment_approve_date:payment_approve_date},
              success: function(data){
                  var responsedata = $.parseJSON(data);

                  if(responsedata.status == 1){
                    swal({
                       title: responsedata.msg,
                       type: "success", confirmButtonText: "OK",
                    }).then(function(){
                      setTimeout(function(){
                        $('#loading_body').hide();
                        window.location.href = "<?php echo base_url();?>index.php/finance/delivery/payment/approve";
                      },100)
                    });
                  }
                  else if(responsedata.status == 0){
                      Swal('Error!',responsedata.msg,'error');
                      $('#loading_body').hide();
                  }
              }
          })
        }
    })
}
//---

function f_show_detail(id){
  var link = 'finance/delivery/payment/v_detail';
  data = {'id':id}
  $('#modal_detail_data').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail_data').load(
      "<?php echo base_url();?>index.php/finance/delivery/payment/detail",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $("#modal_detail_title").text(id);
  $('#myModalDetail').modal();
}
//---

$("#btn_check_all").click(function(){
  var table = $('#DataTable').DataTable();

  table.column(25, { search:'applied' } ).data().each(function(value, index) {
    $("#no_"+value).prop( "checked", true );  // "checked"
  });

})
//--

$("#btn_uncheck_all").click(function(){
  var table = $('#DataTable').DataTable();

  table.column(25, { search:'applied' } ).data().each(function(value, index) {
    $("#no_"+value).prop( "checked", false );  // "checked"
  });

})
//--


</script>
