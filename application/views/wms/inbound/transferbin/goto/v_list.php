

<?php echo loading_body_full(); ?>

<div class="container">

<?php
    if($var_transferbin_h == 0){
        echo "No Transfer Bin for now";
    }
    else{
      foreach($var_transferbin_h as $row){
          echo "<div class='card' style='margin-bottom:20px;'>";
            echo "<div class='card-header' style='font-size:12px; font-weight:bold;'>".$row['doc_no']." | ".$row['doc_datetime']."</div>";
              echo "";
            echo "<div class='card-body'>";
              echo "<table class='table table-sm'>";
                echo "<tr><td>Location</td><td>".$row["location_code_to"]."-".$row["zone_code_to"]."-".$row["area_code_to"]."-".$row["rack_code_to"]."-".$row["bin_code_to"]."</td></tr>";
                echo "<tr><td>Assign</td><td>".$row['assigned_name']."</td></tr>";
                echo "<tr><td>Qty</td><td>".$row['qty']." ".$row['uom']."</td></tr>";
                echo "<tr><td>Message</td><td>".$row['text1']."</td></tr>";
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

function goto_process(doc_no){
  swal({
    title: "Are you sure ?",
    html: "Start going to Transfer this Items",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {
        if(result.value){
            window.location.href = "<?php echo base_url();?>index.php/wms/inbound/transferbin/goto_process?docno="+doc_no;
        }
  })
}

</script>
