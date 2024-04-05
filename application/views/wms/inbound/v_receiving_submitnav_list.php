<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      "order": [[ 1, "desc" ]]
    });
});
</script>

<div class="modal" id="myModalDetailReceived">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detail</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_received"></div>
    </div>
  </div>
</div>

<?php echo loading_body_full(); ?>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Date</th>
      <th>PutAway Finished</th>
      <th>Doc No</th>
      <th>WHS</th>
      <th>Ext Doc</th>
      <th>User</th>
      <th>Qty</th>
      <th>Uom</th>
      <th>Doc Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
        foreach($var_receiving as $row){
            echo "<tr id='row_".$row['doc_no']."'>";
              echo "<td>".$row['doc_datetime']."</td>";
              echo "<td>".$row['putaway_finished']."</td>";
              echo "<td>".$row['doc_no']."</td>";
              echo "<td>".$row['doc_location_code']."</td>";
              echo "<td>".$row['external_document']."</td>";
              echo "<td>".$row['uname']."</td>";
              echo "<td>".$row['qty']."</td>";
              echo "<td>".$row['uom']."</td>";
              echo "<td>".$row['sts_name']."</td>";
              echo "<td>
                <button class='btn btn-sm btn-outline-primary' onclick=f_show_detail('".$row['doc_no']."')>Detail</button>
                <button class='btn btn-sm btn-primary') onclick=f_process('".$row['doc_no']."','".$row['doc_location_code']."')>Submit to Nav</button>
              </td>";
            echo "</tr>";
        }
    ?>
  </tbody>
</table>

<script>

function f_show_detail(id){
  var link = 'wms/inbound/v_receiving_list_data';
  data = {'id':id, 'link':link }
  $('#modal_detail').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail').load(
      "<?php echo base_url();?>index.php/wms/inbound/receiving/get_in_out_bound_list_d",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetail').modal();
}
//---


function f_show_detail_received(id,loc_code){
  var link = 'wms/inbound/v_receiving_list_data_received';
  data = {'id':id, 'link':link, 'loc_code':loc_code }
  $('#modal_detail_received').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail_received').load(
      "<?php echo base_url();?>index.php/wms/inbound/receiving/get_in_out_bound_list_d",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetailReceived').modal();
}
//---

function f_process(id,whs){

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
                        url  : "<?php echo base_url();?>index.php/wms/inbound/receiving/submitnav_process",
                        type : "post",
                        dataType  : 'html',
                        data : {doc_no:id, message:message,whs:whs},
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
//----


</script>
