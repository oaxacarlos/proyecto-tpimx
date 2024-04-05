<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      "order": [[ 1, "desc" ]]
    });
});
</script>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Date</th>
      <th>Doc No</th>
      <th>WHS</th>
      <th>Created User</th>
      <th>Assigned User</th>
      <th>Qty</th>
      <th>Uom</th>
      <th>Doc Status</th>
      <th>Action</th>
      <th>Action 2</th>
      <th>Action 3</th>
    </tr>
  </thead>
  <tbody>
    <?php
        foreach($var_transferbin_h as $row){
            if($row['statuss'] == 1){
                $status="Waiting Transfer"; $label = "warning";
            }
            else if($row['statuss'] == 2){
                $status="Transfer Finished"; $label = "success";
            }
            else if($row['statuss'] == 3){
                $status="All Finished"; $label = "info";
            }

            echo "<tr id='row_".$row['doc_no']."'>";
              echo "<td>".$row['doc_datetime']."</td>";
              echo "<td>".$row['doc_no']."</td>";
              echo "<td>".$row['location_code']."</td>";
              echo "<td>".$row['created_name']."</td>";
              echo "<td>".$row['assigned_name']."</td>";
              echo "<td>".$row['qty']."</td>";
              echo "<td>".$row['uom']."</td>";
              echo "<td><div class='badge badge-".$label."'>".$status."</div></td>";
              echo "<td><button class='btn btn-outline-primary btn-sm' onclick=f_show_detail('".$row['doc_no']."')>Detail</button></td>";
              echo "<td>";

              if($row['statuss'] == 1){
                  echo "<button class='btn btn-danger btn-sm' style='margin-left:10px;' onclick=f_cancel('".$row['doc_no']."')>CANCEL</button>";
              }

              if($row['statuss'] == 2){
                  echo "<button class='btn btn-primary btn-sm' style='margin-left:10px;' onclick=f_confirm('".$row['doc_no']."')>Confirm</button>";
              }

              if($row['statuss'] == 3){
                  echo "<a href='".base_url()."index.php/wms/barcode/print_barcode_by_doc?doctype=transferbin&docno=".$row['doc_no']."' class='btn btn-sm btn-success ".$btn_disabled."' target=_blank>Print barcode</a><br>";
                  echo "Print : ".$row["print_barcode"];
              }

              if($row['statuss'] == 4){
                  echo "<label class='badge badge-danger'>Canceled</label>";
              }

              echo "</td>";

              if($row['statuss'] == 3){
                echo "<td><a href='".base_url()."index.php/wms/barcode/print_master_barcode_by_doc?doctype=transferbin2&docno=".$row['doc_no']."' class='btn btn-sm btn-success ".$btn_disabled."' target=_blank>Master barcode</a><br>
                Print : ".$row["print_master_barcode"]."</td>";
              }
              else echo "<td>-</td>";


            echo "</tr>";
        }
    ?>
  </tbody>
</table>



<script>
function f_show_detail(id){
  var link = 'wms/inbound/transferbin/v_detail';
  data = {'id':id, 'link':link }
  $('#modal_detail').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail').load(
      "<?php echo base_url();?>index.php/wms/inbound/transferbin/get_list_d",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetail').modal();
}
//---

function f_confirm(id){
    var link = 'wms/inbound/transferbin/v_confirm';
    data = {'id':id, 'link':link }
    $('#modal_confirm').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_confirm').load(
        "<?php echo base_url();?>index.php/wms/inbound/transferbin/confirm",
        data,
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalConfirm').modal();
}
//---

function f_cancel(id){
    var link = 'wms/inbound/transferbin/v_cancel';
    data = {'id':id, 'link':link }
    $('#modal_cancel').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_cancel').load(
        "<?php echo base_url();?>index.php/wms/inbound/transferbin/cancel",
        data,
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalCancel').modal();
}
//---

</script>
