<?php
    if($status == 0){
        echo $message;
        return 0;
    }

?>

<div class="row">
  <div class="col-6">
    <table class="table table-bordered table-striped">
      <thead>
        <th>Doc No - Picking</th>
        <th>Item Code</th>
        <th>Qty</th>
        <th>UOM</th>
      </thead>
      <tbody>
        <?php
          if($var_picking_d == 0){
              echo "<tr><td colspan='4'>No Data</td></tr>";
          }
          else{
              foreach($var2_picking_d as $row){
                echo "<tr>";
                  echo "<td>".$row["doc_no"]."</td>";
                  echo "<td>".$row["item_code"]."</td>";
                  echo "<td>".$row["qty_to_picked"]."</td>";
                  echo "<td>".$row["uom"]."</td>";
                echo "</tr>";
              }
          }
        ?>
      </tbody>
    </table>
  </div>
  <div class="col-6">
    <table class="table table-bordered table-striped">
      <thead>
        <th>Doc No - Packing</th>
        <th>Item Code</th>
        <th>Qty</th>
        <th>UOM</th>
      </thead>
      <tbody>
        <?php
          if($var_packing_d == 0){
              echo "<tr><td colspan='4'>No Data</td></tr>";
          }
          else{
              foreach($var2_packing_d as $row){
                echo "<tr>";
                  echo "<td>".$row["doc_no"]."</td>";
                  echo "<td>".$row["item_code"]."</td>";
                  echo "<td>".$row["qty_to_packed"]."</td>";
                  echo "<td>".$row["uom"]."</td>";
                echo "</tr>";
              }
          }
        ?>
      </tbody>
    </table>
  </div>
</div>

<div class="row border-bottom" style="margin-top:10px;"></div>

<div class="container-fluid" style="margin-top:10px;">
  <button class="btn btn-danger" id="btn_cancel">PROCESS CANCEL</button>
</div>

<?php echo loading_body_full(); ?>

<script>
  $("#btn_cancel").click(function(){
      swal({
        input: 'textarea',
        inputPlaceholder: 'Type your message here',
        showCancelButton: true,
        confirmButtonText: 'OK'
      }).then(function (result) {
          if(result.dismiss == "cancel"){}
          else{
              if(result.value == ""){ show_error("You have to type message");}
              else{
                  var message = result.value;
                  swal({
                    title: "Are you sure ?",
                    html: "Proceed this Cancel",
                    type: "question",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false
                  }).then(function (result) {
                      if(result.value){
                          $("#loading_text").text("Canceling Document, Please wait...");
                          $('#loading_body').show();

                          var doc_no = '<?php echo $doc_no; ?>';

                          $.ajax({
                              url  : "<?php echo base_url();?>index.php/wms/outbound/canceldoc/process",
                              type : "post",
                              dataType  : 'html',
                              data : {doc_no:doc_no,message:message},
                              success: function(data){
                                var responsedata = $.parseJSON(data);

                                if(responsedata.status == 1){
                                      swal({
                                         title: responsedata.msg,
                                         type: "success", confirmButtonText: "OK",
                                      }).then(function(){
                                        setTimeout(function(){
                                          $('#loading_body').hide();
                                          location.reload();
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
          }
      })
  })


</script>
