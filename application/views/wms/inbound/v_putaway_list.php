<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      "order": [[ 1, "desc" ]]
    });
});
</script>

<?php echo loading_body_full(); ?>

<div class="modal" id="myModalChangeUser">
  <div class="modal-dialog2 modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Change User</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_change_user"></div>
    </div>
  </div>
</div>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Date</th>
      <th>Doc No</th>
      <th>WHS</th>
      <th>Created User</th>
      <th>Assign to User</th>
      <th>Qty</th>
      <th>Uom</th>
      <th>Doc Status</th>
      <th>Action</th>
      <th>Action 2</th>
    </tr>
  </thead>
  <tbody>
    <?php
        foreach($var_putaway as $row){
            echo "<tr id='row_".$row['doc_no']."'>";
              echo "<td>".$row['doc_datetime']."</td>";
              echo "<td>".$row['doc_no']."</td>";
              echo "<td>".$row['src_location_code']."</td>";
              echo "<td>".$row['uname']."</td>";
              echo "<td>".$row['assign_name']."</td>";
              echo "<td>".$row['qty']."</td>";
              echo "<td>".$row['uom']."</td>";
              echo "<td>".$row['sts_name']."</td>";
              echo "<td><button class='btn btn-sm btn-outline-primary' onclick=f_show_detail('".$row['doc_no']."')>DETAIL</button></td>";
              if($row['statuss']!='7') echo "<td><button class='btn btn-sm btn-outline-warning' onclick=f_change_user('".$row['doc_no']."')>CHANGE USER</button></td>";
              else echo "<td></td>";
            echo "</tr>";
        }
    ?>
  </tbody>
</table>

<script>

function f_show_detail(id){
  var link = 'wms/inbound/v_putaway_list_data';
  data = {'id':id, 'link':link }
  $('#modal_detail').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail').load(
      "<?php echo base_url();?>index.php/wms/inbound/putaway/get_putaway_list_d",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetail').modal();
}
//---

function f_change_user(id){
    data = {'id':id }
    $('#modal_change_user').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_change_user').load(
        "<?php echo base_url();?>index.php/wms/inbound/putaway/get_change_user",
        data,
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalChangeUser').modal();
}

</script>
