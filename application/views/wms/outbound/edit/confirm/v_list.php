<style>
  tr{
    font-size:14px;
  }
</style>

<table class="table table-bordered table-striped table-sm">
  <thead>
    <th>Doc No</th>
    <th>Line</th>
    <th>Doc No Edited</th>
    <th>Line No Edited</th>
    <th>Item Code</th>
    <th>Desc</th>
    <th>Qty to Ship</th>
    <th>Qty Minus</th>
    <th>Qty Result</th>
  </thead>
  <tbody>
    <?php
      foreach($var_doc_d as $row){
          echo "<tr>";
            echo "<td>".$row["doc_no"]."</td>";
            echo "<td>".$row["line_no"]."</td>";
            echo "<td>".$row["doc_no_edited"]."</td>";
            echo "<td>".$row["line_no_edited"]."</td>";
            echo "<td>".$row["item_code"]."</td>";
            echo "<td>".$row["description"]."</td>";
            echo "<td>".$row["qty_to_ship"]."</td>";
            echo "<td>".$row["qty_minus"]."</td>";
            echo "<td>".$row["qty_result"]."</td>";
          echo "</tr>";
      }
    ?>
  </tbody>
</table>

<?php
  if($status == 2) echo "<div><button class='btn btn-warning' id='btn_send_process'>ENVIAR</button></div>";
  else if($status == 1) echo "<div><button class='btn btn-success' id='btn_edit_process'>EDIT</button></div>";
  else if($status == 0) echo "<div><button class='btn btn-danger' id='btn_cancel_process'>Cancel</button></div>";

?>


<script>
    $("#btn_edit_process").click(function(){
        swal({
          title: "Are you sure ?",
          html: "Proceed this Edited",
          type: "question",
          showCancelButton: true,
          confirmButtonText: "Yes",
          showLoaderOnConfirm: true,
          closeOnConfirm: false
        }).then(function (result) {
            if(result.value){
              $("#loading_text").text("Editing Document, Please wait...");
              $('#loading_body').show();

              var doc_no = '<?php echo $doc_no; ?>';

              $.ajax({
                  url  : "<?php echo base_url();?>index.php/wms/outbound/edit/confirm_process",
                  type : "post",
                  dataType  : 'html',
                  data : {doc_no: doc_no},
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
    })
    //---

    $("#btn_cancel_process").click(function(){
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
              $("#loading_text").text("Cancel Document, Please wait...");
              $('#loading_body').show();

              var doc_no = '<?php echo $doc_no; ?>';

              $.ajax({
                  url  : "<?php echo base_url();?>index.php/wms/outbound/edit/confirm_cancel",
                  type : "post",
                  dataType  : 'html',
                  data : {doc_no: doc_no},
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
    })
    //---

    $("#btn_send_process").click(function(){
        swal({
          title: "Are you sure ?",
          html: "Send Email",
          type: "question",
          showCancelButton: true,
          confirmButtonText: "Yes",
          showLoaderOnConfirm: true,
          closeOnConfirm: false
        }).then(function (result) {
            if(result.value){
              $("#loading_text").text("Sending Email, Please wait...");
              $('#loading_body').show();

              var doc_no = '<?php echo $doc_no; ?>';

              $.ajax({
                  url  : "<?php echo base_url();?>index.php/wms/outbound/edit/send_process",
                  type : "post",
                  dataType  : 'html',
                  data : {doc_no: doc_no},
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
    })
    //---

</script>
