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
      <input type="hidden" value="" id="inp_doc_no">
      <div class="modal-body" id="modal_detail_received"></div>
    </div>
  </div>
</div>

<?php echo loading_body_full(); ?>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Date</th>
      <th>Doc No</th>
      <th>WHS</th>
      <th>User Req</th>
      <th>SHOPPING PURPOSE</th>
      <th>Urgent</th>
      <th>Qty</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
        foreach($var_po_list as $row){
            echo "<tr id='row_".$row['doc_no']."'>";
              echo "<td>".$row['doc_creation_datetime']."</td>";
              echo "<td>".$row['doc_no']."</td>";
              echo "<td>".$row['doc_location_code']."</td>";
              echo "<td>".$row['name']."</td>";
              echo "<td>".$row['shopping_purpose']."</td>";
              if ($row['urgent']=='1')echo "<td><span class='badge badge-danger'>Urgent</span></td>"; else echo "<td><span class='badge badge-info'>No</span></td>";
              echo "<td>".$row['qty_t']."</td>";
              if ($var_status[0]=='1')
              echo "<td>
                <button class='btn btn-sm btn-danger' onclick=f_po_cancel('".$row['doc_no']."','".$row['id_statuss']."')>Cancel</button>
                <button class='btn btn-sm btn-primary' onclick=f_po_approval_d('".$row['doc_no']."','".$row['id_statuss']."')>Approve</button>
              </td>";
              else if ($var_status[0]=='2')
              echo "<td>
                <button class='btn btn-outline-primary' onclick=f_po_details('".$row['doc_no']."','".$row['id_statuss']."')>Details</button>
                <button class='btn btn-sm btn-primary' onclick=f_po_process('".$row['doc_no']."')>Process</button>
              </td>";
            echo "</tr>";
        }

    ?>
  </tbody>
  
</table>

<script>


 function f_po_approval_d(doc_no,id_statuss){
  var link = 'purchasing/v_po_approval_details';
  data = {'doc_no':doc_no, 'id_statuss':id_statuss }
  $('#modal_detail').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail').load(
      "<?php echo base_url();?>index.php/purchasing/po_approval/get_details_po",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetail').modal();
 }

 function f_po_cancel(doc_no,id_status){
  swal({
      input: 'textarea',
      inputPlaceholder: 'Type your reason here',
      showCancelButton: true,
      confirmButtonText: 'OK'
    }).then(function (result) {
      if (result.dismiss == "cancel") { }
      else {
        if (result.value == "") { show_error("You have to type message"); }
         
        else {
          var message = result.value;
  swal({
            title: "Are you sure ?",
            html: "Cancel this Purchase Order",
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Yes",
            showLoaderOnConfirm: true,
            closeOnConfirm: false
          }).then(function (result) {
            if (result.dismiss == "cancel") { }
            else {
    $.ajax({
      url  : "<?php echo base_url();?>index.php/purchasing/po_approval/cancel_doc_po",
      type : "post",
      dataType  : 'html',
      async: false,
      data : {doc_no:doc_no,message:message, id_status:id_status},
      success: function(data){
        var responsedata = $.parseJSON(data);
        if (responsedata.status == 1) {
          swal({
            title: responsedata.msg,
            type: "success", confirmButtonText: "OK",
          }).then(function () {
            setTimeout(function () {
              $('#loading_body').hide();
              window.location.href = "<?php echo base_url(); ?>index.php/purchasing/po_approval";
            }, 100)
          });
                }
              }
                 })
                }
               })
              } 
            }  
          })
        }



function f_po_details(doc_no,id_statuss){
  
  data = {'doc_no':doc_no, 'id_statuss': id_statuss }
  $('#modal_detail').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail').load(
      "<?php echo base_url();?>index.php/purchasing/po_approval/get_details_po",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetail').modal();

}
function f_po_process(doc_no){
  swal({
            title: "Are you sure ?",
            html: "Process this Purchase Order",
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Yes",
            showLoaderOnConfirm: true,
            closeOnConfirm: false
          }).then(function (result) {
            if (result.dismiss == "cancel") { }
            else {
              $.ajax({
              url: "<?php echo base_url(); ?>index.php/purchasing/po_process/proces_po_by_doc",
              type: "post",
              dataType: 'html',
              data: {
                doc_no : doc_no,
              },
              success: function(data){
                var responsedata = $.parseJSON(data);
                var doc_no = responsedata.doc_no;
              window.location.href = "<?php echo base_url();?>index.php/purchasing/po_process/proces_doc_view?doc_no="+doc_no;
              }
            })
            }
          })
}
</script>