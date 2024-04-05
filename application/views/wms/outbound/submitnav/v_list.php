<script>
$(document).ready(function() {
    $('#DataTable').DataTable();
});
</script>

<?php echo loading_body_full(); ?>

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

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Date</th>
      <th>Doc No</th>
      <th>Cust No</th>
      <th>Cust Name</th>
      <th>City</th>
      <th>Ext Doc</th>
      <th>WHS</th>
      <th>Created User</th>
      <th>Qty</th>
      <th>Uom</th>
      <th>Doc Status</th>
      <th>Action</th>
      <th>Action 2</th>
    </tr>
  </thead>
  <tbody>
    <?php
        foreach($var_submitnav as $row){

            echo "<tr id='row_".$row['doc_no']."'>";
              echo "<td>".$row['doc_datetime']."</td>";
              echo "<td>".$row['doc_no']."</td>";
              echo "<td>".$row['bill_cust_no']."</td>";
              echo "<td>".$row['bill_cust_name']."</td>";
              echo "<td>".$row['ship_to_city']."</td>";
              echo "<td>".$row['external_document']."</td>";
              echo "<td>".$row['doc_location_code']."</td>";
              echo "<td>".$row['uname']."</td>";
              echo "<td>".$row['qty_to_ship']."</td>";
              //echo "<td>".$row['qty_outstanding']."</td>";
              //echo "<td>".$row['qty_to_picked']."</td>";
              //echo "<td>".$row['qty_has_picked']."</td>";
              echo "<td>".$row['uom']."</td>";
              echo "<td>".$row['sts_name']."</td>";

              echo "<td>";
              echo "<button class='btn btn-sm btn-outline-primary' onclick=f_show_detail('".$row['doc_no']."')>Detail</button>";
              echo "</td>";

              echo "<td>";
              if(is_null($row['month_end']) or $row['month_end']=='') $month_end=0;
              else $month_end = $row['month_end'];

              if($row["month_end"] == 1)
                echo "<button class='btn btn-sm btn-warning' style='margin-left:10px;' onclick=f_submit('".$row['doc_no']."',".$month_end.", '".$row['doc_location_code']."')>Submit Month End</button>";
              else
                echo "<button class='btn btn-sm btn-primary' style='margin-left:10px;' onclick=f_submit('".$row['doc_no']."',".$month_end.",'".$row['doc_location_code']."')>Submit</button>";

              echo "</td>";
            echo "</tr>";
        }
    ?>
  </tbody>
</table>

<script>

function f_show_detail(id){
  var link = 'wms/outbound/warehouse/v_detail';
  data = {'id':id, 'link':link }
  $('#modal_detail').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail').load(
      "<?php echo base_url();?>index.php/wms/outbound/whship/get_warehouse_detail",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetail').modal();
}
//---

function f_submit(id, month_end, whs){
  swal({
    input: 'textarea',
    inputPlaceholder: 'Type your message here',
    showCancelButton: true,
    confirmButtonText: 'OK'
  }).then(function (result) {

    if(result.dismiss == "cancel"){}
    else{
      if(result.value == ""){
          show_error("You have to type message");
      }
      else{
          var message = result.value;

          swal({
            title: "Are you sure ?",
            html: "Submit this document to Navision..",
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Yes",
            showLoaderOnConfirm: true,
            closeOnConfirm: false
          }).then(function (result) {
                if(result.value){
                    $("#loading_text").text("Submitting process, Please wait...");
                    $('#loading_body').show();

                    $.ajax({
                        url  : "<?php echo base_url();?>index.php/wms/outbound/submitnav/submit",
                        type : "post",
                        dataType  : 'html',
                        data : {doc_no:id, message:message, whs:whs},
                        success: function(data){
                          var responsedata = $.parseJSON(data);

                          if(responsedata.status == 1){
                                swal({
                                   title: responsedata.msg,
                                   type: "success", confirmButtonText: "OK",
                                }).then(function(){
                                  setTimeout(function(){
                                    $('#loading_body').hide();
                                    f_refresh();
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
    }
  })
}

</script>
