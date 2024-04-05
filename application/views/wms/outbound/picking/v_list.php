<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      "order": [[ 1, "asc" ]]
    });
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

<?php

unset($user_pick_doc);

foreach($var_picking_list as $row){
    if(!isset($user_pick_doc[$row["assign_user"]])) $user_pick_doc[$row["assign_user"]] = 1;
    else  $user_pick_doc[$row["assign_user"]] += 1;
}

?>

<div class="row" style="margin-bottom:10px;">
  <?php
    foreach($user_pick_doc as $key => $value){
        echo "<span class='col-sm-1'><i class='bi bi-person-fill' style='color:".$user_color[$key].";'></i>".$value."</span>";
    }
  ?>
</div>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Date</th>
      <th>Doc No</th>
      <th>WHS</th>
      <th>Cust Code</th>
      <th>Cust Name</th>
      <th>City</th>
      <th>Created User</th>
      <th>Assign to User</th>
      <th>Type</th>
      <th>Qty</th>
      <th>Qty Picked</th>
      <th>Uom</th>
      <th>Doc Status</th>
      <th>Action</th>
      <th>Action 2</th>
      <th>Action 3</th>
    </tr>
  </thead>
  <tbody>
    <?php
        foreach($var_picking_list as $row){
            echo "<tr id='row_".$row['doc_no']."'>";
              echo "<td>".$row['doc_datetime']."</td>";
              echo "<td>".$row['doc_no']."</td>";
              echo "<td>".$row['src_location_code']."</td>";
              echo "<td>".$row['bill_cust_no']."</td>";
              echo "<td>".$row['bill_cust_name']."</td>";
              echo "<td>".$row['ship_to_city']."</td>";
              echo "<td>".$row['uname']."</td>";
              echo "<td><i class='bi bi-person-fill' style='color:".$user_color[$row["assign_user"]].";'></i>".$row['assign_name']."</td>";

              if($row['bill_cust_no'][0] == "1"){
                  $type = "Filter"; $type_color="danger";
              }
              else if($row['bill_cust_no'][0] == "2"){
                  $type = "Belt"; $type_color="primary";
              }
              else{
                  $type = "-"; $type_color="warning";
              }
              echo "<td><div class='badge badge-".$type_color."'>".$type."</td>";

              echo "<td>".$row['qty']."</td>";
              echo "<td>".$row['qty_has_picked']."</td>";
              echo "<td>".$row['uom']."</td>";
              echo "<td>".$row['sts_name']."</td>";
              echo "<td>
                <button class='btn btn-sm btn-outline-primary' onclick=f_show_detail('".$row['doc_no']."')>DETAIL</button>
              </td>";

              echo "<td>
                <a href='".base_url()."index.php/wms/outbound/picking/print?id=".$row['doc_no']."' class='btn btn-primary btn-sm' target='_blank'>PRINT</a>
              </td>";

              echo "<td><button class='btn btn-sm btn-outline-warning' onclick=f_change_user('".$row['doc_no']."')>CHANGE USER</button></td>";

            echo "</tr>";
        }
    ?>
  </tbody>
</table>


<script>

function f_show_detail(id){
  var link = 'wms/outbound/picking/v_detail';
  data = {'id':id, 'link':link }
  $('#modal_detail').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail').load(
      "<?php echo base_url();?>index.php/wms/outbound/picking/get_picking_list_d",
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
        "<?php echo base_url();?>index.php/wms/outbound/picking/get_change_user",
        data,
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalChangeUser').modal();
}

</script>
