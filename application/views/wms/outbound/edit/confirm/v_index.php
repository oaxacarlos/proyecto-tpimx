<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
        "aaSorting": []
    });
});
</script>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Outbound Edit Confirm
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

<div class="container-fluid" style="">
<table class="table table-bordered table-striped table-sm" id="DataTable">
  <thead>
    <tr>
      <th>Doc No</th>
      <th>Created Datetime</th>
      <th>User</th>
      <th>Remarks</th>
      <th>Confirm DateTime</th>
      <th>Canceled DateTime</th>
      <th>Action</th>
      <th>Action 2</th>
      <th>Action 3</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($var_doc_h as $row){
          echo "<tr>";
            echo "<td>".$row["doc_no"]."</td>";
            echo "<td>".$row["created_datetime"]."</td>";
            echo "<td>".$row["name"]."</td>";
            echo "<td>".$row["text1"]."</td>";
            echo "<td>".$row["confirm_datetime"]."</td>";
            echo "<td>".$row["canceled_datetime"]."</td>";
            echo "<td><button class='btn btn-outline-primary btn-sm' onclick=f_show_detail('".$row['doc_no']."',-1)>DETAIL</button></td>";

            if($row["confirm"] == 2 && $row["canceled"] == 0) echo "<td><button class='btn btn-warning btn-sm' onclick=f_show_detail('".$row['doc_no']."',2)>Envia Correo a Operacion</button></td>";
            else if($row["confirm"] == 0 && $row["canceled"] == 0) echo "<td><button class='btn btn-primary btn-sm' onclick=f_show_detail('".$row['doc_no']."',1)>Confirm</button></td>";
            else if($row["confirm"] == 0 && $row["canceled"] == 1) echo "<td><label class='badge badge-danger'>Canceled</label></td>";
            else if($row["confirm"] == 2 && $row["canceled"] == 1) echo "<td><label class='badge badge-danger'>Canceled</label></td>";
            else if($row["confirm"] == 1) echo "<td><label class='badge badge-success'>Finished</label></td>";

            if($row["confirm"] == 2 && $row["canceled"] == 0) echo "<td><button class='btn btn-danger btn-sm' onclick=f_show_detail('".$row['doc_no']."',0)>Cancel</button></td>";
            else if($row["confirm"] == 0 && $row["canceled"] == 0) echo "<td><button class='btn btn-danger btn-sm' onclick=f_show_detail('".$row['doc_no']."',0)>Cancel</button></td>";
            else if($row["canceled"] == 1) echo "<td><label class='badge badge-danger'>Canceled</label></td>";
            else if($row["confirm"] == 1) echo "<td>-</td>";

          echo "</tr>";
      }
    ?>
  </tbody>
</table>
</div>

<?php echo loading_body_full(); ?>

<script>

function f_show_detail(id, status){

  var link = 'wms/outbound/edit/confirm/v_list';
  data = {'id':id, 'link':link, 'status':status }
  $('#modal_detail').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail').load(
      "<?php echo base_url();?>index.php/wms/outbound/edit/confirm_detail",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetail').modal();
}
//---

</script>
