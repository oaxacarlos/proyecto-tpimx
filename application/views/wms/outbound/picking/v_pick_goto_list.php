<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      "order": [[ 1, "desc" ]]
    });
});
</script>

<style>
  tr{
    font-size: 12px;
  }

</style>

<?php echo loading_body_full(); ?>

<div class="container">

<?php
    if($var_pick == 0){
        echo "No Picking for now";
    }
    else{
      $now = get_datetime_now();
      foreach($var_pick as $row){
          $total_time = calculateTransactionDuration($row['doc_datetime'], $now);

          if($row['urgent'] == 1){
              $badge_doc_no = "<span class='badge badge-danger' style='font-size:10px;'>URGENTE</span>";
          }
          else $badge_doc_no = "";

          if($row['urgent'] == 0){
            if($total_time >= $var_timeout_wms)
              $badge_doc_no = "<span class='badge badge-danger' style='font-size:10px;'>".$var_timeout_text."</span>";
            else $badge_doc_no = "";
          }

          echo "<div class='card' style='margin-bottom:20px;'>";
            echo "<div class='card-header' style='font-size:12px; font-weight:bold;'>".$row['doc_no']." | ".$row['doc_datetime']." | ".$badge_doc_no."</div>";
              echo "";
            echo "<div class='card-body'>";
              echo "<table class='table table-sm'>";
                echo "<tr><td>Cust No</td><td>".$row['bill_cust_no']."</td></tr>";
                echo "<tr><td>Cust Name</td><td>".$row['bill_cust_name']."</td></tr>";
                echo "<tr><td>City</td><td>".$row['ship_to_city']."</td></tr>";
                echo "<tr><td>Location</td><td>".$row['src_location_code']."</td></tr>";
                echo "<tr><td>Assign</td><td>".$row['assign_name']."</td></tr>";
                echo "<tr><td>Qty</td><td>".$row['qty']." ".$row['uom']."</td></tr>";
                echo "<tr><td>Status</td><td>".$row['sts_name']."</td></tr>";
                echo "<tr><td>Message</td><td>".$row['text']."</td></tr>";
              echo "</table>";
            echo "</div>";

            echo "<div class='card-footer'>
                    <button class='btn btn-sm btn-outline-primary' onclick=f_show_detail('".$row['doc_no']."')>DETAIL</button>
                    <button class='btn btn-sm btn-primary' onclick=goto_process('".$row['doc_no']."')>GO TO</button>
                  </div>";
          echo "</div>";
      }
    }


?>
  </tbody>
</table>
</div>

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

function goto_process(doc_no){
  swal({
    title: "Are you sure ?",
    html: "Start going to Pick this Items",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {
        if(result.value){
            window.location.href = "<?php echo base_url();?>index.php/wms/outbound/picking/goto_process?docno="+doc_no;
        }
  })
}

</script>
