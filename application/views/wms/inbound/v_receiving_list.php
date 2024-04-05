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
      <th>Ext Doc No</th>
      <th>WHS</th>
      <th>User</th>
      <th>Qty</th>
      <th>Qty Recvd</th>
      <th>Qty Rem</th>
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
        foreach($var_receiving as $row){
            echo "<tr id='row_".$row['doc_no']."'>";
              echo "<td>".$row['doc_datetime']."</td>";
              echo "<td>".$row['doc_no']."</td>";
              echo "<td>".$row['external_document']."</td>";
              echo "<td>".$row['doc_location_code']."</td>";
              echo "<td>".$row['uname']."</td>";
              echo "<td>".$row['qty']."</td>";
              echo "<td>".$row['qty_received']."</td>";
              echo "<td>".($row['qty']-$row['qty_received'])."</td>";
              echo "<td>".$row['uom']."</td>";
              echo "<td>".$row['sts_name']."</td>";
              echo "<td>".$row['text']."</td>";
              echo "<td>".$row['from_wh']."</td>";
              echo "<td>".$row['transfer_from_wh']."</td>";
              echo "<td>
                <button class='btn btn-sm btn-outline-primary' onclick=f_show_detail('".$row['doc_no']."')>Detail</button>
                <button class='btn btn-sm btn-primary' onclick=f_show_detail_received('".$row['doc_no']."','".$row['doc_location_code']."','".$row['from_wh']."','".$row['transfer_from_wh']."')>Received</button>
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


function f_show_detail_received(id,loc_code,from_wh,transfer_from_wh){

  // check if locked
  if(check_doc_locked(id)==1){
      show_error("This Document has been locked by another user");
      return false;
  }
  //---

  doc_locked(id);

  var link = 'wms/inbound/v_receiving_list_data_received';
  data = {'id':id, 'link':link, 'loc_code':loc_code,'from_wh':from_wh,'transfer_from_wh':transfer_from_wh }
  $('#modal_detail_received').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail_received').load(
      "<?php echo base_url();?>index.php/wms/inbound/receiving/get_in_out_bound_list_d",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $("#inp_doc_no").val(id);
  $('#myModalDetailReceived').modal();
}
//---

function check_doc_locked(id){
  $.ajax({
      url  : "<?php echo base_url();?>index.php/wms/inbound/receiving/check_doc_locked",
      type : "post",
      dataType  : 'json',
      async: false,
      data : {id:id},
      success: function(data){
          result = $.parseJSON(data);
      }
  })

  return result;
}
//--

function doc_locked(id){
    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/inbound/receiving/doc_locked",
        type : "post",
        dataType  : 'json',
        async: false,
        data : {id:id},
        success: function(data){
            result = $.parseJSON(data);
        }
    })

    return result;
}
//---

$('#myModalDetailReceived').on('hidden.bs.modal', function () {

    id = $("#inp_doc_no").val();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/inbound/receiving/doc_unlocked",
        type : "post",
        dataType  : 'json',
        async: false,
        data : {id:id},
        success: function(data){
            result = $.parseJSON(data);
        }
    })
})

</script>
