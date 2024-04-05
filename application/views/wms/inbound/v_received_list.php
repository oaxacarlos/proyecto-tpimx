<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      "order": [[ 1, "desc" ]]
    });
});
</script>

<?php echo loading_body_full(); ?>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Date</th>
      <th>Doc No</th>
      <th>WHS</th>
      <th>User</th>
      <th>Qty</th>
      <th>Uom</th>
      <th>Doc Status</th>
      <th>Message</th>
      <th>WH Transfer From</th>
      <th>WH Transfer Doc</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
        foreach($var_received as $row){
            echo "<tr id='row_".$row['doc_no']."'>";
              echo "<td>".$row['doc_datetime']."</td>";
              echo "<td>".$row['doc_no']."</td>";
              echo "<td>".$row['doc_location_code']."</td>";
              echo "<td>".$row['uname']."</td>";
              echo "<td>".$row['qty']."</td>";
              echo "<td>".$row['uom']."</td>";
              echo "<td>".$row['sts_name']."</td>";
              echo "<td>".$row['text']."</td>";
              echo "<td>".$row['from_wh']."</td>";
              echo "<td>".$row['transfer_from_wh']."</td>";
              echo "<td>
                <button class='btn btn-sm btn-primary' onclick=f_show_detail('".$row['doc_no']."')>VERIFY</button>
                <button class='btn btn-sm btn-danger' onclick=f_cancel('".$row['doc_no']."')>CANCEL</button>
              </td>";
            echo "</tr>";
        }
    ?>
  </tbody>
</table>

<script>

function f_show_detail(id){
  var link = 'wms/inbound/v_received_list_data';
  data = {'id':id, 'link':link }
  $('#modal_detail').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail').load(
      "<?php echo base_url();?>index.php/wms/inbound/received/get_received_list_d",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetail').modal();
}
//---

function f_cancel(id){
  swal({
    title: "Are you sure ?",
    html: "Cancel this Received",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {
        if(result.value){
            $("#loading_text").text("Canceling process, Please wait...");
            $('#loading_body').show();

            $.ajax({
                url  : "<?php echo base_url();?>index.php/wms/inbound/received/cancel_received",
                type : "post",
                dataType  : 'html',
                data : {id:id},
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

</script>
