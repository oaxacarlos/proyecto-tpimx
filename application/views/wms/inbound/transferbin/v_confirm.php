<table class="table table-bordered table-striped table-sm" style="font-size:12px;">
  <thead>
    <tr>
      <th>From</th>
      <th>To</th>
      <th>Item Code</th>
      <th>Desc</th>
      <th>Qty</th>
      <th>Uom</th>
      <th>Pick</th>
      <th>Put</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php
        unset($array_line_no);
        foreach($var_transferbin_d as $row){
            if($row["confirmed"] == 1){
                $display = "";
            }
            else{
                $display = "style='display:none;'";
                $array_line_no[] = $row['line_no'];
            }

            $from = $row['location_code_from']."-".$row['zone_code_from']."-".$row['area_code_from']."-".$row['rack_code_from']."-".$row['bin_code_from'];
            $to = $row['location_code_to']."-".$row['zone_code_to']."-".$row['area_code_to']."-".$row['rack_code_to']."-".$row['bin_code_to'];

            echo "<tr>";
              echo "<td>".$from."</td>";
              echo "<td>".$to."</td>";
              echo "<td>".$row['item_code']."</td>";
              echo "<td>".$row['description']."</td>";
              echo "<td>".$row['qty']."</td>";
              echo "<td>".$row['uom']."</td>";
              echo "<td>".$row['pick_datetime']."</td>";
              echo "<td>".$row['putaway_datetime']."</td>";
              echo "<td><span class='badge badge-success' id='check_".$row['line_no']."' ".$display."><i class='bi-check2'></i></span></td>";
            echo "</tr>";
        }

        if(!$array_line_no) $btn_disabled= "disabled";
        else $btn_disabled="";

    ?>
  </tbody>
</table>

<div class="container">
  <button class="btn btn-primary text-right" onclick=f_confirm_process('<?php echo $var_doc_no; ?>') <?php echo $btn_disabled; ?>>PROCESS</button>
</div>

<?php echo loading_body_full(); ?>

<script>

function f_confirm_process(id){
    /*$("#loading_text").text("Transfer Stocks, Please wait...");
    $('#loading_body').show();

    setTimeout(f_confirm_process2(id), 1000);

    f_change_status(id); // change status
*/

  $("#loading_text").text("Transfer Stocks, Please wait...");
  $('#loading_body').show();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/inbound/transferbin/transfer_stocks2",
        type : "post",
        dataType  : 'html',
        data : {id:id},
        success: function(data){
            response = $.parseJSON(data);
            if(response.status == 1){
              f_change_status(id);
            }
        }
    })

}
//---

function f_confirm_process2(id){
  var array_line_no = <?php echo json_encode($array_line_no); ?>;

  for(i=0;i<array_line_no.length;i++){
      result = f_process_transferbin(id,array_line_no[i]);

      if(result){
          percent = parseInt(i/array_line_no.length*100);
          $("#loading_text").text("Transfer Stocks, "+percent+"%");
          $("#check_"+array_line_no[i]).show();
      }
  }
}
//--

function f_process_transferbin(id, line_no){
  $("#loading_text").text("Transfer Stocks, Please wait...");
  $.ajax({
      url  : "<?php echo base_url();?>index.php/wms/inbound/transferbin/transfer_stocks",
      type : "post",
      dataType  : 'json',
      async : false,
      data : {id:id, line_no:line_no},
      success: function(data){
        result = $.parseJSON(data);
      }
  })
  return result;
}
//--

function f_change_status(doc_no){
  status = 3;
  $.ajax({
      url  : "<?php echo base_url();?>index.php/wms/inbound/transferbin/update_status3",
      type : "post",
      dataType  : 'json',
      data : {doc_no:doc_no,status:status},
      success: function(data){
          if(data.status == 1){
            swal({
               title: data.msg,
               type: "success", confirmButtonText: "OK",
            }).then(function(){
              setTimeout(function(){
                  f_refresh();
                  $('#myModalConfirm').modal("toggle");
              },100)
            });
          }
      }
  })
}
//---

</script>
